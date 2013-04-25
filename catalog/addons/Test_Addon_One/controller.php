<?php
/*
  $Id: controller.php v1.0 2013-04-20 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
require_once(DIR_FS_CATALOG . 'addons/inc/bootstrap.php');

class Test_Addon_One extends lC_Addons_Bootstrap {
  /*
  * Class constructor
  */
  public function Test_Addon_One() {
   /**
    * The addon enable/disable switch
    */    
    $this->_enabled = (defined('MODULE_ADDONS_' . strtoupper(__CLASS__) . '_STATUS') && @constant('MODULE_ADDONS_' . strtoupper(__CLASS__) . '_STATUS') == '1') ? true : false;    
   /**
    * The addon type
    */    
    $this->_type = 'payment';
   /**
    * The addon class name
    */    
    $this->_code = 'Test_Addon_One';    
   /**
    * The addon title used in the addons store listing
    */     
    $this->_title = 'Loaded Payments';
   /**
    * The addon description used in the addons store listing
    */     
    $this->_description = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus diam. Sed nisi. Nulla quis sem at nibh elementum imperdiet. Duis sagittis ipsum. Praesent mauris. Fusce nec tellus sed augue semper porta. Mauris massa. Vestibulum lacinia arcu eget nulla. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Curabitur sodales ligula in libero. Sed dignissim lacinia nunc. Curabitur tortor. Pellentesque nibh. Aenean quam. In scelerisque sem at dolor. Maecenas mattis. Sed convallis tristique sem. Proin ut ligula vel nunc egestas porttitor. Morbi lectus risus, iaculis vel, suscipit quis, luctus non, massa. Fusce ac turpis quis ligula lacinia aliquet. Mauris ipsum. Nulla metus metus, ullamcorper vel, tincidunt sed, euismod in, nibh. Quisque volutpat condimentum velit. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Nam nec ante. Sed lacinia, urna non tincidunt mattis, tortor neque adipiscing diam, a cursus ipsum ante quis turpis. Nulla facilisi. Ut fringilla. Suspendisse potenti. Nunc feugiat mi a tellus consequat imperdiet. Vestibulum sapien. Proin quam. Etiam ultrices. Suspendisse in justo eu magna luctus suscipit. Sed lectus.';
   /**
    * The developers name
    */    
    $this->_author = 'Loaded Commerce, LLC';
   /**
    * The addon version
    */     
    $this->_version = '2.21';
   /**
    * The base64 encoded addon image used in the addons store listing
    */     
    $this->_thumbnail = '<img alt="" src="data:image/png;base64,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" />';
  }
 /**
  * Checks to see if the addon has been installed
  *
  * @access public
  * @return boolean
  */
  public function isInstalled() {
    return (bool)defined('MODULE_ADDONS_' . strtoupper(__CLASS__) . '_STATUS');
  }
 /**
  * Install the addon
  *
  * @access public
  * @return void
  */
  public function install() {
    global $lC_Database;

    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Enable AddOn', 'MODULE_ADDONS_" . strtoupper(__CLASS__) . "_STATUS', '-1', 'Do you want to enable this addon?', '6', '0', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))', now())");
    //$lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Payment Zone', 'MODULE_PAYMENT_COD_ZONE', '0', 'If a zone is selected, only enable this payment method for that zone.', '6', '0', 'lc_cfg_use_get_zone_class_title', 'lc_cfg_set_zone_classes_pull_down_menu', now())");
    //$lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Set Order Status', 'MODULE_PAYMENT_COD_ORDER_STATUS_ID', '1', 'Set the status of orders made with this payment module to this value', '6', '0', 'lc_cfg_set_order_statuses_pull_down_menu', 'lc_cfg_use_get_order_status_title', now())");
    //$lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_PAYMENT_COD_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
  }
 /**
  * Return the configuration parameter keys in an array
  *
  * @access public
  * @return array
  */
  public function getKeys() {
    if (!isset($this->_keys)) {
      $this->_keys = array('MODULE_ADDONS_' . strtoupper(__CLASS__) . '_STATUS');
      //                     'MODULE_PAYMENT_COD_ZONE',
      //                     'MODULE_PAYMENT_COD_ORDER_STATUS_ID',
      //                     'MODULE_PAYMENT_COD_SORT_ORDER');
    }

    return $this->_keys;
  }  

}
?>