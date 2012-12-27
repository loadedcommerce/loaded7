<?php
/*
  $Id: zone_groups.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html

  @function The lC_Zone_groups_Admin class manages addon modules
*/
class lC_Zone_groups_Admin {
 /*
  * Returns the zone groups datatable data for listings
  *
  * @access public
  * @return array
  */
  public static function getAll() {
    global $lC_Database, $lC_Language;

    $media = $_GET['media'];
    
    $Qgroups = $lC_Database->query('select SQL_CALC_FOUND_ROWS * from :table_geo_zones order by geo_zone_name');
    $Qgroups->bindTable(':table_geo_zones', TABLE_GEO_ZONES);
    $Qgroups->execute();

    $result = array('aaData' => array());
    while ( $Qgroups->next() ) {
      $check = '<td><input class="batch" type="checkbox" name="batch[]" value="' . $Qgroups->valueInt('geo_zone_id') . '" id="' . $Qgroups->valueInt('geo_zone_id') . '"></td>';
      $group = '<td><a href="' . lc_href_link_admin(FILENAME_DEFAULT, 'zone_groups=' . $Qgroups->valueInt('geo_zone_id')) . '"><span class="icon-folder icon-orange"></span>&nbsp;' . $Qgroups->valueProtected('geo_zone_name') . '</a></td>';
      $entries = '<td class="hide-on-tablet">' . self::numberOfEntries($Qgroups->valueInt('geo_zone_id')) . '</td>';
      $action = '<td class="align-right vertical-center"><span class="button-group compact">
                   <a href="' . ((int)($_SESSION['admin']['access']['zone_groups'] < 3) ? '#' : 'javascript://" onclick="editGroup(\'' . $Qgroups->valueInt('geo_zone_id') . '\')') . '" class="button icon-pencil' . ((int)($_SESSION['admin']['access']['zone_groups'] < 3) ? ' disabled' : NULL) . '">' . (($media === 'mobile-portrait' || $media === 'mobile-landscape') ? NULL : $lC_Language->get('icon_edit')) . '</a>
                   <a href="' . ((int)($_SESSION['admin']['access']['zone_groups'] < 4) ? '#' : 'javascript://" onclick="deleteGroup(\'' . $Qgroups->valueInt('geo_zone_id') . '\', \'' . urlencode($Qgroups->valueProtected('geo_zone_name')) . '\')') . '" class="button icon-trash with-tooltip' . ((int)($_SESSION['admin']['access']['zone_groups'] < 4) ? ' disabled' : NULL) . '" title="' . $lC_Language->get('icon_delete') . '"></a>
                 </span></td>';

      $result['aaData'][] = array("$check", "$group", "$entries", "$action");
      $result['entries'][] = array_merge($Qgroups->toArray(), array('total_entries' => self::numberOfEntries($Qgroups->valueInt('geo_zone_id'))));
    }

    $Qgroups->freeResult();

    return $result;
  }
 /*
  * Return all geo zones
  *
  * @access public
  * @return array
  */
  public static function getAllZones() {
    global $lC_Database;

    $Qgroups = $lC_Database->query('select SQL_CALC_FOUND_ROWS * from :table_geo_zones order by geo_zone_name');
    $Qgroups->bindTable(':table_geo_zones', TABLE_GEO_ZONES);
    $Qgroups->execute();

    $result['zonesArray'] = $Qgroups->toArray();

    $Qgroups->freeResult();

    return $result;
  }
 /*
  * Return the zone group information
  *
  * @param string $id The geo zone id
  * @param string $key The geo zone key
  * @access public
  * @return array
  */
  public static function get($id, $key = null) {
    global $lC_Database;

    $Qzones = $lC_Database->query('select * from :table_geo_zones where geo_zone_id = :geo_zone_id');
    $Qzones->bindTable(':table_geo_zones', TABLE_GEO_ZONES);
    $Qzones->bindInt(':geo_zone_id', $id);
    $Qzones->execute();

    $data = array_merge($Qzones->toArray(), array('total_entries' => self::numberOfEntries($id)));

    $data['hasTaxRate'] = lC_Zone_groups_Admin::hasTaxRate($id);
    $data['numberOfTaxRates'] = lC_Zone_groups_Admin::numberOfTaxRates($id);

    $Qzones->freeResult();

    if ( empty($key) ) {
      return $data;
    } else {
      return $data[$key];
    }
  }
 /*
  * Save the geo zone group
  *
  * @param integer $id The geo zone id
  * @param array $data An array containing the geo zone group information
  * @access public
  * @return boolean
  */
  public static function save($id = null, $data) {
    global $lC_Database;

    if ( is_numeric($id) ) {
      $Qzone = $lC_Database->query('update :table_geo_zones set geo_zone_name = :geo_zone_name, geo_zone_description = :geo_zone_description, last_modified = now() where geo_zone_id = :geo_zone_id');
      $Qzone->bindInt(':geo_zone_id', $id);
    } else {
      $Qzone = $lC_Database->query('insert into :table_geo_zones (geo_zone_name, geo_zone_description, date_added) values (:geo_zone_name, :geo_zone_description, now())');
    }

    $Qzone->bindTable(':table_geo_zones', TABLE_GEO_ZONES);
    $Qzone->bindValue(':geo_zone_name', $data['zone_name']);
    $Qzone->bindValue(':geo_zone_description', $data['zone_description']);
    $Qzone->setLogging($_SESSION['module'], $id);
    $Qzone->execute();

    if ( !$lC_Database->isError() ) {
      return true;
    }

    return false;
  }
 /*
  * Delete the geo zone group
  *
  * @param integer $id The geo zone id
  * @access public
  * @return boolean
  */
  public static function delete($id) {
    global $lC_Database;

    $error = false;

    $lC_Database->startTransaction();

    $Qentry = $lC_Database->query('delete from :table_zones_to_geo_zones where geo_zone_id = :geo_zone_id');
    $Qentry->bindTable(':table_zones_to_geo_zones', TABLE_ZONES_TO_GEO_ZONES);
    $Qentry->bindInt(':geo_zone_id', $id);
    $Qentry->setLogging($_SESSION['module'], $id);
    $Qentry->execute();

    if ( !$lC_Database->isError() ) {
      $Qzone = $lC_Database->query('delete from :table_geo_zones where geo_zone_id = :geo_zone_id');
      $Qzone->bindTable(':table_geo_zones', TABLE_GEO_ZONES);
      $Qzone->bindInt(':geo_zone_id', $id);
      $Qzone->setLogging($_SESSION['module'], $id);
      $Qzone->execute();

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
  * Batch delete zone groups
  *
  * @param array $batch The geo zone id's to delete
  * @access public
  * @return boolean
  */
  public static function batchDeleteGroups($batch) {
    global $lC_Language;

    $lC_Language->loadIniFile('zone_groups.php');

    $result = array();
    $result['namesString'] = '';
    foreach ( $batch as $id ) {
      if ( lC_Zone_groups_Admin::hasTaxRate($id) ) {
        $zData = lC_Zone_groups_Admin::get($id);
        $result['namesString'] .= $zData['geo_zone_name'] . ' (' . sprintf($lC_Language->get('total_entries'), $zData['total_entries']) . '), ';
      } else {
        lC_Zone_groups_Admin::delete($id);
      }
    }
    if ( !empty($result['namesString']) ) {
      $result['namesString'] = substr($result['namesString'], 0, -2);
    }

    return $result;
  }
 /*
  * Return the number of entries in a zone group
  *
  * @param integer $id The geo zone id
  * @access public
  * @return integer
  */
  public static function numberOfEntries($id) {
    global $lC_Database;

    $Qentries = $lC_Database->query('select count(*) as total from :table_zones_to_geo_zones where geo_zone_id = :geo_zone_id');
    $Qentries->bindTable(':table_zones_to_geo_zones', TABLE_ZONES_TO_GEO_ZONES);
    $Qentries->bindInt(':geo_zone_id', $id);
    $Qentries->execute();

    return $Qentries->valueInt('total');
  }
 /*
  * Check to see if a tax rate has been assigned to the zone
  *
  * @param integer $id The tax zone id
  * @access public
  * @return boolean
  */
  public static function hasTaxRate($id) {
    global $lC_Database;

    $Qcheck = $lC_Database->query('select tax_zone_id from :table_tax_rates where tax_zone_id = :tax_zone_id limit 1');
    $Qcheck->bindTable(':table_tax_rates', TABLE_TAX_RATES);
    $Qcheck->bindInt(':tax_zone_id', $id);
    $Qcheck->execute();

    return ( $Qcheck->numberOfRows() === 1 );
  }
 /*
  * Return the number of tax rates in a zone group
  *
  * @param integer $id The tax zone id
  * @access public
  * @return integer
  */
  public static function numberOfTaxRates($id) {
    global $lC_Database;

    $Qtotal = $lC_Database->query('select count(*) as total from :table_tax_rates where tax_zone_id = :tax_zone_id');
    $Qtotal->bindTable(':table_tax_rates', TABLE_TAX_RATES);
    $Qtotal->bindInt(':tax_zone_id', $id);
    $Qtotal->execute();

    return $Qtotal->valueInt('total');
  }
 /*
  * Returns the zone group data
  *
  * @param integer $id The association id
  * @access public
  * @return array
  */
  public static function getEntry($id) {
    global $lC_Database, $lC_Language;

    $Qentries = $lC_Database->query('select z2gz.*, c.countries_name, z.zone_name from :table_zones_to_geo_zones z2gz left join :table_countries c on (z2gz.zone_country_id = c.countries_id) left join :table_zones z on (z2gz.zone_id = z.zone_id) where z2gz.association_id = :association_id');
    $Qentries->bindTable(':table_zones_to_geo_zones', TABLE_ZONES_TO_GEO_ZONES);
    $Qentries->bindTable(':table_zones', TABLE_ZONES);
    $Qentries->bindTable(':table_countries', TABLE_COUNTRIES);
    $Qentries->bindInt(':association_id', $id);
    $Qentries->execute();

    $data = $Qentries->toArray();

    if ( empty($data['countries_name']) ) {
      $data['countries_name'] = $lC_Language->get('all_countries');
    }

    if ( empty($data['zone_name']) ) {
      $data['zone_name'] = $lC_Language->get('all_zones');
    }

    $Qentries->freeResult();

    return $data;
  }
 /*
  * Returns the zone group entries datatable data for listings
  *
  * @param integer $group_id The geo zone id
  * @access public
  * @return array
  */
  public static function getAllEntries($group_id) {
    global $lC_Database, $lC_Language;

    $lC_Language->loadIniFile('zone_groups.php');

    $media = $_GET['media'];
    
    $result = array('entries' => array());

    $Qentries = $lC_Database->query('select z2gz.*, c.countries_name, z.zone_name from :table_zones_to_geo_zones z2gz left join :table_countries c on (z2gz.zone_country_id = c.countries_id) left join :table_zones z on (z2gz.zone_id = z.zone_id) where z2gz.geo_zone_id = :geo_zone_id order by c.countries_name, z.zone_name');
    $Qentries->bindTable(':table_zones_to_geo_zones', TABLE_ZONES_TO_GEO_ZONES);
    $Qentries->bindTable(':table_zones', TABLE_ZONES);
    $Qentries->bindTable(':table_countries', TABLE_COUNTRIES);
    $Qentries->bindInt(':geo_zone_id', $group_id);
    $Qentries->execute();

    $result = array('aaData' => array());
    while ( $Qentries->next() ) {
      $check = '<td><input class="batch" type="checkbox" name="batch[]" value="' . $Qentries->valueInt('association_id') . '" id="' . $Qentries->valueInt('association_id') . '"></td>';
      $country = '<td>' . ( ($Qentries->value('countries_name') != null) ? $Qentries->value('countries_name') : $lC_Language->get('all_countries') ) . '</td>';
      $zone = '<td class="hide-on-tablet">' . ( ($Qentries->value('zone_name') != null) ? $Qentries->value('zone_name') : $lC_Language->get('all_zones') ) . '</td>';
      $action = '<td class="align-right vertical-center"><span class="button-group compact" style="white-space:nowrap;">
                   <a href="' . ((int)($_SESSION['admin']['access']['zone_groups'] < 3) ? '#' : 'javascript://" onclick="editEntry(\'' . $Qentries->valueInt('association_id') . '\')') . '" class="button icon-pencil' . ((int)($_SESSION['admin']['access']['zone_groups'] < 3) ? ' disabled' : NULL) . '">' .  (($media === 'mobile-portrait' || $media === 'mobile-landscape') ? NULL : $lC_Language->get('icon_edit')) . '</a>
                   <a href="' . ((int)($_SESSION['admin']['access']['zone_groups'] < 4) ? '#' : 'javascript://" onclick="deleteEntry(\'' . $Qentries->valueInt('association_id') . '\', \'' . urlencode($Qentries->valueProtected('zone_name')) . '\')') . '" class="button icon-trash with-tooltip' . ((int)($_SESSION['admin']['access']['zone_groups'] < 4) ? ' disabled' : NULL) . '" title="' . $lC_Language->get('icon_delete') . '"></a>
                 </span></td>';

      $result['aaData'][] = array("$check", "$country", "$zone", "$action");
      $result['entries'][] = $Qentries->toArray();
    }

    $Qentries->freeResult();

    return $result;
  }
 /*
  * Return the zone group entry information used on dialog forms
  *
  * @param integer $zaid The geo zone id
  * @access public
  * @return array
  */
  public static function getEntryFormData($zaid) {
    global $lC_Language;

    $lC_Language->loadIniFile('zone_groups.php');

    $result = array();
    $countries_array = array('' => $lC_Language->get('all_countries'));
    foreach ( lC_Address::getCountries() as $country ) {
      $countries_array[$country['id']] = $country['name'];
    }
    $result['countriesArray'] = $countries_array;
    $result['zonesArray'] = array('' => $lC_Language->get('all_zones'));

    if ( isset($zaid) && $zaid != null ) {
      $result['zoneData'] = lC_Zone_groups_Admin::getEntry($zaid);
    }

    return $result;
  }
 /*
  * Return all zones
  *
  * @param integer $id The zone country id
  * @access public
  * @return array
  */
  public static function getZones($id) {
    global $lC_Database, $lC_Language;

    $lC_Language->loadIniFile('zone_groups.php');

    $result = array();
    $Qzones = $lC_Database->query('select zone_id, zone_name from :table_zones where zone_country_id = :zone_country_id order by zone_name');
    $Qzones->bindTable(':table_zones', TABLE_ZONES);
    $Qzones->bindInt(':zone_country_id', $id);
    $Qzones->execute();

    $zones_array = array('' => $lC_Language->get('all_zones'));
    while ( $Qzones->next() ) {
      $zones_array[$Qzones->value('zone_id')] = $Qzones->value('zone_name');
    }
    $result['zonesArray'] = $zones_array;

    return $result;
  }
 /*
  * Save the zone group entry
  *
  * @param integer $id The association id
  * @param array $data An array containing the group entry information
  * @access public
  * @return boolean
  */
  public static function saveEntry($id = null, $data) {
    global $lC_Database;

    if ( is_numeric($id) ) {
      $Qentry = $lC_Database->query('update :table_zones_to_geo_zones set zone_country_id = :zone_country_id, zone_id = :zone_id, last_modified = now() where association_id = :association_id');
      $Qentry->bindInt(':association_id', $id);
    } else {
      $Qentry = $lC_Database->query('insert into :table_zones_to_geo_zones (zone_country_id, zone_id, geo_zone_id, date_added) values (:zone_country_id, :zone_id, :geo_zone_id, now())');
      $Qentry->bindInt(':geo_zone_id', $data['group_id']);
    }
    $Qentry->bindTable(':table_zones_to_geo_zones', TABLE_ZONES_TO_GEO_ZONES);
    $Qentry->bindInt(':zone_country_id', $data['zone_country_id']);
    $Qentry->bindInt(':zone_id', $data['zone_id']);
    $Qentry->setLogging($_SESSION['module'], $id);
    $Qentry->execute();

    if ( !$lC_Database->isError() ) {
      return true;
    }

    return false;
  }
 /*
  * Delete the zone group entry
  *
  * @param integer $id The association id
  * @access public
  * @return boolean
  */
  public static function deleteEntry($id) {
    global $lC_Database;

    $Qentry = $lC_Database->query('delete from :table_zones_to_geo_zones where association_id = :association_id');
    $Qentry->bindTable(':table_zones_to_geo_zones', TABLE_ZONES_TO_GEO_ZONES);
    $Qentry->bindInt(':association_id', $id);
    $Qentry->setLogging($_SESSION['module'], $id);
    $Qentry->execute();

    if ( !$lC_Database->isError() ) {
      return true;
    }

    return false;
  }
 /*
  * Batch delete zone group entries
  *
  * @param array $batch The association id's to delete
  * @access public
  * @return boolean
  */
  public static function batchDeleteEntries($batch) {
    foreach ( $batch as $id ) {
      lC_Zone_groups_Admin::deleteEntry($id);
    }
    return true;
  }
}
?>