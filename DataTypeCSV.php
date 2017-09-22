<?php

/**
 * @package AwesomeDataLists
 */
namespace Cheich\AwesomeDataLists;

/**
 * @package AwesomeDataLists
 */
class DataTypeCSV implements DataTypeInterface {
  /**
   * Options
   *
   * @var array
   */
  protected $options = array(
    'escape'    => '\\',
    'header'    => true,
    'delimiter' => ',',
    'enclosure' => '"',
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
  }

  /**
   * Get mime type
   *
   * @return string
   */
  public function getMimeType() {
    return 'text/csv';
  }
}
