<?php
/*
  $Id: rpc.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html

  @function The lC_Product_attributes_Admin_rpc class is for AJAX remote program control
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
