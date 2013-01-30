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
require_once('includes/applications/customers/classes/customers.php'); 

class lC_Customers_Admin_rpc {
 /*
  * Returns the customers datatable data for listings
  *
  * @access public
  * @return json
  */
  public static function getAll() {  
    $result = lC_Customers_Admin::getAll();
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  }
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
  public static function saveCustomer() {   
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
  public static function updateCustomer() { 
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
  public static function deleteCustomer() {
    $result = array();
    $deleted = lC_Customers_Admin::delete((int)$_GET['cid']);
    if ($deleted) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
 /*
  * Batch delete customer records
  *
  * @param array $_GET['batch'] The customer id's to delete
  * @access public
  * @return json
  */
  public static function batchDeleteEntries() {
    $result = array();
    $deleted = lC_Customers_Admin::batchDelete($_GET['batch']);
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
}
?>