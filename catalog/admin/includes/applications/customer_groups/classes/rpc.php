<?php
/*
  $Id: rpc.php v1.0 2011-11-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html

  @function The lC_Customer_groups_Admin_rpc class is for AJAX remote program control
*/
require('includes/applications/customer_groups/classes/customer_groups.php');

class lC_Customer_groups_Admin_rpc {
 /*
  * Returns the customer groups datatable data for listings
  *
  * @access public
  * @return json
  */
  public static function getAll() {
    $result = lC_Customer_groups_Admin::getAll();
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  }
 /*
  * Return the data used on the dialog forms
  *
  * @param integer $_GET['cgid'] The customer group id
  * @param boolean $edit True = called from edit dialog else called from delete dialog
  * @access public
  * @return json
  */
  public static function getFormData() {
    $edit = (isset($_GET['edit']) && $_GET['edit'] == 'true') ? true : false;
    $result = lC_Customer_groups_Admin::getFormData($_GET['cgid'], $edit);
    if (!isset($result['rpcStatus'])) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
 /*
  * Save the customer group information
  *
  * @param integer $_GET['cgid'] The customer group id used on update, null on insert
  * @param array $_GET An array containing the customer group information
  * @param boolean $default True = set the customer group to be default
  * @access public
  * @return json
  */
  public static function saveGroup() { 
    $result = array();
    $default = (isset($_GET['default']) && $_GET['default'] == 'on') ? true : false;
    $saved = lC_Customer_groups_Admin::save($_GET['cgid'], $_GET, $default);
    if ($saved) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
 /*
  * Delete the customer groups record
  *
  * @param integer $_GET['cgid'] The customer group id to delete
  * @access public
  * @return json
  */    
  public static function deleteGroup() {
    $result = array();
    $deleted = lC_Customer_groups_Admin::delete($_GET['cgid']);
    if ($deleted) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
 /*
  * Batch delete customer group records
  *
  * @param array $_GET['batch'] An array of customer group id's
  * @access public
  * @return json
  */ 
  public static function batchDelete() {
    $result = lC_Customer_groups_Admin::batchDelete($_GET['batch']);
    if (isset($result['namesString']) && $result['namesString'] != null) {
    } else {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }    
}
?>