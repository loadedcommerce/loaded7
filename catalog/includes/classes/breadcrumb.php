<?php
/*
  $Id: breadcrumb.php v1.0 2011-11-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

/**
 * The lC_Breadcrumb class handles the breadcrumb navigation path
 */

  class lC_Breadcrumb implements iterator {

/**
 * An array containing the breadcrumb navigation path
 *
 * @var array
 * @access private
 */

    private $_path = array();

/**
 * The string to separate the breadcrumb entries with
 *
 * @var string
 * @access private
 */

    private $_separator = ' &raquo; ';

/**
 * Resets the breadcrumb navigation path
 *
 * @access public
 */

    public function reset() {
      $this->_path = array();
    }

/**
 * Adds an entry to the breadcrumb navigation path
 *
 * @param string $title The title of the breadcrumb navigation entry
 * @param string $link The link of the breadcrumb navigation entry
 * @access public
 */

    public function add($title, $link = null) {
      if ( !empty($link) ) {
        $title = lc_link_object($link, $title);
      }

      $this->_path[] = $title;
    }

/**
 * Returns the breadcrumb navigation path with the entries separated by $separator
 *
 * @param string $separator The string value to separate the breadcrumb navigation path entries with
 * @access public
 * @return string
 */

    public function getPath($separator = null) {
      if ( is_null($separator) ) {
        $separator = $this->_separator;
      }

      return implode($separator, $this->_path);
    }

/**
 * Returns the breadcrumb navigation path in a list format with the entries separated by $separator
 *
 * @param string $separator The string value to separate the breadcrumb navigation path entries with
 * @access public
 * @return string
 */

    public function getPathList() {

      $path_list = '';
      foreach($this->_path as $path) {
        $path_list .= '<li>' . $path . '</li>';  
      }

      return $path_list;
    }

/**
 * Returns the breadcrumb navigation path array
 *
 * @access public
 * @return array
 */

    public function getArray() {
      return $this->_path;
    }

/**
 * Returns the breadcrumb separator
 *
 * @access public
 * @return string
 */

    public function getSeparator() {
      return $this->_separator;
    }

/**
 * Sets the breadcrumb string separator
 *
 * @param string $separator The string to separator breadcrumb entries with
 * @access public
 * @return string
 */

    public function setSeparator($separator) {
      $this->_separator = $separator;
    }

/**
 * Overloaded rewind iterator function
 *
 * @access public
 */

    public function rewind() {
      return reset($this->_path);
    }

/**
 * Overloaded current iterator function
 *
 * @access public
 */

    public function current() {
      return current($this->_path);
    }

/**
 * Overloaded key iterator function
 *
 * @access public
 */

    public function key() {
      return key($this->_path);
    }

/**
 * Overloaded next iterator function
 *
 * @access public
 */

    public function next() {
      return next($this->_path);
    }

/**
 * Overloaded valid iterator function
 *
 * @access public
 */

    public function valid() {
      return ( current($this->_path) !== false );
    }
  }
?>