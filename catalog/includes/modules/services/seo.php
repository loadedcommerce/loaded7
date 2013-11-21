<?php
/**
  @package    catalog::modules::services
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: seo.php v1.0 2013-08-08 datazen $
*/
class lC_Services_seo {
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