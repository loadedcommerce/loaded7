<?php
/**
  $Id: product.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
class lC_Product {
  protected $_data = array();

  public function __construct($id) {
    global $lC_Database, $lC_Services, $lC_Language, $lC_Image;

    if ( !empty($id) ) {
      if ( is_numeric($id) ) {
        $Qproduct = $lC_Database->query('select products_id as id, parent_id, products_quantity as quantity, products_price as price, products_model as model, products_tax_class_id as tax_class_id, products_weight as weight, products_weight_class as weight_class_id, products_date_added as date_added, manufacturers_id, has_children from :table_products where products_id = :products_id and products_status = :products_status');
        $Qproduct->bindTable(':table_products', TABLE_PRODUCTS);
        $Qproduct->bindInt(':products_id', $id);
        $Qproduct->bindInt(':products_status', 1);
        $Qproduct->execute();

        if ( $Qproduct->numberOfRows() === 1 ) {
          $this->_data = $Qproduct->toArray();

          $this->_data['master_id'] = $Qproduct->valueInt('id');
          $this->_data['has_children'] = $Qproduct->valueInt('has_children');

          if ( $Qproduct->valueInt('parent_id') > 0 ) {
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

            $this->_data = array_merge($this->_data, $Qdesc->toArray());
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
        $Qpb->bindInt(':group_id', (isset($_SESSION['lC_Customer_data']['customers_group_id'])? $_SESSION['lC_Customer_data']['customers_group_id'] : 1));
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
            //$this->_data['variants'][$Qsubproducts->valueInt('products_id')]['data'] = array('price' => $this->getPriceBreak(),
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
          $QsimpleOptionsValues = $lC_Database->query('select values_id, price_modifier from :table_products_simple_options_values where options_id = :options_id and customers_group_id = :customers_group_id');
          $QsimpleOptionsValues->bindTable(':table_products_simple_options_values', TABLE_PRODUCTS_SIMPLE_OPTIONS_VALUES);
          $QsimpleOptionsValues->bindInt(':options_id', $QsimpleOptions->valueInt('options_id'));
          $QsimpleOptionsValues->bindInt(':customers_group_id', '1');
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

  public function getBasePrice() {
    return $this->_data['price'];
  }
  
  public function getPriceBreak($qty = 1) {
    $base_price = $this->_data['price'];  
       
    if (isset($this->_data['price_breaks'])) {
      reset($this->_data['price_breaks']);
      foreach ($this->_data['price_breaks'] as $value) {
        if ($qty >= $value['qty_break']) {
          $base_price = $value['price_break'];
        }    
      }    
    }
    
    return $base_price;        
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

  public function getPriceFormated($with_special = false) {
    global $lC_Services, $lC_Specials, $lC_Currencies;

    if (($with_special === true) && $lC_Services->isStarted('specials') && ($new_price = $lC_Specials->getPrice($this->_data['id'])) && ($lC_Specials->getPrice($this->_data['id']) < $this->getPriceBreak())  ) {
     // $price = '<big>' . $lC_Currencies->displayPrice($new_price, $this->_data['tax_class_id']) . '</big><small>' . $lC_Currencies->displayPrice($this->_data['price'], $this->_data['tax_class_id']) . '</small>'; 
        $price = '<s>' . $lC_Currencies->displayPrice($this->_data['price'], $this->_data['tax_class_id']) . '</s> <span class="product-special-price">' . $lC_Currencies->displayPrice($new_price, $this->_data['tax_class_id']) . '</span>';
    } else {
      if ( $this->hasVariants() ) {
        $price = 'from&nbsp;' . $lC_Currencies->displayPrice($this->getVariantMinPrice(), $this->_data['tax_class_id']);
      } else {
        $price = $lC_Currencies->displayPrice($this->getPriceBreak(), $this->getTaxClassID());
      }
    }

    return $price;
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
    global $lC_Image;
    
    $output = '';
    foreach ( $this->getImages() as $key => $value ) {
      if ($value['default_flag'] == true) continue;
      $output .= '<li><a href="' . (file_exists(DIR_FS_CATALOG . $lC_Image->getAddress($value['image'], 'popup'))) ? lc_href_link(DIR_WS_CATALOG . $lC_Image->getAddress($value['image'], 'popup')) : lc_href_link(DIR_WS_IMAGES . 'no_image.png') . '" title="<?php echo $lC_Product->getTitle(); ?>" class="thickbox"><img src="' . $lC_Image->getAddress($value['image'], $size) . '" title="' . $lC_Product->getTitle() . '" /></a></li>';
    }
    
    return $output;    
  }

  protected static function _usortVariantValues($a, $b) {
    if ( $a['sort_order'] == $b['sort_order'] ) {
      return strnatcasecmp($a['text'], $b['text']);
    }

    return ( $a['sort_order'] < $b['sort_order'] ) ? -1 : 1;
  }

  public function hasSpecial() {
    global $lC_Database;


    $Qspecial = $lC_Database->query('select specials_id from :table_specials where products_id = :products_id limit 1');
    $Qspecial->bindTable(':table_specials', TABLE_SPECIALS);
    $Qspecial->bindInt(':products_id', $this->getID());
    $Qspecial->execute();

    if ( $Qspecial->numberOfRows() === 1 ) {

      return true;
    }

    return false;
  }
}
?>