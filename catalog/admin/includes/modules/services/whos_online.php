<?php
/**
  @package    admin::modules
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: whos_online.php v1.0 2013-08-08 datazen $
*/
class lC_Services_whos_online_Admin {
  var $title,
      $description,
      $uninstallable = true,
      $depends = array('session', 'core'),
      $precedes;

  public function lC_Services_whos_online_Admin() {
    global $lC_Language;

    $lC_Language->loadIniFile('modules/services/whos_online.php');

    $this->title = $lC_Language->get('services_whos_online_title');
    $this->description = $lC_Language->get('services_whos_online_description');
  }

  public function install() {
    global $lC_Database;

    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_public function, set_public function, date_added) VALUES ('Detect Search Engine Spider Robots', 'SERVICE_WHOS_ONLINE_SPIDER_DETECTION', '1', 'Detect search engine spider robots (GoogleBot, Yahoo, etc).', '6', '0', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))', now())");
  }

  public function remove() {
    global $lC_Database;

    $lC_Database->simpleQuery("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
  }

  public function keys() {
    return array('SERVICE_WHOS_ONLINE_SPIDER_DETECTION');
  }
}
?>