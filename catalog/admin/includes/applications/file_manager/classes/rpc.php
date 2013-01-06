<?php
/*
  $Id: rpc.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html

  @function The lC_File_manager_Admin_rpc class is for AJAX remote program control
*/
require_once('includes/applications/file_manager/classes/file_manager.php'); 

class lC_File_manager_Admin_rpc {
 /*
  * Returns the file manager datatable data for listings
  *
  * @access public
  * @return json
  */
  public static function getAll() {
    $result = lC_File_manager_Admin::getAll();
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  }   
 /*
  * Delete the file
  *
  * @param string $_GET['fid'] The file name to delete
  * @param string $_GET['fm_directory'] The file manager directory
  * @access public
  * @return json
  */
  public static function deleteEntry() {
    $result = array();
    $deleted = lC_File_manager_Admin::delete($_GET['fid'], $_SESSION['fm_directory']); 
    if ($deleted) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
 /*
  * Return the file information
  *
  * @param string $_GET['fid'] The file name
  * @access public
  * @return json
  */  
  public static function getEntry() {
    $result = array();
    $result = lC_File_manager_Admin::getEntry($_GET['fid']);
    if ($result['error'] == true) {
    } else {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;       
    }

    echo json_encode($result);
  }     
 /*
  * Save the file information
  *
  * @param string $_GET['fid'] The file name
  * @param string $_GET['contents'] The file contents
  * @param string $_GET['dir'] The file directory
  * @access public
  * @return json
  */   
  public static function saveEntry() {
    $result = lC_File_manager_Admin::saveFile($_GET['fid'], $_GET['contents'], $_GET['dir']); 
        
    $return = array();
    if ($result == true) $return = array('rpcStatus' => RPC_STATUS_SUCCESS);

    echo json_encode($return);
  } 
 /*
  * Create a directory
  *
  * @param string $_GET['fid'] The dir name
  * @param string $_GET['dir'] The path name
  * @access public
  * @return json
  */    
  public static function addFolder() {
    $result = lC_File_manager_Admin::createDirectory($_GET['fid'], $_GET['dir']); 
        
    $return = array();
    if ($result == true) $return = array('rpcStatus' => RPC_STATUS_SUCCESS);

    echo json_encode($return);
  }
 /*
  * Check the directory permissions
  *
  * @param string $_GET['dir'] The directory path
  * @access public
  * @return json
  */       
  public static function checkPerms() {
    $result = lC_File_manager_Admin::isWriteable($_GET['dir']); 

    $return = array();
    if ($result == true) $return = array('rpcStatus' => RPC_STATUS_SUCCESS);

    echo json_encode($return);
  }      
}
?>
