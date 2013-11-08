<?php
/**
  $Id: controller.php v1.0 2011-11-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html

*/
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
    
    $lC_Database->simpleQuery("ALTER IGNORE TABLE `" . $pf . "customers` CHANGE `customers_password` `customers_password` VARCHAR( 128 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL");
    parent::log("Database Update: ALTER IGNORE TABLE `" . $pf . "customers` CHANGE `customers_password` `customers_password` VARCHAR( 128 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL");    
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
    
    $lC_Database->simpleQuery("UPDATE `" . $pf . "configuration` SET `configuration_value` = 'core' where `configuration_key` = 'DEFAULT_TEMPLATE'");
    parent::log("Database Update: UPDATE `" . $pf . "configuration` SET `configuration_value` = 'core' where `configuration_key` = 'DEFAULT_TEMPLATE'");
    
    $lC_Database->simpleQuery("UPDATE `" . $pf . "configuration` SET `configuration_value` = 'output_compression;session;language;breadcrumb;currencies;core;whos_online;simple_counter;category_path;recently_visited;specials;reviews;banner;coupons' where `configuration_key` = 'MODULE_SERVICES_INSTALLED'");
    parent::log("Database Update: UPDATE `" . $pf . "configuration` SET `configuration_value` = 'output_compression;session;language;breadcrumb;currencies;core;whos_online;simple_counter;category_path;recently_visited;specials;reviews;banner;coupons' where `configuration_key` = 'MODULE_SERVICES_INSTALLED'");
    
    $lC_Database->simpleQuery("UPDATE `" . $pf . "configuration` SET `configuration_value` = '-1' where `configuration_key` = 'ACCOUNT_GENDER'");
    parent::log("Database Update: UPDATE `" . $pf . "configuration` SET `configuration_value` = '-1' where `configuration_key` = 'ACCOUNT_GENDER'");
    
    $lC_Database->simpleQuery("UPDATE `" . $pf . "configuration` SET `configuration_value` = '-1' where `configuration_key` = 'ACCOUNT_DATE_OF_BIRTH'");
    parent::log("Database Update: UPDATE `" . $pf . "configuration` SET `configuration_value` = '-1' where `configuration_key` = 'ACCOUNT_DATE_OF_BIRTH'");
    
    $lC_Database->simpleQuery("UPDATE `" . $pf . "configuration` SET `configuration_value` = '2' where `configuration_key` = 'PREV_NEXT_BAR_LOCATION'");
    parent::log("Database Update: UPDATE `" . $pf . "configuration` SET `configuration_value` = '2' where `configuration_key` = 'PREV_NEXT_BAR_LOCATION'");

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
    
  } 
}  
?>