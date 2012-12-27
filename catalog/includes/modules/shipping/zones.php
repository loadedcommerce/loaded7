<?php
/*
  $Id$

  LoadedCommerce, Open Source E-Commerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2006 LoadedCommerce

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/

  class lC_Shipping_zones extends lC_Shipping {
    var $num_zones;

    var $_title,
        $_code = 'zones',
        $_status = true,
        $_sort_order;

// class constructor
    function lC_Shipping_zones() {
      global $lC_Language;

      $this->icon = '';

      $this->_title = $lC_Language->get('shipping_zones_title');
      $this->_description = $lC_Language->get('shipping_zones_description');
      $this->_status = (defined('MODULE_SHIPPING_ZONES_STATUS') && (MODULE_SHIPPING_ZONES_STATUS == 'True') ? true : false);
      $this->_sort_order = (defined('MODULE_SHIPPING_ZONES_SORT_ORDER') ? MODULE_SHIPPING_ZONES_SORT_ORDER : null);

// CUSTOMIZE THIS SETTING FOR THE NUMBER OF ZONES NEEDED
// HPDL; also needs to match the value at zenmerchant/admin/includes/modules/shipping/zones.php
      $this->num_zones = 1;
    }

// class methods
    function initialize() {
      $this->tax_class = MODULE_SHIPPING_ZONES_TAX_CLASS;
    }

    function quote() {
      global $lC_Language, $lC_ShoppingCart, $lC_Weight;

      $dest_country = $lC_ShoppingCart->getShippingAddress('country_iso_code_2');
      $dest_zone = 0;
      $error = false;
      $shipping_method = null;
      $shipping_cost = null;

      $shipping_weight = $lC_Weight->convert($lC_ShoppingCart->getWeight(), SHIPPING_WEIGHT_UNIT, MODULE_SHIPPING_ZONES_WEIGHT_UNIT);

      for ($i=1; $i<=$this->num_zones; $i++) {
        $countries_table = constant('MODULE_SHIPPING_ZONES_COUNTRIES_' . $i);
        $country_zones = preg_split("/[,]/", $countries_table);
        if (in_array($dest_country, $country_zones)) {
          $dest_zone = $i;
          break;
        }
      }

      if ($dest_zone == 0) {
        $error = true;
      } else {
        $shipping = -1;
        $zones_cost = constant('MODULE_SHIPPING_ZONES_COST_' . $dest_zone);

        $zones_table = preg_split("/[:,]/" , $zones_cost);
        $size = sizeof($zones_table);
        for ($i=0; $i<$size; $i+=2) {
          if ($shipping_weight <= $zones_table[$i]) {
            $shipping = $zones_table[$i+1];
            $shipping_method = $lC_Language->get('shipping_zones_method') . ' ' . $dest_country . ' : ' . $lC_Weight->display($lC_ShoppingCart->getWeight(), MODULE_SHIPPING_ZONES_WEIGHT_UNIT);
            break;
          }
        }

        if ($shipping == -1) {
          $shipping_cost = 0;
          $shipping_method = $lC_Language->get('shipping_zones_undefined_rate');
        } else {
          $shipping_cost = ($shipping * $lC_ShoppingCart->numberOfShippingBoxes()) + constant('MODULE_SHIPPING_ZONES_HANDLING_' . $dest_zone);
        }
      }

      $this->quotes = array('id' => $this->_code,
                            'module' => $this->_title,
                            'methods' => array(array('id' => $this->_code,
                                                     'title' => $shipping_method,
                                                     'cost' => $shipping_cost)),
                            'tax_class_id' => $this->tax_class);

      if (!empty($this->icon)) $this->quotes['icon'] = lc_image($this->icon, $this->_title);

      if ($error == true) $this->quotes['error'] = $lC_Language->get('shipping_zones_invalid_zone');

      return $this->quotes;
    }
  }
?>
