<?php
/**
  @package    catalog::templates
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: success.php v1.0 2013-08-08 datazen $
*/
class lC_Success { 
 /*
  * Returns the current order_id
  *
  * @param int $_cID customers_id 
  * @access public
  * @return array
  */
  public static function getOrderID($_cID) {
    global $lC_Database;

    $Qorder = $lC_Database->query('select orders_id from :table_orders where customers_id = :customers_id order by date_purchased desc limit 1');
    $Qorder->bindTable(':table_orders', TABLE_ORDERS);
    $Qorder->bindInt(':customers_id', $_cID);
    $Qorder->execute();
    
    $oID = $Qorder->valueInt('orders_id');

    $Qorder->freeResult();
    
    return $oID;
  }
 /*
  * Returns the customers first name for use in the address arrays
  *
  * @param int $_cID customers_id 
  * @access public
  * @return array
  */
  public static function getFirstName($_cID) {
    global $lC_Database;
    
    $Qfirst = $lC_Database->query('select customers_firstname from :table_customers where customers_id = :customers_id');
    $Qfirst->bindTable(':table_customers', TABLE_CUSTOMERS);
    $Qfirst->bindInt(':customers_id', $_cID);
    $Qfirst->execute();
    
    $first = $Qfirst->value('customers_firstname');

    $Qfirst->freeResult();
    
    return $first;
  }
 /*
  * Returns the customers last name for use in the address arrays
  *
  * @param int $_cID customers_id 
  * @access public
  * @return array
  */
  public static function getLastName($_cID) {
    global $lC_Database;

    $Qlast = $lC_Database->query('select customers_lastname from :table_customers where customers_id = :customers_id');
    $Qlast->bindTable(':table_customers', TABLE_CUSTOMERS);
    $Qlast->bindInt(':customers_id', $_cID);
    $Qlast->execute();
    
    $last = $Qlast->value('customers_lastname');

    $Qlast->freeResult();
    
    return $last;
  }
 /*
  * Returns result of variants check
  *
  * @param int $_pID products_id 
  * @access public
  * @return array
  */
  public static function isVariant($_pID) {
    global $lC_Database;

    $Qvariant = $lC_Database->query('select products_id from :table_products_variants where products_id = :products_id limit 1');
    $Qvariant->bindTable(':table_products_variants', TABLE_PRODUCTS_VARIANTS);
    $Qvariant->bindInt(':products_id', $_pID);
    $Qvariant->execute();
    
    if ($Qvariant->valueInt('products_id')) {
      $vID = true;
    } else {
      $vID = false;
    }

    $Qvariant->freeResult();
    
    return $vID;
  }
 /*
  * Returns variants data
  *
  * @param int $pID products_id 
  * @access public
  * @return array
  */
  public static function getVariants($_pID) {
    global $lC_Database, $lC_Language;
    
    $Qvariant = $lC_Database->query('select pvg.id as group_id, pvg.title as group_title, pvg.module, pvv.id as value_id, pvv.title as value_title from :table_products_variants pv, :table_products_variants_values pvv, :table_products_variants_groups pvg where pv.products_id = :products_id and pv.products_variants_values_id = pvv.id and pvv.languages_id = :languages_id and pvv.products_variants_groups_id = pvg.id and pvg.languages_id = :languages_id');
    $Qvariant->bindTable(':table_products_variants', TABLE_PRODUCTS_VARIANTS);
    $Qvariant->bindTable(':table_products_variants_values', TABLE_PRODUCTS_VARIANTS_VALUES);
    $Qvariant->bindTable(':table_products_variants_groups', TABLE_PRODUCTS_VARIANTS_GROUPS);
    $Qvariant->bindInt(':products_id', $_pID);
    $Qvariant->bindInt(':languages_id', $lC_Language->getID());
    $Qvariant->bindInt(':languages_id', $lC_Language->getID());
    $Qvariant->execute();
    
    if ( $Qvariant->numberOfRows() > 0 ) {
      while ( $Qvariant->next() ) {
        $variants_array[] = array('group_id' => $Qvariant->valueInt('group_id'),
                                  'value_id' => $Qvariant->valueInt('value_id'),
                                  'group_title' => $Qvariant->value('group_title'),
                                  'value_title' => $Qvariant->value('value_title'));
      }
    }
    
    $vArray = $variants_array;

    $Qvariant->freeResult();
    
    return $vArray;
  }
 /*
  * Returns the customers global notifications status
  *
  * @param int $_cID customers_id 
  * @access public
  * @return array
  */
  public static function globalNotifications($_cID) {
    global $lC_Database;
    
    $Qglobal = $lC_Database->query('select global_product_notifications from :table_customers where customers_id = :customers_id');
    $Qglobal->bindTable(':table_customers', TABLE_CUSTOMERS);
    $Qglobal->bindInt(':customers_id', $_cID);
    $Qglobal->execute();
    
    if ($Qglobal->valueInt('global_product_notifications') !== 1) {
      $Qproducts = $lC_Database->query('select products_id, products_name from :table_orders_products where orders_id = :orders_id order by products_name');
      $Qproducts->bindTable(':table_orders_products', TABLE_ORDERS_PRODUCTS);
      $Qproducts->bindInt(':orders_id', self::getOrderID($_cID));
      $Qproducts->execute();
      $products_array = array();
      while ($Qproducts->next()) {
        $products_array[] = array('id' => $Qproducts->valueInt('products_id'),
                                  'text' => $Qproducts->value('products_name'));
      }
    }
    
    return $products_array;
    
    $Qglobal->freeResult();
    $Qproducts->freeResult();
  }
 /*
  * Returns the order totals array
  *
  * @param int $_oID orders_id 
  * @access public
  * @return array
  */
  public static function getOrderTotals($_oID) {
    global $lC_Database;
  
    $QorderTotals = $lC_Database->query('select * from :table_orders_total where orders_id = :orders_id order by sort_order asc');
    $QorderTotals->bindTable(':table_orders_total', TABLE_ORDERS_TOTAL);
    $QorderTotals->bindInt(':orders_id', $_oID);
    $QorderTotals->execute();
    
    $orders_total_array = array();
    while ($QorderTotals->next()) {
      $orders_total_array[] = array('title' => $QorderTotals->value('title'),
                                    'text' => $QorderTotals->value('text'),
                                    'value' => $QorderTotals->value('value'),
                                    'class' => $QorderTotals->value('class'),
                                    'sort_order' => $QorderTotals->valueInt('sort_order'));
    }
    
    return $orders_total_array;
    
    $QorderTotals->freeResult();    
  }
 /*
  * Returns the order products array
  *
  * @param int $_oID orders_id 
  * @access public
  * @return array
  */
  public static function getOrderProducts($_oID) {
    global $lC_Database;
  
    $QorderProducts = $lC_Database->query('select * from :table_orders_products where orders_id = :orders_id order by orders_products_id asc');
    $QorderProducts->bindTable(':table_orders_products', TABLE_ORDERS_PRODUCTS);
    $QorderProducts->bindInt(':orders_id', $_oID);
    $QorderProducts->execute();
    $orders_products_array = array();
    while ($QorderProducts->next()) {
      $orders_products_array[] = array('id' => $QorderProducts->valueInt('products_id'),
                                       'quantity' => $QorderProducts->valueInt('products_quantity'),
                                       'name' => $QorderProducts->value('products_name'),
                                       'model' => $QorderProducts->value('products_model'),
                                       'price' => $QorderProducts->value('products_price'),
                                       'options' => unserialize($QorderProducts->value('products_simple_options_meta_data')));
    }
    
    return $orders_products_array;
    
    $QorderProducts->freeResult();    
  }
 /*
  * Returns the payment method
  *
  * @param int $_oID orders_id 
  * @access public
  * @return array
  */
  public static function getPaymentMethod($_oID) {
    global $lC_Database;

    $QorderPayment = $lC_Database->query('select payment_method from :table_orders where orders_id = :orders_id');
    $QorderPayment->bindTable(':table_orders', TABLE_ORDERS);
    $QorderPayment->bindInt(':orders_id', $_oID);
    $QorderPayment->execute();
    
    $payment_method = $QorderPayment->value('payment_method');

    $QorderPayment->freeResult();
    
    return $payment_method;
  }
 /*
  * Returns the shipping address
  *
  * @param int $_oID orders_id 
  * @access public
  * @return array
  */
  public static function getShippingAddress($_oID, $_cID) {
    global $lC_Database;

    $QorderShipping = $lC_Database->query('select delivery_name, delivery_company, delivery_street_address, delivery_suburb, delivery_city, delivery_postcode, delivery_state_code, delivery_country, delivery_address_format from :table_orders where orders_id = :orders_id');
    $QorderShipping->bindTable(':table_orders', TABLE_ORDERS);
    $QorderShipping->bindInt(':orders_id', $_oID);
    $QorderShipping->execute();
    
    $shipping_data = array('firstname' => self::getFirstName($_cID),
                           'lastname' => self::getLastName($_cID),
                           'company' => $QorderShipping->value('delivery_company'),
                           'street_address' => $QorderShipping->value('delivery_street_address'),
                           'suburb' => $QorderShipping->value('delivery_suburb'),
                           'city' => $QorderShipping->value('delivery_city'),
                           'postcode' => $QorderShipping->value('delivery_postcode'),
                           'zone_code' => $QorderShipping->value('delivery_state_code'),
                           'country_title' => $QorderShipping->value('delivery_country'),
                           'format' => $QorderShipping->value('delivery_address_format'));

    $QorderShipping->freeResult();
    
    return $shipping_data;
  }
 /*
  * Returns the billing address
  *
  * @param int $_oID orders_id 
  * @access public
  * @return array
  */
  public static function getBillingAddress($_oID, $_cID) {
    global $lC_Database;

    $QorderBilling = $lC_Database->query('select billing_name, billing_company, billing_street_address, billing_suburb, billing_city, billing_postcode, billing_state_code, billing_country, billing_address_format from :table_orders where orders_id = :orders_id');
    $QorderBilling->bindTable(':table_orders', TABLE_ORDERS);
    $QorderBilling->bindInt(':orders_id', $_oID);
    $QorderBilling->execute();
    
    $billing_data = array('firstname' => self::getFirstName($_cID),
                          'lastname' => self::getLastName($_cID),
                          'company' => $QorderBilling->value('billing_company'),
                          'street_address' => $QorderBilling->value('billing_street_address'),
                          'suburb' => $QorderBilling->value('billing_suburb'),
                          'city' => $QorderBilling->value('billing_city'),
                          'postcode' => $QorderBilling->value('billing_postcode'),
                          'zone_code' => $QorderBilling->value('billing_state_code'),
                          'country_title' => $QorderBilling->value('billing_country'),
                          'format' => $QorderBilling->value('billing_address_format'));

    $QorderBilling->freeResult();
    
    return $billing_data;
  }
 /*
  * Returns the order comments
  *
  * @param int $_oID orders_id 
  * @access public
  * @return array
  */
  public static function getOrderComments($_oID) {
    global $lC_Database;

    $QorderComments = $lC_Database->query('select comments from :table_orders_status_history where orders_id = :orders_id and comments != ""');
    $QorderComments->bindTable(':table_orders_status_history', TABLE_ORDERS_STATUS_HISTORY);
    $QorderComments->bindInt(':orders_id', $_oID);
    $QorderComments->execute();
    
    $order_comments = $QorderComments->value('comments');

    $QorderComments->freeResult();
    
    return $order_comments;
  }
}
?>