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
    
    $guestAccess = (defined('B2B_SETTINGS_GUEST_CATALOG_ACCESS') && B2B_SETTINGS_GUEST_CATALOG_ACCESS > 0) ? (int)B2B_SETTINGS_GUEST_CATALOG_ACCESS : 0;

    // get the access levels for the group
    $Qcg = $lC_Database->query('select customers_access_levels from :table_customers_groups_data where customers_group_id = :customers_group_id limit 1');
    $Qcg->bindTable(':table_customers_groups_data', TABLE_CUSTOMERS_GROUPS_DATA);
    $Qcg->bindInt(':customers_group_id', $customers_group_id);
    $Qcg->execute();
          
    $cg_access_levels = explode(';', $Qcg->value('customers_access_levels')); 
    
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
    
    $guestAccess = $valid;
    if (!$lC_Customer->isLoggedOn()) {
      $guestAccess = (defined('B2B_SETTINGS_GUEST_CATALOG_ACCESS') && B2B_SETTINGS_GUEST_CATALOG_ACCESS > 0) ? (int)B2B_SETTINGS_GUEST_CATALOG_ACCESS : 0;
    }

    return $guestAccess;
  }  
 /*
  * Check to see if category has restricted accesa
  *
  * @access public
  * @return array
  */
  public static function hasCategoryAccess($categories_id, $customers_group_id) {
    global $lC_Database;
    
    // get the access levels for the group
    $Qcg = $lC_Database->query('select customers_access_levels from :table_customers_groups_data where customers_group_id = :customers_group_id limit 1');
    $Qcg->bindTable(':table_customers_groups_data', TABLE_CUSTOMERS_GROUPS_DATA);
    $Qcg->bindInt(':customers_group_id', $customers_group_id);
    $Qcg->execute();
          
    $cg_access_levels = explode(';', $Qcg->value('customers_access_levels')); 
    
    $Qcg->freeResult();   
    
    // get the product access levels
    $Qcat = $lC_Database->query('select access_levels from :table_categories where categories_id = :categories_id limit 1');
    $Qcat->bindTable(':table_categories', TABLE_CATEGORIES);
    $Qcat->bindInt(':categories_id', $categories_id);
    $Qcat->execute();
          
    $category_access_levels = explode(';', $Qcat->value('access_levels'));  
    
    $Qcat->freeResult();  
    
    // check if product has access 
    $valid = false;
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

    return $valid;
  }  
}
?>