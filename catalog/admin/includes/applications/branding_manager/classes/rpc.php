<?php
/*
  $Id: rpc.php v1.0 2011-11-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html

  @function The lC_Branding_manager_Admin_rpc class is for AJAX remote program control
*/
require('includes/applications/branding_manager/classes/branding_manager.php');
require_once($lC_Vqmod->modCheck('includes/classes/image.php'));

class lC_Branding_manager_Admin_rpc {

 /*
  * Returns the store branding data
  *
  * @access public
  * @return json
  */
  public static function get() {
    die("lineeeeee 26 <br>");
    $result = lC_Branding_manager_Admin::get();
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  }

 /*
  * Returns the store branding data
  *
  * @access public
  * @return json
  */
  public static function save() {
    $result = lC_Branding_manager_Admin::save();
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
     die("lineeeeee 55 <br>");
    global $_module;

    $result = lC_Categories_Admin::formData($_GET['cid'], $_GET[$_module]); 
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

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
    
    $branding_logo = $uploader->handleUpload('../images/branding/');
    
    $result = array('result' => 1,
                    'fileName' => $branding_logo['filename'],
                    'success' => true,
                    'rpcStatus' => RPC_STATUS_SUCCESS);

    echo json_encode($result);
  }
}
?>