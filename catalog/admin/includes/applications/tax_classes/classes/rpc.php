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

require_once($lC_Vqmod->modCheck('includes/applications/tax_classes/classes/tax_classes.php'));
require_once($lC_Vqmod->modCheck('includes/applications/zone_groups/classes/zone_groups.php'));

class lC_Tax_classes_Admin_rpc {
 /*
  * Returns the tax class datatable data for listings
  *
  * @access public
  * @return json
  */
  public static function getAll() {
    $result = lC_Tax_classes_Admin::getAll();
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  }
 /*
  * Return the data used on the dialog forms
  *
  * @param string $_GET['tcid'] The tax class id
  * @access public
  * @return json
  */
  public static function getFormData() {
    $result = lC_Tax_classes_Admin::getData($_GET['tcid']);
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  } 
 /*
  * Save the tax class
  *
  * @param string $_GET['tcid'] The tax class id
  * @access public
  * @return json
  */   
  public static function saveClass() { 
    $result = array();
    $saved = lC_Tax_classes_Admin::save($_GET['tcid'], $_GET);
    if ($saved) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
 /*
  * Delete the tax class
  *
  * @param string $_GET['tcid'] The tax class id
  * @access public
  * @return json
  */
  public static function deleteClass() {
    $result = array();
    $deleted = lC_Tax_classes_Admin::delete($_GET['tcid']);
    if ($deleted) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
 /*
  * Batch delete the tax classes
  *
  * @param array $batch The tax class id's to delete
  * @access public
  * @return json
  */
  public static function batchDeleteClasses() {
    $result = lC_Tax_classes_Admin::batchDelete($_GET['batch']);
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  } 
 /*
  * Returns the tax rates datatable data for listings
  *
  * @access public
  * @return json
  */
  public static function getAllEntries() {
    global $_module;

    $result = lC_Tax_classes_Admin::getAllEntries($_GET[$_module]);
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  } 
 /*
  * Return the tax rates data used on the dialog forms
  *
  * @param string $_GET['trid'] The tax rates id
  * @access public
  * @return json
  */  
  public static function getEntryFormData() {
    global $_module;

    $result = lC_Tax_classes_Admin::getEntryFormData($_GET['trid']);     
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  } 
 /*
  * Save the tax rate
  *
  * @param string $_GET['trid'] The tax rates id
  * @access public
  * @return json
  */ 
   public static function saveEntry() { 
    $result = array();
    $saved = lC_Tax_classes_Admin::saveEntry($_GET['trid'], $_GET);
    if ($saved) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
 /*
  * Delete the tax rate
  *
  * @param string $_GET['trid'] The tax rates id
  * @access public
  * @return json
  */
  public static function deleteEntry() {
    $result = array();
    $deleted = lC_Tax_classes_Admin::deleteEntry($_GET['trid']);
    if ($deleted) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
 /*
  * Batch delete tax rates
  *
  * @param array $_GET['batch'] The tax rate id's to delete
  * @access public
  * @return json
  */
  public static function batchDeleteEntries() {
    $result = array();
    $deleted = lC_Tax_classes_Admin::batchDeleteEntries($_GET['batch']);
    if ($deleted) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  } 
}
?>