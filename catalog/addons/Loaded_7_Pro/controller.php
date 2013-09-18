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
    $this->_type = 'payment';
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
  
  public function remove() {
    global $lC_Database;
    
    parent::remove();
    
    // product classes
    $lC_Database->simpleQuery("DROP TABLE IF EXISTS `" . DB_TABLE_PREFIX . "product_classes`");
  }   
}
?>