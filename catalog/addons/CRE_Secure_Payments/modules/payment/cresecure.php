<?php
/**  
*  $Id: cresecure.php v1.0 2013-01-01 datazen $
*
*  LoadedCommerce, Innovative eCommerce Solutions
*  http://www.loadedcommerce.com
*
*  Copyright (c) 2013 Loaded Commerce, LLC
*
*  @author     Loaded Commerce Team
*  @copyright  (c) 2013 Loaded Commerce Team
*  @license    http://loadedcommerce.com/license.html
*/
include_once(DIR_FS_CATALOG . 'includes/classes/transport.php');

class lC_Payment_cresecure extends lC_Payment {     
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
  protected $_code = 'cresecure';
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
  * The allowed credit card types (pipe separated)
  *
  * @var string
  * @access protected
  */ 
  protected $_allowed_types;  
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
  * The credit card image string
  *
  * @var string
  * @access protected
  */   
  protected $_card_images;   
 /**
  * Constructor
  */      
  public function lC_Payment_cresecure() {
    global $lC_Language;

    $this->_title = $lC_Language->get('payment_cresecure_title');
    $this->_method_title = $lC_Language->get('payment_cresecure_method_title');
    //$this->_status = (defined('ADDONS_PAYMENT_CRE_SECURE_PAYMENTS_STATUS') && (ADDONS_PAYMENT_CRE_SECURE_PAYMENTS_STATUS == '1') ? true : false);
    $this->_status = true;
    $this->_sort_order = (defined('ADDONS_PAYMENT_CRE_SECURE_PAYMENTS_SORT_ORDER') ? ADDONS_PAYMENT_CRE_SECURE_PAYMENTS_SORT_ORDER : null);

    if (defined('ADDONS_PAYMENT_CRE_SECURE_PAYMENTS_STATUS')) {
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

    if ((int)ADDONS_PAYMENT_CRE_SECURE_PAYMENTS_ORDER_STATUS_ID > 0) {
      $this->order_status = ADDONS_PAYMENT_CRE_SECURE_PAYMENTS_ORDER_STATUS_ID;
    }
    
    if ((int)ADDONS_PAYMENT_CRE_SECURE_PAYMENTS_ORDER_STATUS_COMPLETE_ID > 0) {
      $this->_order_status_complete = ADDONS_PAYMENT_CRE_SECURE_PAYMENTS_ORDER_STATUS_COMPLETE_ID;
    }    

    if (is_object($order)) $this->update_status();
    
    if (defined('ADDONS_PAYMENT_CRE_SECURE_PAYMENTS_TEST_MODE') && ADDONS_PAYMENT_CRE_SECURE_PAYMENTS_TEST_MODE == '1') {
      $this->iframe_action_url = 'https://sandbox-cresecure.net/securepayments/a1/cc_collection.php?' . $this->_iframe_params();  // sandbox url
    } else {
      $this->iframe_action_url = 'https://cresecure.net/securepayments/a1/cc_collection.php?' . $this->_iframe_params();  // production url
    }  
    $this->form_action_url = (getenv('HTTPS') == 'on') ? lc_href_link(FILENAME_CHECKOUT, 'payment_template', 'SSL', true, true, true) : null;
    
    $Qcredit_cards = $lC_Database->query('select credit_card_name from :table_credit_cards where credit_card_status = :credit_card_status');
    $Qcredit_cards->bindRaw(':table_credit_cards', TABLE_CREDIT_CARDS);
    $Qcredit_cards->bindInt(':credit_card_status', '1');
    $Qcredit_cards->setCache('credit-cards');
    $Qcredit_cards->execute();

    while ($Qcredit_cards->next()) {
      $this->_card_images .= lc_image('images/cards/cc_' . strtolower(str_replace(" ", "_", $Qcredit_cards->value('credit_card_name'))) . '.png', null, null, null, 'style="vertical-align:middle; margin:0 2px;"');
      $name = strtolower($Qcredit_cards->value('credit_card_name'));
      if (stristr($Qcredit_cards->value('credit_card_name'), 'discover')) $name = 'Discover';
      if (stristr($Qcredit_cards->value('credit_card_name'), 'jcb')) $name = 'JCB';
      $this->_allowed_types .= ucwords($name) . '|';
    }
    if (substr($this->_allowed_types, -1) == '|') $this->_allowed_types = substr($this->_allowed_types, 0, strlen($this->_allowed_types) - 1);
    
    $Qcredit_cards->freeResult();      
  }
 /**
  * Disable module if zone selected does not match billing zone  
  *
  * @access public
  * @return void
  */  
  public function update_status() {
    global $lC_Database, $order;

    if ( ($this->_status === true) && ((int)ADDONS_PAYMENT_CRE_SECURE_PAYMENTS_ZONE > 0) ) {
      $check_flag = false;

      $Qcheck = $lC_Database->query('select zone_id from :table_zones_to_geo_zones where geo_zone_id = :geo_zone_id and zone_country_id = :zone_country_id order by zone_id');
      $Qcheck->bindTable(':table_zones_to_geo_zones', TABLE_ZONES_TO_GEO_ZONES);
      $Qcheck->bindInt(':geo_zone_id', ADDONS_PAYMENT_CRE_SECURE_PAYMENTS_ZONE);
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
                       'module' => '<div class="payment-selection">' . $this->_method_title . '<span>' . $this->_card_images . '</span></div><div class="payment-selection-title">' . $lC_Language->get('payment_cresecure_blurb') . '</div>');    
    
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
    $_SESSION['cartSync']['paymentMethod'] = $this->_code;
    $this->_order_id = lC_Order::insert();
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
    return false;
  }
 /**
  * Parse the response from the processor
  *
  * @access public
  * @return string
  */ 
  public function process() {
    global $lC_Language, $lC_Database, $lC_MessageStack;

    $error = false;
    $action = (isset($_POST['action']) && !empty($_POST['action'])) ? preg_replace('/[^a-zA-Z]/', '', $_POST['action']) : NULL;
    $uID = (isset($_POST['uID']) && !empty($_POST['uID'])) ? preg_replace('/[^A-Z0-9]/', '', $_POST['uID']) : NULL;
    
    switch (strtolower($action)) {
      case 'cancel' :
        lc_redirect(lc_href_link(FILENAME_CHECKOUT, null, 'SSL'));
        break;
        
      default :
        if (!isset($uID)) {
          // uID is missing for some reason
          $code = '360';
          $message = 'uID missing from response.';
          $lC_MessageStack->add('checkout_payment', $code . ' - ' . $msg);
          $error = true;
        }
    
        // get the transaction details
        $details = utility::nvp2arr($this->_query_uid($uID));

        $code = (isset($details['code']) && !empty($details['code'])) ? preg_replace('/[^0-9]/', '', $details['code']) : NULL;
        $msg = (isset($details['msg']) && !empty($details['msg'])) ? preg_replace('/[^a-zA-Z0-9]\:\|\[\]/', '', $details['msg']) : NULL;
        $order_id = (isset($details['order_id']) && !empty($details['order_id'])) ? preg_replace('/[^a-zA-Z0-9]\:\|\[\]\-/', '', $details['order_id']) : NULL;    

        switch ($code) {
          case '000' :
            // update order status
            lC_Order::process($order_id, $this->_order_status_complete);
            break;
            
          default :
            // there was an error
            $lC_MessageStack->add('checkout_payment', $code . ' - ' . $msg);
            $error = true;
        }
        // insert into transaction history
        $this->_transaction_response = $code;

        $response_array = array('root' => $details);
        $response_array['root']['transaction_response'] = trim($this->_transaction_response);
        $lC_XML = new lC_XML($response_array);
        
        $Qtransaction = $lC_Database->query('insert into :table_orders_transactions_history (orders_id, transaction_code, transaction_return_value, transaction_return_status, date_added) values (:orders_id, :transaction_code, :transaction_return_value, :transaction_return_status, now())');
        $Qtransaction->bindTable(':table_orders_transactions_history', TABLE_ORDERS_TRANSACTIONS_HISTORY);
        $Qtransaction->bindInt(':orders_id', $order_id);
        $Qtransaction->bindInt(':transaction_code', 1);
        $Qtransaction->bindValue(':transaction_return_value', $lC_XML->toXML());
        $Qtransaction->bindInt(':transaction_return_status', (strtoupper(trim($this->_transaction_response)) == '000') ? 1 : 0);
        $Qtransaction->execute();
        
        if ($error) lc_redirect(lc_href_link(FILENAME_CHECKOUT, 'payment', 'SSL'));
    }
  } 
 /**
  * Check the status of the pasyment module
  *
  * @access public
  * @return boolean
  */ 
  public function check() {
    if (!isset($this->_check)) {
      $this->_check = defined('ADDONS_PAYMENT_CRE_SECURE_PAYMENTS_STATUS');
    }

    return $this->_check;
  }
 /**
  * Get the transaction details from CRE
  *
  * @access public
  * @return string
  */ 
  private function _query_uid($uID) {
    
    if (defined('ADDONS_PAYMENT_CRE_SECURE_PAYMENTS_TEST_MODE') && ADDONS_PAYMENT_CRE_SECURE_PAYMENTS_TEST_MODE == '1') {
      $uid_query_url = 'https://sandbox-cresecure.net/direct/services/request/query/';  // sandbox url
    } else {
      $uid_query_url = 'https://cresecure.net/direct/services/request/query/'; // production url
    }
    
    $uid_query_params = array('CRESecureID' => ADDONS_PAYMENT_CRE_SECURE_PAYMENTS_LOGIN, 
                              'CRESecureAPIToken' => ADDONS_PAYMENT_CRE_SECURE_PAYMENTS_API_TOKEN,
                              'uID' => $uID);    
        
    $response = transport::getResponse(array('url' => $uid_query_url, 'method' => 'post', 'parameters' => $uid_query_params));
    
    $params = substr($response, strpos($response, 'code='));         

    return $params;
  }  
 /**
  * Return the confirmation button logic
  *
  * @access public
  * @return string
  */ 
  private function _iframe_params() {
    global $lC_Language, $lC_ShoppingCart, $lC_Currencies, $lC_Customer; 
    
    if (defined('ADDONS_PAYMENT_CRE_SECURE_PAYMENTS_TEST_MODE') && ADDONS_PAYMENT_CRE_SECURE_PAYMENTS_TEST_MODE == '1') {
      $uid_action_url = 'https://sandbox-cresecure.net/direct/services/request/init/';  // sandbox url
    } else {
      $uid_action_url = 'https://cresecure.net/direct/services/request/init/'; // production url
    }    
    
    $uid_action_params = array('CRESecureID' => ADDONS_PAYMENT_CRE_SECURE_PAYMENTS_LOGIN, 
                               'CRESecureAPIToken' => ADDONS_PAYMENT_CRE_SECURE_PAYMENTS_API_TOKEN,
                               'total_amt' => $lC_Currencies->formatRaw($lC_ShoppingCart->getTotal(), $lC_Currencies->getCode()),
                               'order_id' => $this->_order_id,
                               'customer_id' => $lC_Customer->getID(),
                               'currency_code' => $_SESSION['currency'],
                               'lang' => $lC_Language->getCode(),
                               'allowed_types' => $this->_allowed_types,
                               'sess_id' => session_id(),
                               'sess_name' => session_name(),
                               'ip_address' => $_SERVER["REMOTE_ADDR"],
                               'return_url' => lc_href_link(FILENAME_IREDIRECT, '', 'SSL', true, true, true),
                               'content_template_url' => (getenv('HTTPS') == 'on') ? lc_href_link('cresecure_template.php', '', 'SSL', true, true, true) : null,
                               'customer_company' => $lC_ShoppingCart->getBillingAddress('company'),
                               'customer_firstname' => $lC_ShoppingCart->getBillingAddress('firstname'),
                               'customer_lastname' => $lC_ShoppingCart->getBillingAddress('lastname'),
                               'customer_address' => $lC_ShoppingCart->getBillingAddress('street_address'),
                               'customer_email' => $lC_Customer->getEmailAddress(),
                               'customer_phone' => $lC_Customer->getTelephone(),
                               'customer_city' => $lC_ShoppingCart->getBillingAddress('city'), 
                               'customer_state' => $lC_ShoppingCart->getBillingAddress('state'), 
                               'customer_postal_code' => $lC_ShoppingCart->getBillingAddress('postcode'),
                               'customer_country' => $lC_ShoppingCart->getBillingAddress('country_iso_code_3'),
                               'delivery_company' => $lC_ShoppingCart->getShippingAddress('company'),
                               'delivery_firstname' => $lC_ShoppingCart->getShippingAddress('firstname'),
                               'delivery_lastname' => $lC_ShoppingCart->getShippingAddress('lastname'),
                               'delivery_address' => $lC_ShoppingCart->getShippingAddress('street_address'),
                               'delivery_email' => $lC_Customer->getEmailAddress(),
                               'delivery_phone' => $lC_Customer->getTelephone(),
                               'delivery_city' => $lC_ShoppingCart->getShippingAddress('city'), 
                               'delivery_state' =>  $lC_ShoppingCart->getShippingAddress('state'),
                               'delivery_postal_code' => $lC_ShoppingCart->getShippingAddress('postcode'),
                               'delivery_country' => $lC_ShoppingCart->getShippingAddress('country_iso_code_3'),  
                               'form' => 'mage');   
                                  
    $response = transport::getResponse(array('url' => $uid_action_url, 'method' => 'post', 'parameters' => $uid_action_params));   

    $params = substr($response, strpos($response, 'uID='));         
                                  
    return $params;
  }  
}
?>