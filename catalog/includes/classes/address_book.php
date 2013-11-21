<?php
/**
  @package    catalog::classes
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: address_book.php v1.0 2013-08-08 datazen $
*/
global $lC_Vqmod;
require_once($lC_Vqmod->modCheck('includes/classes/address.php'));

class lC_AddressBook {
  /**
  * Returns the address book entries for the current customer
  *
  * @access public
  * @return array
  */
  public static function &getListing() {
    global $lC_Database, $lC_Customer;

    $Qaddresses = $lC_Database->query('select ab.address_book_id, ab.entry_firstname as firstname, ab.entry_lastname as lastname, ab.entry_company as company, ab.entry_street_address as street_address, ab.entry_suburb as suburb, ab.entry_city as city, ab.entry_postcode as postcode, ab.entry_state as state, ab.entry_zone_id as zone_id, ab.entry_country_id as country_id, z.zone_code as zone_code, c.countries_name as country_title from :table_address_book ab left join :table_zones z on (ab.entry_zone_id = z.zone_id), :table_countries c where ab.customers_id = :customers_id and ab.entry_country_id = c.countries_id order by ab.entry_firstname, ab.entry_lastname');
    $Qaddresses->bindTable(':table_address_book', TABLE_ADDRESS_BOOK);
    $Qaddresses->bindTable(':table_zones', TABLE_ZONES);
    $Qaddresses->bindTable(':table_countries', TABLE_COUNTRIES);
    $Qaddresses->bindInt(':customers_id', $lC_Customer->getID());
    $Qaddresses->execute();

    return $Qaddresses;
  }
  /**
  * Returns a specific address book entry for the current customer
  *
  * @param int $id The ID of the address book entry to return
  * @access public
  * @return array
  */
  public static function &getEntry($id) {
    global $lC_Database, $lC_Customer;

    $Qentry = $lC_Database->query('select entry_gender, entry_company, entry_firstname, entry_lastname, entry_street_address, entry_suburb, entry_postcode, entry_city, entry_state, entry_zone_id, entry_country_id, entry_telephone, entry_fax from :table_address_book where address_book_id = :address_book_id and customers_id = :customers_id');
    $Qentry->bindTable(':table_address_book', TABLE_ADDRESS_BOOK);
    $Qentry->bindInt(':address_book_id', $id);
    $Qentry->bindInt(':customers_id', $lC_Customer->getID());
    $Qentry->execute();

    return $Qentry;
  }
  /**
  * Verify the address book entry belongs to the current customer
  *
  * @param int $id The ID of the address book entry to verify
  * @access public
  * @return boolean
  */
  public static function checkEntry($id) {
    global $lC_Database, $lC_Customer;

    $Qentry = $lC_Database->query('select address_book_id from :table_address_book where address_book_id = :address_book_id and customers_id = :customers_id');
    $Qentry->bindTable(':table_address_book', TABLE_ADDRESS_BOOK);
    $Qentry->bindInt(':address_book_id', $id);
    $Qentry->bindInt(':customers_id', $lC_Customer->getID());
    $Qentry->execute();

    return ( $Qentry->numberOfRows() === 1 );
  }
  /**
  * Return the number of address book entries the current customer has
  *
  * @access public
  * @return integer
  */
  public static function numberOfEntries() {
    global $lC_Database, $lC_Customer;

    static $total_entries;

    if ( !isset($total_entries) ) {
      $Qaddresses = $lC_Database->query('select count(*) as total from :table_address_book where customers_id = :customers_id');
      $Qaddresses->bindTable(':table_address_book', TABLE_ADDRESS_BOOK);
      $Qaddresses->bindInt(':customers_id', $lC_Customer->getID());
      $Qaddresses->execute();

      $total_entries = $Qaddresses->valueInt('total');
    }

    return $total_entries;
  }
  /**
  * Save an address book entry
  *
  * @param array $data An array containing the address book information
  * @param int $id The ID of the address book entry to update (if this is not provided, a new address book entry is created)
  * @access public
  * @return boolean
  */
  public static function saveEntry($data, $id = '') {
    global $lC_Database, $lC_Customer;

    $updated_record = false;

    if ( is_numeric($id) ) {
      $Qab = $lC_Database->query('update :table_address_book set customers_id = :customers_id, entry_gender = :entry_gender, entry_company = :entry_company, entry_firstname = :entry_firstname, entry_lastname = :entry_lastname, entry_street_address = :entry_street_address, entry_suburb = :entry_suburb, entry_postcode = :entry_postcode, entry_city = :entry_city, entry_state = :entry_state, entry_country_id = :entry_country_id, entry_zone_id = :entry_zone_id, entry_telephone = :entry_telephone, entry_fax = :entry_fax where address_book_id = :address_book_id and customers_id = :customers_id');
      $Qab->bindInt(':address_book_id', $id);
      $Qab->bindInt(':customers_id', $lC_Customer->getID());
    } else {
      $Qab = $lC_Database->query('insert into :table_address_book (customers_id, entry_gender, entry_company, entry_firstname, entry_lastname, entry_street_address, entry_suburb, entry_postcode, entry_city, entry_state, entry_country_id, entry_zone_id, entry_telephone, entry_fax) values (:customers_id, :entry_gender, :entry_company, :entry_firstname, :entry_lastname, :entry_street_address, :entry_suburb, :entry_postcode, :entry_city, :entry_state, :entry_country_id, :entry_zone_id, :entry_telephone, :entry_fax)');
    }

    $Qab->bindTable(':table_address_book', TABLE_ADDRESS_BOOK);
    $Qab->bindInt(':customers_id', $lC_Customer->getID());
    $Qab->bindValue(':entry_gender', ((ACCOUNT_GENDER > -1) && isset($data['gender']) && (($data['gender'] == 'm') || ($data['gender'] == 'f'))) ? $data['gender'] : '');
    $Qab->bindValue(':entry_company', (ACCOUNT_COMPANY > -1) ? $data['company'] : '');
    $Qab->bindValue(':entry_firstname', $data['firstname']);
    $Qab->bindValue(':entry_lastname', $data['lastname']);
    $Qab->bindValue(':entry_street_address', $data['street_address']);
    $Qab->bindValue(':entry_suburb', (ACCOUNT_SUBURB > -1) ? $data['suburb'] : '');
    $Qab->bindValue(':entry_postcode', (ACCOUNT_POST_CODE > -1) ? $data['postcode'] : '');
    $Qab->bindValue(':entry_city', $data['city']);
    $Qab->bindValue(':entry_state', (ACCOUNT_STATE > -1) ? ((isset($data['zone_id']) && ($data['zone_id'] > 0)) ? '' : $data['state']) : '');
    $Qab->bindInt(':entry_country_id', $data['country']);
    $Qab->bindInt(':entry_zone_id', (ACCOUNT_STATE > -1) ? ((isset($data['zone_id']) && ($data['zone_id'] > 0)) ? $data['zone_id'] : 0) : '');
    $Qab->bindValue(':entry_telephone', (ACCOUNT_TELEPHONE > -1) ? $data['telephone'] : '');
    $Qab->bindValue(':entry_fax', (ACCOUNT_FAX > -1) ? $data['fax'] : '');
    $Qab->execute();

    if ( $Qab->affectedRows() === 1 ) {
      $updated_record = true;
    }

    if ( isset($data['primary']) && ($data['primary'] === true) ) {
      if ( !is_numeric($id) ) {
        $id = $lC_Database->nextID();
      }

      if ( lC_AddressBook::setPrimaryAddress($id) ) {
        $lC_Customer->setCountryID($data['country']);
        $lC_Customer->setZoneID(($data['zone_id'] > 0) ? (int)$data['zone_id'] : '0');
        $lC_Customer->setDefaultAddressID($id);

        if ( $updated_record === false ) {
          $updated_record = true;
        }
      }
    }

    if ( $updated_record === true ) {
      return true;
    }

    return false;
  }
  /**
  * Set the address book entry as the primary address for the current customer
  *
  * @param int $id The ID of the address book entry
  * @access public
  * @return boolean
  */
  public static function setPrimaryAddress($id) {
    global $lC_Database, $lC_Customer;

    if ( is_numeric($id) && ($id > 0) ) {
      $Qupdate = $lC_Database->query('update :table_customers set customers_default_address_id = :customers_default_address_id where customers_id = :customers_id');
      $Qupdate->bindTable(':table_customers', TABLE_CUSTOMERS);
      $Qupdate->bindInt(':customers_default_address_id', $id);
      $Qupdate->bindInt(':customers_id', $lC_Customer->getID());
      $Qupdate->execute();

      return ( $Qupdate->affectedRows() === 1 );
    }

    return false;
  }
  /**
  * Delete an address book entry
  *
  * @param int $id The ID of the address book entry to delete
  * @access public
  * @return boolean
  */
  public static function deleteEntry($id) {
    global $lC_Database, $lC_Customer;

    $Qdelete = $lC_Database->query('delete from :table_address_book where address_book_id = :address_book_id and customers_id = :customers_id');
    $Qdelete->bindTable(':table_address_book', TABLE_ADDRESS_BOOK);
    $Qdelete->bindInt(':address_book_id', $id);
    $Qdelete->bindInt(':customers_id', $lC_Customer->getID());
    $Qdelete->execute();

    return ( $Qdelete->affectedRows() === 1 );
  }
 /*
  * Returns the zones dropdown field
  *
  * @access public
  * @return string
  */
  public static function getZonesField($country) {
    global $lC_Database, $lC_Language, $lC_Template, $entry_state_has_zones;

    if ( (isset($_GET['new']) && ($_GET['new'] == 'save')) || (isset($_GET['edit']) && ($_GET['edit'] == 'save')) || (isset($_GET[$lC_Template->getModule()]) && ($_GET[$lC_Template->getModule()] == 'process')) ) {
      if ($entry_state_has_zones === true) {
        $Qzones = $lC_Database->query('select zone_name from :table_zones where zone_country_id = :zone_country_id order by zone_name');
        $Qzones->bindTable(':table_zones', TABLE_ZONES);
        $Qzones->bindInt(':zone_country_id', $country);
        $Qzones->execute();

        $zones_array = array();
        while ($Qzones->next()) {
          $zones_array[] = array('id' => $Qzones->value('zone_name'), 'text' => $Qzones->value('zone_name'));
        }
        $output = lc_draw_pull_down_menu('state', $zones_array);
      } else {
        $output = lc_draw_input_field('state');
      }
    } else {
      if (isset($Qentry)) {
        $zone = $Qentry->value('entry_state');
        if ($Qentry->valueInt('entry_zone_id') > 0) {
          $zone = lC_Address::getZoneName($Qentry->valueInt('entry_zone_id'));
        }
      }
      $output = lc_draw_input_field('state', (isset($Qentry) ? $zone : null));
    }    

    return $output;    
  }   
 /**
  * Return the zones dropdown array
  *
  * @access public
  * @return json
  */
  public static function getZonesDropdownHtml($countries_id, $zone = null) {
    global $lC_Database, $lC_Language;

    $Qzones = $lC_Database->query('select zone_name from :table_zones where zone_country_id = :zone_country_id order by zone_name');
    $Qzones->bindTable(':table_zones', TABLE_ZONES);
    $Qzones->bindInt(':zone_country_id', $countries_id);
    $Qzones->execute();

    $result = array();
    if ($Qzones->numberOfRows() > 0) {
      $zones_array = array();
      while ($Qzones->next()) {
        $zones_array[] = array('id' => $Qzones->value('zone_name'), 'text' => $Qzones->value('zone_name'));
      }
      $zone_name = (isset($zone) && is_numeric($zone) && $zone != 0) ? lC_Address::getZoneName($zone) : NULL;
      $result['zonesHtml'] = lc_draw_label('', null, 'state') . lc_draw_pull_down_menu('state', $zones_array, $zone_name, 'class="form-control"');
      $result['single'] = '0';

    } else {                      
      $result['zonesHtml'] = lc_draw_label('', null, 'state') . ' ' . lc_draw_input_field('state', (($zone != 'undefined') ? $zone : null), 'placeholder="' . $lC_Language->get('field_customer_state') ./* '" onfocus="this.placeholder = \'\'" onblur="this.placeholder = \'' . $lC_Language->get('field_customer_state') . '\'" */'"class="required form-control" style="width:99.7%;"');
      $result['single'] = '1';
    }
    
    return $result;
  }  
 /*
  * Returns the countries dropdown array
  *
  * @access public
  * @return array
  */
  public static function getCountriesDropdownArray() {
    global $lC_Language;
    
    $countries_array = array(array('id' => '',
                                   'text' => $lC_Language->get('pull_down_default')));
                                   
    foreach (lC_Address::getCountries() as $country) {
      $countries_array[] = array('id' => $country['id'],
                                 'text' => $country['name']);
    } 
    
    return $countries_array;   
  }  
}
?>