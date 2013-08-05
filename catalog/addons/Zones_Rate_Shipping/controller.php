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
class Zones_Rate_Shipping extends lC_Addon { // your addon must extend lC_Addon
  
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
    * The addon title used in the addons store listing
    */     
    $this->_title = $lC_Language->get('addon_shipping_zones_title');
    /**
    * The addon blurb used in the addons store listing
    */     
    $this->_blurb = $lC_Language->get('addon_shipping_zones_blurb');   
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
    * The addon image used in the addons store listing
    */     
    $this->_thumbnail = lc_image(DIR_WS_CATALOG . 'addons/' . $this->_code . '/images/zones.png');
   /**
    * The addon enable/disable switch
    */    
    $this->_enabled = (defined('ADDONS_SHIPPING_' . strtoupper($this->_code) . '_STATUS') && @constant('ADDONS_SHIPPING_' . strtoupper($this->_code) . '_STATUS') == '1') ? true : false;  
    $this->_rating = '3'; 
   /**
    * The number of zones needed; also needs to match the value in the module
    */
    if (defined('ADDONS_SHIPPING_' . strtoupper($this->_code) . '_NUMBER_OF_ZONES')) {
      $this->num_zones = ADDONS_SHIPPING_ZONES_RATE_SHIPPING_NUMBER_OF_ZONES;
    } else {
      $this->num_zones = 1;
    } 
    
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
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Module weight Unit', 'ADDONS_SHIPPING_" . strtoupper($this->_code) . "_WEIGHT_UNIT', '2', 'What unit of weight does this shipping module use?.', '6', '0', 'lC_Weight::getTitle', 'lc_cfg_set_weight_classes_pulldown_menu', now())");
    
    if (!defined("ADDONS_SHIPPING_" . strtoupper($this->_code) . "_NUMBER_OF_ZONES")) {
      $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Zones Rate Shipping: Number of Zones', 'ADDONS_SHIPPING_" . strtoupper($this->_code) . "_NUMBER_OF_ZONES', '1', 'Set the number number of zones to use in the Zones Rate Shipping add-on.', '7', '0', now())");
    } 

    for ($i = 1; $i <= $this->num_zones; $i++) {
      $default_countries = '';

      if ($i == 1) {
        $default_countries = 'US,CA';
      }

      $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Zone " . $i ." Countries', 'ADDONS_SHIPPING_" . strtoupper($this->_code) . "_COUNTRIES_" . $i ."', '" . $default_countries . "', 'Comma separated list of two character ISO country codes that are part of Zone " . $i . ".', '6', '0', now())");
      $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Zone " . $i ." Shipping Table', 'ADDONS_SHIPPING_" . strtoupper($this->_code) . "_TABLE_" . $i ."', '3:8.50,7:10.50,99:20.00', 'Shipping rates to Zone " . $i . " destinations based on a group of maximum order weights. Example: 3:8.50,7:10.50,... Weights less than or equal to 3 would cost 8.50 for Zone " . $i . " destinations.', '6', '0', now())");
      $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Zone " . $i ." Handling Fee', 'ADDONS_SHIPPING_" . strtoupper($this->_code) . "_HANDLING_" . $i."', '0', 'Handling Fee for this shipping zone', '6', '0', now())");
      
    }
  }
 /**
  * Return the configuration parameter keys an an array
  *
  * @access public
  * @return array
  */
  public function getKeys() {
    global $lC_Database;
    
    if (!isset($this->_keys)) {
      if (!isset($this->_keys)) {
        $this->_keys = array('ADDONS_SHIPPING_' . strtoupper($this->_code) . '_STATUS',
                             'ADDONS_SHIPPING_' . strtoupper($this->_code) . '_TAX_CLASS',
                             'ADDONS_SHIPPING_' . strtoupper($this->_code) . '_SORT_ORDER',
                             'ADDONS_SHIPPING_' . strtoupper($this->_code) . '_WEIGHT_UNIT');

        for ($i=1; $i<=$this->num_zones; $i++) {

          if(!defined('ADDONS_SHIPPING_' . strtoupper($this->_code) . '_COUNTRIES_'.$i)){ 

            $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Zone " . $i ." Countries', 'ADDONS_SHIPPING_" . strtoupper($this->_code) . "_COUNTRIES_" . $i ."', '" . $default_countries . "', 'Comma separated list of two character ISO country codes that are part of Zone " . $i . ".', '6', '0', now())");
            $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Zone " . $i ." Shipping Table', 'ADDONS_SHIPPING_" . strtoupper($this->_code) . "_TABLE_" . $i ."', '3:8.50,7:10.50,99:20.00', 'Shipping rates to Zone " . $i . " destinations based on a group of maximum order weights. Example: 3:8.50,7:10.50,... Weights less than or equal to 3 would cost 8.50 for Zone " . $i . " destinations.', '6', '0', now())");
            $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Zone " . $i ." Handling Fee', 'ADDONS_SHIPPING_" . strtoupper($this->_code) . "_HANDLING_" . $i."', '0', 'Handling Fee for this shipping zone', '6', '0', now())");
          }

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