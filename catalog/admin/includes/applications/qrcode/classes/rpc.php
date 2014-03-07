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
  require('includes/applications/qrcode/classes/qrcode.php');
  class lC_Qrcode_Admin_rpc {
    /*
    * Get QR Code  
    *
    * @access public
    * @return json
    */
    public static function getqrcode() {
      $result = array();
      if ($result = lC_Qrcode_Admin::qrcode()) {
        $result['rpcStatus'] = RPC_STATUS_SUCCESS;
      }
      echo json_encode($result);
    }
  }
?>