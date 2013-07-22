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
class Fedex_Web_Services extends lC_Addon {
  /*
  * Class constructor
  */
  public function Fedex_Web_Services() {    
    global $lC_Language;    
   /**
    * The addon type (category)
    * valid types; payment, shipping, themes, checkout, catalog, admin, reports, connectors, other 
    */    
    $this->_type = 'shipping';
   /**
    * The addon class name
    */    
    $this->_code = 'Fedex_Web_Services';        
   /**
    * The addon title used in the addons store listing
    */     
    $this->_title = $lC_Language->get('addon_shipping_fedex_title');
   /**
    * The addon description used in the addons store listing
    */     
    $this->_description = $lC_Language->get('addon_shipping_fedex_description');
   /**
    * The addon blurb used in the addons store listing
    */  
    $this->_blurb = (!(@extension_loaded('soap'))) ? $lC_Language->get('addon_shipping_fedex_blurb') : null;
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
    $this->_compatibility = '7.0.1.1'; // the addon is compatible with this core version and later   
   /**
    * The base64 encoded addon image used in the addons store listing
    */     
    $this->_thumbnail = lc_image(DIR_WS_CATALOG . 'addons/' . $this->_code . '/images/fedex.png');
   /**
    * The addon enable/disable switch
    */    
    $this->_enabled = (defined('ADDONS_SHIPPING_' . strtoupper($this->_code) . '_STATUS') && @constant('ADDONS_SHIPPING_' . strtoupper($this->_code) . '_STATUS') == '1') ? true : false;      
  }
 /**
  * Checks to see if the addon has been installed
  *
  * @access public
  * @return boolean
  */
  public function isInstalled() {
    return (bool)defined('ADDONS_SHIPPING_' . strtoupper($this->_code) . '_STATUS');
  }
 /**
  * Install the addon
  *
  * @access public
  * @return void
  */
  public function install() {
    global $lC_Database;

    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Enable AddOn', 'ADDONS_SHIPPING_" . strtoupper($this->_code) . "_STATUS', '-1', 'Do you want to enable this addon?', '6', '0', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Web Services Key', 'ADDONS_SHIPPING_" . strtoupper($this->_code) . "_KEY', '', 'Enter your FedEx Web Services key.', '6', '0', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Password', 'ADDONS_SHIPPING_" . strtoupper($this->_code) . "_PWD', '', 'Enter your FedEx Web Services password.', '6', '0', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Account Number', 'ADDONS_SHIPPING_" . strtoupper($this->_code) . "_ACT_NUM', '', 'Enter your FedEx Account Number.', '6', '0', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Meter Number', 'ADDONS_SHIPPING_" . strtoupper($this->_code) . "_METER_NUM', '', 'Enter your FedEx Meter Number.', '6', '0', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Weight Units', 'ADDONS_SHIPPING_" . strtoupper($this->_code) . "_WEIGHT', 'LB', 'Select the weight unit.', '6', '0', 'lc_cfg_set_boolean_value(array(\'LB\', \'KG\'))', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Address Line 1', 'ADDONS_SHIPPING_" . strtoupper($this->_code) . "_ADDRESS_1', '', 'Enter the first line of your ship-from street address; REQUIRED.', '6', '0', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Address Line 2', 'ADDONS_SHIPPING_" . strtoupper($this->_code) . "_ADDRESS_2', '', 'Enter the second line of your ship-from street address.', '6', '0', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('City', 'ADDONS_SHIPPING_" . strtoupper($this->_code) . "_CITY', '', 'Enter the city name for the ship-from street address; REQUIRED.', '6', '0', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('State or Province', 'ADDONS_SHIPPING_" . strtoupper($this->_code) . "_STATE', '', 'Enter the 2 letter state or province name for the ship-from street address; REQUIRED.', '6', '0', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Postal Code', 'ADDONS_SHIPPING_" . strtoupper($this->_code) . "_POSTAL', '', 'Enter the postal code for the ship-from street address; REQUIRED.', '6', '0', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Phone Number', 'ADDONS_SHIPPING_" . strtoupper($this->_code) . "_PHONE', '', 'Enter a contact phone number for your company; REQUIRED.', '6', '0', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Drop Off Type', 'ADDONS_SHIPPING_" . strtoupper($this->_code) . "_DROPOFF', '1', 'Dropoff type: (1 = Regular, 2 = Courier, 3 = Drop Box, 4 = Drop at BSC, 5 = Drop at Station)', '6', '0', 'lc_cfg_set_boolean_value(array(\'1\', \'2\', \'3\', \'4\', \'5\'))', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Express Saver', 'ADDONS_SHIPPING_" . strtoupper($this->_code) . "_EXPRESS_SAVER', '1', 'Do you want to enable FedEx Express Saver?', '6', '0', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Standard Overnight', 'ADDONS_SHIPPING_" . strtoupper($this->_code) . "_STANDARD_OVERNIGHT', '1', 'Do you want to enable FedEx Express Standard Overnight?', '6', '0', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('First Overnight', 'ADDONS_SHIPPING_" . strtoupper($this->_code) . "_FIRST_OVERNIGHT', '1', 'Do you want to enable FedEx Express First Overnight?', '6', '0', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Priority Overnight', 'ADDONS_SHIPPING_" . strtoupper($this->_code) . "_PRIORITY_OVERNIGHT', '1', 'Do you want to enable FedEx Express Priority Overnight?', '6', '0', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('2 Day', 'ADDONS_SHIPPING_" . strtoupper($this->_code) . "_2DAY', '1', 'Do you want to enable FedEx Express 2 Day?', '6', '0', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Int\'l Priority', 'ADDONS_SHIPPING_" . strtoupper($this->_code) . "_INTERNATIONAL_PRIORITY', '1', 'Do you want to enable FedEx Express International Priority?', '6', '0', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Int\'l Economy', 'ADDONS_SHIPPING_" . strtoupper($this->_code) . "_INTERNATIONAL_ECONOMY', '1', 'Do you want to enable FedEx Express International Economy?', '6', '0', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Ground', 'ADDONS_SHIPPING_" . strtoupper($this->_code) . "_GROUND', '1', 'Do you want to enable FedEx Ground?', '6', '0', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Int\'l Ground', 'ADDONS_SHIPPING_" . strtoupper($this->_code) . "_INTERNATIONAL_GROUND', '1', 'Do you want to enable FedEx International Ground?', '6', '0', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Freight', 'ADDONS_SHIPPING_" . strtoupper($this->_code) . "_FREIGHT', '1', 'Do you want to enable FedEx Freight?', '6', '0', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Saturday Delivery', 'ADDONS_SHIPPING_" . strtoupper($this->_code) . "_SATURDAY', '-1', 'Do you want to enable Saturday Delivery?', '6', '0', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Ground Handling Fee', 'ADDONS_SHIPPING_" . strtoupper($this->_code) . "_HANDLING_FEE', '0.00', 'Add a domestic handling fee or leave blank (example: 15 or 15%).', '6', '0', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Home Delivery Fee', 'ADDONS_SHIPPING_" . strtoupper($this->_code) . "_HOME_DELIVERY_HANDLING_FEE', '0.00', 'Add a home delivery handling fee or leave blank (example: 15 or 15%).', '6', '0', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Express Handling Fee', 'ADDONS_SHIPPING_" . strtoupper($this->_code) . "_EXPRESS_HANDLING_FEE', '0.00', 'Add a domestic handling fee or leave blank (example: 15 or 15%).', '6', '0', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Int\'l Ground Handling Fee', 'ADDONS_SHIPPING_" . strtoupper($this->_code) . "_INT_HANDLING_FEE', '0.00', 'Add an international handling fee or leave blank (example: 15 or 15%).', '6', '0', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Int\'l Express Handling Fee', 'ADDONS_SHIPPING_" . strtoupper($this->_code) . "_INT_EXPRESS_HANDLING_FEE', '0.00', 'Add an international handling fee or leave blank (example: 15 or 15%).', '6', '0', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('FedEx Rates', 'ADDONS_SHIPPING_" . strtoupper($this->_code) . "_RATES', 'LIST', 'FedEx Rates method.', '6', '0', 'lc_cfg_set_boolean_value(array(\'LIST\', \'ACCOUNT\'))', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('FedEx Server', 'ADDONS_SHIPPING_" . strtoupper($this->_code) . "_SERVER', 'TEST', 'Set to TEST for test server pr PRODUCTION for live server.', '6', '0', 'lc_cfg_set_boolean_value(array(\'TEST\', \'PRODUCTION\'))', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Signature Option', 'ADDONS_SHIPPING_" . strtoupper($this->_code) . "_SIGNATURE_OPTION', '-1', 'Require a signature on orders greater than or equal to this value (-1 to disable).', '6', '0', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Ready to Ship', 'ADDONS_SHIPPING_" . strtoupper($this->_code) . "_READY_TO_SHIP', '-1', 'Enable products_ship_sep or products_ready_to_ship field (required to identify products which ship separately)?', '6', '0', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Shipping Zone', 'ADDONS_SHIPPING_" . strtoupper($this->_code) . "_ZONE', '0', 'If a zone is selected, only enable this shipping method for that zone.', '6', '0', 'lc_cfg_use_get_zone_class_title', 'lc_cfg_set_zone_classes_pull_down_menu', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Tax Class', 'ADDONS_SHIPPING_" . strtoupper($this->_code) . "_TAX_CLASS', '0', 'Use the following tax class on the shipping fee.', '6', '0', 'lc_cfg_use_get_tax_class_title', 'lc_cfg_set_tax_classes_pull_down_menu', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'ADDONS_SHIPPING_" . strtoupper($this->_code) . "_SORT_ORDER', '0', 'Sort order of display.', '6', '0', now())");
    
    $this->_installSQL();
  }
 /**
  * Return the configuration parameter keys an an array
  *
  * @access public
  * @return array
  */
  public function getKeys() {
    if (!isset($this->_keys)) {
      $this->_keys = array('ADDONS_SHIPPING_' . strtoupper($this->_code) . '_STATUS',
                           'ADDONS_SHIPPING_' . strtoupper($this->_code) . '_KEY',
                           'ADDONS_SHIPPING_' . strtoupper($this->_code) . '_PWD',
                           'ADDONS_SHIPPING_' . strtoupper($this->_code) . '_ACT_NUM',
                           'ADDONS_SHIPPING_' . strtoupper($this->_code) . '_METER_NUM',
                           'ADDONS_SHIPPING_' . strtoupper($this->_code) . '_WEIGHT',
                           'ADDONS_SHIPPING_' . strtoupper($this->_code) . '_ADDRESS_1',
                           'ADDONS_SHIPPING_' . strtoupper($this->_code) . '_ADDRESS_2',
                           'ADDONS_SHIPPING_' . strtoupper($this->_code) . '_CITY',
                           'ADDONS_SHIPPING_' . strtoupper($this->_code) . '_STATE',
                           'ADDONS_SHIPPING_' . strtoupper($this->_code) . '_POSTAL',
                           'ADDONS_SHIPPING_' . strtoupper($this->_code) . '_PHONE',
                           'ADDONS_SHIPPING_' . strtoupper($this->_code) . '_DROPOFF',
                           'ADDONS_SHIPPING_' . strtoupper($this->_code) . '_EXPRESS_SAVER',
                           'ADDONS_SHIPPING_' . strtoupper($this->_code) . '_STANDARD_OVERNIGHT',
                           'ADDONS_SHIPPING_' . strtoupper($this->_code) . '_FIRST_OVERNIGHT',
                           'ADDONS_SHIPPING_' . strtoupper($this->_code) . '_PRIORITY_OVERNIGHT',
                           'ADDONS_SHIPPING_' . strtoupper($this->_code) . '_2DAY',
                           'ADDONS_SHIPPING_' . strtoupper($this->_code) . '_INTERNATIONAL_PRIORITY',
                           'ADDONS_SHIPPING_' . strtoupper($this->_code) . '_INTERNATIONAL_ECONOMY',
                           'ADDONS_SHIPPING_' . strtoupper($this->_code) . '_GROUND',
                           'ADDONS_SHIPPING_' . strtoupper($this->_code) . '_FREIGHT',
                           'ADDONS_SHIPPING_' . strtoupper($this->_code) . '_INTERNATIONAL_GROUND',
                           'ADDONS_SHIPPING_' . strtoupper($this->_code) . '_SATURDAY',
                           'ADDONS_SHIPPING_' . strtoupper($this->_code) . '_TAX_CLASS',
                           'ADDONS_SHIPPING_' . strtoupper($this->_code) . '_HANDLING_FEE',
                           'ADDONS_SHIPPING_' . strtoupper($this->_code) . '_HOME_DELIVERY_HANDLING_FEE',
                           'ADDONS_SHIPPING_' . strtoupper($this->_code) . '_EXPRESS_HANDLING_FEE',
                           'ADDONS_SHIPPING_' . strtoupper($this->_code) . '_INT_HANDLING_FEE',
                           'ADDONS_SHIPPING_' . strtoupper($this->_code) . '_INT_EXPRESS_HANDLING_FEE',
                           'ADDONS_SHIPPING_' . strtoupper($this->_code) . '_RATES',
                           'ADDONS_SHIPPING_' . strtoupper($this->_code) . '_SERVER',
                           'ADDONS_SHIPPING_' . strtoupper($this->_code) . '_SIGNATURE_OPTION',
                           'ADDONS_SHIPPING_' . strtoupper($this->_code) . '_READY_TO_SHIP',
                           'ADDONS_SHIPPING_' . strtoupper($this->_code) . '_ZONE',
                           'ADDONS_SHIPPING_' . strtoupper($this->_code) . '_SORT_ORDER');                           
    }

    return $this->_keys;
  }
 /**
  * remove the add-on module and SQL
  *
  * @access public
  */ 
  public function remove() {
    global $lC_Database;

    parent::remove();
    
    $lC_Database->simpleQuery("ALTER IGNORE TABLE " . TABLE_PRODUCTS . " DROP `products_ready_to_ship`, DROP `products_ship_sep`");
  }   
 /**
  * install the addon SQL
  *
  * @access private
  */  
  private function _installSQL() {
    global $lC_Database;
    
    $lC_Database->simpleQuery("ALTER IGNORE TABLE " . TABLE_PRODUCTS . " ADD `products_ready_to_ship` TINYINT( 1 ) NOT NULL DEFAULT '0' AFTER `products_ordered`, ADD `products_ship_sep` TINYINT( 1 ) NOT NULL DEFAULT '0' AFTER `products_ready_to_ship`");
  }      
}
?>