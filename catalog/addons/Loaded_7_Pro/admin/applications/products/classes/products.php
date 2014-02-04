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

include_once($lC_Vqmod->modCheck(DIR_FS_CATALOG . 'includes/applications/products/classes/products.php'));
include_once($lC_Vqmod->modCheck(DIR_FS_CATALOG . 'includes/applications/customer_groups/classes/customer_groups.php'));
//include_once($lC_Vqmod->modCheck('includes/applications/product_variants/classes/product_variants.php'));
//include_once($lC_Vqmod->modCheck('includes/applications/specials/classes/specials.php'));
//include_once($lC_Vqmod->modCheck('includes/applications/categories/classes/categories.php'));
//include_once($lC_Vqmod->modCheck('includes/classes/addons.php'));

class lC_Products_Admin_Pro extends lC_Products_Admin {
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
    $products_id = parent::save($id, $data);
    $group = (defined('DEFAULT_CUSTOMERS_GROUP_ID') && DEFAULT_CUSTOMERS_GROUP_ID != null) ? (int)DEFAULT_CUSTOMERS_GROUP_ID : 1;    
    
    if (is_array($data['products_qty_break_point'][$group]) && $data['products_qty_break_point'][$group][1] != null) {
      if ($products_id != null) {
        
        $lC_Database->startTransaction();    

        // remove any old pricing records
        $Qpricing = $lC_Database->query('delete from :table_products_pricing where products_id = :products_id');
        $Qpricing->bindTable(':table_products_pricing', TABLE_PRODUCTS_PRICING);
        $Qpricing->bindInt(':products_id', $products_id);
        $Qpricing->setLogging($_SESSION['module'], $products_id);
        $Qpricing->execute();
        
        if ( $lC_Database->isError() ) {
          $error = true;
        } else {        
          // add the new records
          foreach($data['products_qty_break_point'][$group] as $key => $val) {
            
            if ($val['qty_break'] == 1) continue; // do not save the base price in pricing table
            if ($data['products_qty_break_point'][$group][$key] == null) continue;
            
            $Qpb = $lC_Database->query('insert into :table_products_pricing (products_id, group_id, tax_class_id, qty_break, price_break, date_added) values (:products_id, :group_id, :tax_class_id, :qty_break, :price_break, :date_added)');
            $Qpb->bindTable(':table_products_pricing', TABLE_PRODUCTS_PRICING);
            $Qpb->bindInt(':products_id', $products_id );
            $Qpb->bindInt(':group_id', $group);
            $Qpb->bindInt(':tax_class_id', $data['tax_class_id'] );
            $Qpb->bindValue(':qty_break', $data['products_qty_break_point'][$group][$key] );
            $Qpb->bindValue(':price_break', $data['products_qty_break_price'][$group][$key] );
            $Qpb->bindRaw(':date_added', 'now()');
            $Qpb->setLogging($_SESSION['module'], $products_id);
            $Qpb->execute();
          }
        }        
        
        if ( $error === false ) {
          $lC_Database->commitTransaction();

          return $products_id; // Return the products id for use with the save_close buttons
        }

        $lC_Database->rollbackTransaction();        
      }
    }
  }
 /*
  *  Determine if product has qty price breaks
  *
  * @param integer $id The product id
  * @access public
  * @return boolean
  */
  public static function hasQPBPricing($id) {
    global $lC_Database;

    $Qpb = $lC_Database->query('select * from :table_products_pricing where products_id = :products_id limit 1');
    $Qpb->bindTable(':table_products_pricing', TABLE_PRODUCTS_PRICING);
    $Qpb->bindInt(':products_id', $id);
    $Qpb->execute();
    
    $rows = $Qpb->numberOfRows();
    
    $Qpb->freeResult();
    
    if ( $rows > 0 ) {
      return true;
    }
    
    return false;
  }
 /*
  *  Retrieve qty price breaks
  *
  * @param integer $id    The product id
  * @param integer $group The customer group id
  * @access public
  * @return array
  */
  public static function getQPBPricing($id, $group = null) {
    global $lC_Database;

    if ($group == null) $group = (defined('DEFAULT_CUSTOMERS_GROUP_ID') && DEFAULT_CUSTOMERS_GROUP_ID != null) ? (int)DEFAULT_CUSTOMERS_GROUP_ID : 1;
    
    $Qpb = $lC_Database->query('select * from :table_products_pricing where products_id = :products_id and group_id = :group_id order by qty_break asc');
    $Qpb->bindTable(':table_products_pricing', TABLE_PRODUCTS_PRICING);
    $Qpb->bindInt(':products_id', $id);
    $Qpb->bindInt(':group_id', $group);
    $Qpb->execute();
    
    $data = array();
    while($Qpb->next()) {
      $data[] = $Qpb->toArray();
    }
    
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
          
          $cnt = 0;
          foreach ($qpbData as $key => $val) {
            $content .= self::_getNewRow($val['group_id'], $key+1, $val);
            $cnt = $key+1;
          }
          // add a new row
          $content .= self::_getNewRow($value['customers_group_id'], $cnt+1);
        } else { // no qpb recorded, setup new
          $content .= self::_getNewRow($value['customers_group_id'], 1);
        }      
        
        $content .= '</div>';                
      } 
    }
    
    return $content;
  } 
 /*
  *  Retrieve qty price break row
  *
  * @param integer $group The customer group id
  * @param integer $cnt   The product id
  * @param array   $data  The product data
  * @access private
  * @return string
  */
  private static function _getNewRow($group, $cnt, $data = array()) {
    global $lC_Currencies;

    $content = '  <div class="new-row-mobile twelve-columns small-margin-top">' .
               '    <div class="inputs" style="display:inline; padding:8px 0;">' .
               '      <span class="mid-margin-left no-margin-right">#</span>' .                  
               '      <input type="text" onblur="validateQPBPoint(this);" onfocus="this.select();" name="products_qty_break_point[' . $group . '][' . $cnt . ']" id="products_qty_break_point_' . $group . '_' . $cnt . '" value="' . $data['qty_break'] . '" class="input-unstyled small-margin-right" style="width:60px;" />' .
               '    </div>' .         
               '    <small class="input-info mid-margin-left mid-margin-right no-wrap">Qty</small>' . 
               '    <div class="inputs" style="display:inline; padding:8px 0;">' .
               '      <span class="mid-margin-left no-margin-right">' . $lC_Currencies->getSymbolLeft() . '</span>' .
               '      <input type="text" onblur="validateQPBPrice(this);" onfocus="this.select();" name="products_qty_break_price[' . $group . '][' . $cnt . ']" id="products_qty_break_price_' . $group . '_' . $cnt . '" value="' . (($data['qty_break'] != null) ? number_format($data['price_break'], DECIMAL_PLACES) : null) . '" class="input-unstyled small-margin-right" style="width:60px;" />' .
               '    </div>' . 
               '    <small class="input-info mid-margin-left no-wrap">Price</small>' . 
               '  </div>'; 
                 
    return $content;
  } 
}