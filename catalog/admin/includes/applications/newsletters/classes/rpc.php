<?php
/*
  $Id: rpc.php v1.0 2011-11-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html

  @function The lC_Newsletters_Admin_rpc class is for AJAX remote program control
*/
require_once('includes/applications/newsletters/classes/newsletters.php'); 

class lC_Newsletters_Admin_rpc {
 /*
  * Returns the specials datatable data for listings
  *
  * @access public
  * @return json
  */
  public static function getAll() {
    $result = lC_Newsletters_Admin::getAll();
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  }
 /*
  * Return the data used on the dialog forms
  *
  * @param integer $_GET['nid'] The newsletter id
  * @access public
  * @return json
  */
  public static function getFormData() {  
    $result = lC_Newsletters_Admin::formData($_GET['nid']);
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  } 
 /*
  * Save the newsletter information
  *
  * @param integer $_GET['nid'] The newsletter id used on update, null on insert
  * @param array $_GET An array containing the newsletter information
  * @access public
  * @return json
  */
  public static function saveEntry() { 
    $result = array();
    $saved = lC_Newsletters_Admin::save($_GET['nid'], $_GET);
    if ($saved) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
 /*
  * Delete the newsletter record
  *
  * @param integer $_GET['nid'] The newsletter id to delete
  * @access public
  * @return json
  */
  public static function deleteEntry() {
    $result = array();
    $deleted = lC_Newsletters_Admin::delete($_GET['nid']);
    if ($deleted) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
 /*
  * Batch delete newsletter records
  *
  * @param array $_GET['batch'] The newsletter id's to delete
  * @access public
  * @return json
  */
  public static function batchDeleteEntries() {
    $result = array();
    $deleted = lC_Newsletters_Admin::batchDelete($_GET['batch']);
    if ($deleted) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
 /*
  * Return the data used on the send dialog form
  *
  * @param integer $_GET['nid'] The newsletter id
  * @param boolean $_GET['send'] True = send the newsletter
  * @access public
  * @return json
  */
  public static function getSendData() {  
    $result = lC_Newsletters_Admin::sendData($_GET['nid'], $_GET['send']);
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  } 
 /*
  * Return the data used on the preview dialog form
  *
  * @param integer $_GET['nid'] The newsletter id
  * @access public
  * @return json
  */
  public static function getPreview() {
    $result = lC_Newsletters_Admin::preview($_GET['nid']);
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  }
 /*
  * Return the data used on the log dialog form
  *
  * @param integer $_GET['nid'] The newsletter id
  * @access public
  * @return json
  */ 
  public static function getLog() {
    $result = lC_Newsletters_Admin::logInfo($_GET['nid']);
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  }
}
?>