<?php
/*
  $Id: address.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

/**
 * The lC_Address class handles address related functions such as the format and country and zone information
 */

  class lC_Address {

/**
 * Correctly format an address to the address format rule assigned to its country
 *
 * @param array $address An array (or address_book ID) containing the address information
 * @param string $new_line The string to break new lines with
 * @access public
 * @return string
 */

    public static function format($address, $new_line = null) {
      global $lC_Database;

      $address_format = '';

      if ( is_numeric($address) ) {
        $Qaddress = $lC_Database->query('select ab.entry_firstname as firstname, ab.entry_lastname as lastname, ab.entry_company as company, ab.entry_street_address as street_address, ab.entry_suburb as suburb, ab.entry_city as city, ab.entry_postcode as postcode, ab.entry_state as state, ab.entry_zone_id as zone_id, ab.entry_country_id as country_id, z.zone_code as zone_code, c.countries_name as country_title from :table_address_book ab left join :table_zones z on (ab.entry_zone_id = z.zone_id), :table_countries c where ab.address_book_id = :address_book_id and ab.entry_country_id = c.countries_id');
        $Qaddress->bindTable(':table_address_book', TABLE_ADDRESS_BOOK);
        $Qaddress->bindTable(':table_zones', TABLE_ZONES);
        $Qaddress->bindTable(':table_countries', TABLE_COUNTRIES);
        $Qaddress->bindInt(':address_book_id', $address);
        $Qaddress->execute();

        $address = $Qaddress->toArray();
      }

      $firstname = $lastname = '';

      if ( isset($address['firstname']) && !empty($address['firstname']) ) {
        $firstname = $address['firstname'];
        $lastname = $address['lastname'];
      } elseif ( isset($address['name']) && !empty($address['name']) ) {
        $firstname = $address['name'];
      }

      $state = $address['state'];
      $state_code = $address['zone_code'];

      if ( isset($address['zone_id']) && is_numeric($address['zone_id']) && ($address['zone_id'] > 0) ) {
        $state = lC_Address::getZoneName($address['zone_id']);
        $state_code = lC_Address::getZoneCode($address['zone_id']);
      }

      $country = $address['country_title'];

      if ( empty($country) && isset($address['country_id']) && is_numeric($address['country_id']) && ($address['country_id'] > 0) ) {
        $country = lC_Address::getCountryName($address['country_id']);
      }

      if ( isset($address['format']) ) {
        $address_format = $address['format'];
      } elseif ( isset($address['country_id']) && is_numeric($address['country_id']) && ($address['country_id'] > 0) ) {
        $address_format = lC_Address::getFormat($address['country_id']);
      }

      if ( empty($address_format) ) {
        $address_format = ":name\n:street_address\n:postcode :city\n:country";
      }

      $find_array = array('/\:name\b/',
                          '/\:street_address\b/',
                          '/\:suburb\b/',
                          '/\:city\b/',
                          '/\:postcode\b/',
                          '/\:state\b/',
                          '/\:state_code\b/',
                          '/\:country\b/');

      $replace_array = array(lc_output_string_protected($firstname . ' ' . $lastname),
                             lc_output_string_protected($address['street_address']),
                             lc_output_string_protected($address['suburb']),
                             lc_output_string_protected($address['city']),
                             lc_output_string_protected($address['postcode']),
                             lc_output_string_protected($state),
                             lc_output_string_protected($state_code),
                             lc_output_string_protected($country));

      $formated = preg_replace($find_array, $replace_array, $address_format);

      if ( (ACCOUNT_COMPANY > -1) && !empty($address['company']) ) {
        $formated = lc_output_string_protected($address['company']) . "\n" . $formated;
      }

      if ( !empty($new_line) ) {
        $formated = str_replace("\n", $new_line, $formated);
      }

      return $formated;
    }

/**
 * Return all countries in an array
 *
 * @access public
 * @return array
 */

    public static function getCountries() {
      global $lC_Database;

      static $countries;

      if ( !isset($countries) ) {
        $countries = array();

        $Qcountries = $lC_Database->query('select * from :table_countries order by countries_name');
        $Qcountries->bindTable(':table_countries', TABLE_COUNTRIES);
        $Qcountries->execute();

        while ( $Qcountries->next() ) {
          $countries[] = array('id' => $Qcountries->valueInt('countries_id'),
                               'name' => $Qcountries->value('countries_name'),
                               'iso_2' => $Qcountries->value('countries_iso_code_2'),
                               'iso_3' => $Qcountries->value('countries_iso_code_3'),
                               'format' => $Qcountries->value('address_format'));
        }

        $Qcountries->freeResult();
      }

      return $countries;
    }

/**
 * Return the country name
 *
 * @param int $id The ID of the country
 * @access public
 * @return string
 */

    public static function getCountryName($id) {
      global $lC_Database;

      $Qcountry = $lC_Database->query('select countries_name from :table_countries where countries_id = :countries_id');
      $Qcountry->bindTable(':table_countries', TABLE_COUNTRIES);
      $Qcountry->bindInt(':countries_id', $id);
      $Qcountry->execute();

      return $Qcountry->value('countries_name');
    }

/**
 * Return the country 2 character ISO code
 *
 * @param int $id The ID of the country
 * @access public
 * @return string
 */

    public static function getCountryIsoCode2($id) {
      global $lC_Database;

      $Qcountry = $lC_Database->query('select countries_iso_code_2 from :table_countries where countries_id = :countries_id');
      $Qcountry->bindTable(':table_countries', TABLE_COUNTRIES);
      $Qcountry->bindInt(':countries_id', $id);
      $Qcountry->execute();

      return $Qcountry->value('countries_iso_code_2');
    }

/**
 * Return the country 3 character ISO code
 *
 * @param int $id The ID of the country
 * @access public
 * @return string
 */

    public static function getCountryIsoCode3($id) {
      global $lC_Database;

      $Qcountry = $lC_Database->query('select countries_iso_code_3 from :table_countries where countries_id = :countries_id');
      $Qcountry->bindTable(':table_countries', TABLE_COUNTRIES);
      $Qcountry->bindInt(':countries_id', $id);
      $Qcountry->execute();

      return $Qcountry->value('countries_iso_code_3');
    }

/**
 * Return the address format rule for the country
 *
 * @param int $id The ID of the country
 * @access public
 * @return string
 */

    public static function getFormat($id) {
      global $lC_Database;

      $Qcountry = $lC_Database->query('select address_format from :table_countries where countries_id = :countries_id');
      $Qcountry->bindTable(':table_countries', TABLE_COUNTRIES);
      $Qcountry->bindInt(':countries_id', $id);
      $Qcountry->execute();

      return $Qcountry->value('address_format');
    }

/**
 * Return the zone name
 *
 * @param int $id The ID of the zone
 * @access public
 * @return string
 */

    public static function getZoneName($id) {
      global $lC_Database;

      $Qzone = $lC_Database->query('select zone_name from :table_zones where zone_id = :zone_id');
      $Qzone->bindTable(':table_zones', TABLE_ZONES);
      $Qzone->bindInt(':zone_id', $id);
      $Qzone->execute();

      return $Qzone->value('zone_name');
    }

/**
 * Return the zone code
 *
 * @param int $id The ID of the zone
 * @access public
 * @return string
 */

    public static function getZoneCode($id) {
      global $lC_Database;

      $Qzone = $lC_Database->query('select zone_code from :table_zones where zone_id = :zone_id');
      $Qzone->bindTable(':table_zones', TABLE_ZONES);
      $Qzone->bindInt(':zone_id', $id);
      $Qzone->execute();

      return $Qzone->value('zone_code');
    }

/**
 * Return the zones belonging to a country, or all zones
 *
 * @param int $id The ID of the country
 * @access public
 * @return array
 */

    public static function getZones($id = null) {
      global $lC_Database;

      $zones_array = array();

      $Qzones = $lC_Database->query('select z.zone_id, z.zone_country_id, z.zone_name, c.countries_name from :table_zones z, :table_countries c where');

      if ( !empty($id) ) {
        $Qzones->appendQuery('z.zone_country_id = :zone_country_id and');
        $Qzones->bindInt(':zone_country_id', $id);
      }

      $Qzones->appendQuery('z.zone_country_id = c.countries_id order by c.countries_name, z.zone_name');
      $Qzones->bindTable(':table_countries', TABLE_COUNTRIES);
      $Qzones->bindTable(':table_zones', TABLE_ZONES);
      $Qzones->execute();

      while ( $Qzones->next() ) {
        $zones_array[] = array('id' => $Qzones->valueInt('zone_id'),
                               'name' => $Qzones->value('zone_name'),
                               'country_id' => $Qzones->valueInt('zone_country_id'),
                               'country_name' => $Qzones->value('countries_name'));
      }

      return $zones_array;
    }
  }
?>
