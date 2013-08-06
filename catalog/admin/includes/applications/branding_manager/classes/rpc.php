<?php
/*
  $Id: rpc.php v1.0 2011-11-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html

  @function The lC_Branding_manager_Admin_rpc class is for AJAX remote program control
*/
require('includes/applications/branding_manager/classes/branding_manager.php');

class lC_Branding_manager_Admin_rpc {
 /*
  * Returns the store branding data
  *
  * @access public
  * @return json
  */
  public static function get() {
    $result = lC_Branding_manager_Admin::get();
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  }

}
?>