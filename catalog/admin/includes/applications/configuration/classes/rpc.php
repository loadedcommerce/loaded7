<?php
/*
  $Id: rpc.php v1.0 2011-11-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html

  @function The lC_Configuration_Admin_rpc class is for AJAX remote program control
*/
require('includes/applications/configuration/classes/configuration.php');

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