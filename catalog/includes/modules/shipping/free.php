<?php
/*
  $Id: flat.php 421 2006-02-08 17:53:17Z hpdl $

  LoadedCommerce, Open Source E-Commerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2006 LoadedCommerce

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/

  class lC_Shipping_free extends lC_Shipping {
    var $icon;

    var $_title,
        $_code = 'free',
        $_status = false,
        $_sort_order;

// class constructor
    function lC_Shipping_free() {
      global $lC_Language;

      $this->icon = '';

      $this->_title = $lC_Language->get('shipping_free_title');
      $this->_description = $lC_Language->get('shipping_free_description');
      $this->_status = (defined('MODULE_SHIPPING_FREE_STATUS') && (MODULE_SHIPPING_FREE_STATUS == 'True') ? true : false);
    }

// class methods
    function initialize() {
      global $lC_Database, $lC_ShoppingCart;

      if ($lC_ShoppingCart->getTotal() >= MODULE_SHIPPING_FREE_MINIMUM_ORDER) {
        if ($this->_status === true) {
          if ((int)MODULE_SHIPPING_FREE_ZONE > 0) {
            $check_flag = false;

            $Qcheck = $lC_Database->query('select zone_id from :table_zones_to_geo_zones where geo_zone_id = :geo_zone_id and (zone_country_id = :zone_country_id or zone_country_id = 0) order by zone_id');
            $Qcheck->bindTable(':table_zones_to_geo_zones', TABLE_ZONES_TO_GEO_ZONES);
            $Qcheck->bindInt(':geo_zone_id', MODULE_SHIPPING_FREE_ZONE);
            $Qcheck->bindInt(':zone_country_id', $lC_ShoppingCart->getShippingAddress('country_id'));
            $Qcheck->execute();

            while ($Qcheck->next()) {
              if ($Qcheck->valueInt('zone_id') < 1) {
                $check_flag = true;
                break;
              } elseif ($Qcheck->valueInt('zone_id') == $lC_ShoppingCart->getShippingAddress('zone_id')) {
                $check_flag = true;
                break;
              }
            }

            $this->_status = $check_flag;
          } else {
            $this->_status = true;
          }
        }
      } else {
        $this->_status = false;
      }
    }

    function quote() {
      global $lC_Language, $lC_Currencies;

      $this->quotes = array('id' => $this->_code,
                            'module' => $this->_title,
                            'methods' => array(array('id' => $this->_code,
                                                     'title' => sprintf($lC_Language->get('shipping_free_for_amount'), $lC_Currencies->format(MODULE_SHIPPING_FREE_MINIMUM_ORDER)),
                                                     'cost' => 0)),
                            'tax_class_id' => 0);

      if (!empty($this->icon)) $this->quotes['icon'] = lc_image($this->icon, $this->_title);

      return $this->quotes;
    }
  }
?>
