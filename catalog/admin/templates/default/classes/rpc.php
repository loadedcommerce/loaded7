<?php
/**
  @package    catalog::admin::templates
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: rpc.php v1.0 2013-08-08 datazen $
*/
global $lC_Vqmod;

require_once($lC_Vqmod->modCheck('templates/default/classes/general.php'));

class lC_General_Admin_rpc {
 /*
  * Returns the live search results
  *
  * @param string $_GET['q'] The search string
  * @access public
  * @return json
  */
  public static function search() {
    $result = array();
    if ($result = lC_General_Admin::find($_GET['q'])) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }
    
    echo json_encode($result);
  }
}
?>