<?php
/*
  $Id: rpc.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html

  @function The lC_Banner_manager_Admin_rpc class is for AJAX remote program control
*/
require_once('includes/applications/banner_manager/classes/banner_manager.php'); 

class lC_Banner_manager_Admin_rpc {
 /*
  * Returns the specials datatable data for listings
  *
  * @access public
  * @return json
  */
  public static function getAll() {
    $result = lC_Banner_manager_Admin::getAll();
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  }
 /*
  * Return the data used on the dialog forms
  *
  * @param integer $_GET['bid'] The banner id
  * @access public
  * @return json
  */
  public static function getFormData() {  
    $result = lC_Banner_manager_Admin::formData($_GET['bid']);
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  } 
 /*
  * Delete the banner record
  *
  * @param integer $_GET['bid'] The banner id to delete
  * @access public
  * @return json
  */
  public static function deleteEntry() {
    $result = array();
    $deleted = lC_Banner_manager_Admin::delete($_GET['bid']);
    if ($deleted) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
 /*
  * Batch delete banner records
  *
  * @param array $_GET['batch'] The banner id's to delete
  * @access public
  * @return json
  */
  public static function batchDeleteEntries() {
    $result = array();
    $deleted = lC_Banner_manager_Admin::batchDelete($_GET['batch']);
    if ($deleted) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
 /*
  * Return the data used on the stats dialog form
  *
  * @param integer $_GET['bid'] The banner id
  * @param string $_GET['type'] The banner stats type; yearly, monthly, daily
  * @param integer $_GET['month'] The banner stats month
  * @param integer $_GET['year'] The banner stats year
  * @access public
  * @return json
  */
  public static function getStats() {
    $result = lC_Banner_manager_Admin::stats($_GET['bid'], $_GET['type'], $_GET['month'], $_GET['year']);
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  } 
 /*
  * Return the data used on the preview dialog form
  *
  * @param integer $_GET['bid'] The banner id
  * @access public
  * @return json
  */
  public static function getPreview() {
    $result = lC_Banner_manager_Admin::preview($_GET['bid']);
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  }
}
?>