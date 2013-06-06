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
    
    $lC_Database->simpleQuery("ALTER IGNORE TABLE `lc_banners` ADD `banners_target` INT( 1 ) NOT NULL DEFAULT '1' AFTER `banners_url`");
    parent::log("Database Update: ALTER IGNORE TABLE `lc_banners` ADD `banners_target` INT( 1 ) NOT NULL DEFAULT '1' AFTER `banners_url`");
    
  } 
}  
?>