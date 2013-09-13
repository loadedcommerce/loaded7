<?php
/**
  @package    catalog::templates
  @author     Loaded Commerce, LLC
  @copyright  Copyright 2003-2013 Loaded Commerce Development Team
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: rpcphp v1.0 2013-08-08 datazen $
*/
require_once(DIR_FS_CATALOG . 'addons/Loaded_7_Pro/admin/applications/product_classes/classes/product_classes.php');

class lC_Product_classes_Admin_rpc {
 /*
  * Returns the customer groups datatable data for listings
  *
  * @access public
  * @return json
  */
  public static function getAll() {
    $result = lC_Product_classes_Admin::getAll();
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  }

}
?>