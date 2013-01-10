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
// set the level of error reporting
error_reporting(E_ALL & ~E_NOTICE);
ini_set("display_errors", 1);

require_once('templates/default/classes/default.php');

class lC_Default_rpc {
 /*
  * Returns the live search results
  *
  * @param string $_GET['q'] The search string
  * @access public
  * @return json
  */
  public static function search() {
    $result = lC_Default::find($_GET['q']);

    echo $result;
  }
 /*
  * Delete item from shopping cart page
  *
  * @param string $_GET['q'] The search string
  * @access public
  * @return json
  */
  public static function deleteItem() {
    $result = array();
    
    $response = lC_Default::removeItem($_GET['item']);

    if ($response) $result['rpcStatus'] = '1';
    
    echo json_encode($result);
  }  
}
?>