<?php
/*
  $Id: rpc.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html

  @function The lC_Services_Admin_rpc class is for AJAX remote program control
*/
require_once('includes/applications/services/classes/services.php');    

class lC_Services_Admin_rpc {
 /*
  * Returns the modules datatable data for listings
  *
  * @access public
  * @return json
  */
  public static function getAll() {
    $result = lC_Services_Admin::getAll();
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  }
 /*
  * Return the data used on the dialog forms
  *
  * @param string $_GET['module'] The module name
  * @access public
  * @return json
  */
  public static function getFormData() {
    $result = lC_Services_Admin::getData($_GET['module']);     
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  }       
 /*
  * Save the module
  *
  * @param array $_GET The service module information
  * @access public
  * @return json
  */
  public static function saveModule() { 
    $result = array();
    $saved = lC_Services_Admin::save($_GET);
    if ($saved) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  } 
 /*
  * Uninstall the service module
  *
  * @param string $_GET['module'] The service module name
  * @access public
  * @return json
  */  
  public static function uninstallModule() {
    $result = array();
    $uninstalled = lC_Services_Admin::uninstall($_GET['module']);
    if ($uninstalled) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }  
 /*
  * Install the service module
  *
  * @param string $_GET['module'] The service module name
  * @access public
  * @return json
  */
  public static function installModule() {
    $result = array();
    $installed = lC_Services_Admin::install($_GET['module']);
    if ($installed) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }        
}
?>