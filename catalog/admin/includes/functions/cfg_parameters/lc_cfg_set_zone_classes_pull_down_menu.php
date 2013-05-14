<?php
/*
  $Id: lc_cfg_set_zone_classes_pull_down_menu.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

  function lc_cfg_set_zone_classes_pull_down_menu($default, $key = null) {
    global $lC_Database, $lC_Language;

    $css_class = 'class="input with-small-padding"';
    $args = func_get_args();
    if(count($args) > 2 &&  strpos($args[0], 'class') !== false ) {
      $css_class = $args[0];
      $default = $args[1];
      $key  = $args[2];
    }

    if (isset($_GET['plugins'])) {
      $name = (empty($key)) ? 'plugins_value' : 'plugins[' . $key . ']';
    } else {
      $name = (empty($key)) ? 'configuration_value' : 'configuration[' . $key . ']';
    }

    $zone_class_array = array(array('id' => '0',
                                    'text' => $lC_Language->get('parameter_none')));

    $Qzones = $lC_Database->query('select geo_zone_id, geo_zone_name from :table_geo_zones order by geo_zone_name');
    $Qzones->bindTable(':table_geo_zones', TABLE_GEO_ZONES);
    $Qzones->execute();

    while ($Qzones->next()) {
      $zone_class_array[] = array('id' => $Qzones->valueInt('geo_zone_id'),
                                  'text' => $Qzones->value('geo_zone_name'));
    }

    return lc_draw_pull_down_menu($name, $zone_class_array, $default, $css_class);
  }
?>