<?php
/**  
  $Id: lC_Shipping_free.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     Loaded Commerce Team
  @copyright  (c) 2013 Loaded Commerce Team
  @license    http://loadedcommerce.com/license.html
*/
class lC_Shipping_free extends lC_Shipping {

  public $icon = '';
  
  protected $_title,
            $_code = 'free',
            $_status = false,
            $_sort_order = 0;

  // class constructor
  public function lC_Shipping_free() {
    global $lC_Language;

    $this->_title = $lC_Language->get('shipping_free_title');
    $this->_description = $lC_Language->get('shipping_free_description');
    $this->_status = (defined('ADDONS_SHIPPING_FREE_SHIPPING_STATUS') && (ADDONS_SHIPPING_FREE_SHIPPING_STATUS == '1') ? true : false);
  }

  // class methods
  public function initialize() {
    global $lC_Database, $lC_ShoppingCart;

    if ($lC_ShoppingCart->getTotal() >= ADDONS_SHIPPING_FREE_SHIPPING_MINIMUM_ORDER) {
      if ($this->_status === true) {
        if ((int)ADDONS_SHIPPING_FREE_SHIPPING_ZONE > 0) {
          $check_flag = false;

          $Qcheck = $lC_Database->query('select zone_id from :table_zones_to_geo_zones where geo_zone_id = :geo_zone_id and (zone_country_id = :zone_country_id or zone_country_id = 0) order by zone_id');
          $Qcheck->bindTable(':table_zones_to_geo_zones', TABLE_ZONES_TO_GEO_ZONES);
          $Qcheck->bindInt(':geo_zone_id', ADDONS_SHIPPING_FREE_SHIPPING_ZONE);
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

  public function quote() {
    global $lC_Language, $lC_Currencies;

    $this->quotes = array('id' => $this->_code,
                          'module' => $this->_title,
                          'methods' => array(array('id' => $this->_code,
                                                   'title' => sprintf($lC_Language->get('shipping_free_for_amount'), $lC_Currencies->format(ADDONS_SHIPPING_FREE_SHIPPING_MINIMUM_ORDER)),
                                                   'cost' => 0)),
                          'tax_class_id' => 0);

    if (!empty($this->icon)) $this->quotes['icon'] = lc_image($this->icon, $this->_title);

    return $this->quotes;
  }
}
?>