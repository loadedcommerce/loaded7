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

  class lC_Services_core {
    function start() {
      global $lC_Customer, $lC_Tax, $lC_Weight, $lC_ShoppingCart, $lC_NavigationHistory, $lC_Image;

      include('includes/classes/template.php');
      include('includes/classes/modules.php');
      include('includes/classes/category.php');
      include('includes/classes/variants.php');
      include('includes/classes/product.php');
      include('includes/classes/datetime.php');
      include('includes/classes/xml.php');
      include('includes/classes/mail.php');
      include('includes/classes/address.php');

      include('includes/classes/customer.php');
      $lC_Customer = new lC_Customer();

      include('includes/classes/tax.php');
      $lC_Tax = new lC_Tax();

      include('includes/classes/weight.php');
      $lC_Weight = new lC_Weight();

      include('includes/classes/shopping_cart.php');
      $lC_ShoppingCart = new lC_ShoppingCart();
      $lC_ShoppingCart->refresh();

      include('includes/classes/navigation_history.php');
      $lC_NavigationHistory = new lC_NavigationHistory(true);

      include('includes/classes/image.php');
      $lC_Image = new lC_Image();

      return true;
    }

    function stop() {
      return true;
    }
  }
?>
