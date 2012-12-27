<?php
/*
  $Id: recently_visited.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

  class lC_Services_recently_visited_Admin {
    var $title,
        $description,
        $uninstallable = true,
        $depends = array('session', 'category_path'),
        $precedes;

    function lC_Services_recently_visited_Admin() {
      global $lC_Language;

      $lC_Language->loadIniFile('modules/services/recently_visited.php');

      $this->title = $lC_Language->get('services_recently_visited_title');
      $this->description = $lC_Language->get('services_recently_visited_description');
    }

    function install() {
      global $lC_Database;

      $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('Display latest products', 'SERVICE_RECENTLY_VISITED_SHOW_PRODUCTS', '1', 'Display recently visited products.', '6', '0', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))', now())");
      $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('Display product images', 'SERVICE_RECENTLY_VISITED_SHOW_PRODUCT_IMAGES', '1', 'Display the product image.', '6', '0', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))', now())");
      $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('Display product prices', 'SERVICE_RECENTLY_VISITED_SHOW_PRODUCT_PRICES', '1', 'Display the products price.', '6', '0', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))', now())");
      $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Maximum products to show', 'SERVICE_RECENTLY_VISITED_MAX_PRODUCTS', '5', 'Maximum number of recently visited products to show', '6', '0', now())");
      $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('Display latest categories', 'SERVICE_RECENTLY_VISITED_SHOW_CATEGORIES', '1', 'Display recently visited categories.', '6', '0', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))', now())");
      $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Maximum categories to show', 'SERVICE_RECENTLY_VISITED_MAX_CATEGORIES', '3', 'Mazimum number of recently visited categories to show', '6', '0', now())");
      $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('Display latest searches', 'SERVICE_RECENTLY_VISITED_SHOW_SEARCHES', '1', 'Show recent searches.', '6', '0', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))', now())");
      $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Maximum searches to show', 'SERVICE_RECENTLY_VISITED_MAX_SEARCHES', '3', 'Mazimum number of recent searches to display', '6', '0', now())");
    }

    function remove() {
      global $lC_Database;

      $lC_Database->simpleQuery("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('SERVICE_RECENTLY_VISITED_SHOW_PRODUCTS',
                   'SERVICE_RECENTLY_VISITED_SHOW_PRODUCT_IMAGES',
                   'SERVICE_RECENTLY_VISITED_SHOW_PRODUCT_PRICES',
                   'SERVICE_RECENTLY_VISITED_MAX_PRODUCTS',
                   'SERVICE_RECENTLY_VISITED_SHOW_CATEGORIES',
                   'SERVICE_RECENTLY_VISITED_MAX_CATEGORIES',
                   'SERVICE_RECENTLY_VISITED_SHOW_SEARCHES',
                   'SERVICE_RECENTLY_VISITED_MAX_SEARCHES');
    }
  }
?>