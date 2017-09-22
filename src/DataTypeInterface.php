<?php

/**
 * @package AwesomeDataLists
 */
namespace Cheich\AwesomeDataLists;

/**
 * @package AwesomeDataLists
 */
interface DataTypeInterface {
  /**
   * Decode data
   *
   * @param array|string $input
   *
   * @return Data
   */
  public function decode($input);

  /**
   * Encode data
   *
   * @param  Data  $data
   *
   * @return mixed
   */
  public function encode(Data $data);

  /**
   * Get mime type
   *
   * @return string
   */
  public function getMimeType();
}
