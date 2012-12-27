<?php
/*
  $Id: $

  LoadedCommerce, Open Source E-Commerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2006 LoadedCommerce

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/

  class lC_Payment_paypal_ipn extends lC_Payment {
    var $_title,
        $_code = 'paypal_ipn',
        $_status = false,
        $_sort_order,
        $_order_id,
        $_transaction_response;

    function lC_Payment_paypal_ipn() {
      global $lC_Database, $lC_Language, $lC_ShoppingCart;

      $this->_title = $lC_Language->get('payment_paypal_ipn_title');
      $this->_method_title = $lC_Language->get('payment_paypal_ipn_method_title');
      $this->_status = (MODULE_PAYMENT_PAYPAL_IPN_STATUS == '1') ? true : false;
      $this->_sort_order = MODULE_PAYMENT_PAYPAL_IPN_SORT_ORDER;

      switch (MODULE_PAYMENT_PAYPAL_IPN_SERVER) {
        case 'Production':
          $this->form_action_url = 'https://www.paypal.com/cgi-bin/webscr';
          break;

        default:
          $this->form_action_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
          break;
      }

      if ($this->_status === true) {
        if ((int)MODULE_PAYMENT_PAYPAL_IPN_ORDER_STATUS_ID > 0) {
          $this->order_status = MODULE_PAYMENT_PAYPAL_IPN_ORDER_STATUS_ID;
        }

        if ((int)MODULE_PAYMENT_PAYPAL_IPN_ZONE > 0) {
          $check_flag = false;

          $Qcheck = $lC_Database->query('select zone_id from :table_zones_to_geo_zones where geo_zone_id = :geo_zone_id and zone_country_id = :zone_country_id order by zone_id');
          $Qcheck->bindTable(':table_zones_to_geo_zones', TABLE_ZONES_TO_GEO_ZONES);
          $Qcheck->bindInt(':geo_zone_id', MODULE_PAYMENT_PAYPAL_IPN_ZONE);
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

          if ($check_flag == false) {
            $this->_status = false;
          }
        }
      }
    }

    function selection() {
      return array('id' => $this->_code,
                   'module' => $this->_method_title);
    }

    function confirmation() {
      $this->_order_id = lC_Order::insert();
    }

    function process_button() {
      global $lC_Customer, $lC_Currencies, $lC_ShoppingCart;

      if (MODULE_PAYMENT_PAYPAL_IPN_CURRENCY == 'Selected Currency') {
        $currency = $lC_Currencies->getCode();
      } else {
        $currency = MODULE_PAYMENT_PAYPAL_IPN_CURRENCY;
      }

      if (in_array($currency, array('CAD', 'EUR', 'GBP', 'JPY', 'USD')) === false) {
        $currency = DEFAULT_CURRENCY;
      }

      $params = array('cmd' => '_ext-enter',
                      'redirect_cmd' => '_xclick',
                      'business' => MODULE_PAYMENT_PAYPAL_IPN_ID,
                      'item_name' => STORE_NAME,
                      'amount' => $lC_Currencies->formatRaw($lC_ShoppingCart->getTotal() - $lC_ShoppingCart->getShippingMethod('cost'), $currency),
                      'first_name' => $lC_ShoppingCart->getBillingAddress('firstname'),
                      'last_name' => $lC_ShoppingCart->getBillingAddress('lastname'),
                      'address1' => $lC_ShoppingCart->getBillingAddress('street_address'),
                      'address2' => $lC_ShoppingCart->getBillingAddress('suburb'),
                      'city' => $lC_ShoppingCart->getBillingAddress('city'),
                      'zip' => $lC_ShoppingCart->getBillingAddress('postcode'),
                      'country' => $lC_ShoppingCart->getBillingAddress('country_iso_code_2'),
                      'address_override' => '1',
                      'notify_url' => lc_href_link(FILENAME_CHECKOUT, 'callback&module=' . $this->_code . (!lc_empty(MODULE_PAYMENT_PAYPAL_IPN_SECRET_KEY) ? '&secret=' . MODULE_PAYMENT_PAYPAL_IPN_SECRET_KEY : ''), 'SSL', false, false, true),
                      'email' => $lC_Customer->getEmailAddress(),
                      'invoice' => $this->_order_id,
                      'shipping' => $lC_Currencies->formatRaw($lC_ShoppingCart->getShippingMethod('cost'), $currency),
                      'currency_code' => $currency,
                      'lc' => 'EN', //AU, DE, FR, IT, GB, ES, US
                      'return' => lc_href_link(FILENAME_CHECKOUT, 'process', 'SSL', null, null, true),
                      'rm' => '2',
                      'no_note' => '1',
                      'cancel_return' => lc_href_link(FILENAME_CHECKOUT, 'payment', 'SSL', null, null, true),
                      'paymentaction' => 'authorization');

      if ($lC_ShoppingCart->getBillingAddress('country_iso_code_2') == 'US') {
        $params['state'] = $lC_ShoppingCart->getBillingAddress('zone_code');
      }

      if (MODULE_PAYMENT_PAYPAL_IPN_EWP_STATUS == '1') {
        $params['cert_id'] = MODULE_PAYMENT_PAYPAL_IPN_EWP_CERT_ID;

        $random_string = $lC_Customer->getID() . '-' . time() . '-' . lc_create_random_string(5) . '-';

        $data = '';
        foreach ($params as $key => $value) {
          $data .= $key . '=' . $value . "\n";
        }

        $fp = fopen(DIR_FS_WORK . $random_string . 'data.txt', 'w');
        fwrite($fp, $data);
        fclose($fp);

        unset($data);
        unset($fp);

        if (function_exists('openssl_pkcs7_sign') && function_exists('openssl_pkcs7_encrypt')) {
          openssl_pkcs7_sign(DIR_FS_WORK . $random_string . 'data.txt', DIR_FS_WORK . $random_string . 'signed.txt', file_get_contents(MODULE_PAYMENT_PAYPAL_IPN_EWP_PUBLIC_KEY), file_get_contents(MODULE_PAYMENT_PAYPAL_IPN_EWP_PRIVATE_KEY), array('From' => MODULE_PAYMENT_PAYPAL_IPN_ID), PKCS7_BINARY);

          unlink(DIR_FS_WORK . $random_string . 'data.txt');

// remove headers from the signature
          $signed = file_get_contents(DIR_FS_WORK . $random_string . 'signed.txt');
          $signed = explode("\n\n", $signed);
          $signed = base64_decode($signed[1]);

          $fp = fopen(DIR_FS_WORK . $random_string . 'signed.txt', 'w');
          fwrite($fp, $signed);
          fclose($fp);

          unset($signed);
          unset($fp);

          openssl_pkcs7_encrypt(DIR_FS_WORK . $random_string . 'signed.txt', DIR_FS_WORK . $random_string . 'encrypted.txt', file_get_contents(MODULE_PAYMENT_PAYPAL_IPN_EWP_PAYPAL_KEY), array('From' => MODULE_PAYMENT_PAYPAL_IPN_ID), PKCS7_BINARY);

          unlink(DIR_FS_WORK . $random_string . 'signed.txt');

// remove headers from the encrypted result
          $data = file_get_contents(DIR_FS_WORK . $random_string . 'encrypted.txt');
          $data = explode("\n\n", $data);
          $data = '-----BEGIN PKCS7-----' . "\n" . $data[1] . "\n" . '-----END PKCS7-----';

          unlink(DIR_FS_WORK . $random_string . 'encrypted.txt');
        } else {
          exec(MODULE_PAYMENT_PAYPAL_IPN_EWP_OPENSSL . ' smime -sign -in ' . DIR_FS_WORK . $random_string . 'data.txt -signer ' . MODULE_PAYMENT_PAYPAL_IPN_EWP_PUBLIC_KEY . ' -inkey ' . MODULE_PAYMENT_PAYPAL_IPN_EWP_PRIVATE_KEY . ' -outform der -nodetach -binary > ' . DIR_FS_WORK . $random_string . 'signed.txt');
          unlink(DIR_FS_WORK . $random_string . 'data.txt');

          exec(MODULE_PAYMENT_PAYPAL_IPN_EWP_OPENSSL . ' smime -encrypt -des3 -binary -outform pem ' . MODULE_PAYMENT_PAYPAL_IPN_EWP_PAYPAL_KEY . ' < ' . DIR_FS_WORK . $random_string . 'signed.txt > ' . DIR_FS_WORK . $random_string . 'encrypted.txt');
          unlink(DIR_FS_WORK . $random_string . 'signed.txt');

          $fp = fopen(DIR_FS_WORK . $random_string . 'encrypted.txt', 'rb');
          $data = fread($fp, filesize(DIR_FS_WORK . $random_string . 'encrypted.txt'));
          fclose($fp);

          unset($fp);

          unlink(DIR_FS_WORK . $random_string . 'encrypted.txt');
        }

        $process_button_string = lc_draw_hidden_field('cmd', '_s-xclick') .
                                 lc_draw_hidden_field('encrypted', $data);

        unset($data);
      } else {
        $process_button_string = '';

        foreach ($params as $key => $value) {
          $process_button_string .= lc_draw_hidden_field($key, $value);
        }
      }

      return $process_button_string;
    }

    function process() {
      if (isset($_POST['invoice']) && is_numeric($_POST['invoice']) && isset($_POST['receiver_email']) && ($_POST['receiver_email'] == MODULE_PAYMENT_PAYPAL_IPN_ID) && isset($_POST['verify_sign']) && (empty($_POST['verify_sign']) === false) && isset($_POST['txn_id']) && (empty($_POST['txn_id']) === false)) {
        unset($_SESSION['prepOrderID']);
      }
    }

    function callback() {
      global $lC_Database;

      if (isset($_POST['invoice']) && is_numeric($_POST['invoice']) && isset($_POST['receiver_email']) && ($_POST['receiver_email'] == MODULE_PAYMENT_PAYPAL_IPN_ID) && isset($_POST['verify_sign']) && (empty($_POST['verify_sign']) === false) && isset($_POST['txn_id']) && (empty($_POST['txn_id']) === false)) {
        if (!lc_empty(MODULE_PAYMENT_PAYPAL_IPN_SECRET_KEY)) {
          if (isset($_GET['secret']) && ($_GET['secret'] == MODULE_PAYMENT_PAYPAL_IPN_SECRET_KEY)) {
            $pass = true;
          } else {
            $pass = false;
          }
        } else {
          $pass = true;
        }

        if ( ($pass === true) && (lC_Order::getStatusID($_POST['invoice']) === 4) ) {
          $post_string = 'cmd=_notify-validate&';

          foreach ($_POST as $key => $value) {
            $post_string .= $key . '=' . urlencode($value) . '&';
          }

          $post_string = substr($post_string, 0, -1);

          $this->_transaction_response = $this->sendTransactionToGateway($this->form_action_url, $post_string);

          $post_array = array('root' => $_POST);
          $post_array['root']['transaction_response'] = trim($this->_transaction_response);
          $lC_XML = new lC_XML($post_array);

          if (strtoupper(trim($this->_transaction_response)) == 'VERIFIED') {
            lC_Order::process($_POST['invoice'], $this->order_status);
          }

          $Qtransaction = $lC_Database->query('insert into :table_orders_transactions_history (orders_id, transaction_code, transaction_return_value, transaction_return_status, date_added) values (:orders_id, :transaction_code, :transaction_return_value, :transaction_return_status, now())');
          $Qtransaction->bindTable(':table_orders_transactions_history', TABLE_ORDERS_TRANSACTIONS_HISTORY);
          $Qtransaction->bindInt(':orders_id', $_POST['invoice']);
          $Qtransaction->bindInt(':transaction_code', 1);
          $Qtransaction->bindValue(':transaction_return_value', $lC_XML->toXML());
          $Qtransaction->bindInt(':transaction_return_status', (strtoupper(trim($this->_transaction_response)) == 'VERIFIED') ? 1 : 0);
          $Qtransaction->execute();
        }
      }
    }
  }
?>
