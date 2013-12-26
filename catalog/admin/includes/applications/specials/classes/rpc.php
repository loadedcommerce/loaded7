<?php
/*
  $Id: rpc.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html

  @function The lC_Specials_Admin_rpc class is for AJAX remote program control
*/
global $lC_Vqmod;

require_once($lC_Vqmod->modCheck('includes/applications/specials/classes/specials.php')); 

class lC_Specials_Admin_rpc {
 /*
  * Returns the specials datatable data for listings
  *
  * @access public
  * @return json
  */
  public static function getAll() {  
    $result = lC_Specials_Admin::getAll();
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  }
 /*
  * Return the data used on the dialog forms
  *
  * @param integer $_GET['sid'] The specials id
  * @access public
  * @return json
  */
  public static function getFormData() {  
    $result = lC_Specials_Admin::formData();
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  } 
 /*
  * Return the specials information
  *
  * @param integer $_GET['sid'] The specials id
  * @access public
  * @return json
  */
  public static function getEntry() {  
    $result = lC_Specials_Admin::getData($_GET['sid']);
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  }
 /*
  * Save the specials information
  *
  * @param integer $_GET['sid'] The specials id used on update, null on insert
  * @param array $_GET An array containing the specials information
  * @access public
  * @return json
  */
  public static function saveEntry() { 
    $result = lC_Specials_Admin::save($_GET['sid'], $_GET);
    if (!isset($result['rpcStatus'])) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
 /*
  * Delete the specials record
  *
  * @param integer $_GET['sid'] The specials id to delete
  * @access public
  * @return json
  */
  public static function deleteEntry() {
    $result = array();
    $deleted = lC_Specials_Admin::delete((int)$_GET['sid']);
    if ($deleted) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
 /*
  * Batch delete specials records
  *
  * @param array $_GET['batch'] The specials id's to delete
  * @access public
  * @return json
  */
  public static function batchDeleteEntries() {
    $result = array();
    $deleted = lC_Specials_Admin::batchDelete($_GET['batch']);
    if ($deleted) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }    
 /*
  * Return the product tax class
  *
  * @param array $_GET['pid'] The product id
  * @access public
  * @return json
  */
  public static function getTaxClass() {  
    $result = lC_Specials_Admin::getTax($_GET['pid']);
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  } 
}
?>
