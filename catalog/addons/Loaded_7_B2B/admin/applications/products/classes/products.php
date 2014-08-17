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
    
    $products_id = lC_Products_pro_Admin::save($id, $data); 
    
    $error = false;
    
    $lC_Database->startTransaction();    
    
    // remove all old pricing records
    $Qpricing = $lC_Database->query('delete from :table_products_pricing where products_id = :products_id or parent_id = :products_id');
    $Qpricing->bindTable(':table_products_pricing', TABLE_PRODUCTS_PRICING);
    $Qpricing->bindInt(':products_id', $products_id);
    $Qpricing->bindInt(':products_id', $products_id);
    $Qpricing->setLogging($_SESSION['module'], $products_id);
    $Qpricing->execute(); 
    
    $qpb_pricing_enabled = (isset($data['qpb_pricing_switch']) && $data['qpb_pricing_switch'] == 1) ? true : false;
    $groups_pricing_enabled = (isset($data['groups_pricing_switch']) && $data['groups_pricing_switch'] == 1) ? true : false;
    $specials_pricing_enabled = (isset($data['specials_pricing_switch']) && $data['specials_pricing_switch'] == 1) ? true : false;
    
    if ( $lC_Database->isError() ) {
      $error = true;
    } else {      
      // add qty price breaks
      if (is_array($data['products_qty_break_point']) && !empty($data['products_qty_break_point'])) {
        if ($products_id != null) {
         
          // add the new records
          foreach($data['products_qty_break_point'] as $group => $values) {
            if (is_array($data['products_qty_break_point'][$group]) && $data['products_qty_break_point'][$group][1] != null) {          
              foreach($values as $key => $val) {
              
                if ($val == -1) continue;
                if ($data['products_qty_break_point'][$group][$key] == null) continue;
                if ((int)$data['products_qty_break_point'][$group][$key] == 1) continue;  // do not save qty 1, base price is same
                
                $price = (is_array($data['options_pricing']) && !empty($data['options_pricing'])) ? 0.00 : $data['products_qty_break_price'][$group][$key]; // for options support
                
                $Qpb = $lC_Database->query('insert into :table_products_pricing (products_id, group_id, tax_class_id, qty_break, price_break, date_added) values (:products_id, :group_id, :tax_class_id, :qty_break, :price_break, :date_added)');
                $Qpb->bindTable(':table_products_pricing', TABLE_PRODUCTS_PRICING);
                $Qpb->bindInt(':products_id', $products_id );
                $Qpb->bindInt(':group_id', $group);
                $Qpb->bindInt(':tax_class_id', $data['tax_class_id'] );
                $Qpb->bindValue(':qty_break', $data['products_qty_break_point'][$group][$key] );
                $Qpb->bindFloat(':price_break', $price );
                $Qpb->bindRaw(':date_added', 'now()');
                $Qpb->setLogging($_SESSION['module'], $products_id);
                $Qpb->execute();
                
                if ( $lC_Database->isError() ) { 
die($lC_Database->getError());              
                  
                  $error = true;
                  break 2;
                }                
              }
            }
          }

          // add qpb for options
          if (is_array($data['options_pricing']) && !empty($data['options_pricing'])) {
            
            $parent_id = $products_id;
            
            foreach($data['options_pricing'] as $product_id => $groups) {
              foreach($groups as $group_id => $data) {
                foreach($data as $qty_break => $price) {
                  $Qpb2 = $lC_Database->query('insert into :table_products_pricing (products_id, parent_id, group_id, tax_class_id, qty_break, price_break, date_added) values (:products_id, :parent_id, :group_id, :tax_class_id, :qty_break, :price_break, :date_added)');
                  $Qpb2->bindTable(':table_products_pricing', TABLE_PRODUCTS_PRICING);
                  $Qpb2->bindInt(':products_id', $product_id );
                  $Qpb2->bindInt(':parent_id', $parent_id );
                  $Qpb2->bindInt(':group_id', $group_id);
                  $Qpb2->bindInt(':tax_class_id', $data['tax_class_id'] );
                  $Qpb2->bindValue(':qty_break', $qty_break );
                  $Qpb2->bindFloat(':price_break', number_format((float)$price, DECIMAL_PLACES) );
                  $Qpb2->bindRaw(':date_added', 'now()');
                  $Qpb2->setLogging($_SESSION['module'], $product_id);
                  $Qpb2->execute();
                  
                  if ( $lC_Database->isError() ) {
die($lC_Database->getError());              
                    $error = true;
                    break 3;
                  }   
                  if ($qty_break == 1) self::_updateBasePrice($product_id, number_format((float)$price, DECIMAL_PLACES)); 
                }  
              }  
            }
          }          
        }
      }
    }
    if ($error === false) {    
      // add group pricing
      if (is_array($data['group_pricing']) && !empty($data['group_pricing'])) {
        if ($products_id != null) {
          // add the new records
          foreach($data['group_pricing'] as $group => $values) {
            $Qgp = $lC_Database->query('insert into :table_products_pricing (products_id, group_id, tax_class_id, group_status, group_price, date_added) values (:products_id, :group_id, :tax_class_id, :group_status, :group_price, :date_added)');
            $Qgp->bindTable(':table_products_pricing', TABLE_PRODUCTS_PRICING);
            $Qgp->bindInt(':products_id', $products_id );
            $Qgp->bindInt(':group_id', $group);
            $Qgp->bindInt(':tax_class_id', $data['tax_class_id'] );
            $Qgp->bindValue(':group_status', (($values['enable'] == 'on') ? 1 : 0));
            $Qgp->bindValue(':group_price', number_format($values['price'], DECIMAL_PLACES));
            $Qgp->bindRaw(':date_added', 'now()');
            $Qgp->setLogging($_SESSION['module'], $products_id);
            $Qgp->execute(); 
                
            if ( $lC_Database->isError() ) {
die($lC_Database->getError());              
              
              $error = true;
              break;
            }                     
          }
        }      
      }
    }
    
    if ($error === false) {
      // add special pricing
      if (is_array($data['products_special_pricing']) && !empty($data['products_special_pricing'])) {
        if ($products_id != null) {
          
          // add the new records
          foreach($data['products_special_pricing'] as $group => $values) {
            $Qsp = $lC_Database->query('insert into :table_products_pricing (products_id, group_id, tax_class_id, special_status, special_price, special_start, special_end, date_added) values (:products_id, :group_id, :tax_class_id, :special_status, :special_price, :special_start, :special_end, :date_added)');
            $Qsp->bindTable(':table_products_pricing', TABLE_PRODUCTS_PRICING);
            $Qsp->bindInt(':products_id', $products_id );
            $Qsp->bindInt(':group_id', $group);
            $Qsp->bindInt(':tax_class_id', $data['tax_class_id'] );
            $Qsp->bindValue(':special_status', (($values['enable'] == 'on') ? 1 : 0));
            $Qsp->bindValue(':special_price', number_format($values['price'], DECIMAL_PLACES));
            $Qsp->bindRaw(':date_added', 'now()');
            $Qsp->bindRaw(':special_start', "'" . ((strstr($values['start_date'], '/')) ? lC_DateTime::toDateTime($values['start_date']) : $values['start_date']) . "'");
            $Qsp->bindRaw(':special_end', "'" . ((strstr($values['expires_date'], '/')) ? lC_DateTime::toDateTime($values['expires_date']) : $values['expires_date']) . "'");
            $Qsp->setLogging($_SESSION['module'], $products_id);
            $Qsp->execute();          
            
            if ( $lC_Database->isError() ) { 
die($lC_Database->getError());              
              $error = true;
              break;
            }            
          }
        }      
      }   
    } 
   
    if ( $error === false ) {
      $lC_Database->commitTransaction();
      
      lC_Cache::clear('categories');
      lC_Cache::clear('category_tree');
      lC_Cache::clear('also_purchased');
      
      return $products_id; // Return the products id for use with the save_close buttons
    }
    
    $lC_Database->rollbackTransaction();
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
    global $lC_Language, $lC_Currencies, $lC_Database, $pInfo;
    
    $products_id = (isset($pInfo)) ? $pInfo->get('products_id') : null;
    
    $content = '';
    $groups = lC_Customer_groups_Admin::getAll();
    
    foreach($groups['entries'] as $key => $value) {
      $group_status = 0;
      if ($products_id != null) {    
        $Qpricing = $lC_Database->query('select * from :table_products_pricing where products_id = :products_id and group_id = :group_id and group_status != :group_status order by qty_break asc');
        $Qpricing->bindTable(':table_products_pricing', TABLE_PRODUCTS_PRICING);
        $Qpricing->bindInt(':products_id', $products_id);
        $Qpricing->bindInt(':group_id', $value['customers_group_id']);
        $Qpricing->bindInt(':group_status', -1);
        $Qpricing->execute(); 
        
        $group_status = $Qpricing->valueInt('group_status');
        $diff = (float)$base_price - $Qpricing->valueDecimal('group_price');
        $discount_text = number_format(round(($diff / $base_price)  * 100, DECIMAL_PLACES), DECIMAL_PLACES); 
        $discounted_price = $Qpricing->valueDecimal('group_price');
      
      } else {      
        $discount = round((float)$base_price * ((float)$value['baseline_discount'] * .01), DECIMAL_PLACES); 
        $discounted_price = $base_price - $discount;
        $discount_text = number_format($value['baseline_discount'], DECIMAL_PLACES);
      }
      
      $checked = ($group_status == 0) ? null : 'checked="checked"';
     
      $content .= '<div>' .
                  '  <label for="" class="label margin-right"><b>'. $value['customers_group_name'] .'</b></label>' .
                  '  <input type="checkbox" ' . $checked . ' name="group_pricing[' . $value['customers_group_id'] . '][enable]" class="switch medium margin-right" />' .
                  '    <div class="inputs grey" style="display:inline; padding:8px 0;">' .
                  '      <span class="mid-margin-left no-margin-right">' . $lC_Currencies->getSymbolLeft() . '</span>' .
                  '      <input type="text" onfocus="this.select();" name="group_pricing[' . $value['customers_group_id'] . '][price]" id="group_pricing_' . $value['customers_group_id'] . '_price" value="' . number_format($discounted_price, DECIMAL_PLACES) . '" class="input-unstyled small-margin-right grey" style="width:60px;"/>' .
                  '    </div>' .
                  '  <small class="input-info mid-margin-left no-wrap">' . $lC_Language->get('text_price') . '<span class="tag glossy mid-margin-left">-' . $discount_text . '%</span></small>' . 
                  '</div>';
    }
    
    return $content;
  }
 /*
  *  Return the product specials price listing content
  *
  * @access public
  * @return array
  */
  public static function getSpecialPricingContent() {
    global $lC_Language, $lC_Currencies, $lC_Database, $pInfo;
    
    $products_id = (isset($pInfo)) ? $pInfo->get('products_id') : null;
    
    $content = '';
    $groups = lC_Customer_groups_Admin::getAll();
    
    $has_options = (isset($pInfo) && (lC_Products_pro_Admin::hasComboOptions($products_id) || lC_Products_pro_Admin::hasSubProducts($products_id))) ? true : false;
    
    foreach($groups['entries'] as $key => $value) {
    
      $special_status = 0;
      $base = (isset($pInfo)) ? (float)$pInfo->get('products_price') : 0.00;

      if ($products_id != null) {    
        $Qpricing = $lC_Database->query('select * from :table_products_pricing where products_id = :products_id and group_id = :group_id and special_status != :special_status order by group_id asc');
        $Qpricing->bindTable(':table_products_pricing', TABLE_PRODUCTS_PRICING);
        $Qpricing->bindInt(':products_id', $products_id);
        $Qpricing->bindInt(':group_id', $value['customers_group_id']);
        $Qpricing->bindInt(':special_status', -1);
        $Qpricing->execute(); 
        
        $special_status = ($Qpricing->valueInt('special_status') == 1) ? ' checked' : '';
        $special_price = number_format($Qpricing->valueDecimal('special_price'), DECIMAL_PLACES);
        
        $start_date_formatted = null;
        if ($Qpricing->value('special_start') != null) {
          $start_date_formatted = lC_DateTime::getShort($Qpricing->value('special_start'));
        }
        $expires_date_formatted = null;
        if ($Qpricing->value('special_end') != null) {
          $expires_date_formatted = lC_DateTime::getShort($Qpricing->value('special_end'));
        }        
        
      } else {   
        $special_status = (isset($pInfo) && $pInfo->get('products_special_status') != 0) ? ' checked' : null;  
        $special_price = (isset($pInfo)) ? number_format($pInfo->get('products_special_price'), DECIMAL_PLACES) : 0.00;      
        $start_date_formatted = (isset($pInfo)) ? lC_DateTime::getShort($pInfo->get('products_special_start_date')) : null;
        $expires_date_formatted = (isset($pInfo)) ? lC_DateTime::getShort($pInfo->get('products_special_expires_date')) : null;
      }
      
      $discount = (isset($base) && $base > 0.00) ? round( ((($base - $special_price) / $base) * 100), DECIMAL_PLACES) : 0.00; 
      
      $checked = ($special_status == 0) ? null : 'checked="checked"';

      $content .= '<label for="products_special_pricing_enable' . $value['customers_group_id'] . '" class="label margin-right"><b>'. $value['customers_group_name'] .'</b></label>' .
                  '<div class="columns">' .
                  '  <div class="new-row-mobile twelve-columns twelve-columns-mobile mid-margin-bottom">' .
                  '    <input id="products_special_pricing_pricing_' . $value['customers_group_id'] . '_enable" name="products_special_pricing[' . $value['customers_group_id'] . '][enable]" type="checkbox" ' . $checked . ' class="margin-right medium switch"' . $special_status . ' />';
      if ($has_options === false) {                  
        $content .= '    <div class="inputs" style="display:inline; padding:8px 0;">' .
                    '      <span class="mid-margin-left no-margin-right">' . $lC_Currencies->getSymbolLeft() . '</span>' .
                    '      <input type="text" onfocus="this.select();" onchange="updatePricingDiscountDisplay();" name="products_special_pricing[' . $value['customers_group_id'] . '][price]" id="products_special_pricing_' . $value['customers_group_id'] . '_price" value="' . $special_price . '" class="sprice input-unstyled small-margin-right" style="width:60px;" />' .
                    '    </div>' .
                    '    <small class="input-info mid-margin-left no-wrap">' . $lC_Language->get('text_special_price') . '<span class="disctag tag glossy mid-margin-left">-' . number_format($discount, DECIMAL_PLACES) . '%</span></small>';
      }
      $content .= '  </div>' .
                  '  <div class="new-row-mobile twelve-columns twelve-columns-mobile">' .
                  '    <span class="nowrap margin-right">' .
                  '      <span class="input small-margin-top">' .
                  '        <input name="products_special_pricing[' . $value['customers_group_id'] . '][start_date]" id="products_special_pricing_' . $value['customers_group_id'] . '_start_date" type="text" placeholder="Start" class="input-unstyled datepicker" value="' . $start_date_formatted . '" style="width:97px;" />' .
                  '      </span>' .
                  '      <span class="icon-calendar icon-size2 small-margin-left"></span>' .
                  '    </span>' .
                  '    <span class="nowrap">' .
                  '      <span class="input small-margin-top">' .
                  '        <input name="products_special_pricing[' . $value['customers_group_id'] . '][expires_date]" id="products_special_pricing_' . $value['customers_group_id'] . '_expires_date" type="text" placeholder="End" class="input-unstyled datepicker" value="' . $expires_date_formatted . '" style="width:97px;" />' .
                  '      </span>' .
                  '      <span class="icon-calendar icon-size2 small-margin-left"></span>' .
                  '    </span>' .
                  '  </div>' .
                  '</div>';
    }
    
    return $content;   
  }
 /*
  *  Determine if product has group pricing
  *
  * @param integer $id The product id
  * @access public
  * @return boolean
  */   
  public static function hasGroupPricing($id) {
    global $lC_Database;

    $Qgp = $lC_Database->query('select * from :table_products_pricing where products_id = :products_id and group_status = :group_status limit 1');
    $Qgp->bindTable(':table_products_pricing', TABLE_PRODUCTS_PRICING);
    $Qgp->bindInt(':products_id', $id);
    $Qgp->bindInt(':group_status', 1);
    $Qgp->execute();
    
    $rows = $Qgp->numberOfRows();
    
    $Qgp->freeResult();
    
    if ( $rows > 0 ) {
      return true;
    }
    
    return false;
  }  
 /*
  *  Update main product price
  *
  * @param integer $id The product id
  * @access public
  * @return boolean
  */   
  private static function _updateBasePrice($products_id, $products_price) {
    global $lC_Database;

    $Qupdate = $lC_Database->query('update :table_products set products_price = :products_price where products_id = :products_id');
    $Qupdate->bindTable(':table_products', TABLE_PRODUCTS);
    $Qupdate->bindInt(':products_id', $products_id);
    $Qupdate->bindFloat(':products_price', $products_price);
    $Qupdate->execute();
    
    if ( $lC_Database->isError() ) {
      return false;
    }
    
    return true;
  }  
}