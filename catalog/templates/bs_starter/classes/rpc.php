<?php
/**
  @package    catalog::templates::classes
  @author     Loaded Commerce, LLC
  @copyright  Copyright 2003-2013 Loaded Commerce Development Team
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on DevKit http://www.bootstraptor.com under GPL license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: rpc.php v1.0 2013-08-08 datazen $
*/
require_once($lC_Vqmod->modCheck('templates/default/classes/default.php'));
require_once($lC_Vqmod->modCheck('includes/classes/coupons.php'));

class lC_Bs_starter_rpc {
 /*
  * Returns the live search results
  *
  * @param string $_GET['q'] The search string
  * @access public
  * @return json
  */
  public static function search() {
    $result = lC_Bs_starter::find($_GET['q']);

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
    $result = lC_Bs_starter::removeItem($_GET['item']);
    if (is_array($result)) $result['rpcStatus'] = '1';
    
    echo json_encode($result);
  }  
 /*
  * Return the zones dropdown array
  *
  * @access public
  * @return json
  */
  public static function getZonesDropdown() {
    $result = array();
    $result = lC_Bs_starter::getZonesDropdownHtml($_GET['country'], $_GET['zone']);
    if (is_array($result)) $result['rpcStatus'] = '1';
    
    echo json_encode($result);
  }
 /*
  * Set the media type to session
  *
  * @access public
  * @return json
  */
  public static function setMediaType() {
    $result = array();
    if (lC_Bs_starter::setMediaType($_GET['type'], $_GET['size'])) {
      $result['rpcStatus'] = '1';
    }
    
    echo json_encode($result);
  }   
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