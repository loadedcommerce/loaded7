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

   /**
  * upload the profile image
  *
  * @access public
  * @return json
  */
  public static function fileUpload() {
    $result = array();

    $result = lC_Branding_manager_Admin::brandingImageUpload($_GET['branding_manager_logo']);

    echo json_encode($result);
  }


   

}
?>