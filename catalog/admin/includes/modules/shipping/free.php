<?php
/*
  $Id: free.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

  class lC_Shipping_free extends lC_Shipping_Admin {
    var $icon;

    var $_title,
        $_code = 'free',
        $_author_name = 'LoadedCommerce',
        $_author_www = 'http://www.loadedcommerce.com',
        $_status = false,
        $_sort_order;

// class constructor
    function lC_Shipping_free() {
      global $lC_Language;

      $this->icon = '';

      $this->_title = $lC_Language->get('shipping_free_title');
      $this->_description = $lC_Language->get('shipping_free_description');
      $this->_status = (defined('MODULE_SHIPPING_FREE_STATUS') && (MODULE_SHIPPING_FREE_STATUS == 'True') ? true : false);
    }

// class methods
    function isInstalled() {
      return (bool)defined('MODULE_SHIPPING_FREE_STATUS');
    }

    function install() {
      global $lC_Database;

      parent::install();

      $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Free Shipping', 'MODULE_SHIPPING_FREE_STATUS', 'True', 'Do you want to offer flat rate shipping?', '6', '0', 'lc_cfg_set_boolean_value(array(\'True\', \'False\'))', now())");
      $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Shipping Cost', 'MODULE_SHIPPING_FREE_MINIMUM_ORDER', '20', 'The minimum order amount to apply free shipping to.', '6', '0', now())");
      $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Shipping Zone', 'MODULE_SHIPPING_FREE_ZONE', '0', 'If a zone is selected, only enable this shipping method for that zone.', '6', '0', 'lc_cfg_use_get_zone_class_title', 'lc_cfg_set_zone_classes_pull_down_menu(class=\"select\",', now())");
    }

    function getKeys() {
      if (!isset($this->_keys)) {
        $this->_keys = array('MODULE_SHIPPING_FREE_STATUS',
                             'MODULE_SHIPPING_FREE_MINIMUM_ORDER',
                             'MODULE_SHIPPING_FREE_ZONE');
      }

      return $this->_keys;
    }
  }
?>