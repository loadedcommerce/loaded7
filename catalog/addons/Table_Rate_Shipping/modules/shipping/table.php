<?php
/**  
  $Id: lC_Shipping_table.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     Loaded Commerce Team
  @copyright  (c) 2013 Loaded Commerce Team
  @license    http://loadedcommerce.com/license.html
*/
class lC_Shipping_table extends lC_Shipping {
  
  public $icon = '';
  
  protected $_title,
            $_code = 'table',
            $_status = false,
            $_sort_order;

  // class constructor
  public function lC_Shipping_table() {
    global $lC_Language;

    $this->_title = $lC_Language->get('shipping_table_title');
  //  $this->_description = $lC_Language->get('shipping_table_description');
    $this->_status = (defined('ADDONS_SHIPPING_TABLE_RATE_SHIPPING_STATUS') && (ADDONS_SHIPPING_TABLE_RATE_SHIPPING_STATUS == '1') ? true : false);
    $this->_sort_order = (defined('ADDONS_SHIPPING_TABLE_RATE_SHIPPING_SORT_ORDER') ? ADDONS_SHIPPING_TABLE_RATE_SHIPPING_SORT_ORDER : null);
  }

  // class methods
  public function initialize() {
    global $lC_Database, $lC_ShoppingCart;

    $this->tax_class = ADDONS_SHIPPING_TABLE_RATE_SHIPPING_TAX_CLASS;

    if ( ($this->_status === true) && ((int)ADDONS_SHIPPING_TABLE_RATE_SHIPPING_ZONE > 0) ) {
      $check_flag = false;

      $Qcheck = $lC_Database->query('select zone_id from :table_zones_to_geo_zones where geo_zone_id = :geo_zone_id and zone_country_id = :zone_country_id order by zone_id');
      $Qcheck->bindTable(':table_zones_to_geo_zones', TABLE_ZONES_TO_GEO_ZONES);
      $Qcheck->bindInt(':geo_zone_id', ADDONS_SHIPPING_TABLE_RATE_SHIPPING_ZONE);
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

  public function quote() {
    global $lC_Language, $lC_ShoppingCart, $lC_Weight;

    if (ADDONS_SHIPPING_TABLE_RATE_SHIPPING_MODE == 'price') {
      $order_total = $lC_ShoppingCart->getSubTotal();
    } else {
      $order_total = $lC_Weight->convert($lC_ShoppingCart->getWeight(), SHIPPING_WEIGHT_UNIT, ADDONS_SHIPPING_TABLE_RATE_SHIPPING_WEIGHT_UNIT);
    }
    
    $table_cost = preg_split("/[:,]/" , ADDONS_SHIPPING_TABLE_RATE_SHIPPING_TABLE);
    $size = sizeof($table_cost);
    for ($i=0, $n=$size; $i<$n; $i+=2) {
      if ($order_total <= $table_cost[$i]) {
        $shipping = $table_cost[$i+1];
        break;
      }
    }

    if (ADDONS_SHIPPING_TABLE_RATE_SHIPPING_MODE == 'weight') {
      $shipping = $shipping * $lC_ShoppingCart->numberOfShippingBoxes();
    }

    $this->quotes = array('id' => $this->_code,
                          'module' => $this->_title,
                          'methods' => array(array('id' => $this->_code,
                                                   'title' => $lC_Language->get('shipping_table_method'),
                                                   'cost' => $shipping + ADDONS_SHIPPING_TABLE_RATE_SHIPPING_HANDLING)),
                          'tax_class_id' => $this->tax_class);

    if (!empty($this->icon)) $this->quotes['icon'] = lc_image($this->icon, $this->_title);

    return $this->quotes;
  }
}
?>