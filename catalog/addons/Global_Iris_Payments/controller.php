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
// include the addon class (this is NOT the addons class; notice there is no `s` in the class name)
require_once(DIR_FS_CATALOG . 'includes/classes/addon.php');

// your addon must extend lC_Addon
class Global_Iris_Payments extends lC_Addon {
  /*
  * Class constructor
  */
  public function Global_Iris_Payments() {    
    global $lC_Language;    
   /**
    * The addon type (category)
    * valid types; payment, shipping, themes, checkout, catalog, admin, reports, connectors, other 
    */    
    $this->_type = 'payment';
   /**
    * The addon class name
    */    
    $this->_code = 'Global_Iris_Payments';        
   /**
    * The addon title used in the addons store listing
    */     
    $this->_title = $lC_Language->get('addon_payment_globaliris_title');
   /**
    * The addon description used in the addons store listing
    */     
    $this->_description = $lC_Language->get('addon_payment_globaliris_description');
   /**
    * The developers name
    */    
    $this->_author = 'Loaded Commerce';
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
    $this->_compatibility = '7.0.1.1'; // the addon is compatible with this core version and later   
   /**
    * The base64 encoded addon image used in the addons store listing
    */     
    $this->_thumbnail = lc_image(DIR_WS_CATALOG . 'addons/' . $this->_code . '/images/iris.png');
   /**
    * The mobile capability of the addon
    */ 
    $this->_mobile_enabled = false;    
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
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Client ID', 'ADDONS_PAYMENT_" . strtoupper($this->_code) . "_MERCHANT_ID', '', 'The Merchant ID used for the Global Iris payment service.', '6', '0', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Secret key', 'ADDONS_PAYMENT_" . strtoupper($this->_code) . "_SECRET_KEY', '', 'The Secret Key used for the Global Iris payment service.', '6', '0', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Cards Accepted', 'ADDONS_PAYMENT_" . strtoupper($this->_code) . "_ACCEPTED_TYPES', '', 'Accept these credit card types for this payment method.', '6', '0', 'lc_cfg_set_credit_cards_checkbox_field', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Payment Zone', 'ADDONS_PAYMENT_" . strtoupper($this->_code) . "_ZONE', '0', 'If a zone is selected, only enable this payment method for that zone.', '6', '2', 'lc_cfg_use_get_zone_class_title', 'lc_cfg_set_zone_classes_pull_down_menu', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Set Pending Status', 'ADDONS_PAYMENT_" . strtoupper($this->_code) . "_ORDER_STATUS_ID', '1', 'For Pending orders, set the status of orders made with this payment module to this value.', '6', '0', 'lc_cfg_use_get_order_status_title', 'lc_cfg_set_order_statuses_pull_down_menu', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Set Complete Status', 'ADDONS_PAYMENT_" . strtoupper($this->_code) . "_ORDER_STATUS_COMPLETE_ID', '4', 'For Completed orders, set the status of orders made with this payment module to this value.', '6', '0', 'lc_cfg_use_get_order_status_title', 'lc_cfg_set_order_statuses_pull_down_menu', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'ADDONS_PAYMENT_" . strtoupper($this->_code) . "_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0' , now())");   
    
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
                           'ADDONS_PAYMENT_' . strtoupper($this->_code) . '_MERCHANT_ID',
                           'ADDONS_PAYMENT_' . strtoupper($this->_code) . '_SECRET_KEY',
                           'ADDONS_PAYMENT_' . strtoupper($this->_code) . '_ACCEPTED_TYPES',
                           'ADDONS_PAYMENT_' . strtoupper($this->_code) . '_ZONE',
                           'ADDONS_PAYMENT_' . strtoupper($this->_code) . '_ORDER_STATUS_ID',
                           'ADDONS_PAYMENT_' . strtoupper($this->_code) . '_ORDER_STATUS_COMPLETE_ID',
                           'ADDONS_PAYMENT_' . strtoupper($this->_code) . '_SORT_ORDER');      
    }

    return $this->_keys;
  }    
}
?>