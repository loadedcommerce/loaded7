<?php
/**
  @package    catalog::content
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: shipping.php v1.0 2013-08-08 datazen $
*/
class lC_Checkout_Shipping extends lC_Template {

  /* Private variables */
  var $_module = 'shipping',
      $_group = 'checkout',
      $_page_title,
      $_page_contents = 'checkout_shipping.php',
      $_page_image = 'table_background_delivery.gif';

  /* Class constructor */
  public function lC_Checkout_Shipping() {  
    global $lC_Database, $lC_ShoppingCart, $lC_Customer, $lC_Services, $lC_Language, $lC_NavigationHistory, $lC_Breadcrumb, $lC_Shipping, $lC_MessageStack, $lC_Vqmod;
       
    require_once($lC_Vqmod->modCheck('includes/classes/address_book.php')); 

    /*VQMOD-002*/
    
    if ($lC_Customer->isLoggedOn() === false) {
      $lC_NavigationHistory->setSnapshot();
      lc_redirect(lc_href_link(FILENAME_ACCOUNT, 'login', 'SSL'));
    }

    if ($lC_ShoppingCart->hasContents() === false) {
      lc_redirect(lc_href_link(FILENAME_CHECKOUT, null, 'SSL'));
    }

    // if the order contains only virtual products, forward the customer to the billing page as a shipping address is not needed
    if ($lC_ShoppingCart->getContentType() == 'virtual' || (defined('SKIP_CHECKOUT_SHIPPING_PAGE') && SKIP_CHECKOUT_SHIPPING_PAGE == '1')) {
      lc_redirect(lc_href_link(FILENAME_CHECKOUT, 'payment', 'SSL'));
    }

    $this->_page_title = $lC_Language->get('shipping_method_heading');

    if ($lC_Services->isStarted('breadcrumb')) {
      $lC_Breadcrumb->add($lC_Language->get('breadcrumb_checkout_shipping'), lc_href_link(FILENAME_CHECKOUT, $this->_module, 'SSL'));
    }

    if ($lC_Customer->hasDefaultAddress() === false) {
      if(isset($_GET['account_created'])){
      lc_redirect(lc_href_link(FILENAME_CHECKOUT, 'shipping_address&account_created=true', 'SSL'));
    }else{
      lc_redirect(lc_href_link(FILENAME_CHECKOUT, 'shipping_address', 'SSL'));
    }
    } else {
      $this->addJavascriptFilename('templates/' . $this->getCode() . '/javascript/shipping.js.php');

      // if no shipping destination address was selected, use the customers own address as default
      if ($lC_ShoppingCart->hasShippingAddress() === false) {
        $lC_ShoppingCart->setShippingAddress($lC_Customer->getDefaultAddressID());
      } else {
        // verify the selected shipping address
        $Qcheck = $lC_Database->query('select address_book_id from :table_address_book where address_book_id = :address_book_id and customers_id = :customers_id limit 1');
        $Qcheck->bindTable(':table_address_book', TABLE_ADDRESS_BOOK);
        $Qcheck->bindInt(':address_book_id', $lC_ShoppingCart->getShippingAddress('id'));
        $Qcheck->bindInt(':customers_id', $lC_Customer->getID());
        $Qcheck->execute();

        if ($Qcheck->numberOfRows() !== 1) {
          $lC_ShoppingCart->setShippingAddress($lC_Customer->getDefaultAddressID());
        }
      }
      
      // load all enabled shipping modules
      if (class_exists('lC_Shipping') === false) {
        include($lC_Vqmod->modCheck('includes/classes/shipping.php'));
      }

      $lC_Shipping = new lC_Shipping();

      // if no shipping method has been selected, automatically select the cheapest method.
      if ($lC_ShoppingCart->hasShippingMethod() === false) {          
        if ($lC_Shipping->numberOfQuotes() === 1) {  
          $lC_ShoppingCart->setShippingMethod($lC_Shipping->getFirstQuote(), false);
        } else {  
          $lC_ShoppingCart->setShippingMethod($lC_Shipping->getCheapestQuote());
        }
      }
    } 

    if ($_GET[$this->_module] == 'process') {
      $this->_process();
    }
  }

  /* Private methods */
  protected function _process() {
    global $lC_ShoppingCart, $lC_Shipping, $lC_Vqmod;

    require_once($lC_Vqmod->modCheck('includes/classes/address_book.php'));

    if (!empty($_POST['comments'])) {
      $_SESSION['comments'] = lc_sanitize_string($_POST['comments']);
    }
    // added to carry ship to address as billing address
    if (!empty($_POST['shipto_as_billable'])) {
      $_SESSION['shipto_as_billable'] = $_POST['shipto_as_billable'];
    }

    if ($lC_Shipping->hasQuotes()) {
      if (isset($_POST['shipping_mod_sel']) && strpos($_POST['shipping_mod_sel'], '_')) {
        list($module, $method) = explode('_', $_POST['shipping_mod_sel']);
        $module = 'lC_Shipping_' . $module;

        if (is_object($GLOBALS[$module]) && $GLOBALS[$module]->isEnabled()) {
          $quote = $lC_Shipping->getQuote($_POST['shipping_mod_sel']);

          if (isset($quote['error'])) {
            $lC_ShoppingCart->resetShippingMethod();
          } else {
            $lC_ShoppingCart->setShippingMethod($quote);
            $_SESSION['SelectedShippingMethodCost'] = $quote['cost'];
            lc_redirect(lc_href_link(FILENAME_CHECKOUT, 'payment', 'SSL'));
          }
        } else {
          $lC_ShoppingCart->resetShippingMethod();
        }
      }
    } else {
      $lC_ShoppingCart->resetShippingMethod();

      lc_redirect(lc_href_link(FILENAME_CHECKOUT, 'payment', 'SSL'));
    }
  }
}
?>