<?php
/*
  $Id$

  LoadedCommerce, Open Source E-Commerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2006 LoadedCommerce

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/

  class lC_OrderTotal_shipping extends lC_OrderTotal {
    var $output;

    var $_title,
        $_code = 'shipping',
        $_status = false,
        $_sort_order;

    function lC_OrderTotal_shipping() {
      global $lC_Language, $lC_ShoppingCart;

      $this->output = array();

      $this->_title = $lC_Language->get('order_total_shipping_title');
      $this->_description = $lC_Language->get('order_total_shipping_description');
      $this->_status = (defined('MODULE_ORDER_TOTAL_SHIPPING_STATUS') && (MODULE_ORDER_TOTAL_SHIPPING_STATUS == 'true') ? true : false);
      $this->_sort_order = (defined('MODULE_ORDER_TOTAL_SHIPPING_SORT_ORDER') ? MODULE_ORDER_TOTAL_SHIPPING_SORT_ORDER : null);
    }

    function process() {
      global $lC_Tax, $lC_ShoppingCart, $lC_Currencies;

      if ($lC_ShoppingCart->hasShippingMethod()) {
        $lC_ShoppingCart->addToTotal($lC_ShoppingCart->getShippingMethod('cost'));

        if ($lC_ShoppingCart->getShippingMethod('tax_class_id') > 0) {
          $tax = $lC_Tax->getTaxRate($lC_ShoppingCart->getShippingMethod('tax_class_id'), $lC_ShoppingCart->getShippingAddress('country_id'), $lC_ShoppingCart->getShippingAddress('zone_id'));
          $tax_description = $lC_Tax->getTaxRateDescription($lC_ShoppingCart->getShippingMethod('tax_class_id'), $lC_ShoppingCart->getShippingAddress('country_id'), $lC_ShoppingCart->getShippingAddress('zone_id'));

          $lC_ShoppingCart->addTaxAmount($lC_Tax->calculate($lC_ShoppingCart->getShippingMethod('cost'), $tax));
          $lC_ShoppingCart->addTaxGroup($tax_description, $lC_Tax->calculate($lC_ShoppingCart->getShippingMethod('cost'), $tax));

          if (DISPLAY_PRICE_WITH_TAX == '1') {
            $lC_ShoppingCart->addToTotal($lC_Tax->calculate($lC_ShoppingCart->getShippingMethod('cost'), $tax));
            $lC_ShoppingCart->_shipping_method['cost'] += $lC_Tax->calculate($lC_ShoppingCart->getShippingMethod('cost'), $tax);
          }
        }

        $this->output[] = array('title' => $lC_ShoppingCart->getShippingMethod('title') . ':',
                                'text' => $lC_Currencies->format($lC_ShoppingCart->getShippingMethod('cost')),
                                'value' => $lC_ShoppingCart->getShippingMethod('cost'));
      }
    }
  }
?>
