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

require_once($lC_Vqmod->modCheck('includes/applications/administrators_log/classes/administrators_log.php'));

class lC_Administrators_log_Admin_rpc {
 /*
  * Returns the administrators log datatable data for listings
  *
  * @access public
  * @return json
  */
  public static function getAll() {
    $result = lC_Administrators_log_Admin::getAll();
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
  * Delete the administrator log entry
  *
  * @param integer $_GET['lid'] The administrator log entry id to delete
  * @access public
  * @return json
  */
  public static function deleteEntry() {
    $result = array();
    $deleted = lC_Administrators_log_Admin::delete($_GET['lid']);
    if ($deleted) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
 /*
  * Batch delete administrator log entries
  *
  * @param integer $_GET['batch'] The administrator log entry id's to delete
  * @access public
  * @return json
  */
  public static function batchDeleteEntries() {
    $result = array();
    $deleted = lC_Administrators_log_Admin::batchDelete($_GET['batch']);
    if ($deleted) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
 /*
  * Delete all administrator log entries
  *
  * @access public
  * @return json
  */
  public static function deleteAll() {
    $result = array();
    $deleted = lC_Administrators_log_Admin::deleteAll();
    if ($deleted) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
}
?>