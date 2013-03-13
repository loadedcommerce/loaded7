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
  * The public title of the payment module (storeside)
  *
  * @var string
  * @access protected
  */  
  protected $_payment_title;  
 /**
  * Constructor
  */      
  public function lC_Payment_paypal_adv() {
    global $lC_Language;

    $this->_title = $lC_Language->get('payment_paypal_adv_title');
    $this->_method_title = $lC_Language->get('payment_paypal_adv_title');
    $this->_payment_title = $lC_Language->get('payment_paypal_adv_payment_title');
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
      $this->form_action_url = 'https://pilot-payflowlink.paypal.com';  // sandbox url
    } else {
      $this->form_action_url = 'https://payflowlink.paypal.com';  // production url
    }
    $this->iframe_action_url = (defined('MODULE_PAYMENT_PAYPAL_ADV_TEMPLATE') && MODULE_PAYMENT_PAYPAL_ADV_TEMPLATE == 'IFRAME') ? lc_href_link(FILENAME_CHECKOUT, 'payment_template', 'SSL', true, true, true) : NULL;  // sandbox url
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
                       'module' => '<div class="payment-selection">' . sprintf($this->_payment_title, lc_image('images/paypal-small.png', null, null, null, 'style="vertical-align:middle;"')) . '<span>' . lc_image('images/paypal-cards.png', null, null, null, 'style="vertical-align:middle;"') . '</span></div><div class="payment-selection-title">' . $lC_Language->get('payment_paypal_adv_button_description') . '</div>');    
    
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

    $tokenArr = $this->_getSecureToken();
        
    $process_button_string = lc_draw_hidden_field('SECURETOKEN', $tokenArr['SECURETOKEN']) . 
                             lc_draw_hidden_field('SECURETOKENID', $tokenArr['SECURETOKENID']);
                     
    if (defined('MODULE_PAYMENT_PAYPAL_ADV_TEST_MODE') && MODULE_PAYMENT_PAYPAL_ADV_TEST_MODE == '1') {                            
      $process_button_string .= lc_draw_hidden_field('MODE', 'TEST');
    }
    
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
    $result = (isset($_POST['RESULT']) && $_POST['RESULT'] != NULL) ? $_POST['RESULT'] : NULL;
    if (!isset($this->_order_id) || $this->_order_id == NULL) $this->_order_id = (isset($_POST['INVNUM']) && !empty($_POST['INVNUM'])) ? $_POST['INVNUM'] : $_POST['INVOICE'];

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
        
        // insert into transaction history
        $this->_transaction_response = $result;

        $response_array = array('root' => $_POST);
        $response_array['root']['transaction_response'] = trim($this->_transaction_response);
        $lC_XML = new lC_XML($response_array);
        
        $Qtransaction = $lC_Database->query('insert into :table_orders_transactions_history (orders_id, transaction_code, transaction_return_value, transaction_return_status, date_added) values (:orders_id, :transaction_code, :transaction_return_value, :transaction_return_status, now())');
        $Qtransaction->bindTable(':table_orders_transactions_history', TABLE_ORDERS_TRANSACTIONS_HISTORY);
        $Qtransaction->bindInt(':orders_id', $order_id);
        $Qtransaction->bindInt(':transaction_code', 1);
        $Qtransaction->bindValue(':transaction_return_value', $lC_XML->toXML());
        $Qtransaction->bindInt(':transaction_return_status', (strtoupper(trim($this->_transaction_response)) == '000') ? 1 : 0);
        $Qtransaction->execute();
        
        if ($error) lc_redirect(lc_href_link(FILENAME_CHECKOUT, 'payment&payment_error=' . $errmsg, 'SSL'));
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
  
  private function _getSecureToken() {
    global $lC_Language, $lC_ShoppingCart, $lC_Currencies, $lC_Customer, $lC_MessageStack;
    
    require(DIR_FS_CATALOG . 'includes/classes/transport.php');  
    
    if (defined('MODULE_PAYMENT_PAYPAL_ADV_TEST_MODE') && MODULE_PAYMENT_PAYPAL_ADV_TEST_MODE == '1') {
      $action_url = 'https://pilot-payflowpro.paypal.com';  // sandbox url
    } else {
      $action_url = 'https://payflowpro.paypal.com';  // production url
    }    
    
    // build the product description
    $cnt = 0;
    $itemsString = '';
    foreach ($lC_ShoppingCart->getProducts() as $products) {
      $itemsString .= '&L_NAME' . (string)$cnt . '=' . $products['name'] .
                      //'&L_DESC' . (string)$cnt . '=' . substr($products['description'], 0, 40) .
                      '&L_DESC' . (string)$cnt . '=test_desc' .
                      '&L_SKU' . (string)$cnt . '=' . $products['id'] .
                      '&L_COST' . (string)$cnt . '=' . $products['price'] .
                      '&L_QTY' . (string)$cnt . '=' . $products['quantity'];
    }
    
    // get the shipping amount
    $taxTotal = 0;
    $shippingTotal = 0;
    foreach ($lC_ShoppingCart->getOrderTotals() as $ot) {
      if ($ot['code'] == 'shipping') $shippingTotal = (float)$ot['value'];
      if ($ot['code'] == 'tax') $taxTotal = (float)$ot['value'];
    }

    $secureTokenId = uniqid('', true); 
    $transType = (defined('MODULE_PAYMENT_PAYPAL_ADV_TRXTYPE') && MODULE_PAYMENT_PAYPAL_ADV_TRXTYPE == 'Authorization') ? 'A' : 'S';
    $template = (defined('MODULE_PAYMENT_PAYPAL_ADV_TEMPLATE') && MODULE_PAYMENT_PAYPAL_ADV_TEMPLATE == 'IFRAME') ? 'MINILAYOUT' : ((MODULE_PAYMENT_PAYPAL_ADV_TEMPLATE == 'B') ? 'TEMPLATEB' : 'TEMPLATEA');
    
    $postData = "USER=" . MODULE_PAYMENT_PAYPAL_ADV_USER .
                "&VENDOR=" . MODULE_PAYMENT_PAYPAL_ADV_USER .
                "&PARTNER=Paypal" . 
                "&PWD=" . MODULE_PAYMENT_PAYPAL_ADV_PWD .  
                "&TRXTYPE=" . $transType . 
                "&BUTTONSOURCE=CRELoaded_Cart_EC_US" . 
                "&CREATESECURETOKEN=Y" . 
                "&SECURETOKENID=" . $secureTokenId . $itemsString .
                "&ITEMAMT=" . $lC_Currencies->formatRaw($lC_ShoppingCart->getSubTotal(), $lC_Currencies->getCode()) . 
                "&TAXAMT=" . $lC_Currencies->formatRaw($taxTotal, $lC_Currencies->getCode()) . 
                "&FREIGHTAMT=" . $shippingTotal . 
                "&AMT=" . $lC_Currencies->formatRaw($lC_ShoppingCart->getTotal(), $lC_Currencies->getCode()) .
                "&RETURNURL=" . lc_href_link(FILENAME_CHECKOUT, 'process', 'SSL', true, true, true) .
                "&ERRORURL=" . lc_href_link(FILENAME_CHECKOUT, 'process', 'SSL', true, true, true) .
                "&CANCELURL=" . lc_href_link(FILENAME_CHECKOUT, 'process', 'SSL', true, true, true) .
                "&TEMPLATE=" . MODULE_PAYMENT_PAYPAL_ADV_TEMPLATE .
                "&BILLTOFIRSTNAME=" . $lC_ShoppingCart->getBillingAddress('firstname') . 
                "&BILLTOLASTNAME=" . $lC_ShoppingCart->getBillingAddress('lastname') . 
                "&BILLTOSTREET=" . $lC_ShoppingCart->getBillingAddress('street_address') . 
                "&BILLTOCITY=" . $lC_ShoppingCart->getBillingAddress('city') . 
                "&BILLTOSTATE=" . $lC_ShoppingCart->getBillingAddress('zone_code') . 
                "&BILLTOCOUNTRY=" . $lC_ShoppingCart->getBillingAddress('country_iso_code_2') . 
                "&BILLTOZIP=" . $lC_ShoppingCart->getBillingAddress('postcode') . 
                "&BILLTOPHONENUM=" . $lC_Customer->getTelephone() . 
                "&BILLTOEMAIL=" . $lC_Customer->getEmailAddress() . 
                "&SHIPTOFIRSTNAME=" . $lC_ShoppingCart->getShippingAddress('firstname') . 
                "&SHIPTOLASTNAME=" . $lC_ShoppingCart->getShippingAddress('lastname') . 
                "&SHIPTOSTREET=" . $lC_ShoppingCart->getShippingAddress('street_address') . 
                "&SHIPTOCITY=" . $lC_ShoppingCart->getBillingAddress('city') . 
                "&SHIPTOSTATE=" . $lC_ShoppingCart->getBillingAddress('zone_code') . 
                "&SHIPTOCOUNTRY=" . $lC_ShoppingCart->getShippingAddress('country_iso_code_2') . 
                "&SHIPTOZIP=" . $lC_ShoppingCart->getShippingAddress('postcode') . 
                "&SHIPTOPHONENUM=" . $lC_Customer->getTelephone() . 
                "&SHIPTOEMAIL=" . $lC_Customer->getEmailAddress() . 
                "&CURRENCY=" . $_SESSION['currency'] . 
                "&INVNUM=" . $this->_order_id . 
                "&URLMETHOD=POST" . 
                "&ADDROVERRIDE=1"; 
                
    $response = transport::getResponse(array('url' => $action_url, 'method' => 'post', 'parameters' => $postData));

    if (!$response) {
      $errmsg = sprintf($lC_Language->get('error_payment_problem'), $lC_Language->get('payment_paypal_adv_error_no_response'));
      lc_redirect(lc_href_link(FILENAME_CHECKOUT, 'payment&payment_error=' . $errmsg, 'SSL'));
    }

    @parse_str($response, $dataArr);
    
    if ($dataArr['RESULT'] != 0) { // server failure error
      $errmsg = sprintf($lC_Language->get('error_payment_problem'), $lC_Language->get('payment_paypal_adv_error_occurred'));
      lc_redirect(lc_href_link(FILENAME_CHECKOUT, 'payment&payment_error=' . $errmsg, 'SSL'));
    }
    
    return $dataArr;
  }
}
?>