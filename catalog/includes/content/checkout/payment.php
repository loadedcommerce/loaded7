<?php
/**
  @package    catalog::content
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: payment.php v1.0 2013-08-08 datazen $
*/
class lC_Checkout_Payment extends lC_Template {

  /* Private variables */
  var $_module = 'payment',
      $_group = 'checkout',
      $_page_title,
      $_page_contents = 'checkout_payment.php',
      $_page_image = 'table_background_payment.gif';

  /* Class constructor */
  public function lC_Checkout_Payment() {
    global $lC_Database, $lC_Session, $lC_ShoppingCart, $lC_Customer, $lC_Services, $lC_Language, $lC_NavigationHistory, $lC_Breadcrumb, $lC_Payment, $lC_MessageStack, $lC_Vqmod;

    require($lC_Vqmod->modCheck('includes/classes/address_book.php'));

    if ($lC_Customer->isLoggedOn() === false) {
      $lC_NavigationHistory->setSnapshot();

      lc_redirect(lc_href_link(FILENAME_ACCOUNT, 'login', 'SSL'));
    }

    if ($lC_ShoppingCart->hasContents() === false) {
      lc_redirect(lc_href_link(FILENAME_CHECKOUT, null, 'SSL'));
    }

    // if no shipping method has been selected, redirect the customer to the shipping method selection page
    if ($lC_ShoppingCart->hasShippingMethod() === false ) {
      if (defined('SKIP_CHECKOUT_SHIPPING_PAGE') && SKIP_CHECKOUT_SHIPPING_PAGE == '1') {
      } else {
        lc_redirect(lc_href_link(FILENAME_CHECKOUT, 'shipping', 'SSL'));
      }
    }

    // Stock Check
    if ( (STOCK_CHECK == '1') && (AUTODISABLE_OUT_OF_STOCK_PRODUCT == '1') ) {
      foreach ($lC_ShoppingCart->getProducts() as $products) {
        if ($lC_ShoppingCart->isInStock($products['id']) === false) {
          lc_redirect(lc_href_link(FILENAME_CHECKOUT, 'SSL'));
          break;
        }
      }
    }

    $this->_page_title = $lC_Language->get('payment_method_heading');

    if ($lC_Services->isStarted('breadcrumb')) {
      $lC_Breadcrumb->add($lC_Language->get('breadcrumb_checkout_payment'), lc_href_link(FILENAME_CHECKOUT, $this->_module, 'SSL'));
    }
    
    // redirect to the billing address page when no default address exists
    if ($lC_Customer->hasDefaultAddress() === false) {
      $this->_page_title = $lC_Language->get('payment_address_heading');
      $this->_page_contents = 'checkout_payment_address.php';

      $this->addJavascriptPhpFilename('templates/' . $this->getCode() . '/javascript/addressBookDetails.js.php');
    } else {
      // if no billing destination address was selected, use the customers own address as default
      if ($lC_ShoppingCart->hasBillingAddress() == false) {
        $lC_ShoppingCart->setBillingAddress($lC_Customer->getDefaultAddressID());
      } else {
        // verify the selected billing address
        $Qcheck = $lC_Database->query('select address_book_id from :table_address_book where address_book_id = :address_book_id and customers_id = :customers_id limit 1');
        $Qcheck->bindTable(':table_address_book', TABLE_ADDRESS_BOOK);
        $Qcheck->bindInt(':address_book_id', $lC_ShoppingCart->getBillingAddress('id'));
        $Qcheck->bindInt(':customers_id', $lC_Customer->getID());
        $Qcheck->execute();

        if ($Qcheck->numberOfRows() !== 1) {
          $lC_ShoppingCart->setBillingAddress($lC_Customer->getDefaultAddressID());
          $lC_ShoppingCart->resetBillingMethod();
        }
      }

      // load all enabled payment modules
      include($lC_Vqmod->modCheck('includes/classes/payment.php'));
      $lC_Payment = new lC_Payment();

      $this->addJavascriptBlock($lC_Payment->getJavascriptBlocks());
    }

    if (isset($_GET['payment_error'])) {
      $lC_MessageStack->add('checkout_payment', urldecode($_GET['payment_error']), 'error'); 
    }
    
    if (isset($_SESSION['messageToStack']) && !empty($_SESSION['messageToStack'])) $lC_MessageStack->__construct();
    
    // ppec inject
    if ((isset($_GET['skip']) && $_GET['skip'] == 'no') || isset($_GET['payment_error'])) {
      if (isset( $_SESSION['SKIP_PAYMENT_PAGE'])) unset($_SESSION['SKIP_PAYMENT_PAGE']);
      if (isset( $_SESSION['cartSync'])) unset($_SESSION['cartSync']);
    } else {
      if (isset($_SESSION['SKIP_PAYMENT_PAGE']) && $_SESSION['SKIP_PAYMENT_PAGE'] === TRUE) {
        lc_redirect(lc_href_link(FILENAME_CHECKOUT, 'confirmation', 'SSL'));
      }
    }
  }
}
?>