<?php
/*
  $Id: success.php v1.0 2013-01-01 maestro $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
 
  @function The lC_Success class returns data for display on the checkout success page
*/
class lC_Success {
 /*
  * Returns the current order_id
  *
  * @param string $cID customers_id 
  * @access public
  * @return array
  */
  public static function getOrderID($cID) {
    global $lC_Database;

    $Qorder = $lC_Database->query('select orders_id from :table_orders where customers_id = :customers_id order by date_purchased desc limit 1');
    $Qorder->bindTable(':table_orders', TABLE_ORDERS);
    $Qorder->bindInt(':customers_id', $cID);
    $Qorder->execute();
    
    $oID = $Qorder->valueInt('orders_id');

    $Qorder->freeResult();
    
    return $oID;
  }
 /*
  * Returns result of variants check
  *
  * @param string $pID products_id 
  * @access public
  * @return array
  */
  public static function isVariant($pID) {
    global $lC_Database;

    $Qvariant = $lC_Database->query('select products_id from :table_products_variants where products_id = :products_id limit 1');
    $Qvariant->bindTable(':table_products_variants', TABLE_PRODUCTS_VARIANTS);
    $Qvariant->bindInt(':products_id', $pID);
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
  * @param string $pID products_id 
  * @access public
  * @return array
  */
  public static function getVariants($pID) {
    global $lC_Database, $lC_Language;
    
    $Qvariant = $lC_Database->query('select pvg.id as group_id, pvg.title as group_title, pvg.module, pvv.id as value_id, pvv.title as value_title from :table_products_variants pv, :table_products_variants_values pvv, :table_products_variants_groups pvg where pv.products_id = :products_id and pv.products_variants_values_id = pvv.id and pvv.languages_id = :languages_id and pvv.products_variants_groups_id = pvg.id and pvg.languages_id = :languages_id');
    $Qvariant->bindTable(':table_products_variants', TABLE_PRODUCTS_VARIANTS);
    $Qvariant->bindTable(':table_products_variants_values', TABLE_PRODUCTS_VARIANTS_VALUES);
    $Qvariant->bindTable(':table_products_variants_groups', TABLE_PRODUCTS_VARIANTS_GROUPS);
    $Qvariant->bindInt(':products_id', $pID);
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
  * @param string $cID customers_id 
  * @access public
  * @return array
  */
  public static function globalNotifications($cID) {
    global $lC_Database;

    $Qglobal = $lC_Database->query('select global_product_notifications from :table_customers where customers_id = :customers_id');
    $Qglobal->bindTable(':table_customers', TABLE_CUSTOMERS);
    $Qglobal->bindInt(':customers_id', $cID);
    $Qglobal->execute();
    
    if ($Qglobal->valueInt('global_product_notifications') !== 1) {
      $Qproducts = $lC_Database->query('select products_id, products_name from :table_orders_products where orders_id = :orders_id order by products_name');
      $Qproducts->bindTable(':table_orders_products', TABLE_ORDERS_PRODUCTS);
      $Qproducts->bindInt(':orders_id', self::getOrderID($cID));
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
  * @param string $oID orders_id 
  * @access public
  * @return array
  */
  public static function getOrderTotals($oID) {
    global $lC_Database;
  
    $QorderTotals = $lC_Database->query('select * from :table_orders_total where orders_id = :orders_id order by sort_order asc');
    $QorderTotals->bindTable(':table_orders_total', TABLE_ORDERS_TOTAL);
    $QorderTotals->bindInt(':orders_id', $oID);
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
  * @param string $oID orders_id 
  * @access public
  * @return array
  */
  public static function getOrderProducts($oID) {
    global $lC_Database;
  
    $QorderProducts = $lC_Database->query('select * from :table_orders_products where orders_id = :orders_id order by orders_products_id asc');
    $QorderProducts->bindTable(':table_orders_products', TABLE_ORDERS_PRODUCTS);
    $QorderProducts->bindInt(':orders_id', $oID);
    $QorderProducts->execute();
    $orders_products_array = array();
    while ($QorderProducts->next()) {
      $orders_products_array[] = array('id' => $QorderProducts->valueInt('products_id'),
                                       'quantity' => $QorderProducts->valueInt('products_quantity'),
                                       'name' => $QorderProducts->value('products_name'),
                                       'model' => $QorderProducts->value('products_model'),
                                       'price' => $QorderProducts->value('products_price'));
    }
    
    return $orders_products_array;
    
    $QorderProducts->freeResult();    
  }
 /*
  * Returns the payment method
  *
  * @param string $oID orders_id 
  * @access public
  * @return array
  */
  public static function getPaymentMethod($oID) {
    global $lC_Database;

    $QorderPayment = $lC_Database->query('select payment_method from :table_orders where orders_id = :orders_id');
    $QorderPayment->bindTable(':table_orders', TABLE_ORDERS);
    $QorderPayment->bindInt(':orders_id', $oID);
    $QorderPayment->execute();
    
    $payment_method = $QorderPayment->value('payment_method');

    $QorderPayment->freeResult();
    
    return $payment_method;
  }
 /*
  * Returns the order comments
  *
  * @param string $oID orders_id 
  * @access public
  * @return array
  */
  public static function getOrderComments($oID) {
    global $lC_Database;

    $QorderComments = $lC_Database->query('select comments from :table_orders_status_history where orders_id = :orders_id and customer_notified = 0');
    $QorderComments->bindTable(':table_orders_status_history', TABLE_ORDERS_STATUS_HISTORY);
    $QorderComments->bindInt(':orders_id', $oID);
    $QorderComments->execute();
    
    $order_comments = $QorderComments->value('comments');

    $QorderComments->freeResult();
    
    return $order_comments;
  }
}
?>