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

include_once($lC_Vqmod->modCheck(DIR_FS_ADMIN . 'includes/applications/products/classes/products.php'));
include_once($lC_Vqmod->modCheck(DIR_FS_ADMIN . 'includes/applications/customer_groups/classes/customer_groups.php'));

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
            
            if ($data['products_qty_break_point'][$group][$key] == null) continue;
            if ($data['products_qty_break_point'][$group][$key] == '1') continue;
            
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
            $content .= self::_getNewSubProductsRow($val['group_id'], $key+1, $val);
            $cnt = $key+1;
          }
          // add a new row
          $content .= self::_getNewSubProductsRow($value['customers_group_id'], $cnt+1);
        } else { // no qpb recorded, setup new
          $content .= self::_getNewSubProductsRow($value['customers_group_id'], 1);
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
  private static function _getNewSubProductsRow($group, $cnt, $data = array()) {
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
 /*
  *  Return the multi sku options listing content
  *
  * @param array $options The multi sku options array
  * @access public
  * @return array
  */  
  public static function getComboOptionsContent($options = array()) {
    $content = '';
    
    $content .= self::_getComboOptionsTbody($options);
    
    return $content;
  }
 /*
  *  Return the product simple options accordian price listing content
  *
  * @access public
  * @return array
  */
  public static function getComboOptionsPricingContent() {
    global $lC_Language, $pInfo;
    
    $content = '';
    $groups = lC_Customer_groups_Admin::getAll();
    foreach($groups['entries'] as $key => $value) {
      $content .= '<dt><span class="strong">' . $value['customers_group_name'] . '</span></dt>' .
                  '<dd>' .
                  '  <div class="with-padding">';
                  
      if (isset($pInfo) && is_array($pInfo->get('simple_options'))) {                  
        $content .= '    <div class="big-text underline" style="padding-bottom:8px;">' . $lC_Language->get('text_simple_options') . '</div>' .
                    '    <table style="" id="simpleOptionsPricingTable" class="simple-table">' .
                    '      <tbody id="tbody-' . $value['customers_group_id'] . '">' . self::_getComboOptionsPricingTbody($pInfo->get('simple_options'), $value['customers_group_id']) . '</tbody>' .
                    '    </table>';
                    
      } else if (isset($pInfo) && $pInfo->get('has_subproducts') == '1') {
        $content .= '    <div class="big-text underline" style="padding-bottom:8px;">' . $lC_Language->get('text_sub_products') . '</div>' .
                    '    <table id="subProductsPricingTable" class="simple-table">' .
                    '      <tbody id="tbody-' . $value['customers_group_id'] . '">' . self::_getComboOptionsPricingTbody($pInfo, $value['customers_group_id']) . '</tbody>' .
                    '    </table>';        
        
      } else if (isset($pInfo) && $pInfo->get('has_variants') == '1') {
      
      } else {      
        $content .= '<table class="simple-table"><tbody id="tbody-' . $value['customers_group_id'] . '"><tr id="no-options-' . $value['customers_group_id'] . '"><td>' . $lC_Language->get('text_no_options_defined') . '</td></tr></tbody></table>'; 
      }
                
      $content .= '  </div>' .
                  '</dd>';  
    }
    
    return $content;
  }
 /*
  * Determine if the product has subproducts
  *
  * @param integer $id The product id
  * @access public
  * @return boolean
  */   
  public static function hasSubProducts($id) {
    global $lC_Database;

    $Qchk = $lC_Database->query('select products_id from :table_products where parent_id = :parent_id and is_subproduct > :is_subproduct limit 1');
    $Qchk->bindTable(':table_products', TABLE_PRODUCTS);
    $Qchk->bindInt(':parent_id', $id);
    $Qchk->bindInt(':is_subproduct', 0);
    $Qchk->execute();

    if ( $Qchk->numberOfRows() === 1 ) {
      return true;
    }

    return false;
  }  
 /*
  * Return the combo options pricing content
  *
  * @param array $data The product data object
  * @access public
  * @return string
  */  
  public static function getComboOptionsPricingTbody($pInfo, $customers_group_id) {
    global $lC_Currencies;
    
    if ($customers_group_id == '') return false;  
    $ok = (defined('ADDONS_SYSTEM_LOADED_7_PRO_STATUS') && ADDONS_SYSTEM_LOADED_7_PRO_STATUS == '1') ? true : false;
    
    $tbody = ''; 
    $cnt = 0; 
    if (isset($pInfo) && $pInfo->get('has_children') == '1') {
      
      foreach ($pInfo->get('variants') as $product_id => $val) {       
        $title = '';
        if (is_array($val['values'])) {
          foreach ($val['values'] as $group_id => $value_id) {
            foreach ($value_id as $value) {
              $title .= $value['value_title'] . ', ';
            }
          }
        }
        if (strstr($title, ',')) $title = substr($title, 0, -2);
        
        if ((isset($title) && $title != NULL)) {
          $tbody .= '<tr class="trp-' . $cnt . '">' .
                    '  <td id="name-td-' . $cnt . '" class="element">' . $title . '</td>' . 
                    '  <td>' .
                    '    <div class="inputs' . (($customers_group_id == '1' || $ok) ? '' : ' disabled') . '" style="display:inline; padding:8px 0;">' .
                    '      <span class="mid-margin-left no-margin-right">' . $lC_Currencies->getSymbolLeft() . '</span>' .
                    '      <input type="text" class="input-unstyled" onfocus="$(this).select()" value="' . $val['data']['price'] . '" id="variants_' . $product_id . '_price_' . $customers_group_id . '" name="variants[' . $product_id . '][price][' . $customers_group_id . ']" ' . (($customers_group_id == '1' || $ok) ? '' : ' DISABLED') . '>' .
                    '    </div>' .
                    '  </td>' .
                    '</tr>';
          $cnt++;                    
        } 
      }
    }    
    
    return $tbody;
  }          
 /*
  * Return the product simple options tbody content
  *
  * @param array $options The product simple options array
  * @access public
  * @return string
  */  
  private static function _getComboOptionsTbody($options) {
  	global $lC_Currencies;
		
    $tbody = '';  	
		  
    if (isset($options) && !empty($options)) {
      foreach ($options as $product_id => $mso) {     	
        
        $combo = '';
				$default = '';
				$module = '';
        $comboInput = '';
        if (is_array($mso['values'])) {
          foreach ($mso['values'] as $group_id => $value_id) {
          	foreach ($value_id as $data) {						    
              $combo .= $data['value_title'] . ', ';
							$module = $data['module'];	
							$default = $data['default'];						
							$comboInput .= '<input type="hidden" id="variants_' . $product_id .'_values_' . $group_id . '_' . $value_id . '"  name="variants[' . $product_id .'][\'values\'][' . $group_id . '][' . $value_id . ']">';          							
					  }
          }
					if (strstr($combo, ',')) $combo = substr($combo, 0, -2);
          
          $statusIcon = (isset($mso['data']['status']) && $mso['data']['status'] == '1') ? '<span id="variants_status_span_' . $product_id .'" class="icon-tick icon-size2 icon-green with-tooltip" data-tooltip-options=\'{"classes":["grey-gradient"],"position":"left"}\' title="Set Status"></span><input type="hidden" id="variants_status_' . $product_id .'" name="variants[' . $product_id . '][status]" value="1">' : '<span id="variants_status_span_' . $product_id .'" class="icon-cross icon-size2 icon-red with-tooltip" data-tooltip-options=\'{"classes":["grey-gradient"],"position":"left"}\' title="Set Status"></span><input type="hidden" id="variants_status_' . $product_id .'" name="variants[' . $product_id . '][status]" value="0">';
          $defaultIcon = (isset($default) && $default == '1') ? '<span id="variants_default_combo_span_' . $product_id .'" class="default-combo-span icon-star icon-size2 icon-orange with-tooltip" data-tooltip-options=\'{"classes":["grey-gradient"],"position":"left"}\' title="Default Combo"></span><input class="default-combo" type="hidden" id="variants_default_combo_' . $product_id .'" name="variants[' . $product_id . '][default_combo]" value="1">' : '<span id="variants_default_combo_span_' . $product_id .'" class="default-combo-span icon-star icon-size2 icon-grey with-tooltip" data-tooltip-options=\'{"classes":["grey-gradient"],"position":"left"}\' title="Set Default Combo"></span><input class="default-combo" type="hidden" id="variants_default_combo_' . $product_id .'" name="variants[' . $product_id . '][default_combo]" value="0">';          
					          
          $tbody .= '<tr id="trmso-' . $product_id .'">' .
                    '  <td width="16px" style="cursor:move;"><span class="icon-list icon-grey icon-size2"></span></td>' .
                    '  <td width="25%">' . $combo . '</td>' .
                    '  <td width="16px" style="cursor:pointer;" onclick="toggleMultiSKUOptionsFeatured(\'' . $product_id . '\');">' . $defaultIcon . '</td>' .                    
                    '  <td><input type="text" class="input half-width" onfocus="this.select();" tabindex="' . $product_id . '2" name="variants[' . $product_id . '][weight]" value="' . $mso['data']['weight'] . '"></td>' .
                    '  <td><input type="text" class="input half-width" onfocus="this.select();" tabindex="' . $product_id . '3" name="variants[' . $product_id . '][sku]" value="' . $mso['data']['sku'] . '"></td>' .
                    '  <td><input type="text" class="input half-width" onfocus="this.select();" tabindex="' . $product_id . '4" name="variants[' . $product_id . '][qoh]" value="' . $mso['data']['quantity'] . '"></td>' .
                    '  <td style="white-space:nowrap;">
                         <div class="inputs" style="display:inline; padding:8px 0;">
                           <span class="mid-margin-left no-margin-right">' . $lC_Currencies->getSymbolLeft() . '</span>
                           <input type="text" class="input-unstyled" style="width:87%;" onfocus="this.select();" value="' . $mso['data']['price'] . '" tabindex="' . $product_id . '5" name="variants[' . $product_id . '][price]">
                         </div>
                       </td>' .
			              '  <td class="align-center align-middle">' .
			              '    <input style="display:none;" type="file" id="multi_sku_image_' . $product_id . '" name="variants[' . $product_id . '][image]" onchange="setMultiSKUImage(\'' . $product_id . '\');" multiple />' .
			              '    <span class="icon-camera icon-size2 cursor-pointer with-tooltip ' . ((isset($mso['data']['image']) && $mso['data']['image'] != null) ? 'icon-green' : 'icon-grey') . '" title="' . ((isset($mso['data']['image']) && $mso['data']['image'] != null) ? $mso['data']['image'] : null) . '" id="fileSelectButtonMultiSKU-' . $product_id . '" onclick="document.getElementById(\'multi_sku_image_' . $product_id . '\').click();"></span>' .
			              '  </td>' .                       
                    '  <td width="16px" align="center" style="cursor:pointer;" onclick="toggleMultiSKUOptionsStatus(\'' . $product_id . '\');">' . $statusIcon . '</td>' .
                    '  <td width="40px" align="right">
                         <span class="icon-pencil icon-orange icon-size2 margin-right with-tooltip" data-tooltip-options=\'{"classes":["grey-gradient"],"position":"left"}\' title="Edit Entry" style="cursor:pointer;" onclick="addMultiSKUOption(\'' . $product_id. '\')"></span>
                         <span class="icon-trash icon-size2 icon-red with-tooltip" data-tooltip-options=\'{"classes":["grey-gradient"],"position":"right"}\' title="Remove Entry" style="cursor:pointer;" onclick="removeMultiSKUOptionsRow(\'' . $product_id . '\');"></span>
                       </td>' .
                    '</tr>';
        }
      }
    }
    
    return $tbody;    
  }  
  
 /*
  * Return the sub products pricing content
  *
  * @param array $data The product data object
  * @access public
  * @return string
  */  
  public static function getSubProductsPricingTbody($pInfo, $customers_group_id) {
    global $lC_Currencies;
    
    if ($customers_group_id == '') return false;  
    $ok = (defined('ADDONS_SYSTEM_LOADED_7_PRO_STATUS') && ADDONS_SYSTEM_LOADED_7_PRO_STATUS == '1') ? true : false;
    
    $tbody = ''; 
    $cnt = 0; 
    if (isset($pInfo) && $pInfo->get('has_subproducts') == '1') {
      foreach ($pInfo->get('subproducts') as $key => $sub) {
        if ((isset($sub['products_name']) && $sub['products_name'] != NULL)) {

          $tbody .= '<tr class="trp-' . $cnt . '">' .
                    '  <td id="name-td-' . $cnt . '" class="element">' . $sub['products_name'] . '</td>' . 
                    '  <td>' .
                    '    <div class="inputs' . (($customers_group_id == '1' || $ok) ? '' : ' disabled') . '" style="display:inline; padding:8px 0;">' .
                    '      <span class="mid-margin-left no-margin-right">' . $lC_Currencies->getSymbolLeft() . '</span>' .
                    '      <input type="text" class="input-unstyled" onfocus="$(this).select()" value="' . $sub['products_price'] . '" id="sub_products_price_' . $customers_group_id . '_' . $cnt . '" name="sub_products_price[' . $customers_group_id . '][' . $cnt . ']" ' . (($customers_group_id == '1' || $ok) ? '' : ' DISABLED') . '>' .
                    '    </div>' .
                    '  </td>' .
                    '</tr>';
          $cnt++;                    
        } 
      }
    }    
    
    return $tbody;
  }    
  
  
 /*
  *  Return the product simple options accordian price listing content
  *
  * @access public
  * @return array
  */
  public static function getOptionsPricingContent() {
    global $lC_Language, $pInfo;
    
    $content = '';
    $groups = lC_Customer_groups_Admin::getAll();
    foreach($groups['entries'] as $key => $value) {
      $content .= '<dt><span class="strong">' . $value['customers_group_name'] . '</span></dt>' .
                  '<dd>' .
                  '  <div class="with-padding">';
                  
      if (isset($pInfo) && is_array($pInfo->get('simple_options'))) {                  
        $content .= '    <div class="big-text underline" style="padding-bottom:8px;">' . $lC_Language->get('text_simple_options') . '</div>' .
                    '    <table style="" id="simpleOptionsPricingTable" class="simple-table">' .
                    '      <tbody id="tbody-' . $value['customers_group_id'] . '">' . lC_Products_Admin::_getSimpleOptionsPricingTbody($pInfo->get('simple_options'), $value['customers_group_id']) . '</tbody>' .
                    '    </table>';
                    
      } else if (isset($pInfo) && $pInfo->get('has_subproducts') == '1') {               
        $content .= '    <div class="big-text underline" style="padding-bottom:8px;">' . $lC_Language->get('text_sub_products') . '</div>' .
                    '    <table id="subProductsPricingTable" class="simple-table">' .
                    '      <tbody id="tbody-' . $value['customers_group_id'] . '">' . lC_Products_Admin_Pro::getSubProductsPricingTbody($pInfo, $value['customers_group_id']) . '</tbody>' .
                    '    </table>';        
        
      } else if (isset($pInfo) && $pInfo->get('has_children') == '1') {
        $content .= '    <div class="big-text underline" style="padding-bottom:8px;">' . $lC_Language->get('text_combo_options') . '</div>' .
                    '    <table id="comboOptionsPricingTable" class="simple-table">' .
                    '      <tbody id="tbody-' . $value['customers_group_id'] . '">' . lC_Products_Admin_Pro::getComboOptionsPricingTbody($pInfo, $value['customers_group_id']) . '</tbody>' .
                    '    </table>';         
      } else {      
        $content .= '<table class="simple-table"><tbody id="tbody-' . $value['customers_group_id'] . '"><tr id="no-options-' . $value['customers_group_id'] . '"><td>' . $lC_Language->get('text_no_options_defined') . '</td></tr></tbody></table>'; 
      }
                
      $content .= '  </div>' .
                  '</dd>';  
    }
    
    return $content;
  }  
}