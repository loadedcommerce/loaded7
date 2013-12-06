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