<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: lost_password.php v1.0 2013-08-08 datazen $
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
      if (lC_Login_Admin::lostPasswordConfirmKey($_POST['key'], $_POST['email'])) {
        $rInfo = new lC_ObjectInfo($_POST);  
      } else {
        // if key is invalid redirect back to login
        lc_redirect_admin(lc_href_link_admin(FILENAME_DEFAULT, $this->_module));
      }
    }    
  } 
}
?>