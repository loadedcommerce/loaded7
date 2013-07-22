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

class lC_Payment_globaliris extends lC_Payment {     
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
  protected $_code = 'globaliris';
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
  public function lC_Payment_globaliris() {
    global $lC_Language;

    $this->_title = $lC_Language->get('payment_globaliris_title');
    $this->_method_title = $lC_Language->get('payment_globaliris_method_title');
    $this->_status = (defined('ADDONS_PAYMENT_GLOBAL_IRIS_PAYMENTS_STATUS') && (ADDONS_PAYMENT_GLOBAL_IRIS_PAYMENTS_STATUS == '1') ? true : false);
    $this->_sort_order = (defined('ADDONS_PAYMENT_GLOBAL_IRIS_PAYMENTS_SORT_ORDER') ? ADDONS_PAYMENT_GLOBAL_IRIS_PAYMENTS_SORT_ORDER : null);

    if ($this->_status === true) {
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
    global $lC_Database, $lC_Language;

    if ((int)ADDONS_PAYMENT_GLOBAL_IRIS_PAYMENTS_ORDER_STATUS_ID > 0) {
      $this->order_status = ADDONS_PAYMENT_GLOBAL_IRIS_PAYMENTS_ORDER_STATUS_ID;
    }
    
    if ((int)ADDONS_PAYMENT_GLOBAL_IRIS_PAYMENTS_ORDER_STATUS_COMPLETE_ID > 0) {
      $this->_order_status_complete = ADDONS_PAYMENT_GLOBAL_IRIS_PAYMENTS_ORDER_STATUS_COMPLETE_ID;
    }    

    $this->iframe_relay_url = 'https://redirect.globaliris.com/epage.cgi';  // production url as no test URL
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
    
    if ($this->_status === true) {
      if ((int)ADDONS_PAYMENT_GLOBAL_IRIS_PAYMENTS_ORDER_STATUS_ID > 0) {
        $this->order_status = ADDONS_PAYMENT_GLOBAL_IRIS_PAYMENTS_ORDER_STATUS_ID;
      }

      if ((int)ADDONS_PAYMENT_GLOBAL_IRIS_PAYMENTS_ZONE > 0) {
        $check_flag = false;

        $Qcheck = $lC_Database->query('select zone_id from :table_zones_to_geo_zones where geo_zone_id = :geo_zone_id and zone_country_id = :zone_country_id order by zone_id');
        $Qcheck->bindTable(':table_zones_to_geo_zones', TABLE_ZONES_TO_GEO_ZONES);
        $Qcheck->bindInt(':geo_zone_id', ADDONS_PAYMENT_GLOBAL_IRIS_PAYMENTS_ZONE);
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

        if ($check_flag === false) {
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
    global $lC_Language;

    $selection = array('id' => $this->_code,
                       'module' => '<div class="payment-selection">' . $lC_Language->get('payment_globaliris_method_title') . '<span>' . $this->_card_images . '</span></div><div class="payment-selection-title">' . $lC_Language->get('payment_globaliris_method_blurb') . '</div>');    
   
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
    global $lC_Currencies, $lC_ShoppingCart;

    $orderid = lC_Order::insert();
    
    //create the timestamp format required by Global Iris
    $timestamp = strftime("%Y%m%d%H%M%S");
    mt_srand((double)microtime()*1000000);
    
    $merchantid = ADDONS_PAYMENT_GLOBAL_IRIS_PAYMENTS_MERCHANT_ID;
    $secret = ADDONS_PAYMENT_GLOBAL_IRIS_PAYMENTS_SECRET_KEY;
    
    $amount = str_replace(".", "", $lC_ShoppingCart->getTotal());
    $curr = $lC_Currencies->getCode();
    
    $tmp = "$timestamp.$merchantid.$orderid.$amount.$curr";
    $md5hash = md5($tmp);
    $tmp = "$md5hash.$secret";
    $md5hash = md5($tmp);    

    $process_button_string  = lc_draw_hidden_field('MERCHANT_ID', $merchantid) . "\n";
    $process_button_string .= lc_draw_hidden_field('ORDER_ID', $orderid) . "\n";
    $process_button_string .= lc_draw_hidden_field('AMOUNT', $amount) . "\n"; 
    $process_button_string .= lc_draw_hidden_field('CURRENCY', $curr) . "\n";
    $process_button_string .= lc_draw_hidden_field('TIMESTAMP', $timestamp) . "\n";
    $process_button_string .= lc_draw_hidden_field('MD5HASH', $md5hash) . "\n";
    $process_button_string .= lc_draw_hidden_field('AUTO_SETTLE_FLAG', '1') . "\n";

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

    $timestamp = $_POST['TIMESTAMP'];
    $result = $_POST['RESULT'];
    $orderid = $_POST['ORDER_ID'];
    $message = $_POST['MESSAGE'];
    $authcode = $_POST['AUTHCODE'];
    $pasref = $_POST['PASREF'];
    $realexmd5 = $_POST['MD5HASH'];

    $merchantid = ADDONS_PAYMENT_GLOBAL_IRIS_PAYMENTS_MERCHANT_ID;
    $secret = ADDONS_PAYMENT_GLOBAL_IRIS_PAYMENTS_SECRET_KEY;

    //---------------------------------------------------------------
    //Below is the code for creating the digital signature using the md5 algorithm. 
    //This digital siganture should correspond to the 
    //one Global Iris POSTs back to this script and can therefore be used to verify the message Global Iris sends back.

    $tmp = "$timestamp.$merchantid.$orderid.$result.$message.$pasref.$authcode";
    $md5hash = md5($tmp);
    $tmp = "$md5hash.$secret";
    $md5hash = md5($tmp);

    //Check to see if hashes match or not
    if ($md5hash != $realexmd5) {
      $action = 'error';
    } else if($result == '00') {
      $action = 'success';
    } else if($result == '01') {
      $action = 'cancel';
    } else {
      $action = 'error';
    }

    switch($action){
      case 'error' :
        $lC_MessageStack->add('checkout_payment', $result . ' - ' . $message);
        lC_Order::remove($orderid);
        $error = true;
        break;
      case'success':
        lC_Order::process($orderid, $this->_order_status_complete);
        break;
      default : //cancel
        lc_redirect(lc_href_link(FILENAME_CHECKOUT, 'cart', 'SSL'));
    }
    
    // insert into transaction history
    $this->_transaction_response = $result;

    $response_array = array('root' => $_POST);
    $response_array['root']['transaction_response'] = trim($this->_transaction_response);
    $lC_XML = new lC_XML($response_array);
    
    $Qtransaction = $lC_Database->query('insert into :table_orders_transactions_history (orders_id, transaction_code, transaction_return_value, transaction_return_status, date_added) values (:orders_id, :transaction_code, :transaction_return_value, :transaction_return_status, now())');
    $Qtransaction->bindTable(':table_orders_transactions_history', TABLE_ORDERS_TRANSACTIONS_HISTORY);
    $Qtransaction->bindInt(':orders_id', $orderid);
    $Qtransaction->bindInt(':transaction_code', 1);
    $Qtransaction->bindValue(':transaction_return_value', $lC_XML->toXML());
    $Qtransaction->bindInt(':transaction_return_status', (strtoupper(trim($this->_transaction_response)) == '00') ? 1 : 0);
    $Qtransaction->execute();   
    
    if ($error) lc_redirect(lc_href_link(FILENAME_CHECKOUT, 'payment', 'SSL'));    
  } 
 /**
  * Check the status of the payment module
  *
  * @access public
  * @return boolean
  */ 
  public function check() {
    if (!isset($this->_check)) {
      $this->_check = defined('ADDONS_PAYMENT_GLOBAL_IRIS_PAYMENTS_STATUS');
    }

    return $this->_check;
  }  
}
?>