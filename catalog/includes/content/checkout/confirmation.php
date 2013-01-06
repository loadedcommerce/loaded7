<?php
/*
  $Id: confirmation.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

  require('includes/classes/address_book.php');

  class lC_Checkout_Confirmation extends lC_Template {

    /* Private variables */
    var $_module = 'confirmation',
        $_group = 'checkout',
        $_page_title,
        $_page_contents = 'checkout_confirmation.php',
        $_page_image = 'table_background_confirmation.gif';

    /* Class constructor */
    function lC_Checkout_Confirmation() {
      global $lC_Session, $lC_Services, $lC_Language, $lC_ShoppingCart, $lC_Customer, $lC_MessageStack, $lC_NavigationHistory, $lC_Breadcrumb, $lC_Payment;

      if ($lC_Customer->isLoggedOn() === false) {
        $lC_NavigationHistory->setSnapshot();

        lc_redirect(lc_href_link(FILENAME_ACCOUNT, 'login', 'SSL'));
      }

      if ($lC_ShoppingCart->hasContents() === false) {
        lc_redirect(lc_href_link(FILENAME_CHECKOUT, null, 'SSL'));
      }

      // if no shipping method has been selected, redirect the customer to the shipping method selection page
      if ($lC_ShoppingCart->hasShippingAddress() == false) {
        lc_redirect(lc_href_link(FILENAME_CHECKOUT, 'shipping', 'SSL'));
      }

      include('includes/classes/order.php');

      $this->_page_title = $lC_Language->get('confirmation_heading');

      $lC_Language->load('order');

      if ($lC_Services->isStarted('breadcrumb')) {
        $lC_Breadcrumb->add($lC_Language->get('breadcrumb_checkout_confirmation'), lc_href_link(FILENAME_CHECKOUT, $this->_module, 'SSL'));
      }

      if ( (isset($_POST['comments'])) && (isset($_SESSION['comments'])) && (empty($_POST['comments'])) ) {
        unset($_SESSION['comments']);
      } elseif (!empty($_POST['comments'])) {
        $_SESSION['comments'] = lc_sanitize_string($_POST['comments']);
      }

      if (DISPLAY_CONDITIONS_ON_CHECKOUT == '1') {
        if (!isset($_POST['conditions']) || ($_POST['conditions'] != '1')) {
          $lC_MessageStack->add('checkout_payment', $lC_Language->get('error_conditions_not_accepted'), 'error');
        }
      }

      // load the selected payment module
      include('includes/classes/payment.php');
      $lC_Payment = new lC_Payment((isset($_POST['payment_method']) ? $_POST['payment_method'] : $lC_ShoppingCart->getBillingMethod('id')));

      if (isset($_POST['payment_method'])) {
        $lC_ShoppingCart->setBillingMethod(array('id' => $_POST['payment_method'], 'title' => $GLOBALS['lC_Payment_' . $_POST['payment_method']]->getMethodTitle()));
      }

      if ( $lC_Payment->hasActive() && ((isset($GLOBALS['lC_Payment_' . $lC_ShoppingCart->getBillingMethod('id')]) === false) || (isset($GLOBALS['lC_Payment_' . $lC_ShoppingCart->getBillingMethod('id')]) && is_object($GLOBALS['lC_Payment_' . $lC_ShoppingCart->getBillingMethod('id')]) && ($GLOBALS['lC_Payment_' . $lC_ShoppingCart->getBillingMethod('id')]->isEnabled() === false))) ) {
        $lC_MessageStack->add('checkout_payment', $lC_Language->get('error_no_payment_module_selected'), 'error');
      }

      if ($lC_MessageStack->size('checkout_payment') > 0) {
        lc_redirect(lc_href_link(FILENAME_CHECKOUT, 'payment', 'SSL'));
      }

      if ($lC_Payment->hasActive()) {
        $lC_Payment->pre_confirmation_check();
      }

      // Stock Check
      if ( (STOCK_CHECK == '1') && (STOCK_ALLOW_CHECKOUT == '-1') ) {
        foreach ($lC_ShoppingCart->getProducts() as $product) {
          if (!$lC_ShoppingCart->isInStock($product['item_id'])) {
            lc_redirect(lc_href_link(FILENAME_CHECKOUT, null, 'AUTO'));
          }
        }
      }
    }
  }
?>