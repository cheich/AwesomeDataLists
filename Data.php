<?php

/**
 * @package AwesomeDataLists
 */
namespace AwesomeDataLists;

/**
 * @package AwesomeDataLists
 */
class Data implements \Countable, \Iterator, \Serializable, \ArrayAccess {
  /**
   * @var integer
   */
  protected $index = 0;

  /**
   * @var array
   */
  protected $data = array();

  /**
   * Data without changes
   *
   * @var array
   */
  protected $_data = array();

  /**
   * Constructor
   *
   * @param array $data Array of associative arrays
   */
  public function __construct($data) {
    $this->_data = array_values($data);
    $this->data = array_values($data);
  }

  /**
   * Sort
   *
   * @param   callback $callback Return -1|0|1
   *                             Parameters: $row1, $row2
   *
   * @example $data->sort(function($row1, $row2) {
   *    return strcmp($row1['lastname'], $row2['lastname']);
   *  });
   *
   * @example $data->sort(function($row1, $row2) {
   *    if ($row1['price'] == $row2['price'])
   *      return 0;
   *    return ($row1['price'] < $row2['price']) ? 1 : 0;
   *  });
   */
  public function sort($callback) {
    usort($this->data, $callback);
  }

  /**
   * Filter
   *
   * @param   callback $callback Return false to delete data, true to keep
   *                             Parameters: $row
   *
   * @example $albums->filter(function($data) {
   *    if ($data['year'] < 2000)
   *      return false;
   *    return true;
   *  });
   */
  public function filter($callback) {
    while ($this->valid()) {
      if (!call_user_func($callback, $this->current())) {
        $this->offsetUnset($this->key());
      }
      $this->next();
    }

    $this->rewind();
  }

  /**
   * Walk
   *
   * @param   callback $callback Return modified data in callback
   *                             Parameters: $row
   *
   * @example $albums->walk(function($data) {
   *    $data['ARTIST'] = strtoupper($data['ARTIST']);
   *    return $data;
   *  });
   */
  public function walk($callback) {
    while ($this->valid()) {
      $this->offsetSet($this->key(), call_user_func($callback, $this->current()));
      $this->next();
    }

    $this->rewind();
  }

  /**
   * Serialize data
   *
   * @return string
   */
  public function serialize() {
    return serialize($this->data);
  }

  /**
   * Unserialize data
   *
   * @param string $data Serialized data
   */
  public function unserialize($data) {
    $this->data = unserialize($data);
  }

  /**
   * Set value by index or append if offset is null
   *
   * @param integer $offset
   * @param mixed   $value
   */
  public function offsetSet($offset, $value) {
    if (is_null($offset)) {
      $this->data[] = $value;
    } else {
      $this->data[$offset] = $value;
    }
  }

  /**
   * Check if offset exists
   *
   * @param integer $offset
   *
   * @return boolean
   */
  public function offsetExists($offset) {
    return isset($this->data[$offset]);
  }

  /**
   * Remove an item by index
   *
   * @param integer $offset
   */
  public function offsetUnset($offset) {
    unset($this->data[$offset]);
    $this->data = array_values($this->data);
  }

  /**
   * Get value by index
   *
   * @param integer $offset
   *
   * @return mixed
   */
  public function offsetGet($offset) {
    return isset($this->data[$offset]) ? $this->data[$offset] : null;
  }

  /**
   * Get current key
   *
   * @return integer
   */
  public function key() {
    return $this->index;
  }

  /**
   * Move cursor forward
   */
  public function next() {
    $this->index++;
  }

  /**
   * Current index validation
   *
   * @return boolean
   */
  public function valid() {
    return isset($this->data[$this->index]);
  }

  /**
   * Return current value
   *
   * @return mixed
   */
	public function current() {
		return $this->data[$this->index];
	}

  /**
   * Reset cursor
   *
   * @return void
   */
  public function rewind() {
    $this->index = 0;
  }

  /**
   * Count rows
   *
   * @return integer
   */
  public function count() {
    return count($this->data);
  }

  /**
   * Get column names
   *
   * @return array
   */
  public function columns() {
    return array_keys($this->current());
  }

  /**
   * Count columns
   *
   * @return array
   */
  public function columnCount() {
    return count($this->columns());
  }

  /**
   * Reset all changes
   */
  public function reset() {
    $this->data = $this->_data;
  }
}
