<?php
/*
  $Id: tax_classes.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html

  @function The lC_Tax_classes_Admin class manages tax classes
*/
class lC_Tax_classes_Admin {
 /*
  * Returns the tax classes datatable data for listings
  *
  * @access public
  * @return array
  */
  public static function getAll() {
    global $lC_Database, $lC_Language;

    $media = $_GET['media'];
    
    $Qclasses = $lC_Database->query('select SQL_CALC_FOUND_ROWS * from :table_tax_class order by tax_class_title');
    $Qclasses->bindTable(':table_tax_class', TABLE_TAX_CLASS);
    $Qclasses->execute();

    $result = array('aaData' => array());
    while ( $Qclasses->next() ) {
      $check = '<td><input class="batch" type="checkbox" name="batch[]" value="' . $Qclasses->valueInt('tax_class_id') . '" id="' . $Qclasses->valueInt('tax_class_id') . '"></td>';
      $class = '<td><a href="' . lc_href_link_admin(FILENAME_DEFAULT, 'tax_classes=' . $Qclasses->valueInt('tax_class_id')) . '"><span class="icon-folder icon-orange"></span>&nbsp;' . $Qclasses->value('tax_class_title') . '</a></td>';
      $rate = '<td class="hide-on-tablet">' . self::getNumberOfTaxRates($Qclasses->valueInt('tax_class_id')) . '</td>';
      $action = '<td class="align-right vertical-center"><span class="button-group compact">
                   <a href="' . ((int)($_SESSION['admin']['access']['tax_classes'] < 3) ? '#' : 'javascript://" onclick="editClass(\'' . $Qclasses->valueInt('tax_class_id') . '\')') . '" class="button icon-pencil' . ((int)($_SESSION['admin']['access']['tax_classes'] < 3) ? ' disabled' : NULL) . '">' . (($media === 'mobile-portrait' || $media === 'mobile-landscape') ? NULL : $lC_Language->get('icon_edit')) . '</a>
                   <a href="' . ((int)($_SESSION['admin']['access']['tax_classes'] < 4) ? '#' : 'javascript://" onclick="deleteClass(\'' . $Qclasses->valueInt('tax_class_id') . '\', \'' . urlencode($Qclasses->value('tax_class_title')) . '\')') . '" class="button icon-trash with-tooltip' . ((int)($_SESSION['admin']['access']['tax_classes'] < 4) ? ' disabled' : NULL) . '" title="' . $lC_Language->get('icon_delete') . '"></a>
                 </span></td>';

      $result['aaData'][] = array("$check", "$class", "$rate", "$action");
      $result['entries'][] = array_merge($Qclasses->toArray(), array('total_entries' => self::getNumberOfTaxRates($Qclasses->valueInt('tax_class_id'))));
    }

    $Qclasses->freeResult();

    return $result;
  }
 /*
  * Return the tax class information
  *
  * @param integer $id The tax class id
  * @access public
  * @return array
  */
  public static function getData($id) {
    $result = array();
    $result['tcData'] = lC_Tax_classes_Admin::get($id);

    return $result;
  }
 /*
  * Return the tax class information
  *
  * @param integer $id The tax class id
  * @param string $key The tax class field
  * @access public
  * @return array
  */
  public static function get($id, $key = null) {
    global $lC_Database;

    $Qclasses = $lC_Database->query('select * from :table_tax_class where tax_class_id = :tax_class_id');
    $Qclasses->bindTable(':table_tax_class', TABLE_TAX_CLASS);
    $Qclasses->bindInt(':tax_class_id', $id);
    $Qclasses->execute();

    if(is_array($Qclasses->toArray())) {
      $data = array_merge($Qclasses->toArray(), array('total_tax_rates' => self::getNumberOfTaxRates($id)));
    } else {
      $data = array('total_tax_rates' => self::getNumberOfTaxRates($id));
    }

    $Qclasses->freeResult();

    if ( empty($key) ) {
      return $data;
    } else {
      return $data[$key];
    }
  }
 /*
  * Save the tax class information
  *
  * @param integer $id The tax class id
  * @param array $data An array containing the tax class information
  * @access public
  * @return boolean
  */
  public static function save($id = null, $data) {
    global $lC_Database;

    if ( isset($id) && $id != null ) {
      $Qclass = $lC_Database->query('update :table_tax_class set tax_class_title = :tax_class_title, tax_class_description = :tax_class_description, last_modified = now() where tax_class_id = :tax_class_id');
      $Qclass->bindInt(':tax_class_id', $id);
    } else {
      $Qclass = $lC_Database->query('insert into :table_tax_class (tax_class_title, tax_class_description, date_added) values (:tax_class_title, :tax_class_description, now())');
    }

    $Qclass->bindTable(':table_tax_class', TABLE_TAX_CLASS);
    $Qclass->bindValue(':tax_class_title', $data['tax_class_title']);
    $Qclass->bindValue(':tax_class_description', $data['tax_class_description']);
    $Qclass->setLogging($_SESSION['module'], $id);
    $Qclass->execute();

    if ( !$lC_Database->isError() ) {
      return true;
    }

    return false;
  }
 /*
  * Delete the tax class
  *
  * @param integer $id The tax class id
  * @access public
  * @return boolean
  */
  public static function delete($id) {
    global $lC_Database;

    $error = false;

    $lC_Database->startTransaction();

    $Qrates = $lC_Database->query('delete from :table_tax_rates where tax_class_id = :tax_class_id');
    $Qrates->bindTable(':table_tax_rates', TABLE_TAX_RATES);
    $Qrates->bindInt(':tax_class_id', $id);
    $Qrates->setLogging($_SESSION['module'], $id);
    $Qrates->execute();

    if ( !$lC_Database->isError() ) {
      $Qclass = $lC_Database->query('delete from :table_tax_class where tax_class_id = :tax_class_id');
      $Qclass->bindTable(':table_tax_class', TABLE_TAX_CLASS);
      $Qclass->bindInt(':tax_class_id', $id);
      $Qclass->setLogging($_SESSION['module'], $id);
      $Qclass->execute();

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
  * Batch delete tax classes
  *
  * @param array $batch An array containing the tax class id's to delete
  * @access public
  * @return array
  */
  public static function batchDelete($batch) {
    global $lC_Language;

    $lC_Language->loadIniFile('tax_classes.php');

    $result = array();
    $result['namesString'] = '';
    foreach ( $batch as $id ) {
      if ( lC_Tax_classes_Admin::hasProducts($id) ) {
        $zData = lC_Tax_classes_Admin::get($id);
        $result['namesString'] .= $zData['tax_class_title'] . ' (' . sprintf($lC_Language->get('total_products'), self::getNumberOfProducts($id)) . '), ';
      } else {
        lC_Tax_classes_Admin::delete($id);
      }
    }
    if ( !empty($result['namesString']) ) {
      $result['namesString'] = substr($result['namesString'], 0, -2);
    }

    return $result;
  }
 /*
  * Returns the tax rates datatable data for listings
  *
  * @access public
  * @return array
  */
  public static function getAllEntries($tax_class_id) {
    global $lC_Database, $lC_Language;

    $media = $_GET['media'];

    $result = array('entries' => array());

    $Qrates = $lC_Database->query('select tr.*, z.geo_zone_id, z.geo_zone_name from :table_tax_rates tr, :table_geo_zones z where tr.tax_class_id = :tax_class_id and tr.tax_zone_id = z.geo_zone_id order by tr.tax_priority, z.geo_zone_name');
    $Qrates->bindTable(':table_tax_rates', TABLE_TAX_RATES);
    $Qrates->bindTable(':table_geo_zones', TABLE_GEO_ZONES);
    $Qrates->bindInt(':tax_class_id', $tax_class_id);
    $Qrates->execute();

    $result = array('aaData' => array());
    while ( $Qrates->next() ) {
      $check = '<td><input class="batch" type="checkbox" name="batch[]" value="' . $Qrates->valueInt('tax_rates_id') . '" id="' . $Qrates->valueInt('tax_rates_id') . '"></td>';
      $priority = '<td>' . $Qrates->valueInt('tax_priority') . '</td>';
      $rate = '<td>' . number_format($Qrates->valueDecimal('tax_rate'), DECIMAL_PLACES) . '</td>';
      $zone = '<td>' . $Qrates->value('geo_zone_name') . '</td>';
      $action = '<td class="align-right vertical-center"><span class="button-group compact">
                   <a href="' . ((int)($_SESSION['admin']['access']['tax_classes'] < 3) ? '#' : 'javascript://" onclick="editEntry(\'' . $Qrates->valueInt('tax_rates_id') . '\')') . '" class="button icon-pencil' . ((int)($_SESSION['admin']['access']['tax_classes'] < 3) ? ' disabled' : NULL) . '">' .  (($media === 'mobile-portrait' || $media === 'mobile-landscape') ? NULL : $lC_Language->get('icon_edit')) . '</a>
                   <a href="' . ((int)($_SESSION['admin']['access']['tax_classes'] < 4) ? '#' : 'javascript://" onclick="deleteEntry(\'' . $Qrates->valueInt('tax_rates_id') . '\', \'' . urlencode($Qrates->value('geo_zone_name')) . '\')') . '" class="button icon-trash with-tooltip' . ((int)($_SESSION['admin']['access']['tax_classes'] < 4) ? ' disabled' : NULL) . '" title="' . $lC_Language->get('icon_delete') . '"></a>
                 </span></td>';

      $result['aaData'][] = array("$check", "$priority", "$zone", "$rate", "$action");
      $result['entries'][] = $Qrates->toArray();
    }

    $Qrates->freeResult();

    return $result;
  }
 /*
  * Return the tax rate information
  *
  * @param integer $id The tax rates id
  * @access public
  * @return array
  */
  public static function getEntryFormData($id) {
   global $lC_Language;

    $lC_Language->loadIniFile('tax_classes.php');
    $lC_Language->loadIniFile('zone_groups.php');

    $result = array();
    $zones_array = array();
    foreach ( lC_Zone_groups_Admin::getAllZones() as $group ) {
      for ($i = 0; $i < count($group); $i++) {
        $zones_array[$group[$i]['geo_zone_id']] = $group[$i]['geo_zone_name'];
      }
    }
    $result['zonesArray'] = $zones_array;
    
    if ( isset($id) && $id != null ) {
      $result['editFormData'] = lC_Tax_classes_Admin::getEntry($id);
    }

    $Qgroups->freeResult;

    return $result;
  }
 /*
  * Return the tax rate information
  *
  * @param integer $id The tax rates id
  * @param string $key The tax rates field
  * @access public
  * @return array
  */
  public static function getEntry($id, $key = null) {
    global $lC_Database;

    $Qrates = $lC_Database->query('select tr.*, tc.tax_class_title, z.geo_zone_id, z.geo_zone_name from :table_tax_rates tr, :table_tax_class tc, :table_geo_zones z where tr.tax_rates_id = :tax_rates_id and tr.tax_class_id = tc.tax_class_id and tr.tax_zone_id = z.geo_zone_id');
    $Qrates->bindTable(':table_tax_rates', TABLE_TAX_RATES);
    $Qrates->bindTable(':table_tax_class', TABLE_TAX_CLASS);
    $Qrates->bindTable(':table_geo_zones', TABLE_GEO_ZONES);
    $Qrates->bindInt(':tax_rates_id', $id);
    $Qrates->execute();

    $data = $Qrates->toArray();

    $Qrates->freeResult();

    if ( empty($key) ) {
      return $data;
    } else {
      return $data[$key];
    }
  }
 /*
  * Save the tax rate information
  *
  * @param integer $id The tax rates id
  * @param array $data An array containing the tax rates information
  * @access public
  * @return boolean
  */
  public static function saveEntry($id = null, $data) {
    global $lC_Database;

    if ( isset($id) && $id != null ) {
      $Qrate = $lC_Database->query('update :table_tax_rates set tax_zone_id = :tax_zone_id, tax_priority = :tax_priority, tax_rate = :tax_rate, tax_description = :tax_description, last_modified = now() where tax_rates_id = :tax_rates_id');
      $Qrate->bindInt(':tax_rates_id', $id);
    } else {
      $Qrate = $lC_Database->query('insert into :table_tax_rates (tax_zone_id, tax_class_id, tax_priority, tax_rate, tax_description, date_added) values (:tax_zone_id, :tax_class_id, :tax_priority, :tax_rate, :tax_description, now())');
      $Qrate->bindInt(':tax_class_id', $data['tax_class_id']);
    }

    $Qrate->bindTable(':table_tax_rates', TABLE_TAX_RATES);
    $Qrate->bindInt(':tax_zone_id', $data['tax_zone_id']);
    $Qrate->bindInt(':tax_priority', $data['tax_priority']);
    $Qrate->bindValue(':tax_rate', $data['tax_rate']);
    $Qrate->bindValue(':tax_description', $data['tax_description']);
    $Qrate->setLogging($_SESSION['module'], $id);
    $Qrate->execute();

    if ( !$lC_Database->isError() ) {
      return true;
    }

    return false;
  }
 /*
  * Delete the tax rate
  *
  * @param integer $id The tax rates id
  * @access public
  * @return boolean
  */
  public static function deleteEntry($id) {
    global $lC_Database;

    $Qrate = $lC_Database->query('delete from :table_tax_rates where tax_rates_id = :tax_rates_id');
    $Qrate->bindTable(':table_tax_rates', TABLE_TAX_RATES);
    $Qrate->bindInt(':tax_rates_id', $id);
    $Qrate->setLogging($_SESSION['module'], $id);
    $Qrate->execute();

    if ( !$lC_Database->isError() ) {
      return true;
    }

    return false;
  }
 /*
  * Batch delete tax rate entries
  *
  * @param array $batch The tax rate id's to delete
  * @access public
  * @return boolean
  */
  public static function batchDeleteEntries($batch) {
    foreach ( $batch as $id ) {
      lC_Tax_classes_Admin::deleteEntry($id);
    }
    return true;
  }
 /*
  * Return the number of tax rates for a given class
  *
  * @param integer $id The tax class id
  * @access public
  * @return integer
  */
  public static function getNumberOfTaxRates($id) {
    global $lC_Database;

    $Qrates = $lC_Database->query('select count(*) as total_tax_rates from :table_tax_rates where tax_class_id = :tax_class_id');
    $Qrates->bindTable(':table_tax_rates', TABLE_TAX_RATES);
    $Qrates->bindInt(':tax_class_id', $id);
    $Qrates->execute();

    return $Qrates->valueInt('total_tax_rates');
  }
 /*
  * Check if products are attached to the tax class
  *
  * @param integer $id The products tax class id
  * @access public
  * @return boolean
  */
  public static function hasProducts($id) {
    global $lC_Database;

    $Qcheck = $lC_Database->query('select products_id from :table_products where products_tax_class_id = :products_tax_class_id limit 1');
    $Qcheck->bindTable(':table_products', TABLE_PRODUCTS);
    $Qcheck->bindInt(':products_tax_class_id', $id);
    $Qcheck->execute();

    return ( $Qcheck->numberOfRows() === 1 );
  }
 /*
  * Return the number of products attached to the tax class
  *
  * @param integer $id The products tax class id
  * @access public
  * @return integer
  */
  public static function getNumberOfProducts($id) {
    global $lC_Database;

    $Qtotal = $lC_Database->query('select count(*) as total from :table_products where products_tax_class_id = :products_tax_class_id');
    $Qtotal->bindTable(':table_products', TABLE_PRODUCTS);
    $Qtotal->bindInt(':products_tax_class_id', $id);
    $Qtotal->execute();

    return $Qtotal->valueInt('total');
  }
}
?>