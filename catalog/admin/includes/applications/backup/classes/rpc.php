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

require_once($lC_Vqmod->modCheck('includes/applications/backup/classes/backup.php')); 

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

    $result = array();
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