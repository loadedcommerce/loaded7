<?php
/**  
  $Id: payflow_EC.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     Loaded Commerce Team
  @copyright  (c) 2013 Loaded Commerce Team
  @license    http://loadedcommerce.com/license.html
*/
require_once(DIR_FS_CATALOG . 'includes/classes/transport.php'); 

class lC_Payment_payflow_EC extends lC_Payment {     
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
  protected $_code = 'payflow_EC';
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
  public function lC_Payment_payflow_EC() {
    global $lC_Language;

    $this->_title = $lC_Language->get('payment_payflow_EC_title'); // admin listing title
    $this->_method_title = $lC_Language->get('payment_payflow_EC_method_title'); // public sidebar title 
    $this->_status = (defined('ADDONS_PAYMENT_PAYFLOW_EXPRESS_CHECKOUT_STATUS') && (ADDONS_PAYMENT_PAYFLOW_EXPRESS_CHECKOUT_STATUS == '1') ? true : false);
    $this->_sort_order = (defined('ADDONS_PAYMENT_PAYFLOW_EXPRESS_CHECKOUT_SORT_ORDER') ? ADDONS_PAYMENT_PAYFLOW_EXPRESS_CHECKOUT_SORT_ORDER : null);

    if (defined('ADDONS_PAYMENT_PAYFLOW_EXPRESS_CHECKOUT_STATUS')) {
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

    if ((int)ADDONS_PAYMENT_PAYFLOW_EXPRESS_CHECKOUT_ORDER_STATUS_ID > 0) {
      $this->order_status = ADDONS_PAYMENT_PAYFLOW_EXPRESS_CHECKOUT_ORDER_STATUS_ID;
    }
    
    if ((int)ADDONS_PAYMENT_PAYFLOW_EXPRESS_CHECKOUT_ORDER_STATUS_COMPLETE_ID > 0) {
      $this->_order_status_complete = ADDONS_PAYMENT_PAYFLOW_EXPRESS_CHECKOUT_ORDER_STATUS_COMPLETE_ID;
    }    

    if (is_object($order)) $this->update_status();
    
    if (defined('ADDONS_PAYMENT_PAYFLOW_EXPRESS_CHECKOUT_TEST_MODE') && ADDONS_PAYMENT_PAYFLOW_EXPRESS_CHECKOUT_TEST_MODE == '1') {
      $this->_ec_redirect_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=';  // sandbox url
      if (defined('ADDONS_PAYMENT_PAYFLOW_EXPRESS_CHECKOUT_TEMPLATE') && ADDONS_PAYMENT_PAYFLOW_EXPRESS_CHECKOUT_TEMPLATE == 'C') {
        $this->form_action_url = lc_href_link(FILENAME_CHECKOUT, 'payment_template', 'SSL', true, true, true);  // payment page
        // set the width, height and scroll for the iframe
        $this->iframe_params = $this->_getIframeParams();
        $this->iframe_action_url = 'https://pilot-payflowpro.paypal.com?SECURETOKEN=' . $_SESSION['cartSync']['SECURETOKEN'] . '&SECURETOKENID=' . $_SESSION['cartSync']['SECURETOKENID'] . '&MODE=TEST';  
      } else {
        $this->form_action_url = 'https://pilot-payflowpro.paypal.com';  // sandbox url
      }
    } else {
      $this->_ec_redirect_url = 'https://www.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=';  // production url
      if (defined('ADDONS_PAYMENT_PAYFLOW_EXPRESS_CHECKOUT_TEMPLATE') && ADDONS_PAYMENT_PAYFLOW_EXPRESS_CHECKOUT_TEMPLATE == 'C') {
        $this->form_action_url = lc_href_link(FILENAME_CHECKOUT, 'payment_template', 'SSL', true, true, true);  // payment page
        // set the width, height and scroll for the iframe
        $this->iframe_params = $this->_getIframeParams();
        $this->iframe_action_url = 'https://payflowpro.paypal.com?SECURETOKEN=' . $_SESSION['cartSync']['SECURETOKEN'] . '&SECURETOKENID=' . $_SESSION['cartSync']['SECURETOKENID'];  
      } else {
        $this->form_action_url = 'https://payflowpro.paypal.com';  // production url
      }      
      
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

    if ( ($this->_status === true) && ((int)ADDONS_PAYMENT_PAYFLOW_EXPRESS_CHECKOUT_ZONE > 0) ) {
      $check_flag = false;

      $Qcheck = $lC_Database->query('select zone_id from :table_zones_to_geo_zones where geo_zone_id = :geo_zone_id and zone_country_id = :zone_country_id order by zone_id');
      $Qcheck->bindTable(':table_zones_to_geo_zones', TABLE_ZONES_TO_GEO_ZONES);
      $Qcheck->bindInt(':geo_zone_id', ADDONS_PAYMENT_PAYFLOW_EXPRESS_CHECKOUT_ZONE);
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
                       'module' => '<div class="payment-selection">' . $lC_Language->get('payment_payflow_EC_method_title') . '<span>' . lc_image('images/payment/paypal-cards.png', null, null, null, 'style="vertical-align:middle;"') . '</span></div><div class="payment-selection-title">' . $lC_Language->get('payment_payflow_EC_method_blurb') . '</div>');    
    
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
    global $lC_Language, $lC_Database, $lC_MessageStack, $lC_ShoppingCart;

    $lC_ShoppingCart->setBillingMethod(array('id' => $this->_code, 'title' => $this->_method_title));

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
    global $lC_MessageStack;

    if(!$_SESSION['PPEC_PAYDATA']) {
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

    if (isset($_SESSION['PPEC_TOKEN']) && $_SESSION['PPEC_TOKEN'] != NULL) {  // this is express checkout - goto ec process
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
       $result = (isset($response['RESULT']) && $response['RESULT'] != NULL) ? $response['RESULT'] : NULL;  
    } else {     
      $result = (isset($_POST['RESULT']) && $_POST['RESULT'] != NULL) ? $_POST['RESULT'] : NULL;
      if (!isset($this->_order_id) || $this->_order_id == NULL) $this->_order_id = (isset($_POST['INVNUM']) && !empty($_POST['INVNUM'])) ? $_POST['INVNUM'] : $_POST['INVOICE'];
    }               

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
          $errmsg = sprintf($lC_Language->get('error_payment_problem'), '(' . $result . ') ' . $_POST['RESPMSG']);
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
      $this->_check = defined('ADDONS_PAYMENT_PAYFLOW_EXPRESS_CHECKOUT_STATUS');
    }

    return $this->_check;
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
  private function _getUserParams() {
    return "USER=" . ADDONS_PAYMENT_PAYFLOW_EXPRESS_CHECKOUT_USER .
           "&VENDOR=" . ADDONS_PAYMENT_PAYFLOW_EXPRESS_CHECKOUT_MERCH .
           "&PARTNER=" . ADDONS_PAYMENT_PAYFLOW_EXPRESS_CHECKOUT_PARTNER .
           "&PWD=" . ADDONS_PAYMENT_PAYFLOW_EXPRESS_CHECKOUT_PASSWORD;
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
      $lC_ShoppingCart->setBillingMethod(array('id' => 'payflow_EC', 'title' => $GLOBALS['lC_Payment_payflow_EC']->getMethodTitle()));

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
     
    if (defined('ADDONS_PAYMENT_PAYFLOW_EXPRESS_CHECKOUT_TEST_MODE') && ADDONS_PAYMENT_PAYFLOW_EXPRESS_CHECKOUT_TEST_MODE == '1') {
      $action_url = 'https://pilot-payflowpro.paypal.com';  // sandbox url
    } else {
      $action_url = 'https://payflowpro.paypal.com';  // production url
    }     

    $transType = (defined('ADDONS_PAYMENT_PAYFLOW_EXPRESS_CHECKOUT_TRXTYPE') && ADDONS_PAYMENT_PAYFLOW_EXPRESS_CHECKOUT_TRXTYPE == 'Authorization') ? 'A' : 'S';
    $returnUrl = (defined('ADDONS_PAYMENT_PAYFLOW_EXPRESS_CHECKOUT_TEMPLATE') && ADDONS_PAYMENT_PAYFLOW_EXPRESS_CHECKOUT_TEMPLATE == 'IFRAME') ?  lc_href_link(FILENAME_IREDIRECT, '', 'SSL', true, true, true) : lc_href_link(FILENAME_CHECKOUT, 'process', 'SSL', true, true, true);

    $postData = $this->_getUserParams() .  
                "&TRXTYPE=" . $transType . 
                "&TENDER=P" . 
                "&ACTION=D" . 
                "&BUTTONSOURCE=CRELoaded_Cart_EC_US" .
                "&AMT=" . $lC_Currencies->formatRaw($lC_ShoppingCart->getTotal(), $lC_Currencies->getCode()) .
                "&TOKEN=" . $token . 
                "&PAYERID=" . $payerID;
         
    $response = transport::getResponse(array('url' => $action_url, 'method' => 'post', 'parameters' => $postData));    
  
    if (!$response) { // server failure error
      $lC_MessageStack->add('shopping_cart', $lC_Language->get('payment_payflow_EC_error_server'), 'error');
      return false;
    }

    @parse_str($response, $dataArr);
    
    if ($dataArr['RESULT'] != 0) { // other error
      $lC_MessageStack->add('shopping_cart', sprintf($lC_Language->get('payment_payflow_EC_error_occurred'), '(' . $dataArr['RESULT'] . ') ' . $dataArr['RESPMSG']), 'error');
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
     
    if (defined('ADDONS_PAYMENT_PAYFLOW_EXPRESS_CHECKOUT_TEST_MODE') && ADDONS_PAYMENT_PAYFLOW_EXPRESS_CHECKOUT_TEST_MODE == '1') {
      $action_url = 'https://pilot-payflowpro.paypal.com';  // sandbox url
    } else {
      $action_url = 'https://payflowpro.paypal.com';  // production url
    }     

    $transType = (defined('ADDONS_PAYMENT_PAYFLOW_EXPRESS_CHECKOUT_TRXTYPE') && ADDONS_PAYMENT_PAYFLOW_EXPRESS_CHECKOUT_TRXTYPE == 'Authorization') ? 'A' : 'S';
    $returnUrl = (defined('ADDONS_PAYMENT_PAYFLOW_EXPRESS_CHECKOUT_TEMPLATE') && ADDONS_PAYMENT_PAYFLOW_EXPRESS_CHECKOUT_TEMPLATE == 'IFRAME') ?  lc_href_link(FILENAME_IREDIRECT, '', 'SSL', true, true, true) : lc_href_link(FILENAME_CHECKOUT, 'process', 'SSL', true, true, true);

    $postData = $this->_getUserParams() .  
                "&TRXTYPE=" . $transType . 
                "&TENDER=P" . 
                "&ACTION=G" . 
                "&TOKEN=" . $token;
         
    $response = transport::getResponse(array('url' => $action_url, 'method' => 'post', 'parameters' => $postData));    
  
    if (!$response) { // server failure error
      $lC_MessageStack->add('shopping_cart', $lC_Language->get('payment_payflow_EC_error_server'), 'error');
      return false;
    }

    @parse_str($response, $dataArr);
    
    if ($dataArr['RESULT'] != 0) { // other error
      $lC_MessageStack->add('shopping_cart', sprintf($lC_Language->get('payment_payflow_EC_error_occurred'), '(' . $dataArr['RESULT'] . ') ' . $dataArr['RESPMSG']), 'error');
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
     
    if (defined('ADDONS_PAYMENT_PAYFLOW_EXPRESS_CHECKOUT_TEST_MODE') && ADDONS_PAYMENT_PAYFLOW_EXPRESS_CHECKOUT_TEST_MODE == '1') {
      $action_url = 'https://pilot-payflowpro.paypal.com';  // sandbox url
    } else {
      $action_url = 'https://payflowpro.paypal.com';  // production url
    }   
    
    // build the product description
    $cnt = 0;
    $itemsString = '';
    foreach ($lC_ShoppingCart->getProducts() as $products) {
      $itemsString .= '&L_NAME' . (string)$cnt . '=' . $products['name'] .
                      '&L_DESC' . (string)$cnt . '=' . substr($products['description'], 0, 40) .
                      //'&L_SKU' . (string)$cnt . '=' . $products['id'] .
                      '&L_COST' . (string)$cnt . '=' . $products['price'] .
                      '&L_QTY' . (string)$cnt . '=' . $products['quantity'];
      $cnt++;                      
    } 
    
    // get the shipping amount
    $taxTotal = 0;
    $shippingTotal = 0;
    $discountTotal = 0;
    foreach ($lC_ShoppingCart->getOrderTotals() as $ot) {
      if ($ot['code'] == 'shipping') $shippingTotal = (float)$ot['value'];
      if ($ot['code'] == 'tax') $taxTotal = (float)$ot['value'];
    }         

    $transType = (defined('ADDONS_PAYMENT_PAYFLOW_EXPRESS_CHECKOUT_TRXTYPE') && ADDONS_PAYMENT_PAYFLOW_EXPRESS_CHECKOUT_TRXTYPE == 'Authorization') ? 'A' : 'S';
    $postData = $this->_getUserParams() .  
                "&TRXTYPE=" . $transType . 
                "&TENDER=P" . 
                "&USERSELECTEDFUNDINGSOURCE=BML" .
                "&ACTION=S" . $itemsString .
                "&AMT=" . $lC_Currencies->formatRaw($lC_ShoppingCart->getTotal(), $lC_Currencies->getCode()) .
                "&RETURNURL=" . lc_href_link(FILENAME_CHECKOUT, 'process', 'SSL', true, true, true) .
                "&CANCELURL=" . lc_href_link(FILENAME_CHECKOUT, 'process', 'SSL', true, true, true) .                 
                "&ITEMAMT=" . $lC_Currencies->formatRaw($lC_ShoppingCart->getSubTotal(), $lC_Currencies->getCode()) . 
                "&TAXAMT=" . $lC_Currencies->formatRaw($taxTotal, $lC_Currencies->getCode()) . 
                "&FREIGHTAMT=" . $shippingTotal .               
                "&DISCOUNT=" . $discountTotal .               
                "&PHONENUM=" . $lC_Customer->getTelephone() . 
                "&EMAIL=" . $lC_Customer->getEmailAddress() . 
                "&SHIPTONAME=" . $lC_ShoppingCart->getShippingAddress('firstname') . " " . $lC_ShoppingCart->getShippingAddress('lastname') .
                "&SHIPTOSTREET=" . $lC_ShoppingCart->getShippingAddress('street_address') . 
                "&SHIPTOCITY=" . $lC_ShoppingCart->getBillingAddress('city') . 
                "&SHIPTOSTATE=" . $lC_ShoppingCart->getBillingAddress('zone_code') . 
                "&SHIPTOCOUNTRY=" . $lC_ShoppingCart->getShippingAddress('country_iso_code_2') . 
                "&SHIPTOZIP=" . $lC_ShoppingCart->getShippingAddress('postcode') . 
                "&CURRENCY=" . $_SESSION['currency'] . 
                "&INVNUM=" . $this->_order_id . 
                "&ADDROVERRIDE=1";
    
    $response = transport::getResponse(array('url' => $action_url, 'method' => 'post', 'parameters' => $postData));    
   
    if (!$response) { // server failure error
      $lC_MessageStack->add('shopping_cart', $lC_Language->get('payment_payflow_EC_error_server'), 'error');
      return false;
    }

    @parse_str($response, $dataArr);
 
    if ($dataArr['RESULT'] != 0) { // other error  
      $lC_MessageStack->add('shopping_cart', sprintf($lC_Language->get('payment_payflow_EC_error_occurred'), '(' . $dataArr['RESULT'] . ') ' . $dataArr['RESPMSG']), 'error');
      return false;
    }
    
    return $dataArr['TOKEN'];    
  }  
}
?>