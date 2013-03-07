<?php
/**  
*  $Id: paypal_adv.php v1.0 2013-01-01 datazen $
*
*  LoadedCommerce, Innovative eCommerce Solutions
*  http://www.loadedcommerce.com
*
*  Copyright (c) 2013 Loaded Commerce, LLC
*
*  @author     Loaded Commerce Team
*  @copyright  (c) 2013 Loaded Commerce Team
*  @license    http://loadedcommerce.com/license.html
* 
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
  * Constructor
  */
  public function lC_Payment_paypal_adv() {
    global $lC_Language;

    $this->_title = $lC_Language->get('payment_paypal_adv_title');
    $this->_description = $lC_Language->get('payment_paypal_adv_description');
    $this->_method_title = $lC_Language->get('payment_paypal_adv_method_title');
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
    global $lC_Database, $lC_Language;

    parent::install();
    
    $Qcheck = $lC_Database->query("select orders_status_id from :table_orders_status where orders_status_name = '" . $lC_Language->get('payment_paypal_adv_preparing_status') . "' limit 1");
    $Qcheck->bindTable(':table_orders_status', TABLE_ORDERS_STATUS);
    $Qcheck->execute();
    
    if ($Qcheck->numberOfRows() > 0) {
      $Qstatus = $lC_Database->query("select max(orders_status_id) as status_id from :table_orders_status");
      $Qstatus->bindTable(':table_orders_status', TABLE_ORDERS_STATUS);
      $Qstatus->execute();
    
      $lC_Database->simpleQuery("insert into " . TABLE_ORDERS_STATUS . " (orders_status_id, language_id, orders_status_name) values ('" . $Qstatus->valueInt('status_id')+1 . "', '" . $lC_Language->getID() . "', '" . $lC_Language->get('payment_paypal_adv_preparing_status') . "')"); 
      
      $Qstatus->freeResult();
    }      

    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Enable CRE Secure Payment Module', 'MODULE_PAYMENT_PAYPAL_ADV_STATUS', '-1', 'Do you want to accept payments through the CRE Secure Payment System?', '6', '0', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('CRE Secure Account ID', 'MODULE_PAYMENT_PAYPAL_ADV_LOGIN', '', 'The Account ID used for the CRE Secure payment service.', '6', '0', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Enable Sandbox Mode', 'MODULE_PAYMENT_PAYPAL_ADV_TEST_MODE', '-1', 'Set to \'Yes\' for sandbox test environment or set to \'No\' for production environment.', '6', '0', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))', now())");
    //$lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Show Incomplete Orders', 'MODULE_PAYMENT_PAYPAL_ADV_SHOW_INCOMPLETE', '-1', 'Set to \'Yes\' to show incomplete orders on shopping cart page and customer order history page.', '6', '0', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))', now())");           
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Credit Cards', 'MODULE_PAYMENT_PAYPAL_ADV_ACCEPTED_TYPES', '', 'Accept these credit card types for this payment method.', '6', '0', 'lc_cfg_set_credit_cards_checkbox_field', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Payment Zone', 'MODULE_PAYMENT_PAYPAL_ADV_ZONE', '0', 'If a zone is selected, only enable this payment method for that zone.', '6', '2', 'lc_cfg_use_get_zone_class_title', 'lc_cfg_set_zone_classes_pull_down_menu', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Set Pending Order Status', 'MODULE_PAYMENT_PAYPAL_ADV_ORDER_STATUS_ID', '1', 'For Pending orders, set the status of orders made with this payment module to this value. Default is \'Preparing [CRE Secure]\'', '6', '0', 'lc_cfg_set_order_statuses_pull_down_menu', 'lc_cfg_use_get_order_status_title', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Set Complete Order Status', 'MODULE_PAYMENT_PAYPAL_ADV_ORDER_STATUS_COMPLETE_ID', '4', 'For Completed orders, set the status of orders made with this payment module to this value', '6', '0', 'lc_cfg_set_order_statuses_pull_down_menu', 'lc_cfg_use_get_order_status_title', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort order of display.', 'MODULE_PAYMENT_PAYPAL_ADV_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0' , now())");
    
    $Qcheck->freeResult();
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
                           'MODULE_PAYMENT_PAYPAL_ADV_LOGIN',
                           'MODULE_PAYMENT_PAYPAL_ADV_TEST_MODE',
                         //   'MODULE_PAYMENT_PAYPAL_ADV_SHOW_INCOMPLETE',
                           'MODULE_PAYMENT_PAYPAL_ADV_ACCEPTED_TYPES',
                           'MODULE_PAYMENT_PAYPAL_ADV_ZONE',
                           'MODULE_PAYMENT_PAYPAL_ADV_ORDER_STATUS_ID',
                           'MODULE_PAYMENT_PAYPAL_ADV_ORDER_STATUS_COMPLETE_ID',
                           'MODULE_PAYMENT_PAYPAL_ADV_SORT_ORDER');
    }

    return $this->_keys;
  }
}
?>