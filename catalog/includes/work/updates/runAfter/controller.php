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
    $lC_Database->simpleQuery("ALTER IGNORE TABLE `" . $pf . "categories` ADD `categories_show_in_listings` TINYINT( 1 ) DEFAULT '1' AFTER `categories_custom_url`");
    parent::log("Database Update: ALTER IGNORE TABLE `" . $pf . "categories` ADD `categories_show_in_listings` TINYINT( 1 ) DEFAULT '1' AFTER `categories_custom_url`");     
    
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
  
    $lC_Database->simpleQuery("ALTER IGNORE TABLE `" . $pf . "categories` ADD `categories_mode` VARCHAR( 128 ) DEFAULT NULL AFTER `sort_order`");
    parent::log("Database Update: ALTER IGNORE TABLE `" . $pf . "categories` ADD `categories_mode` VARCHAR( 128 ) DEFAULT NULL AFTER `sort_order`");
    $lC_Database->simpleQuery("ALTER IGNORE TABLE `" . $pf . "categories` ADD `categories_link_target` TINYINT( 1 ) DEFAULT 0 AFTER `categories_mode`");
    parent::log("Database Update: ALTER IGNORE TABLE `" . $pf . "categories` ADD `categories_link_target` TINYINT( 1 ) DEFAULT 0 AFTER `categories_mode`");
    $lC_Database->simpleQuery("ALTER IGNORE TABLE `" . $pf . "categories` ADD `categories_custom_url` VARCHAR( 255 ) DEFAULT NULL AFTER `categories_link_target`");
    parent::log("Database Update: ALTER IGNORE TABLE `" . $pf . "categories` ADD `categories_custom_url` VARCHAR( 255 ) DEFAULT NULL AFTER `categories_link_target`");
    $lC_Database->simpleQuery("ALTER IGNORE TABLE `" . $pf . "categories` ADD `categories_status` TINYINT( 1 ) DEFAULT NULL AFTER `categories_custom_url`");
    parent::log("Database Update: ALTER IGNORE TABLE `" . $pf . "categories` ADD `categories_status` TINYINT( 1 ) DEFAULT NULL AFTER `categories_custom_url`");
    $lC_Database->simpleQuery("ALTER IGNORE TABLE `" . $pf . "categories` ADD `categories_visibility_nav` TINYINT( 1 ) DEFAULT '0' AFTER `categories_status`");
    parent::log("Database Update: ALTER IGNORE TABLE `" . $pf . "categories` ADD `categories_visibility_nav` TINYINT( 1 ) DEFAULT '0' AFTER `categories_status`");
    $lC_Database->simpleQuery("ALTER IGNORE TABLE `" . $pf . "categories` ADD `categories_visibility_box` TINYINT( 1 ) DEFAULT '1' AFTER `categories_visibility_nav`");
    parent::log("Database Update: ALTER IGNORE TABLE `" . $pf . "categories` ADD `categories_visibility_box` TINYINT( 1 ) DEFAULT '1' AFTER `categories_visibility_nav`");
    
    $lC_Database->simpleQuery("UPDATE IGNORE `" . $pf . "configuration_group` SET `configuration_group_title` = 'Checkout', `configuration_group_description` = 'Checkout settings' where `configuration_group_id` = '19'");
    parent::log("Database Update: UPDATE IGNORE `" . $pf . "configuration_group` SET `configuration_group_title` = 'Checkout', `configuration_group_description` = 'Checkout settings' where `configuration_group_id` = '19'");    
    $lC_Database->simpleQuery("INSERT IGNORE INTO `" . $pf . "configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('Supress Non-Mobile Payment Modules', 'CHECKOUT_SUPRESS_NON_MOBILE_PAYMENT_MODULES', '-1', 'Suppress non-mobile payment modules in catalog when being viewed in mobile format.', 19, 0, NULL, '2012-10-09 18:17:08', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))')");
    parent::log("Database Update: INSERT IGNORE INTO `" . $pf . "configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('Supress Non-Mobile Payment Modules', 'CHECKOUT_SUPRESS_NON_MOBILE_PAYMENT_MODULES', '-1', 'Suppress non-mobile payment modules in catalog when being viewed in mobile format.', 19, 0, NULL, '2012-10-09 18:17:08', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))')");
    
  } 
}  
?>