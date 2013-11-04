<?php
/**
  @package    catalog::addons::payment
  @author     Loaded Commerce, LLC
  @copyright  Copyright 2003-2013 Loaded Commerce Development Team
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: controller.php v1.0 2013-08-08 datazen $
*/
class Vantiv_Whitelabel extends lC_Addon { // your addon must extend lC_Addon
  /*
  * Class constructor
  */
  public function Vantiv_Whitelabel() {    
    global $lC_Language;    
   /**
    * The addon type (category)
    * valid types; payment, shipping, themes, checkout, catalog, admin, reports, connectors, other 
    */    
    $this->_type = 'custom';
   /**
    * The addon class name
    */    
    $this->_code = 'Vantiv_Whitelabel';        
   /**
    * The addon title used in the addons store listing
    */     
    $this->_title = $lC_Language->get('addon_payment_vantiv_wl_title');
   /**
    * The addon description used in the addons store listing
    */     
    $this->_description = $lC_Language->get('addon_payment_vantiv_wl_description');
   /**
    * The developers name
    */    
    $this->_author = 'Vantiv';
   /**
    * The developers web address
    */    
    $this->_authorWWW = 'http://www.vantiv.com';    
   /**
    * The addon version
    */     
    $this->_version = '1.0.0';
   /**
    * The Loaded 7 core compatibility version
    */     
    $this->_compatibility = '7.0.0'; // the addon is compatible with this core version and later   
   /**
    * The base64 encoded addon image used in the addons store listing
    */     
    $this->_thumbnail = lc_image(DIR_WS_CATALOG . 'addons/' . $this->_code . '/images/vantiv.png', $this->_title);
   /**
    * The mobile capability of the addon
    */ 
    $this->_mobile_enabled = false;    
   /**
    * The addon enable/disable switch
    */    
    $this->_enabled = (defined('ADDONS_CUSTOM_' . strtoupper($this->_code) . '_STATUS') && @constant('ADDONS_CUSTOM_' . strtoupper($this->_code) . '_STATUS') == '1') ? true : false; 
   /**
    * Automatically install the module
    */ 
    $this->_auto_install = true;  
  }
 /**
  * Checks to see if the addon has been installed
  *
  * @access public
  * @return boolean
  */
  public function isInstalled() {
    return (bool)defined('ADDONS_CUSTOM_' . strtoupper($this->_code) . '_STATUS');
  }
 /**
  * Install the addon
  *
  * @access public
  * @return void
  */
  public function install() {
    global $lC_Database;

    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Enable AddOn', 'ADDONS_CUSTOM_" . strtoupper($this->_code) . "_STATUS', '1', 'Do you want to enable this addon?', '6', '0', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))', now())");
  }
 /**
  * Return the configuration parameter keys an an array
  *
  * @access public
  * @return array
  */
  public function getKeys() {
    if (!isset($this->_keys)) {
      $this->_keys = array('ADDONS_CUSTOM_' . strtoupper($this->_code) . '_STATUS');
    }

    return $this->_keys;
  } 
}
?>