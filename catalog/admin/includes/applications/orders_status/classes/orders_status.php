<?php
/*
  $Id: orders_status.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html

  @function The lC_Orders_status_Admin class manages order status definitions
*/
class lC_Orders_status_Admin {
 /*
  * Returns the orders status datatable data for listings
  *
  * @access public
  * @return array
  */
  public static function getAll() {
    global $lC_Database, $lC_Language;

    $media = $_GET['media'];
    
    $Qstatuses = $lC_Database->query('select orders_status_id, orders_status_name from :table_orders_status where language_id = :language_id order by orders_status_name');
    $Qstatuses->bindTable(':table_orders_status', TABLE_ORDERS_STATUS);
    $Qstatuses->bindInt(':language_id', $lC_Language->getID());
    $Qstatuses->setBatchLimit($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS);
    $Qstatuses->execute();

    $result = array('aaData' => array());
    while ( $Qstatuses->next() ) {
      $status_name = $Qstatuses->value('orders_status_name');
      if ( $Qstatuses->valueInt('orders_status_id') == DEFAULT_ORDERS_STATUS_ID ) {
        $status_name .= '<small class="tag purple-gradient glossy margin-left">' . $lC_Language->get('default_entry') . '</small>';
      }
      $name = '<td>' . $status_name . '</td>';
      $action = '<td class="align-right vertical-center"><span class="button-group compact">
                   <a href="' . ((int)($_SESSION['admin']['access']['orders'] < 3) ? '#' : 'javascript://" onclick="editStatus(\'' . $Qstatuses->valueInt('orders_status_id') . '\')') . '" class="button icon-pencil' . ((int)($_SESSION['admin']['access']['orders'] < 3) ? ' disabled' : NULL) . '">' . (($media === 'mobile-portrait' || $media === 'mobile-landscape') ? NULL : $lC_Language->get('icon_edit')) . '</a>
                   <a href="' . ((int)($_SESSION['admin']['access']['orders'] < 4 || $Qstatuses->valueInt('orders_status_id') == DEFAULT_ORDERS_STATUS_ID) ? '#' : 'javascript://" onclick="deleteStatus(\'' . $Qstatuses->valueInt('orders_status_id') . '\', \'' . urlencode($Qstatuses->valueProtected('title')) . '\')') . '" class="button icon-trash with-tooltip' . ((int)($_SESSION['admin']['access']['orders'] < 4 || $Qstatuses->valueInt('orders_status_id') == DEFAULT_ORDERS_STATUS_ID) ? ' disabled' : NULL) . '" title="' . $lC_Language->get('icon_delete') . '"></a>
                 </span></td>';
      $result['aaData'][] = array("$name", "$action");
    }

    $Qstatuses->freeResult();

    return $result;
  }
 /*
  * Return the data used on the dialog forms
  *
  * @param integer $id The orders status id
  * @param boolean $edit True = called from edit dialog else called from delete dialog
  * @access public
  * @return array
  */
  public static function getFormData($id = null, $edit = false) {
    global $lC_Database, $lC_Language;

    $result = array();

    $result['names'] = '';
    foreach ( $lC_Language->getAll() as $l ) {
      $result['names'] .= '<span class="input" style="width:88%"><label for="name[' . $l['id'] . ']" class="button silver-gradient glossy">' . $lC_Language->showImage($l['code']) . '</label>' . lc_draw_input_field('name[' . $l['id'] . ']', null, 'class="input-unstyled"') . '</span><br />';
    }

    if (isset($id) && $id != null) {
      if ($edit === true) {
        $Qsd = $lC_Database->query('select language_id, orders_status_name from :table_orders_status where orders_status_id = :orders_status_id');
        $Qsd->bindTable(':table_orders_status', TABLE_ORDERS_STATUS);
        $Qsd->bindInt(':orders_status_id', $id);
        $Qsd->execute();
        $status_name = array();
        while ( $Qsd->next() ) {
          $status_name[$Qsd->valueInt('language_id')] = $Qsd->value('orders_status_name');
        }
        $result['editNames'] = '';
        foreach ( $lC_Language->getAll() as $l ) {
          $result['editNames'] .= '<span class="input" style="width:88%"><label for="name[' . $l['id'] . ']" class="button silver-gradient glossy">' . $lC_Language->showImage($l['code']) . '</label>' . lc_draw_input_field('name[' . $l['id'] . ']', (isset($status_name[$l['id']]) ? $status_name[$l['id']] : null), 'class="input-unstyled"') . '</span><br />';
        }
      } else {
        $Qorders = $lC_Database->query('select count(*) as total from :table_orders where orders_status = :orders_status');
        $Qorders->bindTable(':table_orders', TABLE_ORDERS);
        $Qorders->bindInt(':orders_status', $id);
        $Qorders->execute();
        $Qhistory = $lC_Database->query('select count(*) as total from :table_orders_status_history where orders_status_id = :orders_status_id group by orders_id');
        $Qhistory->bindTable(':table_orders_status_history', TABLE_ORDERS_STATUS_HISTORY);
        $Qhistory->bindInt(':orders_status_id', $id);
        $Qhistory->execute();

        if ( ( $Qorders->valueInt('total') > 0)  || ( $Qhistory->valueInt('total') > 0 ) ) {
          if ( $Qorders->valueInt('total') > 0 ) {
            $result['rpcStatus'] = -2;
            $result['totalOrders'] = $Qorders->valueInt('total');
          }
          if ( $Qhistory->valueInt('total') > 0 ) {
            $result['rpcStatus'] = -3;
            $result['totalOrders'] = $Qhistory->valueInt('total');
          }
        }
      }
    }

    return $result;
  }
 /*
  * Get the orders status information
  *
  * @param integer $id The orders status id
  * @access public
  * @return array
  */
  public static function getData($id) {
    global $lC_Database, $lC_Language;

    $Qstatus = $lC_Database->query('select * from :table_orders_status where orders_status_id = :orders_status_id and language_id = :language_id');
    $Qstatus->bindTable(':table_orders_status', TABLE_ORDERS_STATUS);
    $Qstatus->bindInt(':orders_status_id', $id);
    $Qstatus->bindInt(':language_id', $lC_Language->getID());
    $Qstatus->execute();

    $data = $Qstatus->toArray();

    $Qstatus->freeResult();

    return $data;
  }
 /*
  * Save the orders status information
  *
  * @param integer $id The order status id used on update, null on insert
  * @param array $data An array containing the order status information
  * @param boolean $default True = set the order status to be the default
  * @access public
  * @return boolean
  */
  public static function save($id = null, $data, $default = false) {
    global $lC_Database, $lC_Language;

    $error = false;

    $lC_Database->startTransaction();

    if ( isset($id) && $id != null ) {
      $orders_status_id = $id;
      // ISSUE: if we add a new language, editing values does not save the new language.
      // To cure this, we delete the old records first, then re-insert instead of update.
      lC_Orders_status_Admin::delete($orders_status_id);
    } else {
      $Qstatus = $lC_Database->query('select max(orders_status_id) as orders_status_id from :table_orders_status');
      $Qstatus->bindTable(':table_orders_status', TABLE_ORDERS_STATUS);
      $Qstatus->execute();

      $orders_status_id = $Qstatus->valueInt('orders_status_id') + 1;
    }

    foreach ( $lC_Language->getAll() as $l ) {
      $Qstatus = $lC_Database->query('insert into :table_orders_status (orders_status_id, language_id, orders_status_name) values (:orders_status_id, :language_id, :orders_status_name)');
      $Qstatus->bindTable(':table_orders_status', TABLE_ORDERS_STATUS);
      $Qstatus->bindInt(':orders_status_id', $orders_status_id);
      $Qstatus->bindValue(':orders_status_name', $data['name'][$l['id']]);
      $Qstatus->bindInt(':language_id', $l['id']);
      $Qstatus->setLogging($_SESSION['module'], $orders_status_id);
      $Qstatus->execute();

      if ( $lC_Database->isError() ) {
        $error = true;
        break;
      }
    }

    if ( $error === false ) {
      if ( $default === true ) {
        $Qupdate = $lC_Database->query('update :table_configuration set configuration_value = :configuration_value where configuration_key = :configuration_key');
        $Qupdate->bindTable(':table_configuration', TABLE_CONFIGURATION);
        $Qupdate->bindInt(':configuration_value', $orders_status_id);
        $Qupdate->bindValue(':configuration_key', 'DEFAULT_ORDERS_STATUS_ID');
        $Qupdate->setLogging($_SESSION['module'], $orders_status_id);
        $Qupdate->execute();

        if ( $lC_Database->isError() ) {
          $error = true;
        }
      }
    }

    if ( $error === false ) {
      $lC_Database->commitTransaction();

      if ( $default === true ) {
        lC_Cache::clear('configuration');
      }

      return true;
    }

    $lC_Database->rollbackTransaction();

    return false;
  }
 /*
  * Delete the order status record
  *
  * @param integer $id The order status id to delete
  * @access public
  * @return boolean
  */
  public static function delete($id) {
    global $lC_Database;

    $Qstatus = $lC_Database->query('delete from :table_orders_status where orders_status_id = :orders_status_id');
    $Qstatus->bindTable(':table_orders_status', TABLE_ORDERS_STATUS);
    $Qstatus->bindInt(':orders_status_id', $id);
    $Qstatus->setLogging($_SESSION['module'], $id);
    $Qstatus->execute();

    if ( !$lC_Database->isError() ) {
      return true;
    }

    return false;
  }
 /*
  * Batch delete orders status records
  *
  * @param array $batch An array of order status id's to delete
  * @access public
  * @return array
  */
  public static function batchDelete($batch) {
    global $lC_Language, $lC_Database;

    $lC_Language->loadIniFile('orders_status.php');

    $result = array();
    $Qstatuses = $lC_Database->query('select orders_status_id, orders_status_name from :table_orders_status where orders_status_id in (":orders_status_id") and language_id = :language_id order by orders_status_name');
    $Qstatuses->bindTable(':table_orders_status', TABLE_ORDERS_STATUS);
    $Qstatuses->bindRaw(':orders_status_id', implode('", "', array_unique(array_filter(array_slice($batch, 0, MAX_DISPLAY_SEARCH_RESULTS), 'is_numeric'))));
    $Qstatuses->bindInt(':language_id', $lC_Language->getID());
    $Qstatuses->execute();
    $names_string = '';
    while ( $Qstatuses->next() ) {
      if ( $Qstatuses->value('orders_status_id') == DEFAULT_ORDERS_STATUS_ID ) {
        $names_string .= $Qstatuses->value('orders_status_name') . ' (' . $lC_Language->get('default') . ') ,';
      }
      $Qorders = $lC_Database->query('select count(*) as total from :table_orders where orders_status = :orders_status');
      $Qorders->bindTable(':table_orders', TABLE_ORDERS);
      $Qorders->bindInt(':orders_status', $Qstatuses->valueInt('orders_status_id'));
      $Qorders->execute();
      if ( $Qorders->valueInt('total') > 0 ) {
        $names_string .= $Qstatuses->value('orders_status_name') . ' (' . $Qorders->valueInt('total') . ' ' . $lC_Language->get('orders') . ') ,';
      }
      $Qorders->freeResult();
      $Qhistory = $lC_Database->query('select count(*) as total from :table_orders_status_history where orders_status_id = :orders_status_id group by orders_id');
      $Qhistory->bindTable(':table_orders_status_history', TABLE_ORDERS_STATUS_HISTORY);
      $Qhistory->bindInt(':orders_status_id', $Qstatuses->valueInt('orders_status_id'));
      $Qhistory->execute();
      if ( $Qhistory->valueInt('total') > 0 ) {
        $names_string .= $Qstatuses->value('orders_status_name') . ' (' . $Qhistory->valueInt('total') . ' ' . $lC_Language->get('orders_history') . ') ,';
      }
      $Qhistory->freeResult();
      if ( ($Qstatuses->value('orders_status_id') != DEFAULT_ORDERS_STATUS_ID) && ($Qorders->valueInt('total') == 0) && ($Qhistory->valueInt('total') == 0) ) {
        lC_Orders_status_Admin::delete($Qstatuses->value('orders_status_id'));
      }
    }
    if ( !empty($names_string) ) {
      $names_string = substr($names_string, 0, -2);
    }
    $result['namesString'] = $names_string;

    $Qstatuses->freeResult();

    return $result;
  }
}
?>