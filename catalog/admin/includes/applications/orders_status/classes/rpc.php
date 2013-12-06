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

require($lC_Vqmod->modCheck('includes/applications/orders_status/classes/orders_status.php'));

class lC_Orders_status_Admin_rpc {
 /*
  * Returns the order status datatable data for listings
  *
  * @access public
  * @return json
  */
  public static function getAll() {
    $result = lC_Orders_status_Admin::getAll();
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  }
 /*
  * Save the order status information
  *
  * @param integer $_GET['osid'] The order status id used on update, null on insert
  * @param array $_GET An array containing the order status information
  * @param boolean $default True = set the order status to be default
  * @access public
  * @return json
  */
  public static function saveStatus() { 
    $result = array();
    $default = (isset($_GET['default']) && $_GET['default'] == 'on') ? true : false;
    $saved = lC_Orders_status_Admin::save($_GET['osid'], $_GET, $default);
    if ($saved) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
 /*
  * Return the data used on the dialog forms
  *
  * @param integer $_GET['osid'] The order status id
  * @param boolean $edit True = called from edit dialog else called from delete dialog
  * @access public
  * @return json
  */
  public static function getFormData() {
    $edit = (isset($_GET['edit']) && $_GET['edit'] == 'true') ? true : false;
    $result = lC_Orders_status_Admin::getFormData($_GET['osid'], $edit);
    if (!isset($result['rpcStatus'])) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }    
 /*
  * Delete the order status record
  *
  * @param integer $_GET['osid'] The order status id to delete
  * @access public
  * @return json
  */    
  public static function deleteStatus() {
    $result = array();
    $deleted = lC_Orders_status_Admin::delete($_GET['osid']);
    if ($deleted) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
 /*
  * Batch delete order status records
  *
  * @param array $_GET['batch'] An array of order status id's
  * @access public
  * @return json
  */ 
  public static function batchDelete() {
    $result = lC_Orders_status_Admin::batchDelete($_GET['batch']);
    if (isset($result['namesString']) && $result['namesString'] != null) {
    } else {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }    
}
?>