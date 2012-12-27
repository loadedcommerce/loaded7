<?php
/*
  $Id$

  LoadedCommerce, Open Source E-Commerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2007 LoadedCommerce

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
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
