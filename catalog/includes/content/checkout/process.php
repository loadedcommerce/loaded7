<?php
/**
  @package    catalog::content
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: process.php v1.0 2013-08-08 datazen $
*/
class lC_Checkout_Process extends lC_Template {

  /* Private variables */
  var $_module = 'process';

  /* Class constructor */
  public function lC_Checkout_Process() {
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
      if (defined('SKIP_CHECKOUT_SHIPPING_PAGE') && SKIP_CHECKOUT_SHIPPING_PAGE == '1') {
      } else {
        lc_redirect(lc_href_link(FILENAME_CHECKOUT, 'shipping', 'SSL'));
      }
    }
             
    // load selected payment module
    include($lC_Vqmod->modCheck('includes/classes/payment.php'));

    /*VQMOD-003*/   
    if (isset($_SESSION['PPEC_TOKEN']) && $_SESSION['PPEC_TOKEN'] != NULL && isset($_GET['token']) && $_GET['token'] == $_SESSION['PPEC_TOKEN']) { 
      $lC_Payment = new lC_Payment($lC_ShoppingCart->getBillingMethod('id'));
      //$lC_ShoppingCart->setBillingMethod(array('id' => 'paypal_adv', 'title' => $GLOBALS['lC_Payment_paypal_adv']->getMethodTitle()));
      
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
    if (isset($_SESSION['this_handling'])) unset($_SESSION['this_handling']);
    if (isset($_SESSION['this_payment'])) unset($_SESSION['this_payment']);    

    if (isset($_SESSION['SelectedShippingMethodCost'])) unset($_SESSION['SelectedShippingMethodCost']);

    lc_redirect(lc_href_link(FILENAME_CHECKOUT, 'success', 'SSL'));
  }
}
?>