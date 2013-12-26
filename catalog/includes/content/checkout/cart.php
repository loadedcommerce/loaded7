<?php
/**
  @package    catalog::content
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: cart.php v1.0 2013-08-08 datazen $
*/
class lC_Checkout_Cart extends lC_Template {

  /* Private variables */
  var $_module = 'cart',
      $_group = 'checkout',
      $_page_title,
      $_page_contents = 'shopping_cart.php',
      $_page_image = '';

  /* Class constructor */
  public function lC_Checkout_Cart() {
    global $lC_Services, $lC_Language, $lC_Breadcrumb, $lC_MessageStack, $lC_Customer, $lC_NavigationHistory;

    $this->_page_title = $lC_Language->get('shopping_cart_heading');

    if ($lC_Services->isStarted('breadcrumb')) {
      $lC_Breadcrumb->add($lC_Language->get('breadcrumb_checkout_shopping_cart'), lc_href_link(FILENAME_CHECKOUT, null, 'SSL'));
    }
    
    if (isset($_SESSION['messageToStack'])) {   
      $lC_MessageStack = new lC_MessageStack(); 
    }
  }
}
?>