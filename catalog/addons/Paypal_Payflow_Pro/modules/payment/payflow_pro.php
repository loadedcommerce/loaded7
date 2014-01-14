<?php
/**  
  $Id: payflow_pro.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     Loaded Commerce Team
  @copyright  (c) 2013 Loaded Commerce Team
  @license    http://loadedcommerce.com/license.html
*/
require_once(DIR_FS_CATALOG . 'includes/classes/transport.php'); 

class lC_Payment_payflow_pro extends lC_Payment {     
 /**
  * The public title of the payment module (admin)
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
  protected $_code = 'payflow_pro';
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
  * The completed order status ID
  *
  * @var integer
  * @access protected
  */   
  protected $_order_status_complete;  
 /**
  * The Express Checkout Redirect URL
  *
  * @var string
  * @access protected
  */  
  public $_ec_redirect_url;   
  
 /**
  * Constructor
  */      
  public function lC_Payment_payflow_pro() {
    global $lC_Language;

    $this->_title = $lC_Language->get('payment_payflow_pro_title'); // admin listing title
    $this->_method_title = $lC_Language->get('payment_payflow_pro_method_title'); // public sidebar title 
    $this->_status = (defined('ADDONS_PAYMENT_PAYPAL_PAYFLOW_PRO_STATUS') && (ADDONS_PAYMENT_PAYPAL_PAYFLOW_PRO_STATUS == '1') ? true : false);
    $this->_sort_order = (defined('ADDONS_PAYMENT_PAYPAL_PAYFLOW_PRO_SORT_ORDER') ? ADDONS_PAYMENT_PAYPAL_PAYFLOW_PRO_SORT_ORDER : null);

    if (defined('ADDONS_PAYMENT_PAYPAL_PAYFLOW_PRO_STATUS')) {
      $this->initialize();
    }
  }
 /**
  * Initialize the payment module 
  *
  * @access public
  * @return void
  */
  public function initialize() {
    global $lC_Database, $lC_Language, $order;

    if ((int)ADDONS_PAYMENT_PAYPAL_PAYFLOW_PRO_ORDER_STATUS_ID > 0) {
      $this->order_status = ADDONS_PAYMENT_PAYPAL_PAYFLOW_PRO_ORDER_STATUS_ID;
    }
    
    if ((int)ADDONS_PAYMENT_PAYPAL_PAYFLOW_PRO_ORDER_STATUS_COMPLETE_ID > 0) {
      $this->_order_status_complete = ADDONS_PAYMENT_PAYPAL_PAYFLOW_PRO_ORDER_STATUS_COMPLETE_ID;
    }    

    if (is_object($order)) $this->update_status();
    
    if (defined('ADDONS_PAYMENT_PAYPAL_PAYFLOW_PRO_TEST_MODE') && ADDONS_PAYMENT_PAYPAL_PAYFLOW_PRO_TEST_MODE == '1') {
      $this->action_url = 'https://pilot-payflowpro.paypal.com';  // sandbox url
    } else {
      $this->action_url = 'https://payflowpro.paypal.com';  // production url
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

    if ( ($this->_status === true) && ((int)ADDONS_PAYMENT_PAYPAL_PAYFLOW_PRO_ZONE > 0) ) {
      $check_flag = false;

      $Qcheck = $lC_Database->query('select zone_id from :table_zones_to_geo_zones where geo_zone_id = :geo_zone_id and zone_country_id = :zone_country_id order by zone_id');
      $Qcheck->bindTable(':table_zones_to_geo_zones', TABLE_ZONES_TO_GEO_ZONES);
      $Qcheck->bindInt(':geo_zone_id', ADDONS_PAYMENT_PAYPAL_PAYFLOW_PRO_ZONE);
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
    global $lC_Language, $lC_ShoppingCart, $lC_Database;
    $selection = array();

    $card_accepted_types = ADDONS_PAYMENT_PAYPAL_PAYFLOW_PRO_ACCEPTED_CARDS;
    $Qcc = $lC_Database->query('select id, credit_card_name from :table_credit_cards where credit_card_status = :credit_card_status');      
    if(!empty($card_accepted_types)) {
      $Qcc->appendQuery('and id IN( :id )');
      $Qcc->bindRaw(':id', $card_accepted_types);
    }
    $Qcc->appendQuery('order by sort_order, credit_card_name');
    $Qcc->bindTable(':table_credit_cards', TABLE_CREDIT_CARDS);
    $Qcc->bindInt(':credit_card_status', 1);      
    $Qcc->execute();

    while ($Qcc->next()) {
      $cc_array[] = array('id' => $Qcc->value('credit_card_name'),
                          'text' => $Qcc->value('credit_card_name'));
    }

    for ($i=1; $i < 13; $i++) {
      $expires_month[] = array('id' => sprintf('%02d', $i), 'text' => strftime('%B',mktime(0,0,0,$i,1,2000)));
    }
    $today = getdate();
    for ($i=$today['year']; $i < $today['year']+15; $i++) {
      $expires_year[] = array('id' => strftime('%Y',mktime(0,0,0,1,1,$i)), 'text' => strftime('%Y',mktime(0,0,0,1,1,$i)));
    }     

    $selection = array('id' => $this->_code,
                       'module' => '<div class="payment-selection">' . $lC_Language->get('payment_payflow_pro_method_title') . '</div><div class="payment-selection-title">' . $lC_Language->get('payment_payflow_pro_method_blurb') . '</div>',
                       'fields' => array(
                                      array('title' => $lC_Language->get('payment_payflow_pro_cc_firstname'),
                                            'field' => lc_draw_input_field('payflow_pro_cc_firstname', $lC_ShoppingCart->getBillingAddress('firstname'), ' class="form-control"')),
                                      array('title' => $lC_Language->get('payment_payflow_pro_cc_lastname'),
                                            'field' => lc_draw_input_field('payflow_pro_cc_lastname', $lC_ShoppingCart->getBillingAddress('lastname'), ' class="form-control"')),
                                      array('title' => $lC_Language->get('payment_payflow_pro_cc_type'),
                                            'field' => lc_draw_pull_down_menu('payflow_pro_cc_type', $cc_array, ' class="form-control"')),
                                      array('title' => $lC_Language->get('payment_payflow_pro_cc_number'),
                                            'field' => lc_draw_input_field('payflow_pro_cc_number', NULL, ' class="form-control"')),
                                      array('title' => $lC_Language->get('payment_payflow_pro_cc_expiry'),
                                            'field' => lc_draw_pull_down_menu('payflow_pro_cc_expires_month', $expires_month, ' class="form-control"') . '&nbsp;' . lc_draw_pull_down_menu('payflow_pro_cc_expires_year', $expires_year, ' class="form-control"')),
                                      array('title' => $lC_Language->get('payment_payflow_pro_cc_cvv').' '.$lC_Language->get('payment_payflow_pro_cc_cvv_text'),
                                            'field' => lc_draw_input_field('payflow_pro_cc_cvv', NULL, ' class="form-control" SIZE="4" MAXLENGTH="4"'))
                                   )
                      );

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
  * Return the confirmation button logic
  *
  * @access public
  * @return string
  */ 
  public function process_button() { 
    global $lC_MessageStack;

    $payflow_pro_cc_firstname  = $_POST['payflow_pro_cc_firstname'];
    $payflow_pro_cc_lastname   = $_POST['payflow_pro_cc_lastname'];
    $payflow_pro_cc_type       = $_POST['payflow_pro_cc_type'];
    $payflow_pro_cc_number     = $_POST['payflow_pro_cc_number'];
    $payflow_pro_cc_expiry     = $_POST['payflow_pro_cc_expires_month'].substr($_POST['payflow_pro_cc_expires_year'],0,2);
    $payflow_pro_cc_cvv        = $_POST['payflow_pro_cc_cvv'];

    echo lc_draw_hidden_field('payflow_pro_cc_firstname', $payflow_pro_cc_firstname);
    echo lc_draw_hidden_field('payflow_pro_cc_lastname', $payflow_pro_cc_lastname);
    echo lc_draw_hidden_field('payflow_pro_cc_type', $payflow_pro_cc_type);
    echo lc_draw_hidden_field('payflow_pro_cc_number', $payflow_pro_cc_number);
    echo lc_draw_hidden_field('payflow_pro_cc_expiry', $payflow_pro_cc_expiry);
    echo lc_draw_hidden_field('payflow_pro_cc_cvv', $payflow_pro_cc_cvv);
    
    return $process_button_string;
  }
 /**
  * Parse the response from the processor
  *
  * @access public
  * @return string
  */ 
  public function process() {
    global $lC_Language, $lC_Database, $lC_MessageStack, $lC_ShoppingCart;

    // insert order into DB
    $this->_order_id = lC_Order::insert($this->order_status);

    $response = $this->DoDirectPayment();
    $result = (isset($response['RESULT']) && $response['RESULT'] != NULL) ? $response['RESULT'] : NULL;

    $error = false;
    switch ($result) {
      case '0' :
        // update order status
        lC_Order::process($this->_order_id, $this->_order_status_complete);
        break;        
      default :
        if ($result == NULL) { // customer clicked cancel
          lc_redirect(lc_href_link(FILENAME_CHECKOUT, null, 'SSL'));
        } else { // an error occurred
          $errmsg = sprintf($lC_Language->get('error_payment_problem'), '(' . $result . ') ' . $response['RESPMSG']);
          $error = true;
        }
    }   
    // insert into transaction history
    $this->_transaction_response = $response;    
    $response_array['root']['transaction_response'] = trim($this->_transaction_response); 
    
    $lC_XML = new lC_XML($response_array);    
    $Qtransaction = $lC_Database->query('insert into :table_orders_transactions_history (orders_id, transaction_code, transaction_return_value, transaction_return_status, date_added) values (:orders_id, :transaction_code, :transaction_return_value, :transaction_return_status, now())');
    $Qtransaction->bindTable(':table_orders_transactions_history', TABLE_ORDERS_TRANSACTIONS_HISTORY);
    $Qtransaction->bindInt(':orders_id', $this->_order_id);
    $Qtransaction->bindInt(':transaction_code', 1);
    $Qtransaction->bindValue(':transaction_return_value', $lC_XML->toXML());
    $Qtransaction->bindInt(':transaction_return_status', (strtoupper(trim($this->_transaction_response)) == '0') ? 1 : 0);
    $Qtransaction->execute();
                        
    // unset the ppec sesssion
    if (isset($_SESSION['PPEC_PROCESS'])) unset($_SESSION['PPEC_PROCESS']);
    if (isset($_SESSION['PPEC_TOKEN'])) unset($_SESSION['PPEC_TOKEN']);
    if (isset($_SESSION['SKIP_PAYMENT_PAGE'] )) unset($_SESSION['SKIP_PAYMENT_PAGE']);
    
    if ($error) lc_redirect(lc_href_link(FILENAME_CHECKOUT, 'payment&payment_error=' . $errmsg, 'SSL'));
  } 
 /**
  * Check the status of the payment module
  *
  * @access public
  * @return boolean
  */ 
  public function check() {
    if (!isset($this->_check)) {
      $this->_check = defined('ADDONS_PAYMENT_PAYPAL_PAYFLOW_PRO_STATUS');
    }

    return $this->_check;
  } 
  /**
  * Initialize the transaciton
  *
  * @access public
  * @return string
  */ 
  public function DoDirectPayment() {
    global $lC_MessageStack;

    $response = $this->_DoDirectPayment();
    if (!$response) {
      if ($lC_MessageStack->size('shopping_cart') > 0) {
        $_SESSION['messageToStack'] = $lC_MessageStack->getAll();
      } else {
        $_SESSION['messageToStack'] = array('shopping_cart', array('text' => 'An unknown error has occurred', 'type' => 'error'));
      }
      return false;
    }
    return $response;
  }
  /**
  * Do the DoDirectPayment API call
  *
  * @access private
  * @return string
  */  
  private function _DoDirectPayment() {
    global $lC_ShoppingCart, $lC_Currencies, $lC_Language, $lC_MessageStack, $lC_Customer;
    $lC_Language->load('modules-payment');
    $action_url = $this->action_url;

    $payflow_pro_cc_firstname  = $_POST['payflow_pro_cc_firstname'];
    $payflow_pro_cc_lastname   = $_POST['payflow_pro_cc_lastname'];
    $payflow_pro_cc_type       = $_POST['payflow_pro_cc_type'];
    $payflow_pro_cc_number     = $_POST['payflow_pro_cc_number'];    
    $payflow_pro_cc_expiry     = $_POST['payflow_pro_cc_expiry'];
    $payflow_pro_cc_cvv        = $_POST['payflow_pro_cc_cvv'];
    $comments = '';
    $orderdesc = '';

// get the shipping amount
    $taxTotal = 0;
    $shippingTotal = 0;
    foreach ($lC_ShoppingCart->getOrderTotals() as $ot) {
      if ($ot['code'] == 'shipping') $shippingTotal = (float)$ot['value'];
      if ($ot['code'] == 'tax') $taxTotal = (float)$ot['value'];
    } 

    $transType = (defined('ADDONS_PAYMENT_PAYPAL_PAYFLOW_PRO_TRXTYPE') && ADDONS_PAYMENT_PAYPAL_PAYFLOW_PRO_TRXTYPE == 'Authorization') ? 'A' : 'S';
    $postData = $this->_getUserParams() .
                "&TENDER=C" . 
                "&TRXTYPE=" . $transType .                 
                "&ACCT=" . $payflow_pro_cc_number .
                "&CVV2=" . $payflow_pro_cc_cvv . 
                "&EXPDATE=" . $payflow_pro_cc_expiry .
                "&FREIGHTAMT=" . $shippingTotal .
                "&TAXAMT=" . $lC_Currencies->formatRaw($taxTotal, $lC_Currencies->getCode()) .
                "&AMT=" . $lC_Currencies->formatRaw($lC_ShoppingCart->getTotal(), $lC_Currencies->getCode()) . 
                "&CURRENCY=" . $_SESSION['currency'] .
                "&FIRSTNAME=" . $payflow_pro_cc_firstname . 
                "&LASTNAME=" . $payflow_pro_cc_lastname . 
                "&STREET=" . $lC_ShoppingCart->getBillingAddress('street_address') . 
                "&CITY=" . $lC_ShoppingCart->getBillingAddress('city') . 
                "&STATE=" . $lC_ShoppingCart->getBillingAddress('zone_code') .
                "&ZIP=" . $lC_ShoppingCart->getBillingAddress('postcode') .
                "&COUNTRY=" . $lC_ShoppingCart->getBillingAddress('country_iso_code_2') . 
                "&SHIPTOFIRSTNAME=" . $lC_ShoppingCart->getShippingAddress('firstname') . 
                "&SHIPTOLASTNAME=" . $lC_ShoppingCart->getShippingAddress('lastname') . 
                "&SHIPTOSTREET=" . $lC_ShoppingCart->getShippingAddress('street_address') . 
                "&SHIPTOCITY=" . $lC_ShoppingCart->getBillingAddress('city') . 
                "&SHIPTOSTATE=" . $lC_ShoppingCart->getBillingAddress('zone_code') . 
                "&SHIPTOZIP=" . $lC_ShoppingCart->getShippingAddress('postcode') . 
                "&SHIPTOCOUNTRY=" . $lC_ShoppingCart->getShippingAddress('country_iso_code_2') . 
                "&SHIPTOPHONENUM=" . $lC_Customer->getTelephone() . 
                "&SHIPTOEMAIL=" . $lC_Customer->getEmailAddress() . 
                "&CUSTIP=" . lc_get_ip_address() .  
                "&COMMENT1=" . $comments . 
                "&INVNUM=" . $this->_order_id . 
                "&ORDERDESC=" . $orderdesc . 
                "&VERBOSITY=MEDIUM";

    $response = transport::getResponse(array('url' => $action_url, 'method' => 'post', 'parameters' => $postData));   

    if (!$response) { // server failure error
      $lC_MessageStack->add('shopping_cart', $lC_Language->get('payment_payflow_pro_error_server'), 'error');
      return false;
    }

    @parse_str($response, $dataArr);
    
    if ($dataArr['RESULT'] != 0) { // server failure error
      $errmsg = sprintf($lC_Language->get('payment_payflow_pro_error_occurred'), '(' . $dataArr['RESULT'] . ') ' . $dataArr['RESPMSG']);
      lc_redirect(lc_href_link(FILENAME_CHECKOUT, 'payment&payment_error=' . $errmsg, 'SSL'));
    }    
    
    return $dataArr;    
  }
 /**
  * Set the API authenticaiton parameters
  *
  * @access private
  * @return string
  */
  private function _getUserParams() {
    return "USER=" . ADDONS_PAYMENT_PAYPAL_PAYFLOW_PRO_USER .
           "&VENDOR=" . ADDONS_PAYMENT_PAYPAL_PAYFLOW_PRO_MERCH .
           "&PARTNER=" . ADDONS_PAYMENT_PAYPAL_PAYFLOW_PRO_PARTNER .
           "&PWD=" . ADDONS_PAYMENT_PAYPAL_PAYFLOW_PRO_PASSWORD;
  } 
}
?>