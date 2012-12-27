<?php
/*
  $Id: rpc.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html

  @function The lC_Templates_Admin_rpc class is for AJAX remote program control
*/
require_once('includes/applications/templates/classes/templates.php');

class lC_Templates_Admin_rpc {
 /*
  * Returns the templates datatable data for listings
  *
  * @access public
  * @return json
  */
  public static function getAll() {
    $result = lC_Templates_Admin::getAll();
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  }
 /*
  * Return the data used on the dialog forms
  *
  * @param string $_GET['template'] The template name
  * @access public
  * @return json
  */
  public static function getFormData() {
    $result = lC_Templates_Admin::getData($_GET['template']);     
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  }  
 /*
  * Save the template
  *
  * @param string $_GET['template'] The template name
  * @param array $_GET The template data
  * @access public
  * @return json
  */     
  public static function saveTemplate() { 
    $result = array();
    $saved = lC_Templates_Admin::save($_GET['template'], $_GET);
    if ($saved) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  } 
 /*
  * Uninstall the template
  *
  * @param string $_GET['template'] The template name
  * @access public
  * @return json
  */
  public static function uninstallTemplate() {
    $result = array();
    $uninstalled = lC_Templates_Admin::uninstall($_GET['template']);
    if ($uninstalled) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  } 
 /*
  * Install the template
  *
  * @param string $_GET['template'] The template name
  * @access public
  * @return json
  */ 
  public static function installTemplate() {
    $result = array();
    $uninstalled = lC_Templates_Admin::install($_GET['template']);
    if ($uninstalled) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  } 
}
?>