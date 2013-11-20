<?php
/**
  @package    catalog::classes
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: coupons.php v1.0 2013-08-08 datazen $
*/
class lC_Coupons {
  public $is_enabled = false;

  private $_contents = array();
  
  // class constructor
  public function __construct() {
    $this->is_enabled = (defined('MODULE_SERVICES_INSTALLED') && in_array('coupons', explode(';', MODULE_SERVICES_INSTALLED))) ? true : false;
    
    if ($this->is_enabled) {
      if ( !isset($_SESSION['lC_Coupons_data']) ) {
        $_SESSION['lC_Coupons_data'] = array('contents' => array());
      }

      $this->_contents =& $_SESSION['lC_Coupons_data']['contents'];     
    }
  }
  
  // public methods
  public function addEntry($code) {
    global $lC_Coupons, $lC_ShoppingCart, $lC_OrderTotal;
    
    $cInfo = $lC_Coupons->_getData($code);
         
    if (is_array($cInfo) && empty($cInfo) === false) {
      
      $valid = $lC_Coupons->_isValid($cInfo);    
      
      if ($valid['status'] === true) {

        $name = $cInfo['name'];
        $discount = $this->_calculate($cInfo);

        $this->_contents[$code] = array('title' => $name . ' (' . $code . ')',
                                        'total' => $discount); 

        $lC_ShoppingCart->refresh(true);
        $this->_refreshCouponOrderTotals();

        return array('rpcStatus' => 1);                                              
      } else {
        // coupon not valid
        return $valid;
      }
    
    } else {
      // coupon not found
      return array('rpcStatus' => -2);
    }   
          
  }
  
  public function removeEntry($code) {
    global $lC_ShoppingCart;
    
    if (array_key_exists($code, $this->_contents)) {    
      unset($this->_contents[$code]);
      $lC_ShoppingCart->refresh(true);
      $this->_refreshCouponOrderTotals();

    }    

    return true;
  }
  
  public function getTotalDiscount() {
    global $lC_ShoppingCart;
    
    $dTotal = 0;
    foreach ($this->_contents as $key => $module) {
      $dTotal += (float)$module['total'];
    }    

    return $dTotal;
  }
  
  public function reset() {
    $this->_contents = array();
  }
  
  public function getAll() {
    return $this->_contents;
  }
  
  public function redeem($code, $order_id) {
    global $lC_Database, $lC_Customer;
    
    if ($code == null) return false;
    
    $cInfo = $this->_getData($code);  
    
    if (isset($cInfo['coupons_id']) && empty($cInfo['coupons_id']) === false) {
    
      $Qchk = $lC_Database->query('select id from :table_coupons_redeemed where coupons_id = :coupons_id and order_id = :order_id limit 1)');
      $Qchk->bindTable(':table_coupons_redeemed', TABLE_COUPONS_REDEEMED);
      $Qchk->bindInt(':coupons_id', $cInfo['coupons_id']);
      $Qchk->bindInt(':order_id', $order_id);
      $Qchk->execute();
      
      if ($Qchk->numberOfRows() > 0) {
        $Qredeemed = $lC_Database->query('update :table_coupons_redeemed set coupons_id = :coupons_id, customers_id = :customers_id, redeem_date = now(), redeem_ip = :redeem_ip, order_id = :order_id where coupons_id = :coupons_id and order_id = :order_id ');
      } else {
        $Qredeemed = $lC_Database->query('insert into :table_coupons_redeemed (coupons_id, customers_id, redeem_date, redeem_ip, order_id) values (:coupons_id, :customers_id, now(), :redeem_ip, :order_id)');
      }
      
      $Qredeemed->bindTable(':table_coupons_redeemed', TABLE_COUPONS_REDEEMED);
      $Qredeemed->bindInt(':coupons_id', $cInfo['coupons_id']);
      $Qredeemed->bindInt(':customers_id', $lC_Customer->getID());
      $Qredeemed->bindValue(':redeem_ip', lc_get_ip_address());
      $Qredeemed->bindInt(':order_id', $order_id);
      $Qredeemed->execute();

      $Qchk->freeResult();
      
      return ( $Qredeemed->affectedRows() === 1 );    
    }
    
    return false;
  }
  
  public function hasContents() {
    return !empty($this->_contents);
  }  
  
  // private methods  
  private function _getData($code, $status = 1) {
    global $lC_Database, $lC_Language;

    $Qcoupons = $lC_Database->query('select * from :table_coupons c left join :table_coupons_description cd on (c.coupons_id = cd.coupons_id) where c.code = :code and c.status = :status and cd.language_id = :language_id limit 1');
    $Qcoupons->bindTable(':table_coupons', TABLE_COUPONS);
    $Qcoupons->bindTable(':table_coupons_description', TABLE_COUPONS_DESCRIPTION);
    $Qcoupons->bindValue(':code', $code);
    $Qcoupons->bindInt(':status', $status);
    $Qcoupons->bindInt(':language_id', $lC_Language->getID());
    $Qcoupons->execute();   
    
    return (is_array($Qcoupons->toArray())) ? $Qcoupons->toArray() : false;     
  } 
  
  private function _isValid($cInfo) {
    global $lC_ShoppingCart, $lC_Customer, $lC_Currencies;
    
    $valid = array('status' => true, 'rpcStatus' => 1, 'msg' => '');
    
    // check status
    if (isset($cInfo['status']) && $cInfo['status'] != '1') $valid = array('status' => false, 'rpcStatus' => -3);
    
    // check purchase over
    $total = (float)$lC_ShoppingCart->getTotal();
    if (isset($cInfo['purchase_over']) && (float)$cInfo['purchase_over'] > $total) $valid = array('status' => false, 'rpcStatus' => -4, 'msg' => $lC_Currencies->format(number_format($cInfo['purchase_over'], DECIMAL_PLACES)));
    
    // check start/end dates
    $today = lC_DateTime::getShort(lC_DateTime::getNow());
    $start = (isset($cInfo['start_date']) && $cInfo['start_date'] != NULL) ? lC_DateTime::getShort($cInfo['start_date']) : NULL;
    $expires = (isset($cInfo['expires_date']) && $cInfo['expires_date'] != NULL) ? lC_DateTime::getShort($cInfo['expires_date']) : NULL;

    if ($start != NULL) {
      if($start <= $today) {
      } else {
        $valid = array('status' => false, 'rpcStatus' => -5, 'msg' => $start);
      }
    }
    
    if ($expires != NULL) {
      if($today <= $expires) {
      } else {
        $valid = array('status' => false, 'rpcStatus' => -6);
      }   
    }
    
    // check uses per coupon and uses per customer
    $uses = $this->_getUses($cInfo['coupons_id']);
    if ((int)$cInfo['uses_per_coupon'] > 0 && (int)$cInfo['uses_per_coupon'] <= (int)$uses['per_coupon']) $valid = array('status' => false, 'rpcStatus' => -7, 'msg' => $cInfo['uses_per_coupon']); 
    if ((int)$cInfo['uses_per_customer'] > 0 && (int)$cInfo['uses_per_customer'] <= (int)$uses['per_customer']) $valid = array('status' => false, 'rpcStatus' => -8, 'msg' => $cInfo['uses_per_customer']); 
    
    return $valid;
  } 
  
  private function _getUses($id) {
    global $lC_Database, $lC_Customer;
    
    $uses = array();
    
    $Qcust = $lC_Database->query('select count(*) as total from :table_coupons_redeemed where coupons_id = :coupons_id and customers_id = :customers_id');
    $Qcust->bindTable(':table_coupons_redeemed', TABLE_COUPONS_REDEEMED);
    $Qcust->bindInt(':coupons_id', $id);
    $Qcust->bindInt(':customers_id', $lC_Customer->getID());
    $Qcust->execute();
    
    $uses['per_customer'] = $Qcust->valueInt('total'); 
    
    $Qcust->freeResult();   
    
    $Qcoupon = $lC_Database->query('select count(*) as total from :table_coupons_redeemed where coupons_id = :coupons_id');
    $Qcoupon->bindTable(':table_coupons_redeemed', 'lc_coupons_redeemed');
    $Qcoupon->bindInt(':coupons_id', $id);
    $Qcoupon->execute();
    
    $uses['per_coupon'] = $Qcoupon->valueInt('total'); 
    
    $Qcoupon->freeResult();  
    
    return $uses;  
  }  
  
  private function _calculate($cInfo) {
    global $lC_ShoppingCart;
    
    
    
    switch ($cInfo['type']) {
      case 'T' : // percen(T) discount
        $total = (isset($lC_ShoppingCart)) ? (float)$lC_ShoppingCart->getSubTotal() : 0.00;
        $discount = ( ((float)$cInfo['reward'] * .01) * $total ); 
        break;
        
      case 'S' : // free shipping
        $discount = $lC_ShoppingCart->getShippingCost();
        break;
        
      case 'P' : // free product
        $discount = 0;
        break;
        
      default : // (R)egular numeric discount 
        $discount = (float)$cInfo['reward'];
    }
    
    return ($discount > 0) ? number_format(round($discount, DECIMAL_PLACES), DECIMAL_PLACES) : 0.00;
  }  
   
  private function _refreshCouponOrderTotals() {
    global $lC_Coupons, $lC_ShoppingCart, $lC_Currencies;
    
    // remove coupon OT entries
    foreach ($lC_ShoppingCart->getOrderTotals() as $key => $module) {
      if ($module['code'] == 'coupon') {
        if (is_array($_SESSION['lC_ShoppingCart_data']['order_totals'][$key])) unset( $_SESSION['lC_ShoppingCart_data']['order_totals'][$key]);
      }
    }

    // add back the entries
    foreach ($lC_Coupons->getAll() as $code => $val) {
      if ($val['total'] > 0) {
        $_SESSION['lC_ShoppingCart_data']['order_totals'][] = array('code' => 'coupon',
                                                                    'title' => $val['title'],
                                                                    'text' => '<span onclick="removeCoupon(\'' . $code . '\');" style="padding:0; cursor:pointer;">' . lc_image(DIR_WS_CATALOG . 'templates/default/images/icons/16/cross_round.png', null, null, null, 'style="vertical-align:middle;"') . '&nbsp;-' . $lC_Currencies->format($val['total']) . '</span>',
                                                                    'value' => $val['total'],
                                                                    'sort_order' => (int)MODULE_ORDER_TOTAL_COUPON_SORT_ORDER);
      }
    } 
    
    // sort the array
    $i = 0;
    $otArr = array();
    foreach ($lC_ShoppingCart->getOrderTotals() as $key => $module) {
      if ($module['code'] == 'coupon') {
        $sort = (int)MODULE_ORDER_TOTAL_COUPON_SORT_ORDER + $i;
        $i++;
      } else {
        $sort = $module['sort_order'];
      }
      $otArr[$sort] = $module;
    } 
    ksort($otArr); 
    
    $_SESSION['lC_ShoppingCart_data']['order_totals'] = $otArr;   
  }
}
?>