<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: rpc.php v1.0 2013-08-08 datazen $
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
  /*
  * Checks for images
  *
  * @access public
  * @return json
  */
  public static function checkImages() {
    $result = lC_Images_Admin::check();
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  }
 /*
  * Returns the data used on the resize dialog form
  *
  * @access public
  * @return json
  */
  public static function getResizeInfo() {
    $result = lC_Images_Admin::getInfo();
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  }
 /*
  * Resize images
  *
  * @access public
  * @return json
  */
  public static function resizeImages() {
    $result = lC_Images_Admin::resizeBatch();
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  }
 
}
?>