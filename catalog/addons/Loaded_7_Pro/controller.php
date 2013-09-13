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
class Loaded_7_Pro extends lC_Addon { // your addon must extend lC_Addon
  /*
  * Class constructor
  */
  public function Loaded_7_Pro() {    
    global $lC_Language;    
   /**
    * The addon type (category)
    * valid types; payment, shipping, themes, checkout, catalog, admin, reports, connectors, other 
    */    
    $this->_type = 'system';
   /**
    * The addon class name
    */    
    $this->_code = 'Loaded_7_Pro';    
   /**
    * The addon title used in the addons store listing
    */     
    $this->_title = $lC_Language->get('addon_system_pro_title');
   /**
    * The addon description used in the addons store listing
    */     
    $this->_description = $lC_Language->get('addon_system_pro_description');
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
    $this->_compatibility = '7.0.0'; // the addon is compatible with this core version and later   
   /**
    * The base64 encoded addon image used in the addons store listing
    */     
    $this->_thumbnail = lc_image(DIR_WS_CATALOG . 'addons/' . $this->_code . '/images/loaded7-pro.png', $this->_title);
   /**
    * The addon enable/disable switch
    */    
    $this->_enabled = (defined('ADDONS_SYSTEM_' . strtoupper($this->_code) . '_STATUS') && @constant('ADDONS_SYSTEM_' . strtoupper($this->_code) . '_STATUS') == '1') ? true : false; 
    
    // auto install the module
    if (!$this->isInstalled()) {
      $this->install();
    }
  }
 /**
  * Checks to see if the addon has been installed
  *
  * @access public
  * @return boolean
  */
  public function isInstalled() {
    
    return (bool)defined('ADDONS_SYSTEM_' . strtoupper($this->_code) . '_STATUS');
  }
 /**
  * Install the addon
  *
  * @access public
  * @return void
  */
  public function install() {
    global $lC_Database;

    $lC_Database->simpleQuery("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'ADDONS_SYSTEM_" . strtoupper($this->_code) . "_STATUS'");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Enable AddOn', 'ADDONS_SYSTEM_" . strtoupper($this->_code) . "_STATUS', '1', 'Do you want to enable this addon?', '6', '0', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))', now())");
    $lC_Database->simpleQuery("delete from " . TABLE_TEMPLATE_BOXES . " where modules_group = 'system|Loaded_7_Pro'");
    $lC_Database->simpleQuery("insert into " . TABLE_TEMPLATE_BOXES . " (title, code, author_name, author_www, modules_group) values ('" . $this->_title . "', '" . $this->_type . "', '" . $this->_author . "','" . $this->authorWWW . "', 'system:Loaded_7_Pro'");
    
    lC_Cache::clear('configuration');
    lC_Cache::clear('addons');
    lC_Cache::clear('vqmoda');
  }
 /**
  * Return the configuration parameter keys an an array
  *
  * @access public
  * @return array
  */
  public function getKeys() {
    if (!isset($this->_keys)) {
      $this->_keys = array('ADDONS_SYSTEM_' . strtoupper($this->_code) . '_STATUS');
    }

    return $this->_keys;
  }    
}
?>