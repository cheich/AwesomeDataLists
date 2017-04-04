<?php

/**
 * @package AwesomeDataLists
 */
namespace AwesomeDataLists;

/**
 * @package AwesomeDataLists
 */
class DataTypeJSON implements DataTypeInterface {
  /**
   * Options
   *
   * @var integer
   */
  protected $options;

  /**
   * Constructor
   *
   * @param integer $type
   * @param integer $options
   */
  public function __construct($options = 0) {
    $this->options = $options;
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
    $array = json_decode($input, true, 512, $this->options);

    if (is_null($array)) {
      throw new DataException(json_last_error() . ' ' . json_​last_​error_​msg());
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

    $array = new DataTypeArray();
    $json = json_encode($array->encode($data), $this->options);

    if ($json === false) {
      throw new DataException(json_last_error() . ' ' . json_​last_​error_​msg());
    }

    return $json;
  }

  /**
   * Get mime type
   *
   * @return string
   */
  public function getMimeType() {
    return 'application/json';
  }
}
