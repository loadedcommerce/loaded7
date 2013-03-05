<?php
/**
  $Id: sefu.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
class lC_Services_sefu {
  function start() {
    if (isset($_SERVER['ORIG_PATH_INFO'])) {
      if (isset($_SERVER['PATH_INFO']) && empty($_SERVER['PATH_INFO'])) {
        $_SERVER['PATH_INFO'] = $_SERVER['ORIG_PATH_INFO'];
      }
    }

    if (isset($_SERVER['PATH_INFO']) && (strlen($_SERVER['PATH_INFO']) > 1)) {
      $parameters = explode('/', substr($_SERVER['PATH_INFO'], 1));

      $_GET = array();
      $GET_array = array();

      foreach ($parameters as $parameter) {
        $param_array = explode(',', $parameter, 2);

        if (!isset($param_array[1])) {
          $param_array[1] = '';
        }

        if (strpos($param_array[0], '[]') !== false) {
          $GET_array[substr($param_array[0], 0, -2)][] = $param_array[1];
        } else {
          $_GET[$param_array[0]] = $param_array[1];
        }

        $i++;
      }

      if (sizeof($GET_array) > 0) {
        foreach ($GET_array as $key => $value) {
          $_GET[$key] = $value;
        }
      }
    }

    return true;
  }

  function stop() {
    return true;
  }
}
?>