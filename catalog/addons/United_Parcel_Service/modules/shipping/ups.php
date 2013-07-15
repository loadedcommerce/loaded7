<?php
/*
  $Id: lC_Shipping_ups.php v1.0 2013-07-10 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  @author     Loaded Commerce, osCommerce
  @copyright  (c) 2013 Loaded Commerce, osCommerce
  @license    http://loadedcommerce.com/license.html
*/
include_once(DIR_FS_CATALOG . 'includes/classes/transport.php');

class lC_Shipping_ups extends lC_Shipping {
  public $icon;

  protected $_title,
            $_code = 'ups',
            $_status = false,
            $_sort_order,
            $_types,
            $_upsProductCode,
            $_upsOriginPostalCode,
            $_upsOriginCountryCode,
            $_upsDestPostalCode,
            $_upsDestCountryCode,
            $_upsRateCode,
            $_upsContainerCode,
            $_upsPackageWeight,
            $_upsResComCode,
            $_upsActionCode;  

  // class constructor
  public function lC_Shipping_ups() {
    global $lC_Language;
    
    $this->icon = DIR_WS_CATALOG . 'addons/United_Parcel_Service/images/ups-small.png';

    $this->_title = $lC_Language->get('shipping_ups_title');
    $this->_status = (defined('ADDONS_SHIPPING_UNITED_PARCEL_SERVICE_STATUS') && (ADDONS_SHIPPING_UNITED_PARCEL_SERVICE_STATUS == '1') ? true : false);
    $this->_sort_order = (defined('ADDONS_SHIPPING_UNITED_PARCEL_SERVICE_SORT_ORDER') ? ADDONS_SHIPPING_UNITED_PARCEL_SERVICE_SORT_ORDER : null);    

    $this->_types = array('1DM' => 'Next Day Air Early AM',
                          '1DML' => 'Next Day Air Early AM Letter',
                          '1DA' => 'Next Day Air',
                          '1DAL' => 'Next Day Air Letter',
                          '1DAPI' => 'Next Day Air Intra (Puerto Rico)',
                          '1DP' => 'Next Day Air Saver',
                          '1DPL' => 'Next Day Air Saver Letter',
                          '2DM' => '2nd Day Air AM',
                          '2DML' => '2nd Day Air AM Letter',
                          '2DA' => '2nd Day Air',
                          '2DAL' => '2nd Day Air Letter',
                          '3DS' => '3 Day Select',
                          'GND' => 'Ground',
                          'GNDCOM' => 'Ground Commercial',
                          'GNDRES' => 'Ground Residential',
                          'STD' => 'Canada Standard',
                          'XPR' => 'Worldwide Express',
                          'XPRL' => 'Worldwide Express Letter',
                          'XDM' => 'Worldwide Express Plus',
                          'XDML' => 'Worldwide Express Plus Letter',
                          'XPD' => 'Worldwide Expedited');
  }
  
  // class methods
  public function initialize() {
    global $lC_Database, $lC_ShoppingCart;

    $this->_tax_class = ADDONS_SHIPPING_UNITED_PARCEL_SERVICE_TAX_CLASS;

    if ( ($this->_status === true) && ((int)ADDONS_SHIPPING_UNITED_PARCEL_SERVICE_ZONE > 0) ) {
      $check_flag = false;

      $Qcheck = $lC_Database->query('select zone_id from :table_zones_to_geo_zones where geo_zone_id = :geo_zone_id and zone_country_id = :zone_country_id order by zone_id');
      $Qcheck->bindTable(':table_zones_to_geo_zones', TABLE_ZONES_TO_GEO_ZONES);
      $Qcheck->bindInt(':geo_zone_id', ADDONS_SHIPPING_UNITED_PARCEL_SERVICE_ZONE);
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

  // class methods
  public function quote($method = '') {
    global $lC_Database, $lC_Language, $lC_ShoppingCart, $lC_Currencies, $lC_Tax;
    
    if ( (!empty($method)) && (isset($this->_types[$method])) ) {
      $prod = $method;
    } else if ($lC_ShoppingCart->getShippingAddress('country_iso_code_2') == 'CA') {
      $prod = 'STD';
    } else {
      $prod = 'GNDRES';
    }
    
    if (!empty($method)) $this->_upsAction('3'); // return a single quote
    
    $this->_upsProduct($prod);   
    $countries_array = lc_get_country_data(SHIPPING_ORIGIN_COUNTRY);
    $this->_upsOrigin(SHIPPING_ORIGIN_ZIP, $countries_array['countries_iso_code_2']);
    $this->_upsDest($lC_ShoppingCart->getShippingAddress('postcode'), $lC_ShoppingCart->getShippingAddress('country_iso_code_2'));
    $this->_upsRate(ADDONS_SHIPPING_UNITED_PARCEL_SERVICE_PICKUP);
    $this->_upsContainer(ADDONS_SHIPPING_UNITED_PARCEL_SERVICE_PACKAGE);
    $this->_upsWeight($lC_ShoppingCart->getWeight());
    $this->_upsRescom(ADDONS_SHIPPING_UNITED_PARCEL_SERVICE_RES);
    
    $shipping_weight = ($lC_ShoppingCart->getShippingBoxesWeight() < 0.1 ? 0.1 : $lC_ShoppingCart->getShippingBoxesWeight());
    $shipping_num_boxes = ceil((float)$shipping_weight / (float)SHIPPING_MAX_WEIGHT);
    if ($shipping_num_boxes <= 0) $shipping_num_boxes = 1;    
    
    $upsQuote = $this->_upsGetQuote();
    
    if ( (is_array($upsQuote)) && (sizeof($upsQuote) > 0) ) {
      
      $this->quotes = array('id' => $this->_code,
                            'module' => $this->_title . ' (' . $shipping_num_boxes . ' x ' . $shipping_weight . 'lbs)');
                            
      $methods = array();
      $allowed_methods = $this->_getAllowedMethods();
      
      $std_rcd = false;
      $qsize = sizeof($upsQuote);
      for ($i=0; $i<$qsize; $i++) {
        list($type, $cost) = each($upsQuote[$i]);
        if ($type == 'STD') {
          if ($std_rcd) { 
            continue;
          } else { 
            $std_rcd = true; 
          }
        }
        if (!in_array($type, $allowed_methods)) continue;
        $methods[] = array('id' => $type,
                           'title' => $this->_types[$type],
                           'cost' => ($cost + (float)ADDONS_SHIPPING_UNITED_PARCEL_SERVICE_HANDLING) * $shipping_num_boxes);
      }

      // begin sort order control - low to high is set, comment out for high to low sort
      usort($methods, array( $this,'_usortMethods'));

      $this->quotes['methods'] = $methods;
      
      if ($this->_tax_class > 0) {
        $this->quotes['tax'] = $lC_Tax->getTaxRate($this->_tax_class);
      }
    } else {
      $this->quotes = array('module' => $this->_title,
                            'error' => 'We are unable to obtain a rate quote for UPS shipping.<br>Please contact the store if no other alternative is shown.');
    }

    if (!empty($this->icon)) $this->quotes['icon'] = lc_image($this->icon, $this->_title, null, null, 'style="vertical-align:-35%;"');

    return $this->quotes;
  }
  
  private function _getAllowedMethods() {
    $allowed = array();
    if (defined('ADDONS_SHIPPING_UNITED_PARCEL_SERVICE_TYPE_1DM') && ADDONS_SHIPPING_UNITED_PARCEL_SERVICE_TYPE_1DM == '1') $allowed[] = '1DM';
    if (defined('ADDONS_SHIPPING_UNITED_PARCEL_SERVICE_TYPE_1DML') && ADDONS_SHIPPING_UNITED_PARCEL_SERVICE_TYPE_1DML == '1') $allowed[] = '1DML';
    if (defined('ADDONS_SHIPPING_UNITED_PARCEL_SERVICE_TYPE_1DA') && ADDONS_SHIPPING_UNITED_PARCEL_SERVICE_TYPE_1DA == '1') $allowed[] = '1DA';
    if (defined('ADDONS_SHIPPING_UNITED_PARCEL_SERVICE_TYPE_1DAL') && ADDONS_SHIPPING_UNITED_PARCEL_SERVICE_TYPE_1DAL == '1') $allowed[] = '1DAL';
    if (defined('ADDONS_SHIPPING_UNITED_PARCEL_SERVICE_TYPE_1DAPI') && ADDONS_SHIPPING_UNITED_PARCEL_SERVICE_TYPE_1DAPI == '1') $allowed[] = '1DAPI';
    if (defined('ADDONS_SHIPPING_UNITED_PARCEL_SERVICE_TYPE_1DP') && ADDONS_SHIPPING_UNITED_PARCEL_SERVICE_TYPE_1DP == '1') $allowed[] = '1DP';
    if (defined('ADDONS_SHIPPING_UNITED_PARCEL_SERVICE_TYPE_1DPL') && ADDONS_SHIPPING_UNITED_PARCEL_SERVICE_TYPE_1DPL == '1') $allowed[] = '1DPL';
    if (defined('ADDONS_SHIPPING_UNITED_PARCEL_SERVICE_TYPE_2DM') && ADDONS_SHIPPING_UNITED_PARCEL_SERVICE_TYPE_2DM == '1') $allowed[] = '2DM';
    if (defined('ADDONS_SHIPPING_UNITED_PARCEL_SERVICE_TYPE_2DML') && ADDONS_SHIPPING_UNITED_PARCEL_SERVICE_TYPE_2DML == '1') $allowed[] = '2DML';
    if (defined('ADDONS_SHIPPING_UNITED_PARCEL_SERVICE_TYPE_2DA') && ADDONS_SHIPPING_UNITED_PARCEL_SERVICE_TYPE_2DA == '1') $allowed[] = '2DA';
    if (defined('ADDONS_SHIPPING_UNITED_PARCEL_SERVICE_TYPE_2DAL') && ADDONS_SHIPPING_UNITED_PARCEL_SERVICE_TYPE_2DAL == '1') $allowed[] = '2DAL';
    if (defined('ADDONS_SHIPPING_UNITED_PARCEL_SERVICE_TYPE_3DS') && ADDONS_SHIPPING_UNITED_PARCEL_SERVICE_TYPE_3DS == '1') $allowed[] = '3DS';
    if (defined('ADDONS_SHIPPING_UNITED_PARCEL_SERVICE_TYPE_GND') && ADDONS_SHIPPING_UNITED_PARCEL_SERVICE_TYPE_GND == '1') $allowed[] = 'GND';
    if (defined('ADDONS_SHIPPING_UNITED_PARCEL_SERVICE_TYPE_STD') && ADDONS_SHIPPING_UNITED_PARCEL_SERVICE_TYPE_STD == '1') $allowed[] = 'STD';
    if (defined('ADDONS_SHIPPING_UNITED_PARCEL_SERVICE_TYPE_XPR') && ADDONS_SHIPPING_UNITED_PARCEL_SERVICE_TYPE_XPR == '1') $allowed[] = 'XPR';
    if (defined('ADDONS_SHIPPING_UNITED_PARCEL_SERVICE_TYPE_XPRL') && ADDONS_SHIPPING_UNITED_PARCEL_SERVICE_TYPE_XPRL == '1') $allowed[] = 'XPRL';
    if (defined('ADDONS_SHIPPING_UNITED_PARCEL_SERVICE_TYPE_XDM') && ADDONS_SHIPPING_UNITED_PARCEL_SERVICE_TYPE_XDM == '1') $allowed[] = 'XDM';
    if (defined('ADDONS_SHIPPING_UNITED_PARCEL_SERVICE_TYPE_XDML') && ADDONS_SHIPPING_UNITED_PARCEL_SERVICE_TYPE_XDML == '1') $allowed[] = 'XDML';
    if (defined('ADDONS_SHIPPING_UNITED_PARCEL_SERVICE_TYPE_XPD') && ADDONS_SHIPPING_UNITED_PARCEL_SERVICE_TYPE_XPD == '1') $allowed[] = 'XPD';   
    
    return $allowed;
  }

  private function _upsProduct($prod){
    $this->_upsProductCode = $prod;
  }

  private function _upsOrigin($postal, $country){
    $this->_upsOriginPostalCode = $postal;
    $this->_upsOriginCountryCode = $country;
  }

  private function _upsDest($postal, $country){
    $postal = str_replace(' ', '', $postal);

    if ($country == 'US') {
      $this->_upsDestPostalCode = substr($postal, 0, 5);
    } else {
      $this->_upsDestPostalCode = $postal;
    }

    $this->_upsDestCountryCode = $country;
  }

  private function _upsRate($foo) {
    switch ($foo) {
      case 'RDP':
        $this->_upsRateCode = 'Regular+Daily+Pickup';
        break;
      case 'OCA':
        $this->_upsRateCode = 'On+Call+Air';
        break;
      case 'OTP':
        $this->_upsRateCode = 'One+Time+Pickup';
        break;
      case 'LC':
        $this->_upsRateCode = 'Letter+Center';
        break;
      case 'CC':
        $this->_upsRateCode = 'Customer+Counter';
        break;
    }
  }

  private function _upsContainer($foo) {
    switch ($foo) {
      case 'CP': // Customer Packaging
        $this->_upsContainerCode = '00';
        break;
      case 'ULE': // UPS Letter Envelope
        $this->_upsContainerCode = '01';
        break;
      case 'UT': // UPS Tube
        $this->_upsContainerCode = '03';
        break;
      case 'UEB': // UPS Express Box
        $this->_upsContainerCode = '21';
        break;
      case 'UW25': // UPS Worldwide 25 kilo
        $this->_upsContainerCode = '24';
        break;
      case 'UW10': // UPS Worldwide 10 kilo
        $this->_upsContainerCode = '25';
        break;
    }
  }

  private function _upsWeight($weight) {
    $this->_upsPackageWeight = $weight;
  }

  private function _upsRescom($foo) {
    switch ($foo) {
      case 'RES': // Residential Address
        $this->_upsResComCode = '1';
        break;
      case 'COM': // Commercial Address
        $this->_upsResComCode = '2';
        break;
    }
  }

  private function _upsAction($action) {
    /* 3 - Single Quote
       4 - All Available Quotes */

    $this->_upsActionCode = $action;
  }

  private function _upsGetQuote() {
    if (!isset($this->_upsActionCode)) $this->_upsActionCode = '4';

    $request = join('&', array('accept_UPS_license_agreement=yes',
                               '10_action=' . $this->_upsActionCode,
                               '13_product=' . $this->_upsProductCode,
                               '14_origCountry=' . $this->_upsOriginCountryCode,
                               '15_origPostal=' . $this->_upsOriginPostalCode,
                               '19_destPostal=' . $this->_upsDestPostalCode,
                               '22_destCountry=' . $this->_upsDestCountryCode,
                               '23_weight=' . $this->_upsPackageWeight,
                               '47_rate_chart=' . $this->_upsRateCode,
                               '48_container=' . $this->_upsContainerCode,
                               '49_residential=' . $this->_upsResComCode));

    $response = transport::getResponse(array('url' => 'http://www.ups.com/using/services/rave/qcostcgi.cgi?' . $request, 'method' => 'get'));
    
    $body_array = explode("\n", $response);

    $returnval = array();
    $errorret = 'error'; // only return error if NO rates returned

    $n = sizeof($body_array);
    for ($i=0; $i<$n; $i++) {
      $result = explode('%', $body_array[$i]);
      $errcode = substr($result[0], -1);
      switch ($errcode) {
        case 3:
          if (is_array($returnval)) $returnval[] = array($result[1] => $result[8]);
          break;
        case 4:
          if (is_array($returnval)) $returnval[] = array($result[1] => $result[8]);
          break;
        case 5:
          $errorret = $result[1];
          break;
        case 6:
          if (is_array($returnval)) $returnval[] = array($result[3] => $result[10]);
          break;
      }
    }
    if (empty($returnval)) $returnval = $errorret;

    return $returnval;
  }
  
  private function _usortMethods($a, $b) {
    if ($a['cost'] == $b['cost']) {
      return 0;
    }
    
    return ($a['cost'] < $b['cost']) ? -1 : 1;
  }   
}
?>