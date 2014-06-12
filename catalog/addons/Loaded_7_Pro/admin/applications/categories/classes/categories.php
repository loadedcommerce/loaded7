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

include_once($lC_Vqmod->modCheck(DIR_FS_ADMIN . 'includes/applications/categories/classes/categories.php'));

class lC_Categories_pro_Admin extends lC_Categories_Admin {
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
    
    $category_id = parent::save($id, $data);

    return $category_id;
  } 
 /*
  * Get related products/categories html
  *
  * @access public
  * @return string
  */  
  public static function getProductRelatedHtml($cid = '') {
    global $lC_Language;
    
    $lC_Language->loadIniFile('categories.php');
    
    $pData = self::_getProductsRelated($cid);
      
    $html = '<style scoped="scoped">.col { width:40%; }</style>' .
            '<fieldset class="fieldset">' .
            '  <legend class="legend">' . $lC_Language->get('field_products_related') . '</legend>' .                
            '  <div class="columns no-margin-bottom">' .
            '    <div class="twelve-columns no-margin-bottom">' .
            '      <div class="clearfix full-width">' .
            '        <div class="float-right col align-right">' . number_format($pData['products_in_category'], 0) . '</div>' .
            '        <div class="float-left col">' . $lC_Language->get('field_products_in_category') . '</div>' .
            '      </div>' .
            '      <div class="clearfix full-width margin-top">' .
            '        <div class="float-right col align-right">' . number_format($pData['number_of_sub_categories'], 0) . '</div>' .
            '        <div class="float-left col">' . $lC_Language->get('field_sub_categories') . '</div>' .
            '      </div>' .
            '      <div class="clearfix full-width margin-top">' .
            '        <div class="float-right col align-right">' . number_format($pData['products_in_sub_categories'], 0) . '</div>' .
            '        <div class="float-left col">' . $lC_Language->get('field_products_in_sub_categories') . '</div>' .
            '      </div>' .
            '      <div class="clearfix full-width margin-top">' .
            '        <div class="float-right col align-right"><b>' . number_format($pData['total_products'], 0) . '</b></div>' .
            '        <div class="float-left col"><b>' . $lC_Language->get('field_total_products') . '</b></div>' .
            '      </div>' .                                                      
            '    </div>' .
            '  </div>' .              
            '</fieldset>';
            
    return $html;                 
  }   
  
  protected static function _getProductsRelated($cid) {
    global $lC_Database, $lC_CategoryTree;
    
    $total = 0;
    $pData = array();
    if (!is_numeric($cid)) return false;

    // get number of products in this category
    $Qp2c = $lC_Database->query('select count(*) as total from :table_products_to_categories where `categories_id` = :categories_id');
    $Qp2c->bindTable(':table_products_to_categories', TABLE_PRODUCTS_TO_CATEGORIES);
    $Qp2c->bindInt(':categories_id', $cid);
    $Qp2c->execute(); 
    
    $pData['products_in_category'] = $Qp2c->valueInt('total');   
    $total = (int)$total + $Qp2c->valueInt('total');   

    // get the number of sub categories under this category
    $lC_CategoryTree = new lC_CategoryTree_Admin();
    $catArr = $lC_CategoryTree->getArray($cid);
    $pData['number_of_sub_categories'] = count($catArr);
    
    $pCnt = 0;
    foreach ($catArr as $value) {
      $catID = end(explode('_', $value['id']));     
      $pCnt = (int)$pCnt + self::_numberofProducts($catID);
    }
    $pData['products_in_sub_categories'] = $pCnt;
    $total = (int)$total + $pCnt; 
    
    $pData['total_products'] = $total;  

    return $pData;
  }
  
  protected static function _numberofProducts($cid) {
    global $lC_Database;
    
    // get number of products in this category
    $Qp2c = $lC_Database->query('select count(*) as total from :table_products_to_categories where `categories_id` = :categories_id');
    $Qp2c->bindTable(':table_products_to_categories', TABLE_PRODUCTS_TO_CATEGORIES);
    $Qp2c->bindInt(':categories_id', $cid);
    $Qp2c->execute(); 
    
    $total = $Qp2c->valueInt('total');
    
    $Qp2c->freeResult();
    
    return (int)$total;
  }
}