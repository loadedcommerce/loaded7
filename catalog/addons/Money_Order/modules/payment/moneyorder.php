<?php
/**  
  $Id: moneyorder.php v1.0 2013-01-01 datazen $

  Loaded Commerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     Loaded Commerce Team
  @copyright  (c) 2013 Loaded Commerce Team
  @license    http://loadedcommerce.com/license.html
*/
class lC_Payment_moneyorder extends lC_Payment {
 /**
  * The public title of the payment module
  *
  * @var string
  * @access protected
  */  
  protected $_title;
 /**
  * The class of the payment module
  *
  * @var string
  * @access protected
  */  
  protected $_code = 'moneyorder';
 /**
  * The status of the module
  *
  * @var boolean
  * @access protected
  */  
  protected $_status = false;
 /**
  * The sort order of the module
  *
  * @var integer
  * @access protected
  */  
  protected $_sort_order;   
 /**
  * The order id
  *
  * @var integer
  * @access protected
  */ 
  protected $_order_id;
 /**
  * Constructor
  */ 
  public function lC_Payment_moneyorder() {
    global $lC_Database, $lC_Language, $lC_ShoppingCart;
    
    $this->_title = $lC_Language->get('payment_moneyorder_title');
    $this->_method_title = $lC_Language->get('payment_moneyorder_method_title');
    $this->_status = (defined('ADDONS_PAYMENT_MONEY_ORDER_STATUS') && (ADDONS_PAYMENT_MONEY_ORDER_STATUS == '1') ? true : false);
    $this->_sort_order = (defined('ADDONS_PAYMENT_MONEY_ORDER_SORT_ORDER') ? ADDONS_PAYMENT_MONEY_ORDER_SORT_ORDER : null);    

    if ($this->_status === true) {
      if ((int)ADDONS_PAYMENT_MONEY_ORDER_ORDER_STATUS_ID > 0) {
        $this->order_status = ADDONS_PAYMENT_MONEY_ORDER_ORDER_STATUS_ID;
      }

      if ((int)ADDONS_PAYMENT_MONEY_ORDER_ZONE > 0) {
        $check_flag = false;

        $Qcheck = $lC_Database->query('select zone_id from :table_zones_to_geo_zones where geo_zone_id = :geo_zone_id and zone_country_id = :zone_country_id order by zone_id');
        $Qcheck->bindTable(':table_zones_to_geo_zones', TABLE_ZONES_TO_GEO_ZONES);
        $Qcheck->bindInt(':geo_zone_id', ADDONS_PAYMENT_MONEY_ORDER_ZONE);
        $Qcheck->bindInt(':zone_country_id', $lC_ShoppingCart->getBillingAddress('country_id'));
        $Qcheck->execute();

        while ($Qcheck->next()) {
          if ($Qcheck->valueInt('zone_id') < 1) {
            $check_flag = true;
            break;
          } elseif ($Qcheck->valueInt('zone_id') == $lC_ShoppingCart->getBillingAddress('zone_id')) {
            $check_flag = true;
            break;
          }
        }

        if ($check_flag == false) {
          $this->_status = false;
        }
      }
    }
  }
 /**
  * Return the payment selections array
  *
  * @access public
  * @return array
  */  
  public function selection() {
    return array('id' => $this->_code,
                 'module' => '<div class="paymentSelectionTitle">' . $this->_method_title . '</div>');
  }
 /**
  * Parse the response from the processor
  *
  * @access public
  * @return string
  */ 
  public function process() {
    global $lC_Language;
    
    $this->email_footer = sprintf($lC_Language->get('payment_moneyorder_email_footer'), ADDONS_PAYMENT_MONEY_ORDER_PAYTO, STORE_NAME_ADDRESS);
    $this->_order_id = lC_Order::insert();
    lC_Order::process($this->_order_id, $this->order_status);
  }
}
?>