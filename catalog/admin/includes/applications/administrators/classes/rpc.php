<?php
/**
  $Id: rpc.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html

  @function The lC_Administrators_Admin_rpc class is for AJAX remote program control
*/
global $lC_Vqmod;

require($lC_Vqmod->modCheck('includes/applications/administrators/classes/administrators.php'));
require_once($lC_Vqmod->modCheck('includes/applications/languages/classes/languages.php'));

class lC_Administrators_Admin_rpc {
 /**
  * Returns the administrators datatable data for listings
  *
  * @access public
  * @return json
  */
  public static function getAll() {
    $result = lC_Administrators_Admin::getAll();
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  }
 /**
  * Returns the administrator data
  *
  * @param integer $_GET['aid'] The administrator id
  * @access public
  * @return json
  */
  public static function getData() {
    $result = lC_Administrators_Admin::getData($_GET['aid']);
    if (!isset($result['rpcStatus'])) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
 /**
  * Saves the administrator information
  *
  * @param integer  $_GET['aid']  The administrator id used on update, null on insert
  * @param array    $_GET         An array containing the administrator information
  * @access public
  * @return json
  */
  public static function saveAdmin() {
    $result = lC_Administrators_Admin::save($_GET['aid'], $_GET);
    if (!isset($result['rpcStatus'])) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
 /**
  * Delete the administrator record
  *
  * @param integer $_GET['aid'] The administrator id to delete
  * @access public
  * @return json
  */
  public static function deleteAdmin() {
    $result = array();
    $deleted = lC_Administrators_Admin::delete($_GET['aid']);
    if ($deleted) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
 /**
  * Returns all the administrator groups data
  *
  * @access public
  * @return json
  */
  public static function getAllGroups() {
    $result = lC_Administrators_Admin::getAllGroups();
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  }
 /**
  * Returns the individual administrators groups data
  *
  * @param integer $_GET['gid'] The administrators group id
  * @access public
  * @return json
  */
  public static function getGroupData() {
    $result = lC_Administrators_Admin::getGroupData($_GET['gid']);
    if (!isset($result['rpcStatus'])) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
 /**
  * Saves the administrators groups data
  *
  * @param integer  $_GET['gid'] The administrators group id used on update, null on insert
  * @param array    $_GET        An array containing the administrator information
  * @access public
  * @return json
  */
  public static function saveGroup() {
    $result = lC_Administrators_Admin::saveGroup($_GET['gid'], $_GET);
    if (!isset($result['rpcStatus'])) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
 /**
  * Delete the administrators groups record
  *
  * @param integer $_GET['aid'] The administrators group id to delete
  * @access public
  * @return json
  */
  public static function deleteGroup() {
    $result = array();
    $deleted = lC_Administrators_Admin::deleteGroup($_GET['gid']);
    if ($deleted) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
 /**
  * Validate the password
  *
  * @param string $_GET['encrypted']  Password hash from DB
  * @param string $_GET['plain']  Plain Password 
  * @access public
  * @return json
  */
  public static function validatePassword() {
    $result = array();

    $result = lC_Administrators_Admin::validatePassword($_GET['plain'], $_GET['encrypted']);

    echo json_encode($result);
  }
 /**
  * upload the profile image
  *
  * @access public
  * @return json
  */
  public static function fileUpload() {
    $result = array();

    $result = lC_Administrators_Admin::profileImageUpload($_GET['administrators']);

    echo json_encode($result);
  }
}
?>