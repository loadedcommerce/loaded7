<?php
/*
  $Id: rpc.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html

  @function The lC_Whos_online_Admin_rpc class is for AJAX remote program control
*/
require_once('../includes/classes/currencies.php');
require_once('../includes/classes/customer.php');
require_once('../includes/classes/navigation_history.php');
require_once('../includes/classes/shopping_cart.php');
require_once('includes/classes/tax.php');
require_once('includes/classes/geoip.php');
require_once('includes/applications/whos_online/classes/whos_online.php');

class lC_Whos_online_Admin_rpc {
 /*
  * Returns the whos online datatable data for listings
  *
  * @access public
  * @return json
  */
  public static function getAll() {
    $result = lC_Whos_online_Admin::getAll();
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  }
 /*
  * Return the whos online information
  *
  * @param integer $_GET['sid'] The session id
  * @access public
  * @return json
  */
  public static function getData() {
    $result = lC_Whos_online_Admin::getData($_GET['sid']);
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  }
 /*
  * Delete the whos online session 
  *
  * @param integer $_GET['sid'] The session id to delete
  * @access public
  * @return json
  */
  public static function deleteEntry() {
    $result = array();
    $deleted = lC_Whos_online_Admin::delete($_GET['sid']);
    if ($deleted) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
 /*
  * Batch delete whos onlone sessions
  *
  * @param array $_GET['batch'] The session id's to delete
  * @access public
  * @return json
  */
  public static function batchDeleteEntries() {
    $result = array();
    $deleted = lC_Whos_online_Admin::batchDelete($_GET['batch']);
    if ($deleted) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
}
?>