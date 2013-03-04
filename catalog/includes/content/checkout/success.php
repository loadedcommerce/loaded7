<?php
/**
  $Id: success.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
class lC_Checkout_Success extends lC_Template {

  /* Private variables */
  var $_module = 'success',
      $_group = 'checkout',
      $_page_title,
      $_page_contents = 'checkout_success.php';

  /* Class constructor */
  function lC_Checkout_Success() {
    global $lC_Services, $lC_Language, $lC_Customer, $lC_NavigationHistory, $lC_Breadcrumb;

    $this->_page_title = $lC_Language->get('success_heading');

    if ($lC_Customer->isLoggedOn() === false) {
      $lC_NavigationHistory->setSnapshot();

      lc_redirect(lc_href_link(FILENAME_ACCOUNT, 'login', 'SSL'));
    }

    if ($lC_Services->isStarted('breadcrumb')) {
      $lC_Breadcrumb->add($lC_Language->get('breadcrumb_checkout_success'), lc_href_link(FILENAME_CHECKOUT, $this->_module, 'SSL'));
    }

    if ($_GET[$this->_module] == 'update') {
      $this->_process();
    }
  }

  /* Private methods */
  function _process() {
    $notify_string = '';

    $products_array = (isset($_POST['notify']) ? $_POST['notify'] : array());

    if (!is_array($products_array)) {
      $products_array = array($products_array);
    }

    $notifications = array();

    foreach ($products_array as $product_id) {
      if (is_numeric($product_id) && !in_array($product_id, $notifications)) {
        $notifications[] = $product_id;
      }
    }

    if (!empty($notifications)) {
      $notify_string = 'action=notify_add&products=' . implode(';', $notifications);
    }

    lc_redirect(lc_href_link(FILENAME_DEFAULT, $notify_string, 'AUTO'));
  }
}
?>