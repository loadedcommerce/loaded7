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

require_once($lC_Vqmod->modCheck('includes/applications/reviews/classes/reviews.php')); 

class lC_Reviews_Admin_rpc {
 /*
  * Returns the reviews datatable data for listings
  *
  * @access public
  * @return json
  */
  public static function getAll() {
    $result = lC_Reviews_Admin::getAll();
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  }
 /*
  * Return the data used on the dialog forms
  *
  * @param integer $_GET['rid'] The reviews id
  * @access public
  * @return json
  */
  public static function getFormData() {  
    $result = lC_Reviews_Admin::formData($_GET['rid']);
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  } 
 /*
  * Return the reviews information
  *
  * @param integer $_GET['rid'] The reviews id
  * @access public
  * @return json
  */
  public static function getEntry() {  
    $result = lC_Reviews_Admin::getData($_GET['rid']);
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  }
 /*
  * Save the reviews information 
  *
  * @param integer $_GET['rid'] The reviews id used on update, null on insert
  * @param array $_GET An array containing the reviews information
  * @access public
  * @return json
  */
  public static function saveEntry() { 
    $result = array();
    $saved = lC_Reviews_Admin::save($_GET['rid'], $_GET);
    if ($saved) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
 /*
  * Delete a reviews record
  *
  * @param integer $_GET['rid'] The reviews id to delete
  * @access public
  * @return json
  */
  public static function deleteEntry() {
    $deleted = lC_Reviews_Admin::delete($_GET['rid']);
    if ($deleted) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
 /*
  * Batch delete review records
  *
  * @param array $_GET['batch'] The reviews id's to delete
  * @access public
  * @return json
  */
  public static function batchDeleteEntries() {
    $deleted = lC_Reviews_Admin::batchDelete($_GET['batch']);
    if ($deleted) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
 /*
  * Approve a review
  *
  * @param integer $_GET['rid'] The reviews id
  * @access public
  * @return json
  */
  public static function approveEntry() {
    $result = array();
    $approved = lC_Reviews_Admin::approve($_GET['rid']);
    if ($approved) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
 /*
  * Reject a review
  *
  * @param integer $_GET['rid'] The reviews id
  * @access public
  * @return json
  */
  public static function rejectEntry() {
    $result = array();
    $rejected = lC_Reviews_Admin::reject($_GET['rid']);
    if ($rejected) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
}
?>
