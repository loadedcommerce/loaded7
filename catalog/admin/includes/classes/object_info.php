<?php
/**
  @package    admin::classes
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: object_info.php v1.0 2013-08-08 datazen $
*/
/**
* The lC_ObjectInfo class wraps an object instance around an array data set
*/
class lC_ObjectInfo {
  /**
  * Holds the array data set values
  *
  * @var array
  * @access private
  */
  private $_data = array();
  /**
  * Constructor, loads the array data set into the object instance
  *
  * @param array $data The array data set to insert into the object instance
  * @access public
  */
  public function __construct($data) {
    $this->_data = $data;
  }
  /**
  * Get the value of a key element in the array data set
  *
  * @param string $key The name of the array key
  * @access public
  */
  public function get($key) {
    return $this->_data[$key];
  }
  /**
  * Get the value of a key element in the array data set and protect the output value
  *
  * @param string $key The name of the array key
  * @access public
  */
  public function getProtected($key) {
    return lc_output_string_protected($this->_data[$key]);
  }
  /**
  * Get the integer value of a key element in the array data set
  *
  * @param string $key The name of the array key
  * @access public
  */
  public function getInt($key) {
    return (int)$this->_data[$key];
  }
  /**
  * Get the whole array data set
  *
  * @access public
  */
  public function getAll() {
    return $this->_data;
  }
  /**
  * Set a value in the array data set
  *
  * @param string $key The name of the array key
  * @param string $value The value of the array key
  * @access public
  */
  public function set($key, $value) {
    $this->_data[$key] = $value;
  }
  /**
  * Checks the existance of a key in the array data set
  *
  * @param string $key The name of the array key
  * @access public
  */
  public function exists($key) {
    return isset($this->_data[$key]);
  }
}
?>