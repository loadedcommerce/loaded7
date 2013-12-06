<?php
/**
  @package    admin::functions
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: lc_cfg_use_get_zone_class_title.php v1.0 2013-08-08 datazen $
*/
function lc_cfg_use_get_zone_class_title($id) {
  global $lC_Database, $lC_Language;

  if ($id == '0') {
    return $lC_Language->get('parameter_none');
  }

  $Qclass = $lC_Database->query('select geo_zone_name from :table_geo_zones where geo_zone_id = :geo_zone_id');
  $Qclass->bindTable(':table_geo_zones', TABLE_GEO_ZONES);
  $Qclass->bindInt(':geo_zone_id', $id);
  $Qclass->execute();

  return $Qclass->value('geo_zone_name');
}
?>