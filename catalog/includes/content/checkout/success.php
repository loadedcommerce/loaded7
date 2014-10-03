<?php
/**
  @package    catalog::content
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: success.php v1.0 2013-08-08 datazen $
*/
class lC_Checkout_Success extends lC_Template {

  /* Private variables */
  var $_module = 'success',
      $_group = 'checkout',
      $_page_title,
      $_page_contents = 'checkout_success.php';

  /* Class constructor */
  function lC_Checkout_Success() {
    global $lC_Services, $lC_Language, $lC_Customer, $lC_NavigationHistory, $lC_Breadcrumb, $lC_Vqmod;

    $template_code = (isset($_SESSION['template']['code']) && $_SESSION['template']['code'] != NULL) ? $_SESSION['template']['code'] : 'core';
    
    include_once($lC_Vqmod->modCheck('templates/' . $template_code . '/classes/success.php'));

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
    
    if (isset($_SESSION['lC_Coupons_data'] )) unset($_SESSION['lC_Coupons_data']);
    if (isset($_SESSION['cartSync'] )) unset($_SESSION['cartSync']);
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