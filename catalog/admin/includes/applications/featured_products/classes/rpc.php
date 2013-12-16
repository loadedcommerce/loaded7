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

require_once($lC_Vqmod->modCheck('includes/applications/featured_products/classes/featured_products.php')); 

class lC_Featured_products_Admin_rpc {
 /*
  * Returns the featured product datatable data for listings
  *
  * @access public
  * @return json
  */
  public static function getAll() {  
    $result = lC_Featured_products_Admin::getAll();
    
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  } 
 /*
  * Delete the featured product record
  *
  * @param integer $_GET['fid'] The featured products id to delete
  * @access public
  * @return json
  */
  public static function delete() {
    $deleted = lC_Featured_products_Admin::delete($_GET['fid']);
    
    if ($deleted) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
 /*
  * Batch delete featured product records
  *
  * @param array $_GET['batch'] The Featured product id's to delete
  * @access public
  * @return json
  */
  public static function batchDelete() {
    $deleted = lC_Featured_products_Admin::batchDelete($_GET['batch']);
    
    if ($deleted) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }  

    echo json_encode($result);
  }
 /*
  * update featured products status
  *
  * @param int $_GET the featured products id and new value of the status 
  * @access public
  * @return json
  */
  public static function updateStatus() {
    $status = lC_Featured_products_Admin::updateStatus($_GET['fid'], $_GET['val']);
    
    if ($status) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }  

    echo json_encode($result);
  }
 /*
  * copy Featured product
  *
  * @param int $_GET the featured product id and create a copy in the database 
  * @access public
  * @return json
  */
  public static function copy() {
    $copy = lC_Featured_products_Admin::copy($_GET['fid']);
    
    if ($copy) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }  

    echo json_encode($result);
  }
}
?>