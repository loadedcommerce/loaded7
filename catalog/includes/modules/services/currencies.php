<?php
/*
  $Id$

  LoadedCommerce, Open Source E-Commerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2007 LoadedCommerce

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/

  class lC_Services_currencies {
    function start() {
      global $lC_Language, $lC_Currencies;

      include('includes/classes/currencies.php');
      $lC_Currencies = new lC_Currencies();

      if ((isset($_SESSION['currency']) == false) || isset($_GET['currency']) || ( (USE_DEFAULT_LANGUAGE_CURRENCY == '1') && ($lC_Currencies->getCode($lC_Language->getCurrencyID()) != $_SESSION['currency']) ) ) {
        if (isset($_GET['currency']) && $lC_Currencies->exists($_GET['currency'])) {
          $_SESSION['currency'] = $_GET['currency'];
        } else {
          $_SESSION['currency'] = (USE_DEFAULT_LANGUAGE_CURRENCY == '1') ? $lC_Currencies->getCode($lC_Language->getCurrencyID()) : DEFAULT_CURRENCY;
        }

        if ( isset($_SESSION['cartID']) ) {
          unset($_SESSION['cartID']);
        }
      }

      return true;
    }

    function stop() {
      return true;
    }
  }
?>
