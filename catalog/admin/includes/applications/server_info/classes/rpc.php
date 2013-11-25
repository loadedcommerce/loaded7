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