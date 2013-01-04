<?php
/*
  $Id: $

  LoadedCommerce, Open Source E-Commerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2006 LoadedCommerce

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/

  class lC_Boxes_checkout_trail extends lC_Modules {
    var $_title,
        $_code = 'checkout_trail',
        $_author_name = 'LoadedCommerce',
        $_author_www = 'http://www.loadedcommerce.com',
        $_group = 'boxes';

    function lC_Boxes_checkout_trail() {
      global $lC_Language;
      
      if (function_exists($lC_Language->injectDefinitions)) $lC_Language->injectDefinitions('modules/' . $_GET['set'] . '/' . $this->_code . '.xml');

      $this->_title = $lC_Language->get('box_ordering_steps_heading');
    }

    function initialize() {
      global $lC_Language, $lC_Template, $lC_ShoppingCart;

      $steps = array();

      if ($lC_ShoppingCart->getContentType() != 'virtual') {
        $steps[] = array('title' => $lC_Language->get('box_ordering_steps_delivery'),
                         'code' => 'shipping',
                         'active' => (($lC_Template->getModule() == 'shipping') || ($lC_Template->getModule() == 'shipping_address') ? true : false));
      }

      $steps[] = array('title' => $lC_Language->get('box_ordering_steps_payment'),
                       'code' => 'payment',
                       'active' => (($lC_Template->getModule() == 'payment') || ($lC_Template->getModule() == 'payment_address') ? true : false));

      $steps[] = array('title' => $lC_Language->get('box_ordering_steps_confirmation'),
                       'code' => 'confirmation',
                       'active' => ($lC_Template->getModule() == 'confirmation' ? true : false));

      $steps[] = array('title' => $lC_Language->get('box_ordering_steps_complete'),
                       'active' => ($lC_Template->getModule() == 'success' ? true : false));


      $content = '<ul class="category">';

      $counter = 0;
      foreach ($steps as $step) {
        $counter++;

        if (isset($step['code'])) {
          $content .= '<li>' . lc_link_object(lc_href_link(FILENAME_CHECKOUT, $step['code'], 'SSL'), $step['title']) . '</li>';
        } else {
          $content .= '<li>' . lc_link_object(lc_href_link('#'), $step['title']) . '</li>';
        }
      }

      $content .= '</ul>';

      $this->_content = $content;
    }
  }
?>
