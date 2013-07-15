<?php
/**  
  $Id: lC_Shipping_fedexwebservices.php v1.0 2013-07-10 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     Loaded Commerce Team
  @copyright  (c) 2013 Loaded Commerce Team
  @license    http://loadedcommerce.com/license.html
*/
class lC_Shipping_fedexwebservices extends lC_Shipping {
  
  public $icon;

  protected $_title,
            $_code = 'fedexwebservices',
            $_status = false,
            $_sort_order,
            $_country,
            $_handling_fee,
            $_weight_type,
            $_fedex_key,
            $_fedex_pwd,
            $_fedex_act_num,
            $_fedex_meter_num;

  // class constructor
  public function lC_Shipping_fedexwebservices() {
    global $lC_Language;

    $this->icon = DIR_WS_CATALOG . 'addons/Fedex_Web_Services/images/fedex-small.png';

    $this->_title = $lC_Language->get('shipping_fedex_title');
    $this->_description = $lC_Language->get('shipping_fedex_description');
    $this->_status = (defined('ADDONS_SHIPPING_FEDEX_WEB_SERVICES_STATUS') && (ADDONS_SHIPPING_FEDEX_WEB_SERVICES_STATUS == '1') ? true : false);
    $this->_sort_order = (defined('ADDONS_SHIPPING_FEDEX_WEB_SERVICES_SORT_ORDER') ? ADDONS_SHIPPING_FEDEX_WEB_SERVICES_SORT_ORDER : null);
    $this->_handling_fee = (defined('ADDONS_SHIPPING_FEDEX_WEB_SERVICES_HANDLING_FEE') ? ADDONS_SHIPPING_FEDEX_WEB_SERVICES_HANDLING_FEE : null);
    $this->_weight_type = (defined('ADDONS_SHIPPING_FEDEX_WEB_SERVICES_WEIGHT') ? ADDONS_SHIPPING_FEDEX_WEB_SERVICES_WEIGHT : null);
    $this->_fedex_key = (defined('ADDONS_SHIPPING_FEDEX_WEB_SERVICES_KEY') ? ADDONS_SHIPPING_FEDEX_WEB_SERVICES_KEY : null);
    $this->_fedex_pwd = (defined('ADDONS_SHIPPING_FEDEX_WEB_SERVICES_PWD') ? ADDONS_SHIPPING_FEDEX_WEB_SERVICES_PWD : null);
    $this->_fedex_act_num = (defined('ADDONS_SHIPPING_FEDEX_WEB_SERVICES_ACT_NUM') ? ADDONS_SHIPPING_FEDEX_WEB_SERVICES_ACT_NUM : null);
    $this->_fedex_meter_num = (defined('ADDONS_SHIPPING_FEDEX_WEB_SERVICES_METER_NUM') ? ADDONS_SHIPPING_FEDEX_WEB_SERVICES_METER_NUM : null);
    
    if (defined("SHIPPING_ORIGIN_COUNTRY")) {
      if ((int)SHIPPING_ORIGIN_COUNTRY > 0) {
        $countries_array = lc_get_country_data(SHIPPING_ORIGIN_COUNTRY);
        $this->_country = $countries_array['countries_iso_code_2'];
      } else {
        $this->_country = SHIPPING_ORIGIN_COUNTRY;
      }
    } else {
      $this->_country = STORE_ORIGIN_COUNTRY;
    }    
  }

  // class methods
  public function initialize() {
    global $lC_Database, $lC_ShoppingCart;

    $this->_tax_class = ADDONS_SHIPPING_FEDEX_WEB_SERVICES_TAX_CLASS;

    if ( ($this->_status === true) && ((int)ADDONS_SHIPPING_FEDEX_WEB_SERVICES_ZONE > 0) ) {
      $check_flag = false;

      $Qcheck = $lC_Database->query('select zone_id from :table_zones_to_geo_zones where geo_zone_id = :geo_zone_id and zone_country_id = :zone_country_id order by zone_id');
      $Qcheck->bindTable(':table_zones_to_geo_zones', TABLE_ZONES_TO_GEO_ZONES);
      $Qcheck->bindInt(':geo_zone_id', ADDONS_SHIPPING_FEDEX_WEB_SERVICES_ZONE);
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

  public function quote($method = '') {
    global $lC_Database, $lC_Language, $lC_ShoppingCart, $lC_Currencies, $lC_Tax;

    require_once(DIR_FS_CATALOG . 'addons/Fedex_Web_Services/lib/fedex-common.php');
    
    if (defined('ADDONS_SHIPPING_FEDEX_WEB_SERVICES_SERVER') && strtoupper(ADDONS_SHIPPING_FEDEX_WEB_SERVICES_SERVER) == 'TEST') {
      $path_to_wsdl = DIR_FS_CATALOG . 'addons/Fedex_Web_Services/lib/wsdl/RateService_v10_test.wsdl';
    } else {
      $path_to_wsdl = DIR_FS_CATALOG . 'addons/Fedex_Web_Services/lib/wsdl/RateService_v10.wsdl';
    }    
    
    ini_set("soap.wsdl_cache_enabled", "0");
    $client = new SoapClient($path_to_wsdl, array('trace' => 1));
    
    $types = array();
    if (ADDONS_SHIPPING_FEDEX_WEB_SERVICES_INTERNATIONAL_PRIORITY == '1') {
      $types['INTERNATIONAL_PRIORITY'] = array('icon' => '', 'handling_fee' => ADDONS_SHIPPING_FEDEX_WEB_SERVICES_INT_EXPRESS_HANDLING_FEE);
      $types['EUROPE_FIRST_INTERNATIONAL_PRIORITY'] = array('icon' => '', 'handling_fee' => ADDONS_SHIPPING_FEDEX_WEB_SERVICES_INT_EXPRESS_HANDLING_FEE);
    }
    if (ADDONS_SHIPPING_FEDEX_WEB_SERVICES_INTERNATIONAL_ECONOMY == '1') {
      $types['INTERNATIONAL_ECONOMY'] = array('icon' => '', 'handling_fee' => ADDONS_SHIPPING_FEDEX_WEB_SERVICES_INT_EXPRESS_HANDLING_FEE);
    }  
    if (ADDONS_SHIPPING_FEDEX_WEB_SERVICES_STANDARD_OVERNIGHT == '1') {
      $types['STANDARD_OVERNIGHT'] = array('icon' => '', 'handling_fee' => ADDONS_SHIPPING_FEDEX_WEB_SERVICES_EXPRESS_HANDLING_FEE);
    }
    if (ADDONS_SHIPPING_FEDEX_WEB_SERVICES_FIRST_OVERNIGHT == '1') {
      $types['FIRST_OVERNIGHT'] = array('icon' => '', 'handling_fee' => ADDONS_SHIPPING_FEDEX_WEB_SERVICES_EXPRESS_HANDLING_FEE);
    }
    if (ADDONS_SHIPPING_FEDEX_WEB_SERVICES_PRIORITY_OVERNIGHT == '1') {
      $types['PRIORITY_OVERNIGHT'] = array('icon' => '', 'handling_fee' => ADDONS_SHIPPING_FEDEX_WEB_SERVICES_EXPRESS_HANDLING_FEE);
    }
    if (ADDONS_SHIPPING_FEDEX_WEB_SERVICES_2DAY == '1') {
      $types['FEDEX_2_DAY'] = array('icon' => '', 'handling_fee' => ADDONS_SHIPPING_FEDEX_WEB_SERVICES_EXPRESS_HANDLING_FEE);
    }
    // because FEDEX_GROUND also is returned for Canadian Addresses, we need to check if the country matches the store country and whether international ground is enabled
    if ((ADDONS_SHIPPING_FEDEX_WEB_SERVICES_GROUND == '1' && $lC_ShoppingCart->getShippingAddress('country_id') == STORE_COUNTRY) || (ADDONS_SHIPPING_FEDEX_WEB_SERVICES_GROUND == '1' && ($lC_ShoppingCart->getShippingAddress('country_id') != STORE_COUNTRY) && ADDONS_SHIPPING_FEDEX_WEB_SERVICES_INTERNATIONAL_GROUND == '1')) {
      $types['FEDEX_GROUND'] = array('icon' => '', 'handling_fee' => ($lC_ShoppingCart->getShippingAddress('country_id') == STORE_COUNTRY ? ADDONS_SHIPPING_FEDEX_WEB_SERVICES_HANDLING_FEE : ADDONS_SHIPPING_FEDEX_WEB_SERVICES_INT_HANDLING_FEE));
      $types['GROUND_HOME_DELIVERY'] = array('icon' => '', 'handling_fee' => ($lC_ShoppingCart->getShippingAddress('country_id') == STORE_COUNTRY ? ADDONS_SHIPPING_FEDEX_WEB_SERVICES_HOME_DELIVERY_HANDLING_FEE : ADDONS_SHIPPING_FEDEX_WEB_SERVICES_INT_HANDLING_FEE));
    }
    if (ADDONS_SHIPPING_FEDEX_WEB_SERVICES_INTERNATIONAL_GROUND == '1') {
      $types['INTERNATIONAL_GROUND'] = array('icon' => '', 'handling_fee' => ADDONS_SHIPPING_FEDEX_WEB_SERVICES_INT_HANDLING_FEE);
    }
    if (ADDONS_SHIPPING_FEDEX_WEB_SERVICES_EXPRESS_SAVER == '1') {
      $types['FEDEX_EXPRESS_SAVER'] = array('icon' => '', 'handling_fee' => ADDONS_SHIPPING_FEDEX_WEB_SERVICES_EXPRESS_HANDLING_FEE);
    }
    if (ADDONS_SHIPPING_FEDEX_WEB_SERVICES_FREIGHT == '1') {
      $types['FEDEX_FREIGHT'] = array('icon' => '', 'handling_fee' => ADDONS_SHIPPING_FEDEX_WEB_SERVICES_EXPRESS_HANDLING_FEE);
      $types['FEDEX_NATIONAL_FREIGHT'] = array('icon' => '', 'handling_fee' => ADDONS_SHIPPING_FEDEX_WEB_SERVICES_EXPRESS_HANDLING_FEE);
      $types['FEDEX_1_DAY_FREIGHT'] = array('icon' => '', 'handling_fee' => ADDONS_SHIPPING_FEDEX_WEB_SERVICES_EXPRESS_HANDLING_FEE);
      $types['FEDEX_2_DAY_FREIGHT'] = array('icon' => '', 'handling_fee' => ADDONS_SHIPPING_FEDEX_WEB_SERVICES_EXPRESS_HANDLING_FEE);
      $types['FEDEX_3_DAY_FREIGHT'] = array('icon' => '', 'handling_fee' => ADDONS_SHIPPING_FEDEX_WEB_SERVICES_EXPRESS_HANDLING_FEE);
      $types['INTERNATIONAL_ECONOMY_FREIGHT'] = array('icon' => '', 'handling_fee' => ADDONS_SHIPPING_FEDEX_WEB_SERVICES_INT_EXPRESS_HANDLING_FEE);
      $types['INTERNATIONAL_PRIORITY_FREIGHT'] = array('icon' => '', 'handling_fee' => ADDONS_SHIPPING_FEDEX_WEB_SERVICES_INT_EXPRESS_HANDLING_FEE);
    }

    // customer details
    $street_address = $lC_ShoppingCart->getShippingAddress('street_address');
    $street_address2 = $lC_ShoppingCart->getShippingAddress('suburb');
    $city = $lC_ShoppingCart->getShippingAddress('city');
    
    $state = $lC_ShoppingCart->getShippingAddress('zone_code');
    
    if ($state == "QC") $state = "PQ";
    $postcode = str_replace(array(' ', '-'), '', $lC_ShoppingCart->getShippingAddress('postcode'));
    $country_code = $lC_ShoppingCart->getShippingAddress('country_iso_code_2');

    $totals = $lC_ShoppingCart->getSubTotal();

    $insurance = $this->_setInsuranceValue($totals);

    $request['WebAuthenticationDetail'] = array('UserCredential' =>
                                          array('Key' => $this->_fedex_key, 'Password' => $this->_fedex_pwd));
    $request['ClientDetail'] = array('AccountNumber' => $this->_fedex_act_num, 'MeterNumber' => $this->_fedex_meter_num);
    $request['TransactionDetail'] = array('CustomerTransactionId' => ' *** Rate Request v10 using PHP ***');
    $request['Version'] = array('ServiceId' => 'crs', 'Major' => '10', 'Intermediate' => '0', 'Minor' => '0');
    $request['ReturnTransitAndCommit'] = true;
    $request['RequestedShipment']['DropoffType'] = $this->_setDropOff(); // valid values REGULAR_PICKUP, REQUEST_COURIER, ...
    $request['RequestedShipment']['ShipTimestamp'] = date('c');
    $request['RequestedShipment']['PackagingType'] = 'YOUR_PACKAGING'; // valid values FEDEX_BOX, FEDEX_PAK, FEDEX_TUBE, YOUR_PACKAGING, ...
    $request['RequestedShipment']['TotalInsuredValue'] = array('Ammount'=> $insurance, 'Currency' => $lC_Currencies->getCode());
    $request['WebAuthenticationDetail'] = array('UserCredential' => array('Key' => $this->_fedex_key, 'Password' => $this->_fedex_pwd));
    $request['ClientDetail'] = array('AccountNumber' => $this->_fedex_act_num, 'MeterNumber' => $this->_fedex_meter_num);

    $request['RequestedShipment']['Shipper'] = array('Address' => array(
                                                     'StreetLines' => array(utf8_encode(ADDONS_SHIPPING_FEDEX_WEB_SERVICES_ADDRESS_1), utf8_encode(ADDONS_SHIPPING_FEDEX_WEB_SERVICES_ADDRESS_2)), // Origin details
                                                     'City' => utf8_encode(ADDONS_SHIPPING_FEDEX_WEB_SERVICES_CITY),
                                                     'StateOrProvinceCode' => ADDONS_SHIPPING_FEDEX_WEB_SERVICES_STATE,
                                                     'PostalCode' => ADDONS_SHIPPING_FEDEX_WEB_SERVICES_POSTAL,
                                                     'CountryCode' => $this->_country));          

    $request['RequestedShipment']['Recipient'] = array('Address' => array (
                             'StreetLines' => array(utf8_encode($street_address), utf8_encode($street_address2)), // customer street address
                                                       'City' => utf8_encode($city), //customer city 
                                                       'PostalCode' => $postcode, //customer postcode
                                                       'CountryCode' => $country_code, //customer country code
                                                       'Residential' => ($lC_ShoppingCart->getShippingAddress('company') != '' ? false : true))); // Sets commercial vs residential (Home)

    if (in_array($country_code, array('US', 'CA'))) {
      $request['RequestedShipment']['Recipient']['StateOrProvinceCode'] = $state;
    }

    $request['RequestedShipment']['ShippingChargesPayment'] = array('PaymentType' => 'SENDER',
                                                                    'Payor' => array('AccountNumber' => $this->_fedex_act_num, // payor's account number
                                                                    'CountryCode' => $this->_country));
    $request['RequestedShipment']['RateRequestTypes'] = 'LIST';
    $request['RequestedShipment']['PackageDetail'] = 'INDIVIDUAL_PACKAGES';
    $request['RequestedShipment']['RequestedPackageLineItems'] = array();
    
    $dimensions_failed = false;
    
    // check for ready to ship field
    if (ADDONS_SHIPPING_FEDEX_WEB_SERVICES_READY_TO_SHIP == '1') {  
      $products = array();
      if ($lC_ShoppingCart->hasContents()) {
        $products = $lC_ShoppingCart->getProducts();
      } 

      $packages = array('default' => 0);
      $product_dim_type = 'in';
      $new_shipping_num_boxes = 0;
      foreach ($products as $product) {
        
        $Qsize = $lC_Database->query('select products_ready_to_ship, products_ship_sep, products_weight from :table_products where products_id = :products_id LIMIT 1');
        $Qsize->bindTable(':table_products', TABLE_PRODUCTS);
        $Qsize->bindInt(':products_id', $product['id']);
        $Qsize->execute();
                
        While ($Qsize->next()) {
          if ($Qsize->valueInt('products_ready_to_ship') == 1 || $Qsize->valueInt('products_ship_sep') == 1) {
            for ($i = 1; $i <= $product['quantity']; $i++) {
              $packages[] = array('weight' => $Qsize->valueDecimal('products_weight'));
            }    
          } else {
            $packages['default'] += $Qsize->valueDecimal('products_weight') * $product['quantity']; 
          }                                                                    
        }
      }
      
      if (count($packages) > 1) {
        $za_tare_array = preg_split("/[:,]/" , SHIPPING_BOX_WEIGHT);
        $zc_tare_percent = $za_tare_array[0];
        $zc_tare_weight = $za_tare_array[1];

        $za_large_array = preg_split("/[:,]/" , SHIPPING_BOX_PADDING);
        $zc_large_percent = $za_large_array[0];
        $zc_large_weight = $za_large_array[1];
      }     
                                                                                                             
      foreach ($packages as $id => $values) {
        if ($id === 'default') {
          // divide the weight by the max amount to be shipped (can be done inside loop as this occurance should only ever happen once
          // note $values is not an array
          if ($values == 0) continue;
          $shipping_num_boxes = ceil((float)$values / (float)SHIPPING_MAX_WEIGHT);
          if ($shipping_num_boxes < 1) $shipping_num_boxes = 1;
          $shipping_weight = round((float)$values / $shipping_num_boxes, 2); // 2 decimal places max
          for ($i=0; $i<$shipping_num_boxes; $i++) {
            $new_shipping_num_boxes++;
            if (SHIPPING_MAX_WEIGHT <= $shipping_weight) {
              $shipping_weight = $shipping_weight + ($shipping_weight*($zc_large_percent/100)) + $zc_large_weight;
            } else {
              $shipping_weight = $shipping_weight + ($shipping_weight*($zc_tare_percent/100)) + $zc_tare_weight;
            }
            if ($shipping_weight <= 0) $shipping_weight = 0.1; 
            $new_shipping_weight += $shipping_weight;           
            $request['RequestedShipment']['RequestedPackageLineItems'][] = array('Weight' => array('Value' => $shipping_weight,
                                                                                                   'Units' => $this->_weight_type));
          }
        } else {
          // note $values is an array
          $new_shipping_num_boxes++;
          if ($values['weight'] <= 0) $values['weight'] = 0.1;
          $new_shipping_weight += $values['weight'];
          $request['RequestedShipment']['RequestedPackageLineItems'][] = array('Weight' => array('Value' => $values['weight'],
                                                                                                 'Units' => $this->_weight_type));
        }
      }
      $shipping_num_boxes = $new_shipping_num_boxes;

      // Begin fix for Google Checkout 1.50
      if (!$shipping_num_boxes || $shipping_num_boxes == 0) {
        $shipping_num_boxes = 1;
      }

      $shipping_weight = round($new_shipping_weight / $shipping_num_boxes, 2);

    } else {
      
      $shipping_weight = ($lC_ShoppingCart->getShippingBoxesWeight() < 0.1 ? 0.1 : $lC_ShoppingCart->getShippingBoxesWeight());
      $shipping_num_boxes = ceil((float)$shipping_weight / (float)SHIPPING_MAX_WEIGHT);
      if ($shipping_num_boxes <= 0) $shipping_num_boxes = 1;

      for ($i=0; $i < $shipping_num_boxes; $i++) {
        $request['RequestedShipment']['RequestedPackageLineItems'][] = array('GroupPackageCount' => $shipping_num_boxes,
                                                                             'Weight' => array('Value' => $shipping_weight,
                                                                                               'Units' => $this->_weight_type));
      }
      
    }
    $request['RequestedShipment']['PackageCount'] = $shipping_num_boxes;
    
    if (ADDONS_SHIPPING_FEDEX_WEB_SERVICES_SATURDAY == '1') {
      $request['RequestedShipment']['ServiceOptionType'] = 'SATURDAY_DELIVERY';
    }
    
    if (ADDONS_SHIPPING_FEDEX_WEB_SERVICES_SIGNATURE_OPTION >= 0 && $totals >= ADDONS_SHIPPING_FEDEX_WEB_SERVICES_SIGNATURE_OPTION) { 
      $request['RequestedShipment']['SpecialServicesRequested'] = 'SIGNATURE_OPTION'; 
    }
    
    $response = $client->getRates($request);

    if ($response->HighestSeverity != 'FAILURE' && $response->HighestSeverity != 'ERROR' && is_array($response->RateReplyDetails) || is_object($response->RateReplyDetails)) {
      if (is_object($response->RateReplyDetails)) {
        $response->RateReplyDetails = get_object_vars($response->RateReplyDetails);
      }

      $show_box_weight = " (" . $shipping_num_boxes . ' x ' . number_format($shipping_weight * $shipping_num_boxes, 2) . ' ' . strtolower($this->_weight_type) . 's.)';
      $this->quotes = array('id' => $this->_code,
                            'module' => $this->_title . $show_box_weight,
                            'info' => '');

      $methods = array();

      foreach ($response->RateReplyDetails as $rateReply) {
        if (array_key_exists($rateReply->ServiceType, $types) && ($method == '' || str_replace('_', '', $rateReply->ServiceType) == $method)) {
          if(ADDONS_SHIPPING_FEDEX_WEB_SERVICES_RATES == 'LIST') {
            foreach($rateReply->RatedShipmentDetails as $ShipmentRateDetail) {
              if($ShipmentRateDetail->ShipmentRateDetail->RateType == 'PAYOR_LIST_PACKAGE') {
              // if($ShipmentRateDetail->ShipmentRateDetail->RateType==('PAYOR_LIST_PACKAGE' || 'PAYOR_LIST_SHIPMENT')) // try this if having international quoting errors
                $cost = $ShipmentRateDetail->ShipmentRateDetail->TotalNetCharge->Amount;
                $cost = (float)round(preg_replace('/[^0-9.]/', '',  $cost), 2);
              }
            }
          } else {
            $cost = $rateReply->RatedShipmentDetails[0]->ShipmentRateDetail->TotalNetCharge->Amount;
            $cost = (float)round(preg_replace('/[^0-9.]/', '',  $cost), 2);
          }
          if (in_array($rateReply->ServiceType, array('GROUND_HOME_DELIVERY', 'FEDEX_GROUND', 'INTERNATIONAL_GROUND'))) {
            $transitTime = ' (' . str_replace(array('_', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten', 'eleven', 'twelve', 'thirteen', 'fourteeen'), array(' ', 1,2,3,4,5,6,7,8,9,10,11,12,13,14), strtolower($rateReply->TransitTime)) . ')';
          }
          $methods[] = array('id' => str_replace('_', '', $rateReply->ServiceType),                                                   
                             'title' => ucwords(strtolower(str_replace('_', ' ', $rateReply->ServiceType))) . $transitTime,     
                             'cost' => $cost + (strpos($types[$rateReply->ServiceType]['handling_fee'], '%') ? ($cost * (float)$types[$rateReply->ServiceType]['handling_fee'] / 100) : (float)$types[$rateReply->ServiceType]['handling_fee']));
        }
      }

      // begin sort order control - low to high is set, comment out for high to low sort
      usort($methods, array( $this,'_usortMethods'));

      $this->quotes['methods'] = $methods;
      
      if ($this->_tax_class > 0) {
        $this->quotes['tax'] = $lC_Tax->getTaxRate($this->_tax_class);
      }
    } else {
      $message = 'Error requesting rates from server.<br /><br />';
      //$message = '<strong>Please enter a ZIP Code to obtain your shipping quote.</strong><br />Or possibly:<br />If no rate is shown, the heavy weight of the item(s) in your Shopping Cart suggests a <strong>Request for Freight Quote</strong>, rather than FedEx Ground service, is recommended.';
      foreach ($response -> Notifications as $notification) {
        if(is_array($response -> Notifications)) {
          $message .= $notification->Severity;
          $message .= ': ';
          $message .= $notification->Message . '<br />';
        } else {
          $message .= $notification->Message . '<br />';
        }
      }
      $this->quotes = array('module' => $this->_title,
                            'error'  => $message);
    }

    // po box hack
    if (preg_match("/^P(.+)O(.+)BOX/i", $order->delivery['street_address']) || 
        preg_match("/^PO BOX/i",$order->delivery['street_address']) || 
        preg_match("/^P(.+)O(.+)BOX/i", $order->delivery['suburb']) || 
        preg_match("/^[A-Z]PO/i", $order->delivery['street_address']) || 
        preg_match("/^[A-Z]PO/i",$order->delivery['suburb'])) {
          
      $this->quotes = array('module' => $this->_title,
                          'error' => '<font size=+1 color=red><b>Federal Express cannot ship to Post Office Boxes.<b></font><br>Use the Change Address button and use a FedEx accepted street address.'); 
    }

    if (!empty($this->icon)) $this->quotes['icon'] = lc_image($this->icon, $this->_title, null, null, 'style="vertical-align:-35%;"');

    return $this->quotes;
  }  

  private function _setInsuranceValue($order_amount){
    if ($order_amount > (float)ADDONS_SHIPPING_FEDEX_WEB_SERVICES_INSURE) {
      $insurance = sprintf("%01.2f", $order_amount);
    } else {
      $insurance = 0;
    }
    
    return $insurance;
  }
  
  private function _setDropOff() {
    switch(ADDONS_SHIPPING_FEDEX_WEB_SERVICES_DROPOFF) {
      case '1':
        return 'REGULAR_PICKUP';
        break;
      case '2':
        return 'REQUEST_COURIER';
        break;
      case '3':
        return 'DROP_BOX';
        break;
      case '4':
        return 'BUSINESS_SERVICE_CENTER';
        break;
      case '5':
        return 'STATION';
        break;
    }
  }  

  private function _usortMethods($a, $b) {
    if ($a['cost'] == $b['cost']) {
      return 0;
    }
    
    return ($a['cost'] < $b['cost']) ? -1 : 1;
  }  
}
?>