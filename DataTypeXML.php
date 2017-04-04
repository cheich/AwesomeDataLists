<?php

/**
 * @package AwesomeDataLists
 */
namespace AwesomeDataLists;

/**
 * @package AwesomeDataLists
 */
class DataTypeXML implements DataTypeInterface {
  /**
   * Options
   *
   * @var array
   */
  protected $options = array(
    'root'         => 'root',
    'item'         => 'item',
    'xml'          => null,
    'formatOutput' => false,
  );
  /**
   * Constructor
   *
   * @param array $options
   */
  public function __construct($options = array()) {
    $this->options = $options + $this->options;
  }

  /**
   * Decode data
   *
   * @param string $input
   *
   * @return Data
   * @throws DataException
   */
  public function decode($input) {
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
  }

  /**
   * Encode data
   *
   * @param  Data  $data
   *
   * @return string
   */
  public function encode(Data $data) {
    if ($data->count() == 0) {
      return null;
    }

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
  }

  /**
   * Get mime type
   *
   * @return string
   */
  public function getMimeType() {
    return 'application/xml';
  }
}
