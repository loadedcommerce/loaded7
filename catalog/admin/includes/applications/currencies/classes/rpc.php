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

require_once($lC_Vqmod->modCheck('includes/applications/currencies/classes/currencies.php'));

class lC_Currencies_Admin_rpc {
 /*
  * Returns the currencies datatable data for listings
  *
  * @access public
  * @return json
  */
  public static function getAll() {
    $result = lC_Currencies_Admin::getAll();
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  }
 /*
  * Return the data used on the dialog forms
  *
  * @param integer $_GET['cid'] The currencies id
  * @access public
  * @return json
  */
  public static function getFormData() {
    $result = lC_Currencies_Admin::getFormData($_GET['cid']);
    if (!isset($result['rpcStatus'])) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
 /*
  * Save the currencies information
  *
  * @param integer $_GET['cid'] The currencies id used on update, null on insert
  * @param array $_GET An array containing the currencies information
  * @param boolean $default True = set the currencies to be default
  * @access public
  * @return json
  */
  public static function saveCurrency() {
    $result = array();
    $default = (isset($_GET['default']) && $_GET['default'] == 'on') ? true : false;
    $saved = lC_Currencies_Admin::save($_GET, $default);
    if ($saved) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
 /*
  * Delete the currencies record
  *
  * @param integer $_GET['cid'] The currencies id to delete
  * @access public
  * @return json
  */
  public static function deleteCurrency() {
    $result = array();
    $deleted = lC_Currencies_Admin::delete($_GET['cid']);
    if ($deleted) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
 /*
  * Batch delete currencies records
  *
  * @param array $_GET['batch'] An array of currencies id's
  * @access public
  * @return json
  */
  public static function batchDelete() {
    $result = lC_Currencies_Admin::batchDelete($_GET['batch']);
    if (isset($result['namesString']) && $result['namesString'] != null) {
    } else {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
 /*
  * Get the update rates data for dialog form
  *
  * @access public
  * @return json
  */
  public static function getRatesData() {
    $result = lC_Currencies_Admin::getRatesData();
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  }
 /*
  * Update currency rates
  *
  * @param string $_GET['service'] The currency update service to use
  * @access public
  * @return json
  */
  public static function updateRates() {
    $result = lC_Currencies_Admin::updateRates($_GET['service']);
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  }
}
?>
