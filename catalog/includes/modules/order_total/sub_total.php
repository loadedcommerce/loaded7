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

  class lC_OrderTotal_sub_total extends lC_OrderTotal {
    var $output;

    var $_title,
        $_code = 'sub_total',
        $_status = false,
        $_sort_order;

    function lC_OrderTotal_sub_total() {
      global $lC_Language;

      $this->output = array();

      $this->_title = $lC_Language->get('order_total_subtotal_title');
      $this->_description = $lC_Language->get('order_total_subtotal_description');
      $this->_status = (defined('MODULE_ORDER_TOTAL_SUBTOTAL_STATUS') && (MODULE_ORDER_TOTAL_SUBTOTAL_STATUS == 'true') ? true : false);
      $this->_sort_order = (defined('MODULE_ORDER_TOTAL_SUBTOTAL_SORT_ORDER') ? MODULE_ORDER_TOTAL_SUBTOTAL_SORT_ORDER : null);
    }

    function process() {
      global $lC_ShoppingCart, $lC_Currencies;

      $this->output[] = array('title' => $this->_title . ':',
                              'text' => $lC_Currencies->format($lC_ShoppingCart->getSubTotal()),
                              'value' => $lC_ShoppingCart->getSubTotal());
    }
  }
?>
