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
  * @param string $_GET['item'] The item to delete
  * @access public
  * @return string
  */
  public static function deleteItem() {
    global $lC_ShoppingCart;

    $result = array();
    
    $lC_ShoppingCart->remove($_GET['item']);
    
    echo json_encode(self::_getCartHtml());
  }
 /*
  * Update Qty and return cart HTML
  *
  * @param string $_GET['q'] The search string
  * @access public
  * @return json
  */
  public static function update() {
    global $lC_ShoppingCart;

    $result = array();
    
    $data = $lC_ShoppingCart->update($_GET['item'], $_GET['quantity']);
    
     echo json_encode(self::_getCartHtml($data));
  }
    
  
  protected static function _getCartHtml($data = null) {
    global $lC_Database, $lC_Language, $lC_Currencies, $lC_Customer, $lC_ShoppingCart, $lC_Image;

    $result = array();
    
    // create the new order total text
    $otText = '';
    foreach ($lC_ShoppingCart->getOrderTotals() as $module) {
      $title = (strstr($module['title'], '(')) ? substr($module['title'], 0, strpos($module['title'], '(')) . ':' : $module['title'];
      $class = str_replace(':', '', $title);
      $class = 'ot-' . strtolower(str_replace(' ', '-', $class));
      
      $otText .= '<div class="clearfix">' .
                 '  <span class="pull-left ' . $class . '">' . $title . '</span>' .
                 '  <span class="pull-right ' . $class . '">' . $module['text'] . '</span>' .
                 '</div>';
    }
    
    $result['priceData'] = $data;
    $result['otText'] = $otText;
    
    if ($lC_ShoppingCart->hasContents()) {
      $result['redirect'] = '0';
    } else {
      $result['redirect'] = '1';
    }     
    
    if (is_array($result)) $result['rpcStatus'] = '1';
    
    return $result;
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