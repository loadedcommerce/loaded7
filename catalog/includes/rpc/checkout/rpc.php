<?php
/**
  @package    catalog::checkout
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: rpc.php v1.0 2013-08-08 datazen $
*/ 
global $lC_Vqmod;
//require_once($lC_Vqmod->modCheck('includes/classes/coupons.php'));
//require_once($lC_Vqmod->modCheck('includes/classes/shopping_cart.php'));

class lC_Checkout_rpc {
 /*
  * Delete item from shopping cart page
  *
  * @param string $_GET['q'] The search string
  * @access public
  * @return json
  */
  public static function deleteItem() {
    global $lC_Database, $lC_Language, $lC_Currencies, $lC_Customer, $lC_ShoppingCart, $lC_Image;

    $result = array();
    
    $lC_ShoppingCart->remove($_GET['item']);
   
    // crete the new order total text
    $otText = '';
    foreach ($lC_ShoppingCart->getOrderTotals() as $module) {
      $otText .= '<tr>' .
                 ' <td class="align_left' . (($module['code'] == 'sub_total') ? ' sc_sub_total' : null) . (($module['code'] == 'total') ? ' sc_total' : null) . '" style="padding-right:10px;">' . $module['title'] . '</td>' .
                 ' <td class="align_right' . (($module['code'] == 'sub_total') ? ' sc_sub_total' : null) . (($module['code'] == 'total') ? ' sc_total' : null) . '">' . $module['text'] . '</td>' .
                 '</tr>';
    }
    
    $result['otText'] = $otText;
    
    // create the new mini-cart text
    $mcText = '';
    if ($lC_ShoppingCart->hasContents()) {
      $mcText .= '<a href="#" class="minicart_link">' . 
                  '  <span class="item"><b>' . $lC_ShoppingCart->numberOfItems() . '</b> ' . ($lC_ShoppingCart->numberOfItems() > 1 ? strtoupper($lC_Language->get('text_cart_items')) : strtoupper($lC_Language->get('text_cart_item'))) . ' /</span> <span class="price"><b>' . $lC_Currencies->format($lC_ShoppingCart->getSubTotal()) . '</b></span>' . 
                  '</a>' .
                  '<div class="cart_drop">' .
                  '  <span class="darw"></span>' .
                  '  <ul>';

      foreach ($lC_ShoppingCart->getProducts() as $products) {
        $mcText .= '<li>' . $lC_Image->show($products['image'], $products['name'], null, 'mini') . lc_link_object(lc_href_link(FILENAME_PRODUCTS, $products['keyword']), '(' . $products['quantity'] . ') x ' . $products['name']) . ' <span class="price">' . $lC_Currencies->format($products['price']) . '</span></li>';
      }           
      
      $mcText .= '</ul>' .
            '<div class="cart_bottom">' .
              '<div class="subtotal_menu">' .
                '<small>' . $lC_Language->get('box_shopping_cart_subtotal') . '</small>' .
                '<big>' . $lC_Currencies->format($lC_ShoppingCart->getSubTotal()) . '</big>' .
              '</div>' .
              '<a href="' . lc_href_link(FILENAME_CHECKOUT, null, 'SSL') . '">' . $lC_Language->get('text_checkout') . '</a>' .
            '</div>' .
          '</div>';
      $result['redirect'] = '0';
    } else {
      $mcText .= $lC_Language->get('box_shopping_cart_empty');
      $result['redirect'] = '1';
    } 
    
    $result['mcText'] = $mcText;
    
    if (is_array($result)) $result['rpcStatus'] = '1';
    
    echo json_encode($result);
  }     
 /*
  * Add a coupon to the stack
  *
  * @access public
  * @return json
  */
  public static function addCoupon() {
    global $lC_Coupons;
    
    $result = array();
    if (defined('MODULE_SERVICES_INSTALLED') && in_array('coupons', explode(';', MODULE_SERVICES_INSTALLED)) && isset($lC_Coupons)) {
      $result = $lC_Coupons->addEntry($_GET['code']);
    }
    
    echo json_encode($result);
  } 
 /*
  * Remove a coupon to the stack
  *
  * @access public
  * @return json
  */
  public static function removeCoupon() {
    global $lC_Coupons;
    
    $result = array();
    if (defined('MODULE_SERVICES_INSTALLED') && in_array('coupons', explode(';', MODULE_SERVICES_INSTALLED)) && isset($lC_Coupons)) {
      if ($lC_Coupons->removeEntry($_GET['code'])) {
        $result['rpcStatus'] = '1';
      }
    }
    
    echo json_encode($result);
  }     
 /*
  * Add order comments to session
  *
  * @access public
  * @return json
  */
  public static function sendOrderCommentsToSession() {
    
    $result = array();
    if (isset($_GET['comments']) && $_GET['comments'] != '') {
      $_SESSION['comments'] = $_GET['comments'];
      $result['rpcStatus'] = '1';
    }
    
    echo json_encode($result);
  }
}
?>