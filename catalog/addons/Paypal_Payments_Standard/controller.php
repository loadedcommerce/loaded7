<?php
/*
  $Id: controller.php v1.0 2013-04-20 datazen $

  Loaded Commerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     Loaded Commerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
class Paypal_Payments_Standard extends lC_Addon { // your addon must extend lC_Addon
  /*
  * Class constructor
  */
  public function Paypal_Payments_Standard() {    
    global $lC_Language;    
   /**
    * The addon type (category)
    * valid types; payment, shipping, themes, checkout, catalog, admin, reports, connectors, other 
    */    
    $this->_type = 'paypal';
   /**
    * The addon class name
    */    
    $this->_code = 'Paypal_Payments_Standard';        
   /**
    * The addon title used in the addons store listing
    */     
    $this->_title = $lC_Language->get('addon_payment_paypal_std_title');
   /**
    * The addon description used in the addons store listing
    */     
    $this->_description = $lC_Language->get('addon_payment_paypal_std_description');  
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
    $this->_version = '1.0.2';
   /**
    * The Loaded 7 core compatibility version
    */     
    $this->_compatibility = '7.002.0.0'; // the addon is compatible with this core version and later   
   /**
    * The base64 encoded addon image used in the addons store listing
    */     
    $this->_thumbnail = lc_image(DIR_WS_CATALOG . 'addons/' . $this->_code . '/images/paypal_std.png', $this->_title);
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
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('E-Mail Address', 'ADDONS_PAYMENT_" . strtoupper($this->_code) . "_ID', '', 'The e-mail address to use for the PayPal service', '6', '1', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Business ID', 'ADDONS_PAYMENT_" . strtoupper($this->_code) . "_BUSINESS_ID', '', 'Email address or account ID of the payment recipient', '6', '2', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Default Currency', 'ADDONS_PAYMENT_" . strtoupper($this->_code) . "_DEFAULT_CURRENCY', '1', 'The <b>default</b> currency to use for when the customer chooses to checkout via the store using a currency not supported by PayPal.', '6', '3', '', 'lc_cfg_set_currencies_pulldown_menu', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Payment Zone', 'ADDONS_PAYMENT_" . strtoupper($this->_code) . "_ZONE', '0', 'If a zone is selected, enable this payment method for that zone only.', '6', '5', 'lc_cfg_use_get_zone_class_title', 'lc_cfg_set_zone_classes_pull_down_menu', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Pending Status', 'ADDONS_PAYMENT_" . strtoupper($this->_code) . "_PROCESSING_STATUS_ID', '1', 'Set the Pending Notification status of orders made with this payment module', '6', '7', 'lc_cfg_use_get_order_status_title', 'lc_cfg_set_order_statuses_pull_down_menu', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Complete Status', 'ADDONS_PAYMENT_" . strtoupper($this->_code) . "_ORDER_DEFAULT_STATUS_ID', '1', 'Set the status of orders made with this payment module', '6', '8', 'lc_cfg_use_get_order_status_title', 'lc_cfg_set_order_statuses_pull_down_menu', now())");   
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Hold Status', 'ADDONS_PAYMENT_" . strtoupper($this->_code) . "_ORDER_ONHOLD_STATUS_ID', '1', 'Set the status of <b>On Hold</b> orders made with this payment module', '6', '9', 'lc_cfg_use_get_order_status_title', 'lc_cfg_set_order_statuses_pull_down_menu', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Canceled Status', 'ADDONS_PAYMENT_" . strtoupper($this->_code) . "_ORDER_CANCELED_STATUS_ID', '10', 'Set the status of <b>Canceled</b> orders made with this payment module', '6', '9', 'lc_cfg_use_get_order_status_title', 'lc_cfg_set_order_statuses_pull_down_menu', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'ADDONS_PAYMENT_" . strtoupper($this->_code) . "_SORT_ORDER', '100', 'Sort order of display. Lowest is displayed first.', '6', '11' , now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Include Note', 'ADDONS_PAYMENT_" . strtoupper($this->_code) . "_NO_NOTE', 'No', 'Choose whether your customer should be prompted to include a note or not?', '6', '16', 'lc_cfg_set_boolean_value(array(\'Yes\', \'No\'))', now())"); 
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Cart Method', 'ADDONS_PAYMENT_" . strtoupper($this->_code) . "_METHOD', 'Aggregate', 'What type of shopping cart do you want to use?', '6', '17', 'lc_cfg_set_boolean_value(array(\'Aggregate\', \'Itemized\'))', now())"); 
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Debug Email', 'ADDONS_PAYMENT_" . strtoupper($this->_code) . "_IPN_DEBUG', 'Yes', 'Enable debug email notifications', '6', '19', 'lc_cfg_set_boolean_value(array(\'Yes\', \'No\'))', now())"); 
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Digest Key', 'ADDONS_PAYMENT_" . strtoupper($this->_code) . "_IPN_DIGEST_KEY', 'PayPal_Shopping_Cart_IPN', 'Key to use for the digest functionality', '6', '20', now())"); 
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Debug Email Address', 'ADDONS_PAYMENT_" . strtoupper($this->_code) . "_IPN_DEBUG_EMAIL', '', 'The e-mail address to send <b>debug</b> notifications to', '6', '23', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Sandbox Mode', 'ADDONS_PAYMENT_" . strtoupper($this->_code) . "_TEST_MODE', '-1', 'Set to \'Yes\' for sandbox test environment or set to \'No\' for production environment.', '6', '24', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Return Behavior', 'ADDONS_PAYMENT_" . strtoupper($this->_code) . "_RM', '1', 'How should the customer be sent back from PayPal to the specified URL?<br>0=No IPN, 1=GET, 2=POST', '6', '25', 'lc_cfg_set_boolean_value(array(\'0\',\'1\',\'2\'))', now())"); 

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
      $this->_keys = array( 
          'ADDONS_PAYMENT_' . strtoupper($this->_code) . '_STATUS',
          'ADDONS_PAYMENT_' . strtoupper($this->_code) . '_ID',
          'ADDONS_PAYMENT_' . strtoupper($this->_code) . '_BUSINESS_ID',
          'ADDONS_PAYMENT_' . strtoupper($this->_code) . '_DEFAULT_CURRENCY',          
          'ADDONS_PAYMENT_' . strtoupper($this->_code) . '_ZONE',          
          'ADDONS_PAYMENT_' . strtoupper($this->_code) . '_PROCESSING_STATUS_ID',
          'ADDONS_PAYMENT_' . strtoupper($this->_code) . '_ORDER_DEFAULT_STATUS_ID',
          'ADDONS_PAYMENT_' . strtoupper($this->_code) . '_ORDER_ONHOLD_STATUS_ID',
          'ADDONS_PAYMENT_' . strtoupper($this->_code) . '_ORDER_CANCELED_STATUS_ID',
          'ADDONS_PAYMENT_' . strtoupper($this->_code) . '_SORT_ORDER',          
          'ADDONS_PAYMENT_' . strtoupper($this->_code) . '_NO_NOTE',
          'ADDONS_PAYMENT_' . strtoupper($this->_code) . '_METHOD',
          'ADDONS_PAYMENT_' . strtoupper($this->_code) . '_IPN_DIGEST_KEY',
          'ADDONS_PAYMENT_' . strtoupper($this->_code) . '_IPN_DEBUG',
          'ADDONS_PAYMENT_' . strtoupper($this->_code) . '_IPN_DEBUG_EMAIL',
          'ADDONS_PAYMENT_' . strtoupper($this->_code) . '_TEST_MODE',
          'ADDONS_PAYMENT_' . strtoupper($this->_code) . '_RM');      
    }

    return $this->_keys;
  }    
}
?>