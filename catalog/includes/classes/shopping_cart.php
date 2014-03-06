<?php
/**
  @package    catalog::classes
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: shopping_cart.php v1.0 2013-08-08 datazen $
*/
class lC_ShoppingCart {
  private $_contents = array();
  private $_sub_total = 0;
  private $_total = 0;
  private $_weight = 0;
  private $_tax = 0;
  private $_tax_groups = array();
  private $_content_type;
  private $_products_in_stock = true;

  public function __construct() {
    if ( !isset($_SESSION['lC_ShoppingCart_data']) ) {
      $_SESSION['lC_ShoppingCart_data'] = array('contents' => array(),
                                                'sub_total_cost' => 0,
                                                'total_cost' => 0,
                                                'total_weight' => 0,
                                                'tax' => 0,
                                                'tax_groups' => array(),
                                                'shipping_boxes_weight' => 0,
                                                'shipping_boxes' => 1,
                                                'shipping_address' => array('zone_id' => STORE_ZONE,
                                                                            'country_id' => STORE_COUNTRY),
                                                'shipping_method' => array(),
                                                'billing_address' => array('zone_id' => STORE_ZONE,
                                                                           'country_id' => STORE_COUNTRY),
                                                'billing_method' => array(),
                                                'shipping_quotes' => array(),
                                                'order_totals' => array());

      $this->resetShippingAddress();
      $this->resetBillingAddress();
    }

    $this->_contents =& $_SESSION['lC_ShoppingCart_data']['contents'];
    $this->_sub_total =& $_SESSION['lC_ShoppingCart_data']['sub_total_cost'];
    $this->_total =& $_SESSION['lC_ShoppingCart_data']['total_cost'];
    $this->_weight =& $_SESSION['lC_ShoppingCart_data']['total_weight'];
    $this->_tax =& $_SESSION['lC_ShoppingCart_data']['tax'];
    $this->_tax_groups =& $_SESSION['lC_ShoppingCart_data']['tax_groups'];
    $this->_shipping_boxes_weight =& $_SESSION['lC_ShoppingCart_data']['shipping_boxes_weight'];
    $this->_shipping_boxes =& $_SESSION['lC_ShoppingCart_data']['shipping_boxes'];
    $this->_shipping_address =& $_SESSION['lC_ShoppingCart_data']['shipping_address'];
    $this->_shipping_method =& $_SESSION['lC_ShoppingCart_data']['shipping_method'];
    $this->_billing_address =& $_SESSION['lC_ShoppingCart_data']['billing_address'];
    $this->_billing_method =& $_SESSION['lC_ShoppingCart_data']['billing_method'];
    $this->_shipping_quotes =& $_SESSION['lC_ShoppingCart_data']['shipping_quotes'];
    $this->_order_totals =& $_SESSION['lC_ShoppingCart_data']['order_totals'];    
  }

  public function refresh($recalc = false) {
    if ($recalc !== false) {
      $this->_calculate(false, false);
    } else {
      if (!isset($_SESSION['cartID'])) {
        $this->_calculate();
      }
    }
  }

  public function hasContents() {
    return !empty($this->_contents);
  }

  public function synchronizeWithDatabase() {
    global $lC_Database, $lC_Services, $lC_Language, $lC_Customer, $lC_Specials;

    if ( !$lC_Customer->isLoggedOn() ) {
      return false;
    }

    foreach ( $this->_contents as $item_id => $data ) {
      $db_action = 'check';

      if ( isset($data['variants']) ) {
        foreach ( $data['variants'] as $variant ) {
          if ( $variant['has_custom_value'] === true ) {
            $db_action = 'insert';

            break;
          }
        }
      }

      if ( $db_action == 'check' ) {
        $Qproduct = $lC_Database->query('select item_id, meta_data, quantity from :table_shopping_carts where customers_id = :customers_id and products_id = :products_id');
        $Qproduct->bindTable(':table_shopping_carts', TABLE_SHOPPING_CARTS);
        $Qproduct->bindInt(':customers_id', $lC_Customer->getID());
        $Qproduct->bindInt(':products_id', $data['id']);
        $Qproduct->execute();
        
        if ( $Qproduct->numberOfRows() > 0 && $data['meta_data'] == $Qproduct->value('meta_data')) {
          $Qupdate = $lC_Database->query('update :table_shopping_carts set quantity = :quantity where customers_id = :customers_id and item_id = :item_id');
          $Qupdate->bindTable(':table_shopping_carts', TABLE_SHOPPING_CARTS);
          $Qupdate->bindInt(':quantity', $data['quantity'] + $Qproduct->valueInt('quantity'));
          $Qupdate->bindInt(':customers_id', $lC_Customer->getID());
          $Qupdate->bindInt(':item_id', $Qproduct->valueInt('item_id'));
          $Qupdate->execute();
        } else {
          $db_action = 'insert';
        }
      }

      if ( $db_action == 'insert') {
        $Qid = $lC_Database->query('select max(item_id) as item_id from :table_shopping_carts where customers_id = :customers_id');
        $Qid->bindTable(':table_shopping_carts', TABLE_SHOPPING_CARTS);
        $Qid->bindInt(':customers_id', $lC_Customer->getID());
        $Qid->execute();

        $db_item_id = $Qid->valueInt('item_id') + 1;
       
        $meta_data = (is_array($data['simple_options']) && empty($data['simple_options']) === false) ? $data['simple_options'] : array();
        
        $Qnew = $lC_Database->query('insert into :table_shopping_carts (customers_id, item_id, products_id, quantity, meta_data, date_added) values (:customers_id, :item_id, :products_id, :quantity, :meta_data, :date_added)');
        $Qnew->bindTable(':table_shopping_carts', TABLE_SHOPPING_CARTS);
        $Qnew->bindInt(':customers_id', $lC_Customer->getID());
        $Qnew->bindInt(':item_id', $db_item_id);
        $Qnew->bindInt(':products_id', $data['id']);
        $Qnew->bindInt(':quantity', $data['quantity']);
        $Qnew->bindInt(':meta_data', serialize($meta_data));
        $Qnew->bindRaw(':date_added', 'now()');
        $Qnew->execute();

        if ( isset($data['variants']) ) {
          foreach ( $data['variants'] as $variant ) {
            if ( $variant['has_custom_value'] === true ) {
              $Qnew = $lC_Database->query('insert into :table_shopping_carts_custom_variants_values (shopping_carts_item_id, customers_id, products_id, products_variants_values_id, products_variants_values_text) values (:shopping_carts_item_id, :customers_id, :products_id, :products_variants_values_id, :products_variants_values_text)');
              $Qnew->bindTable(':table_shopping_carts_custom_variants_values', TABLE_SHOPPING_CARTS_CUSTOM_VARIANTS_VALUES);
              $Qnew->bindInt(':shopping_carts_item_id', $db_item_id);
              $Qnew->bindInt(':customers_id', $lC_Customer->getID());
              $Qnew->bindInt(':products_id', $data['id']);
              $Qnew->bindInt(':products_variants_values_id', $variant['value_id']);
              $Qnew->bindValue(':products_variants_values_text', $variant['value_title']);
              $Qnew->execute();
            }
          }
        }
      }
    }

    // reset per-session cart contents, but not the database contents
    $this->reset();

    $_delete_array = array();

    $Qproducts = $lC_Database->query('select sc.item_id, sc.products_id, sc.quantity, sc.meta_data, sc.date_added, p.parent_id, p.products_price, p.products_model, p.products_tax_class_id, p.products_weight, p.products_weight_class, p.products_status from :table_shopping_carts sc, :table_products p where sc.customers_id = :customers_id and sc.products_id = p.products_id order by sc.date_added desc');
    $Qproducts->bindTable(':table_shopping_carts', TABLE_SHOPPING_CARTS);
    $Qproducts->bindTable(':table_products', TABLE_PRODUCTS);
    $Qproducts->bindInt(':customers_id', $lC_Customer->getID());
    $Qproducts->execute();

    while ( $Qproducts->next() ) {
      if ( $Qproducts->valueInt('products_status') === 1 ) {
        $Qdesc = $lC_Database->query('select products_name, products_keyword, products_description from :table_products_description where products_id = :products_id and language_id = :language_id');
        $Qdesc->bindTable(':table_products_description', TABLE_PRODUCTS_DESCRIPTION);
        $Qdesc->bindInt(':products_id', ($Qproducts->valueInt('parent_id') > 0) ? $Qproducts->valueInt('parent_id') : $Qproducts->valueInt('products_id'));
        $Qdesc->bindInt(':language_id', $lC_Language->getID());
        $Qdesc->execute();

        $Qimage = $lC_Database->query('select image from :table_products_images where products_id = :products_id and default_flag = :default_flag');
        $Qimage->bindTable(':table_products_images', TABLE_PRODUCTS_IMAGES);
        $Qimage->bindInt(':products_id', ($Qproducts->valueInt('parent_id') > 0) ? $Qproducts->valueInt('parent_id') : $Qproducts->valueInt('products_id'));
        $Qimage->bindInt(':default_flag', 1);
        $Qimage->execute();

        $price = $Qproducts->value('products_price');

        if ( $lC_Services->isStarted('specials') ) {
          if ( $new_price = $lC_Specials->getPrice($Qproducts->valueInt('products_id')) ) {
            $price = $new_price;
          }
        }     

        $this->_contents[$Qproducts->valueInt('item_id')] = array('item_id' => $Qproducts->valueInt('item_id'),
                                                                  'id' => $Qproducts->valueInt('products_id'),
                                                                  'parent_id' => $Qproducts->valueInt('parent_id'),
                                                                  'model' => $Qproducts->value('products_model'),
                                                                  'name' => $Qdesc->value('products_name'),
                                                                  'keyword' => $Qdesc->value('products_keyword'),
                                                                  'description' => $Qdesc->value('products_description'),
                                                                  'image' => ($Qimage->numberOfRows() === 1) ? $Qimage->value('image') : '',
                                                                  'price' => $price,
                                                                  'quantity' => $Qproducts->valueInt('quantity'),
                                                                  'weight' => $Qproducts->value('products_weight'),
                                                                  'tax_class_id' => $Qproducts->valueInt('products_tax_class_id'),
                                                                  'date_added' => lC_DateTime::getShort($Qproducts->value('date_added')),
                                                                  'weight_class_id' => $Qproducts->valueInt('products_weight_class'));

                                                                  
        $this->_contents[$Qproducts->valueInt('item_id')]['simple_options'] = unserialize($Qproducts->value('meta_data'));                                                                    

        if ( $Qproducts->valueInt('parent_id') > 0 ) {
          $Qcheck = $lC_Database->query('select products_status from :table_products where products_id = :products_id');
          $Qcheck->bindTable(':table_products', TABLE_PRODUCTS);
          $Qcheck->bindInt(':products_id', $Qproducts->valueInt('parent_id'));
          $Qcheck->execute();

          if ( $Qcheck->valueInt('products_status') === 1 ) {
            $Qvariant = $lC_Database->query('select pvg.id as group_id, pvg.title as group_title, pvg.module, pvv.id as value_id, pvv.title as value_title from :table_products_variants pv, :table_products_variants_values pvv, :table_products_variants_groups pvg where pv.products_id = :products_id and pv.products_variants_values_id = pvv.id and pvv.languages_id = :languages_id and pvv.products_variants_groups_id = pvg.id and pvg.languages_id = :languages_id');
            $Qvariant->bindTable(':table_products_variants', TABLE_PRODUCTS_VARIANTS);
            $Qvariant->bindTable(':table_products_variants_values', TABLE_PRODUCTS_VARIANTS_VALUES);
            $Qvariant->bindTable(':table_products_variants_groups', TABLE_PRODUCTS_VARIANTS_GROUPS);
            $Qvariant->bindInt(':products_id', $Qproducts->valueInt('products_id'));
            $Qvariant->bindInt(':languages_id', $lC_Language->getID());
            $Qvariant->bindInt(':languages_id', $lC_Language->getID());
            $Qvariant->execute();

            if ( $Qvariant->numberOfRows() > 0 ) {
              while ( $Qvariant->next() ) {
                $group_title = lC_Variants::getGroupTitle($Qvariant->value('module'), $Qvariant->toArray());
                $value_title = $Qvariant->value('value_title');
                $has_custom_value = false;

                $Qcvv = $lC_Database->query('select products_variants_values_text from :table_shopping_carts_custom_variants_values where customers_id = :customers_id and shopping_carts_item_id = :shopping_carts_item_id and products_id = :products_id and products_variants_values_id = :products_variants_values_id');
                $Qcvv->bindTable(':table_shopping_carts_custom_variants_values', TABLE_SHOPPING_CARTS_CUSTOM_VARIANTS_VALUES);
                $Qcvv->bindInt(':customers_id', $lC_Customer->getID());
                $Qcvv->bindInt(':shopping_carts_item_id', $Qproducts->valueInt('item_id'));
                $Qcvv->bindInt(':products_id', $Qproducts->valueInt('products_id'));
                $Qcvv->bindInt(':products_variants_values_id', $Qvariant->valueInt('value_id'));
                $Qcvv->execute();

                if ( $Qcvv->numberOfRows() === 1 ) {
                  $value_title = $Qcvv->value('products_variants_values_text');
                  $has_custom_value = true;
                }

                $this->_contents[$Qproducts->valueInt('item_id')]['variants'][] = array('group_id' => $Qvariant->valueInt('group_id'),
                  'value_id' => $Qvariant->valueInt('value_id'),
                  'group_title' => $group_title,
                  'value_title' => $value_title,
                  'has_custom_value' => $has_custom_value);
              }
            } else {
              $_delete_array[] = $Qproducts->valueInt('item_id');
            }
          } else {
            $_delete_array[] = $Qproducts->valueInt('item_id');
          }
        }
      } else {
        $_delete_array[] = $Qproducts->valueInt('item_id');
      }
    }

    if ( !empty($_delete_array) ) {
      foreach ( $_delete_array as $id ) {
        unset($this->_contents[$id]);
      }

      $Qdelete = $lC_Database->query('delete from :table_shopping_carts where customers_id = :customers_id and item_id in (":item_id")');
      $Qdelete->bindTable(':table_shopping_carts', TABLE_SHOPPING_CARTS);
      $Qdelete->bindInt(':customers_id', $lC_Customer->getID());
      $Qdelete->bindRaw(':item_id', implode('", "', $_delete_array));
      $Qdelete->execute();

      $Qdelete = $lC_Database->query('delete from :table_shopping_carts_custom_variants_values where customers_id = :customers_id and shopping_carts_item_id in (":shopping_carts_item_id")');
      $Qdelete->bindTable(':table_shopping_carts_custom_variants_values', TABLE_SHOPPING_CARTS_CUSTOM_VARIANTS_VALUES);
      $Qdelete->bindInt(':customers_id', $lC_Customer->getID());
      $Qdelete->bindRaw(':shopping_carts_item_id', implode('", "', $_delete_array));
      $Qdelete->execute();
    }

    $this->_cleanUp();
    $this->_calculate();
  }

  public function reset($reset_database = false) {
    global $lC_Database, $lC_Customer;

    if ( ($reset_database === true) && $lC_Customer->isLoggedOn() ) {
      $Qdelete = $lC_Database->query('delete from :table_shopping_carts where customers_id = :customers_id');
      $Qdelete->bindTable(':table_shopping_carts', TABLE_SHOPPING_CARTS);
      $Qdelete->bindInt(':customers_id', $lC_Customer->getID());
      $Qdelete->execute();

      $Qdelete = $lC_Database->query('delete from :table_shopping_carts_custom_variants_values where customers_id = :customers_id');
      $Qdelete->bindTable(':table_shopping_carts_custom_variants_values', TABLE_SHOPPING_CARTS_CUSTOM_VARIANTS_VALUES);
      $Qdelete->bindInt(':customers_id', $lC_Customer->getID());
      $Qdelete->execute();
    }

    $this->_contents = array();
    $this->_sub_total = 0;
    $this->_total = 0;
    $this->_weight = 0;
    $this->_tax = 0;
    $this->_tax_groups = array();
    $this->_content_type = null;

    $this->resetShippingAddress();
    $this->resetShippingMethod();
    $this->resetBillingAddress();
    $this->resetBillingMethod();

    if ( isset($_SESSION['cartID']) ) {
      unset($_SESSION['cartID']);
    }
  }

  public function add($product_id, $quantity = null) {
    global $lC_Database, $lC_Services, $lC_Language, $lC_Customer, $lC_Product;

    if ( !is_numeric($product_id) ) {
      return false;
    }
    
    $Qproduct = $lC_Database->query('select p.parent_id, p.products_price, p.products_tax_class_id, p.products_model, p.products_weight, p.products_weight_class, p.products_status, p.is_subproduct, i.image from :table_products p left join :table_products_images i on (p.products_id = i.products_id and i.default_flag = :default_flag) where p.products_id = :products_id');
    $Qproduct->bindTable(':table_products', TABLE_PRODUCTS);
    $Qproduct->bindTable(':table_products_images', TABLE_PRODUCTS_IMAGES);
    $Qproduct->bindInt(':default_flag', 1);
    $Qproduct->bindInt(':products_id', $product_id);
    $Qproduct->execute();
    
    if ( $Qproduct->value('image') == null ) {
      // check for parent image
      $Qimage = $lC_Database->query('select image from :table_products_images where products_id = :parent_id');
      $Qimage->bindTable(':table_products_images', TABLE_PRODUCTS_IMAGES);
      $Qimage->bindInt(':default_flag', 1);
      $Qimage->bindInt(':parent_id', $Qproduct->valueInt('parent_id'));
      $Qimage->execute();
      $image = $Qimage->value('image');
    } else {
      $image = $Qproduct->value('image');
    }

    if ( $Qproduct->valueInt('products_status') === 1 ) {
      if ( $this->exists($product_id) ) {
        $item_id = $this->getBasketID($product_id);

        if ( is_numeric($quantity) ) {
          $quantity = $this->getQuantity($item_id) + 1;
        }

        $this->_contents[$item_id]['quantity'] = $quantity;

        if ( $lC_Customer->isLoggedOn() ) {
          $Qupdate = $lC_Database->query('update :table_shopping_carts set quantity = :quantity where customers_id = :customers_id and item_id = :item_id');
          $Qupdate->bindTable(':table_shopping_carts', TABLE_SHOPPING_CARTS);
          $Qupdate->bindInt(':quantity', $quantity);
          $Qupdate->bindInt(':customers_id', $lC_Customer->getID());
          $Qupdate->bindInt(':item_id', $item_id);
          $Qupdate->execute();
        }
      } else {
        if ( !is_numeric($quantity) ) {
          $quantity = 1;
        } 
        
        $Qdescription = $lC_Database->query('select products_name, products_keyword, products_description, products_tags, products_url from :table_products_description where products_id = :products_id and language_id = :language_id');
        $Qdescription->bindTable(':table_products_description', TABLE_PRODUCTS_DESCRIPTION);
        $Qdescription->bindInt(':products_id', $product_id);
        $Qdescription->bindInt(':language_id', $lC_Language->getID());
        $Qdescription->execute();   
        
        $desc = $Qdescription->toArray();
        
        if ($Qproduct->valueInt('is_subproduct') > 0) {
          $Qmaster = $lC_Database->query('select products_name as parent_name, products_description as description, products_keyword as keyword, products_tags as tags, products_url as url from :table_products_description where products_id = :products_id and language_id = :language_id limit 1');
          $Qmaster->bindTable(':table_products_description', TABLE_PRODUCTS_DESCRIPTION);
          $Qmaster->bindInt(':products_id', $Qproduct->valueInt('parent_id'));
          $Qmaster->bindInt(':language_id', $lC_Language->getID());
          $Qmaster->execute();              
          
          $parent_name = $Qmaster->value('parent_name');
          
          if (empty($parent_name) === false) {
            $desc['products_name'] = $parent_name . ' - ' . $desc['products_name'];
          }            
          $desc['products_description'] = $Qmaster->value('description');
          $desc['products_keyword'] = $Qmaster->value('keyword');
          $desc['products_tags'] = $Qmaster->value('tags');
          $desc['products_url'] = $Qmaster->value('url');
        }

        // we get the product price from the product class - price already includes options, etc.
        if (!isset($lC_Product)) $lC_Product = new lC_Product($product_id);
        $price = $lC_Product->getPrice($product_id, $lC_Customer->getCustomerGroup(), $_POST);        
        
        if ( $lC_Customer->isLoggedOn() ) {
          $Qid = $lC_Database->query('select max(item_id) as item_id from :table_shopping_carts where customers_id = :customers_id');
          $Qid->bindTable(':table_shopping_carts', TABLE_SHOPPING_CARTS);
          $Qid->bindInt(':customers_id', $lC_Customer->getID());
          $Qid->execute();

          $item_id = $Qid->valueInt('item_id') + 1;
        } else {
          if ( empty($this->_contents) ) {
            $item_id = 1;
          } else {
            $item_id = max(array_keys($this->_contents)) + 1;
          }
        }

        $this->_contents[$item_id] = array('item_id' => $item_id,
                                           'id' => $product_id,
                                           'parent_id' => $Qproduct->valueInt('parent_id'),
                                           'name' => $desc['products_name'],
                                           'model' => $Qproduct->value('products_model'),
                                           'keyword' => $desc['products_keyword'],
                                           'tags' => $desc['products_tags'],
                                           'url' => $desc['products_url'],
                                           'description' => $desc['products_description'],
                                           'image' => $image,
                                           'price' => $price,
                                           'quantity' => $quantity,
                                           'weight' => $Qproduct->value('products_weight'),
                                           'tax_class_id' => $Qproduct->valueInt('products_tax_class_id'),
                                           'date_added' => lC_DateTime::getShort(lC_DateTime::getNow()),
                                           'weight_class_id' => $Qproduct->valueInt('products_weight_class'));                                           

        // simple options
        if (isset($_POST['simple_options']) && empty($_POST['simple_options']) === false) {
          
          foreach($_POST['simple_options'] as $options_id => $values_id) {
                     
            $QsimpleOptionsValues = $lC_Database->query('select price_modifier from :table_products_simple_options_values where options_id = :options_id and values_id = :values_id and customers_group_id = :customers_group_id');
            $QsimpleOptionsValues->bindTable(':table_products_simple_options_values', TABLE_PRODUCTS_SIMPLE_OPTIONS_VALUES);
            $QsimpleOptionsValues->bindInt(':options_id', $options_id);
            $QsimpleOptionsValues->bindInt(':values_id', $values_id);
            $QsimpleOptionsValues->bindInt(':customers_group_id', '1');
            $QsimpleOptionsValues->execute();  
            
            $Qvariants = $lC_Database->query('select pvg.title as group_title, pvg.module, pvv.title as value_title from :table_products_variants_groups pvg, :table_products_variants_values pvv where pvg.id = :options_id and pvv.id = :values_id and pvv.languages_id = :languages_id and pvv.products_variants_groups_id = pvg.id and pvg.languages_id = :languages_id limit 1');
            $Qvariants->bindTable(':table_products_variants_groups', TABLE_PRODUCTS_VARIANTS_GROUPS);
            $Qvariants->bindTable(':table_products_variants_values', TABLE_PRODUCTS_VARIANTS_VALUES);
            $Qvariants->bindInt(':options_id', $options_id);
            $Qvariants->bindInt(':values_id', $values_id);
            $Qvariants->bindInt(':languages_id', $lC_Language->getID());
            $Qvariants->bindInt(':languages_id', $lC_Language->getID());
            $Qvariants->execute();
            
            $this->_contents[$item_id]['simple_options'][] = array('value_id' => $values_id,
                                                                   'group_id' => $options_id,
                                                                   'group_title' => $Qvariants->value('group_title'),
                                                                   'value_title' => $Qvariants->value('value_title'),
                                                                   'price_modifier' => $QsimpleOptionsValues->valueDecimal('price_modifier'));
            $QsimpleOptionsValues->freeResult();                      
            $Qvariants->freeResult();                      
          }
        }                                             
                                           
        if ( $lC_Customer->isLoggedOn() ) {
          $Qnew = $lC_Database->query('insert into :table_shopping_carts (customers_id, item_id, products_id, quantity, meta_data, date_added) values (:customers_id, :item_id, :products_id, :quantity, :meta_data, :date_added)');
          $Qnew->bindTable(':table_shopping_carts', TABLE_SHOPPING_CARTS);
          $Qnew->bindInt(':customers_id', $lC_Customer->getID());
          $Qnew->bindInt(':item_id', $item_id);
          $Qnew->bindInt(':products_id', $product_id);
          $Qnew->bindInt(':quantity', $quantity);
          $Qnew->bindValue(':meta_data', serialize($this->_contents[$item_id]['simple_options']));
          $Qnew->bindRaw(':date_added', 'now()');
          $Qnew->execute();
        }

        if ( $Qproduct->valueInt('parent_id') > 0 ) {
          $Qvariant = $lC_Database->query('select pvg.id as group_id, pvg.title as group_title, pvg.module, pvv.id as value_id, pvv.title as value_title from :table_products_variants pv, :table_products_variants_values pvv, :table_products_variants_groups pvg where pv.products_id = :products_id and pv.products_variants_values_id = pvv.id and pvv.languages_id = :languages_id and pvv.products_variants_groups_id = pvg.id and pvg.languages_id = :languages_id');
          $Qvariant->bindTable(':table_products_variants', TABLE_PRODUCTS_VARIANTS);
          $Qvariant->bindTable(':table_products_variants_values', TABLE_PRODUCTS_VARIANTS_VALUES);
          $Qvariant->bindTable(':table_products_variants_groups', TABLE_PRODUCTS_VARIANTS_GROUPS);
          $Qvariant->bindInt(':products_id', $product_id);
          $Qvariant->bindInt(':languages_id', $lC_Language->getID());
          $Qvariant->bindInt(':languages_id', $lC_Language->getID());
          $Qvariant->execute();

          while ( $Qvariant->next() ) {
            $group_title = lC_Variants::getGroupTitle($Qvariant->value('module'), $Qvariant->toArray());
            $value_title = lC_Variants::getValueTitle($Qvariant->value('module'), $Qvariant->toArray());
            $has_custom_value = lC_Variants::hasCustomValue($Qvariant->value('module'));

            $this->_contents[$item_id]['variants'][] = array('group_id' => $Qvariant->valueInt('group_id'),
                                                             'value_id' => $Qvariant->valueInt('value_id'),
                                                             'group_title' => $group_title,
                                                             'value_title' => $value_title,
                                                             'has_custom_value' => $has_custom_value);

            if ( $lC_Customer->isLoggedOn() && ($has_custom_value === true) ) {
              $Qnew = $lC_Database->query('insert into :table_shopping_carts_custom_variants_values (shopping_carts_item_id, customers_id, products_id, products_variants_values_id, products_variants_values_text) values (:shopping_carts_item_id, :customers_id, :products_id, :products_variants_values_id, :products_variants_values_text)');
              $Qnew->bindTable(':table_shopping_carts_custom_variants_values', TABLE_SHOPPING_CARTS_CUSTOM_VARIANTS_VALUES);
              $Qnew->bindInt(':shopping_carts_item_id', $item_id);
              $Qnew->bindInt(':customers_id', $lC_Customer->getID());
              $Qnew->bindInt(':products_id', $product_id);
              $Qnew->bindInt(':products_variants_values_id', $Qvariant->valueInt('value_id'));
              $Qnew->bindValue(':products_variants_values_text', $value_title);
              $Qnew->execute();
            }
          }
        }

      }
      
      $this->_cleanUp();
      $this->_calculate();
    }
  }

  public function numberOfItems() {
    $total = 0;

    foreach ( $this->_contents as $product ) {
      $total += $product['quantity'];
    }

    return $total;
  }

  public function getBasketID($product_id) {
    foreach ( $this->_contents as $item_id => $product ) {
      if ( $product['id'] === $product_id ) {
        return $item_id;
      }
    }
  }

  public function getQuantity($item_id) {
    return ( isset($this->_contents[$item_id]) ) ? $this->_contents[$item_id]['quantity'] : 0;
  }

  public function exists($product_id) {
    foreach ( $this->_contents as $product ) {
      if ( $product['id'] === $product_id ) {
        if ( isset($product['variants']) ) {
          foreach ( $product['variants'] as $variant ) {
            if ( $variant['has_custom_value'] === true ) {
              return false;
            }
          }
        } else if ( isset($product['simple_options']) ) { 
          foreach ( $product['simple_options'] as $simple_options ) {              
            $group_id = $simple_options['group_id'];
            if(array_key_exists ( $group_id , $_POST['simple_options'] )) {
              if($simple_options['value_id'] != $_POST['simple_options'][$group_id]) {
                return false;
              }
            }
          }
        }

        return true;
      }
    }

    return false;
  }

  public function update($item_id, $quantity) {
    global $lC_Database, $lC_Customer, $lC_Services;

    if ( !is_numeric($quantity) ) {
      $quantity = $this->getQuantity($item_id);
    }

    $this->_contents[$item_id]['quantity'] = $quantity;

    if ( $lC_Customer->isLoggedOn() ) {
      $Qupdate = $lC_Database->query('update :table_shopping_carts set quantity = :quantity where customers_id = :customers_id and item_id = :item_id');
      $Qupdate->bindTable(':table_shopping_carts', TABLE_SHOPPING_CARTS);
      $Qupdate->bindInt(':quantity', $quantity);
      $Qupdate->bindInt(':customers_id', $lC_Customer->getID());
      $Qupdate->bindInt(':item_id', $item_id);
      $Qupdate->execute();
    }
    
      // get default tax_class_id
      $Qproduct = $lC_Database->query('select products_tax_class_id as tax_class_id from :table_products where products_id = :products_id and products_status = :products_status');
      $Qproduct->bindTable(':table_products', TABLE_PRODUCTS);
      $Qproduct->bindInt(':products_id', $this->_contents[$item_id]['id']);
      $Qproduct->bindInt(':products_status', 1);
      $Qproduct->execute();

      if ( $Qproduct->numberOfRows() === 1 ) {
        $this->_contents[$item_id]['tax_class_id'] = $Qproduct->valueInt('tax_class_id');
      }
    
    $simple_options = array();
    if (isset($this->_contents[$item_id]['simple_options'])) {
      foreach($this->_contents[$item_id]['simple_options'] as $key => $val) {
        $simple_options[$val['group_id']] = $val['value_id'];
      }     
    }

    // we get the product price from the product class - price already includes options, etc.
    if (!isset($lC_Product)) $lC_Product = new lC_Product($this->_contents[$item_id]['id']);
    $this->_contents[$item_id]['price_data'] = $lC_Product->getPriceInfo($this->_contents[$item_id]['id'], $lC_Customer->getCustomerGroup(), array('quantity' => $quantity, 'simple_options' => $simple_options));
    $this->_contents[$item_id]['price'] = $this->_contents[$item_id]['price_data']['price'];
        
    $this->_cleanUp();
    $this->_calculate(); 
    
    return $this->_contents[$item_id]['price_data'];   
  }

  public function remove($item_id) {
    global $lC_Database, $lC_Customer;

    unset($this->_contents[$item_id]);

    if ( $lC_Customer->isLoggedOn() ) {
      $Qdelete = $lC_Database->query('delete from :table_shopping_carts where customers_id = :customers_id and item_id = :item_id');
      $Qdelete->bindTable(':table_shopping_carts', TABLE_SHOPPING_CARTS);
      $Qdelete->bindInt(':customers_id', $lC_Customer->getID());
      $Qdelete->bindInt(':item_id', $item_id);
      $Qdelete->execute();

      $Qdelete = $lC_Database->query('delete from :table_shopping_carts_custom_variants_values where customers_id = :customers_id and shopping_carts_item_id = :shopping_carts_item_id');
      $Qdelete->bindTable(':table_shopping_carts_custom_variants_values', TABLE_SHOPPING_CARTS_CUSTOM_VARIANTS_VALUES);
      $Qdelete->bindInt(':customers_id', $lC_Customer->getID());
      $Qdelete->bindInt(':shopping_carts_item_id', $item_id);
      $Qdelete->execute();
    }

    $this->_calculate();
  }

  public function getProducts() {
    static $_is_sorted = false;

    if ( $_is_sorted === false ) {
      $_is_sorted = true;

      uasort($this->_contents, array('lC_ShoppingCart', '_uasortProductsByDateAdded'));
    }

    return $this->_contents;
  }

  public function getSubTotal() {
    return $this->_sub_total;
  }

  public function getTotal() {
    return $this->_total;
  }

  public function getWeight() {
    return $this->_weight;
  }    

  public function getShippingCost() {
    return $this->_shipping_method['cost'];
  }
    
  public function generateCartID($length = 5) {
    return lc_create_random_string($length, 'digits');
  }

  public function getCartID() {
    return $_SESSION['cartID'];
  }

  public function getContentType() {
    global $lC_Database;

    $this->_content_type = 'physical';

    if ( (DOWNLOAD_ENABLED == '1') && $this->hasContents() ) {
      foreach ( $this->_contents as $product_id => $data ) {
/* HPDL
        if (isset($data['attributes'])) {
          foreach ($data['attributes'] as $value) {
            $Qcheck = $lC_Database->query('select count(*) as total from :table_products_attributes pa, :table_products_attributes_download pad where pa.products_id = :products_id and pa.options_values_id = :options_values_id and pa.products_attributes_id = pad.products_attributes_id');
            $Qcheck->bindTable(':table_products_attributes', TABLE_PRODUCTS_ATTRIBUTES);
            $Qcheck->bindTable(':table_products_attributes_download', TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD);
            $Qcheck->bindInt(':products_id', $products_id);
            $Qcheck->bindInt(':options_values_id', $value['options_values_id']);
            $Qcheck->execute();

            if ($Qcheck->valueInt('total') > 0) {
              switch ($this->_content_type) {
                case 'physical':
                  $this->_content_type = 'mixed';

                  return $this->_content_type;
                  break;
                default:
                  $this->_content_type = 'virtual';
                  break;
              }
            } else {
              switch ($this->_content_type) {
                case 'virtual':
                  $this->_content_type = 'mixed';

                  return $this->_content_type;
                  break;
                default:
                  $this->_content_type = 'physical';
                  break;
              }
            }
          }
        } else {
*/
          switch ( $this->_content_type ) {
            case 'virtual':
              $this->_content_type = 'mixed';

              break 2;

            default:
              $this->_content_type = 'physical';

              break;
          }
//          }
      }
    }

    return $this->_content_type;
  }
  
  public function hasSimpleOptions($item_id) {
    return isset($this->_contents[$item_id]['simple_options']) && !empty($this->_contents[$item_id]['simple_options']);
  }

  public function getSimpleOptions($item_id) {
    if ( isset($this->_contents[$item_id]['simple_options']) && !empty($this->_contents[$item_id]['simple_options']) ) {
      return $this->_contents[$item_id]['simple_options'];
    }
  }  

  public function isVariant($item_id) {
    return isset($this->_contents[$item_id]['variants']) && !empty($this->_contents[$item_id]['variants']);
  }

  public function getVariant($item_id) {
    if ( isset($this->_contents[$item_id]['variants']) && !empty($this->_contents[$item_id]['variants']) ) {
      return $this->_contents[$item_id]['variants'];
    }
  }

  public function isInStock($item_id) {
    global $lC_Database;

    $Qstock = $lC_Database->query('select products_quantity from :table_products where products_id = :products_id');
    $Qstock->bindTable(':table_products', TABLE_PRODUCTS);
    $Qstock->bindInt(':products_id', $item_id);
    $Qstock->execute();

    if ( ($Qstock->valueInt('products_quantity') - $this->_contents[$item_id]['quantity']) > 0 ) {
      return true;
    } elseif ( $this->_products_in_stock === true ) {
      $this->_products_in_stock = false;
    }

    return false;
  }

  public function hasStock() {
    return $this->_products_in_stock;
  }

  public function hasShippingAddress() {
    return isset($this->_shipping_address['id']);
  }

  public function setShippingAddress($address_id) {
    global $lC_Database, $lC_Customer, $lC_Language;

    $previous_address = null;

    if ( isset($this->_shipping_address['id']) ) {
      $previous_address = $this->getShippingAddress();
    }

    $Qaddress = $lC_Database->query('select ab.entry_firstname, ab.entry_lastname, ab.entry_company, ab.entry_street_address, ab.entry_suburb, ab.entry_postcode, ab.entry_city, ab.entry_zone_id, ab.entry_telephone, z.zone_code, z.zone_name, ab.entry_country_id, c.countries_name, c.countries_iso_code_2, c.countries_iso_code_3, c.address_format, ab.entry_state from :table_address_book ab left join :table_zones z on (ab.entry_zone_id = z.zone_id) left join :table_countries c on (ab.entry_country_id = c.countries_id) where ab.customers_id = :customers_id and ab.address_book_id = :address_book_id');
    $Qaddress->bindTable(':table_address_book', TABLE_ADDRESS_BOOK);
    $Qaddress->bindTable(':table_zones', TABLE_ZONES);
    $Qaddress->bindTable(':table_countries', TABLE_COUNTRIES);
    $Qaddress->bindInt(':customers_id', $lC_Customer->getID());
    $Qaddress->bindInt(':address_book_id', $address_id);
    $Qaddress->execute();

    if ( $Qaddress->numberOfRows() === 1 ) {
      $this->_shipping_address = array('id' => $address_id,
                                       'firstname' => $Qaddress->valueProtected('entry_firstname'),
                                       'lastname' => $Qaddress->valueProtected('entry_lastname'),
                                       'company' => (!lc_empty($Qaddress->valueProtected('entry_company'))) ? $Qaddress->valueProtected('entry_company') : '',
                                       'street_address' => $Qaddress->valueProtected('entry_street_address'),
                                       'suburb' => $Qaddress->valueProtected('entry_suburb'),
                                       'city' => $Qaddress->valueProtected('entry_city'),
                                       'postcode' => $Qaddress->valueProtected('entry_postcode'),
                                       'state' => (!lc_empty($Qaddress->valueProtected('entry_state'))) ? $Qaddress->valueProtected('entry_state') : $Qaddress->valueProtected('zone_name'),
                                       'zone_id' => $Qaddress->valueInt('entry_zone_id'),
                                       'zone_code' => $Qaddress->value('zone_code'),
                                       'country_id' => $Qaddress->valueInt('entry_country_id'),
                                       'country_title' => $Qaddress->value('countries_name'),
                                       'country_iso_code_2' => $Qaddress->value('countries_iso_code_2'),
                                       'country_iso_code_3' => $Qaddress->value('countries_iso_code_3'),
                                       'format' => $Qaddress->value('address_format'),
                                       'telephone_number' => $Qaddress->value('entry_telephone'));

      if ( is_array($previous_address) && ( ($previous_address['id'] != $this->_shipping_address['id']) || ($previous_address['country_id'] != $this->_shipping_address['country_id']) || ($previous_address['zone_id'] != $this->_shipping_address['zone_id']) || ($previous_address['state'] != $this->_shipping_address['state']) || ($previous_address['postcode'] != $this->_shipping_address['postcode']) ) ) {
        $this->_calculate();
      }
    }
  }

  public function getShippingAddress($key = null) {
    if ( empty($key) ) {
      return $this->_shipping_address;
    }

    return $this->_shipping_address[$key];
  }

  public function resetShippingAddress() {
    global $lC_Customer;

    $this->_shipping_address = array('zone_id' => STORE_ZONE,
                                     'country_id' => STORE_COUNTRY);

    if ( $lC_Customer->isLoggedOn() && $lC_Customer->hasDefaultAddress() ) {
      $this->setShippingAddress($lC_Customer->getDefaultAddressID());
    }
  }

  public function setShippingMethod($shipping_array, $calculate_total = true) {  
    $this->_shipping_method = $shipping_array;

    if ( $calculate_total === true ) {
      $this->_calculate(false);
    }
  }

  public function getShippingMethod($key = null) {
    if ( empty($key) ) {
      return $this->_shipping_method;
    }

    return $this->_shipping_method[$key];
  }

  public function resetShippingMethod() {
    $this->_shipping_method = array();

    $this->_calculate();
  }

  public function hasShippingMethod() {
    return !empty($this->_shipping_method);
  }
    
  public function hasBillingAddress() {
    return isset($this->_billing_address['id']);
  }

  public function setBillingAddress($address_id) {
    global $lC_Database, $lC_Customer;

    $previous_address = false;

    if ( isset($this->_billing_address['id']) ) {
      $previous_address = $this->getBillingAddress();
    }

    $Qaddress = $lC_Database->query('select ab.entry_firstname, ab.entry_lastname, ab.entry_company, ab.entry_street_address, ab.entry_suburb, ab.entry_postcode, ab.entry_city, ab.entry_zone_id, ab.entry_telephone, z.zone_code, z.zone_name, ab.entry_country_id, c.countries_name, c.countries_iso_code_2, c.countries_iso_code_3, c.address_format, ab.entry_state from :table_address_book ab left join :table_zones z on (ab.entry_zone_id = z.zone_id) left join :table_countries c on (ab.entry_country_id = c.countries_id) where ab.customers_id = :customers_id and ab.address_book_id = :address_book_id');
    $Qaddress->bindTable(':table_address_book', TABLE_ADDRESS_BOOK);
    $Qaddress->bindTable(':table_zones', TABLE_ZONES);
    $Qaddress->bindTable(':table_countries', TABLE_COUNTRIES);
    $Qaddress->bindInt(':customers_id', $lC_Customer->getID());
    $Qaddress->bindInt(':address_book_id', $address_id);
    $Qaddress->execute();

    if ( $Qaddress->numberOfRows() === 1 ) {
      $this->_billing_address = array('id' => $address_id,
                                      'firstname' => $Qaddress->valueProtected('entry_firstname'),
                                      'lastname' => $Qaddress->valueProtected('entry_lastname'),
                                      'company' => $Qaddress->valueProtected('entry_company'),
                                      'street_address' => $Qaddress->valueProtected('entry_street_address'),
                                      'suburb' => $Qaddress->valueProtected('entry_suburb'),
                                      'city' => $Qaddress->valueProtected('entry_city'),
                                      'postcode' => $Qaddress->valueProtected('entry_postcode'),
                                      'state' => (!lc_empty($Qaddress->valueProtected('entry_state'))) ? $Qaddress->valueProtected('entry_state') : $Qaddress->valueProtected('zone_name'),
                                      'zone_id' => $Qaddress->valueInt('entry_zone_id'),
                                      'zone_code' => $Qaddress->value('zone_code'),
                                      'country_id' => $Qaddress->valueInt('entry_country_id'),
                                      'country_title' => $Qaddress->value('countries_name'),
                                      'country_iso_code_2' => $Qaddress->value('countries_iso_code_2'),
                                      'country_iso_code_3' => $Qaddress->value('countries_iso_code_3'),
                                      'format' => $Qaddress->value('address_format'),
                                      'telephone_number' => $Qaddress->value('entry_telephone'));
                                      
      if ( is_array($previous_address) && ( ($previous_address['id'] != $this->_billing_address['id']) || ($previous_address['country_id'] != $this->_billing_address['country_id']) || ($previous_address['zone_id'] != $this->_billing_address['zone_id']) || ($previous_address['state'] != $this->_billing_address['state']) || ($previous_address['postcode'] != $this->_billing_address['postcode']) ) ) {
        $this->_calculate(false);
      }
    }
  }

  public function getBillingAddress($key = null) {
    if ( empty($key) ) {
      return $this->_billing_address;
    }

    return $this->_billing_address[$key];
  }

  public function resetBillingAddress() {
    global $lC_Customer;

    $this->_billing_address = array('zone_id' => STORE_ZONE,
                                    'country_id' => STORE_COUNTRY);

    if ( $lC_Customer->isLoggedOn() && $lC_Customer->hasDefaultAddress() ) {
      $this->setBillingAddress($lC_Customer->getDefaultAddressID());
    }
  }

  public function setBillingMethod($billing_array) {
    $this->_billing_method = $billing_array;

    $this->_calculate(false);
  }

  public function getBillingMethod($key = null) {
    if ( empty($key) ) {
      return $this->_billing_method;
    }

    return $this->_billing_method[$key];
  }

  public function resetBillingMethod() {
    $this->_billing_method = array();

    $this->_calculate();
  }

  public function hasBillingMethod() {
    return !empty($this->_billing_method);
  }

  public function getTaxingAddress($id = null) {
    if ( $this->getContentType() == 'virtual' || (defined('SKIP_CHECKOUT_SHIPPING_PAGE') && SKIP_CHECKOUT_SHIPPING_PAGE == '1') ) {
      return $this->getBillingAddress($id);
    }

    return $this->getShippingAddress($id);
  }

  public function addTaxAmount($amount) {
    $this->_tax += $amount;
  }
  
  public function numberOfTaxGroups() {
    return sizeof($this->_tax_groups);
  }

  public function addTaxGroup($group, $amount) {
    if ( isset($this->_tax_groups[$group]) ) {
      $this->_tax_groups[$group] += $amount;
    } else {
      $this->_tax_groups[$group] = $amount;
    }
  }

  public function getTaxGroups() {
    return $this->_tax_groups;
  }

  public function addToTotal($amount) {
    $this->_total += $amount;
  }
  
  public function subtractFromTotal($amount) {
    $this->_total -= $amount;
  }  

  public function getOrderTotals() {
    return $this->_order_totals;
  }

  public function getShippingBoxesWeight() {
    return $this->_shipping_boxes_weight;
  }

  public function numberOfShippingBoxes() {
    return $this->_shipping_boxes;
  }

  private function _cleanUp() {
    global $lC_Database, $lC_Customer;

    foreach ( $this->_contents as $item_id => $data ) {
      
      if ( $data['quantity'] < 1 ) {

        unset($this->_contents[$item_id]);

        if ( $lC_Customer->isLoggedOn() ) {
          $Qdelete = $lC_Database->query('delete from :table_shopping_carts where customers_id = :customers_id and item_id = :item_id');
          $Qdelete->bindTable(':table_shopping_carts', TABLE_SHOPPING_CARTS);
          $Qdelete->bindInt(':customers_id', $lC_Customer->getID());
          $Qdelete->bindInt(':item_id', $item_id);
          $Qdelete->execute();

          $Qdelete = $lC_Database->query('delete from :table_shopping_carts_custom_variants_values where customers_id = :customers_id and shopping_carts_item_id = :shopping_carts_item_id');
          $Qdelete->bindTable(':table_shopping_carts_custom_variants_values', TABLE_SHOPPING_CARTS_CUSTOM_VARIANTS_VALUES);
          $Qdelete->bindInt(':customers_id', $lC_Customer->getID());
          $Qdelete->bindInt(':shopping_carts_item_id', $item_id);
          $Qdelete->execute();
        }
      }
    }
  }

  private function _calculate($set_shipping = true, $generate_id = true) {
    global $lC_Currencies, $lC_Tax, $lC_Weight, $lC_Shipping, $lC_Database, $lC_OrderTotal, $lC_Services, $lC_Coupons, $lC_Vqmod;

    $this->_sub_total = 0;
    $this->_total = 0;
    $this->_weight = 0;
    $this->_tax = 0;
    $this->_tax_groups = array();
    $this->_shipping_boxes_weight = 0;
    $this->_shipping_boxes = 0;
    $this->_shipping_quotes = array();
    $this->_order_totals = array();

    if ($generate_id) $_SESSION['cartID'] = $this->generateCartID();

    if ( $this->hasContents() ) {
      foreach ( $this->_contents as $data ) {
        $products_weight = $lC_Weight->convert($data['weight'], $data['weight_class_id'], SHIPPING_WEIGHT_UNIT);
        $this->_weight += $products_weight * $data['quantity'];

        $tax = $lC_Tax->getTaxRate($data['tax_class_id'], $this->getTaxingAddress('country_id'), $this->getTaxingAddress('zone_id'));
        $tax_description = $lC_Tax->getTaxRateDescription($data['tax_class_id'], $this->getTaxingAddress('country_id'), $this->getTaxingAddress('zone_id'));

        $shown_price = $lC_Currencies->addTaxRateToPrice($data['price'], $tax, $data['quantity']);

        $this->_sub_total += $shown_price;
        $this->_total += $shown_price;

        if ( DISPLAY_PRICE_WITH_TAX == '1' ) {
          $tax_amount = $shown_price - ($shown_price / (($tax < 10) ? '1.0' . str_replace('.', '', $tax) : '1.' . str_replace('.', '', $tax)));
        } else {
          $tax_amount = ($tax / 100) * $shown_price;

          $this->_total += $tax_amount;
        }

        $this->_tax += $tax_amount;

        if ( isset($this->_tax_groups[$tax_description]) ) {
          $this->_tax_groups[$tax_description] += $tax_amount;
        } else {
          $this->_tax_groups[$tax_description] = $tax_amount;
        }
      }
           
      // coupons
      if (defined('MODULE_SERVICES_INSTALLED') && in_array('coupons', explode(';', MODULE_SERVICES_INSTALLED)) && isset($lC_Coupons)) {
        if ($lC_Coupons->hasContents()) {
          $discount = $lC_Coupons->getTotalDiscount();
          $this->_total -= $discount;
        }
      }
      
      $this->_shipping_boxes_weight = $this->_weight;
      $this->_shipping_boxes = 1;

      if ( SHIPPING_BOX_WEIGHT >= ($this->_shipping_boxes_weight * SHIPPING_BOX_PADDING/100) ) {
        $this->_shipping_boxes_weight = $this->_shipping_boxes_weight + SHIPPING_BOX_WEIGHT;
      } else {
        $this->_shipping_boxes_weight = $this->_shipping_boxes_weight + ($this->_shipping_boxes_weight * SHIPPING_BOX_PADDING/100);
      }

      if ( $this->_shipping_boxes_weight > SHIPPING_MAX_WEIGHT ) { // Split into many boxes
        $this->_shipping_boxes = ceil($this->_shipping_boxes_weight / SHIPPING_MAX_WEIGHT);
        $this->_shipping_boxes_weight = $this->_shipping_boxes_weight / $this->_shipping_boxes;
      }

      if ( $set_shipping === true ) {
        if ( !class_exists('lC_Shipping') ) {
          include($lC_Vqmod->modCheck('includes/classes/shipping.php'));
        }

        if ( !$this->hasShippingMethod() || ($this->getShippingMethod('is_cheapest') === true) ) {
          $lC_Shipping = new lC_Shipping();
          $this->setShippingMethod($lC_Shipping->getCheapestQuote(), false);
        } else {
          $lC_Shipping = new lC_Shipping($this->getShippingMethod('id'));
          $this->setShippingMethod($lC_Shipping->getQuote(), false);
        } 
      }

      if ( !class_exists('lC_OrderTotal') ) {
        include($lC_Vqmod->modCheck('includes/classes/order_total.php'));
      }    

      $lC_OrderTotal = new lC_OrderTotal();
      $this->_order_totals = $lC_OrderTotal->getResult();
      
       // coupons
      if (defined('MODULE_SERVICES_INSTALLED') && in_array('coupons', explode(';', MODULE_SERVICES_INSTALLED)) && isset($lC_Coupons)) {
        $lC_Coupons->displayCouponInOrderTotal();
      }
    }     
  }

  static private function _uasortProductsByDateAdded($a, $b) {
    if ($a['date_added'] == $b['date_added']) {
      return strnatcasecmp($a['name'], $b['name']);
    }

    return ($a['date_added'] > $b['date_added']) ? -1 : 1;
  }
}
?>