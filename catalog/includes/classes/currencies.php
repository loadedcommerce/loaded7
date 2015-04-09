<?php
/**
  @package    catalog::classes
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: currencies.php v1.0 2013-08-08 datazen $
*/
class lC_Currencies {
  var $currencies = array();

  // class constructor
  public function lC_Currencies() {
    global $lC_Database, $lC_Language;

    $Qcurrencies = $lC_Database->query('select * from :table_currencies');
    $Qcurrencies->bindTable(':table_currencies', TABLE_CURRENCIES);
    $Qcurrencies->setCache('currencies');
    $Qcurrencies->execute();

    while ($Qcurrencies->next()) {
      $this->currencies[$Qcurrencies->value('code')] = array('id' => $Qcurrencies->valueInt('currencies_id'),
                                                             'title' => $Qcurrencies->value('title'),
                                                             'symbol_left' => $Qcurrencies->value('symbol_left'),
                                                             'symbol_right' => $Qcurrencies->value('symbol_right'),
                                                             'decimal_places' => $Qcurrencies->valueInt('decimal_places'),
                                                             'value' => $Qcurrencies->valueDecimal('value'));
    }

    $Qcurrencies->freeResult();
  }

  // class methods
  public function format($number, $currency_code = '', $currency_value = '') {
    global $lC_Language;

    if (empty($currency_code) || ($this->exists($currency_code) == false)) {
      $currency_code = (isset($_SESSION['currency']) ? $_SESSION['currency'] : DEFAULT_CURRENCY);
    }

    if (empty($currency_value) || (is_numeric($currency_value) == false)) {
      $currency_value = $this->currencies[$currency_code]['value'];
    }

    return $this->currencies[$currency_code]['symbol_left'] . number_format(lc_round($number * $currency_value, $this->currencies[$currency_code]['decimal_places']), $this->currencies[$currency_code]['decimal_places'], $lC_Language->getNumericDecimalSeparator(), $lC_Language->getNumericThousandsSeparator()) . $this->currencies[$currency_code]['symbol_right'];
  }

  public function formatRaw($number, $currency_code = '', $currency_value = '') {
    if (empty($currency_code) || ($this->exists($currency_code) == false)) {
      $currency_code = (isset($_SESSION['currency']) ? $_SESSION['currency'] : DEFAULT_CURRENCY);
    }

    if (empty($currency_value) || (is_numeric($currency_value) == false)) {
      $currency_value = $this->currencies[$currency_code]['value'];
    }

    return number_format(lc_round($number * $currency_value, $this->currencies[$currency_code]['decimal_places']), $this->currencies[$currency_code]['decimal_places'], '.', '');
  }

  public function addTaxRateToPrice($price, $tax_rate, $quantity = 1) {
    global $lC_Tax;
    $price = $this->santizePrice($price);
    $price = lc_round($price, $this->currencies[DEFAULT_CURRENCY]['decimal_places']);

    if ( (DISPLAY_PRICE_WITH_TAX == '1' || $_SESSION['localization']['show_tax'] == 1) && ($tax_rate > 0) ) {
      $price += lc_round($price * ($tax_rate / 100), $this->currencies[DEFAULT_CURRENCY]['decimal_places']);
    }
    return lc_round($price * $quantity, $this->currencies[DEFAULT_CURRENCY]['decimal_places']);
  }

  public function displayPrice($price, $tax_class_id, $quantity = 1, $currency_code = null, $currency_value = null) {
    global $lC_Tax;
    $price = $this->santizePrice($price);
    $price = lc_round($price, $this->currencies[DEFAULT_CURRENCY]['decimal_places']);

    if ( (DISPLAY_PRICE_WITH_TAX == '1' || $_SESSION['localization']['show_tax'] == 1) && ($tax_class_id > 0) ) {
      $price += lc_round($price * ($lC_Tax->getTaxRate($tax_class_id) / 100), $this->currencies[DEFAULT_CURRENCY]['decimal_places']);
    }

    return $this->format($price * $quantity, $currency_code, $currency_value);
  }

  public function displayPriceWithTaxRate($price, $tax_rate, $quantity = 1, $force = false, $currency_code = '', $currency_value = '') {
    global $lC_Tax;
    $price = $this->santizePrice($price);
    $price = lc_round($price, $this->currencies[DEFAULT_CURRENCY]['decimal_places']);

    if ( (($force === true) || (DISPLAY_PRICE_WITH_TAX == '1' || $_SESSION['localization']['show_tax'] == 1)) && ($tax_rate > 0) ) {
      $price += lc_round($price * ($tax_rate / 100), $this->currencies[DEFAULT_CURRENCY]['decimal_places']);
    }
    return $this->format($price * $quantity, $currency_code, $currency_value);
  }
  
  public function santizePrice($price){
    //santize the prise string 
    return(str_replace(",", "", $price));
  }

  public function exists($code) {
    if (isset($this->currencies[$code])) {
      return true;
    }

    return false;
  }

  public function decimalPlaces($code) {
    if ($this->exists($code)) {
      return $this->currencies[$code]['decimal_places'];
    }

    return false;
  }

  public function value($code) {
    if ($this->exists($code)) {
      return $this->currencies[$code]['value'];
    }

    return false;
  }

  public function getData() {
    return $this->currencies;
  }

  public function getCode($id = '') {
    if (is_numeric($id)) {
      foreach ($this->currencies as $key => $value) {
        if ($value['id'] == $id) {
          return $key;
        }
      }
    } else {
      return $_SESSION['currency'];
    }
  }

  public function getID($code = '') {
    if (empty($code)) {
      $code = $_SESSION['currency'];
    }

    return $this->currencies[$code]['id'];
  }
  
  public function getSymbolLeft() {
    foreach ($this->currencies as $key => $value) {
      if ($key == DEFAULT_CURRENCY) {
        return $value['symbol_left'];
      }
    }
  }   
  
  public function getSymbolRight() {
    foreach ($this->currencies as $key => $value) {
      if ($key == DEFAULT_CURRENCY) {
        return $value['symbol_right'];
      }
    }
  }
  
  public function getSessionSymbolLeft() {
    foreach ($this->currencies as $key => $value) {
      if ($key == $_SESSION['currency']) {
        return $value['symbol_left'];
      }
    }
  }
  
  public function getSessionSymbolRight() {
    foreach ($this->currencies as $key => $value) {
      if ($key == $_SESSION['currency']) {
        return $value['symbol_right'];
      }
    }
  }     
}
?>
