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

include_once($lC_Vqmod->modCheck(DIR_FS_CATALOG . 'addons/Loaded_7_Pro/admin/applications/categories/classes/categories.php'));
include_once($lC_Vqmod->modCheck(DIR_FS_ADMIN . 'includes/classes/category_tree.php'));

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
    global $lC_Database, $lC_Language, $lC_CategoryTree;
    
    $category_id = lC_Categories_pro_Admin::save($id, $data);
    
    $error = false;
    
    $lC_Database->startTransaction();
      
    $levels = '';
    if (is_array($data['access_levels'])) {
      foreach ($data['access_levels'] as $key => $val) {
        $levels .= $key . ';';
      }
      $levels = substr($levels, 0, -1);
    }

    $Qcat = $lC_Database->query('update :table_categories set `access_levels` = :access_levels where `categories_id` = :categories_id');
    $Qcat->bindTable(':table_categories', TABLE_CATEGORIES);
    $Qcat->bindInt(':categories_id', $category_id);
    $Qcat->bindValue(':access_levels', $levels);
    $Qcat->setLogging($_SESSION['module'], $category_id);
    $Qcat->execute();
    
    if ( !$lC_Database->isError()) {
      $syncAllProducts = (isset($data['sync_all_products']) && $data['sync_all_products'] == 'on') ? true: false; 
      $syncAllChildren = (isset($data['sync_all_children']) && $data['sync_all_children'] == 'on') ? true: false;
      
      if ($syncAllProducts || $syncAllChildren) {
        $lC_CategoryTree = new lC_CategoryTree_Admin();
        $catArr = $lC_CategoryTree->getArray($category_id);
        
        foreach ($catArr as $value) {
          $catID = end(explode('_', $value['id']));     
          if ($syncAllChildren) self::_updateCategoryAccessLevels($catID, $levels);
          if ($syncAllProducts) self::_updateProductAccessLevels($catID, $levels);
        }        
      } 
    }

    if ( !$lC_Database->isError() ) {
      $lC_Database->commitTransaction();
      lC_Cache::clear('categories');

      return $category_id; // used for the save_close buttons
    }

    $lC_Database->rollbackTransaction();

    return false;    
  }  
 /*
  * Update the product access levels
  *
  * @param integer $category_id The parent category id
  * @param string  $levels      The access levels
  * @access public
  * @return boolean
  */
  protected static function _updateProductAccessLevels($category_id, $levels) {
    global $lC_Database;
    
    // get all product_id's assigned to $category_id
    $Qp2c = $lC_Database->query('select products_id from :table_products_to_categories where `categories_id` = :categories_id');
    $Qp2c->bindTable(':table_products_to_categories', TABLE_PRODUCTS_TO_CATEGORIES);
    $Qp2c->bindInt(':categories_id', $category_id);
    $Qp2c->setLogging($_SESSION['module'], $category_id);
    $Qp2c->execute();

    $lC_Database->startTransaction();

    if ( !$lC_Database->isError()) {
      while($Qp2c->next()) {
        $Qproducts = $lC_Database->query('update :table_products set `access_levels` = :access_levels where `products_id` = :products_id');
        $Qproducts->bindTable(':table_products', TABLE_PRODUCTS);
        $Qproducts->bindInt(':products_id', $Qp2c->valueInt('products_id'));
        $Qproducts->bindvalue(':access_levels', $levels);
        $Qproducts->setLogging($_SESSION['module'], $category_id);
        $Qproducts->execute();        
      }
    }  

    $Qp2c->freeResult();      
    
    if ( !$lC_Database->isError() ) {
      $lC_Database->commitTransaction();
      return true;
    }

    $lC_Database->rollbackTransaction();

    return false;        
  }  
 /*
  * Update the category access levels
  *
  * @param integer $category_id The parent category id
  * @param string  $levels      The access levels
  * @access public
  * @return boolean
  */
  protected static function _updateCategoryAccessLevels($category_id, $levels) {
    global $lC_Database;
    
    $lC_Database->startTransaction();
    
    $Qcat = $lC_Database->query('update :table_categories set `access_levels` = :access_levels where `categories_id` = :categories_id');
    $Qcat->bindTable(':table_categories', TABLE_CATEGORIES);
    $Qcat->bindInt(':categories_id', $category_id);
    $Qcat->bindValue(':access_levels', $levels);
    $Qcat->setLogging($_SESSION['module'], $category_id);
    $Qcat->execute();
    
    if ( !$lC_Database->isError() ) {
      $lC_Database->commitTransaction();
      return true;
    }

    $lC_Database->rollbackTransaction();

    return false;        
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
    global $lC_Database, $lC_CategoryTree;

    $levels = '';
    if (is_array($data['access_levels'])) {
      foreach ($data['access_levels'] as $key => $val) {
        $levels .= $key . ';';
      }
      $levels = substr($levels, 0, -1);
    }  
    
    $syncAllProducts = (isset($data['sync_all_products']) && $data['sync_all_products'] == 'on') ? true: false; 
    $syncAllChildren = (isset($data['sync_all_children']) && $data['sync_all_children'] == 'on') ? true: false;
      
    if ($syncAllProducts || $syncAllChildren) {
      
      foreach ( $data['batch'] as $category_id ) {

        // get the children categories
        $lC_CategoryTree = new lC_CategoryTree_Admin();
        $catArr = $lC_CategoryTree->getArray($category_id);
         
        foreach ($catArr as $value) {
          $catID = end(explode('_', $value['id']));     
          if ($syncAllChildren) self::_updateCategoryAccessLevels($catID, $levels);
          if ($syncAllProducts) self::_updateProductAccessLevels($catID, $levels);
        } 
      }       
    }        

    return true;
  }
}