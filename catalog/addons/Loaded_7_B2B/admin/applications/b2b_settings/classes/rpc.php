<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce, LLC
  @copyright  Copyright 2003-2013 Loaded Commerce Development Team
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: rpc.php v1.0 2013-08-08 datazen $
*/
require_once(DIR_FS_CATALOG . 'addons/Loaded_7_B2B/admin/applications/b2b_settings/classes/b2b_settings.php');

class lC_B2b_settings_Admin_rpc {
 /*
  * Returns the customer access levels data for listings
  *
  * @access public
  * @return json
  */
  public static function getCustomersGroupAccessLevels() {
    $result = array();
    $result = lC_B2b_settings_Admin::getCustomersGroupAccessLevels();
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  }
  
  public static function getCustomerAccessMembers() {
    $result = array();
    $result = lC_B2b_settings_Admin::getMembers((int)$_GET['aid']);
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);    
  }
 /*
  * Add new customer access level
  *
  * @param  string  $level  The access level name to save
  * @access public
  * @return json
  */
  public static function addCustomerAccessLevel() { 
    $result = array();  
    $added = lC_B2b_settings_Admin::addCustomerAccessLevel($_GET['level']);
    if ($added) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }  
 /*
  * Delete the customer level record
  *
  * @param integer $_GET['aid'] The customer access level id to delete
  * @access public
  * @return json
  */
  public static function deleteCustomerAccessLevel() {
    $result = array();
    $deleted = lC_B2b_settings_Admin::deleteCustomerAccessLevel((int)$_GET['aid']);
    if ($deleted) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
 /*
  * Return the data used on the modal forms
  *
  * @param integer $_GET['aid'] The customer access level id
  * @access public
  * @return json
  */
  public static function getCustomerAccessFormData() {  
    $result = lC_B2b_settings_Admin::getCustomerAccessFormData($_GET['aid']);
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  }   
 /*
  * Update customer access level
  *
  * @param integer $_GET['aid'] The customer access level id to update
  * @param integer $_GET        The customer access level data update
  * @access public
  * @return json
  */
  public static function updateCustomerAccessLevels() { 
    $result = array();  
    $updated = lC_B2b_settings_Admin::updateCustomerAccessLevels($_GET['aid'], $_GET);
    if ($updated) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }  
    
   
}
?>