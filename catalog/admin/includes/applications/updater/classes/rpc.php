<?php
/*
  $Id: rpc.php v1.0 2011-11-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html

  @function The lC_Updater_Admin_rpc class is for AJAX remote program control
*/
require_once('includes/applications/updater/classes/updater.php');    

class lC_Updater_Admin_rpc {
 /*
  * Returns the modules datatable data for listings
  *
  * @access public
  * @return json
  */
  public static function getUpdateData() {
    $result = lC_Updater_Admin::getUpdateData($_GET['type']);
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  }
}
?>