<?php
/**
  @package    catalog::content
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: orders.php v1.0 2013-08-08 datazen $
*/
class lC_Account_Orders extends lC_Template {

  /* Private variables */
  var $_module = 'orders',
      $_group = 'account',
      $_page_title,
      $_page_contents = 'account_history.php',
      $_page_image = 'table_background_history.gif';

  /* Class constructor */
  public function lC_Account_Orders() {
    global $lC_Services, $lC_Language, $lC_Customer, $lC_Breadcrumb, $lC_Vqmod;

    require($lC_Vqmod->modCheck('includes/classes/order.php'));

    $this->_page_title = $lC_Language->get('orders_heading');

    $lC_Language->load('order');

    if ($lC_Services->isStarted('breadcrumb')) {
      $lC_Breadcrumb->add($lC_Language->get('breadcrumb_my_orders'), lc_href_link(FILENAME_ACCOUNT, $this->_module, 'SSL'));

      if (is_numeric($_GET[$this->_module])) {
        $lC_Breadcrumb->add(sprintf($lC_Language->get('breadcrumb_order_information'), $_GET[$this->_module]), lc_href_link(FILENAME_ACCOUNT, $this->_module . '=' . $_GET[$this->_module], 'SSL'));
      }
    }

    if (is_numeric($_GET[$this->_module])) {
      if (lC_Order::getCustomerID($_GET[$this->_module]) !== $lC_Customer->getID()) {
        lc_redirect(lc_href_link(FILENAME_ACCOUNT, $this->_module, 'SSL'));
      }

      $this->_page_title = sprintf($lC_Language->get('order_information_heading'), $_GET[$this->_module]);
      $this->_page_contents = 'account_history_info.php';
    }
  }
}
?>