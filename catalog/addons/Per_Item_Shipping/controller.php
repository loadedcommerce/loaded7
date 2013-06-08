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
class Per_Item_Shipping extends lC_Addon {
  /*
  * Class constructor
  */
  public function Per_Item_Shipping() {    
    global $lC_Language;    
   /**
    * The addon type (category)
    * valid types; payment, shipping, themes, checkout, catalog, admin, reports, connectors, other 
    */    
    $this->_type = 'shipping';
   /**
    * The addon class name
    */    
    $this->_code = 'Per_Item_Shipping';    
   /**
    * Inject the language definitions if they exist
    */ 
    if (file_exists(DIR_FS_CATALOG . 'addons/' . $this->_code . '/languages/' . $lC_Language->getCode() . '.xml')) {        
      $lC_Language->injectAddonDefinitions(DIR_FS_CATALOG . 'addons/' . $this->_code . '/languages/' . $lC_Language->getCode() . '.xml');
    }    
   /**
    * The addon title used in the addons store listing
    */     
    $this->_title = $lC_Language->get('addon_shipping_item_title');
   /**
    * The addon description used in the addons store listing
    */     
    $this->_description = $lC_Language->get('addon_shipping_item_description');
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
    $this->_thumbnail = '<img alt="" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAFAAAAA8CAYAAADxJz2MAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyJpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMy1jMDExIDY2LjE0NTY2MSwgMjAxMi8wMi8wNi0xNDo1NjoyNyAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNiAoV2luZG93cykiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6M0U0RUZCNDVDRUY1MTFFMkIzQTFCNDdBNTM0RjgwRjkiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6M0U0RUZCNDZDRUY1MTFFMkIzQTFCNDdBNTM0RjgwRjkiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDozRTRFRkI0M0NFRjUxMUUyQjNBMUI0N0E1MzRGODBGOSIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDozRTRFRkI0NENFRjUxMUUyQjNBMUI0N0E1MzRGODBGOSIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/PoSvOg8AAB+0SURBVHja7HwJlGTV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" />';
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
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Shipping Cost', 'ADDONS_SHIPPING_" . strtoupper($this->_code) . "_COST', '2.50', 'The shipping cost will be multiplied by the number of items in an order that uses this shipping method.', '6', '0', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Handling Fee', 'ADDONS_SHIPPING_" . strtoupper($this->_code) . "_HANDLING', '0', 'Handling fee for this shipping method.', '6', '0', now())");
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
                           'ADDONS_SHIPPING_' . strtoupper($this->_code) . '_HANDLING',
                           'ADDONS_SHIPPING_' . strtoupper($this->_code) . '_TAX_CLASS',
                           'ADDONS_SHIPPING_' . strtoupper($this->_code) . '_ZONE',
                           'ADDONS_SHIPPING_' . strtoupper($this->_code) . '_SORT_ORDER');      
    }

    return $this->_keys;
  }    
}
?>