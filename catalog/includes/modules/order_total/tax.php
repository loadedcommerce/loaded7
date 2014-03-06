<?php
/**
  @package    catalog::modules::order_total
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: tax.php v1.0 2013-08-08 datazen $
*/
class lC_OrderTotal_tax extends lC_OrderTotal {
  var $output;

  var $_title,
      $_code = 'tax',
      $_status = false,
      $_sort_order;

  function lC_OrderTotal_tax() {
    global $lC_Language;

    $this->output = array();

    $this->_title = $lC_Language->get('order_total_tax_title');
    $this->_description = $lC_Language->get('order_total_tax_description');
    $this->_status = (defined('MODULE_ORDER_TOTAL_TAX_STATUS') && (MODULE_ORDER_TOTAL_TAX_STATUS == 'true') ? true : false);
    $this->_sort_order = (defined('MODULE_ORDER_TOTAL_TAX_SORT_ORDER') ? MODULE_ORDER_TOTAL_TAX_SORT_ORDER : null);
  }

  function process() {
    global $lC_ShoppingCart, $lC_Currencies;

    if (DISPLAY_PRICE_WITH_TAX == '1') return;
    
    foreach ($lC_ShoppingCart->getTaxGroups() as $key => $value) { 
      if ($value > 0) {
        $this->output[] = array('title' => $key . ':',
                                'text' => $lC_Currencies->format($value),
                                'value' => $value);
      }
    }
  }
}
?>