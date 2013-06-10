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
class Money_Order extends lC_Addon {
  /*
  * Class constructor
  */
  public function Money_Order() {    
    global $lC_Language;    
   /**
    * The addon type (category)
    * valid types; payment, shipping, themes, checkout, catalog, admin, reports, connectors, other 
    */    
    $this->_type = 'payment';
   /**
    * The addon class name
    */    
    $this->_code = 'Money_Order';    
   /**
    * Inject the language definitions if they exist
    */ 
    if (file_exists(DIR_FS_CATALOG . 'addons/' . $this->_code . '/languages/' . $lC_Language->getCode() . '.xml')) {        
      $lC_Language->injectAddonDefinitions(DIR_FS_CATALOG . 'addons/' . $this->_code . '/languages/' . $lC_Language->getCode() . '.xml');
    }    
   /**
    * The addon title used in the addons store listing
    */     
    $this->_title = $lC_Language->get('addon_payment_moneyorder_title');
   /**
    * The addon description used in the addons store listing
    */     
    $this->_description = $lC_Language->get('addon_payment_moneyorder_description');
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
    $this->_thumbnail = '<img alt="" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAFAAAAA8CAYAAADxJz2MAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyJpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMy1jMDExIDY2LjE0NTY2MSwgMjAxMi8wMi8wNi0xNDo1NjoyNyAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNiAoV2luZG93cykiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6RTA2MzVDQzdEMTFCMTFFMkI0RUFDREIwRTBDMDBDODEiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6RTA2MzVDQzhEMTFCMTFFMkI0RUFDREIwRTBDMDBDODEiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDpFMDYzNUNDNUQxMUIxMUUyQjRFQUNEQjBFMEMwMEM4MSIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDpFMDYzNUNDNkQxMUIxMUUyQjRFQUNEQjBFMEMwMEM4MSIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/Pqz7eMMAABoqSURBVHja7FwHmFxl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" />';
   /**
    * The addon enable/disable switch
    */    
    $this->_enabled = (defined('ADDONS_PAYMENT_' . strtoupper($this->_code) . '_STATUS') && @constant('ADDONS_PAYMENT_' . strtoupper($this->_code) . '_STATUS') == '1') ? true : false;      
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
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Payable To', 'ADDONS_PAYMENT_" . strtoupper($this->_code) . "_PAYTO', '', 'Who should payments be made payable to?', '6', '1', now());");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Payment Zone', 'ADDONS_PAYMENT_" . strtoupper($this->_code) . "_ZONE', '0', 'If a zone is selected, only enable this payment method for that zone.', '6', '0', 'lc_cfg_use_get_zone_class_title', 'lc_cfg_set_zone_classes_pull_down_menu(class=\"select\",', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Order Status', 'ADDONS_PAYMENT_" . strtoupper($this->_code) . "_ORDER_STATUS_ID', '1', 'Set the status of orders made with this payment module to this value', '6', '0', 'lc_cfg_use_get_order_status_title', 'lc_cfg_set_order_statuses_pull_down_menu(class=\"select\",', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'ADDONS_PAYMENT_" . strtoupper($this->_code) . "_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
     
  }
 /**
  * Return the configuration parameter keys an an array
  *
  * @access public
  * @return array
  */
  public function getKeys() {
    if (!isset($this->_keys)) {
      $this->_keys = array('ADDONS_PAYMENT_' . strtoupper($this->_code) . '_STATUS',
                           'ADDONS_PAYMENT_' . strtoupper($this->_code) . '_PAYTO',
                           'ADDONS_PAYMENT_' . strtoupper($this->_code) . '_ZONE',
                           'ADDONS_PAYMENT_' . strtoupper($this->_code) . '_ORDER_STATUS_ID',
                           'ADDONS_PAYMENT_' . strtoupper($this->_code) . '_SORT_ORDER');      
    }

    return $this->_keys;
  }    
}
?>