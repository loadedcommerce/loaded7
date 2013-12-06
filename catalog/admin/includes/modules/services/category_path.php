<?php
/**
  @package    admin::modules
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: category_path.php v1.0 2013-08-08 datazen $
*/
class lC_Services_category_path_Admin {
  var $title,
      $description,
      $uninstallable = false,
      $depends,
      $precedes;

  public function lC_Services_category_path_Admin() {
    global $lC_Language;

    $lC_Language->loadIniFile('modules/services/category_path.php');

    $this->title = $lC_Language->get('services_category_path_title');
    $this->description = $lC_Language->get('services_category_path_description');
  }

  public function install() {
    global $lC_Database;

    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_public function, set_public function, date_added) values ('Calculate Number Of Products In Each Category', 'SERVICES_CATEGORY_PATH_CALCULATE_PRODUCT_COUNT', '1', 'Recursively calculate how many products are in each category.', '6', '0', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))', now())");
  }

  public function remove() {
    global $lC_Database;

    $lC_Database->simpleQuery("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
  }

  public function keys() {
    return array('SERVICES_CATEGORY_PATH_CALCULATE_PRODUCT_COUNT');
  }
}
?>