<?php
/*
  $Id: lc_cfg_set_countries_pulldown_menu.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

  function lc_cfg_set_countries_pulldown_menu($default, $key = null) {

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

    return lc_draw_pull_down_menu($name, $countries_array, $default, 'class="input with-small-padding"');
  }
?>
