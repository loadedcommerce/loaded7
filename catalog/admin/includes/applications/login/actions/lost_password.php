<?php
/*
  $Id: lost_password.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html

  @function The lC_Application_Login_Actions_lost_password
*/
class lC_Application_Login_Actions_lost_password extends lC_Application_Login {
    
  /*
  * Protected variables
  */
  protected $_page_contents = 'lost_password.php'; 
  
  public function __construct() {
    global $lC_Database, $lC_Language, $lC_MessageStack, $rInfo;
    
    parent::__construct();
     
    if (isset($_POST['key']) && $_POST['key'] != NULL && isset($_POST['email']) && $_POST['email'] != NULL) {
      $valid = lC_Login_Admin::lostPasswordConfirmKey($_POST['key'], $_POST['email']);
      $rInfo = new lC_ObjectInfo($_POST);  
    }    
  } 
}
?>