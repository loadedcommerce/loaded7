<?php
/*
  $Id: total.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

  class lC_OrderTotal_total extends lC_Modules_order_total_Admin {
    var $_title,
        $_code = 'total',
        $_author_name = 'LoadedCommerce',
        $_author_www = 'http://www.loadedcommerce.com',
        $_status = false,
        $_sort_order;

    public function lC_OrderTotal_total() {
      global $lC_Language;

      $this->_title = $lC_Language->get('order_total_total_title');
      $this->_description = $lC_Language->get('order_total_total_description');
      $this->_status = (defined('MODULE_ORDER_TOTAL_TOTAL_STATUS') && (MODULE_ORDER_TOTAL_TOTAL_STATUS == 'true') ? true : false);
      $this->_sort_order = (defined('MODULE_ORDER_TOTAL_TOTAL_SORT_ORDER') ? MODULE_ORDER_TOTAL_TOTAL_SORT_ORDER : null);
    }

    public function isInstalled() {
      return (bool)defined('MODULE_ORDER_TOTAL_TOTAL_STATUS');
    }

    public function install() {
      global $lC_Database;

      $lC_Database->simpleQuery("insert ignore into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display Total', 'MODULE_ORDER_TOTAL_TOTAL_STATUS', 'true', 'Do you want to display the total order value?', '6', '1', 'lc_cfg_set_boolean_value(array(\'true\', \'false\'))', now())");
      $lC_Database->simpleQuery("insert ignore into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_ORDER_TOTAL_TOTAL_SORT_ORDER', '6', 'Sort order of display.', '6', '2', now())");
      $lC_Database->simpleQuery("insert ignore into " . TABLE_TEMPLATES_BOXES . " (title, code, author_name, author_www, modules_group) values ('Total', 'total', 'LoadedComerce', 'http://www.loadedcommerce.com', 'order_total')");
    }

    public function remove() {
      global $lC_Database;

      $lC_Database->simpleQuery("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->getKeys()) . "')");
      $lC_Database->simpleQuery("delete from " . TABLE_TEMPLATES_BOXES . " where code = 'total'");
    }

    public function getKeys() {
      if (!isset($this->_keys)) {
        $this->_keys = array('MODULE_ORDER_TOTAL_TOTAL_STATUS',
                             'MODULE_ORDER_TOTAL_TOTAL_SORT_ORDER');
      }

      return $this->_keys;
    }
  }
?>