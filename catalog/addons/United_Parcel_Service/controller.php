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
class United_Parcel_Service extends lC_Addon { // your addon must extend lC_Addon
  /*
  * Class constructor
  */
  public function United_Parcel_Service() {    
    global $lC_Language;    
   /**
    * The addon type (category)
    * valid types; payment, shipping, themes, checkout, catalog, admin, reports, connectors, other 
    */    
    $this->_type = 'shipping';
   /**
    * The addon class name
    */    
    $this->_code = 'United_Parcel_Service';        
   /**
    * The addon title used in the addons store listing
    */     
    $this->_title = $lC_Language->get('addon_shipping_ups_title');
   /**
    * The addon blurb used in the addons store listing
    */     
    $this->_blurb = $lC_Language->get('addon_shipping_ups_blurb');    
   /**
    * The addon description used in the addons store listing
    */     
    $this->_description = $lC_Language->get('addon_shipping_ups_description');
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
    $this->_thumbnail = lc_image(DIR_WS_CATALOG . 'addons/' . $this->_code . '/images/ups.png', $this->_title);
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
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Pickup Method', 'ADDONS_SHIPPING_" . strtoupper($this->_code) . "_PICKUP', 'CC', 'How do you give packages to UPS? CC = Customer Counter, RDP = Daily Pickup, OTP = One Time Pickup, LC = Letter Center, OCA = On Call Air.', '6', '0', 'lc_cfg_set_boolean_value(array(\'CC\', \'RDP\', \'OTP\', \'LC\', \'OCA\'))', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Packaging Type', 'ADDONS_SHIPPING_" . strtoupper($this->_code) . "_PACKAGE', 'CP', 'CP = Your Packaging, ULE = UPS Letter, UT = UPS Tube, UBE = UPS Express Box.', '6', '0', 'lc_cfg_set_boolean_value(array(\'CP\', \'ULE\', \'UT\', \'UBE\'))', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Quote Delivery For', 'ADDONS_SHIPPING_" . strtoupper($this->_code) . "_RES', 'Residence', 'Quote for Residential or Commercial Delivery.', '6', '0', 'lc_cfg_set_boolean_value(array(\'Residence\', \'Business\'))', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Handling Fee', 'ADDONS_SHIPPING_" . strtoupper($this->_code) . "_HANDLING', '0.00', 'Handling fee for this shipping method.', '6', '0', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Next AM', 'ADDONS_SHIPPING_" . strtoupper($this->_code) . "_TYPE_1DM', '1', 'Enable Next Day AM delivery.', '6', '0', 'lc_cfg_set_boolean_value(array(1, -1))', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Next AM Ltr', 'ADDONS_SHIPPING_" . strtoupper($this->_code) . "_TYPE_1DML', '1', 'Enable Next Day AM Letter delivery.', '6', '0', 'lc_cfg_set_boolean_value(array(1, -1))', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Next Day', 'ADDONS_SHIPPING_" . strtoupper($this->_code) . "_TYPE_1DA', '1', 'Enable Next Day delivery.', '6', '0', 'lc_cfg_set_boolean_value(array(1, -1))', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Next Day Ltr', 'ADDONS_SHIPPING_" . strtoupper($this->_code) . "_TYPE_1DAL', '1', 'Enable Next Day Letter delivery.', '6', '0', 'lc_cfg_set_boolean_value(array(1, -1))', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Next Day PR', 'ADDONS_SHIPPING_" . strtoupper($this->_code) . "_TYPE_1DAPI', '1', 'Enable Next Day Puerto Rico delivery.', '6', '0', 'lc_cfg_set_boolean_value(array(1, -1))', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Next Day Save', 'ADDONS_SHIPPING_" . strtoupper($this->_code) . "_TYPE_1DP', '1', 'Enable Next Day Air Saver delivery.', '6', '0', 'lc_cfg_set_boolean_value(array(1, -1))', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Next Day Save Ltr', 'ADDONS_SHIPPING_" . strtoupper($this->_code) . "_TYPE_1DPL', '1', 'Enable Next Day Air Saver Letter delivery.', '6', '0', 'lc_cfg_set_boolean_value(array(1, -1))', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('2nd Day AM', 'ADDONS_SHIPPING_" . strtoupper($this->_code) . "_TYPE_2DM', '1', 'Enable 2nd Day AM delivery.', '6', '0', 'lc_cfg_set_boolean_value(array(1, -1))', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('2nd Day AM Ltr', 'ADDONS_SHIPPING_" . strtoupper($this->_code) . "_TYPE_2DML', '1', 'Enable 2nd Day AM Letter delivery.', '6', '0', 'lc_cfg_set_boolean_value(array(1, -1))', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('2nd Day', 'ADDONS_SHIPPING_" . strtoupper($this->_code) . "_TYPE_2DA', '1', 'Enable 2nd Day delivery.', '6', '0', 'lc_cfg_set_boolean_value(array(1, -1))', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('2nd Day Ltr', 'ADDONS_SHIPPING_" . strtoupper($this->_code) . "_TYPE_2DAL', '1', 'Enable 2nd Day Letter delivery.', '6', '0', 'lc_cfg_set_boolean_value(array(1, -1))', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('3 Day Select', 'ADDONS_SHIPPING_" . strtoupper($this->_code) . "_TYPE_3DS', '1', 'Enable 3 Day Select delivery.', '6', '0', 'lc_cfg_set_boolean_value(array(1, -1))', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Ground', 'ADDONS_SHIPPING_" . strtoupper($this->_code) . "_TYPE_GND', '1', 'Enable Ground delivery.', '6', '0', 'lc_cfg_set_boolean_value(array(1, -1))', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Canada', 'ADDONS_SHIPPING_" . strtoupper($this->_code) . "_TYPE_STD', '1', 'Enable Standard delivery to Canada.', '6', '0', 'lc_cfg_set_boolean_value(array(1, -1))', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('World Express', 'ADDONS_SHIPPING_" . strtoupper($this->_code) . "_TYPE_XPR', '1', 'Enable World Express delivery.', '6', '0', 'lc_cfg_set_boolean_value(array(1, -1))', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('World Express Ltr', 'ADDONS_SHIPPING_" . strtoupper($this->_code) . "_TYPE_XPRL', '1', 'Enable World Express Letter delivery.', '6', '0', 'lc_cfg_set_boolean_value(array(1, -1))', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('World Express Plus', 'ADDONS_SHIPPING_" . strtoupper($this->_code) . "_TYPE_XDM', '1', 'Enable World Express Plus delivery.', '6', '0', 'lc_cfg_set_boolean_value(array(1, -1))', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('World Express Plus Ltr', 'ADDONS_SHIPPING_" . strtoupper($this->_code) . "_TYPE_XDML', '1', 'Enable World Express Plus Letter delivery.', '6', '0', 'lc_cfg_set_boolean_value(array(1, -1))', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('World Expedite', 'ADDONS_SHIPPING_" . strtoupper($this->_code) . "_TYPE_XPD', '1', 'Enable World Expedite delivery.', '6', '0', 'lc_cfg_set_boolean_value(array(1, -1))', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Shipping Zone', 'ADDONS_SHIPPING_" . strtoupper($this->_code) . "_ZONE', '0', 'If a zone is selected, only enable this shipping method for that zone.', '6', '0', 'lc_cfg_use_get_zone_class_title', 'lc_cfg_set_zone_classes_pull_down_menu', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Tax Class', 'ADDONS_SHIPPING_" . strtoupper($this->_code) . "_TAX_CLASS', '0', 'Use the following tax class on the shipping fee.', '6', '0', 'lc_cfg_use_get_tax_class_title', 'lc_cfg_set_tax_classes_pull_down_menu', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'ADDONS_SHIPPING_" . strtoupper($this->_code) . "_SORT_ORDER', '0', 'Sort order of display.', '6', '0', now())");
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
                           'ADDONS_SHIPPING_' . strtoupper($this->_code) . '_PICKUP',
                           'ADDONS_SHIPPING_' . strtoupper($this->_code) . '_PACKAGE',
                           'ADDONS_SHIPPING_' . strtoupper($this->_code) . '_RES',
                           'ADDONS_SHIPPING_' . strtoupper($this->_code) . '_HANDLING',
                           'ADDONS_SHIPPING_' . strtoupper($this->_code) . '_TYPE_1DM',
                           'ADDONS_SHIPPING_' . strtoupper($this->_code) . '_TYPE_1DML',
                           'ADDONS_SHIPPING_' . strtoupper($this->_code) . '_TYPE_1DA',
                           'ADDONS_SHIPPING_' . strtoupper($this->_code) . '_TYPE_1DAL',
                           'ADDONS_SHIPPING_' . strtoupper($this->_code) . '_TYPE_1DAPI',
                           'ADDONS_SHIPPING_' . strtoupper($this->_code) . '_TYPE_1DP',
                           'ADDONS_SHIPPING_' . strtoupper($this->_code) . '_TYPE_1DPL',
                           'ADDONS_SHIPPING_' . strtoupper($this->_code) . '_TYPE_2DM',
                           'ADDONS_SHIPPING_' . strtoupper($this->_code) . '_TYPE_2DML',
                           'ADDONS_SHIPPING_' . strtoupper($this->_code) . '_TYPE_2DA',
                           'ADDONS_SHIPPING_' . strtoupper($this->_code) . '_TYPE_2DAL',
                           'ADDONS_SHIPPING_' . strtoupper($this->_code) . '_TYPE_3DS',
                           'ADDONS_SHIPPING_' . strtoupper($this->_code) . '_TYPE_GND',
                           'ADDONS_SHIPPING_' . strtoupper($this->_code) . '_TYPE_STD',
                           'ADDONS_SHIPPING_' . strtoupper($this->_code) . '_TYPE_XPR',
                           'ADDONS_SHIPPING_' . strtoupper($this->_code) . '_TYPE_XPRL',
                           'ADDONS_SHIPPING_' . strtoupper($this->_code) . '_TYPE_XDM',
                           'ADDONS_SHIPPING_' . strtoupper($this->_code) . '_TYPE_XDML',
                           'ADDONS_SHIPPING_' . strtoupper($this->_code) . '_TYPE_XPD',
                           'ADDONS_SHIPPING_' . strtoupper($this->_code) . '_ZONE',
                           'ADDONS_SHIPPING_' . strtoupper($this->_code) . '_TAX_CLASS',
                           'ADDONS_SHIPPING_' . strtoupper($this->_code) . '_SORT_ORDER');
    }

    return $this->_keys;
  }     
}
?>