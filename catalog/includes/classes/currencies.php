<?php
/*
  $Id: currencies.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

  class lC_Currencies {
    var $currencies = array();

    // class constructor
    function lC_Currencies() {
      global $lC_Database;

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
    function format($number, $currency_code = '', $currency_value = '') {
      global $lC_Language;

      if (empty($currency_code) || ($this->exists($currency_code) == false)) {
        $currency_code = (isset($_SESSION['currency']) ? $_SESSION['currency'] : DEFAULT_CURRENCY);
      }

      if (empty($currency_value) || (is_numeric($currency_value) == false)) {
        $currency_value = $this->currencies[$currency_code]['value'];
      }

      return $this->currencies[$currency_code]['symbol_left'] . number_format(lc_round($number * $currency_value, $this->currencies[$currency_code]['decimal_places']), $this->currencies[$currency_code]['decimal_places'], $lC_Language->getNumericDecimalSeparator(), $lC_Language->getNumericThousandsSeparator()) . $this->currencies[$currency_code]['symbol_right'];
    }

    function formatRaw($number, $currency_code = '', $currency_value = '') {
      if (empty($currency_code) || ($this->exists($currency_code) == false)) {
        $currency_code = (isset($_SESSION['currency']) ? $_SESSION['currency'] : DEFAULT_CURRENCY);
      }

      if (empty($currency_value) || (is_numeric($currency_value) == false)) {
        $currency_value = $this->currencies[$currency_code]['value'];
      }

      return number_format(lc_round($number * $currency_value, $this->currencies[$currency_code]['decimal_places']), $this->currencies[$currency_code]['decimal_places'], '.', '');
    }

    function addTaxRateToPrice($price, $tax_rate, $quantity = 1) {
      global $lC_Tax;

      $price = lc_round($price, $this->currencies[DEFAULT_CURRENCY]['decimal_places']);

      if ( (DISPLAY_PRICE_WITH_TAX == '1') && ($tax_rate > 0) ) {
        $price += lc_round($price * ($tax_rate / 100), $this->currencies[DEFAULT_CURRENCY]['decimal_places']);
      }

      return lc_round($price * $quantity, $this->currencies[DEFAULT_CURRENCY]['decimal_places']);
    }

    function displayPrice($price, $tax_class_id, $quantity = 1, $currency_code = null, $currency_value = null) {
      global $lC_Tax;

      $price = lc_round($price, $this->currencies[DEFAULT_CURRENCY]['decimal_places']);

      if ( (DISPLAY_PRICE_WITH_TAX == '1') && ($tax_class_id > 0) ) {
        $price += lc_round($price * ($lC_Tax->getTaxRate($tax_class_id) / 100), $this->currencies[DEFAULT_CURRENCY]['decimal_places']);
      }

      return $this->format($price * $quantity, $currency_code, $currency_value);
    }

    function displayPriceWithTaxRate($price, $tax_rate, $quantity = 1, $force = false, $currency_code = '', $currency_value = '') {
      global $lC_Tax;

      $price = lc_round($price, $this->currencies[DEFAULT_CURRENCY]['decimal_places']);

      if ( (($force === true) || (DISPLAY_PRICE_WITH_TAX == '1')) && ($tax_rate > 0) ) {
        $price += lc_round($price * ($tax_rate / 100), $this->currencies[DEFAULT_CURRENCY]['decimal_places']);
      }

      return $this->format($price * $quantity, $currency_code, $currency_value);
    }

    function exists($code) {
      if (isset($this->currencies[$code])) {
        return true;
      }

      return false;
    }

    function decimalPlaces($code) {
      if ($this->exists($code)) {
        return $this->currencies[$code]['decimal_places'];
      }

      return false;
    }

    function value($code) {
      if ($this->exists($code)) {
        return $this->currencies[$code]['value'];
      }

      return false;
    }

    function getData() {
      return $this->currencies;
    }

    function getCode($id = '') {
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

    function getID($code = '') {
      if (empty($code)) {
        $code = $_SESSION['currency'];
      }

      return $this->currencies[$code]['id'];
    }
    
    function getSymbolLeft() {
      foreach ($this->currencies as $key => $value) {
        if ($key == DEFAULT_CURRENCY) {
          return $value['symbol_left'];
        }
      }
    }   
    
    function getSymbolRight() {
      foreach ($this->currencies as $key => $value) {
        if ($key == DEFAULT_CURRENCY) {
          return $value['symbol_right'];
        }
      }
    }     
    
  }
?>