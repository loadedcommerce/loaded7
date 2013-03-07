<?php
/**  
*  $Id: paypal_adv.php v1.0 2013-01-01 datazen $
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
class lC_Payment_paypal_adv extends lC_Payment {     
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
  protected $_code = 'paypal_adv';
 /**
  * The developers name
  *
  * @var string
  * @access protected
  */  
  protected $_author_name = 'Loaded Commerce';
 /**
  * The developers address
  *
  * @var string
  * @access protected
  */  
  protected $_author_www = 'http://www.loadedcommerce.com';
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
  public function lC_Payment_paypal_adv() {
    global $lC_Language;

    $this->_title = $lC_Language->get('payment_paypal_adv_title');
    $this->_method_title = $lC_Language->get('payment_paypal_adv_title');
    $this->_status = (defined('MODULE_PAYMENT_PAYPAL_ADV_STATUS') && (MODULE_PAYMENT_PAYPAL_ADV_STATUS == '1') ? true : false);
    $this->_sort_order = (defined('MODULE_PAYMENT_PAYPAL_ADV_SORT_ORDER') ? MODULE_PAYMENT_PAYPAL_ADV_SORT_ORDER : null);

    if (defined('MODULE_PAYMENT_PAYPAL_ADV_STATUS')) {
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

    if ((int)MODULE_PAYMENT_PAYPAL_ADV_ORDER_STATUS_ID > 0) {
      $this->order_status = MODULE_PAYMENT_PAYPAL_ADV_ORDER_STATUS_ID;
    }
    
    if ((int)MODULE_PAYMENT_PAYPAL_ADV_ORDER_STATUS_COMPLETE_ID > 0) {
      $this->_order_status_complete = MODULE_PAYMENT_PAYPAL_ADV_ORDER_STATUS_COMPLETE_ID;
    }    

    if (is_object($order)) $this->update_status();
    
    if (defined('MODULE_PAYMENT_PAYPAL_ADV_TEST_MODE') && MODULE_PAYMENT_PAYPAL_ADV_TEST_MODE == '1') {
      $this->form_action_url = 'https://sandbox-paypal_adv.net/securepayments/a1/cc_collection.php';  // sandbox url
    } else {
      $this->form_action_url = 'https://paypal_adv.net/securepayments/a1/cc_collection.php';  // production url
    }  
    
    $Qcredit_cards = $lC_Database->query('select credit_card_name from :table_credit_cards where credit_card_status = :credit_card_status');
    $Qcredit_cards->bindRaw(':table_credit_cards', TABLE_CREDIT_CARDS);
    $Qcredit_cards->bindInt(':credit_card_status', '1');
    $Qcredit_cards->setCache('credit-cards');
    $Qcredit_cards->execute();

    while ($Qcredit_cards->next()) {
      $this->_card_images .= lc_image('images/cards/cc_' . strtolower(str_replace(" ", "_", $Qcredit_cards->value('credit_card_name'))) . '.png');
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

    if ( ($this->_status === true) && ((int)MODULE_PAYMENT_PAYPAL_ADV_ZONE > 0) ) {
      $check_flag = false;

      $Qcheck = $lC_Database->query('select zone_id from :table_zones_to_geo_zones where geo_zone_id = :geo_zone_id and zone_country_id = :zone_country_id order by zone_id');
      $Qcheck->bindTable(':table_zones_to_geo_zones', TABLE_ZONES_TO_GEO_ZONES);
      $Qcheck->bindInt(':geo_zone_id', MODULE_PAYMENT_PAYPAL_ADV_ZONE);
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
                       'module' => '<div class="payment-selection">' . $this->_title . '<span>' . $this->_card_images . '</span></div><div class="payment-selection-title">' . $lC_Language->get('payment_paypal_adv_button_description') . '</div>');    
    
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
    $this->_order_id = lC_Order::insert();
  }
 /**
  * Return the confirmation button logic
  *
  * @access public
  * @return string
  */ 
  public function process_button() {
    global $lC_Language, $lC_ShoppingCart, $lC_Currencies, $lC_Customer;
    
    $process_button_string = lc_draw_hidden_field('CRESecureID', MODULE_PAYMENT_PAYPAL_ADV_LOGIN) . "\n" . 
                             lc_draw_hidden_field('total_amt', $lC_Currencies->formatRaw($lC_ShoppingCart->getTotal(), $lC_Currencies->getCode())) . "\n" .
                             lc_draw_hidden_field('order_id', $this->_order_id) . "\n" .
                             lc_draw_hidden_field('customer_id', $lC_Customer->getID()) . "\n" .
                             lc_draw_hidden_field('currency_code', $_SESSION['currency']) . "\n" .
                             lc_draw_hidden_field('lang', $lC_Language->getCode()) . "\n" .
                             lc_draw_hidden_field('allowed_types', $this->_allowed_types) . "\n" .
                             lc_draw_hidden_field('sess_id', session_id()) . "\n" .
                             lc_draw_hidden_field('sess_name', session_name()) . "\n" .
                             lc_draw_hidden_field('ip_address', $_SERVER["REMOTE_ADDR"]) . "\n" .
                             lc_draw_hidden_field('return_url', lc_href_link(FILENAME_CHECKOUT, 'process', 'SSL', true, true, true)) . "\n" .
                             lc_draw_hidden_field('content_template_url', (getenv('HTTPS') == 'on') ? lc_href_link(FILENAME_CHECKOUT, 'payment_template', 'SSL', true, true, true) : null) . "\n" .
                             lc_draw_hidden_field('customer_company', $lC_ShoppingCart->getBillingAddress('company')) . "\n" .
                             lc_draw_hidden_field('customer_firstname', $lC_ShoppingCart->getBillingAddress('firstname')) . "\n" .
                             lc_draw_hidden_field('customer_lastname', $lC_ShoppingCart->getBillingAddress('lastname')) . "\n" .
                             lc_draw_hidden_field('customer_address', $lC_ShoppingCart->getBillingAddress('street_address')) . "\n" .
                             lc_draw_hidden_field('customer_email', $lC_Customer->getEmailAddress()) . "\n" .
                             lc_draw_hidden_field('customer_phone', $lC_Customer->getTelephone()) . "\n" .
                             lc_draw_hidden_field('customer_city', $lC_ShoppingCart->getBillingAddress('city')) . "\n" . 
                             lc_draw_hidden_field('customer_state', $lC_ShoppingCart->getBillingAddress('state')) . "\n" . 
                             lc_draw_hidden_field('customer_postal_code', $lC_ShoppingCart->getBillingAddress('postcode')) . "\n" .
                             lc_draw_hidden_field('customer_country', $lC_ShoppingCart->getBillingAddress('country_iso_code_3')) . "\n" .
                             lc_draw_hidden_field('delivery_company', $lC_ShoppingCart->getShippingAddress('company')) . "\n" .
                             lc_draw_hidden_field('delivery_firstname', $lC_ShoppingCart->getShippingAddress('firstname')) . "\n" .
                             lc_draw_hidden_field('delivery_lastname', $lC_ShoppingCart->getShippingAddress('lastname')) . "\n" .
                             lc_draw_hidden_field('delivery_address', $lC_ShoppingCart->getShippingAddress('street_address')) . "\n" .
                             lc_draw_hidden_field('delivery_email', $lC_Customer->getEmailAddress()) . "\n" .
                             lc_draw_hidden_field('delivery_phone', $lC_Customer->getTelephone()) . "\n" .
                             lc_draw_hidden_field('delivery_city', $lC_ShoppingCart->getShippingAddress('city')) . "\n" . 
                             lc_draw_hidden_field('delivery_state',  $lC_ShoppingCart->getShippingAddress('state')) . "\n" .
                             lc_draw_hidden_field('delivery_postal_code', $lC_ShoppingCart->getShippingAddress('postcode')) . "\n" .
                             lc_draw_hidden_field('delivery_country', $lC_ShoppingCart->getShippingAddress('country_iso_code_3')) . "\n" .  
                             lc_draw_hidden_field('form', 'mage') . "\n"; 

    return $process_button_string;
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
    $action = (isset($_GET['action']) && !empty($_GET['action'])) ? preg_replace('/[^a-zA-Z]/', '', $_GET['action']) : NULL;
    $code = (isset($_GET['code']) && !empty($_GET['code'])) ? preg_replace('/[^0-9]/', '', $_GET['code']) : NULL;
    $msg = (isset($_GET['msg']) && !empty($_GET['msg'])) ? preg_replace('/[^a-zA-Z0-9]\:\|\[\]/', '', $_GET['msg']) : NULL;
    $order_id = (isset($_GET['order_id']) && !empty($_GET['order_id'])) ? preg_replace('/[^a-zA-Z0-9]\:\|\[\]\-/', '', $_GET['order_id']) : NULL;
    
    switch (strtolower($action)) {
      case 'cancel' :
        lc_redirect(lc_href_link(FILENAME_CHECKOUT, null, 'SSL'));
        break;
        
      default :
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

        $response_array = array('root' => $_GET);
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
      $this->_check = defined('MODULE_PAYMENT_PAYPAL_ADV_STATUS');
    }

    return $this->_check;
  }
}
?>