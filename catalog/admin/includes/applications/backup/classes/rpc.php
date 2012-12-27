<?php
/*
  $Id: rpc.php v1.0 2011-11-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html

  @function The lC_Backup_Admin_rpc class is for AJAX remote program control
*/
require_once('includes/applications/backup/classes/backup.php'); 

class lC_Backup_Admin_rpc {
 /*
  * Returns the backups datatable data for listings
  *
  * @access public
  * @return json
  */
  public static function getAll() {
    $result = lC_Backup_Admin::getAll();
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  }
 /*
  * Execute backup
  *
  * @param boolean $_GET['compression'] True = create backup using compression
  * @param boolean false Turn off local only backup
  * @access public
  * @return json
  */
  public static function doBackup() {
    if ( lC_Backup_Admin::backup($_GET['compression'], false)) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
 /*
  * Restore a backup file from the server
  *
  * @param string $_GET['fname'] The backup file name
  * @access public
  * @return json
  */
  public static function restoreEntry() {

    if (lC_Backup_Admin::restore($_GET['fname'])) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  } 
 /*
  * Delete a backup file from the server
  *
  * @param string $_GET['fname'] The backup file name to delete
  * @access public
  * @return json
  */
  public static function deleteEntry() {
    $deleted = lC_Backup_Admin::delete($_GET['fname']);
    if ($deleted) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
 /*
  * Batch delete backup files
  *
  * @param array $_GET['batch'] The backup file names to delete
  * @access public
  * @return json
  */
  public static function batchDeleteEntries() {
    $deleted = lC_Backup_Admin::batchDelete($_GET['batch']);
    if ($deleted) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
 /*
  * Clear the last backup notice
  *
  * @access public
  * @return json
  */
  public static function doForget() {
    if ( lC_Backup_Admin::forget() ) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
}
?>