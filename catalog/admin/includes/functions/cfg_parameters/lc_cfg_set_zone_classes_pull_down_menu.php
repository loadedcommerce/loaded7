<?php
/**
  @package    admin::functions
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: lc_cfg_set_zone_classes_pull_down_menu.php v1.0 2013-08-08 datazen $
*/
function lc_cfg_set_zone_classes_pull_down_menu($default, $key = null) {
  global $lC_Database, $lC_Language;

  $css_class = 'class="input with-small-padding"';

  $name = (empty($key)) ? 'configuration_value' : 'configuration[' . $key . ']';

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