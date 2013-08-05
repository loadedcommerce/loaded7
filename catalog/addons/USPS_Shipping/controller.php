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
class USPS_Shipping extends lC_Addon { // your addon must extend lC_Addon
  /*
  * Class constructor
  */
  public function USPS_Shipping() {    
    global $lC_Language;    
   /**
    * The addon type (category)
    * valid types; payment, shipping, themes, checkout, catalog, admin, reports, connectors, other 
    */    
    $this->_type = 'shipping';
   /**
    * The addon class name
    */    
    $this->_code = 'USPS_Shipping';        
   /**
    * The addon title used in the addons store listing
    */     
    $this->_title = $lC_Language->get('addon_shipping_usps_title');
   /**
    * The addon description used in the addons store listing
    */     
    $this->_description = $lC_Language->get('addon_shipping_usps_description');
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
    $this->_compatibility = '7.0.0.4.1'; // the addon is compatible with this core version and later   
   /**
    * The base64 encoded addon image used in the addons store listing
    */     
    $this->_thumbnail = lc_image(DIR_WS_CATALOG . 'addons/' . $this->_code . '/images/usps.png');
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
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Username', 'ADDONS_SHIPPING_" . strtoupper($this->_code) . "_USERID', 'NONE', 'Enter the USPS USERID assigned to you.', '6', '0', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Password', 'ADDONS_SHIPPING_" . strtoupper($this->_code) . "_PASSWORD', 'NONE', 'See USERID, above.', '6', '0', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('API Server', 'ADDONS_SHIPPING_" . strtoupper($this->_code) . "_SERVER', 'Production', 'An account at USPS is needed to use the Production server', '6', '0', 'lc_cfg_set_boolean_value(array(\'Test\', \'Production\'))', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Handling Fee', 'ADDONS_SHIPPING_" . strtoupper($this->_code) . "_HANDLING', '0', 'Handling fee for this shipping method.', '6', '0', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Tax Class', 'ADDONS_SHIPPING_" . strtoupper($this->_code) . "_TAX_CLASS', '0', 'Use the following tax class on the shipping fee.', '6', '0', 'lc_cfg_use_get_tax_class_title', 'lc_cfg_set_tax_classes_pull_down_menu', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Shipping Zone', 'ADDONS_SHIPPING_" . strtoupper($this->_code) . "_ZONE', '0', 'If a zone is selected, only enable this shipping method for that zone.', '6', '0', 'lc_cfg_use_get_zone_class_title', 'lc_cfg_set_zone_classes_pull_down_menu', now())");
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
                           'ADDONS_SHIPPING_' . strtoupper($this->_code) . '_USERID',
                           'ADDONS_SHIPPING_' . strtoupper($this->_code) . '_PASSWORD',
                           'ADDONS_SHIPPING_' . strtoupper($this->_code) . '_SERVER',
                           'ADDONS_SHIPPING_' . strtoupper($this->_code) . '_HANDLING',
                           'ADDONS_SHIPPING_' . strtoupper($this->_code) . '_TAX_CLASS',
                           'ADDONS_SHIPPING_' . strtoupper($this->_code) . '_ZONE',
                           'ADDONS_SHIPPING_' . strtoupper($this->_code) . '_SORT_ORDER');
    }

    return $this->_keys;
  }    
}
?>