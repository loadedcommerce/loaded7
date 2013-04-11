<?php
/**  
  $Id: paypal_adv.php v1.0 2013-01-01 datazen $

  Loaded Commerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     Loaded Commerce Team
  @copyright  (c) 2013 Loaded Commerce Team
  @license    http://loadedcommerce.com/license.html
 
*/
class lC_Payment_paypal_adv extends lC_Payment_Admin {
 /**
  * The administrative title of the payment module
  *
  * @var string
  * @access public
  */
  public $_title;
 /**
  * The code of the payment module
  *
  * @var string
  * @access public
  */
  public $_code = 'paypal_adv';
 /**
  * The developers name
  *
  * @var string
  * @access protected
  */
  protected $_author_name = 'Loaded Commerce';
 /**
  * The developers address
  *
  * @var string
  * @access protected
  */
  protected $_author_www = 'http://www.loadedcommerce.com';
 /**
  * The status of the module
  *
  * @var boolean
  * @access protected
  */
  protected $_status = false;
 /**
  * The logo displayed on the top of the edit modal
  *
  * @var string
  * @access protected
  */
  protected $_logo;  
 /**
  * Constructor
  */
  public function lC_Payment_paypal_adv() {
    global $lC_Language;

    $this->_title = $lC_Language->get('payment_paypal_adv_title');
    $this->_method_title = $lC_Language->get('payment_paypal_adv_title');
    $this->_description = $lC_Language->get('payment_paypal_adv_description');
    $this->_status = (defined('MODULE_PAYMENT_PAYPAL_ADV_STATUS') && (MODULE_PAYMENT_PAYPAL_ADV_STATUS == '1') ? true : false);
    $this->_sort_order = (defined('MODULE_PAYMENT_PAYPAL_ADV_SORT_ORDER') ? MODULE_PAYMENT_PAYPAL_ADV_SORT_ORDER : '');
  }
 /**
  * Checks to see if the module has been installed
  *
  * @access public
  * @return boolean
  */
  public function isInstalled() {
    return (bool)defined('MODULE_PAYMENT_PAYPAL_ADV_STATUS');
  }
 /**
  * Install the module
  *
  * @access public
  * @return void
  */
  public function install() {
    global $lC_Database;

    parent::install();
    
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Enable Module', 'MODULE_PAYMENT_PAYPAL_ADV_STATUS', '-1', 'Enable this module?', '6', '0', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Express Checkout', 'MODULE_PAYMENT_PAYPAL_ADV_EC_STATUS', 'On', 'Show the PayPal Express Checkout shortcut button on the shopping cart page?', '6', '0', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(\'On\', \'Off\'))', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Partner', 'MODULE_PAYMENT_PAYPAL_ADV_PARTNER', 'PayPal', 'Enter your PayPal Partner ID.', '6', '0', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Merchant Login', 'MODULE_PAYMENT_PAYPAL_ADV_MERCH', '', 'Enter your PayPal Merchant Login.', '6', '0', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('User', 'MODULE_PAYMENT_PAYPAL_ADV_USER', '', 'Enter your PayPal User Name.', '6', '0', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Password', 'MODULE_PAYMENT_PAYPAL_ADV_PASSWORD', '', 'Enter your PayPal Password.', '6', '0', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Transaction Type', 'MODULE_PAYMENT_PAYPAL_ADV_TRXTYPE', 'Sale', 'Set the transaction type; Authorization-Only or Sale (Authorize and Capture)', '6', '0', 'lc_cfg_set_boolean_value(array(\'Sale\', \'Authorization\'))', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Template Layout', 'MODULE_PAYMENT_PAYPAL_ADV_TEMPLATE', 'A', 'Specifies which layout to use for the PayPal checkout page.', '6', '0', 'lc_cfg_set_boolean_value(array(\'A\', \'B\', \'C\'))', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Sandbox Mode', 'MODULE_PAYMENT_PAYPAL_ADV_TEST_MODE', '-1', 'Set to \'Yes\' for sandbox test environment or set to \'No\' for production environment.', '6', '0', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Payment Zone', 'MODULE_PAYMENT_PAYPAL_ADV_ZONE', '0', 'If a zone is selected, only enable this payment method for that zone.', '6', '2', 'lc_cfg_use_get_zone_class_title', 'lc_cfg_set_zone_classes_pull_down_menu', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Set Pending Status', 'MODULE_PAYMENT_PAYPAL_ADV_ORDER_STATUS_ID', '1', 'For Pending orders, set the status of orders made with this payment module to this value.', '6', '0', 'lc_cfg_set_order_statuses_pull_down_menu', 'lc_cfg_use_get_order_status_title', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Set Complete Status', 'MODULE_PAYMENT_PAYPAL_ADV_ORDER_STATUS_COMPLETE_ID', '4', 'For Completed orders, set the status of orders made with this payment module to this value', '6', '0', 'lc_cfg_set_order_statuses_pull_down_menu', 'lc_cfg_use_get_order_status_title', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order of Display', 'MODULE_PAYMENT_PAYPAL_ADV_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0' , now())");
    
    $lC_Database->simpleQuery("ALTER TABLE " . TABLE_ORDERS . " CHANGE payment_method payment_method VARCHAR( 512 ) NOT NULL");
  }
 /**
  * Return the configuration parameter keys in an array
  *
  * @access public
  * @return array
  */
  public function getKeys() {
    if (!isset($this->_keys)) {
      $this->_keys = array('MODULE_PAYMENT_PAYPAL_ADV_STATUS',
                           'MODULE_PAYMENT_PAYPAL_ADV_EC_STATUS',
                           'MODULE_PAYMENT_PAYPAL_ADV_PARTNER',
                           'MODULE_PAYMENT_PAYPAL_ADV_MERCH',
                           'MODULE_PAYMENT_PAYPAL_ADV_USER',
                           'MODULE_PAYMENT_PAYPAL_ADV_PASSWORD',
                           'MODULE_PAYMENT_PAYPAL_ADV_TEMPLATE',
                           'MODULE_PAYMENT_PAYPAL_ADV_TRXTYPE',
                           'MODULE_PAYMENT_PAYPAL_ADV_TEST_MODE',
                           'MODULE_PAYMENT_PAYPAL_ADV_ZONE',
                           'MODULE_PAYMENT_PAYPAL_ADV_ORDER_STATUS_ID',
                           'MODULE_PAYMENT_PAYPAL_ADV_ORDER_STATUS_COMPLETE_ID',
                           'MODULE_PAYMENT_PAYPAL_ADV_SORT_ORDER');
    }

    return $this->_keys;
  }
}
?>