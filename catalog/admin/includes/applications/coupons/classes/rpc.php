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
 /*
  * copy coupon
  *
  * @param int $_GET the coupon id and create a copy in the database 
  * @access public
  * @return json
  */
  public static function copyCoupon() {
    $copy = lC_Coupons_Admin::copyCoupon($_GET['cid']);
    
    if ($copy) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }  

    echo json_encode($result);
  }
}
?>
