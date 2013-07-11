<?php
/*
  FedEx Webservice Version 9_4.3.1 for osCommerce 2.2rc2a and later(?) -by Roaddoctor 10/24/2012
  Contributed code and the hard work credit to Jeff Lew. Thanks Jeff and Numinex!
  Some Fixes from CRE Loaded version of same module
  http://forums.oscommerce.com/topic/375063-fedex-web-services-v9/page__st__340#entry1653076
  Released under the GNU General Public License
*/
class fedexwebservices {
 var $code, $title, $description, $icon, $sort_order, $enabled, $tax_class, $fedex_key, $fedex_pwd, $fedex_act_num, $fedex_meter_num, $country;

//Class Constructor
  function fedexwebservices() {
    global $order, $customer_id;

    @define('MODULE_SHIPPING_FEDEX_WEB_SERVICES_INSURE', 0);
    $this->code             = 'fedexwebservices';
    $this->title            = MODULE_SHIPPING_FEDEX_WEB_SERVICES_TEXT_TITLE;
    $this->description      = MODULE_SHIPPING_FEDEX_WEB_SERVICES_TEXT_DESCRIPTION;
    $this->sort_order       = MODULE_SHIPPING_FEDEX_WEB_SERVICES_SORT_ORDER;
    $this->handling_fee     = MODULE_SHIPPING_FEDEX_WEB_SERVICES_HANDLING_FEE;
    $this->icon 			= DIR_WS_ICONS . 'shipping_fedex.gif';
    $this->enabled = ((MODULE_SHIPPING_FEDEX_WEB_SERVICES_STATUS == 'true') ? true : false);

    $this->tax_class        = MODULE_SHIPPING_FEDEX_WEB_SERVICES_TAX_CLASS;
    $this->weight_type      = MODULE_SHIPPING_FEDEX_WEB_SERVICES_WEIGHT;
    $this->fedex_key        = MODULE_SHIPPING_FEDEX_WEB_SERVICES_KEY;
    $this->fedex_pwd        = MODULE_SHIPPING_FEDEX_WEB_SERVICES_PWD;
    $this->fedex_act_num    = MODULE_SHIPPING_FEDEX_WEB_SERVICES_ACT_NUM;
    $this->fedex_meter_num  = MODULE_SHIPPING_FEDEX_WEB_SERVICES_METER_NUM;
    if (defined("SHIPPING_ORIGIN_COUNTRY")) {
      if ((int)SHIPPING_ORIGIN_COUNTRY > 0) {
        $countries_array = $this->get_countries(SHIPPING_ORIGIN_COUNTRY, true);
        $this->country = $countries_array['countries_iso_code_2'];
      } else {
        $this->country = SHIPPING_ORIGIN_COUNTRY;
      }
    } else {
      $this->country = STORE_ORIGIN_COUNTRY;
    }
    if ( ($this->enabled == true) && ((int)MODULE_SHIPPING_FEDEX_WEB_SERVICES_ZONE > 0) ) {
      $check_flag = false;
      $check_query = tep_db_query ("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . MODULE_SHIPPING_FEDEX_WEB_SERVICES_ZONE . "' and zone_country_id = '" . $order->delivery['country']['id'] . "' order by zone_id");
      while( $check = tep_db_fetch_array($check_query)) {
        if ($check ['zone_id'] < 1) {
          $check_flag = true;
          break;
        } elseif ($check ['zone_id'] == $order->delivery['zone_id']) {
          $check_flag = true;
          break;
        }
//        $check->MoveNext();  // an old Zen Cart remnant - probably not required in OSC
      }

      if ($check_flag == false) {
        $this->enabled = false;
      }
    }
  }

  //Class Methods

  function quote($method = '') {
    /* FedEx integration starts */
    global $shipping_weight, $shipping_num_boxes, $cart, $order;

    require_once(DIR_FS_CATALOG . DIR_WS_INCLUDES . 'library/fedex-common.php5');  // comment out and test - you likely do not need this line
    $path_to_wsdl = DIR_FS_CATALOG . DIR_WS_INCLUDES . "wsdl/RateService_v9.wsdl";
    ini_set("soap.wsdl_cache_enabled", "0");
    $client = new SoapClient($path_to_wsdl, array('trace' => 1));
    $this->types = array();
    if (MODULE_SHIPPING_FEDEX_WEB_SERVICES_INTERNATIONAL_PRIORITY == 'true') {
      $this->types['INTERNATIONAL_PRIORITY'] = array('icon' => '', 'handling_fee' => MODULE_SHIPPING_FEDEX_WEB_SERVICES_INT_EXPRESS_HANDLING_FEE);
      $this->types['EUROPE_FIRST_INTERNATIONAL_PRIORITY'] = array('icon' => '', 'handling_fee' => MODULE_SHIPPING_FEDEX_WEB_SERVICES_INT_EXPRESS_HANDLING_FEE);
    }
    if (MODULE_SHIPPING_FEDEX_WEB_SERVICES_INTERNATIONAL_ECONOMY == 'true') {
      $this->types['INTERNATIONAL_ECONOMY'] = array('icon' => '', 'handling_fee' => MODULE_SHIPPING_FEDEX_WEB_SERVICES_INT_EXPRESS_HANDLING_FEE);
    }  
    if (MODULE_SHIPPING_FEDEX_WEB_SERVICES_STANDARD_OVERNIGHT == 'true') {
      $this->types['STANDARD_OVERNIGHT'] = array('icon' => '', 'handling_fee' => MODULE_SHIPPING_FEDEX_WEB_SERVICES_EXPRESS_HANDLING_FEE);
    }
    if (MODULE_SHIPPING_FEDEX_WEB_SERVICES_FIRST_OVERNIGHT == 'true') {
      $this->types['FIRST_OVERNIGHT'] = array('icon' => '', 'handling_fee' => MODULE_SHIPPING_FEDEX_WEB_SERVICES_EXPRESS_HANDLING_FEE);
    }
    if (MODULE_SHIPPING_FEDEX_WEB_SERVICES_PRIORITY_OVERNIGHT == 'true') {
      $this->types['PRIORITY_OVERNIGHT'] = array('icon' => '', 'handling_fee' => MODULE_SHIPPING_FEDEX_WEB_SERVICES_EXPRESS_HANDLING_FEE);
    }
    if (MODULE_SHIPPING_FEDEX_WEB_SERVICES_2DAY == 'true') {
      $this->types['FEDEX_2_DAY'] = array('icon' => '', 'handling_fee' => MODULE_SHIPPING_FEDEX_WEB_SERVICES_EXPRESS_HANDLING_FEE);
    }
    // because FEDEX_GROUND also is returned for Canadian Addresses, we need to check if the country matches the store country and whether international ground is enabled
    if ((MODULE_SHIPPING_FEDEX_WEB_SERVICES_GROUND == 'true' && $order->delivery['country']['id'] == STORE_COUNTRY) || (MODULE_SHIPPING_FEDEX_WEB_SERVICES_GROUND == 'true' && ($order->delivery['country']['id'] != STORE_COUNTRY) && MODULE_SHIPPING_FEDEX_WEB_SERVICES_INTERNATIONAL_GROUND == 'true')) {
      $this->types['FEDEX_GROUND'] = array('icon' => '', 'handling_fee' => ($order->delivery['country']['id'] == STORE_COUNTRY ? MODULE_SHIPPING_FEDEX_WEB_SERVICES_HANDLING_FEE : MODULE_SHIPPING_FEDEX_WEB_SERVICES_INT_HANDLING_FEE));
      $this->types['GROUND_HOME_DELIVERY'] = array('icon' => '', 'handling_fee' => ($order->delivery['country']['id'] == STORE_COUNTRY ? MODULE_SHIPPING_FEDEX_WEB_SERVICES_HOME_DELIVERY_HANDLING_FEE : MODULE_SHIPPING_FEDEX_WEB_SERVICES_INT_HANDLING_FEE));
    }
    if (MODULE_SHIPPING_FEDEX_WEB_SERVICES_INTERNATIONAL_GROUND == 'true') {
      $this->types['INTERNATIONAL_GROUND'] = array('icon' => '', 'handling_fee' => MODULE_SHIPPING_FEDEX_WEB_SERVICES_INT_HANDLING_FEE);
    }
    if (MODULE_SHIPPING_FEDEX_WEB_SERVICES_EXPRESS_SAVER == 'true') {
      $this->types['FEDEX_EXPRESS_SAVER'] = array('icon' => '', 'handling_fee' => MODULE_SHIPPING_FEDEX_WEB_SERVICES_EXPRESS_HANDLING_FEE);
    }
    if (MODULE_SHIPPING_FEDEX_WEB_SERVICES_FREIGHT == 'true') {
      $this->types['FEDEX_FREIGHT'] = array('icon' => '', 'handling_fee' => MODULE_SHIPPING_FEDEX_WEB_SERVICES_EXPRESS_HANDLING_FEE);
      $this->types['FEDEX_NATIONAL_FREIGHT'] = array('icon' => '', 'handling_fee' => MODULE_SHIPPING_FEDEX_WEB_SERVICES_EXPRESS_HANDLING_FEE);
      $this->types['FEDEX_1_DAY_FREIGHT'] = array('icon' => '', 'handling_fee' => MODULE_SHIPPING_FEDEX_WEB_SERVICES_EXPRESS_HANDLING_FEE);
      $this->types['FEDEX_2_DAY_FREIGHT'] = array('icon' => '', 'handling_fee' => MODULE_SHIPPING_FEDEX_WEB_SERVICES_EXPRESS_HANDLING_FEE);
      $this->types['FEDEX_3_DAY_FREIGHT'] = array('icon' => '', 'handling_fee' => MODULE_SHIPPING_FEDEX_WEB_SERVICES_EXPRESS_HANDLING_FEE);
      $this->types['INTERNATIONAL_ECONOMY_FREIGHT'] = array('icon' => '', 'handling_fee' => MODULE_SHIPPING_FEDEX_WEB_SERVICES_INT_EXPRESS_HANDLING_FEE);
      $this->types['INTERNATIONAL_PRIORITY_FREIGHT'] = array('icon' => '', 'handling_fee' => MODULE_SHIPPING_FEDEX_WEB_SERVICES_INT_EXPRESS_HANDLING_FEE);
    }

    // customer details
    $street_address = $order->delivery['street_address'];
    $street_address2 = $order->delivery['suburb'];
    $city = $order->delivery['city'];
    $state = tep_get_zone_code($order->delivery['country']['id'], $order->delivery['zone_id'], '');
    if ($state == "QC") $state = "PQ";
    $postcode = str_replace(array(' ', '-'), '', $order->delivery['postcode']);
    $country_id = $order->delivery['country']['iso_code_2'];

// Depending on your version of Order Editor, any one of the following lines may be appropriate for your setup.
    $totals = $order->info['subtotal'] = $cart->total;  // from CRE Loaded code - testing - this may be the one for all?
//    $totals = $order->info['subtotal'] = $_SESSION['cart']->show_total();  // original code
//    $totals = $order->info['subtotal'] || $_SESSION['cart']->show_total();  // or perhaps this is better
//    $totals = $cart->show_total();  // this seems to work for most

    $this->_setInsuranceValue($totals);

    $request['WebAuthenticationDetail'] = array('UserCredential' =>
                                          array('Key' => $this->fedex_key, 'Password' => $this->fedex_pwd));
    $request['ClientDetail'] = array('AccountNumber' => $this->fedex_act_num, 'MeterNumber' => $this->fedex_meter_num);
    $request['TransactionDetail'] = array('CustomerTransactionId' => ' *** Rate Request v9 using PHP ***');
    $request['Version'] = array('ServiceId' => 'crs', 'Major' => '9', 'Intermediate' => '0', 'Minor' => '0');
    $request['ReturnTransitAndCommit'] = true;
    $request['RequestedShipment']['DropoffType'] = $this->_setDropOff(); // valid values REGULAR_PICKUP, REQUEST_COURIER, ...
    $request['RequestedShipment']['ShipTimestamp'] = date('c');
    $request['RequestedShipment']['PackagingType'] = 'YOUR_PACKAGING'; // valid values FEDEX_BOX, FEDEX_PAK, FEDEX_TUBE, YOUR_PACKAGING, ...
    $request['RequestedShipment']['TotalInsuredValue']=array('Ammount'=> $this->insurance, 'Currency' => $_SESSION['currency']);
    $request['WebAuthenticationDetail'] = array('UserCredential' => array('Key' => $this->fedex_key, 'Password' => $this->fedex_pwd));
    $request['ClientDetail'] = array('AccountNumber' => $this->fedex_act_num, 'MeterNumber' => $this->fedex_meter_num);

    $request['RequestedShipment']['Shipper'] = array('Address' => array(
                                                     'StreetLines' => array(utf8_encode(MODULE_SHIPPING_FEDEX_WEB_SERVICES_ADDRESS_1), utf8_encode(MODULE_SHIPPING_FEDEX_WEB_SERVICES_ADDRESS_2)), // Origin details
                                                     'City' => utf8_encode(MODULE_SHIPPING_FEDEX_WEB_SERVICES_CITY),
                                                     'StateOrProvinceCode' => MODULE_SHIPPING_FEDEX_WEB_SERVICES_STATE,
                                                     'PostalCode' => MODULE_SHIPPING_FEDEX_WEB_SERVICES_POSTAL,
                                                     'CountryCode' => $this->country));          

    $request['RequestedShipment']['Recipient'] = array('Address' => array (
													   'StreetLines' => array(utf8_encode($street_address), utf8_encode($street_address2)), // customer street address
                                                       'City' => utf8_encode($city), //customer city 
                                                       'PostalCode' => $postcode, //customer postcode
                                                       'CountryCode' => $country_id, //customer country code
                                                       'Residential' => ($order->delivery['company'] != '' ? false : true))); // Sets commercial vs residential (Home)

    if (in_array($country_id, array('US', 'CA'))) {
      $request['RequestedShipment']['Recipient']['StateOrProvinceCode'] = $state;
    }

    $request['RequestedShipment']['ShippingChargesPayment'] = array('PaymentType' => 'SENDER',
                                                                    'Payor' => array('AccountNumber' => $this->fedex_act_num, // payor's account number
                                                                    'CountryCode' => $this->country));
    $request['RequestedShipment']['RateRequestTypes'] = 'LIST';
    $request['RequestedShipment']['PackageDetail'] = 'INDIVIDUAL_PACKAGES';
    $request['RequestedShipment']['RequestedPackageLineItems'] = array();
    
    $dimensions_failed = false;
    
    // check for ready to ship field
    if (MODULE_SHIPPING_FEDEX_WEB_SERVICES_READY_TO_SHIP == 'true') {  
        if (isset($_SESSION['cart'])) {
          $products = $_SESSION['cart']->get_products();
          } else {
          $products = $cart->contents;
      }

      $packages = array('default' => 0);
	    $product_dim_type = 'in';
      $new_shipping_num_boxes = 0;
      foreach ($products as $product) {
        $dimensions_query = "SELECT products_ready_to_ship, products_ship_sep, products_weight FROM " . TABLE_PRODUCTS . " 
                             WHERE products_id = " . (int)$product['id'] . " 
                             LIMIT 1;";
        $dimensions = tep_db_query($dimensions_query);
        if ($product_dimensions = tep_db_fetch_array($dimensions)) {
          if ($product_dimensions['products_ready_to_ship'] == 1 || $product_dimensions['products_ship_sep'] == 1) {
            for ($i = 1; $i <= $product['quantity']; $i++) {
              $packages[] = array('weight' => $product_dimensions['products_weight']);
            }    
          } else {
            $packages['default'] += $product_dimensions['products_weight'] * $product['quantity']; 
          }                                                                    
        }
      }
      if (count($packages) > 1) {
        $za_tare_array = preg_split("/[:,]/" , SHIPPING_BOX_WEIGHT);
        $zc_tare_percent= $za_tare_array[0];
        $zc_tare_weight= $za_tare_array[1];

        $za_large_array = preg_split("/[:,]/" , SHIPPING_BOX_PADDING);
        $zc_large_percent= $za_large_array[0];
        $zc_large_weight= $za_large_array[1];
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
                                                                                                   'Units' => $this->weight_type
                                                                                                   )
                                                                                );
          }
        } else {
          // note $values is an array
          $new_shipping_num_boxes++;
          if ($values['weight'] <= 0) $values['weight'] = 0.1;
          $new_shipping_weight += $values['weight'];
          $request['RequestedShipment']['RequestedPackageLineItems'][] = array('Weight' => array('Value' => $values['weight'],
                                                                                                 'Units' => $this->weight_type
																								 )
                                                                               );
        }
      }
      $shipping_num_boxes = $new_shipping_num_boxes;
  // Begin fix for Google Checkout 1.50
 If (!$shipping_num_boxes || $shipping_num_boxes == 0) {
  $shipping_num_boxes = 1;
 }
  // End fix for Google Checkout
      $shipping_weight = round($new_shipping_weight / $shipping_num_boxes, 2);
    } else {
      // Zen Cart default method for calculating number of packages
    if ($shipping_weight == 0) $shipping_weight = 0.1;
      for ($i=0; $i<$shipping_num_boxes; $i++) {
        $request['RequestedShipment']['RequestedPackageLineItems'][] = array('Weight' => array('Value' => $shipping_weight,
                                                                                               'Units' => $this->weight_type));
      }
    }
    $request['RequestedShipment']['PackageCount'] = $shipping_num_boxes;
    
    if (MODULE_SHIPPING_FEDEX_WEB_SERVICES_SATURDAY == 'true') {
      $request['RequestedShipment']['ServiceOptionType'] = 'SATURDAY_DELIVERY';
    }
    
    if (MODULE_SHIPPING_FEDEX_WEB_SERVICES_SIGNATURE_OPTION >= 0 && $totals >= MODULE_SHIPPING_FEDEX_WEB_SERVICES_SIGNATURE_OPTION) { 
      $request['RequestedShipment']['SpecialServicesRequested'] = 'SIGNATURE_OPTION'; 
    }

    $response = $client->getRates($request);

    if ($response->HighestSeverity != 'FAILURE' && $response->HighestSeverity != 'ERROR' && is_array($response->RateReplyDetails) || is_object($response->RateReplyDetails)) {
      if (is_object($response->RateReplyDetails)) {
        $response->RateReplyDetails = get_object_vars($response->RateReplyDetails);
      }

      $show_box_weight = " (Total items: " . $shipping_num_boxes . ' pcs. Total weight: '.number_format($shipping_weight * $shipping_num_boxes,2).' '.strtolower($this->weight_type).'s.)';
      $this->quotes = array('id' => $this->code,
                            'module' => $this->title . $show_box_weight,
                            'info' => $this->info());

      $methods = array();

      foreach ($response->RateReplyDetails as $rateReply)
      {
        if (array_key_exists($rateReply->ServiceType, $this->types) && ($method == '' || str_replace('_', '', $rateReply->ServiceType) == $method))
        {
          if(MODULE_SHIPPING_FEDEX_WEB_SERVICES_RATES == 'LIST')
          {
            foreach($rateReply->RatedShipmentDetails as $ShipmentRateDetail)
            {
              if($ShipmentRateDetail->ShipmentRateDetail->RateType=='PAYOR_LIST_PACKAGE')
//			    if($ShipmentRateDetail->ShipmentRateDetail->RateType==('PAYOR_LIST_PACKAGE' || 'PAYOR_LIST_SHIPMENT')) // try this if having international quoting errors
              {
                $cost = $ShipmentRateDetail->ShipmentRateDetail->TotalNetCharge->Amount;
                $cost = (float)round(preg_replace('/[^0-9.]/', '',  $cost), 2);
              }
            }
          }
          else
          {
            $cost = $rateReply->RatedShipmentDetails[0]->ShipmentRateDetail->TotalNetCharge->Amount;
            $cost = (float)round(preg_replace('/[^0-9.]/', '',  $cost), 2);
          }
          if (in_array($rateReply->ServiceType, array('GROUND_HOME_DELIVERY', 'FEDEX_GROUND', 'INTERNATIONAL_GROUND'))) {
            $transitTime = ' (' . str_replace(array('_', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten', 'eleven', 'twelve', 'thirteen', 'fourteeen'), array(' ', 1,2,3,4,5,6,7,8,9,10,11,12,13,14), strtolower($rateReply->TransitTime)) . ')';
          }
          $methods[] = array('id' => str_replace('_', '', $rateReply->ServiceType),                                                   
                             'title' => ucwords(strtolower(str_replace('_', ' ', $rateReply->ServiceType))) . $transitTime,     
                             'cost' => $cost + (strpos($this->types[$rateReply->ServiceType]['handling_fee'], '%') ? ($cost * (float)$this->types[$rateReply->ServiceType]['handling_fee'] / 100) : (float)$this->types[$rateReply->ServiceType]['handling_fee']));
        }
      }

// begin sort order control - low to high is set
   usort($methods, array( $this,'cmp'));
// end sort order control - comment out section to apply high to low rate sort order

      $this->quotes['methods'] = $methods;
      
      if ($this->tax_class > 0) {
        $this->quotes['tax'] = tep_get_tax_rate($this->tax_class, $order->delivery['country']['id'], $order->delivery['zone_id']);
      }
    } else {
//      $message = 'Error in processing transaction.<br /><br />';
      $message = '<strong>Please enter a ZIP Code to obtain your shipping quote.</strong><br />Or possibly:<br />If no rate is shown, the heavy weight of the item(s) in your Shopping Cart suggests a <strong>Request for Freight Quote</strong>, rather than FedEx Ground service, is recommended.';
      foreach ($response -> Notifications as $notification) {
        if(is_array($response -> Notifications)) {
          $message .= $notification->Severity;
          $message .= ': ';
          $message .= $notification->Message . '<br />';
        } else {
          $message .= $notification->Message . '<br />';
        }
      }
      $this->quotes = array('module' => $this->title,
                            'error'  => $message);
    }

// po box hack by JD, improved by John W
     if (preg_match('/^P\.?\s?O\.?\s+?BOX/i', $order->delivery['street_address']) || (preg_match('/^P\.?\s?O\.?\s+?BOX/i', $order->delivery['suburb']))) {
		$this->quotes = array('module' => $this->title,
		                      'error' => '<font size=+1 color=red><b>Federal Express cannot ship to Post Office Boxes.<b></font><br>Use the Change Address button above to use a FedEx accepted street address.'); }
// end po box hack by JD

    if (tep_not_null($this->icon)) $this->quotes['icon'] = tep_image($this->icon, $this->title);

    return $this->quotes;
  }

    function cmp($a, $b) {
    if ($a['cost'] == $b['cost']) {
        return 0;
    }
    return ($a['cost'] < $b['cost']) ? -1 : 1;
  }

  // method added for expanded info in FEAC
  function info() {
    return MODULE_SHIPPING_FEDEX_WEB_SERVICES_INFO; // add a description here or leave blank to disable
  }
    
  function _setInsuranceValue($order_amount){
    if ($order_amount > (float)MODULE_SHIPPING_FEDEX_WEB_SERVICES_INSURE) {
      $this->insurance = sprintf("%01.2f", $order_amount);
    } else {
      $this->insurance = 0;
    }
  }

  function objectToArray($object) {
    if( !is_object( $object ) && !is_array( $object ) ) {
      return $object;
    }
    if( is_object( $object ) ) {
      $object = get_object_vars( $object );
    }
    return array_map( 'objectToArray', $object );
  }

  function _setDropOff() {
    switch(MODULE_SHIPPING_FEDEX_WEB_SERVICES_DROPOFF) {
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

  function check(){
    if(!isset($this->_check)){
      $check_query  = tep_db_query("SELECT configuration_value FROM ". TABLE_CONFIGURATION ." WHERE configuration_key = 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_STATUS'");
      $this->_check = tep_db_num_rows ($check_query);
    }
    return $this->_check;
  }

  function install() {

    tep_db_query ("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable FedEx Web Services','MODULE_SHIPPING_FEDEX_WEB_SERVICES_STATUS','true','Do you want to offer FedEx shipping?','6','0','tep_cfg_select_option(array(\'true\',\'false\'),',now())");
    tep_db_query ("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('FedEx Web Services Key', 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_KEY', '', 'Enter FedEx Web Services Key', '6', '3', now())");
    tep_db_query ("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('FedEx Web Services Password', 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_PWD', '', 'Enter FedEx Web Services Password', '6', '3', now())");
    tep_db_query ("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('FedEx Account Number', 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_ACT_NUM', '', 'Enter FedEx Account Number', '6', '3', now())");
    tep_db_query ("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('FedEx Meter Number', 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_METER_NUM', '', 'Enter FedEx Meter Number', '6', '4', now())");
    tep_db_query ("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Weight Units', 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_WEIGHT', 'LB', 'Weight Units:', '6', '10', 'tep_cfg_select_option(array(\'LB\', \'KG\'), ', now())");
    tep_db_query ("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('First line of street address', 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_ADDRESS_1', '', 'Enter the first line of your ship-from street address, required', '6', '20', now())");
    tep_db_query ("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Second line of street address', 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_ADDRESS_2', '', 'Enter the second line of your ship-from street address, leave blank if you do not need to specify a second line', '6', '21', now())");
    tep_db_query ("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('City name', 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_CITY', '', 'Enter the city name for the ship-from street address, required', '6', '22', now())");
    tep_db_query ("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('State or Province name', 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_STATE', '', 'Enter the 2 letter state or province name for the ship-from street address, required for Canada and US', '6', '23', now())");
    tep_db_query ("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Postal code', 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_POSTAL', '', 'Enter the postal code for the ship-from street address, required', '6', '24', now())");
    tep_db_query ("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Phone number', 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_PHONE', '', 'Enter a contact phone number for your company, required', '6', '25', now())");
    tep_db_query ("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Drop off type', 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_DROPOFF', '1', 'Dropoff type (1 = Regular pickup, 2 = request courier, 3 = drop box, 4 = drop at BSC, 5 = drop at station)?', '6', '30', 'tep_cfg_select_option(array(\'1\',\'2\',\'3\',\'4\',\'5\'),', now())");
    tep_db_query ("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Express Saver', 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_EXPRESS_SAVER', 'true', 'Enable FedEx Express Saver', '6', '10', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
    tep_db_query ("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Standard Overnight', 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_STANDARD_OVERNIGHT', 'true', 'Enable FedEx Express Standard Overnight', '6', '10', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
    tep_db_query ("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable First Overnight', 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_FIRST_OVERNIGHT', 'true', 'Enable FedEx Express First Overnight', '6', '10', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
    tep_db_query ("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Priority Overnight', 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_PRIORITY_OVERNIGHT', 'true', 'Enable FedEx Express Priority Overnight', '6', '10', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
    tep_db_query ("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable 2 Day', 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_2DAY', 'true', 'Enable FedEx Express 2 Day', '6', '10', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
    tep_db_query ("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable International Priority', 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_INTERNATIONAL_PRIORITY', 'true', 'Enable FedEx Express International Priority', '6', '10', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
    tep_db_query ("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable International Economy', 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_INTERNATIONAL_ECONOMY', 'true', 'Enable FedEx Express International Economy', '6', '10', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
    tep_db_query ("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Ground', 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_GROUND', 'true', 'Enable FedEx Ground', '6', '10', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
    tep_db_query ("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable International Ground', 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_INTERNATIONAL_GROUND', 'true', 'Enable FedEx International Ground', '6', '10', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
    tep_db_query ("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Freight', 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_FREIGHT', 'true', 'Enable FedEx Freight', '6', '10', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
    tep_db_query ("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Saturday Delivery', 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_SATURDAY', 'false', 'Enable Saturday Delivery', '6', '10', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
    tep_db_query ("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Domestic Ground Handling Fee', 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_HANDLING_FEE', '', 'Add a domestic handling fee or leave blank (example: 15 or 15%)', '6', '25', now())");
    tep_db_query ("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Home Delivery Handling Fee', 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_HOME_DELIVERY_HANDLING_FEE', '', 'Add a home delivery handling fee or leave blank (example: 15 or 15%)', '6', '25', now())");
    tep_db_query ("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Domestic Express Handling Fee', 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_EXPRESS_HANDLING_FEE', '', 'Add a domestic handling fee or leave blank (example: 15 or 15%)', '6', '25', now())");
    tep_db_query ("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('International Ground Handling Fee', 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_INT_HANDLING_FEE', '', 'Add an international handling fee or leave blank (example: 15 or 15%)', '6', '25', now())");
    tep_db_query ("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('International Express Handling Fee', 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_INT_EXPRESS_HANDLING_FEE', '', 'Add an international handling fee or leave blank (example: 15 or 15%)', '6', '25', now())");
    tep_db_query ("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('FedEx Rates','MODULE_SHIPPING_FEDEX_WEB_SERVICES_RATES','LIST','FedEx Rates','6','0','tep_cfg_select_option(array(\'LIST\',\'ACCOUNT\'),',now())");
    tep_db_query ("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Signature Option', 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_SIGNATURE_OPTION', '-1', 'Require a signature on orders greater than or equal to (set to -1 to disable):', '6', '25', now())");
    tep_db_query ("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Ready to Ship', 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_READY_TO_SHIP', 'false', 'Enable products_ship_sep or products_ready_to_ship field (required to identify products which ship separately', '6', '10', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now())");    
    tep_db_query ("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Shipping Zone', 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_ZONE', '0', 'If a zone is selected, only enable this shipping method for that zone.', '6', '98', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(', now())");
    tep_db_query ("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Tax Class', 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_TAX_CLASS', '0', 'Use the following tax class on the shipping fee.', '6', '25', 'tep_get_tax_class_title', 'tep_cfg_pull_down_tax_classes(', now())");
    tep_db_query ("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_SORT_ORDER', '0', 'Sort order of display.', '6', '99', now())");
  }

  function remove() {
    tep_db_query ("DELETE FROM ". TABLE_CONFIGURATION ." WHERE configuration_key in ('". implode("','",$this->keys()). "')");
  }

  function keys() {
    return array('MODULE_SHIPPING_FEDEX_WEB_SERVICES_STATUS',
                 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_KEY',
                 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_PWD',
                 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_ACT_NUM',
                 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_METER_NUM',
                 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_WEIGHT',
                 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_ADDRESS_1',
                 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_ADDRESS_2',
                 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_CITY',
                 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_STATE',
                 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_POSTAL',
                 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_PHONE',
                 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_DROPOFF',
                 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_EXPRESS_SAVER',
                 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_STANDARD_OVERNIGHT',
                 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_FIRST_OVERNIGHT',
                 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_PRIORITY_OVERNIGHT',
                 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_2DAY',
                 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_INTERNATIONAL_PRIORITY',
                 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_INTERNATIONAL_ECONOMY',
                 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_GROUND',
                 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_FREIGHT',
                 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_INTERNATIONAL_GROUND',
                 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_SATURDAY',
                 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_TAX_CLASS',
                 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_HANDLING_FEE',
		         'MODULE_SHIPPING_FEDEX_WEB_SERVICES_HOME_DELIVERY_HANDLING_FEE',
                 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_EXPRESS_HANDLING_FEE',
                 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_INT_HANDLING_FEE',
                 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_INT_EXPRESS_HANDLING_FEE',
                 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_RATES',
                 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_SIGNATURE_OPTION',
                 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_READY_TO_SHIP',
                 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_ZONE',
                 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_SORT_ORDER'
                 );
  }

	  function get_countries($countries_id = '', $with_iso_codes = false) {
			$countries_array = array();
			if (tep_not_null($countries_id)) {
				if ($with_iso_codes == true) {
					$countries = tep_db_query("select countries_name, countries_iso_code_2, countries_iso_code_3 from " . TABLE_COUNTRIES . " where countries_id = '" . (int)$countries_id . "' order by countries_name");
					$countries_values = tep_db_fetch_array($countries);
					$countries_array = array('countries_name' => $countries_values['countries_name'],
																	 'countries_iso_code_2' => $countries_values['countries_iso_code_2'],
																	 'countries_iso_code_3' => $countries_values['countries_iso_code_3']);
				} else {
					$countries = tep_db_query("select countries_name from " . TABLE_COUNTRIES . " where countries_id = '" . (int)$countries_id . "'");
					$countries_values = tep_db_fetch_array($countries);
					$countries_array = array('countries_name' => $countries_values['countries_name']);
				}
			} else {
				$countries = tep_db_query("select countries_id, countries_name from " . TABLE_COUNTRIES . " order by countries_name");
				while ($countries_values = tep_db_fetch_array($countries)) {
					$countries_array[] = array('countries_id' => $countries_values['countries_id'],
																		 'countries_name' => $countries_values['countries_name']);
				}
			}

			return $countries_array;
		}
}
?>