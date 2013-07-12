<?php
/*
  $Id: rpc.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html

  @function The lC_Coupons_Admin_rpc class is for AJAX remote program control
*/
global $lC_Vqmod;

require_once($lC_Vqmod->modCheck('includes/applications/coupons/classes/coupons.php')); 

class lC_Coupons_Admin_rpc {
 /*
  * Returns the coupons datatable data for listings
  *
  * @access public
  * @return json
  */
  public static function getAll() {  
    $result = lC_Coupons_Admin::getAll();
    
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  } 
 /*
  * Delete the coupon record
  *
  * @param integer $_GET['cid'] The coupons id to delete
  * @access public
  * @return json
  */
  public static function deleteCoupon() {
    $deleted = lC_Coupons_Admin::delete($_GET['cid']);
    
    if ($deleted) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
 /*
  * Batch delete coupon records
  *
  * @param array $_GET['batch'] The coupons id's to delete
  * @access public
  * @return json
  */
  public static function batchDelete() {
    $deleted = lC_Coupons_Admin::batchDelete($_GET['batch']);
    
    if ($deleted) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }  

    echo json_encode($result);
  }
 /*
  * update coupon status
  *
  * @param int $_GET the coupon id and new value of the status 
  * @access public
  * @return json
  */
  public static function updateStatus() {
    $status = lC_Coupons_Admin::updateStatus($_GET['cid'], $_GET['val']);
    
    if ($status) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }  

    echo json_encode($result);
  }
}
?>
