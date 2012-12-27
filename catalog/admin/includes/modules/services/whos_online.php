<?php
/*
  $Id: whos_online.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

  class lC_Services_whos_online_Admin {
    var $title,
        $description,
        $uninstallable = true,
        $depends = array('session', 'core'),
        $precedes;

    function lC_Services_whos_online_Admin() {
      global $lC_Language;

      $lC_Language->loadIniFile('modules/services/whos_online.php');

      $this->title = $lC_Language->get('services_whos_online_title');
      $this->description = $lC_Language->get('services_whos_online_description');
    }

    function install() {
      global $lC_Database;

      $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('Detect Search Engine Spider Robots', 'SERVICE_WHOS_ONLINE_SPIDER_DETECTION', '1', 'Detect search engine spider robots (GoogleBot, Yahoo, etc).', '6', '0', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))', now())");
    }

    function remove() {
      global $lC_Database;

      $lC_Database->simpleQuery("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('SERVICE_WHOS_ONLINE_SPIDER_DETECTION');
    }
  }
?>