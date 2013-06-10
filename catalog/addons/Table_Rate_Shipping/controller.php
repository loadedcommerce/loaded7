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
class Table_Rate_Shipping extends lC_Addon {
  /*
  * Class constructor
  */
  public function Table_Rate_Shipping() {    
    global $lC_Language;    
   /**
    * The addon type (category)
    * valid types; payment, shipping, themes, checkout, catalog, admin, reports, connectors, other 
    */    
    $this->_type = 'shipping';
   /**
    * The addon class name
    */    
    $this->_code = 'Table_Rate_Shipping';    
   /**
    * Inject the language definitions if they exist
    */ 
    if (file_exists(DIR_FS_CATALOG . 'addons/' . $this->_code . '/languages/' . $lC_Language->getCode() . '.xml')) {  
      $lC_Language->injectAddonDefinitions(DIR_FS_CATALOG . 'addons/' . $this->_code . '/languages/' . $lC_Language->getCode() . '.xml');
    }    
   /**
    * The addon title used in the addons store listing
    */     
    $this->_title = $lC_Language->get('addon_shipping_table_title');
   /**
    * The addon description used in the addons store listing
    */     
    $this->_description = $lC_Language->get('addon_shipping_table_description');
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
    $this->_thumbnail = '<img alt="" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAFAAAAA8CAYAAADxJz2MAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyJpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMy1jMDExIDY2LjE0NTY2MSwgMjAxMi8wMi8wNi0xNDo1NjoyNyAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNiAoV2luZG93cykiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6MDAxNjI3OTNEMDgzMTFFMjkyMUVCMUFDM0RGM0M3NkYiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6MDAxNjI3OTREMDgzMTFFMjkyMUVCMUFDM0RGM0M3NkYiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDowMDE2Mjc5MUQwODMxMUUyOTIxRUIxQUMzREYzQzc2RiIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDowMDE2Mjc5MkQwODMxMUUyOTIxRUIxQUMzREYzQzc2RiIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/Pg9SG+sAACMuSURBVHja7Hx3rGTn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" />';
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
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Shipping Table', 'ADDONS_SHIPPING_" . strtoupper($this->_code) . "_COST', '25:8.50,50:5.50,10000:0.00', 'The shipping cost is based on the total cost or weight of items. Example: 25:8.50,50:5.50,etc.. Up to 25 charge 8.50, from there to 50 charge 5.50, etc', '6', '0', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Table Method', 'ADDONS_SHIPPING_" . strtoupper($this->_code) . "_MODE', 'weight', 'The shipping cost is based on the order total or the total weight of the items ordered.', '6', '0', 'lc_cfg_set_boolean_value(array(\'weight\', \'price\'))', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Handling Fee', 'ADDONS_SHIPPING_" . strtoupper($this->_code) . "_HANDLING', '0', 'Handling fee for this shipping method.', '6', '0', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Tax Class', 'ADDONS_SHIPPING_" . strtoupper($this->_code) . "_TAX_CLASS', '0', 'Use the following tax class on the shipping fee.', '6', '0', 'lc_cfg_use_get_tax_class_title', 'lc_cfg_set_tax_classes_pull_down_menu(class=\"select\",', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Shipping Zone', 'ADDONS_SHIPPING_" . strtoupper($this->_code) . "_ZONE', '0', 'If a zone is selected, only enable this shipping method for that zone.', '6', '0', 'lc_cfg_use_get_zone_class_title', 'lc_cfg_set_zone_classes_pull_down_menu(class=\"select\",', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'ADDONS_SHIPPING_" . strtoupper($this->_code) . "_SORT_ORDER', '0', 'Sort order of display.', '6', '0', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Weight Unit', 'ADDONS_SHIPPING_" . strtoupper($this->_code) . "_WEIGHT_UNIT', '2', 'What unit of weight does this shipping module use?.', '6', '0', 'lC_Weight::getTitle', 'lc_cfg_set_weight_classes_pulldown_menu(class=\"select\",', now())");
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
                           'ADDONS_SHIPPING_' . strtoupper($this->_code) . '_MODE',
                           'ADDONS_SHIPPING_' . strtoupper($this->_code) . '_HANDLING',
                           'ADDONS_SHIPPING_' . strtoupper($this->_code) . '_TAX_CLASS',
                           'ADDONS_SHIPPING_' . strtoupper($this->_code) . '_ZONE',
                           'ADDONS_SHIPPING_' . strtoupper($this->_code) . '_SORT_ORDER',      
                           'ADDONS_SHIPPING_' . strtoupper($this->_code) . '_WEIGHT_UNIT');      
    }

    return $this->_keys;
  }    
}
?>