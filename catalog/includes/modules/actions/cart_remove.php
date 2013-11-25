<?php
/**
  @package    catalog::modules::actions
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: cart_remove.php v1.0 2013-08-08 datazen $
*/
class lC_Actions_cart_remove {
  function execute() {
    global $lC_Session, $lC_ShoppingCart;

    if ( is_numeric($_GET['item']) ) {
      $lC_ShoppingCart->remove($_GET['item']);
    }

    lc_redirect(lc_href_link(FILENAME_CHECKOUT));
  }
}
?>