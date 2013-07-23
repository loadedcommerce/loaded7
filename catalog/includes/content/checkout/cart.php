<?php
/**
  $Id: cart.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
class lC_Checkout_Cart extends lC_Template {

  /* Private variables */
  var $_module = 'cart',
      $_group = 'checkout',
      $_page_title,
      $_page_contents = 'shopping_cart.php',
      $_page_image = 'table_background_cart.gif';

  /* Class constructor */
  function lC_Checkout_Cart() {
    global $lC_Services, $lC_Language, $lC_Breadcrumb, $lC_MessageStack, $lC_Customer, $lC_NavigationHistory;

    $this->_page_title = $lC_Language->get('shopping_cart_heading');

    if ($lC_Services->isStarted('breadcrumb')) {
      $lC_Breadcrumb->add($lC_Language->get('breadcrumb_checkout_shopping_cart'), lc_href_link(FILENAME_CHECKOUT, null, 'SSL'));
    }
    
    if (isset($_SESSION['messageToStack'])) {   
      $lC_MessageStack = new lC_MessageStack(); 
    }
    
    if ($lC_Customer->isLoggedOn() === false) {
      $lC_NavigationHistory->setSnapshot();
      lc_redirect(lc_href_link(FILENAME_ACCOUNT, 'login', 'SSL'));
    }

  }
}
?>