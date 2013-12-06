<?php
/**
  @package    catalog::modules::order_total
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: low_order_fee.php v1.0 2013-08-08 datazen $
*/
class lC_OrderTotal_low_order_fee extends lC_OrderTotal {
  var $output;

  var $_title,
      $_code = 'low_order_fee',
      $_status = false,
      $_sort_order;

  function lC_OrderTotal_low_order_fee() {
    global $lC_Language;

    $this->output = array();

    $this->_title = $lC_Language->get('order_total_loworderfee_title');
    $this->_description = $lC_Language->get('order_total_loworderfee_description');
    $this->_status = (defined('MODULE_ORDER_TOTAL_LOWORDERFEE_STATUS') && (MODULE_ORDER_TOTAL_LOWORDERFEE_STATUS == 'true') ? true : false);
    $this->_sort_order = (defined('MODULE_ORDER_TOTAL_LOWORDERFEE_SORT_ORDER') ? MODULE_ORDER_TOTAL_LOWORDERFEE_SORT_ORDER : null);
  }

  function process() {
    global $lC_Tax, $lC_ShoppingCart, $lC_Currencies;

    if (MODULE_ORDER_TOTAL_LOWORDERFEE_LOW_ORDER_FEE == 'true') {
      switch (MODULE_ORDER_TOTAL_LOWORDERFEE_DESTINATION) {
        case 'national':
          if ($lC_ShoppingCart->getShippingAddress('country_id') == STORE_COUNTRY) {
            $pass = true;
          }
          break;

        case 'international':
          if ($lC_ShoppingCart->getShippingAddress('country_id') != STORE_COUNTRY) {
            $pass = true;
          }
          break;

        case 'both':
          $pass = true;
          break;

        default:
          $pass = false;
      }

      if ( ($pass == true) && ($lC_ShoppingCart->getSubTotal() < MODULE_ORDER_TOTAL_LOWORDERFEE_ORDER_UNDER) ) {
        $tax = $lC_Tax->getTaxRate(MODULE_ORDER_TOTAL_LOWORDERFEE_TAX_CLASS, $lC_ShoppingCart->getTaxingAddress('country_id'), $lC_ShoppingCart->getTaxingAddress('zone_id'));
        $tax_description = $lC_Tax->getTaxRateDescription(MODULE_ORDER_TOTAL_LOWORDERFEE_TAX_CLASS, $lC_ShoppingCart->getTaxingAddress('country_id'), $lC_ShoppingCart->getTaxingAddress('zone_id'));

        $lC_ShoppingCart->addTaxAmount($lC_Tax->calculate(MODULE_ORDER_TOTAL_LOWORDERFEE_FEE, $tax));
        $lC_ShoppingCart->addTaxGroup($tax_description, $lC_Tax->calculate(MODULE_ORDER_TOTAL_LOWORDERFEE_FEE, $tax));
        $lC_ShoppingCart->addToTotal(MODULE_ORDER_TOTAL_LOWORDERFEE_FEE + $lC_Tax->calculate(MODULE_ORDER_TOTAL_LOWORDERFEE_FEE, $tax));

        $this->output[] = array('title' => $this->_title . ':',
                                'text' => $lC_Currencies->displayPriceWithTaxRate(MODULE_ORDER_TOTAL_LOWORDERFEE_FEE, $tax),
                                'value' => $lC_Currencies->addTaxRateToPrice(MODULE_ORDER_TOTAL_LOWORDERFEE_FEE, $tax));
      }
    }
  }
}
?>