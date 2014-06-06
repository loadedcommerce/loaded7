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

if (!defined('DIR_FS_CATALOG')) return false;
if (!defined('DIR_FS_ADMIN')) return false;

include_once($lC_Vqmod->modCheck(DIR_FS_CATALOG . 'addons/Loaded_7_B2B/applications/products/classes/products.php'));
include_once($lC_Vqmod->modCheck(DIR_FS_ADMIN . 'includes/applications/products/classes/products.php'));
include_once($lC_Vqmod->modCheck(DIR_FS_ADMIN . 'includes/applications/customer_groups/classes/customer_groups.php'));

class lC_Products_b2b_Admin extends lC_Products_pro_Admin {
 /*
  * Save the product
  *
  * @param integer $id The products id to update, null on insert
  * @param array $data The products information
  * @access public
  * @return boolean
  */ 
  public static function save($id = null, $data) {
    global $lC_Database, $lC_Language;  
    
    $error = false;
    
    $products_id = lC_Products_pro_Admin::save($id, $data);   
   
    return $products_id; 
  }
 /*
  * Batch update the category access levels
  *
  * @param integer $category_id The parent category id
  * @param string  $levels      The access levels
  * @access public
  * @return boolean
  */  
  public static function batchEditAccess($data) {
    global $lC_Database;

    $levels = '';
    if (is_array($data['access_levels'])) {
      foreach ($data['access_levels'] as $key => $val) {
        $levels .= $key . ';';
      }
      $levels = substr($levels, 0, -1);
    }  
    
    $lC_Database->startTransaction();

    foreach ( $data['batch'] as $products_id ) {
      $Qproduct = $lC_Database->query('update :table_products set `access_levels` = :access_levels where `products_id` = :products_id');
      $Qproduct->bindTable(':table_products', TABLE_PRODUCTS);
      $Qproduct->bindInt(':products_id', $products_id);
      $Qproduct->bindvalue(':access_levels', $levels);
      $Qproduct->setLogging($_SESSION['module'], $products_id);
      $Qproduct->execute(); 
    }       
    
    if ( !$lC_Database->isError() ) {
      $lC_Database->commitTransaction();
      return true;
    }

    $lC_Database->rollbackTransaction();

    return false;
  }  
}