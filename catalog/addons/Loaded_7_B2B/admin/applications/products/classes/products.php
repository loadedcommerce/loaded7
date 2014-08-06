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
    
    // group pricing
    if (isset($data['group_pricing']) && is_array($data['group_pricing'])) {
    echo "<pre>";
    print_r($data);
    echo "</pre>";
die('123');    
    }
   
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
 /*
  *  Return the product simple options accordian price listing content
  *
  * @access public
  * @return array
  */
  public static function getGroupPricingContent($base_price) {
    global $lC_Language, $lC_Currencies;
    
    $content = '';
    $groups = lC_Customer_groups_Admin::getAll();
    foreach($groups['entries'] as $key => $value) {

      $discount = round((float)$base_price * ((float)$value['baseline_discount'] * .01), DECIMAL_PLACES); 
      $discounted_price = $base_price - $discount;
     
      $content .= '<div>' .
                  '  <label for="" class="label margin-right"><b>'. $value['customers_group_name'] .'</b></label>' .
                  '  <input type="checkbox" name="group_pricing[' . $value['customers_group_id'] . '][enable]" class="switch medium margin-right" />' .
                  '    <div class="inputs grey" style="display:inline; padding:8px 0;">' .
                  '      <span class="mid-margin-left no-margin-right">' . $lC_Currencies->getSymbolLeft() . '</span>' .
                  '      <input type="text" onchange="" onfocus="this.select();" name="group_pricing[' . $value['customers_group_id'] . '][price]" id="group_pricing_' . $value['customers_group_id'] . '_price" value="' . number_format($discounted_price, DECIMAL_PLACES) . '" class="input-unstyled small-margin-right grey" style="width:60px;"/>' .
                  '    </div>' .
                  '  <small class="input-info mid-margin-left no-wrap">' . $lC_Language->get('text_price') . '<span class="tag glossy mid-margin-left">-' . number_format($value['baseline_discount'], DECIMAL_PLACES) . '%</span></small>' . 
                  '</div>';
    }
    
    return $content;
  }
 /*
  *  Return the product simple options accordian price listing content
  *
  * @access public
  * @return array
  */
  public static function getSpecialPricingContent() {
    global $lC_Language, $lC_Currencies, $pInfo;
    
    $content = '';
    $groups = lC_Customer_groups_Admin::getAll();
    foreach($groups['entries'] as $key => $value) {
      $base = (isset($pInfo)) ? (float)$pInfo->get('products_price') : 0.00;
      $special = (isset($pInfo)) ? (float)$pInfo->get('products_special_price') : 0.00;
      $discount = (isset($base) && $base > 0.00) ? round( ((($base - $special) / $base) * 100), DECIMAL_PLACES) : 0.00; 
     
      $content .= '<label for="products_special_pricing_enable' . $value['customers_group_id'] . '" class="label margin-right"><b>'. $value['customers_group_name'] .'</b></label>' .
                  '<div class="columns">' .
                  '  <div class="new-row-mobile twelve-columns twelve-columns-mobile mid-margin-bottom">' .
                  '    <input id="products_special_pricing_pricing_' . $value['customers_group_id'] . '_enable" name="products_special_pricing[' . $value['customers_group_id'] . '][enable]" type="checkbox" class="margin-right medium switch"' . (($pInfo->get('products_special_status') != 0) ? ' checked' : '') . ' />' .
                  '    <div class="inputs" style="display:inline; padding:8px 0;">' .
                  '      <span class="mid-margin-left no-margin-right">' . $lC_Currencies->getSymbolLeft() . '</span>' .
                  '      <input type="text" onfocus="this.select();" onchange="updatePricingDiscountDisplay();" name="products_special_pricing[' . $value['customers_group_id'] . '][price]" id="products_special_pricing_' . $value['customers_group_id'] . '_price" value="' . number_format($pInfo->get('products_special_price'), DECIMAL_PLACES) . '" class="sprice input-unstyled small-margin-right" style="width:60px;" />' .
                  '    </div>' .
                  '    <small class="input-info mid-margin-left no-wrap">' . $lC_Language->get('text_special_price') . '<span class="disctag tag glossy mid-margin-left">-' . number_format($discount, DECIMAL_PLACES) . '%</span></small>' .
                  '  </div>' .
                  '  <div class="new-row-mobile twelve-columns twelve-columns-mobile">' .
                  '    <span class="nowrap margin-right">' .
                  '      <span class="input small-margin-top">' .
                  '        <input name="products_special_pricing[' . $value['customers_group_id'] . '][start_date]" id="products_special_pricing_' . $value['customers_group_id'] . '_start_date" type="text" placeholder="Start" class="input-unstyled datepicker" value="' . $pInfo->get('products_special_start_date') . '" style="width:97px;" />' .
                  '      </span>' .
                  '      <span class="icon-calendar icon-size2 small-margin-left"></span>' .
                  '    </span>' .
                  '    <span class="nowrap">' .
                  '      <span class="input small-margin-top">' .
                  '        <input name="products_special_pricing[' . $value['customers_group_id'] . '][expires_date]" id="products_special_pricing_' . $value['customers_group_id'] . '_expires_date" type="text" placeholder="End" class="input-unstyled datepicker" value="' . $pInfo->get('products_special_expires_date') . '" style="width:97px;" />' .
                  '      </span>' .
                  '      <span class="icon-calendar icon-size2 small-margin-left"></span>' .
                  '    </span>' .
                  '  </div>' .
                  '</div>';
    }
    
    return $content;   
  }
}