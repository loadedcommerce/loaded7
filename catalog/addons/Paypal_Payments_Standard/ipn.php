<?php
/**  
  $Id: ipn.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     Loaded Commerce Team
  @copyright  (c) 2013 Loaded Commerce Team
  @license    http://loadedcommerce.com/license.html
*/

require('../../includes/application_top.php');
require_once($lC_Vqmod->modCheck(DIR_FS_CATALOG . 'includes/classes/order.php'));
require_once($lC_Vqmod->modCheck(DIR_FS_CATALOG . 'includes/classes/xml.php'));

ini_set('log_errors', true);
ini_set('error_log', DIR_FS_WORK . 'logs/paypal_ipn_errors.log');

// instantiate the IpnListener class
require($lC_Vqmod->modCheck(DIR_FS_CATALOG . 'addons/Paypal_Payments_Standard/ipnlistener.php'));
$listener = new IpnListener();

// testing your IPN in sandbox/live mode.
$listener->use_sandbox = MODULE_PAYMENT_PAYPAL_TEST_MODE;

try {
    $listener->requirePostMethod();
    $verified = $listener->processIpn();
} catch (Exception $e) {
    error_log($e->getMessage());
    if(MODULE_PAYMENT_PAYPAL_IPN_DEBUG == 'Yes') {
      @mail(MODULE_PAYMENT_PAYPAL_IPN_DEBUG_EMAIL, 'PayPal IPN Error', $listener->getTextReport());
    }
    exit(0);
}

$response_array = array('root' => $_POST);
$ipn_order_id = $_GET['ipn_order_id'];

$order = new lC_Order($ipn_order_id);
$amount = $order->info['total'];
$currency = $order->info['currency']; 

//The processIpn() method returned true if the IPN was "VERIFIED" and false if it was "INVALID".
if ($verified) {
  
  $paymentStatus = $listener->paymentStatus();
  // update order status
  switch ($paymentStatus ) {
    case 'Completed':
      //Check that $_POST['payment_amount'] and $_POST['payment_currency'] are correct
      if($listener->validPayment($amount,$currency)) {      
        $_order_status = MODULE_PAYMENT_PAYPAL_ORDER_DEFAULT_STATUS_ID;
      } else {
        $_order_status = MODULE_PAYMENT_PAYPAL_ORDER_ONHOLD_STATUS_ID;
      }
      break;
    case 'Pending':       
    case 'Failed':
      $_order_status = MODULE_PAYMENT_PAYPAL_ORDER_ONHOLD_STATUS_ID;
      break;
    case 'Denied':
      $_order_status = MODULE_PAYMENT_PAYPAL_ORDER_CANCELED_STATUS_ID;
      break;
    default:
      $_order_status = MODULE_PAYMENT_PAYPAL_PROCESSING_STATUS_ID;
  }
  lC_Order::process($_order_id, $_order_status);
  $response_array['root']['transaction_response'] = 'VERIFIED';
  $ipn_transaction_response = 'VERIFIED';
  @mail(MODULE_PAYMENT_PAYPAL_ID, 'Verified IPN', $listener->getTextReport());
} else {
  //An Invalid IPN *may* be caused by a fraudulent transaction attempt. It's a good idea to have a developer or sys admin manually investigate any invalid IPN.
  lC_Order::process($_order_id, MODULE_PAYMENT_PAYPAL_ORDER_CANCELED_STATUS_ID);
  $response_array['root']['transaction_response'] = 'INVALID';
  $ipn_transaction_response = 'INVALID';
  @mail(MODULE_PAYMENT_PAYPAL_ID, 'Invalid IPN', $listener->getTextReport());
}

$lC_XML = new lC_XML($response_array);

$Qtransaction = $lC_Database->query('insert into :table_orders_transactions_history (orders_id, transaction_code, transaction_return_value, transaction_return_status, date_added) values (:orders_id, :transaction_code, :transaction_return_value, :transaction_return_status, now())');
$Qtransaction->bindTable(':table_orders_transactions_history', TABLE_ORDERS_TRANSACTIONS_HISTORY);
$Qtransaction->bindInt(':orders_id', $ipn_order_id);
$Qtransaction->bindInt(':transaction_code', 1);
$Qtransaction->bindValue(':transaction_return_value', $lC_XML->toXML());
$Qtransaction->bindInt(':transaction_return_status', (strtoupper(trim($ipn_transaction_response)) == 'VERIFIED') ? 1 : 0);
$Qtransaction->execute();

?>