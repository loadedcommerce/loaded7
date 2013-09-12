<?php
/*
  $Id: order.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

  class lC_Order {
    // private variables
    var $_valid_order;

    // class constructor
    function lC_Order($order_id = '') {
      $this->_valid_order = false;

      if (is_numeric($order_id)) {
        $this->_getSummary($order_id);
      }
    }

    // private methods
    function _getSummary($order_id) {
      global $lC_Database;

      $Qorder = $lC_Database->query('select * from :table_orders where orders_id = :orders_id');
      $Qorder->bindTable(':table_orders', TABLE_ORDERS);
      $Qorder->bindInt(':orders_id', $order_id);
      $Qorder->execute();

      if ($Qorder->numberOfRows() === 1) {
        $this->_valid_order = true;

        $this->_order_id = $Qorder->valueInt('orders_id');

        $this->_customer = array('id' => $Qorder->value('customers_id'),
                                 'name' => $Qorder->valueProtected('customers_name'),
                                 'company' => $Qorder->valueProtected('customers_company'),
                                 'street_address' => $Qorder->valueProtected('customers_street_address'),
                                 'suburb' => $Qorder->valueProtected('customers_suburb'),
                                 'city' => $Qorder->valueProtected('customers_city'),
                                 'postcode' => $Qorder->valueProtected('customers_postcode'),
                                 'state' => $Qorder->valueProtected('customers_state'),
                                 'zone_code' => $Qorder->value('customers_state_code'),
                                 'country_title' => $Qorder->value('customers_country'),
                                 'country_iso2' => $Qorder->value('customers_country_iso2'),
                                 'country_iso3' => $Qorder->value('customers_country_iso3'),
                                 'format' => $Qorder->value('customers_address_format'),
                                 'telephone' => $Qorder->valueProtected('customers_telephone'),
                                 'email_address' => $Qorder->valueProtected('customers_email_address'));

        $this->_delivery = array('name' => $Qorder->valueProtected('delivery_name'),
                                 'company' => $Qorder->valueProtected('delivery_company'),
                                 'street_address' => $Qorder->valueProtected('delivery_street_address'),
                                 'suburb' => $Qorder->valueProtected('delivery_suburb'),
                                 'city' => $Qorder->valueProtected('delivery_city'),
                                 'postcode' => $Qorder->valueProtected('delivery_postcode'),
                                 'state' => $Qorder->valueProtected('delivery_state'),
                                 'zone_code' => $Qorder->value('delivery_state_code'),
                                 'country_title' => $Qorder->value('delivery_country'),
                                 'country_iso2' => $Qorder->value('delivery_country_iso2'),
                                 'country_iso3' => $Qorder->value('delivery_country_iso3'),
                                 'format' => $Qorder->value('delivery_address_format'));

        $this->_billing = array('name' => $Qorder->valueProtected('billing_name'),
                                'company' => $Qorder->valueProtected('billing_company'),
                                'street_address' => $Qorder->valueProtected('billing_street_address'),
                                'suburb' => $Qorder->valueProtected('billing_suburb'),
                                'city' => $Qorder->valueProtected('billing_city'),
                                'postcode' => $Qorder->valueProtected('billing_postcode'),
                                'state' => $Qorder->valueProtected('billing_state'),
                                'zone_code' => $Qorder->value('billing_state_code'),
                                'country_title' => $Qorder->value('billing_country'),
                                'country_iso2' => $Qorder->value('billing_country_iso2'),
                                'country_iso3' => $Qorder->value('billing_country_iso3'),
                                'format' => $Qorder->value('billing_address_format'));

        $this->_payment_method = $Qorder->value('payment_method');
        $this->_payment_module = $Qorder->value('payment_module');

        $this->_currency = array('code' => $Qorder->value('currency'),
                                 'value' => $Qorder->value('currency_value'));

        $this->_date_purchased = $Qorder->value('date_purchased');
        $this->_last_modified = $Qorder->value('last_modified');

        $this->_status_id = $Qorder->value('orders_status');
      }
    }

    function _getStatus() {
      global $lC_Database, $lC_Language;

      $Qstatus = $lC_Database->query('select orders_status_name from :table_orders_status where orders_status_id = :orders_status_id and language_id = :language_id');
      $Qstatus->bindTable(':table_orders_status', TABLE_ORDERS_STATUS);
      $Qstatus->bindInt(':orders_status_id', $this->_status_id);

      /* DEFAULT_LANGUAGE is the language code, not the language id */
      // $Qstatus->bindInt(':language_id', (isset($_SESSION['languages_id']) ? $_SESSION['languages_id'] : DEFAULT_LANGUAGE));
      $Qstatus->bindInt(':language_id', $lC_Language->getID());
      $Qstatus->execute();

      if ($Qstatus->numberOfRows() === 1) {
        $this->_status = $Qstatus->value('orders_status_name');
      } else {
        $this->_status = $this->_status_id;
      }
    }

    function _getStatusHistory() {
      global $lC_Database, $lC_Language;

      $history_array = array();

      $Qhistory = $lC_Database->query('select osh.orders_status_id, osh.date_added, osh.customer_notified, osh.comments, os.orders_status_name from :table_orders_status_history osh left join :table_orders_status os on (osh.orders_status_id = os.orders_status_id and os.language_id = :language_id) where osh.orders_id = :orders_id order by osh.date_added');
      $Qhistory->bindTable(':table_orders_status_history', TABLE_ORDERS_STATUS_HISTORY);
      $Qhistory->bindTable(':table_orders_status', TABLE_ORDERS_STATUS);

       /* DEFAULT_LANGUAGE is the language code, not the language id */
       // $Qstatus->bindInt(':language_id', (isset($_SESSION['languages_id']) ? $_SESSION['languages_id'] : DEFAULT_LANGUAGE));
      $Qhistory->bindInt(':language_id', $lC_Language->getID());

      $Qhistory->bindInt(':orders_id', $this->_order_id);
      $Qhistory->execute();

      while ($Qhistory->next()) {
        $history_array[] = array('status_id' => $Qhistory->valueInt('orders_status_id'),
                                 'status' => $Qhistory->value('orders_status_name'),
                                 'date_added' => $Qhistory->value('date_added'),
                                 'customer_notified' => $Qhistory->valueInt('customer_notified'),
                                 'comment' => $Qhistory->valueProtected('comments'));
      }

      $this->_status_history = $history_array;
    }

    function _getTransactionHistory() {
      global $lC_Database, $lC_Language;

      $this->_transaction_history = array();

      $Qhistory = $lC_Database->query('select oth.transaction_code, oth.transaction_return_value, oth.transaction_return_status, oth.date_added, ots.status_name from :table_orders_transactions_history oth left join :table_orders_transactions_status ots on (oth.transaction_code = ots.id and ots.language_id = :language_id) where oth.orders_id = :orders_id order by oth.date_added');
      $Qhistory->bindTable(':table_orders_transactions_history', TABLE_ORDERS_TRANSACTIONS_HISTORY);
      $Qhistory->bindTable(':table_orders_transactions_status', TABLE_ORDERS_TRANSACTIONS_STATUS);
      $Qhistory->bindInt(':language_id', $lC_Language->getID());
      $Qhistory->bindInt(':orders_id', $this->_order_id);
      $Qhistory->execute();

      while ($Qhistory->next()) {
        $this->_transaction_history[] = array('status_id' => $Qhistory->valueInt('transaction_code'),
                                              'status' => $Qhistory->value('status_name'),
                                              'return_value' => $Qhistory->valueProtected('transaction_return_value'),
                                              'return_status' => $Qhistory->valueInt('transaction_return_status'),
                                              'date_added' => $Qhistory->value('date_added'));
      }
    }

    function _getPostTransactionActions() {
      global $lC_Database, $lC_Language, $lC_Vqmod;

      $this->_post_transaction_actions = array();

      if (file_exists('includes/modules/payment/' . $this->_payment_module . '.php')) {
        include($lC_Vqmod->modCheck('includes/classes/payment.php'));
        include($lC_Vqmod->modCheck('includes/modules/payment/' . $this->_payment_module . '.php'));

        if (call_user_func(array('lC_Payment_' . $this->_payment_module, 'isInstalled')) === true) {
          $trans_array = array();

          foreach ($this->getTransactionHistory() as $history) {
            if ($history['return_status'] === 1) {
              $trans_array[] = $history['status_id'];
            }
          }

          $transactions = call_user_func(array('lC_Payment_' . $this->_payment_module, 'getPostTransactionActions'), $trans_array);

          if (is_array($transactions) && (empty($transactions) === false)) {
            $Qactions = $lC_Database->query('select id, status_name from :table_orders_transactions_status where language_id = :language_id and id in :id order by status_name');
            $Qactions->bindTable(':table_orders_transactions_status', TABLE_ORDERS_TRANSACTIONS_STATUS);
            $Qactions->bindInt(':language_id', $lC_Language->getID());
            $Qactions->bindRaw(':id', '(' . implode(', ', array_keys($transactions)) . ')');
            $Qactions->execute();

            $trans_code_array = array();

            while ($Qactions->next()) {
              $this->_post_transaction_actions[] = array('id' => $transactions[$Qactions->valueInt('id')],
                                                         'text' => $Qactions->value('status_name'));
            }
          }
        }
      }
    }

    function _getProducts() {
      global $lC_Database;

      $products_array = array();
      $key = 0;

      $Qproducts = $lC_Database->query('select orders_products_id, products_name, products_model, products_price, products_tax, products_quantity, products_simple_options_meta_data from :table_orders_products where orders_id = :orders_id');
      $Qproducts->bindTable(':table_orders_products', TABLE_ORDERS_PRODUCTS);
      $Qproducts->bindInt(':orders_id', $this->_order_id);
      $Qproducts->execute();

      while ($Qproducts->next()) {
        $products_array[$key] = array('quantity' => $Qproducts->valueInt('products_quantity'),
                                      'name' => $Qproducts->value('products_name'),
                                      'model' => $Qproducts->value('products_model'),
                                      'tax' => $Qproducts->value('products_tax'),
                                      'price' => $Qproducts->value('products_price'),
                                      'options' => unserialize($Qproducts->value('products_simple_options_meta_data')));

        $Qvariants = $lC_Database->query('select group_title, value_title from :table_orders_products_variants where orders_id = :orders_id and orders_products_id = :orders_products_id order by id');
        $Qvariants->bindTable(':table_orders_products_variants', TABLE_ORDERS_PRODUCTS_VARIANTS);
        $Qvariants->bindInt(':orders_id', $this->_order_id);
        $Qvariants->bindInt(':orders_products_id', $Qproducts->valueInt('orders_products_id'));
        $Qvariants->execute();

        if ( $Qvariants->numberOfRows() > 0 ) {
          while ( $Qvariants->next() ) {
            $products_array[$key]['attributes'][] = array('option' => $Qvariants->value('group_title'),
                                                          'value' => $Qvariants->value('value_title'));
          }
        }

        $key++;
      }

      $this->_products = $products_array;
    }

    function _getTotals() {
      global $lC_Database;

      $totals_array = array();

      $Qtotals = $lC_Database->query('select title, text, value, class from :table_orders_total where orders_id = :orders_id order by sort_order');
      $Qtotals->bindTable(':table_orders_total', TABLE_ORDERS_TOTAL);
      $Qtotals->bindInt(':orders_id', $this->_order_id);
      $Qtotals->execute();

      while ($Qtotals->next()) {
        $totals_array[] = array('title' => $Qtotals->value('title'),
                                'text' => $Qtotals->value('text'),
                                'value' => $Qtotals->value('value'),
                                'class' => $Qtotals->value('class'));
      }

      $this->_totals = $totals_array;
    }

    // public methods
    function isValid() {
      if ($this->_valid_order === true) {
        return true;
      } else {
        return false;
      }
    }

    function getOrderID() {
      return $this->_order_id;
    }

    function getCustomer($id = '') {
      if (empty($id)) {
        return $this->_customer;
      } elseif (isset($this->_customer[$id])) {
        return $this->_customer[$id];
      }

      return false;
    }

    function getDelivery($id = '') {
      if (empty($id)) {
        return $this->_delivery;
      } elseif (isset($this->_delivery[$id])) {
        return $this->_delivery[$id];
      }

      return false;
    }

    function getBilling($id = '') {
      if (empty($id)) {
        return $this->_billing;
      } elseif (isset($this->_billing[$id])) {
        return $this->_billing[$id];
      }

      return false;
    }

    function getPaymentMethod() {
      return $this->_payment_method;
    }

    function getPaymentModule() {
      return $this->_payment_module;
    }

    function getCreditCardDetails($id = '') {
      if (empty($id)) {
        return $this->_credit_card;
      } elseif (isset($this->_credit_card[$id])) {
        return $this->_credit_card[$id];
      }

      return false;
    }

    function isValidCreditCard() {
      if (!empty($this->_credit_card['owner']) && !empty($this->_credit_card['number']) && !empty($this->_credit_card['expires'])) {
        return true;
      }

      return false;
    }

    function getCurrency($id = 'code') {
      if (isset($this->_currency[$id])) {
        return $this->_currency[$id];
      }

      return false;
    }

    function getCurrencyValue() {
      return $this->getCurrency('value');
    }

    function getDateCreated() {
      return $this->_date_purchased;
    }

    function getDateLastModified() {
      return $this->_last_modified;
    }

    function getStatusID() {
      return $this->_status_id;
    }

    function getStatus() {
      if (!isset($this->_status)) {
        $this->_getStatus();
      }

      return $this->_status;
    }

    function getNumberOfComments() {
      $number_of_comments = 0;

      if (!isset($this->_status_history)) {
        $this->_getStatusHistory();
      }

      foreach ($this->_status_history as $status_history) {
        if (!empty($status_history['comment'])) {
          $number_of_comments++;
        }
      }

      return $number_of_comments;
    }

    function getProducts() {
      if (!isset($this->_products)) {
        $this->_getProducts();
      }

      return $this->_products;
    }

    function getNumberOfProducts() {
      if (!isset($this->_products)) {
        $this->_getProducts();
      }

      return sizeof($this->_products);
    }

    function getNumberOfItems() {
      $number_of_items = 0;

      if (!isset($this->_products)) {
        $this->_getProducts();
      }

      foreach ($this->_products as $product) {
        $number_of_items += $product['quantity'];
      }

      return $number_of_items;
    }

    function getTotal($id = 'total') {
      if (!isset($this->_totals)) {
        $this->_getTotals();
      }

      foreach ($this->_totals as $total) {
        if ($total['class'] == $id) {
          return strip_tags($total['text']);
        }
      }

      return false;
    }

    function getTotals() {
      if (!isset($this->_totals)) {
        $this->_getTotals();
      }

      return $this->_totals;
    }

    function getStatusHistory() {
      if (!isset($this->_status_history)) {
        $this->_getStatusHistory();
      }

      return $this->_status_history;
    }

    function getTransactionHistory() {
      if (!isset($this->_transaction_history)) {
        $this->_getTransactionHistory();
      }

      return $this->_transaction_history;
    }

    function getPostTransactionActions() {
      if (!isset($this->_post_transaction_actions)) {
        $this->_getPostTransactionActions();
      }

      return $this->_post_transaction_actions;
    }

    function hasPostTransactionActions() {
      if (!isset($this->_post_transaction_actions)) {
        $this->_getPostTransactionActions();
      }

      return !empty($this->_post_transaction_actions);
    }

    function delete($id, $restock = false) {
      global $lC_Database;

      $error = false;

      $lC_Database->startTransaction();

      if ($restock === true) {
        $Qproducts = $lC_Database->query('select products_id, products_quantity from :table_orders_products where orders_id = :orders_id');
        $Qproducts->bindTable(':table_orders_products', TABLE_ORDERS_PRODUCTS);
        $Qproducts->bindInt(':orders_id', $id);
        $Qproducts->execute();

        while ($Qproducts->next()) {
          $Qupdate = $lC_Database->query('update :table_products set products_quantity = products_quantity + :products_quantity, products_ordered = products_ordered - :products_ordered where products_id = :products_id');
          $Qupdate->bindTable(':table_products', TABLE_PRODUCTS);
          $Qupdate->bindInt(':products_quantity', $Qproducts->valueInt('products_quantity'));
          $Qupdate->bindInt(':products_ordered', $Qproducts->valueInt('products_quantity'));
          $Qupdate->bindInt(':products_id', $Qproducts->valueInt('products_id'));
          $Qupdate->setLogging($_SESSION['module'], $id);
          $Qupdate->execute();

          if ($lC_Database->isError() === true) {
            $error = true;
            break;
          }

          $Qcheck = $lC_Database->query('select products_quantity from :table_products where products_id = :products_id and products_Status = 0');
          $Qcheck->bindTable(':table_products', TABLE_PRODUCTS);
          $Qcheck->bindInt(':products_id', $Qproducts->valueInt('products_id'));
          $Qcheck->execute();

          if (($Qcheck->numberOfRows() === 1) && ($Qcheck->valueInt('products_quantity') > 0)) {
            $Qstatus = $lC_Database->query('update :table_products set products_status = 1 where products_id = :products_id');
            $Qstatus->bindTable(':table_products', TABLE_PRODUCTS);
            $Qstatus->bindInt(':products_id', $Qproducts->valueInt('products_id'));
            $Qstatus->setLogging($_SESSION['module'], $id);
            $Qstatus->execute();

            if ($lC_Database->isError() === true) {
              $error = true;
              break;
            }
          }
        }
      }

      if ($error === false) {
        $Qvariants = $lC_Database->query('delete from :table_orders_products_variants where orders_id = :orders_id');
        $Qvariants->bindTable(':table_orders_products_variants', TABLE_ORDERS_PRODUCTS_VARIANTS);
        $Qvariants->bindInt(':orders_id', $id);
        $Qvariants->setLogging($_SESSION['module'], $id);
        $Qvariants->execute();

        if ($lC_Database->isError() === true) {
          $error = true;
        }
      }

      if ($error === false) {
        $Qop = $lC_Database->query('delete from :table_orders_products where orders_id = :orders_id');
        $Qop->bindTable(':table_orders_products', TABLE_ORDERS_PRODUCTS);
        $Qop->bindInt(':orders_id', $id);
        $Qop->setLogging($_SESSION['module'], $id);
        $Qop->execute();

        if ($lC_Database->isError() === true) {
          $error = true;
        }
      }

      if ($error === false) {
        $Qosh = $lC_Database->query('delete from :table_orders_transactions_history where orders_id = :orders_id');
        $Qosh->bindTable(':table_orders_transactions_history', TABLE_ORDERS_TRANSACTIONS_HISTORY);
        $Qosh->bindInt(':orders_id', $id);
        $Qosh->setLogging($_SESSION['module'], $id);
        $Qosh->execute();

        if ($lC_Database->isError() === true) {
          $error = true;
        }
      }

      if ($error === false) {
        $Qosh = $lC_Database->query('delete from :table_orders_status_history where orders_id = :orders_id');
        $Qosh->bindTable(':table_orders_status_history', TABLE_ORDERS_STATUS_HISTORY);
        $Qosh->bindInt(':orders_id', $id);
        $Qosh->setLogging($_SESSION['module'], $id);
        $Qosh->execute();

        if ($lC_Database->isError() === true) {
          $error = true;
        }
      }

      if ($error === false) {
        $Qot = $lC_Database->query('delete from :table_orders_total where orders_id = :orders_id');
        $Qot->bindTable(':table_orders_total', TABLE_ORDERS_TOTAL);
        $Qot->bindInt(':orders_id', $id);
        $Qot->setLogging($_SESSION['module'], $id);
        $Qot->execute();

        if ($lC_Database->isError() === true) {
          $error = true;
        }
      }

      if ($error === false) {
        $Qo = $lC_Database->query('delete from :table_orders where orders_id = :orders_id');
        $Qo->bindTable(':table_orders', TABLE_ORDERS);
        $Qo->bindInt(':orders_id', $id);
        $Qo->setLogging($_SESSION['module'], $id);
        $Qo->execute();

        if ($lC_Database->isError() === true) {
          $error = true;
        }
      }

      if ($error === false) {
        $lC_Database->commitTransaction();

        return true;
      } else {
        $lC_Database->rollbackTransaction();

        return false;
      }
    }
  }
?>