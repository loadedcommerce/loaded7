<?php
/**
  @package    admin::functions
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: lc_cfg_set_zones_pulldown_menu.php v1.0 2013-08-08 datazen $
*/
function lc_cfg_set_zones_pulldown_menu($default, $key = null) {
  global $lC_Database;
  
  $css_class = 'class="input with-small-padding"';
  $args = func_get_args();
  if(count($args) > 2 &&  strpos($args[0], 'class') !== false ) {
    $css_class = $args[0];
    $default = $args[1];
    $key = $args[2];
  }

  if (isset($_GET['plugins'])) {
    $name = (!empty($key) ? 'plugins[' . $key . ']' : 'plugins_value');
  } else {
    $name = (!empty($key) ? 'configuration[' . $key . ']' : 'configuration_value');
  }

  $zones_array = array();
  
  $Qcountry = $lC_Database->query('select configuration_value from :table_configuration where configuration_key = :configuration_key');
  $Qcountry->bindTable(':table_configuration', TABLE_CONFIGURATION);
  $Qcountry->bindValue(':configuration_key', 'STORE_COUNTRY');
  $Qcountry->execute();
  
  foreach (lC_Address::getZones($Qcountry->value('configuration_value')) as $zone) {
    $zones_array[] = array('id' => $zone['id'],
                           'text' => $zone['name'],
                           'group' => $zone['country_name']);
  }

  return lc_draw_pull_down_menu($name, $zones_array, $default, $css_class);
}
?>