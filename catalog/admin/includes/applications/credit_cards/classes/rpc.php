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

require($lC_Vqmod->modCheck('includes/applications/credit_cards/classes/credit_cards.php'));

class lC_Credit_cards_Admin_rpc {
 /*
  * Returns the credit cards datatable data for listings
  *
  * @access public
  * @return json
  */
  public static function getAll() {
    $result = lC_Credit_cards_Admin::getAll();
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  }
 /*
  * Returns the data used on the dialog forms
  *
  * @param integer $_GET['ccid'] The credit card id
  * @access public
  * @return json
  */
  public static function getFormData() {
    $result = lC_Credit_cards_Admin::getFormData($_GET['ccid']);
    if (!isset($result['rpcStatus'])) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
 /*
  * Saves the credit card information
  *
  * @param integer $_GET['ccid'] The credit card id used on update, null on insert
  * @param array $_GET An array containing the credit card information
  * @param boolean $default True = set the credit card to be default
  * @access public
  * @return json
  */
  public static function saveCard() { 
    $result = array();
    $default = (isset($_GET['default']) && $_GET['default'] == 'on') ? true : false;
    $saved = lC_Credit_cards_Admin::save($_GET['ccid'], $_GET, $default);
    if ($saved) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
 /*
  * Deletes the credit card record
  *
  * @param integer $_GET['ccid'] The credit card id to delete
  * @access public
  * @return json
  */    
  public static function deleteCard() {
    $result = array();
    $deleted = lC_Credit_cards_Admin::delete($_GET['ccid']);
    if ($deleted) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
 /*
  * Batch delete credit card records
  *
  * @param array $_GET['batch'] An array of credit card id's
  * @access public
  * @return json
  */ 
  public static function batchDelete() {
    $result = array();
    $deleted = lC_Credit_cards_Admin::batchDelete($_GET['batch']);
    if ($deleted) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
 /*
  * Update credit card status
  *
  * @param integer $_GET['ccid'] The credit card id to delete 
  * @param string $_GET['cstatus'] The current status, 1=active, 0=inactive 
  * @access public
  * @return json
  */
  public static function updateStatus() {
    $result = array();
    $updated = lC_Credit_cards_Admin::updateStatus($_GET['ccid'], $_GET['cstatus']);
    if ($updated) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
}
?>