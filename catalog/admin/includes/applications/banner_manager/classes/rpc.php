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

require_once($lC_Vqmod->modCheck('includes/applications/banner_manager/classes/banner_manager.php')); 

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