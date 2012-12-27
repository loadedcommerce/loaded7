<?php
/*
  $Id: paypal_ipn.php v1.0 2011-11-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

/**
 * The administration side of the PayPal IPN payment module
 */

  class lC_Payment_paypal_ipn extends lC_Payment_Admin {

/**
 * The administrative title of the payment module
 *
 * @var string
 * @access private
 */

    var $_title;

/**
 * The code of the payment module
 *
 * @var string
 * @access private
 */

    var $_code = 'paypal_ipn';

/**
 * The developers name
 *
 * @var string
 * @access private
 */

    var $_author_name = 'LoadedCommerce';

/**
 * The developers address
 *
 * @var string
 * @access private
 */

    var $_author_www = 'http://www.loadedcommerce.com';

/**
 * The status of the module
 *
 * @var boolean
 * @access private
 */

    var $_status = false;

/**
 * Constructor
 */

    function lC_Payment_paypal_ipn() {
      global $lC_Language;

      $this->_title = $lC_Language->get('payment_paypal_ipn_title');
      $this->_description = $lC_Language->get('payment_paypal_ipn_description');
      $this->_method_title = $lC_Language->get('payment_paypal_ipn_method_title');
      $this->_status = (defined('MODULE_PAYMENT_PAYPAL_IPN_STATUS') && (MODULE_PAYMENT_PAYPAL_IPN_STATUS == '1') ? true : false);
      $this->_sort_order = (defined('MODULE_PAYMENT_PAYPAL_IPN_SORT_ORDER') ? MODULE_PAYMENT_PAYPAL_IPN_SORT_ORDER : null);

      if (defined('MODULE_PAYMENT_PAYPAL_IPN_SERVER')) {
        switch (MODULE_PAYMENT_PAYPAL_IPN_SERVER) {
          case 'Production':
            $this->_gateway_server = 'https://api.paypal.com/2.0/';
            break;

          default:
            $this->_gateway_server = 'https://api.sandbox.paypal.com/2.0/';
            break;
        }
      }
    }

/**
 * Checks to see if the module has been installed
 *
 * @access public
 * @return boolean
 */

    function isInstalled() {
      return (bool)defined('MODULE_PAYMENT_PAYPAL_IPN_STATUS');
    }

/**
 * Installs the module
 *
 * @access public
 * @see lC_Payment_Admin::install()
 */

    function install() {
      global $lC_Database;

      parent::install();

      $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Enable PayPal IPN Module', 'MODULE_PAYMENT_PAYPAL_IPN_STATUS', '-1', 'Do you want to accept PayPal IPN payments?', '6', '0', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))', now())");
      $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('PayPal E-Mail Address', 'MODULE_PAYMENT_PAYPAL_IPN_ID', '', 'The seller e-mail address to use for accepting PayPal payments.', '6', '0', now())");
      $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('API Username', 'MODULE_PAYMENT_PAYPAL_IPN_API_USERNAME', '', 'The username to use for the PayPal Web Services API.', '6', '0', now())");
      $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('API Password', 'MODULE_PAYMENT_PAYPAL_IPN_API_PASSWORD', '', 'The password to use for the PayPal Web Services API.', '6', '0', now())");
      $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('API Certificate', 'MODULE_PAYMENT_PAYPAL_IPN_API_CERTIFICATE', '', 'The location of the PayPal API Certificate for the PayPal Web Services API.', '6', '0', now())");
      $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Enable Encrypted Web Payments', 'MODULE_PAYMENT_PAYPAL_IPN_EWP_STATUS', '-1', 'Do you want to enable Encrypted Web Payments?', '6', '0', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))', now())");
      $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Encrypted Web Payments Private Key (Seller)', 'MODULE_PAYMENT_PAYPAL_IPN_EWP_PRIVATE_KEY', '', 'The location of your Private Key to use for signing the data. (*.pem)', '6', '0', now())");
      $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Encrypted Web Payments Public Certificate (Seller)', 'MODULE_PAYMENT_PAYPAL_IPN_EWP_PUBLIC_KEY', '', 'The location of your Public Certificate to use for signing the data. (*.pem)', '6', '0', now())");
      $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Encrpyted Web Payments Public Certificate (PayPal)', 'MODULE_PAYMENT_PAYPAL_IPN_EWP_PAYPAL_KEY', '', 'The location of the PayPal Public Certificate for encrypting the data.', '6', '0', now())");
      $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Encrypted Web Payments Public Certificate ID', 'MODULE_PAYMENT_PAYPAL_IPN_EWP_CERT_ID', '', 'The Certificate ID to use from your PayPal Encrypted Payment Settings Profile.', '6', '0', now())");
      $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('OpenSSL Location', 'MODULE_PAYMENT_PAYPAL_IPN_EWP_OPENSSL', '/usr/bin/openssl', 'The location of the openssl binary file.', '6', '0', now())");
      $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Secret Key', 'MODULE_PAYMENT_PAYPAL_IPN_SECRET_KEY', '', 'A secret key to pass to PayPal (primarly used with Encrypted Web Payments).', '6', '0', now())");
      $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Transaction Currency', 'MODULE_PAYMENT_PAYPAL_IPN_CURRENCY', 'Selected Currency', 'The currency to use for credit card transactions', '6', '0', 'lc_cfg_set_boolean_value(array(\'Selected Currency\',\'USD\',\'CAD\',\'EUR\',\'GBP\',\'JPY\'))', now())");
      $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Transaction Server', 'MODULE_PAYMENT_PAYPAL_IPN_SERVER', 'Sandbox', 'The server to perform transactions in.', '6', '0', 'lc_cfg_set_boolean_value(array(\'Production\',\'Sandbox\'))', now())");
      $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort order of display.', 'MODULE_PAYMENT_PAYPAL_IPN_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
      $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Payment Zone', 'MODULE_PAYMENT_PAYPAL_IPN_ZONE', '0', 'If a zone is selected, only enable this payment method for that zone.', '6', '0', 'lc_cfg_use_get_zone_class_title', 'lc_cfg_set_zone_classes_pull_down_menu', now())");
      $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Set Order Status', 'MODULE_PAYMENT_PAYPAL_IPN_ORDER_STATUS_ID', '0', 'Set the status of orders made with this payment module to this value', '6', '0', 'lc_cfg_set_order_statuses_pull_down_menu', 'lc_cfg_use_get_order_status_title', now())");
    }

/**
 * Return the configuration parameter keys in an array
 *
 * @access public
 * @return array
 */

    function getKeys() {
      if (!isset($this->_keys)) {
        $this->_keys = array('MODULE_PAYMENT_PAYPAL_IPN_STATUS',
                             'MODULE_PAYMENT_PAYPAL_IPN_ID',
                             'MODULE_PAYMENT_PAYPAL_IPN_API_USERNAME',
                             'MODULE_PAYMENT_PAYPAL_IPN_API_PASSWORD',
                             'MODULE_PAYMENT_PAYPAL_IPN_API_CERTIFICATE',
                             'MODULE_PAYMENT_PAYPAL_IPN_EWP_STATUS',
                             'MODULE_PAYMENT_PAYPAL_IPN_EWP_PRIVATE_KEY',
                             'MODULE_PAYMENT_PAYPAL_IPN_EWP_PUBLIC_KEY',
                             'MODULE_PAYMENT_PAYPAL_IPN_EWP_PAYPAL_KEY',
                             'MODULE_PAYMENT_PAYPAL_IPN_EWP_CERT_ID',
                             'MODULE_PAYMENT_PAYPAL_IPN_EWP_OPENSSL',
                             'MODULE_PAYMENT_PAYPAL_IPN_SECRET_KEY',
                             'MODULE_PAYMENT_PAYPAL_IPN_CURRENCY',
                             'MODULE_PAYMENT_PAYPAL_IPN_SERVER',
                             'MODULE_PAYMENT_PAYPAL_IPN_ORDER_STATUS_ID',
                             'MODULE_PAYMENT_PAYPAL_IPN_ZONE',
                             'MODULE_PAYMENT_PAYPAL_IPN_SORT_ORDER');
      }

      return $this->_keys;
    }

/**
 * Returns the available post transaction actions in an array
 *
 * @access public
 * @param $history An array of transaction actions already processed
 * @return array
 */

    function getPostTransactionActions($history) {
      if ( (lc_empty(MODULE_PAYMENT_PAYPAL_IPN_API_USERNAME) === false) && (lc_empty(MODULE_PAYMENT_PAYPAL_IPN_API_PASSWORD) === false) && (lc_empty(MODULE_PAYMENT_PAYPAL_IPN_API_CERTIFICATE) === false) ) {
        $actions = array(4 => 'inquiryTransaction');

        if ( (in_array('2', $history) === false) && (in_array('3', $history) === false) ) {
          $actions[2] = 'cancelTransaction';
          $actions[3] = 'approveTransaction';
        }

        return $actions;
      }

      return false;
    }

/**
 * Approves the transaction at the gateway server
 *
 * @access public
 * @param $id The ID of the order
 */

    function approveTransaction($id) {
      global $lC_Database;

      $Qorder = $lC_Database->query('select transaction_return_value from :table_orders_transactions_history where orders_id = :orders_id and transaction_code = 1 order by date_added limit 1');
      $Qorder->bindTable(':table_orders_transactions_history', TABLE_ORDERS_TRANSACTIONS_HISTORY);
      $Qorder->bindInt(':orders_id', $id);
      $Qorder->execute();

      if ($Qorder->numberOfRows() === 1) {
        $lC_XML = new lC_XML($Qorder->value('transaction_return_value'));
        $result = $lC_XML->toArray();

        $string = '<?xml version="1.0" encoding="UTF-8"?>
                   <SOAP-ENV:Envelope xmlns:xsi="http://www.w3.org/1999/XMLSchema-instance" xmlns:SOAP-ENC="http://schemas.xmlsoap.org/soap/encoding/" xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsd="http://www.w3.org/1999/XMLSchema" SOAP-ENV:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/">
                     <SOAP-ENV:Header>
                       <RequesterCredentials xmlns="urn:ebay:api:PayPalAPI" SOAP-ENV:mustUnderstand="1">
                         <Credentials xmlns="urn:ebay:apis:eBLBaseComponents">
                           <Username>' . MODULE_PAYMENT_PAYPAL_IPN_API_USERNAME . '</Username>
                           <Password>' . MODULE_PAYMENT_PAYPAL_IPN_API_PASSWORD . '</Password>
                           <Subject/>
                         </Credentials>
                       </RequesterCredentials>
                     </SOAP-ENV:Header>
                     <SOAP-ENV:Body>
                       <DoCaptureReq xmlns="urn:ebay:api:PayPalAPI">
                         <DoCaptureRequest xmlns="urn:ebay:api:PayPalAPI">
                           <Version xmlns="urn:ebay:apis:eBLBaseComponents" xsi:type="xsd:string">1.0</Version>
                           <AuthorizationID>' . $result['root']['auth_id'] . '</AuthorizationID>
                           <Amount currencyID="' . $result['root']['mc_currency'] . '" xsi:type="cc:BasicAmountType">' . $result['root']['mc_gross'] . '</Amount>
                           <CompleteType>Complete</CompleteType>
                         </DoCaptureRequest>
                       </DoCaptureReq>
                     </SOAP-ENV:Body>
                   </SOAP-ENV:Envelope>';

        $result = $this->sendTransactionToGateway($this->_gateway_server, $string, '', 'post', MODULE_PAYMENT_PAYPAL_IPN_API_CERTIFICATE);

        if (empty($result) === false) {
          $lC_XML = new lC_XML($result);

// there is a PHP 5.1.2 XML root namespace bug; http://bugs.php.net/bug.php?id=37035
          $result = $lC_XML->toArray();

          if (isset($result['SOAP-ENV:Envelope']['SOAP-ENV:Body']['DoCaptureResponse'])) {
            $info = $result['SOAP-ENV:Envelope']['SOAP-ENV:Body']['DoCaptureResponse'];
            $result =& $info['DoCaptureResponseDetails'];

            if ($info['Ack'] == 'Success') {
              $data = array('root' => array('AuthorizationID' => $result['AuthorizationID'],
                                            'PaymentInfo' => array('TransactionID' => $result['PaymentInfo']['TransactionID'],
                                                                   'ParentTransactionID' => $result['PaymentInfo']['ParentTransactionID'],
                                                                   'ReceiptID' => $result['PaymentInfo']['ReceiptID'],
                                                                   'TransactionType' => $result['PaymentInfo']['TransactionType'],
                                                                   'PaymentType' => $result['PaymentInfo']['PaymentType'],
                                                                   'PaymentDate' => $result['PaymentInfo']['PaymentDate'],
                                                                   'GrossAmount' => $result['PaymentInfo']['GrossAmount'],
                                                                   'GrossAmountCurrencyID' => $result['PaymentInfo']['GrossAmount attr']['currencyID'],
                                                                   'FeeAmount' => $result['PaymentInfo']['FeeAmount'],
                                                                   'FeeAmountCurrencyID' => $result['PaymentInfo']['FeeAmount attr']['currencyID'],
                                                                   'TaxAmount' => $result['PaymentInfo']['TaxAmount'],
                                                                   'TaxAmountCurrencyID' => $result['PaymentInfo']['TaxAmount attr']['currencyID'],
                                                                   'ExchangeRate' => $result['PaymentInfo']['ExchangeRate'],
                                                                   'PaymentStatus' => $result['PaymentInfo']['PaymentStatus'],
                                                                   'PendingReason' => $result['PaymentInfo']['PendingReason'],
                                                                   'ReasonCode' => $result['PaymentInfo']['ReasonCode'])));
            } else {
              $data = array('root' => array('Ack' => $info['Ack'],
                                            'Errors' => array('ShortMessage' => $info['Errors']['ShortMessage'],
                                                              'LongMessage' => $info['Errors']['LongMessage'],
                                                              'ErrorCode' => $info['Errors']['ErrorCode'])));
            }

            $lC_XML = new lC_XML($data);

            $Qtransaction = $lC_Database->query('insert into :table_orders_transactions_history (orders_id, transaction_code, transaction_return_value, transaction_return_status, date_added) values (:orders_id, :transaction_code, :transaction_return_value, :transaction_return_status, now())');
            $Qtransaction->bindTable(':table_orders_transactions_history', TABLE_ORDERS_TRANSACTIONS_HISTORY);
            $Qtransaction->bindInt(':orders_id', $id);
            $Qtransaction->bindInt(':transaction_code', 3);
            $Qtransaction->bindValue(':transaction_return_value', $lC_XML->toXML());
            $Qtransaction->bindInt(':transaction_return_status', ($info['Ack'] == 'Success' ? 1 : 0));
            $Qtransaction->execute();
          }
        }
      }
    }

/**
 * Cancels the transaction at the gateway server
 *
 * @access public
 * @param $id The ID of the order
 */

    function cancelTransaction($id) {
      global $lC_Database;

      $Qorder = $lC_Database->query('select transaction_return_value from :table_orders_transactions_history where orders_id = :orders_id and transaction_code = 1 order by date_added limit 1');
      $Qorder->bindTable(':table_orders_transactions_history', TABLE_ORDERS_TRANSACTIONS_HISTORY);
      $Qorder->bindInt(':orders_id', $id);
      $Qorder->execute();

      if ($Qorder->numberOfRows() === 1) {
        $lC_XML = new lC_XML($Qorder->value('transaction_return_value'));
        $result = $lC_XML->toArray();

        $string = '<?xml version="1.0" encoding="UTF-8"?>
                   <SOAP-ENV:Envelope xmlns:xsi="http://www.w3.org/1999/XMLSchema-instance" xmlns:SOAP-ENC="http://schemas.xmlsoap.org/soap/encoding/" xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsd="http://www.w3.org/1999/XMLSchema" SOAP-ENV:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/">
                     <SOAP-ENV:Header>
                       <RequesterCredentials xmlns="urn:ebay:api:PayPalAPI" SOAP-ENV:mustUnderstand="1">
                         <Credentials xmlns="urn:ebay:apis:eBLBaseComponents">
                           <Username>' . MODULE_PAYMENT_PAYPAL_IPN_API_USERNAME . '</Username>
                           <Password>' . MODULE_PAYMENT_PAYPAL_IPN_API_PASSWORD . '</Password>
                           <Subject/>
                         </Credentials>
                       </RequesterCredentials>
                     </SOAP-ENV:Header>
                     <SOAP-ENV:Body>
                       <DoVoidReq xmlns="urn:ebay:api:PayPalAPI">
                         <DoVoidRequest xmlns="urn:ebay:api:PayPalAPI">
                           <Version xmlns="urn:ebay:apis:eBLBaseComponents" xsi:type="xsd:string">1.0</Version>
                           <AuthorizationID>' . $result['root']['auth_id'] . '</AuthorizationID>
                         </DoVoidRequest>
                       </DoVoidReq>
                     </SOAP-ENV:Body>
                   </SOAP-ENV:Envelope>';

        $result = $this->sendTransactionToGateway($this->_gateway_server, $string, '', 'post', MODULE_PAYMENT_PAYPAL_IPN_API_CERTIFICATE);

        if (empty($result) === false) {
          $lC_XML = new lC_XML($result);

// there is a PHP 5.1.2 XML root namespace bug; http://bugs.php.net/bug.php?id=37035
          $result = $lC_XML->toArray();

          if (isset($result['SOAP-ENV:Envelope']['SOAP-ENV:Body']['DoVoidResponse'])) {
            $result = $result['SOAP-ENV:Envelope']['SOAP-ENV:Body']['DoVoidResponse'];

            $data = array('root' => array('Ack' => $result['Ack']));

            if ($result['Ack'] != 'Success') {
              $data['root']['Errors'] = array('ShortMessage' => $result['Errors']['ShortMessage'],
                                              'LongMessage' => $result['Errors']['LongMessage'],
                                              'ErrorCode' => $result['Errors']['ErrorCode']);
            }

            $lC_XML = new lC_XML($data);

            $Qtransaction = $lC_Database->query('insert into :table_orders_transactions_history (orders_id, transaction_code, transaction_return_value, transaction_return_status, date_added) values (:orders_id, :transaction_code, :transaction_return_value, :transaction_return_status, now())');
            $Qtransaction->bindTable(':table_orders_transactions_history', TABLE_ORDERS_TRANSACTIONS_HISTORY);
            $Qtransaction->bindInt(':orders_id', $id);
            $Qtransaction->bindInt(':transaction_code', 2);
            $Qtransaction->bindValue(':transaction_return_value', $lC_XML->toXML());
            $Qtransaction->bindInt(':transaction_return_status', ($result['Ack'] == 'Success' ? 1 : 0));
            $Qtransaction->execute();
          }
        }
      }
    }

/**
 * Send a status enquiry of the transaction to the gateway server
 *
 * @access public
 * @param $id The ID of the order
 */

    function inquiryTransaction($id) {
      global $lC_Database;

      $Qorder = $lC_Database->query('select transaction_return_value from :table_orders_transactions_history where orders_id = :orders_id and transaction_code = 1 order by date_added limit 1');
      $Qorder->bindTable(':table_orders_transactions_history', TABLE_ORDERS_TRANSACTIONS_HISTORY);
      $Qorder->bindInt(':orders_id', $id);
      $Qorder->execute();

      if ($Qorder->numberOfRows() === 1) {
        $lC_XML = new lC_XML($Qorder->value('transaction_return_value'));
        $result = $lC_XML->toArray();

        $string = '<?xml version="1.0" encoding="UTF-8"?>
                  <SOAP-ENV:Envelope xmlns:xsi="http://www.w3.org/1999/XMLSchema-instance" xmlns:SOAP-ENC="http://schemas.xmlsoap.org/soap/encoding/" xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsd="http://www.w3.org/1999/XMLSchema" SOAP-ENV:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/">
                    <SOAP-ENV:Header>
                      <RequesterCredentials xmlns="urn:ebay:api:PayPalAPI" SOAP-ENV:mustUnderstand="1">
                        <Credentials xmlns="urn:ebay:apis:eBLBaseComponents">
                          <Username>' . MODULE_PAYMENT_PAYPAL_IPN_API_USERNAME . '</Username>
                          <Password>' . MODULE_PAYMENT_PAYPAL_IPN_API_PASSWORD . '</Password>
                          <Subject/>
                        </Credentials>
                      </RequesterCredentials>
                    </SOAP-ENV:Header>
                    <SOAP-ENV:Body>
                      <GetTransactionDetailsReq xmlns="urn:ebay:api:PayPalAPI">
                        <GetTransactionDetailsRequest xsi:type="ns:GetTransactionDetailsRequestType">
                          <Version xmlns="urn:ebay:apis:eBLBaseComponents" xsi:type="xsd:string">1.0</Version>
                          <TransactionID xsi:type="ebl:TransactionId">' . $result['root']['txn_id'] . '</TransactionID>
                        </GetTransactionDetailsRequest>
                      </GetTransactionDetailsReq>
                    </SOAP-ENV:Body>
                  </SOAP-ENV:Envelope>';

        $result = $this->sendTransactionToGateway($this->_gateway_server, $string, '', 'post', MODULE_PAYMENT_PAYPAL_IPN_API_CERTIFICATE);

        if (empty($result) === false) {
          $lC_XML = new lC_XML($result);

// there is a PHP 5.1.2 XML root namespace bug; http://bugs.php.net/bug.php?id=37035
          $result = $lC_XML->toArray();

          if (isset($result['SOAP-ENV:Envelope']['SOAP-ENV:Body']['GetTransactionDetailsResponse'])) {
            $info = $result['SOAP-ENV:Envelope']['SOAP-ENV:Body']['GetTransactionDetailsResponse'];
            $result =& $info['PaymentTransactionDetails'];

            if ($info['Ack'] == 'Success') {
              $data = array('root' => array('ReceiverInfo' => array('Business' => $result['ReceiverInfo']['Business'],
                                                                    'Receiver' => $result['ReceiverInfo']['Receiver'],
                                                                    'ReceiverID' => $result['ReceiverInfo']['ReceiverID']),
                                            'PayerInfo' => array('Payer' => $result['PayerInfo']['Payer'],
                                                                 'PayerID' => $result['PayerInfo']['PayerID'],
                                                                 'PayerStatus' => $result['PayerInfo']['PayerStatus'],
                                                                 'PayerName' => array('Salutation' => $result['PayerInfo']['PayerName']['Salutation'],
                                                                                      'FirstName' => $result['PayerInfo']['PayerName']['FirstName'],
                                                                                      'MiddleName' => $result['PayerInfo']['PayerName']['MiddleName'],
                                                                                      'LastName' => $result['PayerInfo']['PayerName']['LastName'],
                                                                                      'Suffix' => $result['PayerInfo']['PayerName']['Suffix']),
                                                                 'PayerCountry' => $result['PayerInfo']['PayerCountry'],
                                                                 'PayerBusiness' => $result['PayerInfo']['PayerBusiness'],
                                                                 'Address' => array('Name' => $result['PayerInfo']['Address']['Name'],
                                                                                    'Street1' => $result['PayerInfo']['Address']['Street1'],
                                                                                    'Street2' => $result['PayerInfo']['Address']['Street2'],
                                                                                    'CityName' => $result['PayerInfo']['Address']['CityName'],
                                                                                    'StateOrProvince' => $result['PayerInfo']['Address']['StateOrProvince'],
                                                                                    'Country' => $result['PayerInfo']['Address']['Country'],
                                                                                    'CountryName' => $result['PayerInfo']['Address']['CountryName'],
                                                                                    'PostalCode' => $result['PayerInfo']['Address']['PostalCode'],
                                                                                    'AddressOwner' => $result['PayerInfo']['Address']['AddressOwner'],
                                                                                    'AddressStatus' => $result['PayerInfo']['Address']['AddressStatus'])),
                                            'PaymentInfo' => array('TransactionID' => $result['PaymentInfo']['TransactionID'],
                                                                   'ParentTransactionID' => $result['PaymentInfo']['ParentTransactionID'],
                                                                   'ReceiptID' => $result['PaymentInfo']['ReceiptID'],
                                                                   'TransactionType' => $result['PaymentInfo']['TransactionType'],
                                                                   'PaymentType' => $result['PaymentInfo']['PaymentType'],
                                                                   'PaymentDate' => $result['PaymentInfo']['PaymentDate'],
                                                                   'GrossAmount' => $result['PaymentInfo']['GrossAmount'],
                                                                   'GrossAmountCurrencyID' => $result['PaymentInfo']['GrossAmount attr']['currencyID'],
                                                                   'TaxAmount' => $result['PaymentInfo']['TaxAmount'],
                                                                   'TaxAmountCurrencyID' => $result['PaymentInfo']['TaxAmount attr']['currencyID'],
                                                                   'ExchangeRate' => $result['PaymentInfo']['ExchangeRate'],
                                                                   'PaymentStatus' => $result['PaymentInfo']['PaymentStatus'],
                                                                   'PendingReason' => $result['PaymentInfo']['PendingReason'],
                                                                   'ReasonCode' => $result['PaymentInfo']['ReasonCode']),
                                            'PaymentItemInfo' => array('InvoiceID' => $result['PaymentItemInfo']['InvoiceID'],
                                                                       'Custom' => $result['PaymentItemInfo']['Custom'],
                                                                       'Memo' => $result['PaymentItemInfo']['Memo'],
                                                                       'SalesTax' => $result['PaymentItemInfo']['SalesTax'],
                                                                       'PaymentItem' => array('Name' => $result['PaymentItemInfo']['PaymentItem']['Name'],
                                                                                              'Number' => $result['PaymentItemInfo']['PaymentItem']['Number'],
                                                                                              'Quantity' => $result['PaymentItemInfo']['PaymentItem']['Quantity'],
                                                                                              'SalesTax' => $result['PaymentItemInfo']['PaymentItem']['SalesTax']),
                                                                       'Subscription' => array('SubscriptionID' => $result['PaymentItemInfo']['Subscription']['SubscriptionID'],
                                                                                               'Username' => $result['PaymentItemInfo']['Subscription']['Username'],
                                                                                               'Password' => $result['PaymentItemInfo']['Subscription']['Password'],
                                                                                               'Recurrences' => $result['PaymentItemInfo']['Subscription']['Recurrences']),
                                                                       'SubscriptionRecurring' => $result['PaymentItemInfo']['Subscription attr']['recurring'],
                                                                       'SubscriptionReattempt' => $result['PaymentItemInfo']['Subscription attr']['reattempt'],
                                                                       'Auction' => array('BuyerID' => $result['PaymentItemInfo']['Auction']['BuyerID']))));
            } else {
              $data = array('root' => array('Ack' => $info['Ack'],
                                            'Errors' => array('ShortMessage' => $info['Errors']['ShortMessage'],
                                                              'LongMessage' => $info['Errors']['LongMessage'],
                                                              'ErrorCode' => $info['Errors']['ErrorCode'])));
            }

            $lC_XML = new lC_XML($data);

            $Qtransaction = $lC_Database->query('insert into :table_orders_transactions_history (orders_id, transaction_code, transaction_return_value, transaction_return_status, date_added) values (:orders_id, :transaction_code, :transaction_return_value, :transaction_return_status, now())');
            $Qtransaction->bindTable(':table_orders_transactions_history', TABLE_ORDERS_TRANSACTIONS_HISTORY);
            $Qtransaction->bindInt(':orders_id', $id);
            $Qtransaction->bindInt(':transaction_code', 4);
            $Qtransaction->bindValue(':transaction_return_value', $lC_XML->toXML());
            $Qtransaction->bindInt(':transaction_return_status', ($info['Ack'] == 'Success' ? 1 : 0));
            $Qtransaction->execute();
          }
        }
      }
    }
  }
?>