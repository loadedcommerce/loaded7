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

include_once($lC_Vqmod->modCheck('includes/applications/products/classes/products.php'));
include_once($lC_Vqmod->modCheck('includes/applications/customer_groups/classes/customer_groups.php'));
//include_once($lC_Vqmod->modCheck('includes/applications/product_variants/classes/product_variants.php'));
//include_once($lC_Vqmod->modCheck('includes/applications/specials/classes/specials.php'));
//include_once($lC_Vqmod->modCheck('includes/applications/categories/classes/categories.php'));
//include_once($lC_Vqmod->modCheck('includes/classes/addons.php'));

class lC_Products_Admin_Pro extends lC_Products_Admin {
  
  public static function save($id = null, $data) {
    parent::save($id, $data);
    
    echo "<pre>";
    print_r($data);
    echo "</pre>";
    
    die('products pro');
  }
  
 /*
  *  Determine if product has qty price breaks
  *
  * @param integer $id The product id
  * @access public
  * @return array
  */
  public static function hasQPBPricing($id) {
    global $lC_Database;

    $Qpb = $lC_Database->query('select * from :table_products_pricing where products_id = :products_id limit 1');
    $Qpb->bindTable(':table_products_pricing', TABLE_PRODUCTS_PRICING);
    $Qpb->bindInt(':products_id', $id);
    $Qpb->execute();

    if ( $Qpb->numberOfRows() > 0 ) {
      return true;
    }

    return false;
  }
 /*
  *  Retrieve qty price breaks
  *
  * @param integer $id The product id
  * @access public
  * @return array
  */
  public static function getQPBPricing($id, $group = null) {
    global $lC_Database;

    if ($group == null) $group = (defined('DEFAULT_CUSTOMERS_GROUP_ID') && DEFAULT_CUSTOMERS_GROUP_ID != null) ? (int)DEFAULT_CUSTOMERS_GROUP_ID : 1;
    
    $Qpb = $lC_Database->query('select * from :table_products_pricing where products_id = :products_id and group_id = :group_id');
    $Qpb->bindTable(':table_products_pricing', TABLE_PRODUCTS_PRICING);
    $Qpb->bindInt(':products_id', $id);
    $Qpb->bindInt(':group_id', $group);
    $Qpb->execute();
    
    $data = $Qpb->toArray();
    
    $Qpb->freeResult();
    
    return $data;
  }  

 /*
  *  Return the qty price breaks listing content
  *
  * @access public
  * @return array
  */
  public static function getQPBPricingContent() {
    
ini_set('display_errors', 1);
    
    global $lC_Language, $lC_Currencies, $pInfo;
    
    $content = '';
    $groups = lC_Customer_groups_Admin::getAll();
    foreach($groups['entries'] as $key => $value) {

      if ($value['customers_group_id'] == DEFAULT_CUSTOMERS_GROUP_ID) { // locked to default for Pro
    
        $base = (isset($pInfo)) ? (float)$pInfo->get('products_price') : 0.00;
        $special = (isset($pInfo)) ? (float)$pInfo->get('products_special_price') : 0.00;

        $content .= '<label for="products_qty_breaks_pricing_enable' . $value['customers_group_id'] . '" class="label margin-right"><b>'. $value['customers_group_name'] .'</b></label>' .
        
                    '<div id="qpbContainer">' .
                    '  <div class="new-row-mobile twelve-columns">' .
                    '    <div class="inputs" style="display:inline; padding:8px 0;">' .
                    '      <span class="mid-margin-left no-margin-right">#</span>' .                  
                    '      <input type="text" onfocus="this.select();" name="products_qty_break_point[' . $value['customers_group_id'] . '][0]" id="products_qty_break_point_' . $value['customers_group_id'] . '_0" value="1" class="disabled input-unstyled small-margin-right" style="width:60px;" READONLY/>' .
                    '    </div>' .         
                    '    <small class="input-info mid-margin-left mid-margin-right no-wrap">Qty</small>' . 
                    '    <div class="inputs" style="display:inline; padding:8px 0;">' .
                    '      <span class="mid-margin-left no-margin-right">' . $lC_Currencies->getSymbolLeft() . '</span>' .
                    '      <input type="text" onfocus="this.select();" name="products_qty_break_price[' . $value['customers_group_id'] . '][0]" id="products_qty_break_price_' . $value['customers_group_id'] . '_0" value="' . number_format($pInfo->get('products_price'), DECIMAL_PLACES) . '" class="disabled input-unstyled small-margin-right" style="width:60px;" READONLY/>' .
                    '    </div>' . 
                    '    <small class="input-info mid-margin-left no-wrap">Price</small>' . 
                    '  </div>';

        if (self::hasQPBPricing($pInfo->get('products_id'))) {
          
          $qpbData = self::getQPBPricing($pInfo->get('products_id'), $value['customers_group_id']);

        } else { // no qpb recorded, setup new
          $content .= '  <div class="new-row-mobile twelve-columns small-margin-top">' .
                      '    <div class="inputs" style="display:inline; padding:8px 0;">' .
                      '      <span class="mid-margin-left no-margin-right">#</span>' .                  
                      '      <input type="text" onblur="validateQPBPoint(this);" onfocus="this.select();" name="products_qty_break_point[' . $value['customers_group_id'] . '][1]" id="products_qty_break_point_' . $value['customers_group_id'] . '_1" value="" class="input-unstyled small-margin-right" style="width:60px;" />' .
                      '    </div>' .         
                      '    <small class="input-info mid-margin-left mid-margin-right no-wrap">Qty</small>' . 
                      '    <div class="inputs" style="display:inline; padding:8px 0;">' .
                      '      <span class="mid-margin-left no-margin-right">' . $lC_Currencies->getSymbolLeft() . '</span>' .
                      '      <input type="text" onblur="validateQPBPrice(this);" onfocus="this.select();" name="products_qty_break_price[' . $value['customers_group_id'] . '][1]" id="products_qty_break_price_' . $value['customers_group_id'] . '_1" value="" class="input-unstyled small-margin-right" style="width:60px;" />' .
                      '    </div>' . 
                      '    <small class="input-info mid-margin-left no-wrap">Price</small>' . 
                      '  </div>';                      
        }      
        
        $content .= '</div>';                
      } 
    
    }
    
    return $content;
  }  
  
  
}