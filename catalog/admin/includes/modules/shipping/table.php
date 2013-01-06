<?php
/*
  $Id: table.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

  class lC_Shipping_table extends lC_Shipping_Admin {
    var $icon;

    var $_title,
        $_code = 'table',
        $_author_name = 'LoadedCommerce',
        $_author_www = 'http://www.loadedcommerce.com',
        $_status = false,
        $_sort_order;

// class constructor
    function lC_Shipping_table() {
      global $lC_Language;

      $this->icon = '';

      $this->_title = $lC_Language->get('shipping_table_title');
      $this->_description = $lC_Language->get('shipping_table_description');
      $this->_status = (defined('MODULE_SHIPPING_TABLE_STATUS') && (MODULE_SHIPPING_TABLE_STATUS == 'True') ? true : false);
      $this->_sort_order = (defined('MODULE_SHIPPING_TABLE_SORT_ORDER') ? MODULE_SHIPPING_TABLE_SORT_ORDER : null);
    }

// class methods
    function isInstalled() {
      return (bool)defined('MODULE_SHIPPING_TABLE_STATUS');
    }

    function install() {
      global $lC_Database;

      parent::install();

      $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Table Method', 'MODULE_SHIPPING_TABLE_STATUS', 'True', 'Do you want to offer table rate shipping?', '6', '0', 'lc_cfg_set_boolean_value(array(\'True\', \'False\'))', now())");
      $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Shipping Table', 'MODULE_SHIPPING_TABLE_COST', '25:8.50,50:5.50,10000:0.00', 'The shipping cost is based on the total cost or weight of items. Example: 25:8.50,50:5.50,etc.. Up to 25 charge 8.50, from there to 50 charge 5.50, etc', '6', '0', now())");
      $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Table Method', 'MODULE_SHIPPING_TABLE_MODE', 'weight', 'The shipping cost is based on the order total or the total weight of the items ordered.', '6', '0', 'lc_cfg_set_boolean_value(array(\'weight\', \'price\'))', now())");
      $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Handling Fee', 'MODULE_SHIPPING_TABLE_HANDLING', '0', 'Handling fee for this shipping method.', '6', '0', now())");
      $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Tax Class', 'MODULE_SHIPPING_TABLE_TAX_CLASS', '0', 'Use the following tax class on the shipping fee.', '6', '0', 'lc_cfg_use_get_tax_class_title', 'lc_cfg_set_tax_classes_pull_down_menu', now())");
      $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Shipping Zone', 'MODULE_SHIPPING_TABLE_ZONE', '0', 'If a zone is selected, only enable this shipping method for that zone.', '6', '0', 'lc_cfg_use_get_zone_class_title', 'lc_cfg_set_zone_classes_pull_down_menu', now())");
      $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_SHIPPING_TABLE_SORT_ORDER', '0', 'Sort order of display.', '6', '0', now())");
      $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Module weight Unit', 'MODULE_SHIPPING_TABLE_WEIGHT_UNIT', '2', 'What unit of weight does this shipping module use?.', '6', '0', 'lC_Weight::getTitle', 'lc_cfg_set_weight_classes_pulldown_menu', now())");
    }

    function getKeys() {
      if (!isset($this->_keys)) {
        $this->_keys = array('MODULE_SHIPPING_TABLE_STATUS',
                             'MODULE_SHIPPING_TABLE_COST',
                             'MODULE_SHIPPING_TABLE_MODE',
                             'MODULE_SHIPPING_TABLE_HANDLING',
                             'MODULE_SHIPPING_TABLE_TAX_CLASS',
                             'MODULE_SHIPPING_TABLE_ZONE',
                             'MODULE_SHIPPING_TABLE_SORT_ORDER',
                             'MODULE_SHIPPING_TABLE_WEIGHT_UNIT');
      }

      return $this->_keys;
    }
  }
?>