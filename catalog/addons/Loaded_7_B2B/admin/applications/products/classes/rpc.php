<?php
/**
  @package    catalog::addons::Loaded_7_Pro::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: rpc.php v1.0 2014-03-08 datazen $
*/
if (!defined('DIR_FS_CATALOG')) return false;

require_once(DIR_FS_CATALOG . 'addons/Loaded_7_B2B/admin/applications/products/classes/products.php');

class lC_Categories_Admin_rpc { 
 /*
  * Batch delete product access
  *
  * @param array $_GET The categories access data 
  * @access public
  * @return json
  */
  public static function batchEditAccess() {
    $result = array();
    $updated = lC_Products_b2b_Admin::batchEditAccess($_GET);
    if ($updated) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }  

    echo json_encode($result);
  } 
}
?>