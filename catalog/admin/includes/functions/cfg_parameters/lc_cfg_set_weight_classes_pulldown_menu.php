<?php
/**
  @package    admin::functions
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: lc_cfg_set_weight_classes_pulldown_menu.php v1.0 2013-08-08 datazen $
*/
function lc_cfg_set_weight_classes_pulldown_menu($default, $key = null) {
  global $lC_Database, $lC_Language;
  
  $css_class = 'class="input with-small-padding"';

  $name = (empty($key)) ? 'configuration_value' : 'configuration[' . $key . ']';

  $weight_class_array = array();

  foreach (lC_Weight::getClasses() as $class) {
    $weight_class_array[] = array('id' => $class['id'],
                                  'text' => $class['title']);
  }

  return lc_draw_pull_down_menu($name, $weight_class_array, $default, $css_class);
}
?>