<?php
/*
  $Id: rpc.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html

  @function The lC_Templates_modules_Admin_rpc class is for AJAX remote program control
*/
require_once('includes/applications/templates_modules/classes/templates_modules.php');

class lC_Templates_modules_Admin_rpc {
 /*
  * Returns the templates modules datatable data for listings
  *
  * @access public
  * @return json
  */
  public static function getAll() {
    $result = lC_Templates_modules_Admin::getAll();
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  }
 /*
  * Return the data used on the dialog forms
  *
  * @param string $_GET['module'] The template module name
  * @access public
  * @return json
  */
  public static function getFormData() {
    $result = lC_Templates_modules_Admin::getData($_GET['module']);     
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  }  
 /*
  * Save the template module
  *
  * @param array $_GET The template module data
  * @access public
  * @return json
  */     
  public static function saveModule() { 
    $result = array();
    $saved = lC_Templates_modules_Admin::save($_GET);
    if ($saved) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  } 
 /*
  * Uninstall the template module
  *
  * @param string $_GET['module'] The template module name
  * @access public
  * @return json
  */
  public static function uninstallModule() {
    $result = array();
    $uninstalled = lC_Templates_modules_Admin::uninstall($_GET['module']);
    if ($uninstalled) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  } 
 /*
  * Install the template module
  *
  * @param string $_GET['module'] The template module name
  * @access public
  * @return json
  */
  public static function installModule() {
    $result = array();
    $uninstalled = lC_Templates_modules_Admin::install($_GET['module']);
    if ($uninstalled) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }        
}
?>