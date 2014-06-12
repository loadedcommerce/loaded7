<?php
/**
  @package    catalog::classes
  @author     Loaded Commerce, LLC
  @copyright  Copyright 2003-2013 Loaded Commerce Development Team
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: product.php v1.0 2013-08-08 datazen $
*/
global $lC_Vqmod;

require_once($lC_Vqmod->modCheck(DIR_FS_CATALOG . 'includes/classes/product.php'));

class lC_Product_b2b extends lC_Product {
 /*
  * Check to see if product has restricted accesa
  *
  * @access public
  * @return array
  */
  public static function hasProductAccess($products_id, $customers_group_id) {
    global $lC_Database, $lC_Customer; 

    $valid = false;
    
    if ($lC_Customer->isLoggedOn === false) $customers_group_id = 0;

    if ($customers_group_id > 0) {  // not guest
    
      // get the access levels for the group
      $Qcg = $lC_Database->query('select customers_access_levels from :table_customers_groups_data where customers_group_id = :customers_group_id limit 1');
      $Qcg->bindTable(':table_customers_groups_data', TABLE_CUSTOMERS_GROUPS_DATA);
      $Qcg->bindInt(':customers_group_id', $customers_group_id);
      $Qcg->execute();
            
      $cg_access_levels = explode(';', $Qcg->value('customers_access_levels')); 
      
      $Qcg->freeResult();   
      
      // get the product access levels
      $Qproduct = $lC_Database->query('select access_levels from :table_products where products_id = :products_id limit 1');
      $Qproduct->bindTable(':table_products', TABLE_PRODUCTS);
      $Qproduct->bindInt(':products_id', $products_id);
      $Qproduct->execute();
            
      $product_access_levels = explode(';', $Qproduct->value('access_levels'));  
      
      $Qproduct->freeResult();  
      
      // check if product has access 
      $valid = false;
      if ($Qproduct->value('access_levels') == '') { // if nothing set, valid = true
        $valid = true;
      } else {
        foreach ($product_access_levels as $id) {
          if ($id != '') {
            if (in_array($id, $cg_access_levels)) {
              $valid = true;
              break;
            } 
          }
        }
      }        

    } else {
      $guestAccess = (defined('B2B_SETTINGS_GUEST_CATALOG_ACCESS') && B2B_SETTINGS_GUEST_CATALOG_ACCESS > 0) ? (int)B2B_SETTINGS_GUEST_CATALOG_ACCESS : 0;
      
      if ($guestAccess > 0) {
        // get the category access levels
        $Qproduct = $lC_Database->query('select access_levels from :table_products where products_id = :products_id limit 1');
        $Qproduct->bindTable(':table_products', TABLE_PRODUCTS);
        $Qproduct->bindInt(':categories_id', $products_id);
        $Qproduct->execute(); 
        
        $product_access_levels = explode(';', $Qproduct->value('access_levels'));  

        $Qproduct->freeResult(); 
        
        if ($Qproduct->value('access_levels') == '') { // if nothing set, valid = true
          $valid = true;
        } else if (in_array('1', $product_access_levels)) { // has guest access at the category level
          $valid = true;
        }                   
      
      }
    }
    
    return $valid;
  }   
 /*
  * Check to see if category has restricted accesa
  *
  * @access public
  * @return array
  */
  public static function hasCategoryAccess($categories_id, $customers_group_id) {
    global $lC_Database, $lC_Customer;  
    
    $valid = false;
    
    if ($lC_Customer->isLoggedOn() === false) $customers_group_id = 0;
    
    if ($customers_group_id > 0) {  // not guest
    
      // get the access levels for the group
      $Qcg = $lC_Database->query('select customers_access_levels from :table_customers_groups_data where customers_group_id = :customers_group_id limit 1');
      $Qcg->bindTable(':table_customers_groups_data', TABLE_CUSTOMERS_GROUPS_DATA);
      $Qcg->bindInt(':customers_group_id', $customers_group_id);
      $Qcg->execute();
            
      $cg_access_levels = explode(';', $Qcg->value('customers_access_levels')); 
      
      $Qcg->freeResult();   
      
      // get the category access levels
      $Qcat = $lC_Database->query('select access_levels from :table_categories where categories_id = :categories_id limit 1');
      $Qcat->bindTable(':table_categories', TABLE_CATEGORIES);
      $Qcat->bindInt(':categories_id', $categories_id);
      $Qcat->execute();
            
      $category_access_levels = explode(';', $Qcat->value('access_levels'));  

      $Qcat->freeResult();  
      
      // check if product has access 
      if ($Qcat->value('access_levels') == '') { // if nothing set, valid = true
        $valid = true;
      } else {
        foreach ($category_access_levels as $id) { 
          if ($id != '') {
            if (in_array($id, $cg_access_levels)) { 
              $valid = true;
              break;
            } 
          }
        }
      }    

    } else {
      $guestAccess = (defined('B2B_SETTINGS_GUEST_CATALOG_ACCESS') && B2B_SETTINGS_GUEST_CATALOG_ACCESS > 0) ? (int)B2B_SETTINGS_GUEST_CATALOG_ACCESS : 0;
      
      if ($guestAccess > 0) {
        // get the category access levels
        $Qcat = $lC_Database->query('select access_levels from :table_categories where categories_id = :categories_id limit 1');
        $Qcat->bindTable(':table_categories', TABLE_CATEGORIES);
        $Qcat->bindInt(':categories_id', $categories_id);
        $Qcat->execute(); 
        
        $category_access_levels = explode(';', $Qcat->value('access_levels'));  

        $Qcat->freeResult(); 
        
        if ($Qcat->value('access_levels') == '') { // if nothing set, valid = true
          $valid = true;
        } else if (in_array('1', $category_access_levels)) { // has guest access at the category level
          $valid = true;
        }                   
      
      }
    }
    
    return $valid;
  }  
  
  public static function restrictCategories($customers_group_id, $data) { 
echo "<pre>";
print_r($data);
echo "</pre>";
die('55');    
    
    foreach ($data[0] as $key => $top) { 
      foreach ($data[$key] as $ckey => $child) {
//        if ($child['item_id'] != NULL && self::hasCategoryAccess($child['item_id'], $customers_group_id) === false) unset($data[$key][$ckey]);
      }
      
      if ($top['item_id'] != NULL && self::hasCategoryAccess($top['item_id'], $customers_group_id) === false) unset($data[0][$key]);
    }
    
    return $data;  
  }
  
}
?>