<?php
/**
  @package    catalog::search
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: rpc.php v1.0 2013-08-08 datazen $
*/ 
class lC_Search_rpc {
 /*
  * Returns the datatable data for listings
  *
  * @access public
  * @return json
  */
  public static function getAll() {  
    global $_module;

    $result = lC_Search::getAll($_GET[$_module]);
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  }
}
?>