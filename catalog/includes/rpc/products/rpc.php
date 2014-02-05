<?php
/**
  @package    catalog::products
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: rpc.php v1.0 2013-08-08 datazen $
*/ 
global $lC_Vqmod;
require_once($lC_Vqmod->modCheck('includes/classes/products.php'));
require_once($lC_Vqmod->modCheck('includes/classes/product.php'));

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
 /*
  * Returns the calculated product price
  *
  * @param integer $_GET['id']     The product id
  * @param integer $_GET['group']  The customer group
  * @param integer $_GET['qty']    The product quantity
  * @access public
  * @return json
  */
  public static function getPriceInfo() {  
    global $lC_Product;  

    if (!isset($lC_Product)) $lC_Product = new lC_Product($_GET['id']);
    
    $result = $lC_Product->getPriceInfo($_GET['id'], $_GET['group'], $_GET);
    if ($result !== false) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }  
}
?>