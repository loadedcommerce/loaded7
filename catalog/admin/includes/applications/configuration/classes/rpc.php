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

require_once($lC_Vqmod->modCheck('includes/applications/configuration/classes/configuration.php'));

class lC_Configuration_Admin_rpc {
 /*
  * Returns the configurations datatable data for listings
  *
  * @access public
  * @return json
  */
  public static function getAll() {
    $result = lC_Configuration_Admin::getAll($_GET['gid'], $_GET['view']);
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  }
 /*
  * Returns the data used on the dialog forms
  *
  * @param integer $_GET['cid'] The configuration id
  * @access public
  * @return json
  */
  public static function getFormData() {
    $result = lC_Configuration_Admin::getFormData($_GET['cid']); 
    if (!isset($result['rpcStatus'])) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
 /*
  * Saves the configuration information
  *
  * @param array $_GET['configuration'] An array containing the configuration data to save
  * @access public
  * @return json
  */
  public static function saveEntry() {
    $result = array();
    $saved = lC_Configuration_Admin::save($_GET['configuration']);
    if ($saved) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
}
?>