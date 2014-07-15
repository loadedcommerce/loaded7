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

if (file_exists(DIR_FS_CATALOG . 'addons/Loaded_7_Pro/controller.php')) include_once($lC_Vqmod->modCheck(DIR_FS_CATALOG . 'addons/Loaded_7_Pro/controller.php'));
if (file_exists(DIR_FS_CATALOG . 'addons/Loaded_7_B2B/classes/product.php')) include_once($lC_Vqmod->modCheck(DIR_FS_CATALOG . 'addons/Loaded_7_B2B/classes/product.php'));
if (file_exists(DIR_FS_CATALOG . 'addons/Loaded_7_B2B/admin/applications/customer_groups/classes/customer_groups.php')) include_once($lC_Vqmod->modCheck(DIR_FS_CATALOG . 'addons/Loaded_7_B2B/admin/applications/customer_groups/classes/customer_groups.php'));
if (file_exists(DIR_FS_CATALOG . 'addons/Loaded_7_B2B/admin/applications/categories/classes/categories.php')) include_once($lC_Vqmod->modCheck(DIR_FS_CATALOG . 'addons/Loaded_7_B2B/admin/applications/categories/classes/categories.php'));

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
    $this->_type = 'systems';
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
    $this->_enabled = $this->_checkAndActivate();
    if ($this->_enabled && !defined('ADDONS_SYSTEM_LOADED_7_B2B_STATUS')) $this->install();
   /**
    * Automatically install the module
    */ 
 //   $this->_auto_install = true;    
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
    
    $lC_Database->simpleQuery("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'B2B_SETTINGS_ALLOW_SELF_REGISTER'");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('', 'B2B_SETTINGS_ALLOW_SELF_REGISTER', '1', '', '6', '0', '', '', now())");

    $lC_Database->simpleQuery("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'B2B_SETTINGS_GUEST_CATALOG_ACCESS'");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('', 'B2B_SETTINGS_GUEST_CATALOG_ACCESS', '4', '', '6', '0', '', '', now())");
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
 /**
  * Check if the config key is already in the database
  *
  * @access protected
  * @return boolean
  */  
  private function _isConfigInstalled($key) {
    global $lC_Database;
    
    $Qcfg = $lC_Database->query('select configuration_id from :table_configuration where configuration_key = :configuration_key');
    $Qcfg->bindTable(':table_configuration', TABLE_CONFIGURATION);
    $Qcfg->bindValue(':configuration_key', $key);
    $Qcfg->execute();    
    
    $status = ($Qcfg->numberOfRows() > 0) ? true : false;
    
    $Qcfg->freeResult();
    
    return $status;
  } 
 /**
  * Check if the Pro addon is active
  *
  * @access public
  * @return array
  */
  protected function _checkAndActivate() {
  
    $isPro = utility::isPro();  
    $isB2B = utility::isB2B();  
    
    $enabled = false;
    if ($isPro) {
      $enabled = true;
      if ($isB2B) {
      } else {
        $this->install();
      }
    }
    
    return $enabled;
  }  
}
?>