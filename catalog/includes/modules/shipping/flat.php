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

  class lC_Shipping_flat extends lC_Shipping {
    var $icon;

    var $_title,
        $_code = 'flat',
        $_status = false,
        $_sort_order;

// class constructor
    function lC_Shipping_flat() {
      global $lC_Language;

      $this->icon = '';

      $this->_title = $lC_Language->get('shipping_flat_title');
      $this->_description = $lC_Language->get('shipping_flat_description');
      $this->_status = (defined('MODULE_SHIPPING_FLAT_STATUS') && (MODULE_SHIPPING_FLAT_STATUS == 'True') ? true : false);
      $this->_sort_order = (defined('MODULE_SHIPPING_FLAT_SORT_ORDER') ? MODULE_SHIPPING_FLAT_SORT_ORDER : null);
    }

// class methods
    function initialize() {
      global $lC_Database, $lC_ShoppingCart;

      $this->tax_class = MODULE_SHIPPING_FLAT_TAX_CLASS;

      if ( ($this->_status === true) && ((int)MODULE_SHIPPING_FLAT_ZONE > 0) ) {
        $check_flag = false;

        $Qcheck = $lC_Database->query('select zone_id from :table_zones_to_geo_zones where geo_zone_id = :geo_zone_id and zone_country_id = :zone_country_id order by zone_id');
        $Qcheck->bindTable(':table_zones_to_geo_zones', TABLE_ZONES_TO_GEO_ZONES);
        $Qcheck->bindInt(':geo_zone_id', MODULE_SHIPPING_FLAT_ZONE);
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

        if ($check_flag == false) {
          $this->_status = false;
        }
      }
    }

    function quote() {
      global $lC_Language;

      $this->quotes = array('id' => $this->_code,
                            'module' => $this->_title,
                            'methods' => array(array('id' => $this->_code,
                                                     'title' => $lC_Language->get('shipping_flat_method'),
                                                     'cost' => MODULE_SHIPPING_FLAT_COST)),
                            'tax_class_id' => $this->tax_class);

      if (!empty($this->icon)) $this->quotes['icon'] = lc_image($this->icon, $this->_title);

      return $this->quotes;
    }
  }
?>
