<?php

/**
 * @package AwesomeDataLists
 */
namespace Cheich\AwesomeDataLists;

/**
 * @package AwesomeDataLists
 */
class DataTypeArray implements DataTypeInterface {
  /**
   * Decode data
   *
   * @param array $input
   *
   * @return Data
   */
  public function decode($input) {
    return new Data($input);
  }

  /**
   * Encode data
   *
   * @param  Data  $data
   *
   * @return array
   */
  public function encode(Data $data) {
    if ($data->count() == 0) {
      return null;
    }

    $array = array();

    while ($data->valid()) {
      $array[$data->key()] = $data->current();
      $data->next();
    }
    $data->rewind();

    return $array;
  }

  /**
   * Get mime type
   *
   * @return string
   */
  public function getMimeType() {
    return 'application/x-httpd-php';
  }
}
