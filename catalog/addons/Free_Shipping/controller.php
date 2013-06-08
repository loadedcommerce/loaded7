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
require_once(DIR_FS_CATALOG . 'addons/inc/addon.inc.php');

class Free_Shipping extends lC_Addon {
  /*
  * Class constructor
  */
  public function Free_Shipping() {    
    global $lC_Language;    
   /**
    * The addon type
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
    $this->_thumbnail = '<img alt="" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAFAAAAA8CAYAAADxJz2MAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyJpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMy1jMDExIDY2LjE0NTY2MSwgMjAxMi8wMi8wNi0xNDo1NjoyNyAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wTU09Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9tbS8iIHhtbG5zOnN0UmVmPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvc1R5cGUvUmVzb3VyY2VSZWYjIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6QzAzNkQ0MTBEMDYzMTFFMjk3RTE4NDg2NEZGRjUxOTQiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6QzAzNkQ0MEZEMDYzMTFFMjk3RTE4NDg2NEZGRjUxOTQiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNSBNYWNpbnRvc2giPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDpBQ0Q5MUI0RDMxQzAxMUUyQkJEM0E2RTQ1QzQ0MTI4QSIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDpBQ0Q5MUI0RTMxQzAxMUUyQkJEM0E2RTQ1QzQ0MTI4QSIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/PtHjZPMAACVYSURBVHja7HwHmF1l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" />';
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