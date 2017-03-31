<?php

/**
 * @package AwesomeDataLists
 */
namespace AwesomeDataLists;

/**
 * @package AwesomeDataLists
 */
class DataType {
  /**
   * @var integer
   */
  const CSV = 1;

  /**
   * @var integer
   */
  const XML = 2;

  /**
   * @var integer
   */
  const JSON = 3;

  /**
   * @var integer
   */
  const PHP_ARRAY = -1;

  /**
   * Options
   *
   * @var integer|array
   */
  protected $options;

  /**
   * Type
   *
   * @var integer
   */
  protected $type;

  /**
   * Constructor
   *
   * @param integer $type
   * @param integer|array $options
   */
  public function __construct($type = DataType::PHP_ARRAY, $options = 0) {
    $this->type = $type;

    switch ($this->type) {
      case DataType::PHP_ARRAY:
        $this->options = $options;
        break;

      case DataType::JSON:
        $this->options = $options;
        break;

      case DataType::XML:
        $this->options = array(
          'root'         => 'root',
          'item'         => 'item',
          'xml'          => null,
          'formatOutput' => false,
        );

        if (is_array($options)) {
          $this->options = $this->options + $options;
        }
        break;

      case DataType::CSV:
        $this->options = array(
          'escape'    => '\\',
          'header'    => true,
          'delimiter' => ',',
          'enclosure' => '"',
        );

        if (is_array($options)) {
          $this->options = $this->options + $options;
        }
        break;

      default:
        throw new DataTypeException('Data type not supported');
        break;
    }
  }

  /**
   * Decode
   *
   * @param array|string $input
   *
   * @return Data
   */
  public function decode($input) {
    switch ($this->type) {
      case DataType::CSV:
        $rows = str_getcsv($input, "\n", $this->options['enclosure'], $this->options['escape']);
        $array = array();

        foreach ($rows as $row) {
          $array[] = str_getcsv($row, $this->options['delimiter'], $this->options['enclosure'], $this->options['escape']);
        }

        if ($this->options['header']) {
          $header = $array[0];
          array_shift($array);

          if (count($header) != count($array[0])) {
            throw new DataException('Number of headers not match with number of data fields');
          }

          array_walk($array, function(&$a) use ($header) {
            $a = array_combine($header, $a);
          });
        }
        return new Data($array);
        break;

      case DataType::XML:
        libxml_use_internal_errors(true);
        $xml = simplexml_load_string($input);

        if ($xml === false) {
          $msg = "Failed loading XML\n";
          foreach (libxml_get_errors() as $error) {
            $msg .= "\t" . $error->message;
          }
          throw new DataException($msg);
        }

        return new Data(reset(json_decode(json_encode($xml), true)));
        break;

      case DataType::JSON:
        $array = json_decode($input, true, 512, $this->options);

        if (is_null($array)) {
          throw new DataException(json_last_error() . ' ' . json_​last_​error_​msg());
        }

        return new Data($array);
        break;

      default:
      case DataType::PHP_ARRAY:
        return new Data($input);
        break;
    }
  }

  /**
   * Encode data
   *
   * @param  Data  $data
   *
   * @return mixed
   */
  public function encode(Data $data) {
    if ($data->count() == 0) {
      return null;
    }

    switch ($this->type) {
      case DataType::CSV:
        // Turn on output buffering
        ob_start();
        $df = fopen('php://output', 'w');

        // Headers
        if ($data->columns() !== range(0, $data->columnCount() - 1)) {
          fputcsv($df, $data->columns(), $this->options['delimiter'], $this->options['enclosure'], $this->options['escape']);
        }

        while ($data->valid()) {
          fputcsv($df, $data->current(), $this->options['delimiter'], $this->options['enclosure'], $this->options['escape']);
          $data->next();
        }
        $data->rewind();

        fclose($df);

        return ob_get_clean();
        break;

      case DataType::XML:
        if (is_null($this->options['xml'])) {
          $xml = new \SimpleXMLElement("<{$this->options['root']} />");
        }

        $a2xml = function ($array, $node = null) use ($xml, &$a2xml) {
          if (is_null($node)) {
            $node = $xml->addChild($this->options['item']);
          }

          foreach ($array as $key => $value) {
            if ($key == '@attributes' && is_array($value)) {
              foreach ($value as $attr_key => $attr_value) {
                $attr_value = is_bool($attr_value) ? var_export($attr_value, true) : $attr_value;
                $node->addAttribute($attr_key, (string) $attr_value);
              }
            } else {
              if (is_array($value)) {
                $subnode = $node->addChild($key);
                $a2xml($value, $subnode);
              } else {
                $node->addChild($key, htmlspecialchars($value));
              }
            }
          }
        };

        while ($data->valid()) {
          $a2xml($data->current());
          $data->next();
        }
        $data->rewind();

        if ($this->options['formatOutput']) {
          $dom = new \DOMDocument('1.0');
          $dom->preserveWhiteSpace = false;
          $dom->formatOutput = true;
          $dom->loadXML($xml->asXML());
          return $dom->saveXML();
        }

        return $xml->asXML();
        break;

      case DataType::JSON:
        $array = new DataType(DataType::PHP_ARRAY);
        $json = json_encode($array->encode($data), $this->options);

        if ($json === false) {
          throw new DataException(json_last_error() . ' ' . json_​last_​error_​msg());
        }

        return $json;
        break;

      case DataType::PHP_ARRAY:
      default:
        $array = array();

        while ($data->valid()) {
          $array[$data->key()] = $data->current();
          $data->next();
        }
        $data->rewind();

        return $array;
        break;
    }
  }
}
