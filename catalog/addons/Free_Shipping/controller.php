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
class Free_Shipping extends lC_Addon {
  /*
  * Class constructor
  */
  public function Free_Shipping() {    
    global $lC_Language;    
   /**
    * The addon type (category)
    * valid types; payment, shipping, themes, checkout, catalog, admin, reports, connectors, other 
    */    
    $this->_type = 'shipping';
   /**
    * The addon class name
    */    
    $this->_code = 'Free_Shipping';    
   /**
    * Inject the language definitions if they exist
    */ 
    if (file_exists(DIR_FS_CATALOG . 'addons/' . $this->_code . '/languages/' . $lC_Language->getCode() . '.xml')) {        
      $lC_Language->injectAddonDefinitions(DIR_FS_CATALOG . 'addons/' . $this->_code . '/languages/' . $lC_Language->getCode() . '.xml');
    }    
   /**
    * The addon title used in the addons store listing
    */     
    $this->_title = $lC_Language->get('addon_shipping_free_title');
   /**
    * The addon description used in the addons store listing
    */     
    $this->_description = $lC_Language->get('addon_shipping_free_description');
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
    $this->_thumbnail = '<img alt="" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAFAAAAA8CAYAAADxJz2MAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyJpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMy1jMDExIDY2LjE0NTY2MSwgMjAxMi8wMi8wNi0xNDo1NjoyNyAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNiAoV2luZG93cykiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6NUMzOUUxMjlEMDg1MTFFMkI5MjlFMDc2NDQzRTYyNzEiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6NUMzOUUxMkFEMDg1MTFFMkI5MjlFMDc2NDQzRTYyNzEiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDo1QzM5RTEyN0QwODUxMUUyQjkyOUUwNzY0NDNFNjI3MSIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDo1QzM5RTEyOEQwODUxMUUyQjkyOUUwNzY0NDNFNjI3MSIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/Pn5cpd4AABmXSURBVHja7Fx3eFzV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" />';
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
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Shipping Cost', 'ADDONS_SHIPPING_" . strtoupper($this->_code) . "_MINIMUM_ORDER', '20', 'The minimum order amount to apply free shipping to.', '6', '0', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Shipping Zone', 'ADDONS_SHIPPING_" . strtoupper($this->_code) . "_ZONE', '0', 'If a zone is selected, only enable this shipping method for that zone.', '6', '0', 'lc_cfg_use_get_zone_class_title', 'lc_cfg_set_zone_classes_pull_down_menu(class=\"select\",', now())");
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
                           'ADDONS_SHIPPING_' . strtoupper($this->_code) . '_MINIMUM_ORDER',
                           'ADDONS_SHIPPING_' . strtoupper($this->_code) . '_ZONE');      
    }

    return $this->_keys;
  }  
}
?>