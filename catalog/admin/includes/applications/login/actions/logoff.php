<?php
/*
  $Id: logoff.php v1.0 2011-11-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html

  @function The lC_Application_Login_Actions_logoff class controls the logoff action
*/
class lC_Application_Login_Actions_logoff extends lC_Application_Login {
  public function __construct() {
    global $lC_Language, $lC_MessageStack;

    parent::__construct();

    unset($_SESSION['admin']);

    lc_redirect_admin(lc_href_link_admin(FILENAME_DEFAULT));
  }
}
?>