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

require($lC_Vqmod->modCheck('includes/applications/manufacturers/classes/manufacturers.php'));

class lC_Manufacturers_Admin_rpc {
 /*
  * Returns the manufacturers datatable data for listings
  *
  * @access public
  * @return json
  */
  public static function getAll() {
    $result = lC_Manufacturers_Admin::getAll();
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  }
 /*
  * Return the data used on the dialog forms
  *
  * @param integer $_GET['mid'] The customer group id
  * @param boolean $edit True = called from edit dialog else called from delete dialog
  * @access public
  * @return json
  */
  public static function getFormData() {
    $result = lC_Manufacturers_Admin::getFormData($_GET['mid']);
    if (!isset($result['rpcStatus'])) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
 /*
  * Delete the manufacturers record
  *
  * @param integer $_GET['mid'] The customer group id to delete
  * @access public
  * @return json
  */    
  public static function deleteEntry() {
    $result = array();
    $deleted = lC_Manufacturers_Admin::delete($_GET['mid']);
    if ($deleted) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
 /*
  * Batch delete manufacturers records
  *
  * @param array $_GET['batch'] An array of manufacturers id's
  * @access public
  * @return json
  */ 
  public static function batchDeleteEntries() {
    $result = array();
    $deleted = lC_Manufacturers_Admin::batchDelete($_GET['batch']);
     if ($deleted) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }    
}
?>