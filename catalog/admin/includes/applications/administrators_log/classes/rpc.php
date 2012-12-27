<?php
/*
  $Id: rpc.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html

  @function The lC_Administrators_log_Admin_rpc class is for AJAX remote program control
*/
require_once('includes/applications/administrators_log/classes/administrators_log.php');

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