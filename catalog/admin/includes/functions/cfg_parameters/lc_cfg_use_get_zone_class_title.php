<?php
/*
  $Id: lc_cfg_use_get_zone_class_title.php v1.0 2011-11-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
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
