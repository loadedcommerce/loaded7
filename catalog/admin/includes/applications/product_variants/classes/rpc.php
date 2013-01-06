<?php
/*
  $Id: rpc.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
 
  @function The lC_Product_variants_Admin_rpc class is for AJAX remote program control
*/
require_once('includes/applications/product_variants/classes/product_variants.php');

class lC_Product_variants_Admin_rpc {
 /*
  * Returns the product variant groups datatable data for listings
  *
  * @access public
  * @return json
  */
  public static function getAll() {
    $result = lC_Product_variants_Admin::getAll();
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  }
 /*
  * Returns the data used on the dialog forms
  *
  * @param integer $_GET['pvid'] The product variant group id
  * @access public
  * @return json
  */
  public static function getFormData() {
    $result = lC_Product_variants_Admin::getFormData($_GET['pvid']);
    if (!isset($result['rpcStatus'])) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  } 
 /*
  * Saves the product variant group information
  *
  * @param integer $_GET['pvid'] The product variant group id used on update, null on insert
  * @param array $_GET An array containing the product variant group information
  * @access public
  * @return json
  */
  public static function saveGroup() { 
    $result = array();
    $saved = lC_Product_variants_Admin::save($_GET['pvid'], $_GET);
    if ($saved) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
 /*
  * Delete the product variant group record
  *
  * @param integer $_GET['pvid'] The product variant group id to delete
  * @access public
  * @return json
  */    
  public static function deleteGroup() {
    $result = array();
    $deleted = lC_Product_variants_Admin::delete($_GET['pvid']);
    if ($deleted) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
 /*
  * Batch delete product variant group records
  *
  * @param array $_GET['batch'] An array of product variant group id's
  * @access public
  * @return json
  */ 
  public static function batchDelete() {
    $result = lC_Product_variants_Admin::batchDelete($_GET['batch']);
    if (isset($result['namesString']) && $result['namesString'] != null) {
    } else {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
 /*
  * Returns the product variant group entries datatable data for listings
  *
  * @access public
  * @return json
  */
  public static function getAllEntries() {
    global $_module;

    $result = lC_Product_variants_Admin::getAllEntries($_GET[$_module]);
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  }
 /*
  * Returns the entries data used on the dialog forms
  *
  * @param integer $_GET['pveid'] The product variant group entry id
  * @access public
  * @return json
  */
  public static function getEntryFormData() {
    $result = lC_Product_variants_Admin::getEntryFormData($_GET['pveid']);
    if (!isset($result['rpcStatus'])) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }

 /*
  * Saves the product variant group entry information
  *
  * @param integer $_GET['pveid'] The product variant group entry id used on update, null on insert
  * @param array $_GET An array containing the product variant group entry information
  * @access public
  * @return json
  */
  public static function saveEntry() { 
    $result = array();
    $saved = lC_Product_variants_Admin::saveEntry($_GET['pveid'], $_GET);
    if ($saved) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }

 /*
  * Delete the product variant group entry
  *
  * @param integer $_GET['pveid'] The product variant group entry id to delete
  * @access public
  * @return json
  */    
  public static function deleteEntry() {
    global $_module;

    $result = array();
    $deleted = lC_Product_variants_Admin::deleteEntry($_GET['pveid'], $_GET[$_module]);
    if ($deleted) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
 /*
  * Batch delete product variant group entries
  *
  * @param array $_GET['batch'] An array of product variant group entry id's
  * @access public
  * @return json
  */ 
  public static function batchDeleteEntries() {
    global $_module;

    $result = lC_Product_variants_Admin::batchDeleteEntries($_GET['batch'], $_GET[$_module]);
    if (isset($result['namesString']) && $result['namesString'] != null) {
    } else {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
}
?>