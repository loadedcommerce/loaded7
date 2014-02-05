<?php
/**
  @package    catalog::modules::boxes
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: shopping_cart.php v1.0 2013-08-08 datazen $
*/
class lC_Boxes_shopping_cart extends lC_Modules {
  var $_title,
      $_code = 'shopping_cart',
      $_author_name = 'Loaded Commerce',
      $_author_www = 'http://www.loadedcommerce.com',
      $_group = 'boxes';

  public function lC_Boxes_shopping_cart() {
    global $lC_Language;
    
    if (function_exists($lC_Language->injectDefinitions)) $lC_Language->injectDefinitions('modules/' . $_GET['set'] . '/' . $this->_code . '.xml');

    $this->_title = $lC_Language->get('box_shopping_cart_heading');
  }

  public function initialize() {
    global $lC_Language, $lC_ShoppingCart, $lC_Currencies;

    $this->_title_link = lc_href_link(FILENAME_CHECKOUT, null, 'SSL');

    if ($lC_ShoppingCart->hasContents()) {
      
      $this->_content = '';
      foreach ($lC_ShoppingCart->getProducts() as $products) {
        $this->_content .= '<li class="box-shopping-cart-product">' . $products['quantity'] . '&nbsp;x&nbsp;' . lc_link_object(lc_href_link(FILENAME_PRODUCTS, $products['keyword']), $products['name']) . '</li>';
      }
      $this->_content .= '<li class="box-shopping-cart-subtotal">' . $lC_Language->get('box_shopping_cart_subtotal') . ' ' . $lC_Currencies->format($lC_ShoppingCart->getSubTotal()) . '</li>';
      
    } else {
      $this->_content = '<li class="box-shopping-cart-empty">' . $lC_Language->get('box_shopping_cart_empty') . '</li>';
    }
  }
}
?>