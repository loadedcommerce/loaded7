<?php
/*
  $Id: rpc.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html

  @function The lC_Modules_addons_Admin_rpc class is for AJAX remote program control
*/
require('includes/applications/templates_modules_layout/classes/templates_modules_layout.php');

class lC_Templates_modules_layout_Admin_rpc {
 /*
  * Returns the templates modules layout datatable data for listings
  *
  * @access public
  * @return json
  */
  public static function getAll() {
    $result = lC_Templates_modules_layout_Admin::getAll($_GET['filter']);
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  }
 /*
  * Returns the available templates
  *
  * @param string $_GET['filter'] The template name 
  * @access public
  * @return json
  */
  public static function getTemplatesArray() {
    $result = lC_Templates_modules_layout_Admin::getTemplatesArray($_GET['filter']);
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  }  
 /*
  * Return the data used on the dialog forms
  *
  * @param string $_GET['tid'] The template id
  * @access public
  * @return json
  */  
  public static function getFormData() {
    $result = lC_Templates_modules_layout_Admin::getData($_GET['tid']);
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  }   
 /*
  * Save the template module layout
  *
  * @param integer $_GET['tid'] The template id
  * @access public
  * @return json
  */ 
  public static function saveModule() { 
    $result = array();
    $saved = lC_Templates_modules_layout_Admin::save($_GET['tid']);
    if ($saved) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
 /*
  * Delete the template module layout
  *
  * @param integer $_GET['tid'] The template id
  * @access public
  * @return json
  */
  public static function deleteModule() {
    $result = array();
    $deleted = lC_Templates_modules_layout_Admin::delete($_GET['tid']);
    if ($deleted) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
 /*
  * Batch delete template module layouts
  *
  * @param array $_GET['batch'] The template id's to delete
  * @access public
  * @return json
  */
  public static function batchDelete() {
    $result = array();
    $deleted = lC_Templates_modules_layout_Admin::batchDelete($_GET['batch']);
    if ($deleted) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }      
   
    echo json_encode($result);
  }    
}
?>