<?php
/*
  $Id: rpc.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html

  @function The lC_Weight_classes_Admin_rpc class is for AJAX remote program control
 */
require_once('includes/applications/weight_classes/classes/weight_classes.php');

class lC_Weight_classes_Admin_rpc {
 /*
  * Returns the weight class datatable data for listings
  *
  * @access public
  * @return json
  */
  public static function getAll() {
    $result = lC_Weight_classes_Admin::getAll();
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  }
 /*
  * Saves the weight class information
  *
  * @param integer $_GET['wcid'] The weight class id used on update, null on insert
  * @param array $_GET An array containing the weight class information
  * @param boolean $default Set the weight class to be default
  * @access public
  * @return json
  */
  public static function saveClass() {
    $result = array();
    $default = (isset($_GET['default']) && $_GET['default'] == 'on') ? true : false;
    $saved = lC_Weight_classes_Admin::save($_GET, $default);
    if ($saved) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
 /*
  * Returns the data used on the dialog forms
  *
  * @param integer $_GET['wcid'] The weight class id
  * @access public
  * @return json
  */
  public static function getFormData() {
    $edit = (isset($_GET['edit']) && $_GET['edit'] == 'true') ? true : false;
    $result = lC_Weight_classes_Admin::getFormData($_GET['wcid'], $edit);
    if (!isset($result['rpcStatus'])) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
 /*
  * Deletes the weight class record
  *
  * @param integer $_GET['wcid'] The weight class id
  * @access public
  * @return json
  */
  public static function deleteClass() {
    $result = array();
    $deleted = lC_Weight_classes_Admin::delete($_GET['wcid']);
    if ($deleted) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
 /*
  * Batch deletes weight class records
  *
  * @param array $_GET['batch'] An array of weight class wcid's
  * @access public
  * @return json
  */
  public static function batchDelete() {
    $result = lC_Weight_classes_Admin::batchDelete($_GET['batch']);
    if (isset($result['namesString']) && $result['namesString'] != null) {
    } else {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
}
?>