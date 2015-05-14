<?php
/**
  @package    catalog::modules::order_total
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: total.php v1.0 2013-08-08 datazen $
*/
class lC_OrderTotal_store_credit extends lC_OrderTotal {
  var $output;

  var $_title,
      $_code = 'store_credit',
      $_status = false,
      $_sort_order;

  function lC_OrderTotal_store_credit() {
    global $lC_Language;

    $this->output = array();

    $this->_title = $lC_Language->get('order_total_store_credit_title');
    $this->_description = $lC_Language->get('order_total_store_credit_description');
    $this->_status = (defined('MODULE_ORDER_TOTAL_STORE_CREDIT_STATUS') && (MODULE_ORDER_TOTAL_STORE_CREDIT_STATUS == 'true') && (isset($_SESSION['use_credit'])) ? true : false);
    $this->_sort_order = (defined('MODULE_ORDER_TOTAL_STORE_CREDIT_SORT_ORDER') ? MODULE_ORDER_TOTAL_STORE_CREDIT_SORT_ORDER : null);
  }

  function process() {
    global $lC_Customer;
    
    $this->output[] = array('title' => $this->_title . ':',
                            'text' => '<b>' . $lC_Customer->getStoreCredit(true) . '</b>',
                            'value' => $lC_Customer->getStoreCredit(true));
  }
}
?>