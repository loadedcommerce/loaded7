<?php
/*
  $Id: tax.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

  class lC_OrderTotal_tax extends lC_Modules_order_total_Admin {
    var $_title,
        $_code = 'tax',
        $_author_name = 'LoadedCommerce',
        $_author_www = 'http://www.loadedcommerce.com',
        $_status = false,
        $_sort_order;

    public function lC_OrderTotal_tax() {
      global $lC_Language;

      $this->_title = $lC_Language->get('order_total_tax_title');
      $this->_description = $lC_Language->get('order_total_tax_description');
      $this->_status = (defined('MODULE_ORDER_TOTAL_TAX_STATUS') && (MODULE_ORDER_TOTAL_TAX_STATUS == 'true') ? true : false);
      $this->_sort_order = (defined('MODULE_ORDER_TOTAL_TAX_SORT_ORDER') ? MODULE_ORDER_TOTAL_TAX_SORT_ORDER : null);
    }

    public function isInstalled() {
      return (bool)defined('MODULE_ORDER_TOTAL_TAX_STATUS');
    }

    public function install() {
      global $lC_Database;

      $lC_Database->simpleQuery("insert ignore into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display Tax', 'MODULE_ORDER_TOTAL_TAX_STATUS', 'true', 'Do you want to display the order tax value?', '6', '1', 'lc_cfg_set_boolean_value(array(\'true\', \'false\'))', now())");
      $lC_Database->simpleQuery("insert ignore into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_ORDER_TOTAL_TAX_SORT_ORDER', '500', 'Sort order of display.', '6', '2', now())");
      $lC_Database->simpleQuery("insert ignore into " . TABLE_TEMPLATES_BOXES . " (title, code, author_name, author_www, modules_group) values ('Tax', 'tax', 'LoadedComerce', 'http://www.loadedcommerce.com', 'order_total')");
    }

    public function remove() {
      global $lC_Database;

      $lC_Database->simpleQuery("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->getKeys()) . "')");
      $lC_Database->simpleQuery("delete from " . TABLE_TEMPLATES_BOXES . " where code = 'tax'");
    }

    public function getKeys() {
      if (!isset($this->_keys)) {
        $this->_keys = array('MODULE_ORDER_TOTAL_TAX_STATUS',
                             'MODULE_ORDER_TOTAL_TAX_SORT_ORDER');
      }

      return $this->_keys;
    }
  }
?>