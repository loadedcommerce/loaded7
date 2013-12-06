<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: countries.php v1.0 2013-08-08 datazen $
*/
class lC_Countries_Admin {
 /*
  * Returns the countries datatable data for listings
  *
  * @access public
  * @return array
  */
  public static function getAll() {
    global $lC_Database, $lC_Language, $_module;

    $result = array('entries' => array());
    $result = array('aaData' => array());
    
    $media = $_GET['media'];    

    /* Total Records */
    $QresultTotal = $lC_Database->query('select count(*) as total from :table_countries');
    $QresultTotal->bindTable(':table_countries', TABLE_COUNTRIES);
    $QresultTotal->execute();
    $result['iTotalRecords'] = $QresultTotal->valueInt('total');
    $QresultTotal->freeResult();

    /* Paging */
    $sLimit = "";
    if (isset($_GET['iDisplayStart'])) {
      if ($_GET['iDisplayLength'] != -1) {
        $sLimit = " LIMIT " . $_GET['iDisplayStart'] . ", " . $_GET['iDisplayLength'];
      }
    }

    /* Ordering */
    if (isset($_GET['iSortCol_0'])) {
      $sOrder = " ORDER BY ";
      for ($i=0 ; $i < (int)$_GET['iSortingCols'] ; $i++ ) {
        $sOrder .= lC_Countries_Admin::fnColumnToField($_GET['iSortCol_'.$i] ) . " " . $_GET['sSortDir_'.$i] .", ";
      }
      $sOrder = substr_replace( $sOrder, "", -2 );
    }

    /* Filtering */
    $sWhere = "";
    if ($_GET['sSearch'] != "") {
      $sWhere = " WHERE countries_name LIKE '%" . $_GET['sSearch'] . "%' OR " .
                       "countries_iso_code_2 LIKE '%" . $_GET['sSearch'] . "%' OR " .
                       "countries_iso_code_3 LIKE '%" . $_GET['sSearch'] . "%' ";
    }

    /* Total Filtered Records */
    $QresultFilterTotal = $lC_Database->query('select count(*) as total from :table_countries' . $sWhere . $sOrder);
    $QresultFilterTotal->bindTable(':table_countries', TABLE_COUNTRIES);
    $QresultFilterTotal->execute();
    $result['iTotalDisplayRecords'] = $QresultFilterTotal->valueInt('total');
    $QresultFilterTotal->freeResult();

    /* Main Listing Query */
    $Qcountries = $lC_Database->query('select * from :table_countries' . $sWhere . $sOrder . $sLimit);
    $Qcountries->bindTable(':table_countries', TABLE_COUNTRIES);
    $Qcountries->execute();

    while ( $Qcountries->next() ) {
      $Qzones = $lC_Database->query('select count(*) as total_zones from :table_zones where zone_country_id = :zone_country_id');
      $Qzones->bindTable(':table_zones', TABLE_ZONES);
      $Qzones->bindInt(':zone_country_id', $Qcountries->valueInt('countries_id'));
      $Qzones->execute();

      $check = '<td><input class="batch" type="checkbox" name="batch[]" value="' . $Qcountries->valueInt('countries_id') . '" id="' . $Qcountries->valueInt('countries_id') . '"></td>';
      $name = '<td><a href="' . lc_href_link_admin(FILENAME_DEFAULT, 'countries=' . $Qcountries->valueInt('countries_id')) . '"><span class="icon-folder icon-orange"></span>&nbsp;' . $Qcountries->valueProtected('countries_name') . '</a></td>';
      $code = '<td>
                 <table width="100%" border="0">
                   <tr>
                     <td>' . lc_image('../images/worldflags/' . strtolower($Qcountries->value('countries_iso_code_2')) . '.png', $Qcountries->value('countries_iso_code_3')) . '</td>
                     <td align="center" width="50%">' . $Qcountries->value('countries_iso_code_2') . '</td>
                     <td align="center" width="50%">' . $Qcountries->value('countries_iso_code_3') . '</td>
                   </tr>
                 </table>
               </td>';
      $total = '<td>' . $Qzones->valueInt('total_zones') . '</td>';
      $action = '<td class="align-right vertical-center"><span class="button-group compact" style="white-space:nowrap;">
                   <a href="' . ((int)($_SESSION['admin']['access']['locale'] < 3) ? '#' : 'javascript://" onclick="editCountry(\'' . $Qcountries->valueInt('countries_id') . '\')') . '" class="button icon-pencil' . ((int)($_SESSION['admin']['access']['locale'] < 3) ? ' disabled' : NULL) . '">' .  (($media === 'mobile-portrait' || $media === 'mobile-landscape') ? NULL : $lC_Language->get('icon_edit')) . '</a>
                   <a href="' . ((int)($_SESSION['admin']['access']['locale'] < 4) ? '#' : 'javascript://" onclick="deleteCountry(\'' . $Qcountries->valueInt('countries_id') . '\', \'' . urlencode($Qcountries->valueProtected('countries_name')) . '\')') . '" class="button icon-trash with-tooltip' . ((int)($_SESSION['admin']['access']['locale'] < 4) ? ' disabled' : NULL) . '" title="' . $lC_Language->get('icon_delete') . '"></a>
                 </span></td>';

      $result['aaData'][] = array("$check", "$name", "$code", "$total", "$action");
      $result['entries'][] = array_merge($Qcountries->toArray(), $Qzones->toArray());
    }
    $result['sEcho'] = intval($_GET['sEcho']);

    if ( $Qcountries->numberOfRows() > 0 ) {
      $Qzones->freeResult();
    }

    $Qcountries->freeResult();

    return $result;
  }
 /*
  * Returns the data used on the dialog forms
  *
  * @param integer $id The countries id
  * @param boolean $edit True = called from edit dialog else called from delete dialog
  * @access public
  * @return array
  */
  public static function getFormData($id = null, $edit = false) {
    global $lC_Database;

    $result = array();
    $result['cData'] = array();
    if (isset($id) && $id != null) {
      if ($edit === true) {
        $result['cData'] = lC_Countries_Admin::getData($id);
      } else {
        $Qcheck = $lC_Database->query('select count(*) as total from :table_address_book where entry_country_id = :entry_country_id');
        $Qcheck->bindTable(':table_address_book', TABLE_ADDRESS_BOOK);
        $Qcheck->bindInt(':entry_country_id', $id);
        $Qcheck->execute();
        if ( $Qcheck->valueInt('total') > 0 ) {
          $result['rpcStatus'] = -2;
          $result['totalAddressBookEntries'] = $Qcheck->valueInt('total');
        }
        $Qcheck = $lC_Database->query('select count(*) as total from :table_zones_to_geo_zones where zone_country_id = :zone_country_id');
        $Qcheck->bindTable(':table_zones_to_geo_zones', TABLE_ZONES_TO_GEO_ZONES);
        $Qcheck->bindInt(':zone_country_id', $id);
        $Qcheck->execute();
        if ( $Qcheck->valueInt('total') > 0 ) {
          $result['rpcStatus'] = -3;
          $result['totalZones'] = $Qcheck->valueInt('total');
        }
      }
    }

    return $result;
  }
 /*
  * Returns the country information
  *
  * @param integer $id The country id
  * @access public
  * @return array
  */
  public static function getData($id) {
    global $lC_Database;

    $Qcountries = $lC_Database->query('select * from :table_countries where countries_id = :countries_id');
    $Qcountries->bindTable(':table_countries', TABLE_COUNTRIES);
    $Qcountries->bindInt(':countries_id', $id);
    $Qcountries->execute();

    $Qzones = $lC_Database->query('select count(*) as total_zones from :table_zones where zone_country_id = :zone_country_id');
    $Qzones->bindTable(':table_zones', TABLE_ZONES);
    $Qzones->bindInt(':zone_country_id', $id);
    $Qzones->execute();

    $data = array_merge($Qcountries->toArray(), $Qzones->toArray());

    $Qzones->freeResult();
    $Qcountries->freeResult();

    return $data;
  }
 /*
  * Saves the country information
  *
  * @param integer $id The country id used on update, null on insert
  * @param array $data An array containing the country information
  * @access public
  * @return boolean
  */
  public static function save($id = null, $data) {
    global $lC_Database;

    if ( is_numeric($id) ) {
      $Qcountry = $lC_Database->query('update :table_countries set countries_name = :countries_name, countries_iso_code_2 = :countries_iso_code_2, countries_iso_code_3 = :countries_iso_code_3, address_format = :address_format where countries_id = :countries_id');
      $Qcountry->bindInt(':countries_id', $id);
    } else {
      $Qcountry = $lC_Database->query('insert into :table_countries (countries_name, countries_iso_code_2, countries_iso_code_3, address_format) values (:countries_name, :countries_iso_code_2, :countries_iso_code_3, :address_format)');
    }

    $Qcountry->bindTable(':table_countries', TABLE_COUNTRIES);
    $Qcountry->bindValue(':countries_name', $data['countries_name']);
    $Qcountry->bindValue(':countries_iso_code_2', $data['countries_iso_code_2']);
    $Qcountry->bindValue(':countries_iso_code_3', $data['countries_iso_code_3']);
    $Qcountry->bindValue(':address_format', $data['address_format']);
    $Qcountry->setLogging($_SESSION['module'], $id);
    $Qcountry->execute();

    if ( !$lC_Database->isError() ) {
      return true;
    }

    return false;
  }
 /*
  * Delete the country record
  *
  * @param integer $id The country id to delete
  * @access public
  * @return boolean
  */
  public static function delete($id) {
    global $lC_Database;

    $error = false;

    $lC_Database->startTransaction();

    $Qzones = $lC_Database->query('delete from :table_zones where zone_country_id = :zone_country_id');
    $Qzones->bindTable(':table_zones', TABLE_ZONES);
    $Qzones->bindInt(':zone_country_id', $id);
    $Qzones->setLogging($_SESSION['module'], $id);
    $Qzones->execute();

    if ( !$lC_Database->isError() ) {
      $Qcountry = $lC_Database->query('delete from :table_countries where countries_id = :countries_id');
      $Qcountry->bindTable(':table_countries', TABLE_COUNTRIES);
      $Qcountry->bindInt(':countries_id', $id);
      $Qcountry->setLogging($_SESSION['module'], $id);
      $Qcountry->execute();

      if ( $lC_Database->isError() ) {
        $error = true;
      }
    } else {
      $error = true;
    }

    if ( $error === false ) {
      $lC_Database->commitTransaction();

      return true;
    }

    $lC_Database->rollbackTransaction();

    return false;
  }
 /*
  * Batch delete country records
  *
  * @param array $batch An array of country id's to delete
  * @access public
  * @return array
  */
  public static function batchDelete($batch) {
    global $lC_Language, $lC_Database;

    $lC_Language->loadIniFile('countries.php');

    $Qcountries = $lC_Database->query('select countries_id, countries_name from :table_countries where countries_id in (":countries_id") order by countries_name');
    $Qcountries->bindTable(':table_countries', TABLE_COUNTRIES);
    $Qcountries->bindRaw(':countries_id', implode('", "', array_unique(array_filter(array_slice($batch, 0, MAX_DISPLAY_SEARCH_RESULTS), 'is_numeric'))));
    $Qcountries->execute();
    $names_string = '';
    while ( $Qcountries->next() ) {
      $Qcheck = $lC_Database->query('select count(address_book_id) as total from :table_address_book where entry_country_id = :entry_country_id limit 1');
      $Qcheck->bindTable(':table_address_book', TABLE_ADDRESS_BOOK);
      $Qcheck->bindInt(':entry_country_id', $Qcountries->valueInt('countries_id'));
      $Qcheck->execute();
      if ( $Qcheck->valueInt('total') > 0 ) {
        $names_string .= $Qcountries->valueProtected('countries_name') . ' (' . $Qcheck->valueInt('total') . ' ' . $lC_Language->get('address_book_entries') . '), ';
      }
      $Qzcheck = $lC_Database->query('select count(association_id) as total_zones from :table_zones_to_geo_zones where zone_country_id = :zone_country_id limit 1');
      $Qzcheck->bindTable(':table_zones_to_geo_zones', TABLE_ZONES_TO_GEO_ZONES);
      $Qzcheck->bindInt(':zone_country_id', $Qcountries->valueInt('countries_id'));
      $Qzcheck->execute();
      if ( $Qzcheck->valueInt('total_zones') > 0 ) {
        $names_string .= $Qcountries->valueProtected('countries_name') . ' (' . $Qzcheck->valueInt('total_zones') . ' ' . $lC_Language->get('tax_zones') . '), ';
      }
      if (($Qzcheck->valueInt('total_zones') == 0) && ( $Qcheck->valueInt('total') == 0 )) {
        lC_Countries_Admin::delete($Qcountries->valueInt('countries_id'));
      }
      $Qcheck->freeResult();
      $Qzcheck->freeResult();
    }
    if ( !empty($names_string) ) {
      $names_string = substr($names_string, 0, -2);
    }

    $result['namesString'] = $names_string;

    $Qcountries->freeResult();

    return $result;
  }
 /*
  * Returns the zone information
  *
  * @param integer $country_id The country id
  * @access public
  * @return array
  */
  public static function getAllZones($country_id) {
    global $lC_Database, $lC_Language, $_module;

    $result = array('aaData' => array());
    $result = array('entries' => array());
    
    $media = $_GET['media'];    

    $Qzones = $lC_Database->query('select * from :table_zones where zone_country_id = :zone_country_id');
    $Qzones->bindTable(':table_zones', TABLE_ZONES);
    $Qzones->bindInt(':zone_country_id', $country_id);
    $Qzones->execute();

    while ( $Qzones->next() ) {
      $check = '<td><input class="batch" type="checkbox" name="batch[]" value="' . $Qzones->valueInt('zone_id') . '" id="' . $Qzones->valueInt('zone_id') . '"></td>';
      $zone = '<td>' . $Qzones->value('zone_name') . '</td>';
      $code = '<td>' . $Qzones->value('zone_code') . '</td>';
      $action = '<td class="align-right vertical-center"><span class="button-group compact">
                   <a href="' . ((int)($_SESSION['admin']['access'][$_module] < 3) ? '#' : 'javascript://" onclick="editZone(\'' . $Qzones->valueInt('zone_id') . '\')') . '" class="button icon-pencil' . ((int)($_SESSION['admin']['access'][$_module] < 3) ? ' disabled' : NULL) . '">' .  (($media === 'mobile-portrait' || $media === 'mobile-landscape') ? NULL : $lC_Language->get('icon_edit')) . '</a>
                   <a href="' . ((int)($_SESSION['admin']['access'][$_module] < 4) ? '#' : 'javascript://" onclick="deleteZone(\'' . $Qzones->valueInt('zone_id') . '\', \'' . urlencode($Qzones->valueProtected('zone_name')) . '\')') . '" class="button icon-trash with-tooltip' . ((int)($_SESSION['admin']['access'][$_module] < 4) ? ' disabled' : NULL) . '" title="' . $lC_Language->get('icon_delete') . '"></a>
                 </span></td>';

      $result['aaData'][] = array("$check", "$zone", "$code", "$action");
      $result['entries'][] = $Qzones->toArray();
    }

    $Qzones->freeResult();

    return $result;
  }
 /*
  * Returns the zone data used on the dialog forms
  *
  * @param integer $id The zone id
  * @param boolean $edit True = called from edit dialog else called from delete dialog
  * @access public
  * @return array
  */
  public static function getZoneFormData($id = null, $edit = false) {
    global $lC_Database;

    $result = array();
    $result['zData'] = array();
    if (isset($id) && $id != null) {
      if ($edit === true) {
        $result['zData'] = lC_Countries_Admin::getZone($id);
      } else {
        $Qcheck = $lC_Database->query('select count(*) as total from :table_address_book where entry_zone_id = :entry_zone_id');
        $Qcheck->bindTable(':table_address_book', TABLE_ADDRESS_BOOK);
        $Qcheck->bindInt(':entry_zone_id', $id);
        $Qcheck->execute();
        if ( $Qcheck->valueInt('total') > 0 ) {
          $result['rpcStatus'] = -2;
          $result['totalAddressBookEntries'] = $Qcheck->valueInt('total');
        }
        $Qcheck = $lC_Database->query('select count(*) as total from :table_zones_to_geo_zones where zone_id = :zone_id');
        $Qcheck->bindTable(':table_zones_to_geo_zones', TABLE_ZONES_TO_GEO_ZONES);
        $Qcheck->bindInt(':zone_id', $id);
        $Qcheck->execute();
        if ( $Qcheck->valueInt('total') > 0 ) {
          $result['rpcStatus'] = -3;
          $result['totalZones'] = $Qcheck->valueInt('total');
        }
      }
    }

    return $result;
  }
 /*
  * Get the zone information
  *
  * @param integer $id The zone id
  * @access public
  * @return array
  */
  public static function getZone($id) {
    global $lC_Database;

    $Qzones = $lC_Database->query('select * from :table_zones where zone_id = :zone_id');
    $Qzones->bindTable(':table_zones', TABLE_ZONES);
    $Qzones->bindInt(':zone_id', $id);
    $Qzones->execute();

    $data = $Qzones->toArray();

    $Qzones->freeResult();

    return $data;
  }
 /*
  * Saves the zone information
  *
  * @param integer $id The zone id used on update, null on insert
  * @param array $data An array containing the zone information
  * @access public
  * @return boolean
  */
  public static function saveZone($id = null, $data) {
    global $lC_Database;

    if ( isset($id) && $id != null ) {
      $Qzone = $lC_Database->query('update :table_zones set zone_name = :zone_name, zone_code = :zone_code, zone_country_id = :zone_country_id where zone_id = :zone_id');
      $Qzone->bindInt(':zone_id', $id);
    } else {
      $Qzone = $lC_Database->query('insert into :table_zones (zone_name, zone_code, zone_country_id) values (:zone_name, :zone_code, :zone_country_id)');
    }
    $Qzone->bindTable(':table_zones', TABLE_ZONES);
    $Qzone->bindValue(':zone_name', $data['zone_name']);
    $Qzone->bindValue(':zone_code', $data['zone_code']);
    $Qzone->bindInt(':zone_country_id', $data['countries']);
    $Qzone->setLogging($_SESSION['module'], $id);
    $Qzone->execute();

    if ( !$lC_Database->isError() ) {
      return true;
    }

    return false;
  }
 /*
  * Delete the zone record
  *
  * @param integer $id The zone id to delete
  * @access public
  * @return boolean
  */
  public static function deleteZone($id) {
    global $lC_Database;

    $Qzone = $lC_Database->query('delete from :table_zones where zone_id = :zone_id');
    $Qzone->bindTable(':table_zones', TABLE_ZONES);
    $Qzone->bindInt(':zone_id', $id);
    $Qzone->setLogging($_SESSION['module'], $id);
    $Qzone->execute();

    if ( !$lC_Database->isError() ) {
      return true;
    }

    return false;
  }
 /*
  * Batch delete zones
  *
  * @param array $batch An array of zone id's to delete
  * @access public
  * @return array
  */
  public static function batchDeleteZones($batch) {
    global $lC_Language, $lC_Database;

    $lC_Language->loadIniFile('countries.php');

    $Qzones = $lC_Database->query('select zone_id, zone_name, zone_code from :table_zones where zone_id in (":zone_id") order by zone_name');
    $Qzones->bindTable(':table_zones', TABLE_ZONES);
    $Qzones->bindRaw(':zone_id', implode('", "', array_unique(array_filter($batch, 'is_numeric'))));
    $Qzones->execute();
    $names_string = '';
    while ( $Qzones->next() ) {
      $Qcheck = $lC_Database->query('select count(address_book_id) as total from :table_address_book where entry_zone_id = :entry_zone_id limit 1');
      $Qcheck->bindTable(':table_address_book', TABLE_ADDRESS_BOOK);
      $Qcheck->bindInt(':entry_zone_id', $Qzones->valueInt('zone_id'));
      $Qcheck->execute();
      if ( $Qcheck->valueInt('total') > 0 ) {
        $names_string .= $Qzones->valueProtected('zone_name') . ' (' . $Qcheck->valueInt('total') . ' ' . $lC_Language->get('address_book_entries') . '), ';
      }
      $Qzcheck = $lC_Database->query('select count(association_id) as total_zones from :table_zones_to_geo_zones where zone_id = :zone_id limit 1');
      $Qzcheck->bindTable(':table_zones_to_geo_zones', TABLE_ZONES_TO_GEO_ZONES);
      $Qzcheck->bindInt(':zone_id', $Qzones->valueInt('zone_id'));
      $Qzcheck->execute();
      if ( $Qzcheck->valueInt('total_zones') > 0 ) {
        $names_string .= $Qzones->valueProtected('zone_name') . ' (' . $Qzcheck->valueInt('total_zones') . ' ' . $lC_Language->get('tax_zones') . '), ';
      }
      if (($Qzcheck->valueInt('total_zones') == 0) && ( $Qcheck->valueInt('total') == 0 )) {
        lC_Countries_Admin::deleteZone($Qzones->valueInt('zone_id'));
      }
      $Qcheck->freeResult();
      $Qzcheck->freeResult();
    }
    if ( !empty($names_string) ) {
      $names_string = substr($names_string, 0, -2);
    }

    $result['namesString'] = $names_string;

    $Qzones->freeResult();

    return $result;
  }
 /*
  * Returns database column for datatable search
  *
  * @param integer $i The datatable sort column
  * @access private
  * @return string
  */
  private static function fnColumnToField($i) {
   if ( $i == 0 )
    return "countries_name";
   else if ( $i == 1 )
    return "countries_iso_code_2";
   else if ( $i == 2 )
    return "countries_iso_code_3";
  }
}
?>