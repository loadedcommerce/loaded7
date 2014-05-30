<?php
/**
  @package    catalog::addons::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: products.php v1.0 2013-08-08 datazen $
*/
global $lC_Vqmod;

if (!defined('DIR_FS_ADMIN')) return false;

include_once($lC_Vqmod->modCheck(DIR_FS_CATALOG . 'addons/Loaded_7_Pro/admin/applications/categories/classes/categories.php'));

class lC_Categories_b2b_Admin extends lC_Categories_pro_Admin {
 /*
  * Save the category record
  *
  * @param integer $id The category id on update, null on insert
  * @param array $data The category information
  * @access public
  * @return boolean
  */
  public static function save($id = null, $data) {
    global $lC_Database, $lC_Language;
  }  
  
  public static function batchEditAccess($data) {
    global $lC_Database;
    
    echo "<pre>";
    print_r($data);
    echo "</pre>";
    die('678');
    return true;
  }
}