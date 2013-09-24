<?php
/**
  $Id: rpc.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
global $lC_Vqmod;

require_once($lC_Vqmod->modCheck('includes/applications/store/classes/store.php'));

class lC_Store_Admin_rpc {
 /*
  * Returns the addons datatable data for listings
  *
  * @access public
  * @return json
  */
  public static function getAll() {
    $result = lC_Store_Admin::getAll();
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  }
 /*
  * Return the data used on the modal forms
  *
  * @param string $_GET['name'] The addon name
  * @access public
  * @return json
  */
  public static function getFormData() {
    $result = lC_Store_Admin::getData($_GET['name']);     
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  } 
 /*
  * Save the addon data
  *
  * @param string $_GET['configuration'] The addon name
  * @access public
  * @return json
  */ 
  public static function saveAddon() { 
    $result = array();
    $saved = lC_Store_Admin::save(array('configuration' => $_GET['configuration']));
    if ($saved) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
 /*
  * Uninstall the addon
  *
  * @param string $_GET['addon'] The addon name
  * @access public
  * @return json
  */
  public static function uninstallAddon() {
    $result = array();
    $uninstalled = lC_Store_Admin::uninstall($_GET['name']);
    if ($uninstalled) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  } 
 /*
  * Install the addon
  *
  * @param string $_GET['addon'] The addon name
  * @access public
  * @return json
  */ 
  public static function installAddon() {
    $result = array();
    $installed = lC_Store_Admin::install($_GET['name']);
    if ($installed) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }   
}
?>