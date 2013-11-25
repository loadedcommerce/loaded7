<?php
/**
  @package    admin::modules
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: specials.php v1.0 2013-08-08 datazen $
*/
class lC_Services_specials_Admin {
  var $title,
      $description,
      $uninstallable = true,
      $depends,
      $precedes;

  public function lC_Services_specials_Admin() {
    global $lC_Language;

    $lC_Language->loadIniFile('modules/services/specials.php');

    $this->title = $lC_Language->get('services_specials_title');
    $this->description = $lC_Language->get('services_specials_description');
  }

  public function install() {
    global $lC_Database;

    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Number Of Specials To Display', 'MAX_DISPLAY_SPECIAL_PRODUCTS', '9', 'Maximum number of products on special to display', '6', '0', now())");
  }

  public function remove() {
    global $lC_Database;

    $lC_Database->simpleQuery("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
  }

  public function keys() {
    return array('MAX_DISPLAY_SPECIAL_PRODUCTS');
  }
}
?>