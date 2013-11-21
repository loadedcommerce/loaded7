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

require_once($lC_Vqmod->modCheck('includes/applications/templates/classes/templates.php'));

class lC_Templates_Admin_rpc {
 /*
  * Returns the templates datatable data for listings
  *
  * @access public
  * @return json
  */
  public static function getAll() {
    $result = lC_Templates_Admin::getAll();
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  }
 /*
  * Return the data used on the dialog forms
  *
  * @param string $_GET['template'] The template name
  * @access public
  * @return json
  */
  public static function getFormData() {
    $result = lC_Templates_Admin::getData($_GET['template']);     
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  }  
 /*
  * Save the template
  *
  * @param string $_GET['template'] The template name
  * @param array $_GET The template data
  * @access public
  * @return json
  */     
  public static function saveTemplate() { 
    $result = array();
    $saved = lC_Templates_Admin::save($_GET['template'], $_GET);
    if ($saved) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  } 
 /*
  * Uninstall the template
  *
  * @param string $_GET['template'] The template name
  * @access public
  * @return json
  */
  public static function uninstallTemplate() {
    $result = array();
    $uninstalled = lC_Templates_Admin::uninstall($_GET['template']);
    if ($uninstalled) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  } 
 /*
  * Install the template
  *
  * @param string $_GET['template'] The template name
  * @access public
  * @return json
  */ 
  public static function installTemplate() {
    $result = array();
    $uninstalled = lC_Templates_Admin::install($_GET['template']);
    if ($uninstalled) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  } 
}
?>