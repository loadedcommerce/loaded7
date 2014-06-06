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

require_once(DIR_FS_CATALOG . 'addons/Loaded_7_Pro/admin/applications/products/classes/products.php');

class lC_Products_Admin_rpc { 
 /*
  * Returns the templates modules layout datatable data for listings
  *
  * @param string $_GET['filter'] The category id 
  * @access public
  * @return json
  */
  public static function getComboRowData() {
    $result = lC_Products_pro_Admin::getComboRowData($_GET);
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  } 
}
?>