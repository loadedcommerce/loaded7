<?php
/*
  $Id: rpc.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html

  @function The lC_Cache_Admin_rpc class is for AJAX remote program control
*/
global $lC_Vqmod;

require_once($lC_Vqmod->modCheck('includes/applications/cache/classes/cache.php')); 

class lC_Cache_Admin_rpc {
 /*
  * Returns the cache files datatable data for listings
  *
  * @access public
  * @return json
  */
  public static function getAll() {
    $result = lC_Cache_Admin::getAll();
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  }
 /*
  * Delete a cache file
  *
  * @param integer $_GET['cid'] The cache file name to delete
  * @access public
  * @return json
  */
  public static function deleteEntry() {
    $result = array();
    $deleted = lC_Cache_Admin::delete($_GET['cid']);
    if ($deleted) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
 /*
  * Batch delete cache files
  *
  * @param array $_GET['batch'] The cache file name's to delete
  * @access public
  * @return json
  */
  public static function batchDeleteEntries() {
    $result = array();
    $deleted = lC_Cache_Admin::batchDelete($_GET['batch']);
    if ($deleted) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
}
?>