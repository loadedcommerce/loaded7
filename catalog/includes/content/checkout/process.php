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

    if (isset($_SESSION['PPEC_TOKEN']) && $_SESSION['PPEC_TOKEN'] != NULL && isset($_GET['token']) && $_GET['token'] == $_SESSION['PPEC_TOKEN']) {  
    } else {
      if ($lC_Customer->isLoggedOn() === false) {
        $lC_NavigationHistory->setSnapshot();                                     
        lc_redirect(lc_href_link(FILENAME_ACCOUNT, 'login', 'SSL'));
      }
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

    /*VQMOD-003*/
    if (isset($_SESSION['PPEC_TOKEN']) && $_SESSION['PPEC_TOKEN'] != NULL && isset($_GET['token']) && $_GET['token'] == $_SESSION['PPEC_TOKEN']) { 
      $lC_Payment = new lC_Payment('paypal_adv');
      $lC_ShoppingCart->setBillingMethod(array('id' => 'paypal_adv', 'title' => $GLOBALS['lC_Payment_paypal_adv']->getMethodTitle()));
      
      if (isset($_SESSION['cartSync']['cartID']) && $_SESSION['cartSync']['cartID'] != NULL) {
        $_SESSION['cartID'] = $_SESSION['cartSync']['cartID'];
        $_SESSION['prepOrderID'] = $_SESSION['cartSync']['prepOrderID'];
      }
    } else if (isset($_SESSION['cartSync']['paymentMethod']) && $_SESSION['cartSync']['paymentMethod'] != NULL) {
      $lC_Payment = new lC_Payment($_SESSION['cartSync']['paymentMethod']);
      $lC_ShoppingCart->setBillingMethod(array('id' => $_SESSION['cartSync']['paymentMethod'], 'title' => $GLOBALS['lC_Payment_' . $_SESSION['cartSync']['paymentMethod']]->getMethodTitle()));
     
    } else {
      $lC_Payment = new lC_Payment($lC_ShoppingCart->getBillingMethod('id'));
    }
    if ($lC_Payment->hasActive() && ($lC_ShoppingCart->hasBillingMethod() === false)) {
      lc_redirect(lc_href_link(FILENAME_CHECKOUT, 'payment', 'SSL'));
    }
                
    include($lC_Vqmod->modCheck('includes/classes/order.php'));

    $lC_Payment->process();

    $lC_ShoppingCart->reset(true);

    // unregister session variables used during checkout
    if (isset($_SESSION['comments'])) unset($_SESSION['comments']);
    if (isset($_SESSION['cartSync'])) unset($_SESSION['cartSync']);
    
    /*VQMOD-004*/
    if (isset($_SESSION['PPEC_TOKEN'])) unset($_SESSION['PPEC_TOKEN']);
    if (isset($_SESSION['PPEC_PROCESS'])) unset($_SESSION['PPEC_PROCESS']);
    if (isset($_SESSION['PPEC_PAYDATA'])) unset($_SESSION['PPEC_PAYDATA']);

    lc_redirect(lc_href_link(FILENAME_CHECKOUT, 'success', 'SSL'));
  }
}
?>