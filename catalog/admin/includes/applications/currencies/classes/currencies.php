<?php
/*
  $Id: currencies.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html

  @function The lC_Currencies_Admin class manages currencies definitions
*/
class lC_Currencies_Admin {
 /*
  * Returns the currencies datatable data for listings
  *
  * @access public
  * @return array
  */
  public static function getAll() {
    global $lC_Database, $lC_Language, $_module;

    $media = $_GET['media'];
    
    $Qcurrencies = $lC_Database->query('select SQL_CALC_FOUND_ROWS * from :table_currencies order by title');
    $Qcurrencies->bindTable(':table_currencies', TABLE_CURRENCIES);
    $Qcurrencies->execute();

    $result = array('entries' => array());
    $result = array('aaData' => array());
    while ( $Qcurrencies->next() ) {
      $name = '<td>' . $Qcurrencies->value('title') . '</a></td>';
      if ( $Qcurrencies->value('code') == DEFAULT_CURRENCY ) {
        $name .= '<small class="tag purple-gradient glossy margin-left">' . $lC_Language->get('default_entry') . '</small>';
      }
      $code = '<td>' . $Qcurrencies->value('code') . '</td>';
      $value = '<td>' . number_format($Qcurrencies->valueDecimal('value'), 8) . '</td>';
      $action = '<td class="align-right vertical-center"><span class="button-group compact">
                   <a href="' . ((int)($_SESSION['admin']['access'][$_module] < 3) ? '#' : 'javascript://" onclick="editCurrency(\'' . $Qcurrencies->valueInt('currencies_id') . '\')') . '" class="button icon-pencil' . ((int)($_SESSION['admin']['access'][$_module] < 3) ? ' disabled' : NULL) . '">' .  (($media === 'mobile-portrait' || $media === 'mobile-landscape') ? NULL : $lC_Language->get('icon_edit')) . '</a>
                   <a href="' . ((int)($_SESSION['admin']['access'][$_module] < 4) ? '#' : 'javascript://" onclick="deleteCurrency(\'' . $Qcurrencies->valueInt('currencies_id') . '\', \'' . urlencode($Qcurrencies->valueProtected('title')) . '\')') . '" class="button icon-trash with-tooltip' . ((int)($_SESSION['admin']['access'][$_module] < 4) ? ' disabled' : NULL) . '" title="' . $lC_Language->get('icon_delete') . '"></a>
                 </span></td>';

      $result['aaData'][] = array("$name", "$code", "$value", "$action");
      $result['entries'][] = $Qcurrencies->toArray();
    }

    $Qcurrencies->freeResult();

    return $result;
  }
 /*
  * Returns the data used on the dialog forms
  *
  * @param integer $id The currencies id
  * @access public
  * @return array
  */
  public static function getFormData($id = null) {
    $result = array();

    if (isset($id) && $id != null) {
      $result['currencyData'] = lC_Currencies_Admin::getData($id);
    }

    return $result;
  }
 /*
  * Return the currencies information
  *
  * @param integer $id The currencies id
  * @access public
  * @return array
  */
  public static function getData($id, $key = null) {
     global $lC_Database;

    $result = false;

    $Qcurrency = $lC_Database->query('select * from :table_currencies where');

    if ( is_numeric($id) ) {
      $Qcurrency->appendQuery('currencies_id = :currencies_id');
      $Qcurrency->bindInt(':currencies_id', $id);
    } else {
      $Qcurrency->appendQuery('code = :code');
      $Qcurrency->bindValue(':code', $id);
    }

    $Qcurrency->bindTable(':table_currencies', TABLE_CURRENCIES);
    $Qcurrency->execute();

    if ( $Qcurrency->numberOfRows() === 1 ) {
      $result = $Qcurrency->toArray();

      if ( !empty($key) && isset($result[$key]) ) {
        $result = $result[$key];
      }
    }

    return $result;
  }
 /*
  * Checks if currency record exists
  *
  * @param integer $id The currencies id
  * @access public
  * @return boolean
  */
  public static function exists($id) {
    return (self::getData($id) !== false);
  }
 /*
  * Save the currencies information
  *
  * @param integer $id The currencies id used on update, null on insert
  * @param array $data An array containing the currencies information
  * @param boolean $default True = set the currency to be the default
  * @access public
  * @return boolean
  */
  public static function save($data, $default = false) {
    global $lC_Database;

    $id = (isset($data['cid']) && is_numeric($data['cid'])) ? (int)$data['cid'] : NULL;

    $lC_Database->startTransaction();

    if ( is_numeric($id) ) {
      $Qcurrency = $lC_Database->query('update :table_currencies set title = :title, code = :code, symbol_left = :symbol_left, symbol_right = :symbol_right, decimal_places = :decimal_places, value = :value where currencies_id = :currencies_id');
      $Qcurrency->bindInt(':currencies_id', $id);
    } else {
      $Qcurrency = $lC_Database->query('insert into :table_currencies (title, code, symbol_left, symbol_right, decimal_places, value) values (:title, :code, :symbol_left, :symbol_right, :decimal_places, :value)');
    }

    $Qcurrency->bindTable(':table_currencies', TABLE_CURRENCIES);
    $Qcurrency->bindValue(':title', $data['title']);
    $Qcurrency->bindValue(':code', $data['code']);
    $Qcurrency->bindValue(':symbol_left', $data['symbol_left']);
    $Qcurrency->bindValue(':symbol_right', $data['symbol_right']);
    $Qcurrency->bindInt(':decimal_places', $data['decimal_places']);
    $Qcurrency->bindValue(':value', $data['value']);
    $Qcurrency->setLogging($_SESSION['module'], $id);
    $Qcurrency->execute();

    if ( $lC_Database->isError() === false ) {
      if ( is_numeric($id) === false ) {
        $id = $lC_Database->nextID();
      }

      if ( $default === true ) {
        $Qupdate = $lC_Database->query('update :table_configuration set configuration_value = :configuration_value where configuration_key = :configuration_key');
        $Qupdate->bindTable(':table_configuration', TABLE_CONFIGURATION);
        $Qupdate->bindValue(':configuration_value', $data['code']);
        $Qupdate->bindValue(':configuration_key', 'DEFAULT_CURRENCY');
        $Qupdate->setLogging($_SESSION['module'], $id);
        $Qupdate->execute();
      }

      if ( $lC_Database->isError() === false ) {
        $lC_Database->commitTransaction();

        lC_Cache::clear('currencies');

        if ( ( $default === true ) && $Qupdate->affectedRows() ) {
          lC_Cache::clear('configuration');
        }

        return true;
      }
    }

    $lC_Database->rollbackTransaction();

    return false;
  }
 /*
  * Delete the currency record
  *
  * @param integer $id The currencies id to delete
  * @access public
  * @return boolean
  */
  public static function delete($id) {
    global $lC_Database;

    $Qcheck = $lC_Database->query('select code from :table_currencies where currencies_id = :currencies_id');
    $Qcheck->bindTable(':table_currencies', TABLE_CURRENCIES);
    $Qcheck->bindInt(':currencies_id', $id);
    $Qcheck->execute();

    if ( $Qcheck->value('code') != DEFAULT_CURRENCY ) {
      $Qdelete = $lC_Database->query('delete from :table_currencies where currencies_id = :currencies_id');
      $Qdelete->bindTable(':table_currencies', TABLE_CURRENCIES);
      $Qdelete->bindInt(':currencies_id', $id);
      $Qdelete->setLogging($_SESSION['module'], $id);
      $Qdelete->execute();

      if ( $lC_Database->isError() === false ) {
        lC_Cache::clear('currencies');

        return true;
      }
    }

    return false;
  }
 /*
  * Batch delete currency records
  *
  * @param array $batch An array of currency id's to delete
  * @access public
  * @return array
  */
  public static function batchDelete($batch) {
    global $lC_Language, $lC_Database;

    $lC_Language->loadIniFile('currencies.php');

    $check_default_flag = false;
    $Qcurrencies = $lC_Database->query('select currencies_id, title, code from :table_currencies where currencies_id in (":currencies_id") order by title');
    $Qcurrencies->bindTable(':table_currencies', TABLE_CURRENCIES);
    $Qcurrencies->bindRaw(':currencies_id', implode('", "', array_unique(array_filter(array_slice($batch, 0, MAX_DISPLAY_SEARCH_RESULTS), 'is_numeric'))));
    $Qcurrencies->execute();
    $names_string = '';
    while ( $Qcurrencies->next() ) {
      if ( $Qcurrencies->value('code') == DEFAULT_CURRENCY ) {
        $names_string .= $Qcurrencies->value('title') . ' (' . $Qcurrencies->value('code') . ')';
      } else {
        lC_Currencies_Admin::delete($Qcurrencies->value('currencies_id'));
      }
    }
    $result['namesString'] = $names_string;

    $Qcurrencies->freeResult();

    return $result;
  }
 /*
  * Get the update rates data for dialog form
  *
  * @access public
  * @return array
  */
  public static function getRatesData() {
    $result = array();
    $services = array(array('id' => 'oanda',
                            'text' => 'Oanda (http://www.oanda.com)'),
                      array('id' => 'xe',
                            'text' => 'XE (http://www.xe.com)'));

    $result['ratesSelection'] = lc_draw_radio_field('service', $services, null, 'class="input"', '<br />');

    return $result;
  }
 /*
  * Updates currency rates
  *
  * @param string $service The currency update service name
  * @access public
  * @return array
  */
  public static function updateRates($service) {
    global $lC_Database;

    $result = array();
    $updated_string = '';
    $not_updated_string = '';
    foreach ( lc_toObjectInfo(self::getAll(-1))->get('entries') as $currency ) {
      $rate = call_user_func('quote_' . $service . '_currency', $currency['code']);

      if ( !empty($rate) ) {
        $Qupdate = $lC_Database->query('update :table_currencies set value = :value, last_updated = now() where currencies_id = :currencies_id');
        $Qupdate->bindTable(':table_currencies', TABLE_CURRENCIES);
        $Qupdate->bindValue(':value', $rate);
        $Qupdate->bindInt(':currencies_id', $currency['currencies_id']);
        $Qupdate->setLogging($_SESSION['module'], $currency['currencies_id']);
        $Qupdate->execute();

        $updated_string .= $currency['title'] . ' (' . $currency['code'] . '), ';
      } else {
        $not_updated_string .= $currency['title'] . ' (' . $currency['code'] . '), ';
      }
    }
    if ( !empty($updated_string) ) {
      $updated_string = substr($updated_string, 0, -2);
    }
    if ( !empty($not_updated_string) ) {
      $not_updated_string = substr($not_updated_string, 0, -2);
    }

    $result['updatedString'] = $updated_string;
    $result['notUpdatedString'] = $not_updated_string;

    lC_Cache::clear('currencies');

    return $result;
  }
}
?>