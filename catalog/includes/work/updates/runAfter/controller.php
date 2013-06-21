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

  } 
}  
?>