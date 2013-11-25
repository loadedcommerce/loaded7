<?php
/**
  @package    admin::functions
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: lc_cfg_set_countries_pulldown_menu.php v1.0 2013-08-08 datazen $
*/
function lc_cfg_set_countries_pulldown_menu($default, $key = null) {

  $css_class = 'class="input with-small-padding"';
  
  $args = func_get_args();
  if (count($args) > 2 && strpos($args[0], 'class') !== false) {
    $css_class = $args[0];
    $default = $args[1];
    $key = $args[2];
  }

  if (isset($_GET['plugins'])) {
    $name = (!empty($key) ? 'plugins[' . $key . ']' : 'plugins_value');
  } else {
    $name = (!empty($key) ? 'configuration[' . $key . ']' : 'configuration_value');
  }

  $countries_array = array();

  foreach (lC_Address::getCountries() as $country) {
    $countries_array[] = array('id' => $country['id'],
                               'text' => $country['name']);
  }

  return lc_draw_pull_down_menu($name, $countries_array, $default, $css_class);
}
?>