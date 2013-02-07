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
class lC_Payment_cresecure extends lC_Payment {
 /**
  * The public title of the payment module
  *
  * @var string
  * @access public
  */  
  public $_title;
 /**
  * The code of the payment module
  *
  * @var string
  * @access public
  */  
  public $_code = 'cresecure';
 /**
  * The developers name
  *
  * @var string
  * @access public
  */  
  public $_author_name = 'Loaded Commerce';
 /**
  * The developers address
  *
  * @var string
  * @access public
  */  
  public $_author_www = 'http://www.loadedcommerce.com';
 /**
  * The status of the module
  *
  * @var boolean
  * @access public
  */  
  public $_status = false;
 /**
  * The sort order of the module
  *
  * @var integer
  * @access public
  */  
  public $_sort_order;
 /**
  * Constructor
  */      
  public function lC_Payment_cresecure() {
    global $lC_Language;

    $this->_title = $lC_Language->get('payment_cresecure_title');
    $this->_description = $lC_Language->get('payment_cresecure_description');
    $this->_status = (defined('MODULE_PAYMENT_CRESECURE_STATUS') && (MODULE_PAYMENT_CRESECURE_STATUS == '1') ? true : false);
    $this->_sort_order = (defined('MODULE_PAYMENT_CRESECURE_SORT_ORDER') ? MODULE_PAYMENT_CRESECURE_SORT_ORDER : null);

    if (defined('MODULE_PAYMENT_CRESECURE_STATUS')) {
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
    global $order;

    if ((int)MODULE_PAYMENT_CRESECURE_ORDER_STATUS_ID > 0) {
      $this->order_status = MODULE_PAYMENT_CRESECURE_ORDER_STATUS_ID;
    }

    if (is_object($order)) $this->update_status();

    $this->form_action_url = 'https://www.cresecure.com/cgi-bin/Abuyers/purchase.2c';
  }
 /**
  * Disable module if zone selected does not match billing zone  
  *
  * @access public
  * @return void
  */  
  public function update_status() {
    global $lC_Database, $order;

    if ( ($this->_status === true) && ((int)MODULE_PAYMENT_CRESECURE_ZONE > 0) ) {
      $check_flag = false;

      $Qcheck = $lC_Database->query('select zone_id from :table_zones_to_geo_zones where geo_zone_id = :geo_zone_id and zone_country_id = :zone_country_id order by zone_id');
      $Qcheck->bindTable(':table_zones_to_geo_zones', TABLE_ZONES_TO_GEO_ZONES);
      $Qcheck->bindInt(':geo_zone_id', MODULE_PAYMENT_CRESECURE_ZONE);
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
  * Get the javascript block for this module  
  *
  * @access public
  * @return mixed
  */ 
  public function getJavascriptBlock() {
    return false;
  }  
 /**
  * Return the payment selections array
  *
  * @access public
  * @return array
  */   
  public function selection() {
    global $lC_Database, $lC_Language, $order, $cart_cresecure_ID;

    /*
    if (isset($_SESSION['cart_cresecure_ID'])) {
      $cart_cresecure_ID = $_SESSION['cart_cresecure_ID'];
      $order_id = substr($cart_cresecure_ID, strpos($cart_cresecure_ID, '-')+1);
      $check_query = tep_db_query('select orders_id from ' . TABLE_ORDERS_STATUS_HISTORY . ' where orders_id = "' . (int)$order_id . '" limit 1');
      if (tep_db_num_rows($check_query) < 1) {
        tep_db_query('delete from ' . TABLE_ORDERS . ' where orders_id = "' . (int)$order_id . '"');
        tep_db_query('delete from ' . TABLE_ORDERS_TOTAL . ' where orders_id = "' . (int)$order_id . '"');
        tep_db_query('delete from ' . TABLE_ORDERS_STATUS_HISTORY . ' where orders_id = "' . (int)$order_id . '"');
        tep_db_query('delete from ' . TABLE_ORDERS_PRODUCTS . ' where orders_id = "' . (int)$order_id . '"');
        tep_db_query('delete from ' . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . ' where orders_id = "' . (int)$order_id . '"');
        tep_db_query('delete from ' . TABLE_ORDERS_PRODUCTS_DOWNLOAD . ' where orders_id = "' . (int)$order_id . '"');

        unset($_SESSION['cart_cresecure_ID']);
      }
    } 
    */       

    $Qcredit_cards = $lC_Database->query('select credit_card_name from :table_credit_cards where credit_card_status = :credit_card_status');

    $Qcredit_cards->bindRaw(':table_credit_cards', TABLE_CREDIT_CARDS);
    $Qcredit_cards->bindInt(':credit_card_status', '1');
    $Qcredit_cards->setCache('credit-cards');
    $Qcredit_cards->execute();

    $credit_card_images = '';
    while ($Qcredit_cards->next()) {
      $credit_card_images .= lc_image('images/cards/cc_' . strtolower(str_replace(" ", "_", $Qcredit_cards->value('credit_card_name'))) . '.png');
    }

    $Qcredit_cards->freeResult();

    $selection = array('id' => $this->_code,
                       'module' => $this->_title . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $credit_card_images . '<div class="payment-selection-title">' . $lC_Language->get('payment_cresecure_button_description') . '</div>');    
    
    return $selection;
  }

  function pre_confirmation_check() {
  //  global $lC_Language, $lC_MessageStack;

//    $this->_verifyData();
    return false;
  }

  function confirmation() {
    global $lC_Language;

    $confirmation = array('title' => $this->_title . ': ' . $this->cc_card_type,
                          'fields' => array(array('title' => $lC_Language->get('payment_cresecure_credit_card_owner'),
                                                  'field' => $_POST['pm_cresecure_cc_owner_firstname'] . ' ' . $_POST['pm_cresecure_cc_owner_lastname']),
                                            array('title' => $lC_Language->get('payment_cresecure_credit_card_number'),
                                                  'field' => substr($this->cc_card_number, 0, 4) . str_repeat('X', (strlen($this->cc_card_number) - 8)) . substr($this->cc_card_number, -4)),
                                            array('title' => $lC_Language->get('payment_cresecure_credit_card_expiry_date'),
                                                  'field' => @strftime('%B, %Y', @mktime(0,0,0,$this->cc_expiry_month, 1, '20' . $this->cc_expiry_year)))));

    if (!empty($this->cc_checkcode)) {
      $confirmation['fields'][] = array('title' => $lC_Language->get('payment_cresecure_credit_card_checknumber'),
                                        'field' => $this->cc_checkcode);
    }

    return $confirmation;
  }

  function process_button() {
    global $order;

    $process_button_string = lc_draw_hidden_field('x_login', MODULE_PAYMENT_CRESECURE_LOGIN) .
                             lc_draw_hidden_field('x_amount', number_format($order->info['total'], 2)) .
                             lc_draw_hidden_field('x_invoice_num', @date('YmdHis')) .
                             lc_draw_hidden_field('x_test_request', ((MODULE_PAYMENT_CRESECURE_TESTMODE == 'Test') ? 'Y' : 'N')) .
                             lc_draw_hidden_field('x_card_num', $this->cc_card_number) .
                             lc_draw_hidden_field('cvv', $this->cc_checkcode) .
                             lc_draw_hidden_field('x_exp_date', $this->cc_expiry_month . substr($this->cc_expiry_year, -2)) .
                             lc_draw_hidden_field('x_first_name', $_POST['pm_cresecure_cc_owner_firstname']) .
                             lc_draw_hidden_field('x_last_name', $_POST['pm_cresecure_cc_owner_lastname']) .
                             lc_draw_hidden_field('x_address', $order->customer['street_address']) .
                             lc_draw_hidden_field('x_city', $order->customer['city']) .
                             lc_draw_hidden_field('x_state', $order->customer['state']) .
                             lc_draw_hidden_field('x_zip', $order->customer['postcode']) .
                             lc_draw_hidden_field('x_country', $order->customer['country']['title']) .
                             lc_draw_hidden_field('x_email', $order->customer['email_address']) .
                             lc_draw_hidden_field('x_phone', $order->customer['telephone']) .
                             lc_draw_hidden_field('x_ship_to_first_name', $order->delivery['firstname']) .
                             lc_draw_hidden_field('x_ship_to_last_name', $order->delivery['lastname']) .
                             lc_draw_hidden_field('x_ship_to_address', $order->delivery['street_address']) .
                             lc_draw_hidden_field('x_ship_to_city', $order->delivery['city']) .
                             lc_draw_hidden_field('x_ship_to_state', $order->delivery['state']) .
                             lc_draw_hidden_field('x_ship_to_zip', $order->delivery['postcode']) .
                             lc_draw_hidden_field('x_ship_to_country', $order->delivery['country']['title']) .
                             lc_draw_hidden_field('x_receipt_link_url', lc_href_link(FILENAME_CHECKOUT, 'process', 'SSL')) .
                             lc_draw_hidden_field('x_email_merchant', ((MODULE_PAYMENT_CRESECURE_EMAIL_MERCHANT == 'True') ? 'TRUE' : 'FALSE'));

    return $process_button_string;
  }

  function before_process() {
    global $lC_Language, $lC_MessageStack;

    if ($_POST['x_response_code'] != '1') {
      $lC_MessageStack->add('checkout_payment', $lC_Language->get('payment_cresecure_error_message'), 'error');

      lc_redirect(lc_href_link(FILENAME_CHECKOUT, 'payment', 'SSL'));
    }
  }

  function after_process() {
    return false;
  }

  function get_error() {
    return false;
  }

  function check() {
    if (!isset($this->_check)) {
      $this->_check = defined('MODULE_PAYMENT_CRESECURE_STATUS');
    }

    return $this->_check;
  }


  function _verifyData() {
    global $lC_Language, $lC_MessageStack, $lC_CreditCard;

    $lC_CreditCard = new lC_CreditCard($_POST['pm_cresecure_cc_number'], $_POST['pm_cresecure_cc_expires_month'], $_POST['pm_cresecure_cc_expires_year']);
    $lC_CreditCard->setOwner($_POST['pm_cresecure_cc_owner']);

    if ($result = $lC_CreditCard->isValid() !== true) {
      $lC_MessageStack->add('checkout_payment', $lC_Language->get('credit_card_number_error'), 'error');

      lc_redirect(lc_href_link(FILENAME_CHECKOUT, 'payment&pm_cresecure_cc_owner=' . $lC_CreditCard->getOwner() . '&pm_cresecure_cc_expires_month=' . $lC_CreditCard->getExpiryMonth() . '&pm_cresecure_cc_expires_year=' . $lC_CreditCard->getExpiryYear(), 'SSL'));
    }
  }
}
?>