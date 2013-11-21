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

require_once($lC_Vqmod->modCheck('includes/applications/categories/classes/categories.php'));
require_once($lC_Vqmod->modCheck('includes/classes/category_tree.php'));
require_once($lC_Vqmod->modCheck('includes/classes/image.php')); 

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
    lC_Cache::clear('category_tree');
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
    lC_Cache::clear('category_tree');
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
    lC_Cache::clear('category_tree');
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
    lC_Cache::clear('category_tree');
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
                    'fileName' => $categories_image['filename'],
                    'success' => true,
                    'rpcStatus' => RPC_STATUS_SUCCESS);

    echo json_encode($result);
  }
  
 /*
  * update category sorting
  *
  * @param array $_GET The categories sort_order_x to update
  * @access public
  * @return json
  */
  public static function cSort() {
    $sort = lC_Categories_Admin::cSort($_GET);
    lC_Cache::clear('category_tree');
    
    if ($sort) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }  

    echo json_encode($result);
  }
  
 /*
  * update category status
  *
  * @param int $_GET the category id and new value of the status 
  * @access public
  * @return json
  */
  public static function updateStatus() {
    $status = lC_Categories_Admin::updateStatus($_GET['cid'], $_GET['val']);
    
    if ($status) {
      lC_Cache::clear('category_tree');
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }  

    echo json_encode($result);
  }
  
 /*
  * update category show in top nav
  *
  * @param int $_GET the category id and new value of the show in top nav 
  * @access public
  * @return json
  */
  public static function updateVisibilityNav() {
    $status = lC_Categories_Admin::updateVisibilityNav($_GET['cid'], $_GET['val']);
    
    if ($status) {
      lC_Cache::clear('category_tree');
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }  

    echo json_encode($result);
  }
  
 /*
  * update category show in infobox
  *
  * @param int $_GET the category id and new value of the show in infobox 
  * @access public
  * @return json
  */
  public static function updateVisibilityBox() {
    $status = lC_Categories_Admin::updateVisibilityBox($_GET['cid'], $_GET['val']);
    
    if ($status) {
      lC_Cache::clear('category_tree');
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }  

    echo json_encode($result);
  }
   
 /*
  * Check category permalink
  *
  * @param array $_GET['categories_permalink'] The category permalink to validate 
  * @access public
  * @return json
  */
  public static function validatePermalink() {
    $data = str_replace('%5B', '[', $_GET);
    $data = str_replace('%5D', ']', $data);
    
    $validated = lC_Categories_Admin::validatePermalink($data['categories_permalink'], $data['cid'], $data['type']);

    echo json_encode($validated);
  }

 /*
  * Deletes the categories image
  *
  * @access public
  * @return json
  */
  public static function deleteCatImage() {
    
    $result = lC_Categories_Admin::deleteCatImage($_GET['image'], $_GET['id']);
    if ($result == 1) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }
    $result = array('rpcStatus' => RPC_STATUS_SUCCESS);
    
    echo json_encode($result);
  }
}
?>