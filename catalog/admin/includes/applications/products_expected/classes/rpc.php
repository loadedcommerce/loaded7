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

require_once($lC_Vqmod->modCheck('includes/applications/products_expected/classes/products_expected.php')); 
require_once($lC_Vqmod->modCheck('includes/applications/products/classes/products.php'));

class lC_Products_expected_Admin_rpc {
 /*
  * Returns the products expected datatable data for listings
  *
  * @access public
  * @return json
  */
  public static function getAll() {  
    $result = lC_Products_expected_Admin::getAll();
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  }
 /*
  * Return the data used on the dialog forms
  *
  * @param integer $_GET['peid'] The products expected id
  * @access public
  * @return json
  */
  public static function getFormData() {  
    $result = lC_Products_expected_Admin::formData($_GET['peid']);
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  } 

 /*
  * Save the products expected information
  *
  * @param integer $_GET['peid'] The products expected id used on update
  * @param array $_GET An array containing the products expected information
  * @access public
  * @return json
  */
  public static function saveEntry() { 
    $result = array();
    $updated = lC_Products_expected_Admin::save($_GET['peid'], $_GET);
    if ($updated) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
}
?>