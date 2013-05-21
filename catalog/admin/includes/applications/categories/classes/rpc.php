<?php
/*
  $Id: rpc.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html

  @function The lC_Categories_Admin_rpc class is for AJAX remote program control
*/
global $lC_Vqmod;

require_once($lC_Vqmod->modCheck('includes/applications/categories/classes/categories.php'));
require_once($lC_Vqmod->modCheck('includes/classes/category_tree.php')); 

class lC_Categories_Admin_rpc {
 /*
  * Returns the categories datatable data for listings
  *
  * @access public
  * @return json
  */
  public static function getAll() {  
    global $_module;

    $result = lC_Categories_Admin::getAll($_GET[$_module]);
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  }
 /*
  * Return the data used on the dialog forms
  *
  * @param integer $_GET['cid'] The categories id
  * @access public
  * @return json
  */
  public static function getFormData() {  
    global $_module;

    $result = lC_Categories_Admin::formData($_GET['cid'], $_GET[$_module]); 
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  } 
 /*
  * Delete the category record
  *
  * @param integer $_GET['cid'] The categories id to delete
  * @access public
  * @return json
  */
  public static function deleteCategory() {
    $result = array();
    $deleted = lC_Categories_Admin::delete($_GET['cid']);
    if ($deleted) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
 /*
  * Batch delete category records
  *
  * @param array $_GET['batch'] The categories id's to delete
  * @access public
  * @return json
  */
  public static function batchDelete() {
    $result = array();
    $deleted = lC_Categories_Admin::batchDelete($_GET['batch']);
    if ($deleted) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }  

    echo json_encode($result);
  }
 /*
  * Move the category record
  *
  * @param integer $_GET['cid'] The category id to move (moving from)
  * @param integer $_GET['new_category_id'] The new category id to (moving to)
  * @access public
  * @return json
  */
  public static function moveCategory() {
    $result = array();
    $moved = lC_Categories_Admin::move($_GET['cid'], $_GET['new_category_id']);
    if ($moved) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
 /*
  * Batch move category records
  *
  * @param array $_GET['batch'] The categories id's to move (moving from)
  * @param integer $_GET['new_category_id'] The new category id to (moving to)
  * @access public
  * @return json
  */
  public static function batchMove() {
    $result = array();
    $moved = lC_Categories_Admin::batchMove($_GET['batch'], $_GET['new_category_id']);
    if ($moved) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }  

    echo json_encode($result);
  }
 /*
  * Upload Category Image
  * 
  * @access public
  * @return json
  */
  public static function fileUpload() {
    global $lC_Database, $lC_Vqmod, $_module;

    $lC_Image = new lC_Image_Admin();

    require_once($lC_Vqmod->modCheck('includes/classes/ajax_upload.php'));

    // list of valid extensions, ex. array("jpeg", "xml", "bmp")
    $allowedExtensions = array('gif', 'jpg', 'jpeg', 'png');
    // max file size in bytes
    $sizeLimit = 10 * 1024 * 1024;

    $uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
    
    $categories_image = $uploader->handleUpload('../images/categories/');

    $result = array('result' => 1,
                    'success' => true,
                    'rpcStatus' => RPC_STATUS_SUCCESS);

    echo json_encode($result);
  } 
}
?>