<?php
/**
  @package    catalog::modules::actions
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: cart_add.php v1.0 2013-08-08 datazen $
*/
class lC_Actions_cart_add {
  function execute() {
    global $lC_Session, $lC_ShoppingCart, $lC_Product, $lC_Language;

    if ( !isset($lC_Product) ) {
      $id = false;

      foreach ( $_GET as $key => $value ) {
        if ( (is_numeric($key) || preg_match('/^[a-zA-Z0-9 -_]*$/', $key)) && ($key != $lC_Session->getName()) ) {
          $id = $key;
        }

        break;
      }

      if ( ($id !== false) && lC_Product::checkEntry($id) ) {
        $lC_Product = new lC_Product($id);
      }
    }

    if ( isset($lC_Product) ) {
      $quantity = (isset($_POST['quantity']) && !empty($_POST['quantity'])) ? (int)$_POST['quantity'] : 1;
      
      
      if ( $lC_Product->hasVariants() ) {
        if ( isset($_POST['variants']) && is_array($_POST['variants']) && !empty($_POST['variants']) ) {
          if ( $lC_Product->variantExists($_POST['variants']) ) {
            $lC_ShoppingCart->add($lC_Product->getProductVariantID($_POST['variants']), $quantity);
          } else {

            lc_redirect(lc_href_link(FILENAME_PRODUCTS, $lC_Product->getKeyword() . '&error=' . urlencode($lC_Language->get('variant_combo_not_available'))));

            return false;
          }
        } else {
          lc_redirect(lc_href_link(FILENAME_PRODUCTS, $lC_Product->getKeyword()));

          return false;
        }
      } else {
        if (isset($_GET['info']) && $_GET['info'] == '1') {
        } else {
          if ($lC_Product->hasSubProducts($lC_Product->getID()) || $lC_Product->hasSimpleOptions()) {
            lc_redirect(lc_href_link(FILENAME_PRODUCTS, $lC_Product->getKeyword()));
          }
        }
        $lC_ShoppingCart->add($lC_Product->getID(), $quantity);
      }
    }

    lc_redirect(lc_href_link(FILENAME_CHECKOUT));
  }
}
?>