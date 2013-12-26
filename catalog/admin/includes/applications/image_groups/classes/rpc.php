<?php
/*
  $Id: rpc.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
 
  @function The lC_Image_groups_Admin_rpc class is for AJAX remote program control
*/
global $lC_Vqmod;

require($lC_Vqmod->modCheck('includes/applications/image_groups/classes/image_groups.php'));

class lC_Image_groups_Admin_rpc {
 /*
  * Returns the image group datatable data for listings
  *
  * @access public
  * @return json
  */
  public static function getAll() {
    $result = lC_Image_groups_Admin::getAll();
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  }
 /*
  * Saves the image group information
  *
  * @param integer $_GET['gid'] The image group id used on update, null on insert
  * @param array $_GET An array containing the image group information
  * @param boolean $default Set the image group to be default
  * @access public
  * @return json
  */
  public static function saveGroup() { 
    $result = array();
    $default = (isset($_GET['default']) && $_GET['default'] == 'on') ? true : false;
    $saved = lC_Image_groups_Admin::save($_GET['gid'], $_GET, $default);
    if ($saved) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
 /*
  * Returns the data used on the dialog forms
  *
  * @param integer $_GET['gid'] The image group id
  * @access public
  * @return json
  */
  public static function getFormData() {
    $edit = (isset($_GET['edit']) && $_GET['edit'] == 'true') ? true : false;
    $result = lC_Image_groups_Admin::getFormData($_GET['gid'], $edit);
    if (!isset($result['rpcStatus'])) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }    
 /*
  * Deletes the image group record
  *
  * @param integer $_GET['gid'] The image group id to delete
  * @access public
  * @return json
  */    
  public static function deleteGroup() {
    $result = array();
    $deleted = lC_Image_groups_Admin::delete($_GET['gid']);
    if ($deleted) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
 /*
  * Batch deletes image group records
  *
  * @param array $_GET['batch'] An array of image group id's
  * @access public
  * @return json
  */ 
  public static function batchDelete() {
    $result = lC_Image_groups_Admin::batchDelete($_GET['batch']);
    if (isset($result['namesString']) && $result['namesString'] != null) {
    } else {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }    
}
?>