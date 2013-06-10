<?php
/**  
  $Id: lC_Shipping_usps.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     Loaded Commerce Team
  @copyright  (c) 2013 Loaded Commerce Team
  @license    http://loadedcommerce.com/license.html
*/
require_once(DIR_FS_CATALOG . 'includes/classes/transport.php');  

class lC_Shipping_usps extends lC_Shipping {
  
  public $icon, 
         $countries;

  protected $_title,
            $_code = 'usps',
            $_status = false,
            $_sort_order;

  // class constructor
  public function lC_Shipping_usps() {
    global $lC_Language;

    $this->icon = DIR_WS_IMAGES . 'icons/shipping_usps.gif';

    $this->_title = $lC_Language->get('shipping_usps_title');
    $this->_description = $lC_Language->get('shipping_usps_description');
    $this->_status = (defined('ADDONS_SHIPPING_USPS_SHIPPING_STATUS') && (ADDONS_SHIPPING_USPS_SHIPPING_STATUS == '1') ? true : false);
    $this->_sort_order = (defined('ADDONS_SHIPPING_USPS_SHIPPING_SORT_ORDER') ? ADDONS_SHIPPING_USPS_SHIPPING_SORT_ORDER : null);
  }

  // class methods
  public function initialize() {
    global $lC_Database, $lC_ShoppingCart;

    $this->tax_class = ADDONS_SHIPPING_USPS_SHIPPING_TAX_CLASS;

    if ( ($this->_status === true) && ((int)ADDONS_SHIPPING_USPS_SHIPPING_ZONE > 0) ) {
      $check_flag = false;

      $Qcheck = $lC_Database->query('select zone_id from :table_zones_to_geo_zones where geo_zone_id = :geo_zone_id and zone_country_id = :zone_country_id order by zone_id');
      $Qcheck->bindTable(':table_zones_to_geo_zones', TABLE_ZONES_TO_GEO_ZONES);
      $Qcheck->bindInt(':geo_zone_id', ADDONS_SHIPPING_USPS_SHIPPING_ZONE);
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

    $this->types = array('Express' => 'EXPRESS',
                         'First Class' => 'First-Class Mail',
                         'Priority' => 'Priority',
                         'Parcel' => 'Parcel');

    $this->intl_types = array('GXG Document' => 'Global Express Guaranteed Document Service',
                              'GXG Non-Document' => 'Global Express Guaranteed Non-Document Service',
                              'Express' => 'Global Express Mail (EMS)',
                              'Priority Lg' => 'Global Priority Mail - Flat-rate Envelope (Large)',
                              'Priority Sm' => 'Global Priority Mail - Flat-rate Envelope (Small)',
                              'Priority Var' => 'Global Priority Mail - Variable Weight Envelope (Single)',
                              'Airmail Letter' => 'Airmail Letter-post',
                              'Airmail Parcel' => 'Airmail Parcel Post',
                              'Surface Letter' => 'Economy (Surface) Letter-post',
                              'Surface Post' => 'Economy (Surface) Parcel Post');

    $this->countries = $this->country_list();
  }

  public function quote() {
    global $lC_Language, $lC_ShoppingCart;

    $this->_setMachinable('False');
    $this->_setContainer('');
    $this->_setSize('REGULAR');

    // usps doesnt accept zero weight
    $shipping_weight = ($lC_ShoppingCart->getShippingBoxesWeight() < 0.1 ? 0.1 : $lC_ShoppingCart->getShippingBoxesWeight());
    $shipping_pounds = floor ($shipping_weight);
    $shipping_ounces = round(16 * ($shipping_weight - floor($shipping_weight)));
    $this->_setWeight($shipping_pounds, $shipping_ounces);

    $uspsQuote = $this->_getQuote();

    if (is_array($uspsQuote)) {
      if (isset($uspsQuote['error'])) {
        $this->quotes = array('module' => $this->_title,
                              'error' => $uspsQuote['error']);
      } else {
        $this->quotes = array('id' => $this->_code,
                              'module' => $this->_title . ' (' . $lC_ShoppingCart->numberOfShippingBoxes() . ' x ' . $shipping_weight . 'lbs)',
                              'tax_class_id' => $this->tax_class);

        $methods = array();
        $size = sizeof($uspsQuote);
        for ($i=0; $i<$size; $i++) {
          list($type, $cost) = each($uspsQuote[$i]);

          $methods[] = array('id' => $type,
                             'title' => ((isset($this->types[$type])) ? $this->types[$type] : $type),
                             'cost' => ($cost + ADDONS_SHIPPING_USPS_SHIPPING_HANDLING) * $lC_ShoppingCart->numberOfShippingBoxes());
        }

        $this->quotes['methods'] = $methods;
      }
    } else {
      $this->quotes = array('module' => $this->_title,
                            'error' => $lC_Language->get('shipping_usps_error'));
    }

    if (!empty($this->icon)) $this->quotes['icon'] = lc_image($this->icon, $this->_title);

    return $this->quotes;
  }

  private function _setService($service) {
    $this->service = $service;
  }

  private function _setWeight($pounds, $ounces=0) {
    $this->pounds = $pounds;
    $this->ounces = $ounces;
  }

  private function _setContainer($container) {
    $this->container = $container;
  }

  private function _setSize($size) {
    $this->size = $size;
  }

  private function _setMachinable($machinable) {
    $this->machinable = $machinable;
  }

  private function _getQuote() {
    global $lC_ShoppingCart;

    if ($lC_ShoppingCart->getShippingAddress('country_id') == SHIPPING_ORIGIN_COUNTRY) {
      $request  = '<RateV4Request USERID="' . ADDONS_SHIPPING_USPS_SHIPPING_USERID . '">';
      $services_count = 0;

      if (isset($this->service)) {
        $this->types = array($this->service => $this->types[$this->service]);
      }

      $dest_zip = str_replace(' ', '', $lC_ShoppingCart->getShippingAddress('postcode'));
      if ($lC_ShoppingCart->getShippingAddress('country_iso_code_2') == 'US') $dest_zip = substr($dest_zip, 0, 5);

      reset($this->types);
      while (list($key, $value) = each($this->types)) {
        $request .= '<Package ID="' . $services_count . '">' .
                    '<Service>' . $key . '</Service>' .
                    '<ZipOrigination>' . SHIPPING_ORIGIN_ZIP . '</ZipOrigination>' .
                    '<ZipDestination>' . $dest_zip . '</ZipDestination>' .
                    '<Pounds>' . $this->pounds . '</Pounds>' .
                    '<Ounces>' . $this->ounces . '</Ounces>' .
                    '<Container>' . $this->container . '</Container>' .
                    '<Size>' . $this->size . '</Size>' .
                    '<Machinable>' . $this->machinable . '</Machinable>' .
                    '</Package>';
        $services_count++;
      }
      $request .= '</RateV4Request>';

      $request = 'API=RateV4&XML=' . urlencode($request);
    } else {
      $request  = '<IntlRateV2Request USERID="' . ADDONS_SHIPPING_USPS_SHIPPING_USERID . '">' .
                  '<Package ID="0">' .
                  '<Pounds>' . $this->pounds . '</Pounds>' .
                  '<Ounces>' . $this->ounces . '</Ounces>' .
                  '<MailType>Package</MailType>' .
                  '<Country>' . $this->countries[$lC_ShoppingCart->getShippingAddress('country_iso_code_2')] . '</Country>' .
                  '</Package>' .
                  '</IntlRateV2Request>';

      $request = 'API=IntlRateV2&XML=' . urlencode($request);
    }

    switch (ADDONS_SHIPPING_USPS_SHIPPING_SERVER) {
      case 'Production': $usps_server = 'http://production.shippingapis.com/ShippingAPI.dll';
                         break;
      case 'Test':
      default:           $usps_server = 'http://testing.shippingapis.com/ShippingAPITest.dll';
                         break;
    }

    $result = transport::getResponse(array('url' => $usps_server . '?' . $request, 'method' => 'get'));

    $response = utility::xml2arr($result);

    $rates = array();
    
    if ($lC_ShoppingCart->getShippingAddress('country_id') == SHIPPING_ORIGIN_COUNTRY) {
      /*
      if (sizeof($response) == '1') {
        if (preg_match('/<Error>/', $response[0])) {
          $number = preg_match('/<Number>(.*)</Number>/', $response[0], $regs);
          $number = $regs[1];
          $description = preg_match('/<Description>(.*)</Description>/', $response[0], $regs);
          $description = $regs[1];

          return array('error' => $number . ' - ' . $description);
        }
      }
      */
      foreach ($response['RateV4Response']['Package'] as $key => $rate) {
        if (is_array($rate['Postage'])) {
          $rates[] = array('<div class="moduleSubRow" style="margin-left:6px;">' . substr($rate['Postage']['MailService'], 0, strpos($rate['Postage']['MailService'], '&lt;')) . '</div>' => $rate['Postage']['Rate']);
        }  
      }
      
    } else {
      if (preg_match('/<Error>/', $response[0])) {
        $number = preg_match('/<Number>(.*)</Number>/', $response[0], $regs);
        $number = $regs[1];
        $description = preg_match('/<Description>(.*)</Description>/', $response[0], $regs);
        $description = $regs[1];

        return array('error' => $number . ' - ' . $description);
      } else {
        $body = $response[0];
        $services = array();
        while (true) {
          if ($start = strpos($body, '<Service ID=')) {
            $body = substr($body, $start);
            $end = strpos($body, '</Service>');
            $services[] = substr($body, 0, $end+10);
            $body = substr($body, $end+9);
          } else {
            break;
          }
        }

        $size = sizeof($services);
        for ($i=0, $n=$size; $i<$n; $i++) {
          if (strpos($services[$i], '<Postage>')) {
            $service = preg_match('/<SvcDescription>(.*)</SvcDescription>/', $services[$i], $regs);
            $service = $regs[1];
            $postage = preg_match('/<Postage>(.*)</Postage>/', $services[$i], $regs);
            $postage = $regs[1];

            if (isset($this->service) && ($service != $this->service) ) {
              continue;
            }

            $rates[] = array($service => $postage);
          }
        }
      }
    }

    return ((sizeof($rates) > 0) ? $rates : false);
  }

  public function country_list() {
    $list = array('AF' => 'Afghanistan',
                  'AL' => 'Albania',
                  'DZ' => 'Algeria',
                  'AD' => 'Andorra',
                  'AO' => 'Angola',
                  'AI' => 'Anguilla',
                  'AG' => 'Antigua and Barbuda',
                  'AR' => 'Argentina',
                  'AM' => 'Armenia',
                  'AW' => 'Aruba',
                  'AU' => 'Australia',
                  'AT' => 'Austria',
                  'AZ' => 'Azerbaijan',
                  'BS' => 'Bahamas',
                  'BH' => 'Bahrain',
                  'BD' => 'Bangladesh',
                  'BB' => 'Barbados',
                  'BY' => 'Belarus',
                  'BE' => 'Belgium',
                  'BZ' => 'Belize',
                  'BJ' => 'Benin',
                  'BM' => 'Bermuda',
                  'BT' => 'Bhutan',
                  'BO' => 'Bolivia',
                  'BA' => 'Bosnia-Herzegovina',
                  'BW' => 'Botswana',
                  'BR' => 'Brazil',
                  'VG' => 'British Virgin Islands',
                  'BN' => 'Brunei Darussalam',
                  'BG' => 'Bulgaria',
                  'BF' => 'Burkina Faso',
                  'MM' => 'Burma',
                  'BI' => 'Burundi',
                  'KH' => 'Cambodia',
                  'CM' => 'Cameroon',
                  'CA' => 'Canada',
                  'CV' => 'Cape Verde',
                  'KY' => 'Cayman Islands',
                  'CF' => 'Central African Republic',
                  'TD' => 'Chad',
                  'CL' => 'Chile',
                  'CN' => 'China',
                  'CX' => 'Christmas Island (Australia)',
                  'CC' => 'Cocos Island (Australia)',
                  'CO' => 'Colombia',
                  'KM' => 'Comoros',
                  'CG' => 'Congo (Brazzaville),Republic of the',
                  'ZR' => 'Congo, Democratic Republic of the',
                  'CK' => 'Cook Islands (New Zealand)',
                  'CR' => 'Costa Rica',
                  'CI' => 'Cote d\'Ivoire (Ivory Coast)',
                  'HR' => 'Croatia',
                  'CU' => 'Cuba',
                  'CY' => 'Cyprus',
                  'CZ' => 'Czech Republic',
                  'DK' => 'Denmark',
                  'DJ' => 'Djibouti',
                  'DM' => 'Dominica',
                  'DO' => 'Dominican Republic',
                  'TP' => 'East Timor (Indonesia)',
                  'EC' => 'Ecuador',
                  'EG' => 'Egypt',
                  'SV' => 'El Salvador',
                  'GQ' => 'Equatorial Guinea',
                  'ER' => 'Eritrea',
                  'EE' => 'Estonia',
                  'ET' => 'Ethiopia',
                  'FK' => 'Falkland Islands',
                  'FO' => 'Faroe Islands',
                  'FJ' => 'Fiji',
                  'FI' => 'Finland',
                  'FR' => 'France',
                  'GF' => 'French Guiana',
                  'PF' => 'French Polynesia',
                  'GA' => 'Gabon',
                  'GM' => 'Gambia',
                  'GE' => 'Georgia, Republic of',
                  'DE' => 'Germany',
                  'GH' => 'Ghana',
                  'GI' => 'Gibraltar',
                  'GB' => 'Great Britain and Northern Ireland',
                  'GR' => 'Greece',
                  'GL' => 'Greenland',
                  'GD' => 'Grenada',
                  'GP' => 'Guadeloupe',
                  'GT' => 'Guatemala',
                  'GN' => 'Guinea',
                  'GW' => 'Guinea-Bissau',
                  'GY' => 'Guyana',
                  'HT' => 'Haiti',
                  'HN' => 'Honduras',
                  'HK' => 'Hong Kong',
                  'HU' => 'Hungary',
                  'IS' => 'Iceland',
                  'IN' => 'India',
                  'ID' => 'Indonesia',
                  'IR' => 'Iran',
                  'IQ' => 'Iraq',
                  'IE' => 'Ireland',
                  'IL' => 'Israel',
                  'IT' => 'Italy',
                  'JM' => 'Jamaica',
                  'JP' => 'Japan',
                  'JO' => 'Jordan',
                  'KZ' => 'Kazakhstan',
                  'KE' => 'Kenya',
                  'KI' => 'Kiribati',
                  'KW' => 'Kuwait',
                  'KG' => 'Kyrgyzstan',
                  'LA' => 'Laos',
                  'LV' => 'Latvia',
                  'LB' => 'Lebanon',
                  'LS' => 'Lesotho',
                  'LR' => 'Liberia',
                  'LY' => 'Libya',
                  'LI' => 'Liechtenstein',
                  'LT' => 'Lithuania',
                  'LU' => 'Luxembourg',
                  'MO' => 'Macao',
                  'MK' => 'Macedonia, Republic of',
                  'MG' => 'Madagascar',
                  'MW' => 'Malawi',
                  'MY' => 'Malaysia',
                  'MV' => 'Maldives',
                  'ML' => 'Mali',
                  'MT' => 'Malta',
                  'MQ' => 'Martinique',
                  'MR' => 'Mauritania',
                  'MU' => 'Mauritius',
                  'YT' => 'Mayotte (France)',
                  'MX' => 'Mexico',
                  'MD' => 'Moldova',
                  'MC' => 'Monaco (France)',
                  'MN' => 'Mongolia',
                  'MS' => 'Montserrat',
                  'MA' => 'Morocco',
                  'MZ' => 'Mozambique',
                  'NA' => 'Namibia',
                  'NR' => 'Nauru',
                  'NP' => 'Nepal',
                  'NL' => 'Netherlands',
                  'AN' => 'Netherlands Antilles',
                  'NC' => 'New Caledonia',
                  'NZ' => 'New Zealand',
                  'NI' => 'Nicaragua',
                  'NE' => 'Niger',
                  'NG' => 'Nigeria',
                  'KP' => 'North Korea (Korea, Democratic People\'s Republic of)',
                  'NO' => 'Norway',
                  'OM' => 'Oman',
                  'PK' => 'Pakistan',
                  'PA' => 'Panama',
                  'PG' => 'Papua New Guinea',
                  'PY' => 'Paraguay',
                  'PE' => 'Peru',
                  'PH' => 'Philippines',
                  'PN' => 'Pitcairn Island',
                  'PL' => 'Poland',
                  'PT' => 'Portugal',
                  'QA' => 'Qatar',
                  'RE' => 'Reunion',
                  'RO' => 'Romania',
                  'RU' => 'Russia',
                  'RW' => 'Rwanda',
                  'SH' => 'Saint Helena',
                  'KN' => 'Saint Kitts (St. Christopher and Nevis)',
                  'LC' => 'Saint Lucia',
                  'PM' => 'Saint Pierre and Miquelon',
                  'VC' => 'Saint Vincent and the Grenadines',
                  'SM' => 'San Marino',
                  'ST' => 'Sao Tome and Principe',
                  'SA' => 'Saudi Arabia',
                  'SN' => 'Senegal',
                  'YU' => 'Serbia-Montenegro',
                  'SC' => 'Seychelles',
                  'SL' => 'Sierra Leone',
                  'SG' => 'Singapore',
                  'SK' => 'Slovak Republic',
                  'SI' => 'Slovenia',
                  'SB' => 'Solomon Islands',
                  'SO' => 'Somalia',
                  'ZA' => 'South Africa',
                  'GS' => 'South Georgia (Falkland Islands)',
                  'KR' => 'South Korea (Korea, Republic of)',
                  'ES' => 'Spain',
                  'LK' => 'Sri Lanka',
                  'SD' => 'Sudan',
                  'SR' => 'Suriname',
                  'SZ' => 'Swaziland',
                  'SE' => 'Sweden',
                  'CH' => 'Switzerland',
                  'SY' => 'Syrian Arab Republic',
                  'TW' => 'Taiwan',
                  'TJ' => 'Tajikistan',
                  'TZ' => 'Tanzania',
                  'TH' => 'Thailand',
                  'TG' => 'Togo',
                  'TK' => 'Tokelau (Union) Group (Western Samoa)',
                  'TO' => 'Tonga',
                  'TT' => 'Trinidad and Tobago',
                  'TN' => 'Tunisia',
                  'TR' => 'Turkey',
                  'TM' => 'Turkmenistan',
                  'TC' => 'Turks and Caicos Islands',
                  'TV' => 'Tuvalu',
                  'UG' => 'Uganda',
                  'UA' => 'Ukraine',
                  'AE' => 'United Arab Emirates',
                  'UY' => 'Uruguay',
                  'UZ' => 'Uzbekistan',
                  'VU' => 'Vanuatu',
                  'VA' => 'Vatican City',
                  'VE' => 'Venezuela',
                  'VN' => 'Vietnam',
                  'WF' => 'Wallis and Futuna Islands',
                  'WS' => 'Western Samoa',
                  'YE' => 'Yemen',
                  'ZM' => 'Zambia',
                  'ZW' => 'Zimbabwe');

    return $list;
  }
}
?>