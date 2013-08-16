<?php
/**
  @package    catalog::core
  @author     Loaded Commerce, LLC
  @copyright  Copyright 2003-2013 Loaded Commerce Development Team
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: rpc.php v1.0 2013-08-08 datazen $
*/
global $lC_Vqmod;
require_once($lC_Vqmod->modCheck('includes/classes/products.php'));

class lC_Products_rpc {
 /*
  * Returns the datatable data for listings
  *
  * @access public
  * @return json
  */
  public static function getAll() {  
    global $_module;

    $result = lC_Products::getAll($_GET[$_module]);
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  }
}
?>