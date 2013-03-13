<?php
/**
  $Id: process.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
class lC_Checkout_Process extends lC_Template {

  /* Private variables */
  var $_module = 'process';

  /* Class constructor */
  function lC_Checkout_Process() {
    global $lC_Session, $lC_ShoppingCart, $lC_Customer, $lC_NavigationHistory, $lC_Payment, $lC_Vqmod;
    
    require($lC_Vqmod->modCheck('includes/classes/address_book.php'));

    if ($lC_Customer->isLoggedOn() === false) {
      $lC_NavigationHistory->setSnapshot();

      lc_redirect(lc_href_link(FILENAME_ACCOUNT, 'login', 'SSL'));
    }

    if ($lC_ShoppingCart->hasContents() === false) {
      lc_redirect(lc_href_link(FILENAME_CHECKOUT, null, 'SSL'));
    }
    
    // added for removal of order comments from shipping and payment pages and placed on confirmation page only during checkout
    if (!empty($_POST['comments'])) {
      $_SESSION['comments'] = lc_sanitize_string($_POST['comments']);
    }

    // if no shipping method has been selected, redirect the customer to the shipping method selection page
    if (($lC_ShoppingCart->hasShippingMethod() === false) && ($lC_ShoppingCart->getContentType() != 'virtual')) {
      lc_redirect(lc_href_link(FILENAME_CHECKOUT, 'shipping', 'SSL'));
    }

    // load selected payment module
    include($lC_Vqmod->modCheck('includes/classes/payment.php'));
    $lC_Payment = new lC_Payment($lC_ShoppingCart->getBillingMethod('id'));

    if ($lC_Payment->hasActive() && ($lC_ShoppingCart->hasBillingMethod() === false)) {
      lc_redirect(lc_href_link(FILENAME_CHECKOUT, 'payment', 'SSL'));
    }

    include($lC_Vqmod->modCheck('includes/classes/order.php'));

      if (isset($_SESSION['PROCESS_DATA']) && $_SESSION['PROCESS_DATA'] != NULL) {
        $_POST = $_SESSION['PROCESS_DATA'];
        unset($_SESSION['PROCESS_DATA']);
      }
      
      $lC_Payment->process();

    $lC_ShoppingCart->reset(true);

    // unregister session variables used during checkout
    unset($_SESSION['comments']);

    lc_redirect(lc_href_link(FILENAME_CHECKOUT, 'success', 'SSL'));
  }
}
?>