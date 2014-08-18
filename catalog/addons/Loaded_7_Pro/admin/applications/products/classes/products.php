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

class lC_Products_pro_Admin extends lC_Products_Admin {
 /*
  * Save the product
  *
  * @param integer $id The products id to update, null on insert
  * @param array $data The products information
  * @access public
  * @return boolean
  */ 
  public static function save($id = null, $data) {
    global $lC_Database, $lC_Language, $lC_Image;  
    
    $error = false;
    
    $products_id = parent::save($id, $data);   
    
    $group = (defined('DEFAULT_CUSTOMERS_GROUP_ID') && DEFAULT_CUSTOMERS_GROUP_ID != null) ? (int)DEFAULT_CUSTOMERS_GROUP_ID : 1;    
                       
    // qty price breaks
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
        }
        
        $lC_Database->rollbackTransaction();        
      }
    }
    
    // subproducts
    if ( $error === false ) {
      if (isset($data['sub_products_name'])) {

        if (is_numeric($id)) {
          // first delete the subproducts that have been removed and not in the post data
          $Qchk = $lC_Database->query('select products_id from :table_products where parent_id = :parent_id');
          $Qchk->bindTable(':table_products', TABLE_PRODUCTS);
          $Qchk->bindInt(':parent_id', $products_id);
          $Qchk->execute();
          
          while( $Qchk->next() ) {
            if (! @in_array($Qchk->valueInt('products_id'), $data['sub_products_id'])) {
              self::delete($Qchk->valueInt('products_id'));              
            }
          }
        } else { 
          // delete any possible ghosts for sanity
          $Qdel = $lC_Database->query('delete from :table_products where parent_id = :products_id and is_subproduct = :is_subproduct');
          $Qdel->bindTable(':table_products', TABLE_PRODUCTS);
          $Qdel->bindInt(':parent_id', $products_id);
          $Qdel->bindInt(':is_subproduct', 1);
          $Qdel->execute();          
        }        

        for ($i=0; $i < sizeof($data['sub_products_name']); $i++) {
          if ($data['sub_products_name'][$i] == '') continue;

          if (is_numeric($id) && @in_array($data['sub_products_id'][$i], $data['sub_products_id'])) {
            // update the subproduct record
            $Qsubproduct = $lC_Database->query('update :table_products set products_quantity = :products_quantity, products_cost = :products_cost, products_price = :products_price, products_sku = :products_sku, products_weight = :products_weight, products_weight_class = :products_weight_class, products_status = :products_status, products_tax_class_id = :products_tax_class_id, products_date_added = :products_date_added, is_subproduct = :is_subproduct where parent_id = :parent_id and products_id = :products_id');
            $Qsubproduct->bindInt(':products_id', $data['sub_products_id'][$i]);
          } else {
            // add new subproduct record
            $Qsubproduct = $lC_Database->query('insert into :table_products (parent_id, products_quantity, products_cost, products_price, products_sku, products_weight, products_weight_class, products_status, products_tax_class_id, products_ordered, products_date_added, is_subproduct) values (:parent_id, :products_quantity, :products_cost, :products_price, :products_sku, :products_weight, :products_weight_class, :products_status, :products_tax_class_id, :products_ordered, :products_date_added, :is_subproduct)');
            $Qsubproduct->bindInt(':products_ordered', $data['products_ordered'][$i]);            
          }
          
          $Qsubproduct->bindTable(':table_products', TABLE_PRODUCTS);
          $Qsubproduct->bindInt(':parent_id', $products_id);
          $Qsubproduct->bindInt(':products_quantity', $data['sub_products_qoh'][$i]);
          $Qsubproduct->bindFloat(':products_cost', preg_replace('/[^0-9]\./', '', $data['sub_products_cost'][$i]));
          $Qsubproduct->bindFloat(':products_price',  preg_replace('/[^0-9]\./', '', $data['sub_products_price'][1][$i])); // retail group - other prices go into pricing table
          $Qsubproduct->bindValue(':products_sku', $data['sub_products_sku'][$i]);
          $Qsubproduct->bindFloat(':products_weight', $data['sub_products_weight'][$i]);
          $Qsubproduct->bindInt(':products_weight_class', $data['weight_class']);
          $Qsubproduct->bindInt(':products_status', $data['sub_products_status'][$i]);
          $Qsubproduct->bindInt(':products_tax_class_id', $data['tax_class_id']);
          $Qsubproduct->bindRaw(':products_date_added', 'now()');            
          $Qsubproduct->bindInt(':is_subproduct', ($data['sub_products_default'][$i] == '1' || sizeof($data['sub_products_name']) == 2) ? 2 : 1);            
          $Qsubproduct->setLogging($_SESSION['module'], $products_id);
          $Qsubproduct->execute();
          
          if ( $lC_Database->isError() ) {
            $error = true;
          } else {
            if ( is_numeric($id) && @in_array($data['sub_products_id'][$i], $data['sub_products_id']) ) {
              $sub_products_id = $data['sub_products_id'][$i];
            } else {
              $sub_products_id = self::_getLastID();
            }                              
            // subproduct description
            foreach ($lC_Language->getAll() as $l) {
              if (is_numeric($id) && @in_array($data['sub_products_id'][$i], $data['sub_products_id'])) {
                $Qpd = $lC_Database->query('update :table_products_description set products_name = :products_name where products_id = :products_id and language_id = :language_id');
              } else {
                $Qpd = $lC_Database->query('insert into :table_products_description (products_id, language_id, products_name) values (:products_id, :language_id, :products_name)');
              }
              $Qpd->bindTable(':table_products_description', TABLE_PRODUCTS_DESCRIPTION);
              $Qpd->bindInt(':products_id', $sub_products_id);
              $Qpd->bindInt(':language_id', $l['id']);
              $Qpd->bindValue(':products_name', $data['sub_products_name'][$i]);
              $Qpd->setLogging($_SESSION['module'], $sub_products_id);
              $Qpd->execute();

              if ( $lC_Database->isError() ) {
                $error = true;
                break;
              }
            }
          }            

          //subproduct images 
          if ( $error === false ) {
            if (empty($_FILES['sub_products_image']['name'][$i]) === false) {
              $images = array();
              $file = array('name' => $_FILES['sub_products_image']['name'][$i],
                            'type' => $_FILES['sub_products_image']['type'][$i],
                            'size' => $_FILES['sub_products_image']['size'][$i],
                            'tmp_name' => $_FILES['sub_products_image']['tmp_name'][$i]);

              $products_image = new upload($file);
              
              $products_image->set_extensions(array('gif', 'jpg', 'jpeg', 'png'));

              if ( $products_image->exists() ) {
                $products_image->set_destination(realpath('../images/products/originals'));

                if ( $products_image->parse() && $products_image->save() ) {
                  $images[] = $products_image->filename;
                }
              }

              $default_flag = 1;
              
              foreach ($images as $image) {
                $Qimage = $lC_Database->query('insert into :table_products_images (products_id, image, default_flag, sort_order, date_added) values (:products_id, :image, :default_flag, :sort_order, :date_added)');
                $Qimage->bindTable(':table_products_images', TABLE_PRODUCTS_IMAGES);
                $Qimage->bindInt(':products_id', $sub_products_id);
                $Qimage->bindValue(':image', $image);
                $Qimage->bindInt(':default_flag', $default_flag);
                $Qimage->bindInt(':sort_order', 0);
                $Qimage->bindRaw(':date_added', 'now()');
                $Qimage->setLogging($_SESSION['module'], $products_id);
                $Qimage->execute();

                if ( $lC_Database->isError() ) {
                  $error = true;
                } else {
                  foreach ($lC_Image->getGroups() as $group) {
                    if ($group['id'] != '1') {
                      $lC_Image->resize($image, $group['id']);
                    }
                  }
                }
                $default_flag = 0;
              }
            }
          }            
        }  
      }
    }     
    
    // combo variants
    if ( $error === false ) {
      $variants_array = array();
      $default_variant_combo = null;

      if ( isset($data['variants']) && !empty($data['variants']) ) {
        foreach ( $data['variants'] as $key => $combo ) {
          if (isset($data['variants'][$key]['product_id']) && $data['variants'][$key]['product_id'] != 0) {
            $Qsubproduct = $lC_Database->query('update :table_products set products_quantity = :products_quantity, products_cost = :products_cost, products_price = :products_price, products_msrp = :products_msrp, products_model = :products_model, products_sku = :products_sku, products_weight = :products_weight, products_weight_class = :products_weight_class, products_status = :products_status, products_tax_class_id = :products_tax_class_id where products_id = :products_id');
            $Qsubproduct->bindInt(':products_id', $data['variants'][$key]['product_id']);
          } else {
            $Qsubproduct = $lC_Database->query('insert into :table_products (parent_id, products_quantity, products_cost, products_price, products_msrp, products_model, products_sku, products_weight, products_weight_class, products_status, products_tax_class_id, products_date_added) values (:parent_id, :products_quantity, :products_cost, :products_price, :products_msrp, :products_model, :products_sku, :products_weight, :products_weight_class, :products_status, :products_tax_class_id, :products_date_added)');
            $Qsubproduct->bindInt(':parent_id', $products_id);
            $Qsubproduct->bindRaw(':products_date_added', 'now()');
          }

          $price = 0.00;
          if (is_array($data['variants'][$key]['price'])) $price = $data['variants'][$key]['price'][0];
          
          $Qsubproduct->bindTable(':table_products', TABLE_PRODUCTS);
          $Qsubproduct->bindInt(':products_quantity', $data['variants'][$key]['qoh']);
          $Qsubproduct->bindFloat(':products_cost', $data['variants'][$key]['cost']);
          $Qsubproduct->bindFloat(':products_price', $price);
          $Qsubproduct->bindFloat(':products_msrp', $data['variants'][$key]['msrp']);
          $Qsubproduct->bindValue(':products_model', $data['variants'][$key]['model']);
          $Qsubproduct->bindValue(':products_sku', $data['variants'][$key]['sku']);
          $Qsubproduct->bindFloat(':products_weight', $data['variants'][$key]['weight']);
          $Qsubproduct->bindInt(':products_weight_class', $data['weight_class']);
          $Qsubproduct->bindInt(':products_status', $data['variants'][$key]['status']);
          $Qsubproduct->bindInt(':products_tax_class_id', $data['tax_class_id']);
          $Qsubproduct->setLogging($_SESSION['module'], $products_id);
          $Qsubproduct->execute();
          
          if ( isset($data['variants'][$key]['product_id']) && $data['variants'][$key]['product_id'] != '0') {
            $subproduct_id = $data['variants'][$key]['product_id'];
          } else {
            $Qnext = $lC_Database->query('select max(products_id) as maxID from :table_products');
            $Qnext->bindTable(':table_products', TABLE_PRODUCTS);
            $Qnext->execute();
            $subproduct_id = $Qnext->valueInt('maxID');
            $Qnext->freeResult();
          }

          if ( $data['variants'][$key]['default_combo'] == 1) {
            $default_variant_combo = $subproduct_id;
          }
      
          foreach ( $data['variants'][$key]['values'] as $values_id => $values_text ) {
            
            $variants_array[$subproduct_id][] = $values_id;

            $check_combos_array[] = $values_id;            

            $Qcheck = $lC_Database->query('select products_id from :table_products_variants where products_id = :products_id and products_variants_values_id = :products_variants_values_id');
            $Qcheck->bindTable(':table_products_variants', TABLE_PRODUCTS_VARIANTS);
            $Qcheck->bindInt(':products_id', $subproduct_id);
            $Qcheck->bindInt(':products_variants_values_id', $values_id);
            $Qcheck->execute();

            if ( $Qcheck->numberOfRows() < 1 ) {
        
              $Qvcombo = $lC_Database->query('insert into :table_products_variants (products_id, products_variants_values_id) values (:products_id, :products_variants_values_id)');
              $Qvcombo->bindTable(':table_products_variants', TABLE_PRODUCTS_VARIANTS);
              $Qvcombo->bindInt(':products_id', $subproduct_id);
              $Qvcombo->bindInt(':products_variants_values_id', $values_id);
              $Qvcombo->setLogging($_SESSION['module'], $products_id);
              $Qvcombo->execute();

              if ( $lC_Database->isError() ) {
                $error = true;
                break 2;
              }
            }
          }
         
        }
      }
   
      if ( $error === false ) {
        if ( empty($variants_array) ) {
          $Qcheck = $lC_Database->query('select pv.* from :table_products p, :table_products_variants pv where p.parent_id = :parent_id and p.products_id = pv.products_id');
          $Qcheck->bindTable(':table_products', TABLE_PRODUCTS);
          $Qcheck->bindTable(':table_products_variants', TABLE_PRODUCTS_VARIANTS);
          $Qcheck->bindInt(':parent_id', $products_id);
          $Qcheck->execute();

          while ( $Qcheck->next() ) {
            $Qdel = $lC_Database->query('delete from :table_products_variants where products_id = :products_id');
            $Qdel->bindTable(':table_products_variants', TABLE_PRODUCTS_VARIANTS);
            $Qdel->bindInt(':products_id', $Qcheck->valueInt('products_id'));
            $Qdel->execute();

            $Qdel = $lC_Database->query('delete from :table_products where products_id = :products_id');
            $Qdel->bindTable(':table_products', TABLE_PRODUCTS);
            $Qdel->bindInt(':products_id', $Qcheck->valueInt('products_id'));
            $Qdel->execute();
            
          }
        } else {
          $Qcheck = $lC_Database->query('select pv.* from :table_products p, :table_products_variants pv where p.parent_id = :parent_id and p.products_id = pv.products_id and pv.products_id not in (":products_id")');
          $Qcheck->bindTable(':table_products', TABLE_PRODUCTS);
          $Qcheck->bindTable(':table_products_variants', TABLE_PRODUCTS_VARIANTS);
          $Qcheck->bindInt(':parent_id', $products_id);
          $Qcheck->bindRaw(':products_id', implode('", "', array_keys($variants_array)));
          $Qcheck->execute();

          while ( $Qcheck->next() ) {
            $Qdel = $lC_Database->query('delete from :table_products_variants where products_id = :products_id and products_variants_values_id = :products_variants_values_id');
            $Qdel->bindTable(':table_products_variants', TABLE_PRODUCTS_VARIANTS);
            $Qdel->bindInt(':products_id', $Qcheck->valueInt('products_id'));
            $Qdel->bindInt(':products_variants_values_id', $Qcheck->valueInt('products_variants_values_id'));
            $Qdel->execute();

            $Qdel = $lC_Database->query('delete from :table_products where products_id = :products_id');
            $Qdel->bindTable(':table_products', TABLE_PRODUCTS);
            $Qdel->bindInt(':products_id', $Qcheck->valueInt('products_id'));
            $Qdel->execute();
            
          }

          foreach ( $variants_array as $key => $values ) {
            $Qdel = $lC_Database->query('delete from :table_products_variants where products_id = :products_id and products_variants_values_id not in (":products_variants_values_id")');
            $Qdel->bindTable(':table_products_variants', TABLE_PRODUCTS_VARIANTS);
            $Qdel->bindInt(':products_id', $key);
            $Qdel->bindRaw(':products_variants_values_id', implode('", "', $values));
            $Qdel->execute();
          }
        }
      }

      $Qupdate = $lC_Database->query('update :table_products set has_children = :has_children where products_id = :products_id');
      $Qupdate->bindTable(':table_products', TABLE_PRODUCTS);
      $Qupdate->bindInt(':has_children', (empty($variants_array)) ? 0 : 1);
      $Qupdate->bindInt(':products_id', $products_id);
      $Qupdate->execute();
      
    }  
    
    if ( $error === false ) {
      $Qupdate = $lC_Database->query('update :table_products_variants set default_combo = :default_combo where products_id in (":products_id")');
      $Qupdate->bindTable(':table_products_variants', TABLE_PRODUCTS_VARIANTS);
      $Qupdate->bindInt(':default_combo', 0);
      $Qupdate->bindRaw(':products_id', implode('", "', array_keys($variants_array)));
      $Qupdate->execute();

      if ( is_numeric($default_variant_combo) ) {
        $Qupdate = $lC_Database->query('update :table_products_variants set default_combo = :default_combo where products_id = :products_id');
        $Qupdate->bindTable(':table_products_variants', TABLE_PRODUCTS_VARIANTS);
        $Qupdate->bindInt(':default_combo', 1);
        $Qupdate->bindInt(':products_id', $default_variant_combo);
        $Qupdate->execute();  
      }
    }  
    
    // customer access levels (B2B)
    if ( $error === false ) {
      $levels = '';   
      if (is_array($data['access_levels'])) {
        foreach($data['access_levels'] as $key => $val) {
          $levels .= $key . ';';
        }
        $levels = substr($levels, 0, -1);   
      }
      
      $Qupdate = $lC_Database->query('update :table_products set access_levels = :access_levels where products_id = :products_id');
      $Qupdate->bindTable(':table_products', TABLE_PRODUCTS);
      $Qupdate->bindValue(':access_levels', $levels);
      $Qupdate->bindInt(':products_id', $products_id);
      $Qupdate->execute();
    } 
    
    return $products_id; // Return the products id for use with the save_close buttons

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
      $content .= '<dt id="dt-' . $value['customers_group_id'] . '"><span class="strong">' . $value['customers_group_name'] . '</span></dt>' .
                  '<dd id="dd-' . $value['customers_group_id'] . '">' .
                  '  <div class="with-padding" id="options-pricing-entries-div-' . $value['customers_group_id'] . '">';
                  
      if (isset($pInfo) && is_array($pInfo->get('simple_options'))) {                  
        $content .= '<div class="simple-options-pricing-container">' .
                    '  <div class="big-text underline" style="padding-bottom:8px;">' . $lC_Language->get('text_simple_options') . '</div>' .
                    '  <table class="simple-table simple-options-pricing-table">' .
                    '    <tbody id="tbody-simple-options-pricing-' . $value['customers_group_id'] . '">' . lC_Products_Admin::getSimpleOptionsPricingTbody($pInfo->get('simple_options'), $value['customers_group_id']) . '</tbody>' .
                    '  </table>' . 
                    '</div>';
                    
      } 
      
      if (isset($pInfo) && $pInfo->get('has_subproducts') == '1') {               
        $content .= '<div class="subproducts-pricing-container">' .    
                    '  <div class="big-text underline margin-top" style="padding-bottom:8px;">' . $lC_Language->get('text_sub_products') . '</div>' .
                    '  <table class="simple-table subproducts-pricing-table">' .
                    '    <tbody id="tbody-subproducts-pricing-' . $value['customers_group_id'] . '">' . lC_Products_pro_Admin::getSubProductsPricingTbody($pInfo, $value['customers_group_id']) . '</tbody>' .
                    '  </table>' .        
                    '</div>';        
        
      } 
      
      if (isset($pInfo) && $pInfo->get('has_children') == '1') {
        $content .= '<div id="combo-options-pricing-container-' . $value['customers_group_id'] . '">' .    
                    '  <div class="big-text underline margin-top" style="padding-bottom:8px;">' . $lC_Language->get('text_combo_options') . '</div>' .
                    '  <table class="simple-table combo-options-pricing-table">' .
                    '    <tbody id="tbody-combo-options-pricing-' . $value['customers_group_id'] . '">' . lC_Products_pro_Admin::getComboOptionsPricingTbody($pInfo, $value['customers_group_id']) . '</tbody>' .
                    '  </table>' .         
                    '</div>';         
      } 
      
      if ( (!isset($pInfo)) || (isset($pInfo) && $pInfo->get('has_subproducts') != '1' && $pInfo->get('has_children') != '1' && !is_array($pInfo->get('simple_options'))) ) {       
        $content .= '<table class="simple-table no-options-defined"><tbody id="tbody-options-pricing-' . $value['customers_group_id'] . '"><tr id="no-options-' . $value['customers_group_id'] . '"><td>' . $lC_Language->get('text_no_options_defined') . '</td></tr></tbody></table>'; 
      }
                
      $content .= '  </div>' .
                  '</dd>';  
    }
    
    return $content;
  }  
  
 /*
  * Returns the icons used in the product listing
  *
  * @param integer $id The products id
  * @access public
  * @return string
  */
  public static function getlistingIcons($products_id) {
    global $lC_Language;
    
    $icons = parent::getlistingIcons($products_id);

    if (self::hasSubProducts($products_id)) {
      $icons .= '<span class="icon-flow-cascade icon-red mid-margin-right with-tooltip" style="cursor:pointer;" title="' . $lC_Language->get('text_has_subproducts') . '"></span>';
    }         
    if (self::hasComboOptions($products_id)) {
      $icons .= '<span class="icon-flow-tree icon-blue mid-margin-right with-tooltip" style="cursor:pointer;" title="' . $lC_Language->get('text_has_combo_options') . '"></span>';
    } 
      
    return $icons;
  }  
 /*
  * Returns the price info used in the product listing
  *
  * @param integer $data  The product data array
  * @access public
  * @return string
  */  
  public function getProductsListingPrice($data) {
    global $lC_Database, $lC_Language, $lC_Currencies;
    
    if (self::hasSubProducts($data['products_id']) === false && self::hasComboOptions($data['products_id']) === false) { 
      $price = parent::getProductsListingPrice($data);
    } else {
      $mm = self::getMinMaxPrice($data['products_id']);
      $price  = ($mm['min'] == $mm['max']) ? $lC_Currencies->format($mm['min']) : '(' . $lC_Currencies->format($mm['min']) . ' - ' . $lC_Currencies->format($mm['max']) . ')';
    }

    return $price;
  }  
 /*
  * Returns the min/max price used in the product listing
  *
  * @param integer $id  The product id
  * @access public
  * @return string
  */
  public static function getMinMaxPrice($id) {
    global $lC_Database;

    $Qproducts = $lC_Database->query('select MAX(products_price) as max, MIN(products_price) as min from :table_products where parent_id = :parent_id');
    $Qproducts->bindTable(':table_products', TABLE_PRODUCTS);
    $Qproducts->bindInt(':parent_id', $id);
    $Qproducts->execute();
   
    $result = $Qproducts->toArray();
    
    $Qproducts->freeResult();
    
    return $result;    
  }
 /*
  * Returns the price info used in the product listing
  *
  * @param integer $data  The product data array
  * @access public
  * @return string
  */  
  public function getProductsListingQty($data) {
    global $lC_Database;
    
    if (self::hasSubProducts($data['products_id']) === false && self::hasComboOptions($data['products_id']) === false) { 
      $result = $data['products_quantity'];
    } else {
      $Qproducts = $lC_Database->query('select SUM(products_quantity) as total from :table_products where parent_id = :parent_id');
      $Qproducts->bindTable(':table_products', TABLE_PRODUCTS);
      $Qproducts->bindInt(':parent_id', $data['products_id']);
      $Qproducts->execute();
     
      $result = '(' . $Qproducts->valueInt('total') . ')';
      
      $Qproducts->freeResult();
    }
    
    return $result;     
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

    $Qpb = $lC_Database->query('select * from :table_products_pricing where products_id = :products_id and qty_break != :qty_break limit 1');
    $Qpb->bindTable(':table_products_pricing', TABLE_PRODUCTS_PRICING);
    $Qpb->bindInt(':products_id', $id);
    $Qpb->bindInt(':qty_break', -1);
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
    
    $Qpb = $lC_Database->query('select * from :table_products_pricing where products_id = :products_id and group_id = :group_id and qty_break != :qty_break order by qty_break asc');
    $Qpb->bindTable(':table_products_pricing', TABLE_PRODUCTS_PRICING);
    $Qpb->bindInt(':products_id', $id);
    $Qpb->bindInt(':group_id', $group);
    $Qpb->bindInt(':qty_break', -1);
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
      $cnt = 0;
      $show_line = false;
      if (utility::isB2B()) {
        if (count($groups['entries'] > 1) && $cnt != count($groups['entries']) ) $show_line = true;
      } else {
        if ($value['customers_group_id'] != DEFAULT_CUSTOMERS_GROUP_ID) continue; // locked to default for Pro
      }
    
      $base = (isset($pInfo)) ? (float)$pInfo->get('products_price') : 0.00;
      $special = (isset($pInfo)) ? (float)$pInfo->get('products_special_price') : 0.00;
      
      $has_options = (isset($pInfo) && (self::hasComboOptions($pInfo->get('products_id')) || self::hasSubProducts($pInfo->get('products_id')))) ? true : false;

      $content .= '<label for="products_qty_breaks_pricing_enable' . $value['customers_group_id'] . '" class="label margin-right"><b>'. $value['customers_group_name'] .'</b></label>' .
      
                  '<div id="qpbContainer_' . $value['customers_group_id'] . '">' .
                  '  <div class="new-row-mobile twelve-columns">' .
                  '    <div class="inputs" style="display:inline; padding:8px 0;">' .
                  '      <span class="mid-margin-left no-margin-right">#</span>' .                  
                  '      <input type="text" onfocus="this.select();" name="products_qty_break_point[' . $value['customers_group_id'] . '][0]" id="products_qty_break_point_' . $value['customers_group_id'] . '_0" value="1" class="disabled input-unstyled small-margin-right" style="width:60px;" READONLY/>' .
                  '    </div>' .         
                  '    <small class="input-info mid-margin-left mid-margin-right no-wrap">Qty</small>';
                  if ($has_options === false) { 
                    $content .= '    <div class="inputs" style="display:inline; padding:8px 0;">' .
                                '      <span class="mid-margin-left no-margin-right">' . $lC_Currencies->getSymbolLeft() . '</span>' .
                                '      <input type="text" onfocus="this.select();" name="products_qty_break_price[' . $value['customers_group_id'] . '][0]" id="products_qty_break_price_' . $value['customers_group_id'] . '_0" value="' . ((isset($pInfo)) ? number_format($pInfo->get('products_price'), DECIMAL_PLACES) : 0.00) . '" class="disabled input-unstyled small-margin-right" style="width:60px;" READONLY/>' .
                                '    </div>' . 
                                '    <small class="input-info mid-margin-left no-wrap">Price</small>';
                  }
                  $content .= '</div>';
                  
      if ( isset($pInfo) && self::hasQPBPricing($pInfo->get('products_id')) ) {
        
        $qpbData = self::getQPBPricing($pInfo->get('products_id'), $value['customers_group_id']);
        
        foreach ($qpbData as $key => $val) {
          $content .= self::_getNewQPBPricingRow($val['group_id'], $cnt+1, $val);
          $cnt++;
        }
        // add a new row
        $content .= self::_getNewQPBPricingRow($value['customers_group_id'], $cnt+1);
      } else { // no qpb recorded, setup new
        $content .= self::_getNewQPBPricingRow($value['customers_group_id'], 1);
      }      
      
      $content .= '</div>';                

      if ($show_line) $content .= '<hr style="width:300px;">';                  
      
      $cnt++;
    }
    
    return $content;
  } 
 /*
  * Generate qty price break row
  *
  * @param integer $group The customer group id
  * @param integer $cnt   The product id
  * @param array   $data  The product data
  * @access private
  * @return string
  */
  private static function _getNewQPBPricingRow($group, $cnt, $data = array()) {
    global $lC_Currencies, $pInfo;
    
    $has_options = (isset($pInfo) && (self::hasComboOptions($pInfo->get('products_id')) || self::hasSubProducts($pInfo->get('products_id')))) ? true : false;

    $content = '  <div class="new-row-mobile twelve-columns small-margin-top" id="products_qty_break_point_div_' . $group . '_' . $cnt . '">' .
               '    <div class="inputs" style="display:inline; padding:8px 0;">' .
               '      <span class="mid-margin-left no-margin-right">#</span>' .                  
               '      <input type="text" onblur="validateQPBPoint(this);" onfocus="this.select();" name="products_qty_break_point[' . $group . '][' . $cnt . ']" id="products_qty_break_point_' . $group . '_' . $cnt . '" value="' . $data['qty_break'] . '" class="input-unstyled small-margin-right" style="width:60px;" />' .
               '    </div>' .         
               '    <small class="input-info mid-margin-left mid-margin-right no-wrap">Qty</small>';
    if ($has_options === false) { 
      $content .= '    <div class="inputs" style="display:inline; padding:8px 0;">' .
                  '      <span class="mid-margin-left no-margin-right">' . $lC_Currencies->getSymbolLeft() . '</span>' .
                  '      <input type="text" onblur="validateQPBPrice(this);" onfocus="this.select();" name="products_qty_break_price[' . $group . '][' . $cnt . ']" id="products_qty_break_price_' . $group . '_' . $cnt . '" value="' . (($data['qty_break'] != null) ? number_format($data['price_break'], DECIMAL_PLACES) : null) . '" class="input-unstyled small-margin-right" style="width:60px;" />' .
                  '    </div>' . 
                  '    <small class="input-info mid-margin-left no-wrap">Price</small>';
    }
    $content .= '    <span onclick="removeQPBRow(\'products_qty_break_point_div_' . $group . '_' . $cnt . '\');" class="margin-left icon-cross icon-red icon-size2 cursor-pointer"></span>' . 
                '  </div>'; 
                 
    return $content;
  } 
 /*
  * Determine if the product has combo options
  *
  * @param integer $id The product id
  * @access public
  * @return boolean
  */                      
  public static function hasComboOptions($id) {
    global $lC_Database;
    
    $Qchk = $lC_Database->query('select has_children from :table_products where parent_id = :parent_id and products_id = :products_id limit 1');
    $Qchk->bindTable(':table_products', TABLE_PRODUCTS);
    $Qchk->bindInt(':parent_id', 0);
    $Qchk->bindInt(':products_id', $id);
    $Qchk->execute();
    
    return ( $Qchk->valueInt('has_children') === 1 );
  }  
 /*
  * Return the combo options listing content
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
  * Return the combo options pricing content
  *
  * @param array $data The product data object
  * @access public
  * @return string
  */  
  public static function getComboOptionsPricingTbody($pInfo, $customers_group_id) {
    global $lC_Currencies;
    
    if ($customers_group_id == '') return false;  
    
    $products_id = (isset($pInfo)) ? $pInfo->get('products_id') : null;
    
    $hasQPBPricing = self::hasQPBPricing($products_id);
    
    if (utility::isB2B()) {
      $ok = true;
      $input_class = null;
      $readonly = null;
    } else if (utility::isPro()) {
      $ok = true;
      $input_class = (($customers_group_id == DEFAULT_CUSTOMERS_GROUP_ID) ? null : ' disabled');
      $readonly = (($customers_group_id == '1' && $ok) ? '' : ' READONLY');
    }
    
    $tbody = ''; 
    $cnt = 0; 
    $bpArr = array();
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
          if (isset($pInfo) && $hasQPBPricing && $cnt == 0) {
            $qpbData = self::getQPBPricing($pInfo->get('products_id'), $customers_group_id);
            $tbody .= '<tr class="trop-0 qpb-opt">' .
                      '  <td>&nbsp;</td>' . 
                      '  <td class="strong" style="padding-left:30px !important">Qty 1</td>';
            foreach ($qpbData as $qkey => $qval) {
              $bpArr[] = $qval['qty_break'];
              $tbody .= '  <td class="strong" style="padding-left:30px !important">Qty ' . $qval['qty_break'] . '</td>';          
            }
            $tbody .= '</tr>';
          }

          if (utility::isB2B()) {
            $default_value = number_format($val['data']['price'], DECIMAL_PLACES);
          } else if (utility::isPro()) {
            $default_value = (($customers_group_id == DEFAULT_CUSTOMERS_GROUP_ID) ? number_format($val['data']['price'], DECIMAL_PLACES) : number_format(0, DECIMAL_PLACES));
          }
          
          $tbody .= '<tr class="trop-1">' .
                    '  <td id="trop-name-td-1" class="element">' . $title . '</td>';
       //             '  <td>' .
       //             '    <div class="inputs' . $input_class . '" style="display:inline; padding:8px 0;">' .
       //             '      <span class="mid-margin-left no-margin-right">' . $lC_Currencies->getSymbolLeft() . '</span>' .
       //             '      <input type="text" class="input-unstyled" onfocus="$(this).select()" value="' . $default_value . '" id="options_pricing_' . $product_id . '_' . $customers_group_id . '_1" name="options_pricing[' . $product_id . '][' . $customers_group_id . '][1]" ' . $readonly . '>' .
       //             '    </div>' .
       //             '  </td>';

   //       if (isset($pInfo) && $hasQPBPricing) {

            $tbody .= self::_getNewQPBPricingCol($product_id, $customers_group_id, $bpArr);
         
   //       }                    
                    
          $tbody .= '</tr>';
          
          $cnt++;                    
        } 
      }
    }    

    return $tbody;
  }  
 /*
  * Generate qty price break column
  *
  * @param integer $group The customer group id
  * @param integer $cnt   The product id
  * @param array   $data  The product data
  * @access private
  * @return string                    
  */
  private static function _getNewQPBPricingCol($product_id, $group_id, $bpArr) {
    global $lC_Currencies, $lC_Database, $pInfo; 
    
    $content = '';
    if (is_array($bpArr)) {
    
      $content .= '  <td>' .
                  '    <div class="inputs" style="display:inline; padding:8px 0;">' .
                  '      <span class="mid-margin-left no-margin-right">' . $lC_Currencies->getSymbolLeft() . '</span>' .
                  '      <input type="text" class="input-unstyled" onfocus="$(this).select()" value="' . self::_getOptionPrice($product_id, $group_id, 1) . '" id="options_pricing_' . $product_id . '_' . $group_id . '_1" name="options_pricing[' . $product_id . '][' . $group_id . '][1]">' .
                  '    </div>' .
                  '  </td>'; 
                          
      $cnt = 0;
      reset($bpArr);
      foreach($bpArr as $qty_break) {
    
        $Qpb = $lC_Database->query('select price_break from :table_products_pricing where products_id = :products_id and group_id = :group_id and qty_break = :qty_break limit 1');
        $Qpb->bindTable(':table_products_pricing', TABLE_PRODUCTS_PRICING);
        $Qpb->bindInt(':products_id', $product_id);
        $Qpb->bindInt(':group_id', $group_id);
        $Qpb->bindInt(':qty_break', $qty_break);
        $Qpb->execute();      
      
        if ($Qpb->numberOfRows() > 0) {
          $default_value = number_format($Qpb->valueDecimal('price_break'), DECIMAL_PLACES);
        } else {
          $default_value = number_format(0, DECIMAL_PLACES);
        }
        
        $Qpb-> freeResult();
        
        $content .= '  <td class="qpb-opt">' .
                    '    <div class="inputs" style="display:inline; padding:8px 0;">' .
                    '      <span class="mid-margin-left no-margin-right">' . $lC_Currencies->getSymbolLeft() . '</span>' .
                    '      <input type="text" class="input-unstyled" onfocus="$(this).select()" value="' . $default_value . '" id="options_pricing_' . $product_id . '_' . $group_id . '_' . $qty_break . '" name="options_pricing[' . $product_id . '][' . $group_id . '][' . $qty_break . ']">' .
                    '    </div>' .
                    '  </td>';  
        $cnt++;
      } 
    }  

    return $content;
  }  
 /*
  * Return the product simple options tbody content
  *
  * @param array $options The product simple options array
  * @access public
  * @return string
  */  
  private static function _getComboOptionsTbody($options) {
    global $lC_Currencies, $lC_Language;
    
    $tbody = '';    
    $sort = 10;
    $cnt = 0;
    if (isset($options) && !empty($options)) {
      foreach ($options as $product_id => $mso) {       
        $combo = '';
        $default = '';
        $module = '';
        $comboInput = '';
        if (is_array($mso['values'])) {
          foreach ($mso['values'] as $group_id => $value_id) {
            foreach ($value_id as $key => $data) {  
              $combo .= $data['value_title'] . ', ';
              $module = $data['module'];  
              $default = $data['default'];            
              $default_visual = $data['default_visual'];            
              $comboInput .= '<input type="hidden" id="variants_' . $cnt .'_values_' . $key . '"  name="variants[' . $cnt .'][values][' . $key . ']" value="' . $data['value_title'] . '">';                        
              $comboInput .= '<input type="hidden" id="variants_' . $cnt .'_default_visual_' . $default_visual . '"  name="variants[' . $cnt .'][default_visual]" value="' . $default_visual . '">';                        
            }
          }
          if (strstr($combo, ',')) $combo = substr($combo, 0, -2);
          
          $statusIcon = (isset($mso['data']['status']) && $mso['data']['status'] == '1') ? '<span id="variants_status_span_' . $cnt .'" class="icon-tick icon-size2 icon-green with-tooltip" data-tooltip-options=\'{"classes":["grey-gradient"],"position":"left"}\' title="Set Status"></span><input type="hidden" id="variants_status_' . $cnt .'" name="variants[' . $cnt . '][status]" value="1">' : '<span id="variants_status_span_' . $cnt .'" class="icon-cross icon-size2 icon-red with-tooltip" data-tooltip-options=\'{"classes":["grey-gradient"],"position":"left"}\' title="Set Status"></span><input type="hidden" id="variants_status_' . $cnt .'" name="variants[' . $cnt . '][status]" value="0">';
          $defaultIcon = (isset($default) && $default == '1') ? '<span id="variants_default_combo_span_' . $cnt .'" class="default-combo-span icon-star icon-size2 icon-orange with-tooltip" data-tooltip-options=\'{"classes":["grey-gradient"],"position":"left"}\' title="' . $lC_Language->get('text_default_selected_combo') . '"></span><input class="default-combo" type="hidden" id="variants_default_combo_' . $cnt .'" name="variants[' . $cnt . '][default_combo]" value="1">' : '<span id="variants_default_combo_span_' . $cnt .'" class="default-combo-span icon-star icon-size2 icon-grey with-tooltip" data-tooltip-options=\'{"classes":["grey-gradient"],"position":"left"}\' title="' . $lC_Language->get('text_set_default_combo') . '"></span><input class="default-combo" type="hidden" id="variants_default_combo_' . $cnt .'" name="variants[' . $cnt . '][default_combo]" value="0">';          
                    
          $tbody .= '<tr id="trmso-' . $cnt .'"><input type="hidden" name="variants[' . $cnt . '][product_id]" value="' . $product_id . '"><input type="hidden" class="combo-sort" name="variants[' . $cnt .'][sort]" value="' . $sort . '">' . $comboInput .
                    '  <td width="16px" class="sort-icon dragsort" style="cursor:move;"><span class="icon-list icon-grey icon-size2"></span></td>' .
                    '  <td class="option-name" width="25%">' . $combo . '</td>' .
                    '  <td width="16px" style="cursor:pointer;" onclick="toggleComboOptionsFeatured(\'' . $cnt . '\');">' . $defaultIcon . '</td>' .                    
                    '  <td style="white-space:nowrap;">
                         <div class="inputs" style="display:inline; padding:8px 0;">
                           <input type="text" class="input-unstyled mid-margin-left" style="width:70%;" onfocus="this.select();" value="' . number_format($mso['data']['weight'], 4) . '" tabindex="' . $cnt . '2" name="variants[' . $cnt . '][weight]">
                           <span class="mid-margin-right no-margin-left">' . lC_Weight::getCode(SHIPPING_WEIGHT_UNIT) . '</span>
                         </div>
                       </td>' .                    
                    '  <td><input type="text" class="input half-width" onfocus="this.select();" tabindex="' . $cnt . '3" name="variants[' . $cnt . '][sku]" value="' . $mso['data']['sku'] . '"></td>' .
                    '  <td><input type="text" class="input half-width" onfocus="this.select();" tabindex="' . $cnt . '4" name="variants[' . $cnt . '][qoh]" value="' . (int)$mso['data']['quantity'] . '"></td>' .
                    '  <td style="white-space:nowrap;">
                         <div class="inputs" style="display:inline; padding:8px 0;">
                           <span class="mid-margin-left no-margin-right">' . $lC_Currencies->getSymbolLeft() . '</span>
                           <input type="text" class="input-unstyled" style="width:87%;" onchange="$(\'#variants_' . $cnt . '_price_1\').val(this.value);" onfocus="this.select();" value="' . number_format($mso['data']['price'], DECIMAL_PLACES) . '" tabindex="' . $cnt . '5" name="variants[' . $cnt . '][price]" id="variants_' . $cnt . '_price">
                         </div>
                       </td>' .
                    '  <td class="align-center align-middle">' .
                    '    <input style="display:none;" type="file" id="multi_sku_image_' . $cnt . '" name="variants[' . $cnt . '][image]" onchange="setComboOptionsImage(\'' . $cnt . '\');" multiple />' .
                    '    <span class="icon-camera icon-size2 cursor-pointer with-tooltip ' . ((isset($mso['data']['image']) && $mso['data']['image'] != null) ? 'icon-green' : 'icon-grey') . '" title="' . ((isset($mso['data']['image']) && $mso['data']['image'] != null) ? $mso['data']['image'] : null) . '" id="fileSelectButtonComboOptions-' . $cnt . '" onclick="document.getElementById(\'multi_sku_image_' . $cnt . '\').click();"></span>' .
                    '  </td>' .                       
                    '  <td width="16px" align="center" style="cursor:pointer;" onclick="toggleComboOptionsStatus(\'' . $cnt . '\');">' . $statusIcon . '</td>' .
                    '  <td width="40px" align="right">
                         <!-- span class="icon-pencil icon-orange icon-size2 margin-right with-tooltip" data-tooltip-options=\'{"classes":["grey-gradient"],"position":"left"}\' title="Edit Entry" style="cursor:pointer;" onclick="addMultiSKUOption(\'' . $cnt. '\')"></span -->
                         <span class="icon-trash icon-size2 icon-red with-tooltip" data-tooltip-options=\'{"classes":["grey-gradient"],"position":"right"}\' title="Remove Entry" style="cursor:pointer;" onclick="removeComboOptionsRow(\'' . $cnt . '\');"></span>
                       </td>' .
                    '</tr>';
        }
        $sort = ($sort + 10);
        $cnt++;
      }
    }
    
    return $tbody;    
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
  *  Get the options product price
  *
  * @param integer $id The product id
  * @access public
  * @return boolean
  */   
  private static function _getOptionPrice($products_id, $group_id, $qty_break) {
    global $lC_Database;
    
    $Qproducts = $lC_Database->query('select price_break from :table_products_pricing where products_id = :products_id and group_id = :group_id and qty_break = :qty_break limit 1');
    $Qproducts->bindTable(':table_products_pricing', TABLE_PRODUCTS_PRICING);
    $Qproducts->bindInt(':products_id', $products_id);
    $Qproducts->bindInt(':group_id', $group_id);
    $Qproducts->bindInt(':qty_break', $qty_break);
    $Qproducts->execute();
    
    $result = 0;
    if ($Qproducts->numberOfRows() > 0) {
      $result = $Qproducts->valueDecimal('price_break');
    }
    
    $Qproducts->freeResult();

    return number_format($result, DECIMAL_PLACES);
  }
}