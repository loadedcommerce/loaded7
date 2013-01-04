<?php
/*
  $Id: shopping_cart.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

  class lC_Boxes_shopping_cart extends lC_Modules {
    var $_title,
        $_code = 'shopping_cart',
        $_author_name = 'LoadedCommerce',
        $_author_www = 'http://www.loadedcommerce.com',
        $_group = 'boxes';

    function lC_Boxes_shopping_cart() {
      global $lC_Language;
      
      if (function_exists($lC_Language->injectDefinitions)) $lC_Language->injectDefinitions('modules/' . $_GET['set'] . '/' . $this->_code . '.xml');

      $this->_title = $lC_Language->get('box_shopping_cart_heading');
    }

    function initialize() {
      global $lC_Language, $lC_ShoppingCart, $lC_Currencies;

      $this->_title_link = lc_href_link(FILENAME_CHECKOUT, null, 'SSL');

      if ($lC_ShoppingCart->hasContents()) {
        $this->_content = '<ul class="category">';

        foreach ($lC_ShoppingCart->getProducts() as $products) {
          $this->_content .= '    <li><span style="float:left;">' . $products['quantity'] . '&nbsp;x&nbsp;</span>' . lc_link_object(lc_href_link(FILENAME_PRODUCTS, $products['keyword']), $products['name']) . '</li>';
        }

        $this->_content .= '</ul>' .
                           '<p style="text-align:right;">' . $lC_Language->get('box_shopping_cart_subtotal') . ' ' . $lC_Currencies->format($lC_ShoppingCart->getSubTotal()) . '</p>';
      } else {
        $this->_content = $lC_Language->get('box_shopping_cart_empty');
      }
    }
  }
?>