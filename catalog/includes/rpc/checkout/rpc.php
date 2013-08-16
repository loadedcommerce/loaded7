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
require_once($lC_Vqmod->modCheck('includes/classes/coupons.php'));

class lC_Checkout_rpc {
 /*
  * Add a coupon to the stack
  *
  * @access public
  * @return json
  */
  public static function addCoupon() {
    global $lC_Coupons;
    
    $result = array();
    if (defined('MODULE_SERVICES_INSTALLED') && in_array('coupons', explode(';', MODULE_SERVICES_INSTALLED)) && isset($lC_Coupons)) {
      $result = $lC_Coupons->addEntry($_GET['code']);
    }
    
    echo json_encode($result);
  } 
 /*
  * Remove a coupon to the stack
  *
  * @access public
  * @return json
  */
  public static function removeCoupon() {
    global $lC_Coupons;
    
    $result = array();
    if (defined('MODULE_SERVICES_INSTALLED') && in_array('coupons', explode(';', MODULE_SERVICES_INSTALLED)) && isset($lC_Coupons)) {
      if ($lC_Coupons->removeEntry($_GET['code'])) {
        $result['rpcStatus'] = '1';
      }
    }
    
    echo json_encode($result);
  }
}
?>