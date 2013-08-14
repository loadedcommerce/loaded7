<?php
/*
  $Id: rpc.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html

  @function The lC_Server_info_Admin_rpc class is for AJAX remote program control
*/
require_once('includes/applications/server_info/classes/server_info.php');

class lC_Server_info_Admin_rpc {
 /*
  * Updates the installation ID
  *
  * @access public
  * @return json
  */
  public static function updateInstallID() {
    $result = array();
    if (lC_Server_info_Admin::updateInstallID($_GET['id'])) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }    
}
?>