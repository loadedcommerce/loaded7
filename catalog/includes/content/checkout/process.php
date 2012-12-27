<?php
/*
  $Id: process.php v1.0 2011-11-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

  require('includes/classes/address_book.php');

  class lC_Checkout_Process extends lC_Template {

    /* Private variables */
    var $_module = 'process';

    /* Class constructor */
    function lC_Checkout_Process() {
      global $lC_Session, $lC_ShoppingCart, $lC_Customer, $lC_NavigationHistory, $lC_Payment;

      if ($lC_Customer->isLoggedOn() === false) {
        $lC_NavigationHistory->setSnapshot();

        lc_redirect(lc_href_link(FILENAME_ACCOUNT, 'login', 'SSL'));
      }

      if ($lC_ShoppingCart->hasContents() === false) {
        lc_redirect(lc_href_link(FILENAME_CHECKOUT, null, 'SSL'));
      }

      // if no shipping method has been selected, redirect the customer to the shipping method selection page
      if (($lC_ShoppingCart->hasShippingMethod() === false) && ($lC_ShoppingCart->getContentType() != 'virtual')) {
        lc_redirect(lc_href_link(FILENAME_CHECKOUT, 'shipping', 'SSL'));
      }

      // load selected payment module
      include('includes/classes/payment.php');
      $lC_Payment = new lC_Payment($lC_ShoppingCart->getBillingMethod('id'));

      if ($lC_Payment->hasActive() && ($lC_ShoppingCart->hasBillingMethod() === false)) {
        lc_redirect(lc_href_link(FILENAME_CHECKOUT, 'payment', 'SSL'));
      }

      include('includes/classes/order.php');

      $lC_Payment->process();

      $lC_ShoppingCart->reset(true);

      // unregister session variables used during checkout
      unset($_SESSION['comments']);

      lc_redirect(lc_href_link(FILENAME_CHECKOUT, 'success', 'SSL'));
    }
  }
?>