<?php
/*
  $Id: rpc.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html

  @function The lC_Manufacturers_Admin_rpc class is for AJAX remote program control
*/
require('includes/applications/manufacturers/classes/manufacturers.php');

class lC_Manufacturers_Admin_rpc {
 /*
  * Returns the manufacturers datatable data for listings
  *
  * @access public
  * @return json
  */
  public static function getAll() {
    $result = lC_Manufacturers_Admin::getAll();
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  }
 /*
  * Return the data used on the dialog forms
  *
  * @param integer $_GET['mid'] The customer group id
  * @param boolean $edit True = called from edit dialog else called from delete dialog
  * @access public
  * @return json
  */
  public static function getFormData() {
    $result = lC_Manufacturers_Admin::getFormData($_GET['mid']);
    if (!isset($result['rpcStatus'])) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
 /*
  * Delete the manufacturers record
  *
  * @param integer $_GET['mid'] The customer group id to delete
  * @access public
  * @return json
  */    
  public static function deleteEntry() {
    $result = array();
    $deleted = lC_Manufacturers_Admin::delete($_GET['mid']);
    if ($deleted) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
 /*
  * Batch delete manufacturers records
  *
  * @param array $_GET['batch'] An array of manufacturers id's
  * @access public
  * @return json
  */ 
  public static function batchDeleteEntries() {
    $result = array();
    $deleted = lC_Manufacturers_Admin::batchDelete($_GET['batch']);
     if ($deleted) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }    
}
?>