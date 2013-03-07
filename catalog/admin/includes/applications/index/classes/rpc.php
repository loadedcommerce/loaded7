<?php
/*
  $Id: rpc.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html

  @function The lC_Customers_Admin_rpc class is for AJAX remote program control
*/
global $lC_Vqmod;

require_once($lC_Vqmod->modCheck('includes/applications/customers/classes/customers.php')); 
require_once($lC_Vqmod->modCheck('includes/applications/orders/classes/orders.php')); 
require_once($lC_Vqmod->modCheck('includes/applications/products/classes/products.php')); 
require_once($lC_Vqmod->modCheck('includes/classes/category_tree.php'));
require_once($lC_Vqmod->modCheck('includes/applications/administrators_log/classes/administrators_log.php'));

class lC_Index_Admin_rpc {

 /*
  * Return the data used on the dialog forms
  *
  * @param integer $_GET['cid'] The customer id
  * @access public
  * @return json
  */
  public static function getCustomerFormData() {  
    $result = lC_Customers_Admin::formData($_GET['cid']);
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  } 
 /*
  * Save the customer information (insert)
  *
  * @param array $_GET An array containing the customer information
  * @access public
  * @return json
  */
  public static function saveCustomerEntry() {   
    $result = lC_Customers_Admin::save(null, $_GET);
    if (!isset($result['rpcStatus'])) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
 /*
  * Save the customer information (update)
  *
  * @param integer $_GET['cid'] The customer id used on update
  * @param array $_GET An array containing the customer information
  * @access public
  * @return json
  */
  public static function updateEntry() { 
    $result = lC_Customers_Admin::save($_GET['cid'], $_GET);
    if (!isset($result['rpcStatus'])) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
 /*
  * Delete the customer record
  *
  * @param integer $_GET['cid'] The customer id to delete
  * @access public
  * @return json
  */
  public static function deleteEntry() {
    $result = array();
    $deleted = lC_Customers_Admin::delete((int)$_GET['cid']);
    if ($deleted) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
 
 /*
  * Save the customer address book information 
  *
  * @param integer $_GET['abid'] The customer address book id used on update, null on insert
  * @param array $_GET An array containing the customer address book information
  * @access public
  * @return json
  */  
  public static function saveAddressEntry() {  
    $result = lC_Customers_Admin::saveAddress((int)$_GET['abid'], $_GET);
    if (!isset($result['rpcStatus'])) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
 /*
  * Delete the customer address book record
  *
  * @param integer $_GET['abid'] The customer address book id
  * @param integer $_GET['cid'] The customer id
  * @access public
  * @return json
  */
  public static function deleteAddressEntry() {
    $result = lC_Customers_Admin::deleteAddress((int)$_GET['abid'], (int)$_GET['cid']);
    if (!isset($result['rpcStatus'])) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
 /*
  * Return the zones for a country
  *
  * @param integer $_GET['country_id'] The country id
  * @access public
  * @return json
  */
  public static function getStateZones() {  
    $result = lC_Customers_Admin::getZones((int)$_GET['country_id']);
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  }
 /*
  * Return the customer address book information
  *
  * @param integer $_GET['cid'] The customer id    
  * @param integer $_GET['abid'] The customer address book id
  * @access public
  * @return json
  */
  public static function getAddressEntry() {  
    $result = lC_Customers_Admin::getAddressBookData((int)$_GET['cid'], (int)$_GET['abid']);
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
  * Returns the administrator log entry information
  *
  * @param integer $_GET['lid'] The administrator log entry id
  * @access public
  * @return json
  */
  public static function getAdminLogData() {
    $result = lC_Administrators_log_Admin::getAdminLogData($_GET['lid']);
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  } 
 /*
  * Copy a product
  *
  * @param integer $_GET['pid'] The product id 
  * @param integer $_GET['new_category_id'] The new category id 
  * @param string $_GET['copy_as'] The product id 
  * @access public
  * @return json
  */
  public static function copyProduct() {
    $result = array();
    $copied = lC_Products_Admin::copy($_GET['pid'], $_GET['new_category_id'], $_GET['copy_as']);
    if ($copied) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }  
 /*
  * Delete a product
  *
  * @param integer $_GET['pid'] The product id to delete
  * @param array $_GET['categories'] The category id's to unlink from
  * @access public
  * @return json
  */
  public static function deleteProduct() {
    $result = array();
    $deleted = lC_Products_Admin::delete($_GET['pid'], $_GET['categories']);
    if ($deleted) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
        /*
  * Returns the data used on the dialog forma
  *
  * @param string $_GET['cid'] The category id 
  * @access public
  * @return json
  */
  public static function getProductFormData() {
    $result = lC_Products_Admin::getProductFormData($_GET['pid']);
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  } 
}
?>