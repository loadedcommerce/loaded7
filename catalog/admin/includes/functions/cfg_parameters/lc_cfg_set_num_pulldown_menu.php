<?php
/**
  @package    admin::functions
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: lc_cfg_set_num_pulldown_menu.php v1.0 2013-08-08 datazen $
*/
function lc_cfg_set_num_pulldown_menu($default, $key = null) {
  global $lC_Database;
  
  $css_class = 'class="input with-small-padding"';
  
  $name = (empty($key)) ? 'configuration_value' : 'configuration[' . $key . ']';

  $i = 0;
  $array = array();
  for ($i = 1; $i <= 20; $i++) {

    $array[] = array('id' => $i,
                     'text' => $i);
  }

  return lc_draw_pull_down_menu($name, $array, $default, $css_class);
}
?>