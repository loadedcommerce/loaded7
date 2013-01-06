<?php
/*
  $Id: rpc.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html

  @function The lC_Login_Admin_rpc class is for AJAX remote program control
*/
require_once('includes/applications/login/classes/login.php'); 

class lC_Login_Admin_rpc {
 /*
  * Validate the admin login credentials 
  *
  * @access public
  * @return json
  */
  public static function validateLogin() { 
    $result = array();
    $result['rpcStatus'] = 0;
    $validated = lC_Login_Admin::validate($_GET['user_name'], $_GET['user_password']);
    if ($validated) {
      $result['rpcStatus'] = 1;
    }

    echo json_encode($result);
    exit();
  }
}
?>