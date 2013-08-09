<?php
/*
  $Id: password_success.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html

  @function The lC_Application_Login_Actions_password_success class
*/
class lC_Application_Login_Actions_password_success extends lC_Application_Login {
    
  /*
  * Protected variables
  */
  protected $_page_contents = 'password_success.php'; 
  
  public function __construct() {
    global $lC_Database, $lC_Language, $lC_MessageStack;

    parent::__construct();
    
//    if (isset($_POST['password']) && $_POST['password'] != NULL && isset($_POST['email']) && $_POST['email'] != NULL) {
//      $updated = lC_Login_Admin::lostPasswordConfirmKey($_POST['password'], $_POST['email']);
//    }    
  }
}
?>
