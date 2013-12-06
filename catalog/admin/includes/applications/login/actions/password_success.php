<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: password_success.php v1.0 2013-08-08 datazen $
*/
require_once('includes/applications/login/classes/login.php'); 

class lC_Application_Login_Actions_password_success extends lC_Application_Login {
  /*
  * Protected variables
  */
  protected $_page_contents = 'password_success.php'; 
  
  public function __construct() {
    global $lC_Database, $lC_Language, $lC_MessageStack;

    parent::__construct();
    
    if (!isset($_SESSION['verify_key_valid']) || $_SESSION['verify_key_valid'] === false) {
      lc_redirect_admin(lc_href_link_admin(FILENAME_DEFAULT, $this->_module));
    } 
    
    if (isset($_POST['password']) && $_POST['password'] != NULL && isset($_POST['email']) && $_POST['email'] != NULL) {
      if (lC_Login_Admin::passwordChange($_POST['password'], $_POST['email'])) {
        $rInfo = new lC_ObjectInfo($_POST);
      } else {
        // if error, redirect back to login
        lc_redirect_admin(lc_href_link_admin(FILENAME_DEFAULT, $this->_module));
      }
    }     
  }
}
?>