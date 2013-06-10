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
class Cash_On_Delivery extends lC_Addon {
  /*
  * Class constructor
  */
  public function Cash_On_Delivery() {    
    global $lC_Language;    
   /**
    * The addon type (category)
    * valid types; payment, shipping, themes, checkout, catalog, admin, reports, connectors, other 
    */    
    $this->_type = 'payment';
   /**
    * The addon class name
    */    
    $this->_code = 'Cash_On_Delivery';    
   /**
    * Inject the language definitions if they exist
    */ 
    if (file_exists(DIR_FS_CATALOG . 'addons/' . $this->_code . '/languages/' . $lC_Language->getCode() . '.xml')) {        
      $lC_Language->injectAddonDefinitions(DIR_FS_CATALOG . 'addons/' . $this->_code . '/languages/' . $lC_Language->getCode() . '.xml');
    }    
   /**
    * The addon title used in the addons store listing
    */     
    $this->_title = $lC_Language->get('addon_payment_cod_title');
   /**
    * The addon description used in the addons store listing
    */     
    $this->_description = $lC_Language->get('addon_payment_cod_description');
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
    $this->_thumbnail = '<img alt="" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAFAAAAA8CAYAAADxJz2MAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyJpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMy1jMDExIDY2LjE0NTY2MSwgMjAxMi8wMi8wNi0xNDo1NjoyNyAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNiAoV2luZG93cykiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6QUVBNzAxNzlEMTFCMTFFMkFBRjA4RDc0NUU3Njg0N0MiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6QUVBNzAxN0FEMTFCMTFFMkFBRjA4RDc0NUU3Njg0N0MiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDpBRUE3MDE3N0QxMUIxMUUyQUFGMDhENzQ1RTc2ODQ3QyIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDpBRUE3MDE3OEQxMUIxMUUyQUFGMDhENzQ1RTc2ODQ3QyIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/Plrvia0AAB9CSURBVHja7HwJcJvn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" />';
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
                           'ADDONS_PAYMENT_' . strtoupper($this->_code) . '_ZONE',
                           'ADDONS_PAYMENT_' . strtoupper($this->_code) . '_ORDER_STATUS_ID',
                           'ADDONS_PAYMENT_' . strtoupper($this->_code) . '_SORT_ORDER');      
    }

    return $this->_keys;
  }    
}
?>