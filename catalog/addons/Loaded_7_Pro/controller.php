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
global $lC_Vqmod;
require_once($lC_Vqmod->modCheck('../includes/classes/transport.php'));

class Loaded_7_Pro extends lC_Addon { // your addon must extend lC_Addon
  /*
  * Class constructor
  */
  public function Loaded_7_Pro() {    
    global $lC_Language, $lC_Database;    
   /**
    * The addon type (category)
    * valid types; payment, shipping, themes, checkout, catalog, admin, reports, connectors, other 
    */    
    $this->_type = 'featured';
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
    $this->_thumbnail = lc_image(DIR_WS_CATALOG . 'addons/' . $this->_code . '/images/loaded7_pro.png', $this->_title);
   /**
    * The addon enable/disable switch
    */ 
    if (defined('INSTALLATION_ID') && INSTALLATION_ID != '') {
//      if ($this->_timeToCheck() === true) {
        $this->_enabled = $this->_validateSerial(INSTALLATION_ID);
        if ($this->_enabled) $this->_updateLastChecked();
//      } else {
//        $this->_enabled = (defined('ADDONS_SYSTEM_' . strtoupper($this->_code) . '_STATUS') && @constant('ADDONS_SYSTEM_' . strtoupper($this->_code) . '_STATUS') == '1') ? true : false;
//      }
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

    if (isset($_SESSION['remove_loaded_7_pro']) && $_SESSION['remove_loaded_7_pro'] == true) {
      unset($_SESSION['remove_loaded_7_pro']);
      $this->_clearCache();
    } else if (!$this->_checkStatus()) { 
      $lC_Database->simpleQuery("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'ADDONS_SYSTEM_" . strtoupper($this->_code) . "_STATUS'");
      $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Enable AddOn', 'ADDONS_SYSTEM_" . strtoupper($this->_code) . "_STATUS', '1', 'Do you want to enable this addon?', '6', '0', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))', now())");
      $lC_Database->simpleQuery("delete from " . TABLE_TEMPLATES_BOXES . " where modules_group like '%Loaded_7_Pro%'");
      $lC_Database->simpleQuery("insert into " . TABLE_TEMPLATES_BOXES . " (title, code, author_name, author_www, modules_group) values ('Loaded 7 Pro', '" . $this->_type . "', '" . $this->_author . "','" . $this->_authorWWW . "', 'system|Loaded_7_Pro')");
      // product classes
      $lC_Database->simpleQuery("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'DEFAULT_PRODUCT_CLASSES_ID'");
      $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('', 'DEFAULT_PRODUCT_CLASSES_ID', '1', '', '6', '0', '', '', now())");
      $lC_Database->simpleQuery("CREATE TABLE IF NOT EXISTS `" . DB_TABLE_PREFIX . "product_classes` (id int(11) NOT NULL AUTO_INCREMENT,`name` varchar(128) NOT NULL DEFAULT '', `comment` varchar(255) DEFAULT NULL, `status` tinyint(1) NOT NULL DEFAULT '0', language_id int(11) NOT NULL DEFAULT '1', PRIMARY KEY (id)) ENGINE=MyISAM CHARACTER SET utf8 COLLATE utf8_general_ci AUTO_INCREMENT=2;");
      $lC_Database->simpleQuery("delete from `" . DB_TABLE_PREFIX . "product_classes` where id = '1'");
      $lC_Database->simpleQuery("insert into `" . DB_TABLE_PREFIX . "product_classes` (id, name, comment, status, language_id) VALUES ('1', 'Common', 'Common Class', 1, 1);");
      $lC_Database->simpleQuery("delete from " . TABLE_TEMPLATES_BOXES . " where code = 'product_classes'");
      $lC_Database->simpleQuery("insert into " . TABLE_TEMPLATES_BOXES . " (title, code, author_name, author_www, modules_group) VALUES ('Product Classes', 'product_classes', 'Loaded Commerce, LLC', 'http://www.loadedcommerce.com', 'product_attributes')");
      $lC_Database->simpleQuery("alter table " . TABLE_PRODUCTS . " ADD `is_subproduct` TINYINT( 1 ) NOT NULL DEFAULT '0'");
      // skip shipping
      $lC_Database->simpleQuery("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'SKIP_CHECKOUT_SHIPPING_PAGE'");
      $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES('Skip Shipping Page', 'SKIP_CHECKOUT_SHIPPING_PAGE', '-1', 'Bypass the checkout shipping page? No shipping will be charged.', 19, 0, NULL, now(), 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))')");

      $this->_clearCache();
    }
  }
 /**
  * Return the configuration parameter keys an an array
  *
  * @access public
  * @return array
  */
  public function getKeys() { 
    global $lC_Database;
    
    if (!isset($this->_keys)) {
      $this->_keys = array('ADDONS_SYSTEM_' . strtoupper($this->_code) . '_STATUS');
    }

    return $this->_keys;
  } 
 /**
  * Remove the addon
  *
  * @access public
  * @return void
  */
  public function remove() {
    global $lC_Database, $lC_Language;
    
    if ($this->hasKeys()) {
      $Qdel = $lC_Database->query('delete from :table_configuration where configuration_key in (":configuration_key")');
      $Qdel->bindTable(':table_configuration', TABLE_CONFIGURATION);
      $Qdel->bindRaw(':configuration_key', implode('", "', $this->getKeys()));
      $Qdel->execute();
    }

    if (file_exists(DIR_FS_CATALOG . 'addons/' . $this->_code . '/languages/' . $lC_Language->getCode() . '.xml')) {
      foreach ($lC_Language->extractAddonDefinitions(DIR_FS_CATALOG . 'addons/' . $this->_code . '/languages/' . $lC_Language->getCode() . '.xml') as $def) {
        $Qdel = $lC_Database->query('delete from :table_languages_definitions where definition_key = :definition_key and content_group = :content_group');
        $Qdel->bindTable(':table_languages_definitions', TABLE_LANGUAGES_DEFINITIONS);
        $Qdel->bindValue(':definition_key', $def['key']);
        $Qdel->bindValue(':content_group', $def['group']);
        $Qdel->execute();
      }
    }    
    // product classes
    $lC_Database->simpleQuery("DROP TABLE IF EXISTS `" . DB_TABLE_PREFIX . "product_classes`");
    $lC_Database->simpleQuery("alter table " . TABLE_PRODUCTS . " DROP COLUMN `is_subproduct`");
    $lC_Database->simpleQuery("delete from " . TABLE_TEMPLATES_BOXES . " where modules_group like '%Loaded_7_Pro%'");   
    $lC_Database->simpleQuery("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'DEFAULT_PRODUCT_CLASSES_ID'");
    $lC_Database->simpleQuery("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'SKIP_CHECKOUT_SHIPPING_PAGE'");
    
    $_SESSION['remove_loaded_7_pro'] = true;
        
    $this->_clearCache();
  }
 /**
  * Clear the cache
  *
  * @access public
  * @return void
  */
  private function _clearCache() {
    lC_Cache::clear('configuration');
    lC_Cache::clear('languages');
    lC_Cache::clear('addons');
    lC_Cache::clear('vqmoda');
  }
 /**
  * Check the addon install status
  *
  * @access public
  * @return void
  */
  private function _checkStatus() {
    $addons = '';
    if (file_exists('../includes/work/cache/addons.cache')) {
      $addons = @file_get_contents('../includes/work/cache/addons.cache');
    }

    return (strstr($addons, 'Loaded_7_Pro/controller.php') != '') ? true : false;
  }
 /**
  * Validate the serial is valid and active
  *
  * @access private
  * @return boolean
  */
  private function _validateSerial($serial) {
    $result = array();
    $validateArr = array('serial' => $serial,
                         'storeName' => STORE_NAME,
                         'storeEmail' => STORE_OWNER_EMAIL_ADDRESS,
                         'storeWWW' => HTTP_SERVER . DIR_WS_HTTP_CATALOG);
                         
    $checksum = hash('sha256', json_encode($validateArr));
    $validateArr['checksum'] = $checksum;
    
    $resultXML = transport::getResponse(array('url' => 'https://api.loadedcommerce.com/1_0/check/serial/?product=pro', 'method' => 'post', 'parameters' => $validateArr));  
    $result = utility::xml2arr($resultXML);
    
    if ($result['data']['error'] == '1') return false;
    
    return ($result['data']['valid'] == '1') ? true : false;    
  } 
  /**
  * Check to see if it's time to re-check addon validity
  *  
  * @access private      
  * @return boolean
  */   
  private function _timeToCheck() {
    global $lC_Database;

    $check = (defined('INSTALLATION_ID') && INSTALLATION_ID != '') ? INSTALLATION_ID : NULL;
    if ($check == NULL) return TRUE;
    
    $Qcheck = $lC_Database->query('select last_modified from :table_configuration where configuration_key = :configuration_key');
    $Qcheck->bindTable(':table_configuration', TABLE_CONFIGURATION);
    $Qcheck->bindValue(':configuration_key', 'INSTALLATION_ID');
    $Qcheck->execute();  
    
    $today = substr(lC_DateTime::getShort(date("Y-m-d H:m:s")), 3, 2);
    $check = substr(lC_DateTime::getShort($Qcheck->value('last_modified')), 3, 2);
    
    $Qcheck->freeResult();

    return (((int)$today != (int)$check) ? true : false);   
  } 
  /**
  * Update the time last checked the install ID
  *  
  * @access private      
  * @return void
  */   
  private function _updateLastChecked() {
    global $lC_Database;

    $Qcheck = $lC_Database->query('update :table_configuration set last_modified = :last_modified where configuration_key = :configuration_key');
    $Qcheck->bindTable(':table_configuration', TABLE_CONFIGURATION);
    $Qcheck->bindValue(':configuration_key', 'ADDONS_SYSTEM_' . strtoupper($this->_code) . '_STATUS');
    $Qcheck->bindRaw(':last_modified', 'now()');
    $Qcheck->execute();  
  }     
}
?>