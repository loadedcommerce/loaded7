<?php
/*
  $Id: controller.php v1.0 2013-04-20 gulsarrays $

  Loaded Commerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     Loaded Commerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
class PayPal_Recurring_Payments_Pro extends lC_Addon { // your addon must extend lC_Addon
  /*
  * Class constructor
  */
  public function PayPal_Recurring_Payments_Pro() {    
    global $lC_Language;    
   /**
    * The addon type (category)
    * valid types; payment, shipping, themes, checkout, catalog, admin, reports, connectors, other 
    */    
    $this->_type = 'paypal';
   /**
    * The addon class name
    */    
    $this->_code = 'PayPal_Recurring_Payments_Pro';        
   /**
    * The addon title used in the addons store listing
    */     
    $this->_title = $lC_Language->get('addon_paypal_recurring_payments_pro_title');
   /**
    * The addon description used in the addons store listing
    */     
    $this->_description = $lC_Language->get('addon_paypal_recurring_payments_pro_description');      
   /**
    * The developers name
    */    
    $this->_author = 'Loaded Commerce, LLC';
   /**
    * The developers web address
    */    
    $this->_authorWWW = 'http://www.loadedcommerce.com';    
   /**
    * The addon version
    */     
    $this->_version = '1.0.0';
   /**
    * The Loaded 7 core compatibility version
    */     
    $this->_compatibility = '7.002.2.0'; // the addon is compatible with this core version and later   
   /**
    * The base64 encoded addon image used in the addons store listing
    */     
    $this->_thumbnail = lc_image(DIR_WS_CATALOG . 'addons/' . $this->_code . '/images/paypal-recurring-billing.png', $this->_title);
   /**
    * The mobile capability of the addon
    */ 
    $this->_mobile_enabled = true;    
   /**
    * The addon enable/disable switch
    */    
    $this->_enabled = (defined('ADDONS_PAYMENT_' . strtoupper($this->_code) . '_STATUS') && @constant('ADDONS_PAYMENT_' . strtoupper($this->_code) . '_STATUS') == '1') ? true : false;      
  }
 /**
  * Checks to see if the addon has been installed
  *
  * @access public
  * @return boolean
  */
  public function isInstalled() {
    return (bool)defined('ADDONS_PAYMENT_' . strtoupper($this->_code) . '_STATUS');
  }
 /**
  * Install the addon
  *
  * @access public
  * @return void
  */
  public function install() {
    global $lC_Database;

    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Enable AddOn', 'ADDONS_PAYMENT_" . strtoupper($this->_code) . "_STATUS', '-1', 'Do you want to enable this addon?', '6', '0', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('API Username', 'ADDONS_PAYMENT_" . strtoupper($this->_code) . "_API_USERNAME', '', 'Enter your PayPal EC API Username.', '6', '0', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('API Password', 'ADDONS_PAYMENT_" . strtoupper($this->_code) . "_API_PASSWORD', '', 'Enter your PayPal EC API Password.', '6', '0', now())");    
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('API Signature', 'ADDONS_PAYMENT_" . strtoupper($this->_code) . "_API_SIGNATURE', '', 'Enter your PayPal EC API Signature.', '6', '0', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Transaction Type', 'ADDONS_PAYMENT_" . strtoupper($this->_code) . "_TRXTYPE', 'Sale', 'Set the transaction type; Authorization-Only or Sale (Authorize and Capture)', '6', '0', 'lc_cfg_set_boolean_value(array(\'Sale\', \'Authorization\'))', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Sandbox Mode', 'ADDONS_PAYMENT_" . strtoupper($this->_code) . "_TEST_MODE', '-1', 'Set to \'Yes\' for sandbox test environment or set to \'No\' for production environment.', '6', '0', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Merchant Country', 'ADDONS_PAYMENT_" . strtoupper($this->_code) . "_MERCHANT_COUNTRY', 'US', 'The country of merchant', '6', '17', 'lc_cfg_set_boolean_value(array(\'US\', \'UK\'))', now())");    
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Credit Card Payments', 'ADDONS_PAYMENT_" . strtoupper($this->_code) . "_DP_STATUS', '1', 'Do you want to accept payment by Credit Card?', '6', '0', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))', now())"); 
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Express Checkout', 'ADDONS_PAYMENT_" . strtoupper($this->_code) . "_EC_STATUS', 'On', 'Show the PayPal Express Checkout shortcut button on the shopping cart page?', '6', '0', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(\'On\', \'Off\'))', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Payment Zone', 'ADDONS_PAYMENT_" . strtoupper($this->_code) . "_ZONE', '0', 'If a zone is selected, only enable this payment method for that zone.', '6', '2', 'lc_cfg_use_get_zone_class_title', 'lc_cfg_set_zone_classes_pull_down_menu', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Set Pending Status', 'ADDONS_PAYMENT_" . strtoupper($this->_code) . "_ORDER_STATUS_ID', '1', 'For Pending orders, set the status of orders made with this payment module to this value.', '6', '0', 'lc_cfg_use_get_order_status_title', 'lc_cfg_set_order_statuses_pull_down_menu', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Set Complete Status', 'ADDONS_PAYMENT_" . strtoupper($this->_code) . "_ORDER_STATUS_COMPLETE_ID', '4', 'For Completed orders, set the status of orders made with this payment module to this value', '6', '0', 'lc_cfg_use_get_order_status_title', 'lc_cfg_set_order_statuses_pull_down_menu', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order of Display', 'ADDONS_PAYMENT_" . strtoupper($this->_code) . "_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0' , now())");

    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Cards Accepted', 'ADDONS_PAYMENT_" . strtoupper($this->_code) . "_ACCEPTED_TYPES', '', 'Accept these credit card types for this payment method.', '6', '0', 'lc_cfg_set_credit_cards_checkbox_field', now())");


    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Billing period', 'ADDONS_PAYMENT_" . strtoupper($this->_code) . "_BILLING_PERIOD', 'Month', 'The unit of measure for the billing cycle.', '6', '0', 'lc_cfg_set_billing_period_pull_down_menu', 'lc_cfg_set_billing_period_pull_down_menu', now())");

    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Billing Frequency', 'ADDONS_PAYMENT_" . strtoupper($this->_code) . "_BILLING_FREQUENCY', '', 'The number of billing periods that make up one billing cycle. If the billing period is Week and the billing frequency is 6, PayPal schedules the payments every 6 weeks.', '6', '0', now())");    


    //$lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Billing Frequency', 'ADDONS_PAYMENT_" . strtoupper($this->_code) . "_BILLING_FREQUENCY', '', 'The number of billing periods that make up one billing cycle. If the billing period is Week and the billing frequency is 6, PayPal schedules the payments every 6 weeks.The combination of billing frequency and billing period must be less than or equal to one year. For example, if the billing cycle is Month, the maximum value for billing frequency is 12. Similarly, if the billing cycle is Week, the maximum value for billing frequency is 52. If the billing period is SemiMonth, the billing frequency must be 1.', '6', '0', now())");

    $lC_Database->simpleQuery("ALTER TABLE " . TABLE_ORDERS . " CHANGE payment_method payment_method VARCHAR( 512 ) NOT NULL");
  }
 /**
  * Return the configuration parameter keys an an array
  *
  * @access public
  * @return array
  */
  public function getKeys() {
    if (!isset($this->_keys)) {
      $this->_keys = array('ADDONS_PAYMENT_' . strtoupper($this->_code) . '_STATUS',
                           'ADDONS_PAYMENT_' . strtoupper($this->_code) . '_DP_STATUS',
                           'ADDONS_PAYMENT_' . strtoupper($this->_code) . '_EC_STATUS',
                           'ADDONS_PAYMENT_' . strtoupper($this->_code) . '_API_USERNAME',
                           'ADDONS_PAYMENT_' . strtoupper($this->_code) . '_API_PASSWORD',
                           'ADDONS_PAYMENT_' . strtoupper($this->_code) . '_API_SIGNATURE',
                           'ADDONS_PAYMENT_' . strtoupper($this->_code) . '_TRXTYPE',
                           'ADDONS_PAYMENT_' . strtoupper($this->_code) . '_BILLING_PERIOD',
                           'ADDONS_PAYMENT_' . strtoupper($this->_code) . '_BILLING_FREQUENCY',
                           'ADDONS_PAYMENT_' . strtoupper($this->_code) . '_TEST_MODE',
                           'ADDONS_PAYMENT_' . strtoupper($this->_code) . '_MERCHANT_COUNTRY',
                           'ADDONS_PAYMENT_' . strtoupper($this->_code) . '_ACCEPTED_TYPES',
                           'ADDONS_PAYMENT_' . strtoupper($this->_code) . '_ZONE',
                           'ADDONS_PAYMENT_' . strtoupper($this->_code) . '_ORDER_STATUS_ID',
                           'ADDONS_PAYMENT_' . strtoupper($this->_code) . '_ORDER_STATUS_COMPLETE_ID',
                           'ADDONS_PAYMENT_' . strtoupper($this->_code) . '_SORT_ORDER');      
    }

    return $this->_keys;
  } 
	
}
function lc_cfg_set_billing_period_pull_down_menu($default, $key = null) {
  global $lC_Database, $lC_Language;
  
  $css_class = 'class="input with-small-padding"';

  $name = (empty($key)) ? 'configuration_value' : 'configuration[' . $key . ']';

  $billing_period_array = array(
    array('id' => 'Day','text' => 'Day'),
    array('id' => 'Week','text' => 'Week'),
    array('id' => 'SemiMonth','text' => 'SemiMonth'),
    array('id' => 'Month','text' => 'Month'),
    array('id' => 'Year','text' => 'Year')
    );
  return lc_draw_pull_down_menu($name, $billing_period_array, $default, $css_class);
}
?>