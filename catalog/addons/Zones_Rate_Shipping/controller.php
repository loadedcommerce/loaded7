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
class Zones_Rate_Shipping extends lC_Addon {
  
  public $num_zones;
  /*
  * Class constructor
  */
  public function Zones_Rate_Shipping() {    
    global $lC_Language;    
   /**
    * The addon type (category)
    * valid types; payment, shipping, themes, checkout, catalog, admin, reports, connectors, other 
    */    
    $this->_type = 'shipping';
   /**
    * The addon class name
    */    
    $this->_code = 'Zones_Rate_Shipping';    
   /**
    * Inject the language definitions if they exist
    */ 
    if (file_exists(DIR_FS_CATALOG . 'addons/' . $this->_code . '/languages/' . $lC_Language->getCode() . '.xml')) {        
      $lC_Language->injectAddonDefinitions(DIR_FS_CATALOG . 'addons/' . $this->_code . '/languages/' . $lC_Language->getCode() . '.xml');
    }    
   /**
    * The addon title used in the addons store listing
    */     
    $this->_title = $lC_Language->get('addon_shipping_zones_title');
   /**
    * The addon description used in the addons store listing
    */     
    $this->_description = $lC_Language->get('addon_shipping_zones_description');
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
    $this->_thumbnail = '<img alt="" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAFAAAAA8CAYAAADxJz2MAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyJpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMy1jMDExIDY2LjE0NTY2MSwgMjAxMi8wMi8wNi0xNDo1NjoyNyAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNiAoV2luZG93cykiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6RjI0NjVGMDlEMDkzMTFFMjk0NjRGQkU3NTA5OEVGNTQiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6RjI0NjVGMEFEMDkzMTFFMjk0NjRGQkU3NTA5OEVGNTQiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDpGMjQ2NUYwN0QwOTMxMUUyOTQ2NEZCRTc1MDk4RUY1NCIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDpGMjQ2NUYwOEQwOTMxMUUyOTQ2NEZCRTc1MDk4RUY1NCIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/Psde+Q8AAB+hSURBVHja7HwHmB3V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" />';
   /**
    * The addon enable/disable switch
    */    
    $this->_enabled = (defined('ADDONS_SHIPPING_' . strtoupper($this->_code) . '_STATUS') && @constant('ADDONS_SHIPPING_' . strtoupper($this->_code) . '_STATUS') == '1') ? true : false;      
   /**
    * The number of zones needed; also needs to match the value in the module
    */     
    $this->num_zones = 1;    
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
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Tax Class', 'ADDONS_SHIPPING_" . strtoupper($this->_code) . "_TAX_CLASS', '0', 'Use the following tax class on the shipping fee.', '6', '0', 'lc_cfg_use_get_tax_class_title', 'lc_cfg_set_tax_classes_pull_down_menu(class=\"select\",', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'ADDONS_SHIPPING_" . strtoupper($this->_code) . "_SORT_ORDER', '0', 'Sort order of display.', '6', '0', now())");

    for ($i = 1; $i <= $this->num_zones; $i++) {
      $default_countries = '';

      if ($i == 1) {
        $default_countries = 'US,CA';
      }

      $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Zone " . $i ." Countries', 'ADDONS_SHIPPING_" . strtoupper($this->_code) . "_COUNTRIES_" . $i ."', '" . $default_countries . "', 'Comma separated list of two character ISO country codes that are part of Zone " . $i . ".', '6', '0', now())");
      $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Zone " . $i ." Shipping Table', 'ADDONS_SHIPPING_" . strtoupper($this->_code) . "_TABLE_" . $i ."', '3:8.50,7:10.50,99:20.00', 'Shipping rates to Zone " . $i . " destinations based on a group of maximum order weights. Example: 3:8.50,7:10.50,... Weights less than or equal to 3 would cost 8.50 for Zone " . $i . " destinations.', '6', '0', now())");
      $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Zone " . $i ." Handling Fee', 'ADDONS_SHIPPING_" . strtoupper($this->_code) . "_HANDLING_" . $i."', '0', 'Handling Fee for this shipping zone', '6', '0', now())");
      $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Module weight Unit', 'ADDONS_SHIPPING_" . strtoupper($this->_code) . "_WEIGHT_UNIT', '2', 'What unit of weight does this shipping module use?.', '6', '0', 'lC_Weight::getTitle', 'lc_cfg_set_weight_classes_pulldown_menu(class=\"select\",', now())");
    }
  }
 /**
  * Return the configuration parameter keys an an array
  *
  * @access public
  * @return array
  */
  public function getKeys() {
    if (!isset($this->_keys)) {
      if (!isset($this->_keys)) {
        $this->_keys = array('ADDONS_SHIPPING_' . strtoupper($this->_code) . '_STATUS',
                             'ADDONS_SHIPPING_' . strtoupper($this->_code) . '_TAX_CLASS',
                             'ADDONS_SHIPPING_' . strtoupper($this->_code) . '_SORT_ORDER',
                             'ADDONS_SHIPPING_' . strtoupper($this->_code) . '_WEIGHT_UNIT');

        for ($i=1; $i<=$this->num_zones; $i++) {
          $this->_keys[] = 'ADDONS_SHIPPING_' . strtoupper($this->_code) . '_COUNTRIES_' . $i;
          $this->_keys[] = 'ADDONS_SHIPPING_' . strtoupper($this->_code) . '_TABLE_' . $i;
          $this->_keys[] = 'ADDONS_SHIPPING_' . strtoupper($this->_code) . '_HANDLING_' . $i;
        }
      }      
    }

    return $this->_keys;
  }    
}
?>