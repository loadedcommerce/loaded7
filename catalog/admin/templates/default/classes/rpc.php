<?php
/*
  $Id: rpc.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
 
  @method The lC_Default_rpc class is for AJAX remote program control
*/
require_once('templates/default/classes/general.php');

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