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
    global $lC_Services, $lC_Language, $lC_Breadcrumb;

    $this->_page_title = $lC_Language->get('shopping_cart_heading');

    if ($lC_Services->isStarted('breadcrumb')) {
      $lC_Breadcrumb->add($lC_Language->get('breadcrumb_checkout_shopping_cart'), lc_href_link(FILENAME_CHECKOUT, null, 'SSL'));
    }

//      if ($_GET[$this->_module] == 'update') {
//        $this->_process();
//      }
  }
}
?>