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

require_once($lC_Vqmod->modCheck('includes/applications/product_attributes/classes/product_attributes.php'));

class lC_Product_attributes_Admin_rpc {
 /*
  * Returns the product attribute modules datatable data for listings
  *
  * @access public
  * @return json
  */
  public static function getAll() {
    $result = lC_Product_attributes_Admin::getAll();
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  }
 /*
  * Uninstall the module
  *
  * @param string $_GET['module'] The module name
  * @access public
  * @return json
  */
  public static function uninstallModule() {
    $result = lC_Product_attributes_Admin::uninstall($_GET['module']);
    if (!isset($result['rpcStatus'])) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }  
 /*
  * Install the module
  *
  * @param string $_GET['module'] The module name
  * @access public
  * @return json
  */
  public static function installModule() {
    $result = lC_Product_attributes_Admin::install($_GET['module']);
    if (!isset($result['rpcStatus'])) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }  
}
?>
