<?php
/*
  $Id: rpc.php v1.0 2011-11-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html

  @function The lC_Coupons_Admin_rpc class is for AJAX remote program control
*/
require('includes/applications/coupons/classes/coupons.php');

class lC_Coupons_Admin_rpc {
 /*
  * Returns the customer groups datatable data for listings
  *
  * @access public
  * @return json
  */
  public static function getAll() {
    $result = lC_Coupons_Admin::getAll();
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  }

}
?>