<?php
/**  
  $Id: paypal_pro.php v1.0 2013-01-01 gulsarrays $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     Loaded Commerce Team
  @copyright  (c) 2013 Loaded Commerce Team
  @license    http://loadedcommerce.com/license.html
*/
require_once(DIR_FS_CATALOG . 'includes/classes/transport.php'); 

class lC_Payment_paypal_pro extends lC_Payment {     
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
  protected $_code = 'paypal_pro';
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
  * @access public
  */  
  public $_ec_redirect_url;   
 /**
  * Constructor
  */      
  public function lC_Payment_paypal_pro() {
    global $lC_Language;

    $this->_title = $lC_Language->get('payment_paypal_pro_title'); // admin listing title
    $this->_method_title = $lC_Language->get('payment_paypal_pro_method_title'); // public sidebar title 
    $this->_status = (defined('ADDONS_PAYMENT_PAYPAL_PAYMENTS_PRO_STATUS') && (ADDONS_PAYMENT_PAYPAL_PAYMENTS_PRO_STATUS == '1') ? true : false);
    $this->_sort_order = (defined('ADDONS_PAYMENT_PAYPAL_PAYMENTS_PRO_SORT_ORDER') ? ADDONS_PAYMENT_PAYPAL_PAYMENTS_PRO_SORT_ORDER : null);

    if (defined('ADDONS_PAYMENT_PAYPAL_PAYMENTS_PRO_STATUS')) {
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

    if ((int)ADDONS_PAYMENT_PAYPAL_PAYMENTS_PRO_ORDER_STATUS_ID > 0) {
      $this->order_status = ADDONS_PAYMENT_PAYPAL_PAYMENTS_PRO_ORDER_STATUS_ID;
    }
    
    if ((int)ADDONS_PAYMENT_PAYPAL_PAYMENTS_PRO_ORDER_STATUS_COMPLETE_ID > 0) {
      $this->_order_status_complete = ADDONS_PAYMENT_PAYPAL_PAYMENTS_PRO_ORDER_STATUS_COMPLETE_ID;
    }    

    if (is_object($order)) $this->update_status();    
    if (defined('ADDONS_PAYMENT_PAYPAL_PAYMENTS_PRO_TEST_MODE') && ADDONS_PAYMENT_PAYPAL_PAYMENTS_PRO_TEST_MODE == '1') {
      $this->_ec_redirect_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=';  // sandbox url
      $this->action_url = 'https://api-3t.sandbox.paypal.com/nvp';  // sandbox url      
    } else {
      $this->_ec_redirect_url = 'https://www.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=';  // production url
      $this->action_url = 'https://api-3t.paypal.com/nvp';  // production url      
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

    if ( ($this->_status === true) && ((int)ADDONS_PAYMENT_PAYPAL_PAYMENTS_PRO_ZONE > 0) ) {
      $check_flag = false;

      $Qcheck = $lC_Database->query('select zone_id from :table_zones_to_geo_zones where geo_zone_id = :geo_zone_id and zone_country_id = :zone_country_id order by zone_id');
      $Qcheck->bindTable(':table_zones_to_geo_zones', TABLE_ZONES_TO_GEO_ZONES);
      $Qcheck->bindInt(':geo_zone_id', ADDONS_PAYMENT_PAYPAL_PAYMENTS_PRO_ZONE);
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

    if((int)ADDONS_PAYMENT_PAYPAL_PAYMENTS_PRO_DP_STATUS) {
      
      $card_accepted_types = ADDONS_PAYMENT_PAYPAL_PAYMENTS_PRO_ACCEPTED_TYPES;
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
                         'module' => '<div class="payment-selection">' . $lC_Language->get('payment_paypal_pro_method_title') . '</div><div class="payment-selection-title">' . $lC_Language->get('payment_paypal_pro_method_blurb') . '</div>',
                         'fields' => array(
                                        array('title' => $lC_Language->get('payment_paypal_pro_cc_firstname'),
                                              'field' => lc_draw_input_field('paypal_pro_cc_firstname', $lC_ShoppingCart->getBillingAddress('firstname'), ' class="form-control"')),
                                        array('title' => $lC_Language->get('payment_paypal_pro_cc_lastname'),
                                              'field' => lc_draw_input_field('paypal_pro_cc_lastname', $lC_ShoppingCart->getBillingAddress('lastname'), ' class="form-control"')),
                                        array('title' => $lC_Language->get('payment_paypal_pro_cc_type'),
                                              'field' => lc_draw_pull_down_menu('paypal_pro_cc_type', $cc_array, ' class="form-control"')),
                                        array('title' => $lC_Language->get('payment_paypal_pro_cc_number'),
                                              'field' => lc_draw_input_field('paypal_pro_cc_number', NULL, ' class="form-control"')),
                                        array('title' => $lC_Language->get('payment_paypal_pro_cc_expiry'),
                                              'field' => lc_draw_pull_down_menu('paypal_pro_cc_expires_month', $expires_month, ' class="form-control"') . '&nbsp;' . lc_draw_pull_down_menu('paypal_pro_cc_expires_year', $expires_year, ' class="form-control"')),
                                        array('title' => $lC_Language->get('payment_paypal_pro_cc_cvv').' '.$lC_Language->get('payment_paypal_pro_cc_cvv_text'),
                                              'field' => lc_draw_input_field('paypal_pro_cc_cvv', NULL, ' class="form-control" SIZE="4" MAXLENGTH="4"'))
                                     )
                        );
    }
    return $selection;
  }
 /**
  * Perform any pre-confirmation logic
  *
  * @access public
  * @return boolean
  */ 
  public function pre_confirmation_check() {
    global $lC_Language, $lC_ShoppingCart;

    $lC_ShoppingCart->setBillingMethod(array('id' => 'paypal_pro', 'title' => ((isset($_SESSION['paypal_pro_ec']) && $_SESSION['paypal_pro_ec'] == 1) ? $lC_Language->get('payment_paypal_pro_express_method_title') : $this->_method_title)));
    return false;
  }
 /**
  * Perform any post-confirmation logic
  *
  * @access public
  * @return integer
  */ 
  public function confirmation() {

    global $lC_Language, $lC_Database, $lC_MessageStack, $lC_ShoppingCart;

    $lC_ShoppingCart->setBillingMethod(array('id' => 'paypal_pro', 'title' => ((isset($_SESSION['paypal_pro_ec']) && $_SESSION['paypal_pro_ec'] == 1) ? $lC_Language->get('payment_paypal_pro_express_method_title') : $this->_method_title)));

    $_SESSION['cartSync']['paymentMethod'] = $this->_code;

    $this->_order_id = lC_Order::insert($this->order_status);
    // store the cartID info to match up on the return - to prevent multiple order IDs being created
    $_SESSION['cartSync']['cartID'] = $_SESSION['cartID'];
    $_SESSION['cartSync']['prepOrderID'] = $_SESSION['prepOrderID'];  
    $_SESSION['cartSync']['orderCreated'] = TRUE;  
  }
 /**
  * Return the confirmation button logic
  *
  * @access public
  * @return string
  */ 
  public function process_button() { 
    
    if($_SESSION['paypal_pro_ec'] == 0) { // Direct Credit Card Pyament
      
      $paypal_pro_cc_firstname  = $_POST['paypal_pro_cc_firstname'];
      $paypal_pro_cc_lastname   = $_POST['paypal_pro_cc_lastname'];
      $paypal_pro_cc_type       = $_POST['paypal_pro_cc_type'];
      $paypal_pro_cc_number     = $_POST['paypal_pro_cc_number'];
      $paypal_pro_cc_expiry     = $_POST['paypal_pro_cc_expires_month'].$_POST['paypal_pro_cc_expires_year'];
      $paypal_pro_cc_cvv        = $_POST['paypal_pro_cc_cvv'];

      echo lc_draw_hidden_field('paypal_pro_cc_firstname', $paypal_pro_cc_firstname);
      echo lc_draw_hidden_field('paypal_pro_cc_lastname', $paypal_pro_cc_lastname);
      echo lc_draw_hidden_field('paypal_pro_cc_type', $paypal_pro_cc_type);
      echo lc_draw_hidden_field('paypal_pro_cc_number', $paypal_pro_cc_number);
      echo lc_draw_hidden_field('paypal_pro_cc_expiry', $paypal_pro_cc_expiry);
      echo lc_draw_hidden_field('paypal_pro_cc_cvv', $paypal_pro_cc_cvv);
      
    } else if(!$_SESSION['PPEC_PAYDATA'] && $_SESSION['paypal_pro_ec'] == 1) {

      $_SESSION['PPEC_TOKEN'] = $this->setExpressCheckout();
      if (!$_SESSION['PPEC_TOKEN']) {
        lc_redirect(lc_href_link(FILENAME_CHECKOUT, 'cart', 'SSL')); 
      }
      // insert the order before leaving for paypal
      $this->confirmation();
      // redirect to paypal
      lc_redirect($this->_ec_redirect_url . $_SESSION['PPEC_TOKEN']); 
    } 
    return false;
  }
 /**
  * Parse the response from the processor
  *
  * @access public
  * @return string
  */ 
  public function process() {
    global $lC_Language, $lC_Database, $lC_MessageStack, $lC_ShoppingCart;

    if(!$_SESSION['paypal_pro_ec']) {      

      $response = $this->DoDirectPayment();
      $result = (isset($response['ACK']) && $response['ACK'] != NULL) ? $response['ACK'] : NULL;     

      if($result == "Success") {
        $this->confirmation();
        $details = $this->_getTransactionDetails($response['TRANSACTIONID']);
        if (!$details) {
          if ($lC_MessageStack->size('shopping_cart') > 0) {
            $_SESSION['messageToStack'] = $lC_MessageStack->getAll();
          } else {
            $_SESSION['messageToStack'] = array('shopping_cart', array('text' => 'An unknown error has occurred', 'type' => 'error'));
          }
          lc_redirect(lc_href_link(FILENAME_CHECKOUT, 'cart', 'SSL'));
        }
        $_SESSION['PPEC_PROCESS']['DATA'] = $details;
      }      
    } else if (isset($_SESSION['PPEC_TOKEN']) && $_SESSION['PPEC_TOKEN'] != NULL) {  // this is express checkout - goto ec process
      if (isset($_GET['PayerID']) && $_GET['PayerID'] != NULL) {
        $_SESSION['PPEC_PAYDATA']['TOKEN'] = $_GET['token'];
        $_SESSION['PPEC_PAYDATA']['PAYERID'] = $_GET['PayerID'];
        if (!$this->_ec_process()) {
          unset($_SESSION['PPEC_TOKEN']);
          lc_redirect(lc_href_link(FILENAME_CHECKOUT, 'cart', 'SSL'));
        } else {
          // ec step1 success
          unset($_SESSION['PPEC_TOKEN']);
          // set the skip payment flag
          $_SESSION['SKIP_PAYMENT_PAGE'] = TRUE;          
          lc_redirect(lc_href_link(FILENAME_CHECKOUT, 'confirmation', 'SSL'));
        }
      } else { // customer clicked cancel      
        if (isset($_GET['token']) && $_GET['token'] != NULL) {  // came from EC
          unset($_SESSION['PPEC_TOKEN']);
          lc_redirect(lc_href_link(FILENAME_CHECKOUT, 'cart', 'SSL'));
        }
      }
    } else if (isset($_SESSION['PPEC_PROCESS']) && $_SESSION['PPEC_PROCESS'] != NULL) {
       $response = $this->doExpressCheckoutPayment();
       $result = (isset($response['ACK']) && $response['ACK'] != NULL) ? $response['ACK'] : NULL;
    } else {      
      $result = (isset($_POST['RESULT']) && $_POST['RESULT'] != NULL) ? $_POST['RESULT'] : NULL;
      if (!isset($this->_order_id) || $this->_order_id == NULL) $this->_order_id = (isset($_POST['INVNUM']) && !empty($_POST['INVNUM'])) ? $_POST['INVNUM'] : $_POST['INVOICE'];
    }               

    $error = false;
    switch ($result) {
      case 'Success' :
        // update order status
        lC_Order::process($this->_order_id, $this->_order_status_complete);
        break;
        
      default :
        if ($result == NULL) { // customer clicked cancel
          lc_redirect(lc_href_link(FILENAME_CHECKOUT, null, 'SSL'));
        } else { // an error occurred
          $errmsg = sprintf($lC_Language->get('payment_paypal_pro_error_occurred'), '(' . $dataArr['L_ERRORCODE0'] . ') ' . $dataArr['L_SHORTMESSAGE0']);
          $error = true;
        }
    }   
    // insert into transaction history
    $this->_transaction_response = $result;
    
    if (isset($_SESSION['PPEC_PROCESS']['DATA']) && $_SESSION['PPEC_PROCESS']['DATA'] != NULL) {
      $response_array = array('root' => $_SESSION['PPEC_PROCESS']['DATA']);
      if (isset($_SESSION['cartSync']['orderID']) && $_SESSION['cartSync']['orderID'] != NULL) $this->_order_id = $_SESSION['cartSync']['orderID'];
    } else {
      $response_array = array('root' => $_POST);
    }
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
    if (isset($_SESSION['paypal_pro_ec'] )) unset($_SESSION['paypal_pro_ec']);    
    
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
      $this->_check = defined('ADDONS_PAYMENT_PAYPAL_PAYMENTS_PRO_STATUS');
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
  * Initialize the transaciton
  *
  * @access public
  * @return string
  */   
  public function setExpressCheckout() {
    global $lC_MessageStack;

    $response = $this->_setExpressCheckout();
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
  * Get the transaction details
  * 
  * @param  string  $token  The transaciton token
  * @access public
  * @return array
  */   
  public function getExpressCheckoutDetails($token) {
    global $lC_MessageStack;

    $response = $this->_getExpressCheckoutDetails($token);
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
  * Finalize the transaction
  *
  * @access public
  * @return bookean
  */   
  public function doExpressCheckoutPayment() {
    global $lC_MessageStack;
                   
    $response = $this->_doExpressCheckoutPayment($_SESSION['PPEC_PAYDATA']['TOKEN'], $_SESSION['PPEC_PAYDATA']['PAYERID']);

    if (!$response) {
      if ($lC_MessageStack->size('shopping_cart') > 0) {
        $_SESSION['messageToStack'] = $lC_MessageStack->getAll();
      } else {
        $_SESSION['messageToStack'] = array('shopping_cart', array('text' => 'An unknown error has occurred', 'type' => 'error'));
      }
      if (isset($_SESSION['PPEC_PROCESS'])) unset($_SESSION['PPEC_PROCESS']);
      if (isset($_SESSION['PPEC_PAYDATA'])) unset($_SESSION['PPEC_PAYDATA']);
      if (isset($_SESSION['SKIP_PAYMENT_PAGE'])) unset($_SESSION['SKIP_PAYMENT_PAGE']);
      return false;
    }
    
    unset($_SESSION['PPEC_PAYDATA']['TOKEN']);
    unset($_SESSION['PPEC_PAYDATA']['PAYERID']);
    
    if (!isset($this->_order_id) || $this->_order_id == NULL) $this->_order_id = (isset($_SESSION['prepOrderID']) && $_SESSION['prepOrderID'] != NULL) ? end(explode('-', $_SESSION['prepOrderID'])) : 0;
    
    return $response;    
  }  
 /**
  * Set the API authenticaiton parameters
  *
  * @access private
  * @return string
  */
  private function _getUserParams($method,$version='98.0') {
    return "METHOD=" . $method .  
           "&VERSION=" . $version .
           "&USER=" . urlencode(ADDONS_PAYMENT_PAYPAL_PAYMENTS_PRO_API_USERNAME) .
           "&PWD=" . urlencode(ADDONS_PAYMENT_PAYPAL_PAYMENTS_PRO_API_PASSWORD). 
           "&SIGNATURE=" . urlencode(ADDONS_PAYMENT_PAYPAL_PAYMENTS_PRO_API_SIGNATURE);
  }
 /**
  * Perform the Express Checkout post process
  *
  * @access private
  * @return string
  */  
  private function _ec_process() {
    global $lC_MessageStack, $lC_Customer, $lC_AddressBook, $lC_ShoppingCart;

    include_once('includes/classes/account.php');
    include_once('includes/classes/address.php');

    $details = $this->_getExpressCheckoutDetails($_SESSION['PPEC_TOKEN']);

    if (!$details) {
      if ($lC_MessageStack->size('shopping_cart') > 0) {
        $_SESSION['messageToStack'] = $lC_MessageStack->getAll();
      } else {
        $_SESSION['messageToStack'] = array('shopping_cart', array('text' => 'An unknown error has occurred', 'type' => 'error'));
      }
      return false;
    }  
      
    if ($lC_Customer->isLoggedOn() === false) {
      // check to see if email exists
      if (lC_Account::checkEntry($details['EMAIL'])) {
        // set customer data
        $lC_Customer->setCustomerData(lC_Account::getID($details['EMAIL']));
        // log the customer in
        $lC_Customer->setIsLoggedOn(true); 
        // sync the cart/order
        $_SESSION['cartSync']['cartID'] = $_SESSION['cartID'];
        $_SESSION['cartSync']['prepOrderID'] = $_SESSION['prepOrderID'];             
      } else {
        // create a new customer account
        $dataArr = array('firstname' => $details['FIRSTNAME'],
                         'lastname' => $details['LASTNAME'],
                         'email_address' => $details['EMAIL'],
                         'newsletter' => '0',
                         'password' => $details['EMAIL'],
                         'dob' => '0000-00-00 00:00:00');
        
        lC_Account::createEntry($dataArr);

        // log the customer in
        $lC_Customer->setIsLoggedOn(true);
        
        // create the address book entry
        $addrArr = array('gender' => '',
                         'company' => '',
                         'firstname' => $details['FIRSTNAME'],
                         'lastname' => $details['LASTNAME'],
                         'street_address' => $details['SHIPTOSTREET'],
                         'suburb' => '',
                         'postcode' => $details['SHIPTOZIP'],
                         'city' => $details['SHIPTOCITY'],
                         'state' => $details['SHIPTOSTATE'],
                         'country' => ($details['COUNTRYCODE'] == 'CA') ? '38' : '223',
                         'zone_id' => lC_Address::getZoneID($details['SHIPTOSTATE']),
                         'telephone' => '',
                         'fax' => '',
                         'primary' => true);
                         
        lC_AddressBook::saveEntry($addrArr);        
      }
      $lC_ShoppingCart->setBillingMethod(array('id' => 'paypal_pro', 'title' => $GLOBALS['lC_Payment_paypal_pro']->getMethodTitle()));
      $lC_ShoppingCart->resetBillingAddress();
      $lC_ShoppingCart->resetShippingAddress();      
    }

    $_SESSION['PPEC_PROCESS']['LINK'] = lc_href_link(FILENAME_CHECKOUT, 'process', 'SSL');
    $_SESSION['PPEC_PROCESS']['DATA'] = $details;
     
    return true; 
  }
  
 /**
  * Perform the doExpressCheckoutPayment API call
  *
  * @access private
  * @return string
  */  
  private function _doExpressCheckoutPayment($token, $payerID) {
    global $lC_ShoppingCart, $lC_Currencies, $lC_Language, $lC_MessageStack;
     
    $action_url = $this->action_url;      
    $transType = ADDONS_PAYMENT_PAYPAL_PAYMENTS_PRO_TRXTYPE;
    $returnUrl = (defined('ADDONS_PAYMENT_PAYPAL_PAYMENTS_PRO_TEMPLATE') && ADDONS_PAYMENT_PAYPAL_PAYMENTS_PRO_TEMPLATE == 'IFRAME') ?  lc_href_link(FILENAME_IREDIRECT, '', 'SSL', true, true, true) : lc_href_link(FILENAME_CHECKOUT, 'process', 'SSL', true, true, true);

    $postData = $this->_getUserParams('DoExpressCheckoutPayment') .  
                "&PAYMENTACTION=" . $transType . 
                "&BUTTONSOURCE=LoadedCommerce_Cart" .
                "&AMT=" . $lC_Currencies->formatRaw($lC_ShoppingCart->getTotal(), $lC_Currencies->getCode()) .
                "&TOKEN=" . $token . 
                "&PAYERID=" . $payerID;

    $response = transport::getResponse(array('url' => $action_url, 'method' => 'post', 'parameters' => $postData));

    if (!$response) { // server failure error
      $lC_MessageStack->add('shopping_cart', $lC_Language->get('payment_paypal_pro_error_server'), 'error');
      return false;
    }

    @parse_str($response, $dataArr);
   
    if ($dataArr['ACK'] == 'Failure') { // other error  
      $lC_MessageStack->add('shopping_cart', sprintf($lC_Language->get('payment_paypal_pro_error_occurred'), '(' . $dataArr['L_ERRORCODE0'] . ') ' . $dataArr['L_SHORTMESSAGE0']), 'error');
      return false;
    }  
    
    return $dataArr;
  }
 /**
  * Do the getExpressCheckoutDetails API call
  *
  * @access private
  * @return string
  */  
  private function _getExpressCheckoutDetails($token) {
    global $lC_ShoppingCart, $lC_Currencies, $lC_Language, $lC_MessageStack;    
        
    $action_url = $this->action_url;      
    $transType = ADDONS_PAYMENT_PAYPAL_PAYMENTS_PRO_TRXTYPE;
    $returnUrl = (defined('ADDONS_PAYMENT_PAYPAL_PAYMENTS_PRO_TEMPLATE') && ADDONS_PAYMENT_PAYPAL_PAYMENTS_PRO_TEMPLATE == 'IFRAME') ?  lc_href_link(FILENAME_IREDIRECT, '', 'SSL', true, true, true) : lc_href_link(FILENAME_CHECKOUT, 'process', 'SSL', true, true, true);

    $postData = $this->_getUserParams('GetExpressCheckoutDetails') .  
                "&TRXTYPE=" . $transType .                                
                "&TOKEN=" . $token;
  
    $response = transport::getResponse(array('url' => $action_url, 'method' => 'post', 'parameters' => $postData));   

    if (!$response) { // server failure error
      $lC_MessageStack->add('shopping_cart', $lC_Language->get('payment_paypal_pro_error_server'), 'error');
      return false;
    }

    @parse_str($response, $dataArr);

    if ($dataArr['ACK'] == 'Failure') { // other error      
      $lC_MessageStack->add('shopping_cart', sprintf($lC_Language->get('payment_paypal_pro_error_occurred'), '(' . $dataArr['L_ERRORCODE0'] . ') ' . $dataArr['L_SHORTMESSAGE0']), 'error');
      return false;
    }  
    
    return $dataArr;
  }
 /**
  * Do the setExpressCheckout API call
  *
  * @access private
  * @return string
  */  
  private function _setExpressCheckout() {
    global $lC_ShoppingCart, $lC_Currencies, $lC_Language, $lC_MessageStack, $lC_Customer;
    $lC_Language->load('modules-payment');
    $action_url = $this->action_url;

    // build the product description
    $cnt = 0;
    $itemsString = '';
    foreach ($lC_ShoppingCart->getProducts() as $products) {
      $itemsString .= '&L_PAYMENTREQUEST_0_NAME' . (string)$cnt . '=' . urlencode($products['name']) .
                      '&L_PAYMENTREQUEST_0_QTY' . (string)$cnt . '=' . $products['quantity'] .
                      '&L_PAYMENTREQUEST_0_AMT' . (string)$cnt . '=' . $lC_Currencies->formatRaw($products['price'], $lC_Currencies->getCode()) ;
      $cnt++;                      
    } 
    
    // get the shipping amount
    $discountTotal = 0;
    $taxTotal = 0;
    $shippingTotal = 0;
    foreach ($lC_ShoppingCart->getOrderTotals() as $ot) {
      if ($ot['code'] == 'shipping') $shippingTotal = (float)$ot['value'];
      if ($ot['code'] == 'tax') $taxTotal = (float)$ot['value'];
    } 

    $transType = ADDONS_PAYMENT_PAYPAL_PAYMENTS_PRO_TRXTYPE ;    
    $postData = $this->_getUserParams('SetExpressCheckout') .
                "&REQCONFIRMSHIPPING=0" .
                "&ADDROVERRIDE=1" . 
                "&SOLUTIONTYPE=Sole" .                
                "&RETURNURL=" . urlencode(lc_href_link(FILENAME_CHECKOUT, 'process', 'SSL', true, true, true)) .
                "&CANCELURL=" . urlencode(lc_href_link(FILENAME_CHECKOUT, 'process', 'SSL', true, true, true)) .
                "&PAYMENTREQUEST_0_CURRENCYCODE=" . $_SESSION['currency'] .
                "&PAYMENTREQUEST_0_AMT=" . $lC_Currencies->formatRaw($lC_ShoppingCart->getTotal(), $lC_Currencies->getCode()) . 
                "&PAYMENTREQUEST_0_SHIPPINGAMT=" . $shippingTotal .
                "&PAYMENTREQUEST_0_SHIPDISCAMT=" . (float)$discountTotal .
                "&PAYMENTREQUEST_0_TAXAMT=" . (float)$taxTotal .
                "&PAYMENTREQUEST_0_SHIPTONAME=" . urlencode($lC_ShoppingCart->getShippingAddress('firstname') . " " . $lC_ShoppingCart->getShippingAddress('lastname')) . 
                "&PAYMENTREQUEST_0_SHIPTOSTREET=" . urlencode($lC_ShoppingCart->getShippingAddress('street_address')) . 
                "&PAYMENTREQUEST_0_SHIPTOCITY=" . urlencode($lC_ShoppingCart->getBillingAddress('city')) . 
                "&PAYMENTREQUEST_0_SHIPTOSTATE=" . urlencode($lC_ShoppingCart->getBillingAddress('zone_code')) . 
                "&PAYMENTREQUEST_0_SHIPTOCOUNTRYCODE=" . urlencode($lC_ShoppingCart->getShippingAddress('country_iso_code_2')) . 
                "&PAYMENTREQUEST_0_SHIPTOZIP=" . $lC_ShoppingCart->getShippingAddress('postcode') .
                $itemsString .
                "&PAYMENTREQUEST_0_ITEMAMT=" . $lC_Currencies->formatRaw($lC_ShoppingCart->getSubTotal(), $lC_Currencies->getCode()) .
                "&PAYMENTREQUEST_0_DESC=Description+goes+here". 
                "&LOCALECODE=" . $lC_ShoppingCart->getBillingAddress('country_iso_code_2');

    $response = transport::getResponse(array('url' => $action_url, 'method' => 'post', 'parameters' => $postData));   

    if (!$response) { // server failure error
      $lC_MessageStack->add('shopping_cart', $lC_Language->get('payment_paypal_pro_error_server'), 'error');
      return false;
    }

    @parse_str($response, $dataArr);

    if ($dataArr['ACK'] == 'Failure') { // other error  
      $lC_MessageStack->add('shopping_cart', sprintf($lC_Language->get('payment_paypal_pro_error_occurred'), '(' . $dataArr['L_ERRORCODE0'] . ') ' . $dataArr['L_SHORTMESSAGE0']), 'error');
      return false;
    }
    return $dataArr['TOKEN'];    
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

    if(ADDONS_PAYMENT_PAYPAL_PAYMENTS_PRO_TRXTYPE == 'Authorization') {
      $version = '58.0';
    } else {
      $version = '56.0';
    }

    $paypal_pro_cc_firstname  = $_POST['paypal_pro_cc_firstname'];
    $paypal_pro_cc_lastname   = $_POST['paypal_pro_cc_lastname'];
    $paypal_pro_cc_type       = $_POST['paypal_pro_cc_type'];
    $paypal_pro_cc_number     = $_POST['paypal_pro_cc_number'];    
    $paypal_pro_cc_expiry     = $_POST['paypal_pro_cc_expiry'];
    $paypal_pro_cc_cvv        = $_POST['paypal_pro_cc_cvv'];

    $transType = ADDONS_PAYMENT_PAYPAL_PAYMENTS_PRO_TRXTYPE ;    
    $postData = $this->_getUserParams('DoDirectPayment', $version) .
                "&PAYMENTACTION=" . $transType . 
                "&IPADDRESS=" . lc_get_ip_address() . 
                "&AMT=" . $lC_Currencies->formatRaw($lC_ShoppingCart->getTotal(), $lC_Currencies->getCode()) . 
                "&CREDITCARDTYPE=" . $paypal_pro_cc_type .
                "&ACCT=" . $paypal_pro_cc_number .
                "&EXPDATE=" . $paypal_pro_cc_expiry .
                "&CVV2=" . $paypal_pro_cc_cvv . 
                "&FIRSTNAME=" . urlencode($paypal_pro_cc_firstname) . 
                "&LASTNAME=" . urlencode($paypal_pro_cc_lastname) . 
                "&STREET=" . urlencode($lC_ShoppingCart->getBillingAddress('street_address')) . 
                "&CITY=" . urlencode($lC_ShoppingCart->getBillingAddress('city')) . 
                "&STATE=" . urlencode($lC_ShoppingCart->getBillingAddress('zone_code')) .
                "&ZIP=" . $lC_ShoppingCart->getBillingAddress('postcode') .
                "&COUNTRYCODE=" . $lC_ShoppingCart->getBillingAddress('country_iso_code_2');

    $response = transport::getResponse(array('url' => $action_url, 'method' => 'post', 'parameters' => $postData));   

    if (!$response) { // server failure error
      $lC_MessageStack->add('shopping_cart', $lC_Language->get('payment_paypal_pro_error_server'), 'error');
      return false;
    }

    @parse_str($response, $dataArr);

    if ($dataArr['ACK'] == 'Failure') { // other error  
      $lC_MessageStack->add('shopping_cart', sprintf($lC_Language->get('payment_paypal_pro_error_occurred'), '(' . $dataArr['L_ERRORCODE0'] . ') ' . $dataArr['L_SHORTMESSAGE0']), 'error');
      return false;
    }
    
    return $dataArr;    
  }

  /**
  * Do the getExpressCheckoutDetails API call
  *
  * @access private
  * @return string
  */  
  private function _getTransactionDetails($transactionId) {
    global $lC_ShoppingCart, $lC_Currencies, $lC_Language, $lC_MessageStack;    
        
    $action_url = $this->action_url;      
    $transType = ADDONS_PAYMENT_PAYPAL_PAYMENTS_PRO_TRXTYPE;
    $returnUrl = (defined('ADDONS_PAYMENT_PAYPAL_PAYMENTS_PRO_TEMPLATE') && ADDONS_PAYMENT_PAYPAL_PAYMENTS_PRO_TEMPLATE == 'IFRAME') ?  lc_href_link(FILENAME_IREDIRECT, '', 'SSL', true, true, true) : lc_href_link(FILENAME_CHECKOUT, 'process', 'SSL', true, true, true);

    $postData = $this->_getUserParams('GetTransactionDetails') .        
                "&TRANSACTIONID=" . $transactionId;

    $response = transport::getResponse(array('url' => $action_url, 'method' => 'post', 'parameters' => $postData));   

    if (!$response) { // server failure error
      $lC_MessageStack->add('shopping_cart', $lC_Language->get('payment_paypal_pro_error_server'), 'error');
      return false;
    }

    @parse_str($response, $dataArr);

    if ($dataArr['ACK'] == 'Failure') { // other error      
      $lC_MessageStack->add('shopping_cart', sprintf($lC_Language->get('payment_paypal_pro_error_occurred'), '(' . $dataArr['L_ERRORCODE0'] . ') ' . $dataArr['L_SHORTMESSAGE0']), 'error');
      return false;
    }
    
    return $dataArr;
  }
}
?>