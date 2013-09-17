<?php
/**
  @package    catalog::templates
  @author     Loaded Commerce, LLC
  @copyright  Copyright 2003-2013 Loaded Commerce Development Team
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: rpcphp v1.0 2013-08-08 datazen $
*/
require_once(DIR_FS_CATALOG . 'addons/Loaded_7_Pro/admin/applications/product_classes/classes/product_classes.php');

class lC_Product_classes_Admin_rpc {
 /*
  * Returns the customer groups datatable data for listings
  *
  * @access public
  * @return json
  */
  public static function getAll() {
    $result = lC_Product_classes_Admin::getAll();
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  }
 /*
  * Return the data used on the dialog forms
  *
  * @param integer $_GET['pcid'] The customer group id
  * @param boolean $edit True = called from edit dialog else called from delete dialog
  * @access public
  * @return json
  */
  public static function getFormData() {
    $edit = (isset($_GET['edit']) && $_GET['edit'] == 'true') ? true : false;
    $result = lC_Product_classes_Admin::getFormData($_GET['pcid'], $edit);
    if (!isset($result['rpcStatus'])) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
 /*
  * Save the customer group information
  *
  * @param integer $_GET['pcid'] The customer group id used on update, null on insert
  * @param array $_GET An array containing the customer group information
  * @param boolean $default True = set the customer group to be default
  * @access public
  * @return json
  */
  public static function saveClass() { 
    $result = array();
    $default = (isset($_GET['default']) && ($_GET['default'] == 'on' || $_GET['default'] == '1')) ? true : false;   
    $saved = lC_Product_classes_Admin::save($_GET['pcid'], $_GET, $default);
    if ($saved) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
 /*
  * Delete the customer groups record
  *
  * @param integer $_GET['pcid'] The customer group id to delete
  * @access public
  * @return json
  */    
  public static function deleteGroup() {
    $result = array();
    $deleted = lC_Product_classes_Admin::delete($_GET['pcid']);
    if ($deleted) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
 /*
  * Batch delete customer group records
  *
  * @param array $_GET['batch'] An array of customer group id's
  * @access public
  * @return json
  */ 
  public static function batchDelete() {
    $result = lC_Product_classes_Admin::batchDelete($_GET['batch']);
    if (isset($result['namesString']) && $result['namesString'] != null) {
    } else {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }    
}
?>