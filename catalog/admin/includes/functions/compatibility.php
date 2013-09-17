<?php
/*
  $Id: compatibility.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

/*
 * file_get_contents() natively supported from PHP 4.3
 */

  if (!function_exists('file_get_contents')) {
    function file_get_contents($filename) {
      if ($handle = @fopen($filename, 'rb')) {
        $data = fread($handle, filesize($filename));
        fclose($fh);

        return $data;
      } else {
        return false;
      }
    }
  }

/*
 * posix_getpwuid() not implemented on Microsoft Windows platforms
 */

  if (!function_exists('posix_getpwuid')) {
    function posix_getpwuid($id) {
      return '-?-';
    }
  }

/*
 * posix_getgrgid() not implemented on Microsoft Windows platforms
 */

  if (!function_exists('posix_getgrgid')) {
    function posix_getgrgid($id) {
      return '-?-';
    }
  }

/*
 * http_build_query() natively supported from PHP 5.0
 * From Pear::PHP_Compat
 */

  if ( !function_exists('http_build_query') ) {
    function http_build_query($formdata, $numeric_prefix = null, $arg_separator = null) {
// If $formdata is an object, convert it to an array
      if ( is_object($formdata) ) {
        $formdata = get_object_vars($formdata);
      }

// Check we have an array to work with
      if ( !is_array($formdata) || !empty($formdata) ) {
        return false;
      }

// Argument seperator
      if ( empty($arg_separator) ) {
        $arg_separator = ini_get('arg_separator.output');

        if ( empty($arg_separator) ) {
          $separator = '&';
        }
      }

// Start building the query
      $tmp = array();

      foreach ( $formdata as $key => $val ) {
        if ( is_null($val) ) {
          continue;
        }

        if ( is_integer($key) && ( $numeric_prefix != null ) ) {
          $key = $numeric_prefix . $key;
        }

        if ( is_scalar($val) ) {
          array_push($tmp, urlencode($key) . '=' . urlencode($val));
          continue;
        }

        // If the value is an array, recursively parse it
        if ( is_array($val) || is_object($val) ) {
          array_push($tmp, http_build_query_helper($val, urlencode($key), $arg_separator));
          continue;
        }

        // The value is a resource 
        return null;
      }

      return implode($separator, $tmp);
    }

    // Helper function
    function http_build_query_helper($array, $name, $arg_separator) {
      $tmp = array();

      foreach ( $array as $key => $value ) {
        if ( is_array($value) ) {
          array_push($tmp, http_build_query_helper($value, sprintf('%s[%s]', $name, $key), $arg_separator));
        } elseif ( is_scalar($value) ) {
          array_push($tmp, sprintf('%s[%s]=%s', $name, urlencode($key), urlencode($value)));
        } elseif ( is_object($value) ) {
          array_push($tmp, http_build_query_helper(get_object_vars($value), sprintf('%s[%s]', $name, $key), $arg_separator));
        }
      }

      return implode($arg_separator, $tmp);
    }
  }

/*
 * imagetypes() is only available when GD is configured with PHP
 */

  if ( !function_exists('imagetypes') ) {
    define('IMG_JPG', false);
    define('IMG_GIF', false);
    define('IMG_PNG', false);

    function imagetypes() {
      return false;
    }
  }
?>