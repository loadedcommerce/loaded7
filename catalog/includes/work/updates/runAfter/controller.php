<?php
/**
  @package    catalog::updates
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: controller.php v1.0 2013-08-08 datazen $
*/ 
error_reporting(0);
require_once('includes/applications/updates/classes/updates.php'); 
 
class lC_Updates_Admin_run_after extends lC_Updates_Admin {

  public static function process() {
    parent::log('##### RUNAFTER PROCESS STARTED');

    self::updateDB();

    parent::log('##### RUNAFTER PROCESS COMPLETE');
  } 
  
  public static function updateDB() {
    global $lC_Database;
    
    $pf = (defined('DB_TABLE_PREFIX')) ? DB_TABLE_PREFIX : 'lc_';
    $engine = (defined('DB_DATABASE_CLASS') && DB_DATABASE_CLASS == 'mysqli_innodb') ? 'InnoDB' : 'MyISAM';
    
    $lC_Database->simpleQuery("ALTER IGNORE TABLE `" . $pf . "banners` ADD `banners_target` INT( 1 ) NOT NULL DEFAULT '1' AFTER `banners_url`");
    parent::log("Database Update: ALTER IGNORE TABLE `" . $pf . "banners` ADD `banners_target` INT( 1 ) NOT NULL DEFAULT '1' AFTER `banners_url`");
    
    $lC_Database->simpleQuery("ALTER IGNORE TABLE `" . $pf . "products` ADD `products_cost` DECIMAL( 15, 4 ) NOT NULL DEFAULT '0' AFTER `products_quantity`");
    parent::log("Database Update: ALTER IGNORE TABLE `" . $pf . "products` ADD `products_cost` DECIMAL( 15, 4 ) NOT NULL DEFAULT '0' AFTER `products_quantity`");
    $lC_Database->simpleQuery("ALTER IGNORE TABLE `" . $pf . "products` ADD `products_msrp` DECIMAL( 15, 4 ) NOT NULL DEFAULT '0' AFTER `products_price`");
    parent::log("Database Update: ALTER IGNORE TABLE `" . $pf . "products` ADD `products_msrp` DECIMAL( 15, 4 ) NOT NULL DEFAULT '0' AFTER `products_price`");    
    $lC_Database->simpleQuery("ALTER IGNORE TABLE `" . $pf . "products` ADD `products_sku` VARCHAR( 255 ) NOT NULL AFTER `products_model`");
    parent::log("Database Update: ALTER IGNORE TABLE `" . $pf . "products` ADD `products_sku` VARCHAR( 255 ) NOT NULL AFTER `products_model`");  
        
    $lC_Database->simpleQuery("ALTER IGNORE TABLE `" . $pf . "products_simple_options_values` ADD `products_id` INT( 11 ) NOT NULL AFTER `id`");
    parent::log("Database Update: ALTER IGNORE TABLE `" . $pf . "products_simple_options_values` ADD `products_id` INT( 11 ) NOT NULL AFTER `id`");  
    $lC_Database->simpleQuery("ALTER IGNORE TABLE `" . $pf . "products_simple_options_values` ADD `sort_order` INT( 11 ) NOT NULL DEFAULT '0' AFTER `price_modifier`");
    parent::log("Database Update: ALTER IGNORE TABLE `" . $pf . "products_simple_options_values` ADD `sort_order` INT( 11 ) NOT NULL DEFAULT '0' AFTER `price_modifier`");
    
    $lC_Database->simpleQuery("ALTER IGNORE TABLE `" . $pf . "orders_products` ADD `products_simple_options_meta_data` VARCHAR( 1024 ) NOT NULL AFTER `products_quantity`");
    parent::log("Database Update: ALTER IGNORE TABLE `" . $pf . "orders_products` ADD `products_simple_options_meta_data` VARCHAR( 1024 ) NOT NULL AFTER `products_quantity`");  
    
    $lC_Database->simpleQuery("ALTER IGNORE TABLE `" . $pf . "shopping_carts` ADD `meta_data` VARCHAR( 1024 ) NOT NULL AFTER `quantity`");
    parent::log("Database Update: ALTER IGNORE TABLE `" . $pf . "shopping_carts` ADD `meta_data` VARCHAR( 1024 ) NOT NULL AFTER `quantity`");        
    
    $lC_Database->simpleQuery("ALTER IGNORE TABLE `" . $pf . "categories` ADD `categories_mode` VARCHAR( 128 ) DEFAULT NULL AFTER `sort_order`");
    parent::log("Database Update: ALTER IGNORE TABLE `" . $pf . "categories` ADD `categories_mode` VARCHAR( 128 ) DEFAULT NULL AFTER `sort_order`");         
    $lC_Database->simpleQuery("ALTER IGNORE TABLE `" . $pf . "categories` ADD `categories_link_target` TINYINT( 1 ) DEFAULT '0' AFTER `categories_mode`");
    parent::log("Database Update: ALTER IGNORE TABLE `" . $pf . "categories` ADD `categories_link_target` TINYINT( 1 ) DEFAULT '0' AFTER `categories_mode`");        
    $lC_Database->simpleQuery("ALTER IGNORE TABLE `" . $pf . "categories` ADD `categories_custom_url` VARCHAR( 255 ) DEFAULT NULL AFTER `categories_link_target`");
    parent::log("Database Update: ALTER IGNORE TABLE `" . $pf . "categories` ADD `categories_custom_url` VARCHAR( 255 ) DEFAULT NULL AFTER `categories_link_target`");      
    $lC_Database->simpleQuery("ALTER IGNORE TABLE `" . $pf . "categories` ADD `categories_status` TINYINT( 1 ) DEFAULT NULL AFTER `categories_show_in_listings`");
    parent::log("Database Update: ALTER IGNORE TABLE `" . $pf . "categories` ADD `categories_status` TINYINT( 1 ) DEFAULT NULL AFTER `categories_show_in_listings`");
    $lC_Database->simpleQuery("ALTER IGNORE TABLE `" . $pf . "categories` ADD `categories_visibility_nav` TINYINT( 1 ) DEFAULT '0' AFTER `categories_status`");
    parent::log("Database Update: ALTER IGNORE TABLE `" . $pf . "categories` ADD `categories_visibility_nav` TINYINT( 1 ) DEFAULT '0' AFTER `categories_status`");
    $lC_Database->simpleQuery("ALTER IGNORE TABLE `" . $pf . "categories` ADD `categories_visibility_box` TINYINT( 1 ) DEFAULT '1' AFTER `categories_visibility_nav`");
    parent::log("Database Update: ALTER IGNORE TABLE `" . $pf . "categories` ADD `categories_visibility_box` TINYINT( 1 ) DEFAULT '1' AFTER `categories_visibility_nav`");
        
    $lC_Database->simpleQuery("ALTER IGNORE TABLE `" . $pf . "categories_description` ADD `categories_menu_name` VARCHAR( 255 ) DEFAULT NULL AFTER `categories_name`");
    parent::log("Database Update: ALTER IGNORE TABLE `" . $pf . "categories_description` ADD `categories_menu_name` VARCHAR( 255 ) DEFAULT NULL AFTER `categories_name`");     
    $lC_Database->simpleQuery("ALTER IGNORE TABLE `" . $pf . "categories_description` ADD `categories_blurb ` VARCHAR( 1024 ) DEFAULT NULL AFTER `categories_menu_name`");
    parent::log("Database Update: ALTER IGNORE TABLE `" . $pf . "categories_description` ADD `categories_blurb ` VARCHAR( 1024 ) DEFAULT NULL AFTER `categories_menu_name`");
    $lC_Database->simpleQuery("ALTER IGNORE TABLE `" . $pf . "categories_description` ADD `categories_description` TEXT AFTER `categories_blurb`");
    parent::log("Database Update: ALTER IGNORE TABLE `" . $pf . "categories_description` ADD `categories_description` TEXT AFTER `categories_blurb`");   
    $lC_Database->simpleQuery("ALTER IGNORE TABLE `" . $pf . "categories_description` ADD `categories_keyword` VARCHAR( 255 ) DEFAULT NULL AFTER `categories_description`");
    parent::log("Database Update: ALTER IGNORE TABLE `" . $pf . "categories_description` ADD `categories_keyword` VARCHAR( 255 ) DEFAULT NULL AFTER `categories_description`");      
    $lC_Database->simpleQuery("ALTER IGNORE TABLE `" . $pf . "categories_description` ADD `categories_tags` VARCHAR( 255 ) DEFAULT NULL AFTER `categories_keyword`");
    parent::log("Database Update:  ALTER IGNORE TABLE `" . $pf . "categories_description` ADD `categories_tags` VARCHAR( 255 ) DEFAULT NULL AFTER `categories_keyword`");
  
    $lC_Database->simpleQuery("UPDATE IGNORE `" . $pf . "configuration_group` SET `configuration_group_title` = 'Checkout', `configuration_group_description` = 'Checkout settings' where `configuration_group_id` = '19'");
    parent::log("Database Update: UPDATE IGNORE `" . $pf . "configuration_group` SET `configuration_group_title` = 'Checkout', `configuration_group_description` = 'Checkout settings' where `configuration_group_id` = '19'");        
    
    if (!defined('CHECKOUT_SUPPRESS_NON_MOBILE_PAYMENT_MODULES')) {
      $lC_Database->simpleQuery("INSERT INTO `" . $pf . "configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES('Suppress Non-Mobile Payment Modules', 'CHECKOUT_SUPPRESS_NON_MOBILE_PAYMENT_MODULES', '-1', 'Suppress non-mobile payment modules in catalog when being viewed in mobile format.', 19, 0, NULL, '2012-10-09 18:17:08', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))')");
      parent::log("Database Update: INSERT INTO `" . $pf . "configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES('Suppress Non-Mobile Payment Modules', 'CHECKOUT_SUPPRESS_NON_MOBILE_PAYMENT_MODULES', '-1', 'Suppress non-mobile payment modules in catalog when being viewed in mobile format.', 19, 0, NULL, '2012-10-09 18:17:08', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))')");
    }
    
    $lC_Database->simpleQuery("ALTER IGNORE TABLE `" . $pf . "customers` CHANGE `customers_password` `customers_password` VARCHAR( 128 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL");
    parent::log("Database Update: ALTER IGNORE TABLE `" . $pf . "customers` CHANGE `customers_password` `customers_password` VARCHAR( 128 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL");    
    $lC_Database->simpleQuery("ALTER IGNORE TABLE `" . $pf . "administrators` CHANGE `user_password` `user_password` VARCHAR( 128 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL");
    parent::log("Database Update: ALTER IGNORE TABLE `" . $pf . "administrators` CHANGE `user_password` `user_password` VARCHAR( 128 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL");     
    
    $lC_Database->simpleQuery("ALTER IGNORE TABLE `" . $pf . "administrators` ADD `verify_key` VARCHAR( 64 ) DEFAULT NULL AFTER `access_group_id`");
    parent::log("Database Update: ALTER IGNORE TABLE `" . $pf . "administrators` ADD `verify_key` VARCHAR( 64 ) DEFAULT NULL AFTER `access_group_id`");
    $lC_Database->simpleQuery("ALTER IGNORE TABLE `" . $pf . "administrators` ADD `language_id` INT( 11 ) NOT NULL DEFAULT '1'  AFTER `verify_key`");
    parent::log("Database Update: ALTER IGNORE TABLE `" . $pf . "administrators` ADD `language_id` INT( 11 ) NOT NULL DEFAULT '1' AFTER `verify_key`");
    
    $lC_Database->simpleQuery("CREATE TABLE IF NOT EXISTS `" . $pf . "coupons` (coupons_id int(11) NOT NULL AUTO_INCREMENT, `type` enum('R','T','S','P') NOT NULL DEFAULT 'R', mode varchar(32) NOT NULL DEFAULT 'coupon', code varchar(32) NOT NULL, reward decimal(8,4) NOT NULL DEFAULT '0.0000', purchase_over decimal(8,4) NOT NULL DEFAULT '0.0000', start_date datetime DEFAULT NULL, expires_date datetime DEFAULT NULL, uses_per_coupon int(11) NOT NULL DEFAULT '0', uses_per_customer int(11) NOT NULL DEFAULT '0', restrict_to_products varchar(1024) DEFAULT NULL, restrict_to_categories varchar(1024) DEFAULT NULL, restrict_to_customers varchar(1024) DEFAULT NULL, `status` tinyint(1) NOT NULL DEFAULT '1', date_created datetime NOT NULL DEFAULT '0000-00-00 00:00:00', date_modified datetime NOT NULL DEFAULT '0000-00-00 00:00:00', sale_exclude tinyint(1) NOT NULL DEFAULT '0', notes varchar(255) NOT NULL, PRIMARY KEY (coupons_id)) ENGINE=" . $engine . " CHARACTER SET utf8 COLLATE utf8_general_ci");
    parent::log("Database Update: CREATE TABLE IF NOT EXISTS `" . $pf . "coupons` (coupons_id int(11) NOT NULL AUTO_INCREMENT, `type` enum('R','T','S','P') NOT NULL DEFAULT 'R', mode varchar(32) NOT NULL DEFAULT 'coupon', code varchar(32) NOT NULL, reward decimal(8,4) NOT NULL DEFAULT '0.0000', purchase_over decimal(8,4) NOT NULL DEFAULT '0.0000', start_date datetime DEFAULT NULL, expires_date datetime DEFAULT NULL, uses_per_coupon int(11) NOT NULL DEFAULT '0', uses_per_customer int(11) NOT NULL DEFAULT '0', restrict_to_products varchar(1024) DEFAULT NULL, restrict_to_categories varchar(1024) DEFAULT NULL, restrict_to_customers varchar(1024) DEFAULT NULL, `status` tinyint(1) NOT NULL DEFAULT '1', date_created datetime NOT NULL DEFAULT '0000-00-00 00:00:00', date_modified datetime NOT NULL DEFAULT '0000-00-00 00:00:00', sale_exclude tinyint(1) NOT NULL DEFAULT '0', notes varchar(255) NOT NULL, PRIMARY KEY (coupons_id)) ENGINE=" . $engine . " CHARACTER SET utf8 COLLATE utf8_general_ci");
    $lC_Database->simpleQuery("CREATE TABLE IF NOT EXISTS `" . $pf . "coupons_description` (coupons_id int(11) NOT NULL DEFAULT '0', language_id int(11) NOT NULL DEFAULT '1', name varchar(1024) NOT NULL DEFAULT '', PRIMARY KEY (coupons_id,language_id)) ENGINE=" . $engine . " CHARACTER SET utf8 COLLATE utf8_general_ci");
    parent::log("Database Update: CREATE TABLE IF NOT EXISTS `" . $pf . "coupons_description` (coupons_id int(11) NOT NULL DEFAULT '0', language_id int(11) NOT NULL DEFAULT '1', name varchar(1024) NOT NULL DEFAULT '', PRIMARY KEY (coupons_id,language_id)) ENGINE=" . $engine . " CHARACTER SET utf8 COLLATE utf8_general_ci");
    $lC_Database->simpleQuery("CREATE TABLE IF NOT EXISTS `" . $pf . "coupons_redeemed` ( id int(11) NOT NULL AUTO_INCREMENT, coupons_id int(11) NOT NULL DEFAULT '0', customers_id int(11) NOT NULL DEFAULT '0', redeem_date datetime NOT NULL DEFAULT '0000-00-00 00:00:00', redeem_ip varchar(32) NOT NULL DEFAULT '', order_id int(11) NOT NULL DEFAULT '0', PRIMARY KEY (id)) ENGINE=" . $engine . " CHARACTER SET utf8 COLLATE utf8_general_ci");
    parent::log("Database Update: CREATE TABLE IF NOT EXISTS `" . $pf . "coupons_redeemed` ( id int(11) NOT NULL AUTO_INCREMENT, coupons_id int(11) NOT NULL DEFAULT '0', customers_id int(11) NOT NULL DEFAULT '0', redeem_date datetime NOT NULL DEFAULT '0000-00-00 00:00:00', redeem_ip varchar(32) NOT NULL DEFAULT '', order_id int(11) NOT NULL DEFAULT '0', PRIMARY KEY (id)) ENGINE=" . $engine . " CHARACTER SET utf8 COLLATE utf8_general_ci");

    if (!defined('MODULE_ORDER_TOTAL_COUPON_STATUS')) {
      $lC_Database->simpleQuery("INSERT INTO `" . $pf . "configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES('Display Coupon', 'MODULE_ORDER_TOTAL_COUPON_STATUS', 'true', 'Do you want to dusplay the coupon discount total on the checkout pages?', 6, 0, NULL, '2013-07-30 14:10:55', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(''true'', ''false''))')");
      parent::log("Database Update: INSERT INTO `" . $pf . "configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES('Display Coupon', 'MODULE_ORDER_TOTAL_COUPON_STATUS', 'true', 'Do you want to dusplay the coupon discount total on the checkout pages?', 6, 0, NULL, '2013-07-30 14:10:55', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(''true'', ''false''))')");
    }
    if (!defined('MODULE_ORDER_TOTAL_COUPON_SORT_ORDER')) {
      $lC_Database->simpleQuery("INSERT INTO `" . $pf . "configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES('Sort Order', 'MODULE_ORDER_TOTAL_COUPON_SORT_ORDER', '300', 'Sort order of the display.', 6, 0, NULL, '2013-07-30 14:10:55', NULL, NULL)");
      parent::log("Database Update: INSERT INTO `" . $pf . "configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES('Sort Order', 'MODULE_ORDER_TOTAL_COUPON_SORT_ORDER', '300', 'Sort order of the display.', 6, 0, NULL, '2013-07-30 14:10:55', NULL, NULL)");
    }
    if (!defined('SERVICE_COUPONS_DISPLAY_ON_CART_PAGE')) {
      $lC_Database->simpleQuery("INSERT INTO `" . $pf . "configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES('Redeem On Cart Page?', 'SERVICE_COUPONS_DISPLAY_ON_CART_PAGE', '1', 'Display the coupons redemption form on the shopping cart page?', 6, 0, NULL, '2013-07-31 19:05:14', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))')");
      parent::log("Database Update: INSERT INTO `" . $pf . "configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES('Redeem On Cart Page?', 'SERVICE_COUPONS_DISPLAY_ON_CART_PAGE', '1', 'Display the coupons redemption form on the shopping cart page?', 6, 0, NULL, '2013-07-31 19:05:14', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))')");
    }      
    if (!defined('SERVICE_COUPONS_DISPLAY_ON_SHIPPING_PAGE')) {
      $lC_Database->simpleQuery("INSERT INTO `" . $pf . "configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES('Redeem On Shipping Page?', 'SERVICE_COUPONS_DISPLAY_ON_SHIPPING_PAGE', '1', 'Display the coupons redemption form on the checkout shipping page?', 6, 0, NULL, '2013-07-31 19:05:14', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))')");
      parent::log("Database Update: INSERT INTO `" . $pf . "configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES('Redeem On Shipping Page?', 'SERVICE_COUPONS_DISPLAY_ON_SHIPPING_PAGE', '1', 'Display the coupons redemption form on the checkout shipping page?', 6, 0, NULL, '2013-07-31 19:05:14', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))')");
    }
    if (!defined('SERVICE_COUPONS_DISPLAY_ON_PAYMENT_PAGE')) {
      $lC_Database->simpleQuery("INSERT INTO `" . $pf . "configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES('Redeem On Payment Page?', 'SERVICE_COUPONS_DISPLAY_ON_PAYMENT_PAGE', '1', 'Display the coupons redemption form on the checkout payment page?', 6, 0, NULL, '2013-07-31 19:05:14', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))')");
      parent::log("Database Update: INSERT INTO `" . $pf . "configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES('Redeem On Payment Page?', 'SERVICE_COUPONS_DISPLAY_ON_PAYMENT_PAGE', '1', 'Display the coupons redemption form on the checkout payment page?', 6, 0, NULL, '2013-07-31 19:05:14', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))')");
    }
    if (!defined('SERVICE_COUPONS_DISPLAY_ON_CONFIRMATION_PAGE')) {
      $lC_Database->simpleQuery("INSERT INTO `" . $pf . "configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES('Redeem On Confirmation Page?', 'SERVICE_COUPONS_DISPLAY_ON_CONFIRMATION_PAGE', '1', 'Display the coupons redemption form on the checkout confirmation page?', 6, 0, NULL, '2013-07-31 19:05:14', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))')");
      parent::log("Database Update: INSERT INTO `" . $pf . "configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES('Redeem On Confirmation Page?', 'SERVICE_COUPONS_DISPLAY_ON_CONFIRMATION_PAGE', '1', 'Display the coupons redemption form on the checkout confirmation page?', 6, 0, NULL, '2013-07-31 19:05:14', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))')");
    }
      
    $lC_Database->simpleQuery("ALTER IGNORE TABLE `" . $pf . "orders_status` ADD `orders_status_type` ENUM( 'Pending', 'Rejected', 'Approved' ) NOT NULL DEFAULT 'Pending' AFTER `orders_status_name`");
    parent::log("Database Update: ALTER IGNORE TABLE `" . $pf . "orders_status` ADD `orders_status_type` ENUM( 'Pending', 'Rejected', 'Approved' ) NOT NULL DEFAULT 'Pending' AFTER `orders_status_name`");

    $lC_Database->simpleQuery("ALTER IGNORE TABLE `" . $pf . "orders_status_history` ADD `administrators_id` INT( 11 ) NOT NULL DEFAULT '0' AFTER `comments`");
    parent::log("Database Update: ALTER IGNORE TABLE `" . $pf . "orders_status_history` ADD `administrators_id` INT( 11 ) NOT NULL DEFAULT '0' AFTER `comments`");
    $lC_Database->simpleQuery("ALTER IGNORE TABLE `" . $pf . "orders_status_history` ADD `append_comment` INT( 11 ) NOT NULL DEFAULT '1' AFTER `administrators_id`");
    parent::log("Database Update: ALTER IGNORE TABLE `" . $pf . "orders_status_history` ADD `append_comment` INT( 11 ) NOT NULL DEFAULT '1' AFTER `administrators_id`");
    
    $lC_Database->simpleQuery("ALTER IGNORE TABLE `" . $pf . "products` ADD `is_subproduct` TINYINT( 1 ) NOT NULL DEFAULT '0' AFTER `has_children`");
    parent::log("Database Update: ALTER IGNORE TABLE `" . $pf . "products` ADD `is_subproduct` TINYINT( 1 ) NOT NULL DEFAULT '0' AFTER `has_children`");
    
    $lC_Database->simpleQuery("CREATE TABLE IF NOT EXISTS `" . $pf . "permalinks` (permalink_id int(11) NOT NULL AUTO_INCREMENT, item_id int(11) NOT NULL, language_id int(11) NOT NULL DEFAULT '1', `type` int(11) NOT NULL, query varchar(255) NOT NULL, permalink varchar(255) NOT NULL, PRIMARY KEY (permalink_id,permalink)) ENGINE=" . $engine . " CHARACTER SET utf8 COLLATE utf8_general_ci");
    parent::log("Database Update: CREATE TABLE IF NOT EXISTS `" . $pf . "permalinks` (permalink_id int(11) NOT NULL AUTO_INCREMENT, item_id int(11) NOT NULL, language_id int(11) NOT NULL DEFAULT '1', `type` int(11) NOT NULL, query varchar(255) NOT NULL, permalink varchar(255) NOT NULL, PRIMARY KEY (permalink_id,permalink)) ENGINE=" . $engine . " CHARACTER SET utf8 COLLATE utf8_general_ci");

    $lC_Database->simpleQuery("INSERT IGNORE INTO `" . $pf . "configuration_group` (configuration_group_id, configuration_group_title, configuration_group_description, sort_order, visible) VALUES (20, 'Editor', 'Editor settings', 20, 1)");
    parent::log("Database Update: INSERT IGNORE INTO `" . $pf . "configuration_group` (configuration_group_id, configuration_group_title, configuration_group_description, sort_order, visible) VALUES (20, 'Editor', 'Editor settings', 20, 1)");

    if (!defined('ENABLE_EDITOR')) {    
      $lC_Database->simpleQuery("INSERT INTO `" . $pf . "configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('Enable/Disable Editor (Global)', 'ENABLE_EDITOR', '1', 'Enable or Disable Editor Globally', 20, 1, NULL, '2013-07-03 15:58:32', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))')");
      parent::log("Database Update: INSERT INTO `" . $pf . "configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('Enable/Disable Editor (Global)', 'ENABLE_EDITOR', '1', 'Enable or Disable Editor Globally', 20, 1, NULL, '2013-07-03 15:58:32', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))')");
    }
    if (!defined('USE_DEFAULT_TEMPLATE_STYLESHEET')) {
      $lC_Database->simpleQuery("INSERT INTO `" . $pf . "configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('Use Default Template Stylesheet', 'USE_DEFAULT_TEMPLATE_STYLESHEET', '-1', 'Use Default Template Stylesheet', 20, 2, NULL, '2013-07-03 15:58:32', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))')");
      parent::log("Database Update: INSERT INTO `" . $pf . "configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('Use Default Template Stylesheet', 'USE_DEFAULT_TEMPLATE_STYLESHEET', '-1', 'Use Default Template Stylesheet', 20, 2, NULL, '2013-07-03 15:58:32', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))')");
    }
    
    $lC_Database->simpleQuery("CREATE TABLE IF NOT EXISTS `" . $pf . "branding` (language_id INT(11) NOT NULL DEFAULT 1, slogan VARCHAR(256) NOT NULL DEFAULT '', meta_description VARCHAR(250) NOT NULL DEFAULT '', meta_keywords VARCHAR(128) NOT NULL DEFAULT '', meta_title VARCHAR(128) NOT NULL DEFAULT '', meta_title_prefix VARCHAR(128) NOT NULL, meta_title_suffix VARCHAR(128) NOT NULL, footer_text VARCHAR(256) NOT NULL DEFAULT '', PRIMARY KEY (language_id)) ENGINE=" . $engine . " CHARACTER SET utf8 COLLATE utf8_general_ci");   
    parent::log("Database Update: CREATE TABLE IF NOT EXISTS `" . $pf . "branding` (language_id INT(11) NOT NULL DEFAULT 1, slogan VARCHAR(256) NOT NULL DEFAULT '', meta_description VARCHAR(250) NOT NULL DEFAULT '', meta_keywords VARCHAR(128) NOT NULL DEFAULT '', meta_title VARCHAR(128) NOT NULL DEFAULT '', meta_title_prefix VARCHAR(128) NOT NULL, meta_title_suffix VARCHAR(128) NOT NULL, footer_text VARCHAR(256) NOT NULL DEFAULT '', PRIMARY KEY (language_id)) ENGINE=" . $engine . " CHARACTER SET utf8 COLLATE utf8_general_ci");   
    $lC_Database->simpleQuery("CREATE TABLE IF NOT EXISTS `" . $pf . "branding_data` (site_image VARCHAR(128) NOT NULL DEFAULT '', chat_code VARCHAR(8192) NOT NULL DEFAULT '', support_phone VARCHAR(16) NOT NULL DEFAULT '', support_email VARCHAR(128) NOT NULL DEFAULT '', sales_phone VARCHAR(16) NOT NULL DEFAULT '', sales_email VARCHAR(128) NOT NULL DEFAULT '', og_image VARCHAR(128) NOT NULL DEFAULT '', meta_delimeter VARCHAR(128) NOT NULL DEFAULT '', social_facebook_page VARCHAR(128) NOT NULL, social_twitter VARCHAR(128) NOT NULL, social_pinterest VARCHAR(128) NOT NULL, social_google_plus VARCHAR(128) NOT NULL, social_youtube VARCHAR(128) NOT NULL, social_linkedin VARCHAR(128) NOT NULL) ENGINE=" . $engine . " CHARACTER SET utf8 COLLATE utf8_general_ci");
    parent::log("Database Update: CREATE TABLE IF NOT EXISTS `" . $pf . "branding_data` (site_image VARCHAR(128) NOT NULL DEFAULT '', chat_code VARCHAR(8192) NOT NULL DEFAULT '', support_phone VARCHAR(16) NOT NULL DEFAULT '', support_email VARCHAR(128) NOT NULL DEFAULT '', sales_phone VARCHAR(16) NOT NULL DEFAULT '', sales_email VARCHAR(128) NOT NULL DEFAULT '', og_image VARCHAR(128) NOT NULL DEFAULT '', meta_delimeter VARCHAR(128) NOT NULL DEFAULT '', social_facebook_page VARCHAR(128) NOT NULL, social_twitter VARCHAR(128) NOT NULL, social_pinterest VARCHAR(128) NOT NULL, social_google_plus VARCHAR(128) NOT NULL, social_youtube VARCHAR(128) NOT NULL, social_linkedin VARCHAR(128) NOT NULL) ENGINE=" . $engine . " CHARACTER SET utf8 COLLATE utf8_general_ci");
      
    if (defined('DEFAULT_TEMPLATE') && DEFAULT_TEMPLATE == 'default') {    
      $lC_Database->simpleQuery("UPDATE `" . $pf . "configuration` SET `configuration_value` = 'core' where `configuration_key` = 'DEFAULT_TEMPLATE'");
      parent::log("Database Update: UPDATE `" . $pf . "configuration` SET `configuration_value` = 'core' where `configuration_key` = 'DEFAULT_TEMPLATE'");
    }
                                                                                                   
    $lC_Database->simpleQuery("UPDATE `" . $pf . "configuration` SET `configuration_value` = '100' where `configuration_key` = 'MODULE_ORDER_TOTAL_SUBTOTAL_SORT_ORDER'");
    parent::log("Database Update: UPDATE `" . $pf . "configuration` SET `configuration_value` = '100' where `configuration_key` = 'MODULE_ORDER_TOTAL_SUBTOTAL_SORT_ORDER'");
    
    $lC_Database->simpleQuery("UPDATE `" . $pf . "configuration` SET `configuration_value` = '200' where `configuration_key` = 'MODULE_ORDER_TOTAL_SHIPPING_SORT_ORDER'");
    parent::log("Database Update: UPDATE `" . $pf . "configuration` SET `configuration_value` = '200' where `configuration_key` = 'MODULE_ORDER_TOTAL_SHIPPING_SORT_ORDER'");
                                                  
    $lC_Database->simpleQuery("UPDATE `" . $pf . "configuration` SET `configuration_value` = '300' where `configuration_key` = 'MODULE_ORDER_TOTAL_COUPON_SORT_ORDER'");
    parent::log("Database Update: UPDATE `" . $pf . "configuration` SET `configuration_value` = '300' where `configuration_key` = 'MODULE_ORDER_TOTAL_COUPON_SORT_ORDER'");
                                                  
    $lC_Database->simpleQuery("UPDATE `" . $pf . "configuration` SET `configuration_value` = '500' where `configuration_key` = 'MODULE_ORDER_TOTAL_TAX_SORT_ORDER'");
    parent::log("Database Update: UPDATE `" . $pf . "configuration` SET `configuration_value` = '500' where `configuration_key` = 'MODULE_ORDER_TOTAL_TAX_SORT_ORDER'");
                                                  
    $lC_Database->simpleQuery("UPDATE `" . $pf . "configuration` SET `configuration_value` = '600' where `configuration_key` = 'MODULE_ORDER_TOTAL_TOTAL_SORT_ORDER'");
    parent::log("Database Update: UPDATE `" . $pf . "configuration` SET `configuration_value` = '600' where `configuration_key` = 'MODULE_ORDER_TOTAL_TOTAL_SORT_ORDER'");
                                                  
    $lC_Database->simpleQuery("UPDATE `" . $pf . "configuration` SET `configuration_value` = '-1' where `configuration_key` = 'USE_DEFAULT_TEMPLATE_STYLESHEET'");
    parent::log("Database Update: UPDATE `" . $pf . "configuration` SET `configuration_value` = '-1' where `configuration_key` = 'USE_DEFAULT_TEMPLATE_STYLESHEET'");
                                                  
    $lC_Database->simpleQuery("UPDATE `" . $pf . "configuration` SET `configuration_title` = 'Tag Cloud Maximum Listings' where `configuration_key` = 'TAG_CLOUD_MAX_LIST'");
    parent::log("Database Update: UPDATE `" . $pf . "configuration` SET `configuration_title` = 'Tag Cloud Maximum Listings' where `configuration_key` = 'TAG_CLOUD_MAX_LIST'");
    $lC_Database->simpleQuery("CREATE TABLE IF NOT EXISTS `" . $pf . "featured_products` (id int(11) NOT NULL AUTO_INCREMENT, products_id int(11) NOT NULL DEFAULT '0', date_added datetime NOT NULL DEFAULT '0000-00-00 00:00:00', last_modified datetime NOT NULL DEFAULT '0000-00-00 00:00:00', expires_date datetime NOT NULL DEFAULT '0000-00-00 00:00:00', `status` int(1) DEFAULT '1', PRIMARY KEY (id)) ENGINE=" . $engine . " CHARACTER SET utf8 COLLATE utf8_general_ci");
    parent::log("Database Update: CREATE TABLE IF NOT EXISTS `" . $pf . "featured_products` (id int(11) NOT NULL AUTO_INCREMENT, products_id int(11) NOT NULL DEFAULT '0', date_added datetime NOT NULL DEFAULT '0000-00-00 00:00:00', last_modified datetime NOT NULL DEFAULT '0000-00-00 00:00:00', expires_date datetime NOT NULL DEFAULT '0000-00-00 00:00:00', `status` int(1) DEFAULT '1', PRIMARY KEY (id)) ENGINE=" . $engine . " CHARACTER SET utf8 COLLATE utf8_general_ci");

    $lC_Database->simpleQuery("UPDATE `" . $pf . "configuration` SET `configuration_key` = 'AUTODISABLE_OUT_OF_STOCK_PRODUCT',`configuration_title` = 'Autodisable out of stock product',`configuration_description` = 'Set product as IN-ACTIVE if there is insufficient stock that is 0 or below' WHERE `configuration_id` = 61");
    parent::log("Database Update: UPDATE `" . $pf . "configuration` SET `configuration_key` = 'AUTODISABLE_OUT_OF_STOCK_PRODUCT',`configuration_title` = 'Autodisable out of stock product',`configuration_description` = 'Set product as IN-ACTIVE if there is insufficient stock that is 0 or below' WHERE `configuration_id` = 61");
    
    if (!defined('DISABLE_ADD_TO_CART')) {
      $lC_Database->simpleQuery("INSERT INTO `" . $pf . "configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES('Disable Add to Cart for out of stock products', 'DISABLE_ADD_TO_CART', '1', 'Disabled the add to cart button on the product page displays text that product is out of stock', 9, 3, NULL, '2009-11-26 15:58:32', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))');");
      parent::log("Database Update: INSERT INTO `" . $pf . "configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES('Disable Add to Cart for out of stock products', 'DISABLE_ADD_TO_CART', '1', 'Disabled the add to cart button on the product page displays text that product is out of stock', 9, 3, NULL, '2009-11-26 15:58:32', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))');");
    }
    if (!defined('EDITOR_CONFIGURATION_PRODUCT')) {
      $lC_Database->simpleQuery("INSERT INTO `" . $pf . "configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES('Product Description Editor Configuration', 'EDITOR_CONFIGURATION_PRODUCT', 'Minimum', 'Set Product description editor configuration.', 20, 3, NULL, now(), NULL, 'lc_cfg_set_boolean_value(array(''Off'',''Minimum'', ''Standard'',''Full''))');");
      parent::log("Database Update: INSERT INTO `" . $pf . "configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES('Product Description Editor Configuration', 'EDITOR_CONFIGURATION_PRODUCT', 'Minimum', 'Set Product description editor configuration.', 20, 3, NULL, now(), NULL, 'lc_cfg_set_boolean_value(array(''Off'',''Minimum'', ''Standard'',''Full''))');");
    }
    if (!defined('EDITOR_CONFIGURATION_CATEGORY')) {
      $lC_Database->simpleQuery("INSERT INTO `" . $pf . "configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES('Category Description Editor Configuration', 'EDITOR_CONFIGURATION_CATEGORY', 'Full', 'Set Category description editor configuration.', 20, 4, NULL, now(), NULL, 'lc_cfg_set_boolean_value(array(''Off'',''Minimum'', ''Standard'',''Full''))');");
      parent::log("Database Update: INSERT INTO `" . $pf . "configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES('Category Description Editor Configuration', 'EDITOR_CONFIGURATION_CATEGORY', 'Full', 'Set Category description editor configuration.', 20, 4, NULL, now(), NULL, 'lc_cfg_set_boolean_value(array(''Off'',''Minimum'', ''Standard'',''Full''))');");
    }
    if (!defined('EDITOR_CONFIGURATION_HOMEPAGE')) {
      $lC_Database->simpleQuery("INSERT INTO `" . $pf . "configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES('Home Page Editor Configuration', 'EDITOR_CONFIGURATION_HOMEPAGE', 'Full', 'Set Home Page editor configuration.', 20, 5, NULL, now(), NULL, 'lc_cfg_set_boolean_value(array(''Off'',''Minimum'', ''Standard'',''Full''))');");
      parent::log("Database Update: INSERT INTO `" . $pf . "configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES('Home Page Editor Configuration', 'EDITOR_CONFIGURATION_HOMEPAGE', 'Full', 'Set Home Page editor configuration.', 20, 5, NULL, now(), NULL, 'lc_cfg_set_boolean_value(array(''Off'',''Minimum'', ''Standard'',''Full''))');");
    }
    if (!defined('EDITOR_UPLOADCARE_PUBLIC_KEY')) {
      $lC_Database->simpleQuery("INSERT IGNORE INTO `" . $pf . "configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES('Uploadcare Public Key', 'EDITOR_UPLOADCARE_PUBLIC_KEY', '', 'Add your Uploadcare public key. <a href=\"https://uploadcare.com/accounts/settings/\" target=\"_blank\">Get your Uploadcare Public Key</a>', 20, 6, NULL, now(), NULL, NULL);");
      parent::log("Database Update: INSERT INTO `" . $pf . "configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES('Uploadcare Public Key', 'EDITOR_UPLOADCARE_PUBLIC_KEY', '', 'Add your Uploadcare public key. <a href=\"https://uploadcare.com/accounts/settings/\" target=\"_blank\">Get your Uploadcare Public Key</a>', 20, 6, NULL, now(), NULL, NULL);");
    }
          
    $lC_Database->simpleQuery("ALTER IGNORE TABLE `" . $pf . "branding` ADD `homepage_text` VARCHAR(20000) NOT NULL DEFAULT '' AFTER `language_id`;");
    parent::log("Database Update: ALTER IGNORE TABLE `" . $pf . "branding` ADD `homepage_text` VARCHAR(20000) NOT NULL DEFAULT '' AFTER `language_id`;");    
    
    $lC_Database->simpleQuery("UPDATE `" . $pf . "currencies` SET `symbol_left` = '&euro;' where `code` = 'EUR'");
    parent::log("Database Update: UPDATE `" . $pf . "currencies` SET `symbol_left` = '&euro;' where `code` = 'EUR'");
    
    $lC_Database->simpleQuery("UPDATE `" . $pf . "currencies` SET `symbol_left` = '&pound;' where `code` = 'GBP'");
    parent::log("Database Update: UPDATE `" . $pf . "currencies` SET `symbol_left` = '&pound;' where `code` = 'GBP'"); 
    
    // default values update
    $lC_Database->simpleQuery("ALTER IGNORE TABLE `" . $pf . "orders_status_history` ADD `administrators_id` INT( 11 ) NOT NULL DEFAULT '0' AFTER `comments`");
    parent::log("Database Update: ALTER IGNORE TABLE `" . $pf . "orders_status_history` ADD `administrators_id` INT( 11 ) NOT NULL DEFAULT '0' AFTER `comments`");
    
    $lC_Database->simpleQuery("ALTER IGNORE TABLE `" . $pf . "administrators` CHANGE `first_name` `first_name` VARCHAR(64) NOT NULL DEFAULT '', CHANGE `last_name` `last_name` VARCHAR(64) NOT NULL DEFAULT '', CHANGE `image` `image` VARCHAR(255) NOT NULL DEFAULT '', CHANGE `access_group_id` `access_group_id` INT(11) NOT NULL DEFAULT '0', CHANGE `verify_key` `verify_key` VARCHAR(64) NOT NULL DEFAULT '';");
    parent::log("Database Update: ALTER IGNORE TABLE `" . $pf . "administrators` CHANGE `first_name` `first_name` VARCHAR(64) NOT NULL DEFAULT '', CHANGE `last_name` `last_name` VARCHAR(64) NOT NULL DEFAULT '', CHANGE `image` `image` VARCHAR(255) NOT NULL DEFAULT '', CHANGE `access_group_id` `access_group_id` INT(11) NOT NULL DEFAULT '0', CHANGE `verify_key` `verify_key` VARCHAR(64) NOT NULL DEFAULT '';");

    $lC_Database->simpleQuery("ALTER IGNORE TABLE `" . $pf . "administrators_access` CHANGE `administrators_id` `administrators_id` INT(11) NOT NULL DEFAULT '0', CHANGE `administrators_groups_id` `administrators_groups_id` INT(11) NOT NULL DEFAULT '0', CHANGE `module` `module` VARCHAR(255) NOT NULL DEFAULT '', CHANGE `level` `level` INT(11) NOT NULL DEFAULT '0';");
    parent::log("Database Update: ALTER IGNORE TABLE `" . $pf . "administrators_access` CHANGE `administrators_id` `administrators_id` INT(11) NOT NULL DEFAULT '0', CHANGE `administrators_groups_id` `administrators_groups_id` INT(11) NOT NULL DEFAULT '0', CHANGE `module` `module` VARCHAR(255) NOT NULL DEFAULT '', CHANGE `level` `level` INT(11) NOT NULL DEFAULT '0';");

    $lC_Database->simpleQuery("ALTER IGNORE TABLE `" . $pf . "banners_history` CHANGE `banners_id` `banners_id` INT(11) NOT NULL DEFAULT '0', CHANGE `banners_shown` `banners_shown` INT(11) NOT NULL DEFAULT '0', CHANGE `banners_clicked` `banners_clicked` INT(11) NOT NULL DEFAULT '0';");
    parent::log("Database Update: ALTER IGNORE TABLE `" . $pf . "banners_history` CHANGE `banners_id` `banners_id` INT(11) NOT NULL DEFAULT '0', CHANGE `banners_shown` `banners_shown` INT(11) NOT NULL DEFAULT '0', CHANGE `banners_clicked` `banners_clicked` INT(11) NOT NULL DEFAULT '0';");

    $lC_Database->simpleQuery("ALTER IGNORE TABLE `" . $pf . "branding` CHANGE `homepage_text` `homepage_text` VARCHAR(20000) NOT NULL DEFAULT '', CHANGE `slogan` `slogan` VARCHAR(256) NOT NULL DEFAULT '', CHANGE `meta_description` `meta_description` VARCHAR(250) NOT NULL DEFAULT '', CHANGE `meta_keywords` `meta_keywords` VARCHAR(128) NOT NULL DEFAULT '', CHANGE `meta_title` `meta_title` VARCHAR(128) NOT NULL DEFAULT '', CHANGE `footer_text` `footer_text` VARCHAR(256) NOT NULL DEFAULT '';");
    parent::log("Database Update: ALTER IGNORE TABLE `" . $pf . "branding` CHANGE `homepage_text` `homepage_text` VARCHAR(20000) NOT NULL DEFAULT '', CHANGE `slogan` `slogan` VARCHAR(256) NOT NULL DEFAULT '', CHANGE `meta_description` `meta_description` VARCHAR(250) NOT NULL DEFAULT '', CHANGE `meta_keywords` `meta_keywords` VARCHAR(128) NOT NULL DEFAULT '', CHANGE `meta_title` `meta_title` VARCHAR(128) NOT NULL DEFAULT '', CHANGE `footer_text` `footer_text` VARCHAR(256) NOT NULL DEFAULT '';");

    $lC_Database->simpleQuery("ALTER IGNORE TABLE `" . $pf . "branding_data` CHANGE `site_image` `site_image` VARCHAR(128) NOT NULL DEFAULT '', CHANGE `chat_code` `chat_code` VARCHAR(8192) NOT NULL DEFAULT '', CHANGE `support_phone` `support_phone` VARCHAR(16) NOT NULL DEFAULT '', CHANGE `support_email` `support_email` VARCHAR(128) NOT NULL DEFAULT '', CHANGE `sales_phone` `sales_phone` VARCHAR(16) NOT NULL DEFAULT '', CHANGE `sales_email` `sales_email` VARCHAR(128) NOT NULL DEFAULT '', CHANGE `og_image` `og_image` VARCHAR(128) NOT NULL DEFAULT '', CHANGE `meta_delimeter` `meta_delimeter` VARCHAR(128) NOT NULL DEFAULT '';");
    parent::log("Database Update: ALTER IGNORE TABLE `" . $pf . "branding_data` CHANGE `site_image` `site_image` VARCHAR(128) NOT NULL DEFAULT '', CHANGE `chat_code` `chat_code` VARCHAR(8192) NOT NULL DEFAULT '', CHANGE `support_phone` `support_phone` VARCHAR(16) NOT NULL DEFAULT '', CHANGE `support_email` `support_email` VARCHAR(128) NOT NULL DEFAULT '', CHANGE `sales_phone` `sales_phone` VARCHAR(16) NOT NULL DEFAULT '', CHANGE `sales_email` `sales_email` VARCHAR(128) NOT NULL DEFAULT '', CHANGE `og_image` `og_image` VARCHAR(128) NOT NULL DEFAULT '', CHANGE `meta_delimeter` `meta_delimeter` VARCHAR(128) NOT NULL DEFAULT '';");

    $lC_Database->simpleQuery("ALTER IGNORE TABLE `" . $pf . "categories` CHANGE `parent_id` `parent_id` INT(11) NOT NULL DEFAULT '0', CHANGE `categories_link_target` `categories_link_target` TINYINT(1) NULL DEFAULT '0', CHANGE `categories_visibility_nav` `categories_visibility_nav` TINYINT(1) NULL DEFAULT '0';");
    parent::log("Database Update: ALTER IGNORE TABLE `" . $pf . "categories` CHANGE `parent_id` `parent_id` INT(11) NOT NULL DEFAULT '0', CHANGE `categories_link_target` `categories_link_target` TINYINT(1) NULL DEFAULT '0', CHANGE `categories_visibility_nav` `categories_visibility_nav` TINYINT(1) NULL DEFAULT '0';");

    $lC_Database->simpleQuery("ALTER IGNORE TABLE `" . $pf . "coupons` CHANGE `uses_per_coupon` `uses_per_coupon` INT(11) NOT NULL DEFAULT '0', CHANGE `uses_per_customer` `uses_per_customer` INT(11) NOT NULL DEFAULT '0', CHANGE `sale_exclude` `sale_exclude` TINYINT(1) NOT NULL DEFAULT '0';");
    parent::log("Database Update: ALTER IGNORE TABLE `" . $pf . "coupons` CHANGE `uses_per_coupon` `uses_per_coupon` INT(11) NOT NULL DEFAULT '0', CHANGE `uses_per_customer` `uses_per_customer` INT(11) NOT NULL DEFAULT '0', CHANGE `sale_exclude` `sale_exclude` TINYINT(1) NOT NULL DEFAULT '0';");

    $lC_Database->simpleQuery("ALTER IGNORE TABLE `" . $pf . "coupons_description` CHANGE `coupons_id` `coupons_id` INT(11) NOT NULL DEFAULT '0', CHANGE `name` `name` VARCHAR(1024) NOT NULL DEFAULT '';");
    parent::log("Database Update: ALTER IGNORE TABLE `" . $pf . "coupons_description` CHANGE `coupons_id` `coupons_id` INT(11) NOT NULL DEFAULT '0', CHANGE `name` `name` VARCHAR(1024) NOT NULL DEFAULT '';");

    $lC_Database->simpleQuery("ALTER IGNORE TABLE `" . $pf . "coupons_redeemed` CHANGE `coupons_id` `coupons_id` INT(11) NOT NULL DEFAULT '0', CHANGE `customers_id` `customers_id` INT(11) NOT NULL DEFAULT '0', CHANGE `redeem_ip` `redeem_ip` VARCHAR(32) NOT NULL DEFAULT '', CHANGE `order_id` `order_id` INT(11) NOT NULL DEFAULT '0';");
    parent::log("Database Update: ALTER IGNORE TABLE `" . $pf . "coupons_redeemed` CHANGE `coupons_id` `coupons_id` INT(11) NOT NULL DEFAULT '0', CHANGE `customers_id` `customers_id` INT(11) NOT NULL DEFAULT '0', CHANGE `redeem_ip` `redeem_ip` VARCHAR(32) NOT NULL DEFAULT '', CHANGE `order_id` `order_id` INT(11) NOT NULL DEFAULT '0';");

    $lC_Database->simpleQuery("ALTER IGNORE TABLE `" . $pf . "featured_products` CHANGE `products_id` `products_id` INT(11) NOT NULL DEFAULT '0';");
    parent::log("Database Update: ALTER IGNORE TABLE `" . $pf . "featured_products` CHANGE `products_id` `products_id` INT(11) NOT NULL DEFAULT '0';");

    $lC_Database->simpleQuery("ALTER IGNORE TABLE `" . $pf . "manufacturers_info` CHANGE `url_clicked` `url_clicked` INT(11) NOT NULL DEFAULT '0';");
    parent::log("Database Update: ALTER IGNORE TABLE `" . $pf . "manufacturers_info` CHANGE `url_clicked` `url_clicked` INT(11) NOT NULL DEFAULT '0';");

    $lC_Database->simpleQuery("ALTER IGNORE TABLE `" . $pf . "orders_status_history` CHANGE `administrators_id` `administrators_id` INT(11) NOT NULL DEFAULT '0';");
    parent::log("Database Update: ALTER IGNORE TABLE `" . $pf . "orders_status_history` CHANGE `administrators_id` `administrators_id` INT(11) NOT NULL DEFAULT '0';");

    $lC_Database->simpleQuery("ALTER IGNORE TABLE `" . $pf . "products` CHANGE `parent_id` `parent_id` INT(11) NULL DEFAULT '0', CHANGE `products_quantity` `products_quantity` INT(11) NOT NULL DEFAULT '0', CHANGE `products_weight_class` `products_weight_class` INT(11) NOT NULL DEFAULT '0', CHANGE `products_status` `products_status` TINYINT(1) NOT NULL DEFAULT '0', CHANGE `products_tax_class_id` `products_tax_class_id` INT(11) NOT NULL DEFAULT '0', CHANGE `products_ordered` `products_ordered` INT(11) NOT NULL DEFAULT '0';");
    parent::log("Database Update: ALTER IGNORE TABLE `" . $pf . "products` CHANGE `parent_id` `parent_id` INT(11) NULL DEFAULT '0', CHANGE `products_quantity` `products_quantity` INT(11) NOT NULL DEFAULT '0', CHANGE `products_weight_class` `products_weight_class` INT(11) NOT NULL DEFAULT '0', CHANGE `products_status` `products_status` TINYINT(1) NOT NULL DEFAULT '0', CHANGE `products_tax_class_id` `products_tax_class_id` INT(11) NOT NULL DEFAULT '0', CHANGE `products_ordered` `products_ordered` INT(11) NOT NULL DEFAULT '0';");

    $lC_Database->simpleQuery("ALTER IGNORE TABLE `" . $pf . "products_simple_options` CHANGE `sort_order` `sort_order` INT(11) NOT NULL DEFAULT '0';");
    parent::log("Database Update: ALTER IGNORE TABLE `" . $pf . "products_simple_options` CHANGE `sort_order` `sort_order` INT(11) NOT NULL DEFAULT '0';");

    $lC_Database->simpleQuery("ALTER IGNORE TABLE `" . $pf . "products_simple_options_values` CHANGE `sort_order` `sort_order` INT(11) NOT NULL DEFAULT '0';");
    parent::log("Database Update: ALTER IGNORE TABLE `" . $pf . "products_simple_options_values` CHANGE `sort_order` `sort_order` INT(11) NOT NULL DEFAULT '0';");

    $lC_Database->simpleQuery("ALTER IGNORE TABLE `" . $pf . "reviews` CHANGE `products_id` `products_id` INT(11) NOT NULL DEFAULT '0', CHANGE `reviews_read` `reviews_read` INT(11) NOT NULL DEFAULT '0', CHANGE `reviews_status` `reviews_status` TINYINT(1) NOT NULL DEFAULT '0';");
    parent::log("Database Update: ALTER IGNORE TABLE `" . $pf . "reviews` CHANGE `products_id` `products_id` INT(11) NOT NULL DEFAULT '0', CHANGE `reviews_read` `reviews_read` INT(11) NOT NULL DEFAULT '0', CHANGE `reviews_status` `reviews_status` TINYINT(1) NOT NULL DEFAULT '0';");

    $lC_Database->simpleQuery("ALTER IGNORE TABLE `" . $pf . "product_attributes` ADD INDEX(`idx_pa_products_id`);");
    parent::log("Database Update: ALTER IGNORE TABLE `" . $pf . "product_attributes` ADD INDEX(`idx_pa_products_id`);");

    $lC_Database->simpleQuery("ALTER IGNORE TABLE `" . $pf . "updates_log` CHANGE `action` `action` VARCHAR(32) NOT NULL DEFAULT '', CHANGE `result` `result` VARCHAR(128) NOT NULL DEFAULT '', CHANGE `user` `user` VARCHAR(64) NOT NULL DEFAULT '';");
    parent::log("Database Update: ALTER IGNORE TABLE `" . $pf . "updates_log` CHANGE `action` `action` VARCHAR(32) NOT NULL DEFAULT '', CHANGE `result` `result` VARCHAR(128) NOT NULL DEFAULT '', CHANGE `user` `user` VARCHAR(64) NOT NULL DEFAULT '';");
    
    $lC_Database->simpleQuery("ALTER IGNORE TABLE `" . $pf . "branding_data` ADD COLUMN `custom_css` text NOT NULL;");
    parent::log("Database Update: ALTER IGNORE TABLE `" . $pf . "branding_data` ADD COLUMN `custom_css` text NOT NULL;");
       
    $lC_Database->simpleQuery("ALTER IGNORE TABLE `" . $pf . "products_variants` ADD `default_visual` INT( 11 ) DEFAULT '0' AFTER `default_combo`");  
    parent::log("Database Update: ALTER IGNORE TABLE `" . $pf . "products_variants` ADD `default_visual` INT( 11 ) DEFAULT '0' AFTER `default_combo`");  
       
    $lC_Database->simpleQuery("ALTER IGNORE TABLE `" . $pf . "products_variants_values` ADD `visual` VARCHAR( 1024 ) DEFAULT NULL AFTER `title`");  
    parent::log("Database Update: ALTER IGNORE TABLE `" . $pf . "products_variants_values` ADD `visual` VARCHAR( 1024 ) DEFAULT NULL AFTER `title`");  
    
    // missing weight class rules
    $lC_Database->simpleQuery("INSERT IGNORE INTO `" . $pf . "weight_classes_rules` (weight_class_from_id, weight_class_to_id, weight_class_rule) VALUES (1, 2, 0.0010)");
    parent::log("Database Update: INSERT IGNORE INTO `" . $pf . "weight_classes_rules` (weight_class_from_id, weight_class_to_id, weight_class_rule) VALUES (1, 2, 0.0010)");
    
    $lC_Database->simpleQuery("INSERT IGNORE INTO `" . $pf . "weight_classes_rules` (weight_class_from_id, weight_class_to_id, weight_class_rule) VALUES (1, 3, 0.0352)");
    parent::log("Database Update: INSERT IGNORE INTO `" . $pf . "weight_classes_rules` (weight_class_from_id, weight_class_to_id, weight_class_rule) VALUES (1, 3, 0.0352)");
    
    $lC_Database->simpleQuery("INSERT IGNORE INTO `" . $pf . "weight_classes_rules` (weight_class_from_id, weight_class_to_id, weight_class_rule) VALUES (1, 4, 0.0022)");
    parent::log("Database Update: INSERT IGNORE INTO `" . $pf . "weight_classes_rules` (weight_class_from_id, weight_class_to_id, weight_class_rule) VALUES (1, 4, 0.0022)");
    
    $lC_Database->simpleQuery("INSERT IGNORE INTO `" . $pf . "weight_classes_rules` (weight_class_from_id, weight_class_to_id, weight_class_rule) VALUES (2, 1, 1000.0000)");
    parent::log("Database Update: INSERT IGNORE INTO `" . $pf . "weight_classes_rules` (weight_class_from_id, weight_class_to_id, weight_class_rule) VALUES (2, 1, 1000.0000)");
    
    $lC_Database->simpleQuery("INSERT IGNORE INTO `" . $pf . "weight_classes_rules` (weight_class_from_id, weight_class_to_id, weight_class_rule) VALUES (2, 3, 35.2739)");
    parent::log("Database Update: INSERT IGNORE INTO `" . $pf . "weight_classes_rules` (weight_class_from_id, weight_class_to_id, weight_class_rule) VALUES (2, 3, 35.2739)");
    
    $lC_Database->simpleQuery("INSERT IGNORE INTO `" . $pf . "weight_classes_rules` (weight_class_from_id, weight_class_to_id, weight_class_rule) VALUES (2, 4, 2.2046)");
    parent::log("Database Update: INSERT IGNORE INTO `" . $pf . "weight_classes_rules` (weight_class_from_id, weight_class_to_id, weight_class_rule) VALUES (2, 4, 2.2046)");
    
    $lC_Database->simpleQuery("INSERT IGNORE INTO `" . $pf . "weight_classes_rules` (weight_class_from_id, weight_class_to_id, weight_class_rule) VALUES (3, 1, 28.3495)");
    parent::log("Database Update: INSERT IGNORE INTO `" . $pf . "weight_classes_rules` (weight_class_from_id, weight_class_to_id, weight_class_rule) VALUES (3, 1, 28.3495)");
    
    $lC_Database->simpleQuery("INSERT IGNORE INTO `" . $pf . "weight_classes_rules` (weight_class_from_id, weight_class_to_id, weight_class_rule) VALUES (3, 2, 0.0283)");
    parent::log("Database Update: INSERT IGNORE INTO `" . $pf . "weight_classes_rules` (weight_class_from_id, weight_class_to_id, weight_class_rule) VALUES (3, 2, 0.0283)");
    
    $lC_Database->simpleQuery("INSERT IGNORE INTO `" . $pf . "weight_classes_rules` (weight_class_from_id, weight_class_to_id, weight_class_rule) VALUES (3, 4, 0.0625)");
    parent::log("Database Update: INSERT IGNORE INTO `" . $pf . "weight_classes_rules` (weight_class_from_id, weight_class_to_id, weight_class_rule) VALUES (3, 4, 0.0625)");
    
    $lC_Database->simpleQuery("INSERT IGNORE INTO `" . $pf . "weight_classes_rules` (weight_class_from_id, weight_class_to_id, weight_class_rule) VALUES (4, 1, 453.5923)");
    parent::log("Database Update: INSERT IGNORE INTO `" . $pf . "weight_classes_rules` (weight_class_from_id, weight_class_to_id, weight_class_rule) VALUES (4, 1, 453.5923)");
    
    $lC_Database->simpleQuery("INSERT IGNORE INTO `" . $pf . "weight_classes_rules` (weight_class_from_id, weight_class_to_id, weight_class_rule) VALUES (4, 2, 0.4535)");
    parent::log("Database Update: INSERT IGNORE INTO `" . $pf . "weight_classes_rules` (weight_class_from_id, weight_class_to_id, weight_class_rule) VALUES (4, 2, 0.4535)");
    
    $lC_Database->simpleQuery("INSERT IGNORE INTO `" . $pf . "weight_classes_rules` (weight_class_from_id, weight_class_to_id, weight_class_rule) VALUES (4, 3, 16.0000)");
    parent::log("Database Update: INSERT IGNORE INTO `" . $pf . "weight_classes_rules` (weight_class_from_id, weight_class_to_id, weight_class_rule) VALUES (4, 3, 16.0000)");
  
    $lC_Database->simpleQuery("UPDATE `" . $pf . "currencies` SET `symbol_left` = '&euro;' where `code` = 'EUR'");
    parent::log("Database Update: UPDATE `" . $pf . "currencies` SET `symbol_left` = '&euro;' where `code` = 'EUR'");
    
    $lC_Database->simpleQuery("UPDATE `" . $pf . "currencies` SET `symbol_left` = '&pound;' where `code` = 'GBP'");
    parent::log("Database Update: UPDATE `" . $pf . "currencies` SET `symbol_left` = '&pound;' where `code` = 'GBP'");      
    $lC_Database->simpleQuery("INSERT IGNORE INTO `" . $pf . "configuration_group` (configuration_group_id, configuration_group_title, configuration_group_description, sort_order, visible) VALUES (10, 'Sessions', 'Session settings', 10, 1)");
    parent::log("Database Update: INSERT IGNORE INTO `" . $pf . "configuration_group` (configuration_group_id, configuration_group_title, configuration_group_description, sort_order, visible) VALUES (10, 'Sessions', 'Session settings', 10, 1)");

    if (!defined('SESSION_LIFETIME')) {
      $lC_Database->simpleQuery("INSERT INTO `" . $pf . "configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES('Session Lifetime', 'SESSION_LIFETIME', 3600, 'The amount of time a user is logged in for after the last action in seconds.', 10, 0, now(), now(), NULL, NULL);");
      parent::log("Database Update: INSERT INTO `" . $pf . "configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES('Session Lifetime', 'SESSION_LIFETIME', 3600, 'The amount of time a user is logged in for after the last action in seconds.', 10, 0, now(), now(), NULL, NULL);");
    
    $lC_Database->simpleQuery("CREATE TABLE IF NOT EXISTS `" . $pf . "customers_access` (id int(11) NOT NULL AUTO_INCREMENT, `level` varchar(128) NOT NULL DEFAULT '', `status` tinyint(1) NOT NULL DEFAULT '0', PRIMARY KEY (id)) ENGINE=" . $engine . " CHARACTER SET utf8 COLLATE utf8_general_ci");   
    parent::log("Database Update: CREATE TABLE IF NOT EXISTS `" . $pf . "customers_access` (id int(11) NOT NULL AUTO_INCREMENT, `level` varchar(128) NOT NULL DEFAULT '', `status` tinyint(1) NOT NULL DEFAULT '0', PRIMARY KEY (id)) ENGINE=" . $engine . " CHARACTER SET utf8 COLLATE utf8_general_ci");   
    
    $lC_Database->simpleQuery("ALTER IGNORE TABLE `" . $pf . "customers_groups_data` ADD `customers_access_levels` VARCHAR(255) NOT NULL DEFAULT '';");
    parent::log("Database Update: ALTER IGNORE TABLE `" . $pf . "customers_groups_data` ADD `customers_access_levels` VARCHAR(255) NOT NULL DEFAULT '';");

    $lC_Database->simpleQuery("ALTER IGNORE TABLE `" . $pf . "customers_groups_data` ADD `hidden_products_notification` TINYINT(1) NOT NULL DEFAULT '0';");
    parent::log("Database Update: ALTER IGNORE TABLE `" . $pf . "customers_groups_data` ADD `hidden_products_notification` TINYINT(1) NOT NULL DEFAULT '0';");
    
    $lC_Database->simpleQuery("ALTER IGNORE TABLE `" . $pf . "customers_groups_data` ADD `taxable` TINYINT(1) NOT NULL DEFAULT '0';");
    parent::log("Database Update: ALTER IGNORE TABLE `" . $pf . "customers_groups_data` ADD `taxable` TINYINT(1) NOT NULL DEFAULT '0';");
    
    $lC_Database->simpleQuery("ALTER IGNORE TABLE `" . $pf . "customers_groups_data` ADD `payment_modules` VARCHAR(255) NOT NULL DEFAULT '';");
    parent::log("Database Update: ALTER IGNORE TABLE `" . $pf . "customers_groups_data` ADD `payment_modules` VARCHAR(255) NOT NULL DEFAULT '';");
        
    $lC_Database->simpleQuery("ALTER IGNORE TABLE `" . $pf . "customers_groups_data` ADD `shipping_modules` VARCHAR(255) NOT NULL DEFAULT '';");
    parent::log("Database Update: ALTER IGNORE TABLE `" . $pf . "customers_groups_data` ADD `shipping_modules` VARCHAR(255) NOT NULL DEFAULT '';");
    
    $lC_Database->simpleQuery("ALTER IGNORE TABLE `" . $pf . "products` ADD `access_levels` VARCHAR(255) NOT NULL DEFAULT '';");
    parent::log("Database Update: ALTER IGNORE TABLE `" . $pf . "products` ADD `access_levels` VARCHAR(255) NOT NULL DEFAULT '';");
    
    $lC_Database->simpleQuery("ALTER IGNORE TABLE `" . $pf . "categories` ADD `access_levels` VARCHAR(255) NOT NULL DEFAULT '';");
    parent::log("Database Update: ALTER IGNORE TABLE `" . $pf . "categories` ADD `access_levels` VARCHAR(255) NOT NULL DEFAULT '';");

    $lC_Database->simpleQuery("INSERT IGNORE INTO `" . $pf . "customers_access` (id, level, status) VALUES (1, 'Guest', 1)");
    parent::log("Database Update: INSERT IGNORE INTO `" . $pf . "customers_access` (id, level, status) VALUES (1, 'Guest', 1)");

    $lC_Database->simpleQuery("INSERT IGNORE INTO `" . $pf . "customers_access` (id, level, status) VALUES (2, 'Registered', 1)");
    parent::log("Database Update: INSERT IGNORE INTO `" . $pf . "customers_access` (id, level, status) VALUES (2, 'Registered', 1)");
    }
    
    if (!defined('SESSION_FORCE_COOKIES')) {
      $lC_Database->simpleQuery("INSERT INTO `" . $pf . "configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES('Force Cookie Use', 'SESSION_FORCE_COOKIES', -1, 'Force the use cookies to handle sessions.', 10, 0, now(), now(), 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))');");
      parent::log("Database Update: INSERT INTO `" . $pf . "configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES('Force Cookie Use', 'SESSION_FORCE_COOKIES', -1, 'Force the use cookies to handle sessions.', 10, 0, now(), now(), 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))');");
    }  
    
    $lC_Database->simpleQuery("ALTER IGNORE TABLE `" . $pf . "orders_products` ADD COLUMN `products_sku` VARCHAR(255) DEFAULT NULL AFTER `products_model`");
    parent::log("Database Update: ALTER IGNORE TABLE `" . $pf . "orders_products` ADD COLUMN `products_sku` VARCHAR(255) DEFAULT NULL AFTER `products_model`");
    
    $lC_Database->simpleQuery("INSERT IGNORE INTO `" . $pf . "configuration_group` (configuration_group_id, configuration_group_title, configuration_group_description, sort_order, visible) VALUES (22, 'File Uploads', 'File Upload Settings', 13, 1)");
    parent::log("Database Update: INSERT IGNORE INTO `" . $pf . "configuration_group` (configuration_group_id, configuration_group_title, configuration_group_description, sort_order, visible) VALUES (22, 'File Uploads', 'File Upload Settings', 13, 1)");
    
    if (!defined('PRODUCT_MODULES_FILE_UPLOAD_TYPES')) {
      $lC_Database->simpleQuery("INSERT INTO `" . $pf . "configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES('Allowed File Types', 'PRODUCT_MODULES_FILE_UPLOAD_TYPES', '.zip,.pdf,.png,.gif,.jpg,.tiff,.gzip,.gz', 'Enter the allowed file upload extensions in a comma delimited format.', 22, 0, now(), now(), NULL, NULL)");
      parent::log("Database Update: INSERT INTO `" . $pf . "configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES('Allowed File Types', 'PRODUCT_MODULES_FILE_UPLOAD_TYPES', '.zip,.pdf,.png,.gif,.jpg,.tiff,.gzip,.gz', 'Enter the allowed file upload extensions in a comma delimited format.', 22, 0, now(), now(), NULL, NULL)");
    }    
    
    if (!defined('PRODUCT_MODULES_FILE_UPLOAD_MAX_SIZE')) {      
      $lC_Database->simpleQuery("INSERT INTO `" . $pf . "configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES('Maximum Upload File Size', 'PRODUCT_MODULES_FILE_UPLOAD_MAX_SIZE', '10', 'Enter the maximum size allowed for file uploads.', 22, 0, now(), now(), NULL, NULL)");
      parent::log("Database Update: INSERT INTO `" . $pf . "configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES('Maximum Upload File Size', 'PRODUCT_MODULES_FILE_UPLOAD_MAX_SIZE', '10', 'Enter the maximum size (in MB) allowed for file uploads.', 22, 0, now(), now(), NULL, NULL)");
    } 
    
    if (utility::isB2B()) {
      if (!defined('B2B_SETTINGS_ALLOW_SELF_REGISTER')) {
        $lC_Database->simpleQuery("insert into `" . $pf . "configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('', 'B2B_SETTINGS_ALLOW_SELF_REGISTER', '1', '', '6', '0', '', '', now())");
        parent::log("Database Update: insert into `" . $pf . "configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('', 'B2B_SETTINGS_ALLOW_SELF_REGISTER', '1', '', '6', '0', '', '', now())");
      }
      
      if (!defined('B2B_SETTINGS_GUEST_CATALOG_ACCESS')) {
        $lC_Database->simpleQuery("insert into `" . $pf . "configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('', 'B2B_SETTINGS_GUEST_CATALOG_ACCESS', '4', '', '6', '0', '', '', now())");
        parent::log("Database Update: insert into `" . $pf . "configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('', 'B2B_SETTINGS_GUEST_CATALOG_ACCESS', '4', '', '6', '0', '', '', now())");
      }
    }

    $lC_Database->simpleQuery("CREATE TABLE IF NOT EXISTS `" . $pf . "purchase_orders_service` (`po_number_id` int(11) NOT NULL AUTO_INCREMENT, `po_number` varchar(255) NOT NULL, `orders_id` int(11) NOT NULL, `po_number_status` int(1) NOT NULL DEFAULT '1', PRIMARY KEY (`po_number_id`)) ENGINE=" . $engine . " CHARACTER SET utf8 COLLATE utf8_general_ci"); 
  }
}  
?>