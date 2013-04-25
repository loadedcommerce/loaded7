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
    * The addon title used in the addons store listing
    */     
    $this->_title = 'PayPal Payments';
   /**
    * The addon description used in the addons store listing
    */     
    $this->_description = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus diam. Sed nisi. Nulla quis sem at nibh elementum imperdiet. Duis sagittis ipsum. Praesent mauris. Fusce nec tellus sed augue semper porta. Mauris massa. Vestibulum lacinia arcu eget nulla. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Curabitur sodales ligula in libero. Sed dignissim lacinia nunc. Curabitur tortor. Pellentesque nibh. Aenean quam. In scelerisque sem at dolor. Maecenas mattis. Sed convallis tristique sem. Proin ut ligula vel nunc egestas porttitor. Morbi lectus risus, iaculis vel, suscipit quis, luctus non, massa. Fusce ac turpis quis ligula lacinia aliquet. Mauris ipsum. Nulla metus metus, ullamcorper vel, tincidunt sed, euismod in, nibh. Quisque volutpat condimentum velit. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Nam nec ante. Sed lacinia, urna non tincidunt mattis, tortor neque adipiscing diam, a cursus ipsum ante quis turpis. Nulla facilisi. Ut fringilla. Suspendisse potenti. Nunc feugiat mi a tellus consequat imperdiet. Vestibulum sapien. Proin quam. Etiam ultrices. Suspendisse in justo eu magna luctus suscipit. Sed lectus.';
   /**
    * The developers name
    */    
    $this->_author = 'PayPal, Inc.';
   /**
    * The addon version
    */     
    $this->_version = '1.01';
   /**
    * The base64 encoded addon image used in the addons store listing
    */     
    $this->_thumbnail = '<img alt="" src="data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD/2wCEAAkGBwgTEgkTEggKFBQRDRYYGRYYFB8UIRcZHR0fHxwdHx8kICgsJCMlHCYcJj0iKSkrOjA6HB82ODMsNy05PS4BCgoKDQwNGg8PGjgkHCY3NzcwNTc0NzQ0Nyw3NTQ2MDc0KzgrNDcsNTYtNzMyNDM3NzQzNDQrNyw4NDg0MiwyNP/AABEIADoATgMBIgACEQEDEQH/xAAbAAACAwEBAQAAAAAAAAAAAAAABAIFBwYDAf/EADsQAAECBAEGCwcDBQAAAAAAAAECAwAEBRESBhMhMVORFlJhcXOBk6Gx0dIHFDNBRFGyIiMyFSRCY8H/xAAXAQEBAQEAAAAAAAAAAAAAAAAAAgED/8QAHhEAAgMAAwADAAAAAAAAAAAAAAECAxESIVEEMVL/2gAMAwEAAhEDEQA/ANtWs3sBp8I+4VbQ7hHjJquZg/7bdQAhmAIYTtFd3lBhO0V3eUTggCGE7RXd5QYTtFd3lE4IAhhO0V3eUGE7RXd5ROCAIEL+Sr88Dawb8msROFcdnVj7tA9dyIA+U/6npz4CPKrVumSwbMxOstYycOI2vbXHrT/qenPgIyr2kzgcq1Ma/pz00mWZxKZQnEV4jci3MBF1w5PCZy4o0+l1ylTGLMT8u4QNISq5HVFjGItyz7ExO1ByUVSmltFplAbxrUtVv4ti2nnsIYpVYygFQpTCalXSmYviTNtobJThJKkgE2Gi/wAo6OnxkKz02aEalWKcwWw9ONNlQNsR121xj8nl3WEyU3LCafdnnaiWWidKkoNtI67pHKb/ACh6aTMJnZFp5man1SkskOAfuFayMStY/iCUi3JFR+P32TO7F0apT6rIP4szNsuW14Te0ORk7LDzDk7NOIXIB/8AQy0hGcWSbXwo0W5zbSYYptZqzc4hBnKmWwwta0zKUpNgkkEAE202g6PyzFf6jQBXKXiUn35m4cCCL6lHQBzkxNXxj0A/IxzHsvlv7aYeUkXfmVK6hoEdOr4x6AfkY42RUZNI6wk5RTZKn/U9OfARXSeS8g3OTc6FPF55IBuq4AsBoFtGgRYU86ZofZ8+AhyJTa+isKLKzJeSn0ModceSWnMaVINiDCdNyLp0vMCb95qLzyGSm7jmcNrabC2vk5Y6mCNU5JZo4rdMx9nuSL/vlRn5mQWz+8vMtL1jFrXuNhzqjuKbk/JsvzswlTpcmD+rEbgab2Gjm3RbQRsrJSZigkU2UWTsrN+743HkKaVdKkGxEJt5F04KmlGZnVLeZzalKcxGx12JEdLBBWSSxMxwi3rQnSacxLtMst4sLabC+k9cfFfGPQD8jDsJHS+rkYH5GJb16y0s6PKcRMNrLrbeIEWWjVe2ojlEQGUNP/yL6T9i0s+AI74tYiUI4id0YCs4RUvbO9k56YOEVL2zvZOemLLNt8RO6DNt8RO6AK3hFS9s72Tnpg4RUvbO9k56Yss23xE7oM23xE7oAreEVL2zvZOemDhFS9s72TnpiyzbfETugzbfETugCsVXpc6Gmn3FHUMCkDrKgP8AsM06XdTjUsgrcNzb5fYDkEOBKRqSI+wB/9k=" />';
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