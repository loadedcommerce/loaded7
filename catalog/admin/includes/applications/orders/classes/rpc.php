<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: rpc.php v1.0 2013-08-08 datazen $
*/
global $lC_Vqmod;

require_once($lC_Vqmod->modCheck('includes/applications/orders/classes/orders.php')); 

class lC_Orders_Admin_rpc {
 /*
  * Returns the orders datatable data for listings
  *
  * @access public
  * @return json
  */
  public static function getAll() {
    $result = lC_Orders_Admin::getAll();
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  }
 /*
  * Delete the order record 
  *
  * @param integer $_GET['oid'] The order id to delete
  * @access public
  * @return json
  */
  public static function deleteOrder() {
    $result = array();
    $restock = (isset($_GET['restock']) && $_GET['restock'] == 'on') ? true : false;
    $deleted = lC_Orders_Admin::delete($_GET['oid'], $restock);
    if ($deleted) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
 /*
  * Batch delete order records
  *
  * @param array $_GET['batch'] The order id's to delete
  * @access public
  * @return json
  */
  public static function batchDeleteEntries() {
    $result = array();
    $restock = (isset($_GET['restock']) && $_GET['restock'] == 'on') ? true : false;      
    $deleted = lC_Orders_Admin::batchDelete($_GET['batch'], $restock);
    if ($deleted) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
 /*
  * Return the order information used on the batch delete dialog form
  *
  * @param array $_GET['batch'] The order id's to delete
  * @access public
  * @return json
  */
  public static function getBatchInfo() {
    $result = lC_Orders_Admin::batchInfo($_GET['batch']);
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  } 
 /*
  * Return the order information
  *
  * @param integer $_GET['oid'] The order id
  * @access public
  * @return json
  */
  public static function getOrderInfo() {
    $result = array();
    $result = lC_Orders_Admin::getInfo($_GET['oid']);
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  } 
 /*
  * Update the order status
  *
  * @param integer $_GET['oid'] The order id to update
  * @param array $_GET The order information
  * @access public
  * @return json
  */
  public static function updateOrderStatus() {
    $result = lC_Orders_Admin::updateStatus($_GET['oid'], $_GET);
    if ($result !== false) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }
    echo json_encode($result);
  } 
 /*
  * Execute a post transaction
  *
  * @param integer $_GET['oid'] The order id
  * @param array $_GET['transaction'] The payment module call function
  * @access public
  * @return json
  */
  public static function executePostTransaction() {
    $result = lC_Orders_Admin::doPostTransaction($_GET['oid'], $_GET['transaction']);
    if ($result !== false) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }
    echo json_encode($result);
  }
 /*
  * Returns a single order product info
  *
  * @access public
  * @return json
  */
  public static function getProduct() {
    $result = lC_Orders_Admin::getProduct($_GET['oid'], $_GET['pid']);
    if ($result !== false) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }
    
    echo json_encode($result);
  }
  public static function getProductData() {
    $result = lC_Orders_Admin::getProductData($_GET['pid']);
    if ($result !== false) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }
    echo json_encode($result);
  }
  public static function updateOrderProductData() {
    $result = lC_Orders_Admin::updateOrderProductData();
    if ($result !== false) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }
    echo json_encode($result);
  }
  public static function removeOrderTotal() {
    $result = lC_Orders_Admin::removeOrderTotal();
    if ($result !== false) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }
    echo json_encode($result);
  }
  public static function getOrdersTotalData() {
    $result = lC_Orders_Admin::OrdersTotalData();
    if ($result !== false) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }
    echo json_encode($result);
  }
  public static function saveOrderTotal() {
    $result = lC_Orders_Admin::saveOrderTotal();
    if ($result !== false) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }
    echo json_encode($result);
  }
}
?>