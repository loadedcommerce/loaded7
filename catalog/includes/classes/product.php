<?php
/**
  @package    catalog::classes
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: product.php v1.0 2013-08-08 datazen $
*/
class lC_Product {
  protected $_data = array();

  public function __construct($id) {
    global $lC_Database, $lC_Services, $lC_Language, $lC_Image;

    if ( !empty($id) ) {
      if ( is_numeric($id) ) {
        $Qproduct = $lC_Database->query('select products_id as id, parent_id, products_quantity as quantity, products_price as price, products_model as model, products_tax_class_id as tax_class_id, products_weight as weight, products_weight_class as weight_class_id, products_date_added as date_added, manufacturers_id, has_children, is_subproduct from :table_products where products_id = :products_id and products_status = :products_status');
        $Qproduct->bindTable(':table_products', TABLE_PRODUCTS);
        $Qproduct->bindInt(':products_id', $id);
        $Qproduct->bindInt(':products_status', 1);
        $Qproduct->execute();

        if ( $Qproduct->numberOfRows() === 1 ) {
          $this->_data = $Qproduct->toArray();

          $this->_data['master_id'] = $Qproduct->valueInt('id');
          $this->_data['has_children'] = $Qproduct->valueInt('has_children');
          $this->_data['is_subproduct'] = $Qproduct->valueInt('is_subproduct');

          if ( $Qproduct->valueInt('parent_id') > 0 && $Qproduct->valueInt('is_subproduct') == 0 ) {
            $Qmaster = $lC_Database->query('select products_id, has_children from :table_products where products_id = :products_id and products_status = :products_status');
            $Qmaster->bindTable(':table_products', TABLE_PRODUCTS);
            $Qmaster->bindInt(':products_id', $Qproduct->valueInt('parent_id'));
            $Qmaster->bindInt(':products_status', 1);
            $Qmaster->execute();

            if ( $Qmaster->numberOfRows() === 1 ) {
              $this->_data['master_id'] = $Qmaster->valueInt('products_id');
              $this->_data['has_children'] = $Qmaster->valueInt('has_children');
            } else { // master product is disabled so invalidate the product variant
              $this->_data = array();
            }
          }

          if ( !empty($this->_data) ) {
            
            $Qdesc = $lC_Database->query('select products_name as name, products_description as description, products_keyword as keyword, products_tags as tags, products_url as url from :table_products_description where products_id = :products_id and language_id = :language_id');
            $Qdesc->bindTable(':table_products_description', TABLE_PRODUCTS_DESCRIPTION);
            $Qdesc->bindInt(':products_id', $this->_data['master_id']);
            $Qdesc->bindInt(':language_id', $lC_Language->getID());
            $Qdesc->execute();
            
            $desc = $Qdesc->toArray();
           
            if ($this->_data['is_subproduct'] > 0) {
              $Qmaster = $lC_Database->query('select products_name as parent_name, products_description as description, products_keyword as keyword, products_tags as tags, products_url as url from :table_products_description where products_id = :products_id and language_id = :language_id limit 1');
              $Qmaster->bindTable(':table_products_description', TABLE_PRODUCTS_DESCRIPTION);
              $Qmaster->bindInt(':products_id', $Qproduct->valueInt('parent_id'));
              $Qmaster->bindInt(':language_id', $lC_Language->getID());
              $Qmaster->execute();              
              
              $parent_name = $Qmaster->value('parent_name');
              
              if (empty($parent_name) === false) {
                $desc['name'] = $parent_name . ' - ' . $desc['name'];
              }            
              $desc['description'] = $Qmaster->value('description');
              $desc['keyword'] = $Qmaster->value('keyword');
              $desc['products_tags'] = $Qmaster->value('tags');
              $desc['products_url'] = $Qmaster->value('url');
            }
              
            $Qdesc->freeResult();

            $this->_data = array_merge($this->_data, $desc);
          }
        }
      } else {
        $Qproduct = $lC_Database->query('select p.products_id as id, p.parent_id, p.products_quantity as quantity, p.products_price as price, p.products_model as model, p.products_tax_class_id as tax_class_id, p.products_weight as weight, p.products_weight_class as weight_class_id, p.products_date_added as date_added, p.manufacturers_id, p.has_children, pd.products_name as name, pd.products_description as description, pd.products_keyword as keyword, pd.products_tags as tags, pd.products_url as url from :table_products p, :table_products_description pd where pd.products_keyword = :products_keyword and pd.language_id = :language_id and pd.products_id = p.products_id and p.products_status = :products_status');
        $Qproduct->bindTable(':table_products', TABLE_PRODUCTS);
        $Qproduct->bindTable(':table_products_description', TABLE_PRODUCTS_DESCRIPTION);
        $Qproduct->bindValue(':products_keyword', $id);
        $Qproduct->bindInt(':language_id', $lC_Language->getID());
        $Qproduct->bindInt(':products_status', 1);
        $Qproduct->execute();

        if ($Qproduct->numberOfRows() === 1) {
          $this->_data = $Qproduct->toArray();

          $this->_data['master_id'] = $Qproduct->valueInt('id');
          $this->_data['has_children'] = $Qproduct->valueInt('has_children');
        }
      }

      if ( !empty($this->_data) ) {
        $this->_data['images'] = array();

        $Qimages = $lC_Database->query('select id, image, default_flag from :table_products_images where products_id = :products_id order by sort_order');
        $Qimages->bindTable(':table_products_images', TABLE_PRODUCTS_IMAGES);
        $Qimages->bindInt(':products_id', $this->_data['master_id']);
        $Qimages->execute();

        while ($Qimages->next()) {
          $this->_data['images'][] = $Qimages->toArray();
        }

        $Qcategory = $lC_Database->query('select categories_id from :table_products_to_categories where products_id = :products_id limit 1');
        $Qcategory->bindTable(':table_products_to_categories', TABLE_PRODUCTS_TO_CATEGORIES);
        $Qcategory->bindInt(':products_id', $this->_data['master_id']);
        $Qcategory->execute();

        $this->_data['category_id'] = $Qcategory->valueInt('categories_id');

        // load price break array
        $this->_data['price_breaks'] = array();

        $Qpb = $lC_Database->query('select tax_class_id, qty_break, price_break from :table_products_pricing where products_id = :products_id and group_id = :group_id order by group_id, qty_break');
        $Qpb->bindTable(':table_products_pricing', TABLE_PRODUCTS_PRICING);
        $Qpb->bindInt(':products_id', $this->_data['master_id']); 
        $Qpb->bindInt(':group_id', (isset($_SESSION['lC_Customer_data']['customers_group_id'])? $_SESSION['lC_Customer_data']['customers_group_id'] : DEFAULT_CUSTOMERS_GROUP_ID));
        $Qpb->execute();

        while ($Qpb->next()) {
          $this->_data['price_breaks'][] = $Qpb->toArray();
        }          
        
        if ( $this->_data['has_children'] === 1 ) {
          $this->_data['variants'] = array();

          $Qsubproducts = $lC_Database->query('select * from :table_products where parent_id = :parent_id and products_status = :products_status');
          $Qsubproducts->bindTable(':table_products', TABLE_PRODUCTS);
          $Qsubproducts->bindInt(':parent_id', $this->_data['master_id']);
          $Qsubproducts->bindInt(':products_status', 1);
          $Qsubproducts->execute();

          while ( $Qsubproducts->next() ) {
            $this->_data['variants'][$Qsubproducts->valueInt('products_id')]['data'] = array('price' => $Qsubproducts->value('products_price'),
                                                                                             'tax_class_id' => $Qsubproducts->valueInt('products_tax_class_id'),
                                                                                             //'tax_class_id' => $this->getTaxClassID(),
                                                                                             'model' => $Qsubproducts->value('products_model'),
                                                                                             'quantity' => $Qsubproducts->value('products_quantity'),
                                                                                             'weight' => $Qsubproducts->value('products_weight'),
                                                                                             'weight_class_id' => $Qsubproducts->valueInt('products_weight_class'),
                                                                                             'availability_shipping' => 1);

            $Qvariants = $lC_Database->query('select pv.default_combo, pvg.id as group_id, pvg.title as group_title, pvg.module, pvv.id as value_id, pvv.title as value_title, pvv.sort_order as value_sort_order from :table_products_variants pv, :table_products_variants_groups pvg, :table_products_variants_values pvv where pv.products_id = :products_id and pv.products_variants_values_id = pvv.id and pvv.languages_id = :languages_id and pvv.products_variants_groups_id = pvg.id and pvg.languages_id = :languages_id order by pvg.sort_order, pvg.title');
            $Qvariants->bindTable(':table_products_variants', TABLE_PRODUCTS_VARIANTS);
            $Qvariants->bindTable(':table_products_variants_groups', TABLE_PRODUCTS_VARIANTS_GROUPS);
            $Qvariants->bindTable(':table_products_variants_values', TABLE_PRODUCTS_VARIANTS_VALUES);
            $Qvariants->bindInt(':products_id', $Qsubproducts->valueInt('products_id'));
            $Qvariants->bindInt(':languages_id', $lC_Language->getID());
            $Qvariants->bindInt(':languages_id', $lC_Language->getID());
            $Qvariants->execute();

            while ( $Qvariants->next() ) {
              $this->_data['variants'][$Qsubproducts->valueInt('products_id')]['values'][$Qvariants->valueInt('group_id')][$Qvariants->valueInt('value_id')] = array('value_id' => $Qvariants->valueInt('value_id'),
                                                                                                                                                                     'group_title' => $Qvariants->value('group_title'),
                                                                                                                                                                     'value_title' => $Qvariants->value('value_title'),
                                                                                                                                                                     'sort_order' => $Qvariants->value('value_sort_order'),
                                                                                                                                                                     'default' => (bool)$Qvariants->valueInt('default_combo'),
                                                                                                                                                                     'module' => $Qvariants->value('module'));
            }
          }
        }
        
        // simple options
        $QsimpleOptions = $lC_Database->query("select * from :table_products_simple_options where products_id = :products_id and status = '1' order by sort_order");
        $QsimpleOptions->bindTable(':table_products_simple_options', TABLE_PRODUCTS_SIMPLE_OPTIONS);
        $QsimpleOptions->bindInt(':products_id', $this->_data['master_id']);        
        $QsimpleOptions->execute();
               
        while ( $QsimpleOptions->next() ) {
          $QsimpleOptionsValues = $lC_Database->query('select values_id, price_modifier from :table_products_simple_options_values where products_id = :products_id and options_id = :options_id and customers_group_id = :customers_group_id order by sort_order asc');
          $QsimpleOptionsValues->bindTable(':table_products_simple_options_values', TABLE_PRODUCTS_SIMPLE_OPTIONS_VALUES);
          $QsimpleOptionsValues->bindInt(':products_id', $this->_data['master_id']);
          $QsimpleOptionsValues->bindInt(':options_id', $QsimpleOptions->valueInt('options_id'));
          $QsimpleOptionsValues->bindInt(':customers_group_id', DEFAULT_CUSTOMERS_GROUP_ID);
          $QsimpleOptionsValues->execute();   
           
          while ( $QsimpleOptionsValues->next() ) {
            $Qvariants = $lC_Database->query('select pvg.title as group_title, pvg.module, pvv.title as value_title from :table_products_variants_groups pvg, :table_products_variants_values pvv where pvg.id = :options_id and pvv.id = :values_id and pvv.languages_id = :languages_id and pvv.products_variants_groups_id = pvg.id and pvg.languages_id = :languages_id limit 1');
            $Qvariants->bindTable(':table_products_variants_groups', TABLE_PRODUCTS_VARIANTS_GROUPS);
            $Qvariants->bindTable(':table_products_variants_values', TABLE_PRODUCTS_VARIANTS_VALUES);
            $Qvariants->bindInt(':options_id', $QsimpleOptions->valueInt('options_id'));
            $Qvariants->bindInt(':values_id', $QsimpleOptionsValues->valueInt('values_id'));
            $Qvariants->bindInt(':languages_id', $lC_Language->getID());
            $Qvariants->bindInt(':languages_id', $lC_Language->getID());
            $Qvariants->execute();
            
            $this->_data['simple_options'][$this->_data['master_id']]['values'][$QsimpleOptions->valueInt('options_id')][$QsimpleOptionsValues->valueInt('values_id')] = array('value_id' => $QsimpleOptionsValues->valueInt('values_id'),
                                                                                                                                                                               'group_id' => $QsimpleOptions->valueInt('options_id'),
                                                                                                                                                                               'group_title' => $Qvariants->value('group_title'),
                                                                                                                                                                               'value_title' => $Qvariants->value('value_title'),
                                                                                                                                                                               'sort_order' => $QsimpleOptions->valueInt('sort_order'),
                                                                                                                                                                               'price_modifier' => $QsimpleOptionsValues->valueDecimal('price_modifier'),
                                                                                                                                                                               'module' => $Qvariants->value('module'));
            $Qvariants->freeResult();                                                                                                                                                                               
          } 
          $QsimpleOptionsValues->freeResult();     
        }
        $QsimpleOptions->freeResult();

        $this->_data['attributes'] = array();

        $Qattributes = $lC_Database->query('select tb.code, pa.value from :table_product_attributes pa, :table_templates_boxes tb where pa.products_id = :products_id and pa.languages_id in (0, :languages_id) and pa.id = tb.id');
        $Qattributes->bindTable(':table_product_attributes');
        $Qattributes->bindTable(':table_templates_boxes');
        $Qattributes->bindInt(':products_id', $this->_data['master_id']);
        $Qattributes->bindInt(':languages_id', $lC_Language->getID());
        $Qattributes->execute();

        while ( $Qattributes->next() ) {
          $this->_data['attributes'][$Qattributes->value('code')] = $Qattributes->value('value');
        }

        if ( $lC_Services->isStarted('reviews') ) {
          $Qavg = $lC_Database->query('select avg(reviews_rating) as rating from :table_reviews where products_id = :products_id and languages_id = :languages_id and reviews_status = 1');
          $Qavg->bindTable(':table_reviews', TABLE_REVIEWS);
          $Qavg->bindInt(':products_id', $this->_data['master_id']);
          $Qavg->bindInt(':languages_id', $lC_Language->getID());
          $Qavg->execute();

          $this->_data['reviews_average_rating'] = round($Qavg->value('rating'));
        }
      }
    }
  }

  public function isValid() {
    return !empty($this->_data);
  }

  public function getData($key = null) {
    if ( isset($this->_data[$key]) ) {
      return $this->_data[$key];
    }

    return $this->_data;
  }

  public function getID() {
    return $this->_data['id'];
  }

  public function getMasterID() {
    return $this->_data['master_id'];
  }

  public function getTitle() {
    return $this->_data['name'];
  }

  public function getDescription() {
    return $this->_data['description'];
  }

  public function hasModel() {
    return (isset($this->_data['model']) && !empty($this->_data['model']));
  }

  public function getModel() {
    return $this->_data['model'];
  }

  public function hasKeyword() {
    return (isset($this->_data['keyword']) && !empty($this->_data['keyword']));
  }

  public function getKeyword() {
    return $this->_data['keyword'];
  }

  public function hasTags() {
    return (isset($this->_data['tags']) && !empty($this->_data['tags']));
  }

  public function getTags() {
    return $this->_data['tags'];
  }
  
  //######## PRICING #########//
  public function getPriceInfo($product_id, $customers_group_id = 1, $data) {
    global $lC_Specials, $lC_Database, $lC_Language, $lC_Customer, $lC_Services, $lC_Currencies;

    $quantity = (isset($_GET['quantity']) && $_GET['quantity'] != null) ? (int)$_GET['quantity'] : 1;

    // #### SET BASE PRICE #### //
    
    // initial price = base price    
    $base_price = $this->getBasePrice();
    $price = (float)$base_price;
    
    //options modifiers
    if (is_array($data['simple_options']) && count($data['simple_options']) > 0) {
      $modTotal = 0;
      foreach ($data['simple_options'] as $options_id => $values_id) {
        $QsimpleOptions = $lC_Database->query("select price_modifier from :table_products_simple_options_values where customers_group_id = :customers_group_id and options_id = :options_id and values_id = :values_id limit 1");
        $QsimpleOptions->bindTable(':table_products_simple_options_values', TABLE_PRODUCTS_SIMPLE_OPTIONS_VALUES);
        $QsimpleOptions->bindInt(':customers_group_id', $customers_group_id);        
        $QsimpleOptions->bindInt(':options_id', $options_id);        
        $QsimpleOptions->bindInt(':values_id', $values_id);        
        $QsimpleOptions->execute();
        
        $modTotal = (float)$modTotal + $QsimpleOptions->valueDecimal('price_modifier');
      }  
    }  
    
    // if has special price, base price becomes special price
    $special_price = 0.00;
    if ($lC_Services->isStarted('specials') && $lC_Specials->isActive($product_id)) {
      $special_price = $lC_Specials->getPrice($product_id);
      $price = ((float)$special_price < (float)$price) ? (float)$special_price : (float)$price;
    }       
    
    // if has qty price breaks, adjust base price to break price
    $qpbText = '';
    if ($this->hasQtyPriceBreaks($product_id, $customers_group_id)) {

      $qpbArr = $this->getQtyPriceBreaks($product_id, $customers_group_id);
      usort($qpbArr, "self::_usortBreakPoint"); 
      
      $maxBreak = end($qpbArr);
        
      $cnt = 0;
      foreach($qpbArr as $key => $value) {
        if ((int)$value['qty_break'] <= (int)$quantity ) {
          if ($lC_Services->isStarted('specials') && $lC_Specials->isActive($product_id)) {
            $price = ($special_price < (float)$value['price_break']) ? $special_price : (float)$value['price_break'];
          } else {
            $price = (float)$value['price_break'];
          }
          $cnt = $key;
        }
      }
      
      if (defined('PRODUCT_PRICING_QPB_FORMAT') && PRODUCT_PRICING_QPB_FORMAT != NULL) {
        switch (PRODUCT_PRICING_QPB_FORMAT) {
          case 'None' :
            $listing = $lC_Currencies->displayPrice($price, DECIMAL_PLACES);
            break;
          case 'Starts At' :
            $listing = '<div class="margin-top-neg"><span class="lt-blue">' . $lC_Language->get('pricing_starts_at') . '</span><p class="lead small-margin-bottom small-margin-top-neg">' . $lC_Currencies->displayPrice($this->getBasePrice(), $this->getTaxClassID()) . '</p></div>';          
            break;
          case 'Low As' :
            $listing = '<div class="margin-top-neg"><span class="lt-blue">' . $lC_Language->get('pricing_low_as')  . '</span><p class="lead small-margin-bottom small-margin-top-neg">' . $lC_Currencies->displayPrice( ($maxBreak['price_break'] < $price) ? $maxBreak['price_break'] : $price, $this->getTaxClassID()) . '</p></div>';          
            break;
          default :
            $listing =  $lC_Currencies->displayPrice( ($maxBreak['price_break'] < $price) ? $maxBreak['price_break'] : $price, $this->getTaxClassID()) . ' - ' . $lC_Currencies->displayPrice($price, $this->getTaxClassID());
        }
      }
      
      // if has special and qpb determine the next break point based off special price and adjust $cnt
      if ($special_price != 0.00) {
        $cnt = 0;
        usort($qpbArr, "self::_usortBreakPoint"); 
        foreach($qpbArr as $key => $value) {
          if ((int)$value['price_break'] < $special_price) {
            $cnt = $key;
            break;
          }
        }
      } else if ($quantity > 1 && $quantity >= $qpbArr[$cnt]['qty_break']) {
        $cnt++; 
      }
      
      $youSave =( ((int)$quantity == 1) ? round( ( 1- ( (float)$qpbArr[$cnt]['price_break'] / (float)$this->getBasePrice() )) * 100, DECIMAL_PLACES) : (((int)$quantity >= (int)$maxBreak['qty_break']) ? round( ( 1- ( (float)$maxBreak['price_break'] / (float)$this->getBasePrice() )) * 100, DECIMAL_PLACES) : round( ( 1- ( (float)$qpbArr[$cnt]['price_break'] / (float)$this->getBasePrice() )) * 100, DECIMAL_PLACES)));
      $qpbData = array('nextBreak' => ( ((int)$quantity == 1) ? (int)$qpbArr[$cnt]['qty_break'] : (($quantity >= (int)$maxBreak['qty_break']) ? $maxBreak['qty_break'] : $qpbArr[$cnt]['qty_break'])  ),
                       'nextPrice' => ( ((int)$quantity == 1) ? number_format($qpbArr[$cnt]['price_break'] + $modTotal, DECIMAL_PLACES) : (((int)$quantity >= $maxBreak['qty_break']) ? number_format($maxBreak['price_break'] + $modTotal, DECIMAL_PLACES) : number_format($qpbArr[$cnt]['price_break'] + $modTotal, DECIMAL_PLACES) ) ),
                       'youSave' => number_format($youSave, 0) . '%',
                       'listing' => $listing);
    }
    
    $price = $price + $modTotal;
    
    if ($lC_Services->isStarted('specials') && $lC_Specials->isActive($product_id)) {
      $formatted = '<s>' . $lC_Currencies->displayPrice($this->getBasePrice() + $modTotal, $this->_data['tax_class_id']) . '</s> <span class="product-special-price">' . $lC_Currencies->displayPrice($price, $this->_data['tax_class_id']) . '</span>';
    } else {
      $formatted = $lC_Currencies->displayPrice($price, $this->getTaxClassID());
    }
    
    // #### DISCOUNTS #### //
   /*
    // set the adjusted base price var
    $base_price = $price;    
    // if logged in and has a group baseline discount, apply to price
    if ($lC_Customer->isLoggedOn()) {
      $baseline_discount = $lC_Customer->getBaselineDiscount($customers_group_id);
      $price = round((float)$base_price * ((float)$baseline_discount * .01), DECIMAL_PLACES); 
    }
   */ 
    $return = array('base' => number_format($this->getBasePrice(), DECIMAL_PLACES),
                    'price' => number_format($price, DECIMAL_PLACES),
                    'formatted' => $formatted,
                    'modTotal' => $modTotal,
                    'qpbData' => $qpbData
                    );
//echo "<pre>return ";
//print_r($return);
//echo "</pre>";
//die('55');                    
                    
    return $return;                    
  }
  
  public function getPrice() {
    $data = $this->getPriceInfo($this->getID(), 1, array());
    
    return $data['price'];
  }

 /*
  * Retrieve the base price
  *
  * @access public
  * @return array
  */  
  public function getBasePrice() {
    return $this->_data['price'];
  }
 /*
  * Determine if product has quantity price breaks
  *
  * @param integer $products_id       The product id
  * @param integer $customers_group_id The customer group id
  * @access public
  * @return boolean
  */   
  public function hasQtyPriceBreaks($products_id, $customers_group_id = 1) {
    global $lC_Database;
    
    $Qpb = $lC_Database->query('select * from :table_products_pricing where products_id = :products_id and group_id = :group_id limit 1');
    $Qpb->bindTable(':table_products_pricing', TABLE_PRODUCTS_PRICING);
    $Qpb->bindInt(':products_id', $products_id);
    $Qpb->bindInt(':group_id', $customers_group_id);
    $Qpb->execute();  
    
    $hasQPB = false;
    if ($Qpb->numberOfRows() > 0) $hasQPB = true;
    
    $Qpb->freeResult();
    
    return $hasQPB;
  }
 /*
  * Retrieve quantity price breaks data
  *
  * @param integer $products_id       The product id
  * @param integer $customers_group_id The customer group id
  * @access public
  * @return array
  */   
  public function getQtyPriceBreaks($products_id, $customers_group_id = 1) {
    global $lC_Database;
    
    $Qpb = $lC_Database->query('select * from :table_products_pricing where products_id = :products_id and group_id = :group_id');
    $Qpb->bindTable(':table_products_pricing', TABLE_PRODUCTS_PRICING);
    $Qpb->bindInt(':products_id', $products_id);
    $Qpb->bindInt(':group_id', $customers_group_id);
    $Qpb->execute();  
    
    $data = array();
    while($Qpb->next()) {
      $data[] = $Qpb->toArray();
    }
    
    $Qpb->freeResult();
    
    return $data;
  }  
  
  public function getPriceFormated($with_special = false) {
    global $lC_Services, $lC_Specials, $lC_Currencies;
    
    $pData = $this->getPriceInfo($this->getID(), 1, array());
    
    if (isset($pData['qpbData']['listing']) && empty($pData['qpbData']['listing']) === false) {
      $result = $pData['qpbData']['listing'];
    } else {
      $result = $pData['formatted'];
    }
    
    
    return $result;
    
    /*
    if (($with_special === true) && $lC_Services->isStarted('specials') && ($new_price = $lC_Specials->getPrice($this->_data['id'])))  {
        $price = '<s>' . $lC_Currencies->displayPrice($this->_data['price'], $this->_data['tax_class_id']) . '</s> <span class="product-special-price">' . $lC_Currencies->displayPrice($new_price, $this->_data['tax_class_id']) . '</span>';
    } else {
      if ( $this->hasVariants() ) {
        $price = 'from&nbsp;' . $lC_Currencies->displayPrice($this->getVariantMinPrice(), $this->_data['tax_class_id']);
      } else {
        $price = $lC_Currencies->displayPrice($this->getPrice(), $this->getTaxClassID());
      }
    }

    return $price;
    */
  }

  public function getVariantMinPrice() {
    $price = null;

    foreach ( $this->_data['variants'] as $variant ) {
      if ( ($price === null) || ($variant['data']['price'] < $price) ) {
        $price = $variant['data']['price'];
      }
    }

    return ( $price !== null ) ? $price : 0;
  }

  public function getVariantMaxPrice() {
    $price = 0;

    foreach ( $this->_data['variants'] as $variant ) {
      if ( $variant['data']['price'] > $price ) {
        $price = $variant['data']['price'];
      }
    }

    return $price;
  }

  //######## PRICING eof #########//
  
  public function getQuantity() {
    $quantity = $this->_data['quantity'];

    if ( $this->hasVariants() ) {
      $quantity = 0;
      foreach ( $this->_data['variants'] as $variants ) {
        $quantity += $variants['data']['quantity'];
      }
    }

    return $quantity;
  }
  
  public function getTaxClassID($qty = 1) {
    $tax_class_id = $this->_data['tax_class_id'];     
    if (isset($this->_data['price_breaks'])) {
      reset($this->_data['price_breaks']);
      foreach ($this->_data['price_breaks'] as $value) {
        if ($qty >= $value['qty_break']) {
          $tax_class_id = $value['tax_class_id'];
        }    
      }    
    }
    
    return $tax_class_id;        
  }
  
  public function getWeight() {
    global $lC_Weight;

    $weight = 0;

    if ( $this->hasVariants() ) {
      foreach ( $this->_data['variants'] as $subproduct_id => $variants ) {
        foreach ( $variants['values'] as $group_id => $values ) {
          foreach ( $values as $value_id => $data ) {
            if ( $data['default'] === true ) {
              $weight = $lC_Weight->display($variants['data']['weight'], $variants['data']['weight_class_id']);

              break 3;
            }
          }
        }
      }
    } else {
      $weight = $lC_Weight->display($this->_data['weight'], $this->_data['weight_class_id']);
    }

    return $weight;
  }

  public function hasManufacturer() {
    return ( $this->_data['manufacturers_id'] > 0 );
  }

  public function getManufacturer() {
    global $lC_Vqmod;
    
    if ( !class_exists('lC_Manufacturer') ) {
      include($lC_Vqmod->modCheck('includes/classes/manufacturer.php'));
    }

    $lC_Manufacturer = new lC_Manufacturer($this->_data['manufacturers_id']);

    return $lC_Manufacturer->getTitle();
  }

  public function getManufacturerID() {
    return $this->_data['manufacturers_id'];
  }

  public function getCategoryID() {
    return $this->_data['category_id'];
  }

  public function getImages() {
    return $this->_data['images'];
  }

  public function hasImage() {
    foreach ($this->_data['images'] as $image) {
      if ($image['default_flag'] == '1') {
        return true;
      }
    }
  }

  public function getImage() {
    if (isset($this->_data['images'])) {
      foreach ($this->_data['images'] as $image) {
        if ($image['default_flag'] == '1') {
          return $image['image'];
        }
      }
    }
  }

  public function hasURL() {
    return (isset($this->_data['url']) && !empty($this->_data['url']));
  }

  public function getURL() {
    return $this->_data['url'];
  }

  public function getDateAvailable() {
    return false; //$this->_data['date_available'];
  }

  public function getDateAdded() {
    return $this->_data['date_added'];
  }
  
  public function hasSimpleOptions() {
    return (isset($this->_data['simple_options']) && !empty($this->_data['simple_options']));
  }  

  public function getSimpleOptions() {
    return $this->_data['simple_options'][$this->_data['master_id']]['values'];
  }  
  
  public function hasVariants() {
    return (isset($this->_data['variants']) && !empty($this->_data['variants']));
  }
  
  public function getVariants($filter_duplicates = true) {
    if ( $filter_duplicates === true ) {
      $values_array = array();

      foreach ( $this->_data['variants'] as $product_id => $variants ) {
        foreach ( $variants['values'] as $group_id => $values ) {
          foreach ( $values as $value_id => $value ) {
            if ( !isset($values_array[$group_id]) ) {
              $values_array[$group_id]['group_id'] = $group_id;
              $values_array[$group_id]['title'] = $value['group_title'];
              $values_array[$group_id]['module'] = $value['module'];
            }

            $value_exists = false;

            if ( isset($values_array[$group_id]['data']) ) {
              foreach ( $values_array[$group_id]['data'] as $data ) {
                if ( $data['id'] == $value_id ) {
                  $value_exists = true;

                  break;
                }
              }
            }

            if ( $value_exists === false ) {
              $values_array[$group_id]['data'][] = array('id' => $value_id,
                                                         'text' => $value['value_title'],
                                                         'default' => $value['default'],
                                                         'sort_order' => $value['sort_order']);
            } elseif ( $value['default'] === true ) {
              foreach ( $values_array[$group_id]['data'] as &$existing_data ) {
                if ( $existing_data['id'] == $value_id ) {
                  $existing_data['default'] = true;

                  break;
                }
              }
            }
          }
        }
      }

      foreach ( $values_array as $group_id => &$value ) {
        usort($value['data'], array('lC_Product', '_usortVariantValues'));
      }

      return $values_array;
    }

    return $this->_data['variants'];
  }

  public function variantExists($variant) {
    return is_numeric($this->getProductVariantID($variant));
  }

  public function getProductVariantID($variant) {
    $_product_id = false;

    $_size = sizeof($variant);

    foreach ( $this->_data['variants'] as $product_id => $variants ) {
      if ( sizeof($variants['values']) === $_size ) {
        $_array = array();

        foreach ( $variants['values'] as $group_id => $value ) {
          foreach ( $value as $value_id => $value_data ) {
            if ( is_array($variant[$group_id]) && array_key_exists($value_id, $variant[$group_id]) ) {
              $_array[$group_id][$value_id] = $variant[$group_id][$value_id];
            } else {
              $_array[$group_id] = $value_id;
            }
          }
        }

        if ( sizeof(array_diff_assoc($_array, $variant)) === 0 ) {
          $_product_id = $product_id;

          break;
        }
      }
    }

    return $_product_id;
  }

  public function hasAttribute($code) {
    return isset($this->_data['attributes'][$code]);
  }

  public function getAttribute($code) {
    global $lC_Vqmod;
    
    if ( !class_exists('lC_ProductAttributes_' . $code) ) {
      if ( file_exists(DIR_FS_CATALOG . 'includes/modules/product_attributes/' . basename($code) . '.php') ) {
        include($lC_Vqmod->modCheck(DIR_FS_CATALOG . 'includes/modules/product_attributes/' . basename($code) . '.php'));
      }
    }

    if ( class_exists('lC_ProductAttributes_' . $code) ) {
      return call_user_func(array('lC_ProductAttributes_' . $code, 'getValue'), $this->_data['attributes'][$code]);
    }
  }

  public function checkEntry($id) {
    global $lC_Database;

    $Qproduct = $lC_Database->query('select p.products_id from :table_products p');
    $Qproduct->bindTable(':table_products', TABLE_PRODUCTS);

    if ( is_numeric($id) ) {
      $Qproduct->appendQuery('where p.products_id = :products_id');
      $Qproduct->bindInt(':products_id', $id);
    } else {
      $Qproduct->appendQuery(', :table_products_description pd where pd.products_keyword = :products_keyword and pd.products_id = p.products_id');
      $Qproduct->bindTable(':table_products_description', TABLE_PRODUCTS_DESCRIPTION);
      $Qproduct->bindValue(':products_keyword', $id);
    }

    $Qproduct->appendQuery('and p.products_status = 1 limit 1');
    $Qproduct->execute();

    return ( $Qproduct->numberOfRows() === 1 );
  }

  public function incrementCounter() {
    global $lC_Database, $lC_Language;

    $Qupdate = $lC_Database->query('update :table_products_description set products_viewed = products_viewed+1 where products_id = :products_id and language_id = :language_id');
    $Qupdate->bindTable(':table_products_description', TABLE_PRODUCTS_DESCRIPTION);
    $Qupdate->bindInt(':products_id', lc_get_product_id($this->_data['id']));
    $Qupdate->bindInt(':language_id', $lC_Language->getID());
    $Qupdate->execute();
  }

  public function numberOfImages() {
    return sizeof($this->_data['images']);
  }
  
  public function getAdditionalImagesHtml($size = 'mini') {
    global $lC_Image, $lC_Language;
    
    $output = '';
    $model = '';
    $_product_additionalimages = '';
    $$popup_image_modal_id = '';
    foreach ( $this->getImages() as $key => $value ) {
      if ($value['default_flag'] == true) continue;      

      if(file_exists(DIR_FS_CATALOG . $lC_Image->getAddress($value['image'], 'popup'))) {      
        $link = lc_href_link(DIR_WS_CATALOG . $lC_Image->getAddress($value['image'], 'popup'));
      } else {
        $link = lc_href_link(DIR_WS_IMAGES . 'no_image.png');
      }
      $output .= '<li><a data-toggle="modal" href="#popup-image-modal-'.$key.'"><img src="' . $lC_Image->getAddress($value['image'], $size) . '" title="' . $this->getTitle() . '" /></a></li>'; 

      if(file_exists(DIR_FS_CATALOG . $lC_Image->getAddress($value['image'], 'originals'))) {
        $link_image_modal = lc_href_link($lC_Image->getAddress($value['image'], 'originals'));
      } else {
        $link_image_modal = lc_href_link(DIR_WS_IMAGES . 'no_image.png');
      }
      $model .= '<!-- Modal -->
    <div class="modal fade" id="popup-image-modal-'.$key.'">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">'. $this->getTitle() .'</h4>
          </div>
          <div class="modal-body">
            <img class="img-responsive" alt="'. $this->getTitle() .'" src="'. $link_image_modal .'">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">'. $lC_Language->get('button_close').'</button>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->'. "\n";
    }
    $_product_additionalimages['images'] = $output;
    $_product_additionalimages['model'] = $model;
    
    return $_product_additionalimages;    
  }
 /*
  * Determine if the product has subproducts
  *
  * @param integer $id The product id
  * @access public
  * @return boolean
  */    
  public function hasSubProducts($id) {
    global $lC_Database;

    $Qchk = $lC_Database->query('select products_id from :table_products where parent_id = :parent_id and is_subproduct = :is_subproduct limit 1');
    $Qchk->bindTable(':table_products', TABLE_PRODUCTS);
    $Qchk->bindInt(':parent_id', $id);
    $Qchk->bindInt(':is_subproduct', 1);
    $Qchk->execute();

    if ( $Qchk->numberOfRows() === 1 ) {
      return true;
    }

    return false;
  }      
 /*
  * Retrieve the subproducts
  *
  * @param integer $id The product id
  * @access public
  * @return array
  */  
  public function getSubProducts($id) {
    global $lC_Database, $lC_Language;

    $Qproducts = $lC_Database->query('select p.*, pd.products_name, pd.products_keyword from :table_products p, :table_products_description pd where p.parent_id = :parent_id and p.products_id = pd.products_id and pd.language_id = :language_id');
    $Qproducts->bindTable(':table_products', TABLE_PRODUCTS);
    $Qproducts->bindTable(':table_products_description', TABLE_PRODUCTS_DESCRIPTION);
    $Qproducts->bindInt(':parent_id', $id);
    $Qproducts->bindInt(':language_id', $lC_Language->getID());
    $Qproducts->execute();  
    
    $result = array();
    while ($Qproducts->next()) {
       
      $image = array();
      $Qimages = $lC_Database->query('select id, image, default_flag from :table_products_images where products_id = :products_id order by sort_order');
      $Qimages->bindTable(':table_products_images', TABLE_PRODUCTS_IMAGES);
      $Qimages->bindInt(':products_id', $Qproducts->valueInt('products_id'));
      $Qimages->execute();

      while ($Qimages->next()) {
        if ($Qimages->valueInt('default_flag') == '1') $image['image'] = $Qimages->value('image');
      }
      
      $Qimages->freeResult();
      
      $result[] = array_merge((array)$Qproducts->toArray(), (array)$image); 
    }
   
    $Qproducts->freeResult();
    
    return $result;  
  }
 /*
  * Parse the subproduct data into HTML
  *
  * @param  integer  $group The product id
  * @param  array    $data  The product id
  * @access public
  * @return array
  */ 
  public function parseSubProducts($data) {
    global $lC_Image, $lC_Currencies, $lC_Language;
    
    $output = '';
    foreach ($data as $key => $value) {
      
     $extra = ''; 
     // $extra = (isset($value['products_model']) && empty($value['products_model']) === false) ? '<em>' . $lC_Language->get('listing_model_heading') . ': ' . $value['products_model'] . '</em>' : null;
     // if ($extra == null && isset($value['products_sku']) && empty($value['products_sku']) === false) $extra = '<em>' . $lC_Language->get('listing_sku_heading') . ': ' . $value['products_sku'] . '</em>';
      
      $img = (isset($value['image']) && empty($value['image']) === false) ? $value['image'] : 'no_image.png';
      $output .= '<div class="row clear-both margin-bottom margin-top">' .
                 '  <div class="col-sm-8 col-lg-8">' .
                 '    <span class="subproduct-image pull-left margin-right">' . 
                 '      <img class="img-responsive" src="' . $lC_Image->getAddress($img, 'small') . '" title="' . $value['products_name'] . '" alt="' . $value['products_name'] . '" />' .
                 '    </span>' .
                 '    <span class="subproduct-name lead lt-blue no-margin-bottom">' . $value['products_name'] . '</span><br />' . 
                 ((isset($extra) && $extra != null) ? '<span class="subproduct-model small-margin-left no-margin-top"><small>' . $extra . '</small></span>' : null) .
                 '  </div>' .
                 '  <div class="col-sm-4 col-lg-4">' .
                 '    <span class="subproduct-price lead">' . $lC_Currencies->format($value['products_price']) . '</span>' .
                 '    <span class="subproduct-buy-now pull-right">' . 
                 '      <form method="post" action="' . lc_href_link(FILENAME_DEFAULT, $value['products_id'] . '&action=cart_add') . '"><button class="subproduct-buy-now-button btn btn-success" type="submit" onclick="$(this).closest(\'form\').submit();">Buy Now</button></form>' . 
                 '    </span>' .
                 '  </div>' .
                 '</div>';
    }
    
    return $output;
  }
 /*
  * Custom variant sort
  *
  * @param integer $id The product id
  * @access private
  * @return boolean
  */ 
  protected static function _usortVariantValues($a, $b) {
    if ( $a['sort_order'] == $b['sort_order'] ) {
      return strnatcasecmp($a['text'], $b['text']);
    }

    return ( $a['sort_order'] < $b['sort_order'] ) ? -1 : 1;
  }
 /*
  * Custom quantity price breaks sort
  *
  * @param integer $a The 1st sort value
  * @param integer $b The 2nd sort value
  * @access protected
  * @return boolean
  */ 
  protected static function _usortBreakPoint($a, $b) {
    return $a['qty_break'] == $b['qty_break'] ? 0 : $a['qty_break'] > $b['qty_break'] ? 1 : -1;
  }  
} 
?>