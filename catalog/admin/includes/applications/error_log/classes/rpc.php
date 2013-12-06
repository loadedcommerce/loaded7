<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: main.php v1.0 2013-08-08 datazen $
*/
global $lC_Vqmod;

require_once($lC_Vqmod->modCheck('includes/applications/error_log/classes/error_log.php'));

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