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
global $lC_Vqmod;

require_once($lC_Vqmod->modCheck('includes/applications/login/classes/login.php')); 

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
  
 /*
  * Send the admin lost password email
  *
  * @access public
  * @return json
  */
  public static function lostPassword() {
    $result = array();
    $result['rpcStatus'] = 0;
    $validated = lC_Login_Admin::lostPassword($_GET['email']);
    if ($validated) {
      $result['rpcStatus'] = 1;
    }

    echo json_encode($result);
    exit();
  }
  
 /*
  * Check the admin email to send lost password email to
  *
  * @access public
  * @return json
  */
  public static function lostPasswordConfirmEmail() {
    $result = array();
    $result['rpcStatus'] = 0;
    $validated = lC_Login_Admin::lostPasswordConfirmEmail($_GET['password_email']);
    if ($validated) {
      $result['rpcStatus'] = 1;
    }

    echo json_encode($result);
    exit();
  }
  
 /*
  * Check to verify the lost password key before sending to change password screen 
  *
  * @access public
  * @return json
  */
  public static function lostPasswordConfirmKey() {
    $result = array();
    $result['rpcStatus'] = 0;
    $validated = lC_Login_Admin::lostPasswordConfirmKey($_GET['key'], $_GET['email']);
    if ($validated) {
      $result['rpcStatus'] = 1;
    }

    echo json_encode($result);
    exit();
  }
  
 /*
  * Check to verify the lost password key before sending to change password screen 
  *
  * @access public
  * @return json
  */
  public static function passwordChange() {
    $result = array();
    $result['rpcStatus'] = 0;
    $validated = lC_Login_Admin::passwordChange($_GET['password'], $_GET['email']);
    if ($validated) {
      $result['rpcStatus'] = 1;
    }

    echo json_encode($result);
    exit();
  }
  
 /*
  * Activate Pro Serial
  *
  * @access public
  * @return json
  */
  public static function activatePro() {
    $result = array();
    $result['rpcStatus'] = 0;
    $validated = lC_Login_Admin::activatePro($_GET['serial']);
    if ($validated) {
      $result['rpcStatus'] = 1;
    }

    echo json_encode($result);
    exit();
  }
  
 /*
  * Activate Free Features
  *
  * @access public
  * @return json
  */
  public static function activateFree() {
    $result = array();
    $result['rpcStatus'] = 0;
    $validated = lC_Login_Admin::activateFree();
    if ($validated) {
      $result['rpcStatus'] = 1;
    }

    echo json_encode($result);
    exit();
  }
}
?>