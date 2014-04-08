<?php
/**
  @package    catalog::addons
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: controller.php v1.0 2013-08-08 datazen $
*/
global $lC_Vqmod;

include_once($lC_Vqmod->modCheck(DIR_FS_CATALOG . 'addons/Loaded_7_Pro/controller.php'));

class Loaded_7_B2B extends Loaded_7_Pro { 
  /*
  * Class constructor
  */
  public function Loaded_7_B2B() {    
    global $lC_Language, $lC_Database;   
   /**
    * The addon type (category)
    * valid types; payment, shipping, themes, checkout, catalog, admin, reports, connectors, other 
    */    
    $this->_type = 'system';
   /**
    * The addon class name
    */    
    $this->_code = 'Loaded_7_B2B';    
   /**
    * The addon title used in the addons store listing
    */     
    $this->_title = $lC_Language->get('addon_system_b2b_title');
   /**
    * The addon description used in the addons store listing
    */     
    $this->_description = $lC_Language->get('addon_system_b2b_description');
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
    $this->_thumbnail = lc_image(DIR_WS_CATALOG . 'addons/' . $this->_code . '/images/loaded7-b2b.png', $this->_title);
   /**
    * The addon enable/disable switch
    */ 
    if (defined('INSTALLATION_ID') && INSTALLATION_ID != '') {
      if ($this->_timeToCheck() === true) {
        $this->_enabled = $this->_validateSerial(INSTALLATION_ID);
        if ($this->_enabled) $this->_updateLastChecked();
      } else {
        $this->_enabled = (defined('ADDONS_SYSTEM_' . strtoupper($this->_code) . '_STATUS') && @constant('ADDONS_SYSTEM_' . strtoupper($this->_code) . '_STATUS') == '1') ? true : false;
      }
      if (!$this->_enabled) {
        $lC_Database->simpleQuery("update " . TABLE_CONFIGURATION . " set configuration_value = '0' where configuration_key = 'ADDONS_SYSTEM_" . strtoupper($this->_code) . "_STATUS'");
      } else {
        $lC_Database->simpleQuery("update " . TABLE_CONFIGURATION . " set configuration_value = '1' where configuration_key = 'ADDONS_SYSTEM_" . strtoupper($this->_code) . "_STATUS'");
      }      
    } else {
      $this->_enabled = false;
    }         
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
  }
 /**
  * Return the configuration parameter keys an an array
  *
  * @access public
  * @return array
  */
  public function getKeys() {
    if (!isset($this->_keys)) {
      $this->_keys = array('ADDONS_PAYMENT_' . strtoupper($this->_code) . '_STATUS');
    }

    return $this->_keys;
  }    
}
?>