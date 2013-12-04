<?php
/**
  @package    catalog::addons
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: controller.php v1.0 2013-08-08 datazen $
*/       
class Pro_Pack_598 extends lC_Addon { // your addon must extend lC_Addon
  /*
  * Class constructor
  */
  public function Pro_Pack_598() {    
    global $lC_Language;    
   /**
    * The addon type (category)
    * valid types; payment, shipping, themes, checkout, catalog, admin, reports, connectors, other 
    */    
    $this->_type = 'pro template pack';
   /**
    * The addon class name
    */    
    $this->_code = 'Pro_Pack_598';       
   /**
    * The addon title used in the addons store listing
    */     
    $this->_title = $lC_Language->get('addon_pro_pack_598_title');
   /**
    * The addon description used in the addons store listing
    */     
    $this->_description = $lC_Language->get('addon_pro_pack_598_description');
   /**
    * The developers name
    */    
    $this->_author = 'Algozone, Inc.';
   /**
    * The developers web address
    */    
    $this->_authorWWW = 'http://www.algozone.com';    
   /**
    * The addon version
    */     
    $this->_version = '1.0.0';
   /**
    * The Loaded 7 core compatibility version
    */     
    $this->_compatibility = '7.0'; // the addon is compatible with this core version and later   
   /**
    * The addon image used in the addons store listing
    */     
    $this->_thumbnail = '<span onclick="showInfo(\'Pro_Pack_598\',\'LCAZ00598\', \'' . $this->_title . '\');">' . lc_image(DIR_WS_CATALOG . 'addons/' . $this->_code . '/images/LCAZ00598.png', null, 160, 120) . '</span>';
   /**
    * The addon enable/disable switch
    */    
    $this->_enabled = true;
    
    $this->_rating = '5';      
  }
 /**
  * Checks to see if the addon has been installed
  *
  * @access public
  * @return boolean
  */
  public function isInstalled() {
    return (bool)defined('ADDONS_PROPACK_' . strtoupper($this->_code) . '_STATUS');
  }
 /**
  * Install the addon
  *
  * @access public
  * @return void
  */
  public function install() {
    global $lC_Database;

  //  $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Enable AddOn', 'ADDONS_PROPACK_" . strtoupper($this->_code) . "_STATUS', '-1', 'Do you want to enable this addon?', '6', '0', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))', now())");
  //  $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Order Threshold', 'ADDONS_PROPACK_" . strtoupper($this->_code) . "_MINIMUM_ORDER', '20', 'The minimum order amount to apply free shipping to.', '6', '0', now())");
  //  $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Shipping Zone', 'ADDONS_PROPACK_" . strtoupper($this->_code) . "_ZONE', '0', 'If a zone is selected, only enable this shipping method for that zone.', '6', '0', 'lc_cfg_use_get_zone_class_title', 'lc_cfg_set_zone_classes_pull_down_menu', now())");
  }
 /**
  * Return the configuration parameter keys an an array
  *
  * @access public
  * @return array
  */
  public function getKeys() {
    if (!isset($this->_keys)) {
      $this->_keys = array('ADDONS_PROPACK_' . strtoupper($this->_code) . '_STATUS',
                           'ADDONS_PROPACK_' . strtoupper($this->_code) . '_MINIMUM_ORDER',
                           'ADDONS_PROPACK_' . strtoupper($this->_code) . '_ZONE');      
    }

    return $this->_keys;
  }  
}
?>