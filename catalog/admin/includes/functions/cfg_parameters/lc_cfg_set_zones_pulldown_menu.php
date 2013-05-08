<?php
/*
  $Id: lc_cfg_set_zones_pulldown_menu.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

  function lc_cfg_set_zones_pulldown_menu($default, $key = null) {
    
    $css_class = 'class="input with-small-padding"';
    $args = func_get_args();
    if(count($args) > 2 &&  strpos($args[0], 'class') !== false ) {
      $css_class = $args[0];
      $default = $args[1];
      $key  = $args[2];
    }

    if (isset($_GET['plugins'])) {
      $name = (!empty($key) ? 'plugins[' . $key . ']' : 'plugins_value');
    } else {
      $name = (!empty($key) ? 'configuration[' . $key . ']' : 'configuration_value');
    }

    $zones_array = array();

    foreach (lC_Address::getZones() as $zone) {
      $zones_array[] = array('id' => $zone['id'],
                             'text' => $zone['name'],
                             'group' => $zone['country_name']);
    }

    return lc_draw_pull_down_menu($name, $zones_array, $default, $css_class);
  }
?>