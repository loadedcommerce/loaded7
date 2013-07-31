<?php
/*
  $Id: coupons.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
class lC_Services_coupons_Admin {
  var $title,
      $description,
      $uninstallable = true,
      $depends,
      $precedes;

  public function lC_Services_coupons_Admin() {
    global $lC_Language;

    $lC_Language->loadIniFile('modules/services/coupons.php');

    $this->title = $lC_Language->get('services_coupons_title');
    $this->description = $lC_Language->get('services_coupons_description');
  }

  public function install() {
    global $lC_Database;

    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('Redeem On Cart Page?', 'SERVICE_COUPONS_DISPLAY_ON_CART_PAGE', '1', 'Display the coupons redemption form on the shopping cart page?', '6', '0', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('Redeem On Shipping Page?', 'SERVICE_COUPONS_DISPLAY_ON_SHIPPING_PAGE', '1', 'Display the coupons redemption form on the checkout shipping page?', '6', '0', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('Redeem On Payment Page?', 'SERVICE_COUPONS_DISPLAY_ON_PAYMENT_PAGE', '1', 'Display the coupons redemption form on the checkout payment page?', '6', '0', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('Redeem On Confirmation Page?', 'SERVICE_COUPONS_DISPLAY_ON_CONFIRMATION_PAGE', '1', 'Display the coupons redemption form on the checkout confirmation page?', '6', '0', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))', now())");
  }


  public  function remove() {
    global $lC_Database;

    $lC_Database->simpleQuery("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
  }

  public function keys() {
    return array('SERVICE_COUPONS_DISPLAY_ON_CART_PAGE',
                 'SERVICE_COUPONS_DISPLAY_ON_SHIPPING_PAGE',
                 'SERVICE_COUPONS_DISPLAY_ON_PAYMENT_PAGE',
                 'SERVICE_COUPONS_DISPLAY_ON_CONFIRMATION_PAGE');
  }
}
?>