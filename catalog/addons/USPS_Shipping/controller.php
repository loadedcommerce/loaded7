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
class USPS_Shipping extends lC_Addon {
  /*
  * Class constructor
  */
  public function USPS_Shipping() {    
    global $lC_Language;    
   /**
    * The addon type (category)
    * valid types; payment, shipping, themes, checkout, catalog, admin, reports, connectors, other 
    */    
    $this->_type = 'shipping';
   /**
    * The addon class name
    */    
    $this->_code = 'USPS_Shipping';    
   /**
    * Inject the language definitions if they exist
    */ 
    if (file_exists(DIR_FS_CATALOG . 'addons/' . $this->_code . '/languages/' . $lC_Language->getCode() . '.xml')) {        
      $lC_Language->injectAddonDefinitions(DIR_FS_CATALOG . 'addons/' . $this->_code . '/languages/' . $lC_Language->getCode() . '.xml');
    }    
   /**
    * The addon title used in the addons store listing
    */     
    $this->_title = $lC_Language->get('addon_shipping_usps_title');
   /**
    * The addon description used in the addons store listing
    */     
    $this->_description = $lC_Language->get('addon_shipping_usps_description');
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
    $this->_thumbnail = '<img alt="" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAFAAAAA8CAYAAADxJz2MAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyJpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMy1jMDExIDY2LjE0NTY2MSwgMjAxMi8wMi8wNi0xNDo1NjoyNyAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNiAoV2luZG93cykiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6QTg4NzZEOTNEMDlGMTFFMkJEQUJEMkIzMzYwNzYyOTYiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6QTg4NzZEOTREMDlGMTFFMkJEQUJEMkIzMzYwNzYyOTYiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDpBODg3NkQ5MUQwOUYxMUUyQkRBQkQyQjMzNjA3NjI5NiIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDpBODg3NkQ5MkQwOUYxMUUyQkRBQkQyQjMzNjA3NjI5NiIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/PnQGTvMAABkaSURBVHja7FwJdFXV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" />';
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
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Username', 'ADDONS_SHIPPING_" . strtoupper($this->_code) . "_USERID', 'NONE', 'Enter the USPS USERID assigned to you.', '6', '0', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Password', 'ADDONS_SHIPPING_" . strtoupper($this->_code) . "_PASSWORD', 'NONE', 'See USERID, above.', '6', '0', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('API Server', 'ADDONS_SHIPPING_" . strtoupper($this->_code) . "_SERVER', 'Production', 'An account at USPS is needed to use the Production server', '6', '0', 'lc_cfg_set_boolean_value(array(\'Test\', \'Production\'))', now())");
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
                           'ADDONS_SHIPPING_' . strtoupper($this->_code) . '_USERID',
                           'ADDONS_SHIPPING_' . strtoupper($this->_code) . '_PASSWORD',
                           'ADDONS_SHIPPING_' . strtoupper($this->_code) . '_SERVER',
                           'ADDONS_SHIPPING_' . strtoupper($this->_code) . '_HANDLING',
                           'ADDONS_SHIPPING_' . strtoupper($this->_code) . '_TAX_CLASS',
                           'ADDONS_SHIPPING_' . strtoupper($this->_code) . '_ZONE',
                           'ADDONS_SHIPPING_' . strtoupper($this->_code) . '_SORT_ORDER');
    }

    return $this->_keys;
  }    
}
?>