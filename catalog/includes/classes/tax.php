<?php
/*
  $Id: tax.php v1.0 2011-11-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

  class lC_Tax {
    var $tax_rates;

    // class constructor
    function lC_Tax() {
      $this->tax_rates = array();
    }

    // class methods
    function getTaxRate($class_id, $country_id = -1, $zone_id = -1) {
      global $lC_Database, $lC_ShoppingCart;

      if ( ($country_id == -1) && ($zone_id == -1) ) {
        $country_id = $lC_ShoppingCart->getTaxingAddress('country_id');
        $zone_id = $lC_ShoppingCart->getTaxingAddress('zone_id');
      }

      if (isset($this->tax_rates[$class_id][$country_id][$zone_id]['rate']) == false) {
        $Qtax = $lC_Database->query('select sum(tax_rate) as tax_rate from :table_tax_rates tr left join :table_zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join :table_geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = 0 or za.zone_country_id = :zone_country_id) and (za.zone_id is null or za.zone_id = 0 or za.zone_id = :zone_id) and tr.tax_class_id = :tax_class_id group by tr.tax_priority');
        $Qtax->bindTable(':table_tax_rates', TABLE_TAX_RATES);
        $Qtax->bindTable(':table_zones_to_geo_zones', TABLE_ZONES_TO_GEO_ZONES);
        $Qtax->bindTable(':table_geo_zones', TABLE_GEO_ZONES);
        $Qtax->bindInt(':zone_country_id', $country_id);
        $Qtax->bindInt(':zone_id', $zone_id);
        $Qtax->bindInt(':tax_class_id', $class_id);
        $Qtax->execute();

        if ($Qtax->numberOfRows()) {
          $tax_multiplier = 1.0;
          while ($Qtax->next()) {
            $tax_multiplier *= 1.0 + ($Qtax->value('tax_rate') / 100);
          }

          $tax_rate = ($tax_multiplier - 1.0) * 100;
        } else {
          $tax_rate = 0;
        }

        $this->tax_rates[$class_id][$country_id][$zone_id]['rate'] = $tax_rate;
      }

      return $this->tax_rates[$class_id][$country_id][$zone_id]['rate'];
    }

    function getTaxRateDescription($class_id, $country_id, $zone_id) {
      global $lC_Database, $lC_Language;

      if (isset($this->tax_rates[$class_id][$country_id][$zone_id]['description']) == false) {
        $Qtax = $lC_Database->query('select tax_description from :table_tax_rates tr left join :table_zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join :table_geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = 0 or za.zone_country_id = :zone_country_id) and (za.zone_id is null or za.zone_id = 0 or za.zone_id = :zone_id) and tr.tax_class_id = :tax_class_id group by tr.tax_priority');
        $Qtax->bindTable(':table_tax_rates', TABLE_TAX_RATES);
        $Qtax->bindTable(':table_zones_to_geo_zones', TABLE_ZONES_TO_GEO_ZONES);
        $Qtax->bindTable(':table_geo_zones', TABLE_GEO_ZONES);
        $Qtax->bindInt(':zone_country_id', $country_id);
        $Qtax->bindInt(':zone_id', $zone_id);
        $Qtax->bindInt(':tax_class_id', $class_id);
        $Qtax->execute();

        if ($Qtax->numberOfRows()) {
          $tax_description = '';

          while ($Qtax->next()) {
            $tax_description .= $Qtax->value('tax_description') . ' + ';
          }

          $this->tax_rates[$class_id][$country_id][$zone_id]['description'] = substr($tax_description, 0, -3);
        } else {
          $this->tax_rates[$class_id][$country_id][$zone_id]['description'] = $lC_Language->get('tax_rate_unknown');
        }
      }

      return $this->tax_rates[$class_id][$country_id][$zone_id]['description'];
    }

    function calculate($price, $tax_rate) {
      global $lC_Currencies;

      return lc_round($price * $tax_rate / 100, $lC_Currencies->currencies[DEFAULT_CURRENCY]['decimal_places']);
    }

    function displayTaxRateValue($value, $padding = null) {
      if (!is_numeric($padding)) {
        $padding = TAX_DECIMAL_PLACES;
      }

      if (strpos($value, '.') !== false) {
        while (true) {
          if (substr($value, -1) == '0') {
            $value = substr($value, 0, -1);
          } else {
            if (substr($value, -1) == '.') {
              $value = substr($value, 0, -1);
            }

            break;
          }
        }
      }

      if ($padding > 0) {
        if (($decimal_pos = strpos($value, '.')) !== false) {
          $decimals = strlen(substr($value, ($decimal_pos+1)));

          for ($i=$decimals; $i<$padding; $i++) {
            $value .= '0';
          }
        } else {
          $value .= '.';

          for ($i=0; $i<$padding; $i++) {
            $value .= '0';
          }
        }
      }

      return $value . '%';
    }
  }
?>