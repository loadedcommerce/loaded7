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
require_once($lC_Vqmod->modCheck('templates/default/classes/default.php'));
require_once($lC_Vqmod->modCheck('includes/classes/coupons.php'));

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
    $result = lC_Default::removeItem($_GET['item']);
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
    $result = lC_Default::getZonesDropdownHtml($_GET['country'], $_GET['zone']);
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
    if (lC_Default::setMediaType($_GET['type'], $_GET['size'])) {
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
    $result = array();
    if (lC_Coupons::addEntry($_GET['code'])) {
      $result['rpcStatus'] = '1';
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
    $result = array();
    if (lC_Coupons::removeEntry($_GET['code'])) {
      $result['rpcStatus'] = '1';
    }
    
    echo json_encode($result);
  }    
}
?>