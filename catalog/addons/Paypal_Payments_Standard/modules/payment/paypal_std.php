<?php
/**  
  $Id: cod.php v1.0 2013-01-01 datazen $

  Loaded Commerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     Loaded Commerce Team
  @copyright  (c) 2013 Loaded Commerce Team
  @license    http://loadedcommerce.com/license.html
*/
class lC_Payment_paypal_std extends lC_Payment {
 /**
  * The public title of the payment module
  *
  * @var string
  * @access protected
  */  
  protected $_title;
 /**
  * The code of the payment module
  *
  * @var string
  * @access protected
  */  
  protected $_code = 'paypal_std';
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
  * Constructor
  */ 
  public function lC_Payment_paypal_std() {
    global $lC_Database, $lC_Language, $lC_ShoppingCart;
    
    $this->_title = $lC_Language->get('payment_paypal_std_title');
    $this->_method_title = $lC_Language->get('payment_paypal_std_method_title');
    $this->_status = (defined('ADDONS_PAYMENT_PAYPAL_PAYMENTS_STANDARD_STATUS') && (ADDONS_PAYMENT_PAYPAL_PAYMENTS_STANDARD_STATUS == '1') ? true : false);
    $this->_sort_order = (defined('ADDONS_PAYMENT_PAYPAL_PAYMENTS_STANDARD_SORT_ORDER') ? ADDONS_PAYMENT_PAYPAL_PAYMENTS_STANDARD_SORT_ORDER : null);    

    if (defined('ADDONS_PAYMENT_PAYPAL_PAYMENTS_STANDARD_STATUS')) {
      $this->initialize();
     }
  }

  public function initialize() {
    global $lC_Database, $lC_Language, $order;

    if ((int)ADDONS_PAYMENT_PAYPAL_PAYMENTS_STANDARD_ORDER_STATUS_ID > 0) {
      $this->order_status = ADDONS_PAYMENT_PAYPAL_PAYMENTS_STANDARD_ORDER_STATUS_ID;
    } else {
      $this->order_status = 0;
    } 

    if (is_object($order)) $this->update_status();    
    if (defined('ADDONS_PAYMENT_PAYPAL_PAYMENTS_STANDARD_TEST_MODE') && ADDONS_PAYMENT_PAYPAL_PAYMENTS_STANDARD_TEST_MODE == '1') {
      $this->form_action_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';  // sandbox url
    } else {
      $this->form_action_url = 'https://www.paypal.com/cgi-bin/webscr';  // production url
    }    
  
  }
 /**
  * Disable module if zone selected does not match billing zone  
  *
  * @access public
  * @return void
  */  
  public function update_status() {
    global $lC_Database, $order;

    if ( ($this->_status === true) && ((int)ADDONS_PAYMENT_PAYPAL_PAYMENTS_STANDARD_ZONE > 0) ) {
      $check_flag = false;

      $Qcheck = $lC_Database->query('select zone_id from :table_zones_to_geo_zones where geo_zone_id = :geo_zone_id and zone_country_id = :zone_country_id order by zone_id');
      $Qcheck->bindTable(':table_zones_to_geo_zones', TABLE_ZONES_TO_GEO_ZONES);
      $Qcheck->bindInt(':geo_zone_id', ADDONS_PAYMENT_PAYPAL_PAYMENTS_STANDARD_ZONE);
      $Qcheck->bindInt(':zone_country_id', $order->billing['country']['id']);
      $Qcheck->execute();

      while ($Qcheck->next()) {
        if ($Qcheck->valueInt('zone_id') < 1) {
          $check_flag = true;
          break;
        } elseif ($Qcheck->valueInt('zone_id') == $order->billing['zone_id']) {
          $check_flag = true;
          break;
        }
      }

      if ($check_flag == false) {
        $this->_status = false;
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
    global $lC_Language;

    $selection = array('id' => $this->_code,
                       'module' => '<div class="payment-selection">' . $lC_Language->get('payment_paypal_std_method_title') . '<span style="margin-left:6px;">' . lc_image('addons/Paypal_Payments_Standard/images/paypal-cards.png', null, null, null, 'style="vertical-align:middle;"') . '</span></div><div class="payment-selection-title">' . $lC_Language->get('payment_paypal_std_method_blurb') . '</div>');    
    
    return $selection;
  }
  

   /**
  * Perform any pre-confirmation logic
  *
  * @access public
  * @return boolean
  */ 
  public function pre_confirmation_check() {
    return false;
  }
 /**
  * Perform any post-confirmation logic
  *
  * @access public
  * @return integer
  */ 
  public function confirmation() {
   return false;
  }

  /**
  * Return the confirmation button logic
  *
  * @access public
  * @return string
  */ 
  public function process_button() {

    if(isset($_SESSION['cartSync']))  {
      lC_Order::remove($_SESSION['cartSync']['orderID']);
      unset($_SESSION['cartSync']['paymentMethod']);
      unset($_SESSION['cartSync']['prepOrderID']);
      unset($_SESSION['cartSync']['orderCreated']);
      unset($_SESSION['cartSync']['orderID']);
    }

    $order_id = lC_Order::insert($this->order_status);    
    $_SESSION['cartSync']['paymentMethod'] = $this->_code;
    // store the cartID info to match up on the return - to prevent multiple order IDs being created
    $_SESSION['cartSync']['cartID'] = $_SESSION['cartID'];
    $_SESSION['cartSync']['prepOrderID'] = $_SESSION['prepOrderID'];     
    $_SESSION['cartSync']['orderCreated'] = TRUE;
    $_SESSION['cartSync']['orderID'] = $order_id;

    echo $this->_paypal_standard_params();        
    
    return false;
  }

   /**
  * Return the confirmation button logic
  *
  * @access public
  * @return string
  */ 
  private function _paypal_standard_params() {
    global $lC_Language, $lC_ShoppingCart, $lC_Currencies, $lC_Customer;  

    $upload         = 0;
    $no_shipping    = '1';
    $redirect_cmd   = '';
    $handling_cart  = '';
    $item_name      = '';
    $shipping       = '';

    // get the shipping amount
    $taxTotal       = 0;
    $shippingTotal  = 0;
    foreach ($lC_ShoppingCart->getOrderTotals() as $ot) {
      if ($ot['code'] == 'shipping') $shippingTotal = (float)$ot['value'];
      if ($ot['code'] == 'tax') $taxTotal = (float)$ot['value'];
    } 

    $shoppingcart_products = $lC_ShoppingCart->getProducts();  


    $amount = $lC_Currencies->formatRaw($lC_ShoppingCart->getSubTotal(), $lC_Currencies->getCode());

    $discount_amount_cart = 0;
    foreach ($lC_ShoppingCart->getOrderTotals() as $module) {  
      if($module['code'] == 'coupon') {
        $discount_amount_cart = $module['value'];          
      }
    }


    if(ADDONS_PAYMENT_PAYPAL_PAYMENTS_STANDARD_METHOD == 'Itemized') {

      $paypal_action_params = array(
        'upload' => sizeof($shoppingcart_products),
        'redirect_cmd' => '_cart',
        'handling_cart' => $shippingTotal,
        'discount_amount' => $discount_amount_cart
        );
       for ($i=1; $i<=sizeof($shoppingcart_products); $i++) {
          $paypal_shoppingcart_params = array(
            'item_name_'.$i => $shoppingcart_products[$i]['name'],
            'item_number_'.$i => $shoppingcart_products[$i]['item_id'],
            'quantity_'.$i => $shoppingcart_products[$i]['quantity'],
            'amount_'.$i => $lC_Currencies->formatRaw($shoppingcart_products[$i]['price'], $lC_Currencies->getCode()),
            'tax_'.$i => $shoppingcart_products[$i]['tax_class_id']            
            ); 
                   
        //Customer Specified Product Options: PayPal Max = 2
        if($shoppingcart_products[$i]['variants']) {
          for ($j=0, $n=sizeof($shoppingcart_products[$i]['variants']); $j<2; $j++) {
            $paypal_shoppingcart_variants_params = array(
                'on'.$j.'_'.$i => $shoppingcart_products[$i]['variants'][$j]['group_title'],
                'os'.$j.'_'.$i => $shoppingcart_products[$i]['variants'][$j]['value_title']          
                ); 
            $paypal_shoppingcart_params =  array_merge($paypal_shoppingcart_params,$paypal_shoppingcart_variants_params);
          }
        }
        $paypal_action_params = array_merge($paypal_action_params,$paypal_shoppingcart_params);
      }
    } else {
      $item_number = '';
      for ($i=1; $i<=sizeof($shoppingcart_products); $i++) {
        $item_number .= ' '.$shoppingcart_products[$i]['name'].' ,';
      }
      $item_number = substr_replace($item_number,'',-2);
      $paypal_action_params = array(
        'item_name' => STORE_NAME,
        'redirect_cmd' => '_xclick',
        'amount' => $amount,
        'shipping' => $shippingTotal,
        'discount_amount' => $discount_amount_cart,
        'item_number' => $item_number
        ); 
    }

    $order_id = (isset($_SESSION['prepOrderID']) && $_SESSION['prepOrderID'] != NULL) ? end(explode('-', $_SESSION['prepOrderID'])) : 0;
    if ($order_id == 0) $order_id = (isset($_SESSION['cartSync']['orderID']) && $_SESSION['cartSync']['orderID'] != NULL) ? $_SESSION['cartSync']['orderID'] : 0;  
    
    $return_href_link = lc_href_link(FILENAME_CHECKOUT, 'process', 'AUTO', true, true, true);
    $cancel_href_link = lc_href_link(FILENAME_CHECKOUT, 'cart', 'AUTO', true, true, true);
    $notify_href_link = lc_href_link('addons/Paypal_Payments_Standard/ipn.php', 'ipn_order_id=' . $order_id, 'AUTO', true, true, true);
    $signature = $this->setTransactionID($amount);

    $paypal_standard_params = array(
        'cmd' => '_ext-enter', 
        'bn' => 'LoadedCommerce_Cart',
        'business' => ADDONS_PAYMENT_PAYPAL_PAYMENTS_STANDARD_BUSINESS_ID,       
        'currency_code' => $_SESSION['currency'],
        'return' => $return_href_link,
        'cancel_return' => $cancel_href_link,
        'notify_url' => $notify_href_link,
        'no_shipping' => $no_shipping,
        'rm' => ADDONS_PAYMENT_PAYPAL_PAYMENTS_STANDARD_RM,
        'custom' => $signature,
        'email' => $lC_Customer->getEmailAddress(),
        'first_name' => $lC_ShoppingCart->getBillingAddress('firstname'),
        'last_name' => $lC_ShoppingCart->getBillingAddress('lastname'),
        'address1' => $lC_ShoppingCart->getBillingAddress('street_address'),
        'address2' => '',
        'city' => $lC_ShoppingCart->getBillingAddress('city'), 
        'state' => $lC_ShoppingCart->getBillingAddress('state'), 
        'zip' => $lC_ShoppingCart->getBillingAddress('postcode'),
        'lc' => $lC_ShoppingCart->getBillingAddress('country_iso_code_3'),
        'no_note' => (ADDONS_PAYMENT_PAYPAL_PAYMENTS_STANDARD_NO_NOTE == 'Yes') ? '0': '1',    
        'form' => 'mage');   
  
    $paypal_standard_action_params =  array_merge($paypal_standard_params,$paypal_action_params); 
    $paypal_params = '';
    foreach($paypal_standard_action_params as $name => $value) {
      $paypal_params .= lc_draw_hidden_field($name, $value);
    }
    return $paypal_params;    
  }
 /**
  * Parse the response from the processor
  *
  * @access public
  * @return string
  */ 
  public function process() { 
    // performed by ipn.php
  }

  public function setTransactionID($amount) {
    global $lC_Language, $lC_ShoppingCart, $lC_Currencies, $lC_Customer;
    $my_currency = $lC_Currencies->getCode();
    $trans_id = STORE_NAME . date('Ymdhis');
    $digest = md5($trans_id . number_format($amount * $lC_Currencies->value($my_currency), $lC_Currencies->decimalPlaces($my_currency), '.', '') . ADDONS_PAYMENT_PAYPAL_PAYMENTS_STANDARD_IPN_DIGEST_KEY);
    return $digest;
  }
}
?>