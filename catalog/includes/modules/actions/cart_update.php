<?php
/**
  @package    catalog::modules::actions
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: cart_update.php v1.0 2013-08-08 datazen $
*/
class lC_Actions_cart_update {
  function execute() {
    global $lC_ShoppingCart;
    // update cart qty
    if ( isset($_POST['products']) && is_array($_POST['products']) && !empty($_POST['products']) ) {         
      foreach ( $_POST['products'] as $item_id => $quantity ) {
        if ( !is_numeric($item_id) || !is_numeric($quantity) ) {
          return false;
        }

        $lC_ShoppingCart->update($item_id, $quantity);
      }
    }
    // remove cart items
    if ( isset($_POST['delete']) && is_array($_POST['delete']) && !empty($_POST['delete']) ) {         
      foreach ( $_POST['delete'] as $item_id => $confirm ) {
        if ( !is_numeric($item_id) ) {
          return false;
        }
        $lC_ShoppingCart->remove($item_id);
      }
    }      
    
    lc_redirect(lc_href_link(FILENAME_CHECKOUT));
  }
}
?>