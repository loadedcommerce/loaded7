<?php
/**
  @package    catalog::content
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: logoff.php v1.0 2013-08-08 datazen $
*/
class lC_Account_Logoff extends lC_Template {

  /* Private variables */
  var $_module = 'logoff',
      $_group = 'account',
      $_page_title,
      $_page_contents = 'logoff.php';

  /* Class constructor */
  public function lC_Account_Logoff() {
    global $lC_Language, $lC_Services, $lC_Breadcrumb;

    $this->_page_title = $lC_Language->get('sign_out_heading');

    if ($lC_Services->isStarted('breadcrumb')) {
      $lC_Breadcrumb->add($lC_Language->get('breadcrumb_sign_out'));
    }

    $this->_process();
  }

  /* Private methods */
  protected function _process() {
    global $lC_ShoppingCart, $lC_Customer, $lC_Coupons;

    $lC_ShoppingCart->reset();
    $lC_Coupons->reset();
    $lC_Customer->reset();
    lC_Cache::clearAll();
    
	if (isset($_SESSION['admin_login']) && $_SESSION['admin_login'] === TRUE) unset($_SESSION['admin_login']); 
  }
}
?>