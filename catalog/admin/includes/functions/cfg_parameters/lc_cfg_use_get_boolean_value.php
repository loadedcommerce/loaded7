<?php
/*
  $Id: lc_cfg_use_get_boolean_value.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

  function lc_cfg_use_get_boolean_value($string) {
    global $lC_Language;

    switch ($string) {
      case -1:
      case '-1':
        return $lC_Language->get('parameter_false');
        break;

      case 0:
      case '0':
        return $lC_Language->get('parameter_optional');
        break;

      case 1:
      case '1':
        return $lC_Language->get('parameter_true');
        break;

      default:
        return $string;
        break;
    }
  }
?>