<?php
/*
  $Id: rpc.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html

  @function The lC_Images_Admin_rpc class is for AJAX remote program control
*/
global $lC_Vqmod;

require_once($lC_Vqmod->modCheck('includes/applications/images/classes/images.php'));

class lC_Images_Admin_rpc {
 /*
  * Returns the image manager datatable data for listings
  *
  * @access public
  * @return json
  */
  public static function getAll() {
    $result = lC_Images_Admin::getAll();
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  }
 /*
  * Checks for images
  *
  * @access public
  * @return json
  */
  public static function checkImages() {
    $result = lC_Images_Admin::check();
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  }
 /*
  * Returns the data used on the resize dialog form
  *
  * @access public
  * @return json
  */
  public static function getResizeInfo() {
    $result = lC_Images_Admin::getInfo();
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  }
 /*
  * Resize images
  *
  * @access public
  * @return json
  */
  public static function resizeImages() {
    $result = lC_Images_Admin::resizeBatch();
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  }
}
?>