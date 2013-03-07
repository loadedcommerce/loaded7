<?php
/*
  $Id: rpc.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html

  @function The lC_Orders_Admin_rpc class is for AJAX remote program control
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
}
?>