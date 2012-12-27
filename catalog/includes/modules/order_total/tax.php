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

      foreach ($lC_ShoppingCart->getTaxGroups() as $key => $value) {
        if ($value > 0) {
          if (DISPLAY_PRICE_WITH_TAX == '1') {
            $lC_ShoppingCart->addToTotal($value);
          }

          $this->output[] = array('title' => $key . ':',
                                  'text' => $lC_Currencies->format($value),
                                  'value' => $value);
        }
      }
    }
  }
?>
