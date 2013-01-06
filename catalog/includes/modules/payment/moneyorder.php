<?php
/*
  $Id: moneyorder.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
class lC_Payment_moneyorder extends lC_Payment {
  var $_title,
      $_code = 'moneyorder',
      $_author_name = 'Loaded Commerce',
      $_author_www = 'http://www.loadedcommerce.com',
      $_status = false,
      $_sort_order;

  function lC_Payment_moneyorder() {
    global $lC_Language;

    $this->_title = $lC_Language->get('payment_moneyorder_title');
    $this->_description = sprintf($lC_Language->get('payment_moneyorder_description'), (defined('MODULE_PAYMENT_MONEYORDER_PAYTO') ? MODULE_PAYMENT_MONEYORDER_PAYTO : '(not set)'), nl2br(STORE_NAME_ADDRESS));
    $this->_status = (defined('MODULE_PAYMENT_MONEYORDER_STATUS') && (MODULE_PAYMENT_MONEYORDER_STATUS == 'True') ? true : false);
    $this->_sort_order = (defined('MODULE_PAYMENT_MONEYORDER_SORT_ORDER') ? MODULE_PAYMENT_MONEYORDER_SORT_ORDER : null);

    if (defined('MODULE_PAYMENT_MONEYORDER_STATUS')) {
      $this->initialize();
    }
  }

  function initialize() {
    global $lC_Language, $order;

    if ((int)MODULE_PAYMENT_MONEYORDER_ORDER_STATUS_ID > 0) {
      $this->order_status = MODULE_PAYMENT_MONEYORDER_ORDER_STATUS_ID;
    }

    if (is_object($order)) $this->update_status();

    $this->email_footer = sprintf($lC_Language->get('payment_moneyorder_email_footer'), MODULE_PAYMENT_MONEYORDER_PAYTO, STORE_NAME_ADDRESS);
  }

  function update_status() {
    global $lC_Database, $order;

    if ( ($this->_status === true) && ((int)MODULE_PAYMENT_MONEYORDER_ZONE > 0) ) {
      $check_flag = false;

      $Qcheck = $lC_Database->query('select zone_id from :table_zones_to_geo_zones where geo_zone_id = :geo_zone_id and zone_country_id = :zone_country_id order by zone_id');
      $Qcheck->bindTable(':table_zones_to_geo_zones', TABLE_ZONES_TO_GEO_ZONES);
      $Qcheck->bindInt(':geo_zone_id', MODULE_PAYMENT_MONEYORDER_ZONE);
      $Qcheck->bindInt(':zone_country_id', $order->billing['country']['id']);
      $Qcheck->execute();

      while ($Qcheck->next()) {
        if ($Qcheck->valueInt('zone_id') < 1) {
          $check_flag = true;
          break;
        } elseif ($Qcheck->valueInt('zone_id') == $order->billing['zone_id']) {
          $check_flag = true;
          break;
        }
      }

      if ($check_flag == false) {
        $this->_status = false;
      }
    }
  }

  function selection() {
    return array('id' => $this->_code,
                 'module' => $this->_title);
  }

  function pre_confirmation_check() {
    return false;
  }

  function confirmation() {
    return array('title' => $this->_title);
  }

  function process_button() {
    return false;
  }

  function before_process() {
    return false;
  }

  function after_process() {
    return false;
  }

  function get_error() {
    return false;
  }

  function check() {
    if (!isset($this->_check)) {
      $this->_check = defined('MODULE_PAYMENT_MONEYORDER_STATUS');
    }

    return $this->_check;
  }

  function install() {
    global $lC_Database;

    parent::install();

    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Check or Money Order Module', 'MODULE_PAYMENT_MONEYORDER_STATUS', 'True', 'Do you want to accept Check or Money Order payments?', '6', '1', 'lc_cfg_set_boolean_value(array(\'True\', \'False\'))', now());");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Make Payable to:', 'MODULE_PAYMENT_MONEYORDER_PAYTO', '', 'Who should payments be made payable to?', '6', '1', now());");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort order of display.', 'MODULE_PAYMENT_MONEYORDER_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Payment Zone', 'MODULE_PAYMENT_MONEYORDER_ZONE', '0', 'If a zone is selected, only enable this payment method for that zone.', '6', '2', 'lc_cfg_use_get_zone_class_title', 'lc_cfg_set_zone_classes_pull_down_menu', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Set Order Status', 'MODULE_PAYMENT_MONEYORDER_ORDER_STATUS_ID', '0', 'Set the status of orders made with this payment module to this value', '6', '0', 'lc_cfg_set_order_statuses_pull_down_menu', 'lc_cfg_use_get_order_status_title', now())");
  }

  function getKeys() {
    if (!isset($this->_keys)) {
      $this->_keys = array('MODULE_PAYMENT_MONEYORDER_STATUS',
                           'MODULE_PAYMENT_MONEYORDER_ZONE',
                           'MODULE_PAYMENT_MONEYORDER_ORDER_STATUS_ID',
                           'MODULE_PAYMENT_MONEYORDER_SORT_ORDER',
                           'MODULE_PAYMENT_MONEYORDER_PAYTO');
    }

    return $this->_keys;
  }
}
?>