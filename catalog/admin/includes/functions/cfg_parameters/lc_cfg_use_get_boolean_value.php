<?php
/**
  @package    admin::functions
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: lc_cfg_use_get_boolean_value.php v1.0 2013-08-08 datazen $
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