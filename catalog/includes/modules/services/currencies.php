<?php
/**
  $Id: currencies.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
class lC_Services_currencies {
  function start() {
    global $lC_Language, $lC_Currencies, $lC_Vqmod;

    include($lC_Vqmod->modCheck('includes/classes/currencies.php'));
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