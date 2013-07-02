<?php
/*
  $Id: $

  LoadedCommerce, Open Source E-Commerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2007 LoadedCommerce

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/

  class lC_Actions_cart_add {
    function execute() {
      global $lC_Session, $lC_ShoppingCart, $lC_Product;

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
              lc_redirect(lc_href_link(FILENAME_PRODUCTS, $lC_Product->getKeyword()));

              return false;
            }
          } else {
            lc_redirect(lc_href_link(FILENAME_PRODUCTS, $lC_Product->getKeyword()));

            return false;
          }
        } else {
          $lC_ShoppingCart->add($lC_Product->getID(), $quantity);
        }
      }

      lc_redirect(lc_href_link(FILENAME_CHECKOUT));
    }
  }
?>