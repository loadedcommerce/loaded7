<?php
/*
  $Id: rpc.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html

  @function The lC_Countries_Admin_rpc class is for AJAX remote program control
*/
require('includes/applications/countries/classes/countries.php');

class lC_Countries_Admin_rpc {
 /*
  * Returns the countries datatable data for listings
  *
  * @access public
  * @return json
  */
  public static function getAll() {
    $result = lC_Countries_Admin::getAll();
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  }
 /*
  * Returns the data used on the dialog forms
  *
  * @param integer $_GET['cid'] The country id
  * @param boolean $edit True = called from edit dialog else called from delete dialog
  * @access public
  * @return json
  */
  public static function getFormData() {
    $edit = (isset($_GET['edit']) && $_GET['edit'] == 'true') ? true : false;
    $result = lC_Countries_Admin::getFormData($_GET['cid'], $edit);
    if (!isset($result['rpcStatus'])) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
 /*
  * Saves the country information
  *
  * @param integer $_GET['cid'] The country id used on update, null on insert
  * @param array $_GET An array containing the country information
  * @access public
  * @return json
  */
  public static function saveCountry() {
    $result = array();
    $saved = lC_Countries_Admin::save($_GET['cid'], $_GET);
    if ($saved) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
 /*
  * Delete the country record
  *
  * @param integer $_GET['cid'] The country id to delete
  * @access public
  * @return json
  */
  public static function deleteCountry() {
    $result = array();
    $deleted = lC_Countries_Admin::delete($_GET['cid']);
    if ($deleted) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
 /*
  * Batch delete country records
  *
  * @param array $_GET['batch'] An array of country id's
  * @access public
  * @return json
  */
  public static function batchDelete() {
    $result = lC_Countries_Admin::batchDelete($_GET['batch']);
     if (isset($result['namesString']) && $result['namesString'] != null) {
    } else {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
 /*
  * Returns the zones datatable data for listings
  *
  * @access public
  * @return json
  */
  public static function getAllZones() {
    global $_module;

    $result = lC_Countries_Admin::getAllZones($_GET[$_module]);
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  }
 /*
  * Saves the zone information
  *
  * @param integer $_GET['zid'] The zone id used on update, null on insert
  * @param array $_GET An array containing the zone information
  * @access public
  * @return json
  */
  public static function saveZone() {
    $result = array();
    $saved = lC_Countries_Admin::saveZone($_GET['zid'], $_GET);
    if ($saved) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
 /*
  * Returns the zone data used on the dialog forms
  *
  * @param integer $_GET['zid'] The zone id
  * @access public
  * @return json
  */
  public static function getZoneFormData() {
    $edit = (isset($_GET['edit']) && $_GET['edit'] == 'true') ? true : false;
    $result = lC_Countries_Admin::getZoneFormData($_GET['zid'], $edit);
    if (!isset($result['rpcStatus'])) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
 /*
  * Delete the zone record
  *
  * @param integer $_GET['zid'] The zone id to delete
  * @access public
  * @return json
  */
  public static function deleteZone() {
    $result = array();
    $deleted = lC_Countries_Admin::deleteZone($_GET['zid']);
    if ($deleted) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
 /*
  * Batch delete zone records
  *
  * @param array $_GET['batch'] An array of zone id's
  * @access public
  * @return json
  */
  public static function batchDeleteZones() {
    $result = lC_Countries_Admin::batchDeleteZones($_GET['batch']);
    if (isset($result['namesString']) && $result['namesString'] != null) {
    } else {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
}
?>