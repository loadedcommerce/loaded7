<?php
/*
  $Id: rpc.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html

  @function The lC_Error_log_Admin_rpc class is for AJAX remote program control
*/
require_once('includes/applications/error_log/classes/error_log.php');

class lC_Error_log_Admin_rpc {
 /*
  * Returns the error log datatable data for listings
  *
  * @access public
  * @return json
  */
  public static function getAll() {
    $result = lC_ErrorLog_Admin::getAll();
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  }
 /*
  * Delete all error log entries
  *
  * @access public
  * @return json
  */
  public static function deleteAll() {
    $result = array();
    $deleted = lC_ErrorLog_Admin::deleteAll();
    if ($deleted) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
}
?>