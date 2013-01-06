<?php
/*
  $Id: tax.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

  require('../includes/classes/tax.php');

  class lC_Tax_Admin extends lC_Tax {
    var $tax_rates;

    // class constructor
    function lC_Tax_Admin() {
      $this->tax_rates = array();
    }

    // class methods
    function getTaxRate($class_id, $country_id = null, $zone_id = null) {
      global $lC_Database;

      if (empty($country_id) && empty($zone_id)) {
        $country_id = STORE_COUNTRY;
        $zone_id = STORE_ZONE;
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
  }
?>