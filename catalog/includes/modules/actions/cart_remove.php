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
