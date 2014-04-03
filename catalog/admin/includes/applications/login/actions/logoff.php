<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: logoff.php v1.0 2013-08-08 datazen $
*/
class lC_Application_Login_Actions_logoff extends lC_Application_Login {
  public function __construct() {
    global $lC_Language, $lC_MessageStack;

    parent::__construct();

    unset($_SESSION['admin']);
    if (isset($_SESSION['img_resize_flag'])) unset($_SESSION['img_resize_flag']);

    lc_redirect_admin(lc_href_link_admin(FILENAME_DEFAULT));
  }
}
?>