<?php
/**
  @package    catalog::modules::order_total
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: terms_handling.php v1.0 2013-08-08 datazen $
*/
class lC_OrderTotal_terms_handling extends lC_OrderTotal {
  var $output;

  var $_title,
      $_code = 'terms_handling',
      $_status = false,
      $_sort_order;

  function lC_OrderTotal_terms_handling() {
    global $lC_Language;

    $this->output = array();

    $this->_title = $lC_Language->get('order_total_terms_handling_title');
    $this->_description = $lC_Language->get('order_total_terms_handling_description');
    $this->_status = (defined('MODULE_ORDER_TOTAL_TERMS_HANDLING_STATUS') && (MODULE_ORDER_TOTAL_TERMS_HANDLING_STATUS == 'true') ? true : false);
    $this->_sort_order = (defined('MODULE_ORDER_TOTAL_TERMS_HANDLING_SORT_ORDER') ? MODULE_ORDER_TOTAL_TERMS_HANDLING_SORT_ORDER : null);
  }

  function process() {
    global $lC_ShoppingCart, $lC_Currencies;
    
    // restrict to payment and confirmation pages
    if (strstr($_SERVER['REQUEST_URI'], 'shipping=process') || strstr($_SERVER['REQUEST_URI'], 'confirmation')) {
      if (utility::isB2B() === true) {
          $value = 1.11;

          $this->output[] = array('title' => 'Handling',
                                  'text' => $lC_Currencies->format($value),
                                  'value' => $value);
        }
    }
  }
}
?>