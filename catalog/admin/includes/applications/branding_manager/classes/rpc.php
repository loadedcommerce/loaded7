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
  /*
  * Deletes the store branding logo
  *
  * @access public
  * @return json
  */
  public static function deleteBmLogo() {
    
    $result = lC_Branding_manager_Admin::deleteBmLogo($_GET['logo']);
    if ($result == 1) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }
    $result = array('rpcStatus' => RPC_STATUS_SUCCESS);
    
    echo json_encode($result);
  }
  /*
  * Deletes the store branding og image
  *
  * @access public
  * @return json
  */
  public static function deleteOgImage() {
    
    $result = lC_Branding_manager_Admin::deleteOgImage($_GET['ogimage']);
    if ($result == 1) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }
    $result = array('rpcStatus' => RPC_STATUS_SUCCESS);

    echo json_encode($result);
  }
}
?>