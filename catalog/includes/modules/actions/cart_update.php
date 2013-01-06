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