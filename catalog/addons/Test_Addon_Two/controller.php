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

class Test_Addon_Two extends lC_Addons_Bootstrap {
  /*
  * Class constructor
  */
  public function Test_Addon_Two() {
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
    $this->_code = 'Test_Addon_Two';    
   /**
    * The addon title used in the addons store listing
    */     
    $this->_title = 'Test Add-On Two';
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
    $this->_version = '2.331';
   /**
    * The base64 encoded addon image used in the addons store listing
    */     
    $this->_thumbnail = '<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAFAAAAA8CAIAAAB+RarbAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyJpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMy1jMDExIDY2LjE0NTY2MSwgMjAxMi8wMi8wNi0xNDo1NjoyNyAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNiAoV2luZG93cykiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6MjBBQTlCMzlBREVEMTFFMjlBRTlGRDY0N0ZENjY5QTAiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6MjBBQTlCM0FBREVEMTFFMjlBRTlGRDY0N0ZENjY5QTAiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDoyMEFBOUIzN0FERUQxMUUyOUFFOUZENjQ3RkQ2NjlBMCIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDoyMEFBOUIzOEFERUQxMUUyOUFFOUZENjQ3RkQ2NjlBMCIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/PuXIdtEAAAHZSURBVHja7NlNSwJBGAdwV1NoDYXK2oKgzE4WFAjmpbzUdS/esmtZfYC6eK9PoaHdhDp5867QKRMi1lgIOmQv9KIGYdvUwqitHmer8f8cVmf08lvneZ5xVrAKVksvRW9pSfTpL4KmcU/VBKEJJnFq4dkcsAg9uqQBBhhggAEGGGCAAQYYYIABBrjtAIBp9Eciblm2j0kOSdJn6opSzeef9g9MBgv6IZ6gaYxOPAjVe5i0iWLHT98rFXV7p57JsHYGCPH7iIf5krYNDXbTkrB7POR2OJaX+MzhN1Wtlkrk2nZHRHE0HucK3KjVbtNHF+Hw5ZS3PDtHrlexGJmkX3CFQvzksM3n+zIryo/58ZPjYVmmwzPGzwNoDjOv0kaqHh8vr61LgP8+7FyYp++rxSLn4IHNDaffT4d3iQQ/OWwM0oRmslnaq54LBXWRedEyrw8btZPJ5j6EtKjr6DqHW8uOuy6iLa+sdqtq/z6H3Xu7v6417xeWUqmR6BodPuZyN7Et87UmbTwm0ilXMEhnSJUyluXG/QPTvxC0aDEHT58XWztQtyB7bLLr5LBK48SDuxz+I4ElDTDAAAMMMMAAAwwwwAADDDDAAHMczSMe7qltz4f1AZY0h/EpwAAFpKK3n0NyYwAAAABJRU5ErkJggg==">';
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
    }

    return $this->_keys;
  }  

}
?>