<?php
/**
*  $Id: payleappayments.php v1.0 2013-01-01 datazen $
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
class lC_Payment_payleappayments extends lC_Payment {
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
  protected $_code = 'payleappayments';
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
  public function lC_Payment_payleappayments() {
    global $lC_Language;

    $this->_title = $lC_Language->get('payment_payleappayments_title');
    $this->_method_title = $lC_Language->get('payment_payleappayments_method_title');
    $this->_status = (defined('ADDONS_PAYMENT_PAYLEAP_PAYMENTS_STATUS') && (ADDONS_PAYMENT_PAYLEAP_PAYMENTS_STATUS == '1') ? true : false);
    $this->_sort_order = (defined('ADDONS_PAYMENT_PAYLEAP_PAYMENTS_SORT_ORDER') ? ADDONS_PAYMENT_PAYLEAP_PAYMENTS_SORT_ORDER : null);

    if (defined('ADDONS_PAYMENT_PAYLEAP_PAYMENTS_STATUS')) {
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

    if ((int)ADDONS_PAYMENT_PAYLEAP_PAYMENTS_ORDER_STATUS_ID > 0) {
      $this->order_status = ADDONS_PAYMENT_PAYLEAP_PAYMENTS_ORDER_STATUS_ID;
    }

    if ((int)ADDONS_PAYMENT_PAYLEAP_PAYMENTS_ORDER_STATUS_COMPLETE_ID > 0) {
      $this->_order_status_complete = ADDONS_PAYMENT_PAYLEAP_PAYMENTS_ORDER_STATUS_COMPLETE_ID;
    }

    if (is_object($order)) $this->update_status();

    if (defined('ADDONS_PAYMENT_PAYLEAP_PAYMENTS_TESTMODE') && ADDONS_PAYMENT_PAYLEAP_PAYMENTS_TESTMODE == '1') {
      $this->iframe_relay_url = 'https://uat.payleap.com/plcheckout.aspx';  // sandbox url
    } else {
      $this->iframe_relay_url = 'https://secure1.payleap.com/plcheckout.aspx';  // production url
    }
    $this->form_action_url = lc_href_link(FILENAME_CHECKOUT, 'payment_template', 'SSL', true, true, true) ;  

    $Qcredit_cards = $lC_Database->query('select credit_card_name from :table_credit_cards where credit_card_status = :credit_card_status');
    $Qcredit_cards->bindRaw(':table_credit_cards', TABLE_CREDIT_CARDS);
    $Qcredit_cards->bindInt(':credit_card_status', '1');
    $Qcredit_cards->setCache('credit-cards');
    $Qcredit_cards->execute();

    while ($Qcredit_cards->next()) {
      $this->_card_images .= lc_image('images/cards/cc_' . strtolower(str_replace(" ", "_", $Qcredit_cards->value('credit_card_name'))) . '.png', null, null, null, 'style="vertical-align:middle; margin:0 2px;"');
    }

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

    if ( ($this->_status === true) && ((int)ADDONS_PAYMENT_PAYLEAP_PAYMENTS_ZONE > 0) ) {
      $check_flag = false;

      $Qcheck = $lC_Database->query('select zone_id from :table_zones_to_geo_zones where geo_zone_id = :geo_zone_id and zone_country_id = :zone_country_id order by zone_id');
      $Qcheck->bindTable(':table_zones_to_geo_zones', TABLE_ZONES_TO_GEO_ZONES);
      $Qcheck->bindInt(':geo_zone_id', ADDONS_PAYMENT_PAYLEAP_PAYMENTS_ZONE);
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
                       'module' => '<div class="payment-selection">' . $this->_method_title . '<span>' . $this->_card_images . '</span></div><div class="payment-selection-title">' . $lC_Language->get('payment_payleappayments_method_blurb') . '</div>');

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
    global $lC_Language, $lC_ShoppingCart, $lC_Currencies, $lC_Customer;  

    $loginid = (defined('ADDONS_PAYMENT_PAYLEAP_PAYMENTS_USERNAME')) ? ADDONS_PAYMENT_PAYLEAP_PAYMENTS_USERNAME : '';
    $transactionkey = (defined('ADDONS_PAYMENT_PAYLEAP_PAYMENTS_TRANSKEY')) ? ADDONS_PAYMENT_PAYLEAP_PAYMENTS_TRANSKEY : '';  
    $amount = $lC_Currencies->formatRaw($lC_ShoppingCart->getTotal(), $lC_Currencies->getCode());
    $sequence = rand(1, 1000); // a sequence number is randomly generated
    $timestamp = time(); // a timestamp is generated
    $fingerprint = hash_hmac("md5", $loginid . "" . $amount . "" . $sequence . "" . $timestamp . "", $transactionkey); 

    $process_button_string = lc_draw_hidden_field('loginid', $loginid) . "\n" .
                             lc_draw_hidden_field('transactionkey', $transactionkey) . "\n" .
                             lc_draw_hidden_field('amount', $amount) . "\n" .
                             lc_draw_hidden_field('invoicenumber', $this->_order_id) . "\n" .
                             lc_draw_hidden_field('ponumber', $this->_order_id) . "\n" .
                             lc_draw_hidden_field('firstname', $lC_ShoppingCart->getBillingAddress('firstname')) . "\n" .
                             lc_draw_hidden_field('lastname', $lC_ShoppingCart->getBillingAddress('lastname')) . "\n" .
                             lc_draw_hidden_field('address1', $lC_ShoppingCart->getBillingAddress('street_address')) . "\n" .
                             lc_draw_hidden_field('email', $lC_Customer->getEmailAddress()) . "\n" .
                             lc_draw_hidden_field('phone', $lC_Customer->getTelephone()) . "\n" .
                             lc_draw_hidden_field('city', $lC_ShoppingCart->getBillingAddress('city')) . "\n" .
                             lc_draw_hidden_field('state', $lC_ShoppingCart->getBillingAddress('state')) . "\n" .
                             lc_draw_hidden_field('zip', $lC_ShoppingCart->getBillingAddress('postcode')) . "\n" .
                             lc_draw_hidden_field('country', $lC_ShoppingCart->getBillingAddress('country_iso_code_3')) . "\n" .
                             lc_draw_hidden_field('sequence', $sequence) . "\n" .
                             lc_draw_hidden_field('timestamp', $timestamp) . "\n" .
                             lc_draw_hidden_field('fingerprint', $fingerprint) . "\n" .                             
                             lc_draw_hidden_field('customField1', session_name()) . "\n" .
                             lc_draw_hidden_field('customField2', session_id()) . "\n" .
                             lc_draw_hidden_field('includeMerchantName', 'F') . "\n" .
                             lc_draw_hidden_field('readonlyorderdetail', 'F') . "\n" .
                             lc_draw_hidden_field('emailReceipt', 'T') . "\n" .
                             lc_draw_hidden_field('includePO', 'F') . "\n" .
                             lc_draw_hidden_field('includeInvoice', 'F') . "\n" .
                             lc_draw_hidden_field('hideAddress', 'T') . "\n" .
                             lc_draw_hidden_field('isRelayResponse', 'T') . "\n" .
                             lc_draw_hidden_field('relayResponseURL', lc_href_link('iredirect.php', '', 'SSL', true, true, true)) . "\n" .
                             lc_draw_hidden_field('styleSheetURL', lc_href_link('addons/Loaded_Payments/payleappayments.css', '', 'SSL', true, true, true)) . "\n";
      
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
    $success = (isset($_POST['success']) && $_POST['success'] == 'T') ? true : false;
    $code = (isset($_POST['responseCode']) && $_POST['responseCode'] != '') ? preg_replace('/[^0-9]/', '', $_POST['responseCode']) : NULL;
    $msg = (isset($_POST['error']) && $_POST['error'] != NULL) ? preg_replace('/[^a-zA-Z0-9]\:\|\[\]/', '', $_POST['error']) : NULL;

    switch ($success) {
      case true : // success
        // update order status
        $this->_order_id = lC_Order::insert();
        lC_Order::process($this->_order_id, $this->_order_status_complete);      
        // insert into transaction history
        $this->_transaction_response = $code;

        $response_array = array('root' => utility::cleanArr($_POST));
        $response_array['root']['transaction_response'] = $this->_transaction_response;
        $lC_XML = new lC_XML($response_array);

        $Qtransaction = $lC_Database->query('insert into :table_orders_transactions_history (orders_id, transaction_code, transaction_return_value, transaction_return_status, date_added) values (:orders_id, :transaction_code, :transaction_return_value, :transaction_return_status, now())');
        $Qtransaction->bindTable(':table_orders_transactions_history', TABLE_ORDERS_TRANSACTIONS_HISTORY);
        $Qtransaction->bindInt(':orders_id', $this->_order_id);
        $Qtransaction->bindInt(':transaction_code', 1);
        $Qtransaction->bindValue(':transaction_return_value', $lC_XML->toXML());
        $Qtransaction->bindInt(':transaction_return_status', (strtoupper(trim($this->_transaction_response)) == '000') ? 1 : 0);
        $Qtransaction->execute();
        break;

      default : // error
        switch ($code) {
          case '2' :
            // cancelled
            lc_redirect(lc_href_link(FILENAME_CHECKOUT, 'cart', 'SSL'));
            break;

          default :
            // there was an error
            $lC_MessageStack->add('checkout_payment', sprintf($lC_Language->get('error_payment_problem'), '(' . $code . ') : ' . $msg));
            $_SESSION['messageToStack'] = $lC_MessageStack->getAll(); 
            $error = true;
        }

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
      $this->_check = defined('ADDONS_PAYMENT_PAYLEAP_PAYMENTS_STATUS');
    }

    return $this->_check;
  }
}
?>