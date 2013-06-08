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
    $this->_thumbnail = '<img alt="" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAFAAAAA8CAYAAADxJz2MAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyJpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMy1jMDExIDY2LjE0NTY2MSwgMjAxMi8wMi8wNi0xNDo1NjoyNyAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNiAoV2luZG93cykiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6MkQzQjQyNkNEMDdCMTFFMkE5Q0NCQjcxNjdGQTJGQjUiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6MkQzQjQyNkREMDdCMTFFMkE5Q0NCQjcxNjdGQTJGQjUiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDoyRDNCNDI2QUQwN0IxMUUyQTlDQ0JCNzE2N0ZBMkZCNSIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDoyRDNCNDI2QkQwN0IxMUUyQTlDQ0JCNzE2N0ZBMkZCNSIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/PugXhk4AACqKSURBVHja5Hx5jGXp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" />';
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