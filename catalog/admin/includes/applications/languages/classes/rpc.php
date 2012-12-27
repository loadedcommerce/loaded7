<?php
/*
  $Id: rpc.php v1.0 2011-11-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html

  @function The lC_Languages_Admin_rpc class is for AJAX remote program control
*/
require_once('includes/applications/languages/classes/languages.php');
require_once('includes/applications/currencies/classes/currencies.php');

class lC_Languages_Admin_rpc {
 /*
  * Returns the languages datatable data for listings
  *
  * @access public
  * @return json
  */
  public static function getAll() {

  $result = lC_Languages_Admin::getAll();
  $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  }
 /*
  * Return the data used on the dialog forms
  *
  * @param integer $_GET['lid'] The language id
  * @access public
  * @return json
  */
  public static function getFormData() {
  $result = lC_Languages_Admin::getData($_GET['lid']);
  $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  }
 /*
  * Save the language
  *
  * @param integer $_GET['lid'] The language id
  * @param array $_GET The language data
  * @access public
  * @return json
  */
  public static function saveLanguage() { 
    $result = array();
    $saved = lC_Languages_Admin::update($_GET['lid'], $_GET);
    if ($saved) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
 /*
  * Return the data used on the export forms
  *
  * @param integer $_GET['lid'] The language id
  * @access public
  * @return json
  */
  public static function getExportFormData() {
  $result = lC_Languages_Admin::getExportData($_GET['lid']);
  $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  }
 /*
  * Return the data used on the import forms
  *
  * @access public
  * @return json
  */
  public static function getImportFormData() {
  $result = lC_Languages_Admin::getImportData();
  $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  }
 /*
  * Delete the language
  *
  * @param integer $_GET['lid'] The language id
  * @access public
  * @return json
  */
  public static function deleteLanguage() {
    $result = array();
    $deleted = lC_Languages_Admin::delete($_GET['lid']);
    if ($deleted) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
 /*
  * Batch delete languages
  *
  * @param array $_GET['batch'] The language id's to delete
  * @access public
  * @return json
  */
  public static function batchDeleteLanguages() {
    $result = lC_Languages_Admin::batchDelete($_GET['batch']);
    if (isset($result['namesString']) && $result['namesString'] != null) {
    } else {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  } 
 /*
  * Return the language definition groups
  *
  * @param string $_GET[$_module] The module name
  * @access public
  * @return json
  */
  public static function getDefinitionGroups() {
    global $_module;

    $result = lC_Languages_Admin::getDefinitionGroups($_GET[$_module]);
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  }
 /*
  * Return the language definition data
  *
  * @param string $_GET[$_module] The module name
  * @param string $_GET['group'] The module definition group
  * @access public
  * @return json
  */
  public static function getDefinitions() {
    global $_module;

    $result = lC_Languages_Admin::getDefinitions($_GET[$_module], $_GET['group']);
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  }
 /*
  * Return the language definition group data
  *
  * @param string $_GET['group'] The module definition group
  * @access public
  * @return json
  */
  public static function getDefinitionGroupsData() {
    global $_module;

    $result = lC_Languages_Admin::getDefinitionGroupsData($_GET['group']);
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  }
 /*
  * Delete the language definition group
  *
  * @param string $_GET['group'] The module definition group
  * @access public
  * @return json
  */
  public static function deleteDefinitionGroup() {
    $result = array();
    $deleted = lC_Languages_Admin::deleteDefinitionGroup($_GET['group']);
    if ($deleted) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
 /*
  * Return the language definition data used in the dialog forms
  *
  * @param integer $_GET['did'] The module definition group id
  * @access public
  * @return json
  */
  public static function getDefinitionsFormData() {
  $result = lC_Languages_Admin::getDefinitionsFormData($_GET['did']);
  $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  }
 /*
  * Insert a language definition
  *
  * @param string $_GET['group'] The module definition group
  * @param array $_GET The language definition data
  * @access public
  * @return json
  */
  public static function insertDefinition() {
    $result = array();
    $saved = lC_Languages_Admin::insertDefinition($_GET['group'], $_GET);
    if ($saved) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
 /*
  * Update the language definition
  * 
  * @param string $_GET['$_module'] The admin module
  * @param string $_GET['group'] The module definition group
  * @param array $_GET The module definition data
  * @access public
  * @return json
  */
  public static function updateDefinitions() {
    global $_module;

    $result = array();
    $saved = lC_Languages_Admin::updateDefinitions($_GET[$_module], $_GET['group'], $_GET);
    if ($saved) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
 /*
  * Delete language definitions
  * 
  * @param string $_GET['$_module'] The admin module
  * @param string $_GET['group'] The module definition group
  * @param array $_GET['did'] The module definitions to delete
  * @access public
  * @return json
  */
  public static function deleteDefinitions() {
    global $_module;

    $result = array();
    $deleted = lC_Languages_Admin::deleteDefinitions($_GET[$_module], $_GET['group'], array($_GET['did']));
    if ($deleted) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
 /*
  * Batch delete language definitions
  * 
  * @param string $_GET['$_module'] The admin module
  * @param string $_GET['group'] The module definition group
  * @param array $_GET['batch'] The language definition id's to delete
  * @access public
  * @return json
  */
  public static function batchDeleteDefinitions() {
    global $_module;

    $result = array();
    $deleted = lC_Languages_Admin::deleteDefinitions($_GET[$_module], $_GET['group'], $_GET['batch']);
    if ($deleted) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
}
?>
