<?php
/*
  $Id: seo.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

  class lC_Services_seo_Admin {
    var $title,
        $description,
        $uninstallable = true,
        $depends,
        $precedes = 'session';

    function lC_Services_seo_Admin() {
      global $lC_Language;

      $lC_Language->loadIniFile('modules/services/seo.php');

      $this->title = $lC_Language->get('services_seo_title');
      $this->description = $lC_Language->get('services_seo_description');
    }

    function install() {
      global $lC_Database;

      $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('Add Category Parent Permalinks?', 'SERVICE_SEO_URL_ADD_CATEGORY_PARENT', '-1', 'Add each parent permalink to the url structure as you drill down into categories and products?', '6', '0', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))', now())");
    }

    function remove() {
      global $lC_Database;

      $lC_Database->simpleQuery("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('SERVICE_SEO_URL_ADD_CATEGORY_PARENT');
    }
  }
?>