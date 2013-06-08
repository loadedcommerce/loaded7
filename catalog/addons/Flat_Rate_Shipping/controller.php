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
class Flat_Rate_Shipping extends lC_Addon {
  /*
  * Class constructor
  */
  public function Flat_Rate_Shipping() {    
    global $lC_Language;    
   /**
    * The addon type (category)
    * valid types; payment, shipping, themes, checkout, catalog, admin, reports, connectors, other 
    */    
    $this->_type = 'shipping';
   /**
    * The addon class name
    */    
    $this->_code = 'Flat_Rate_Shipping';    
   /**
    * Inject the language definitions if they exist
    */ 
    if (file_exists(DIR_FS_CATALOG . 'addons/' . $this->_code . '/languages/' . $lC_Language->getCode() . '.xml')) {        
      $lC_Language->injectAddonDefinitions(DIR_FS_CATALOG . 'addons/' . $this->_code . '/languages/' . $lC_Language->getCode() . '.xml');
    }    
   /**
    * The addon title used in the addons store listing
    */     
    $this->_title = $lC_Language->get('addon_shipping_flat_title');
   /**
    * The addon description used in the addons store listing
    */     
    $this->_description = $lC_Language->get('addon_shipping_flat_description');
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
    $this->_thumbnail = '<img alt="" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAFAAAAA8CAYAAADxJz2MAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyJpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMy1jMDExIDY2LjE0NTY2MSwgMjAxMi8wMi8wNi0xNDo1NjoyNyAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNiAoV2luZG93cykiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6OUMwNzkwNTJEMDdEMTFFMkI3RThEMzlCNjM5QUY4NzAiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6OUMwNzkwNTNEMDdEMTFFMkI3RThEMzlCNjM5QUY4NzAiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDo5QzA3OTA1MEQwN0QxMUUyQjdFOEQzOUI2MzlBRjg3MCIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDo5QzA3OTA1MUQwN0QxMUUyQjdFOEQzOUI2MzlBRjg3MCIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/PtfTskoAAA45SURBVHja7FtLjxxX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" />';
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
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Shipping Cost', 'ADDONS_SHIPPING_" . strtoupper($this->_code) . "_COST', '5.00', 'The shipping cost for all orders using this shipping method.', '6', '0', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Tax Class', 'ADDONS_SHIPPING_" . strtoupper($this->_code) . "_TAX_CLASS', '0', 'Use the following tax class on the shipping fee.', '6', '0', 'lc_cfg_use_get_tax_class_title', 'lc_cfg_set_tax_classes_pull_down_menu(class=\"select\",', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Shipping Zone', 'ADDONS_SHIPPING_" . strtoupper($this->_code) . "_ZONE', '0', 'If a zone is selected, only enable this shipping method for that zone.', '6', '0', 'lc_cfg_use_get_zone_class_title', 'lc_cfg_set_zone_classes_pull_down_menu(class=\"select\",', now())");
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
                           'ADDONS_SHIPPING_' . strtoupper($this->_code) . '_COST',
                           'ADDONS_SHIPPING_' . strtoupper($this->_code) . '_TAX_CLASS',
                           'ADDONS_SHIPPING_' . strtoupper($this->_code) . '_ZONE',
                           'ADDONS_SHIPPING_' . strtoupper($this->_code) . '_SORT_ORDER');      
    }

    return $this->_keys;
  }    
}
?>