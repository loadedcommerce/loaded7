<?php
/**
  @package    catalog::classes
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: order.php v1.0 2013-08-08 datazen $
*/
class lC_Order {
  public $info, $totals, $products, $customer, $delivery, $content_type;

  /* Private variables */
  private $_id;

  /* Class constructor */
  public function __construct($order_id = '') {
    if (is_numeric($order_id)) {
      $this->_id = $order_id;
    }

    $this->info = array();
    $this->totals = array();
    $this->products = array();
    $this->customer = array();
    $this->delivery = array();

    if (!empty($order_id)) {
      $this->query($order_id);
    }
  }

  /* Public methods */
  public function getStatusID($id) {
    global $lC_Database;

    $Qorder = $lC_Database->query('select orders_status from :table_orders where orders_id = :orders_id');
    $Qorder->bindTable(':table_orders', TABLE_ORDERS);
    $Qorder->bindInt(':orders_id', $id);
    $Qorder->execute();

    if ($Qorder->numberOfRows()) {
      return $Qorder->valueInt('orders_status');
    }

    return false;
  }

  public function remove($id) {
    global $lC_Database;

    $Qcheck = $lC_Database->query('select orders_status from :table_orders where orders_id = :orders_id');
    $Qcheck->bindTable(':table_orders', TABLE_ORDERS);
    $Qcheck->bindInt(':orders_id', $id);
    $Qcheck->execute();

    if ($Qcheck->valueInt('orders_status') === 4) {
      /* 
      $Qdel = $lC_Database->query('delete from :table_orders_products_download where orders_id = :orders_id');
      $Qdel->bindTable(':table_orders_products_download', TABLE_ORDERS_PRODUCTS_DOWNLOAD);
      $Qdel->bindInt(':orders_id', $id);
      $Qdel->execute();
      */

      $Qdel = $lC_Database->query('delete from :table_orders_products_variants where orders_id = :orders_id');
      $Qdel->bindTable(':table_orders_products_variants', TABLE_ORDERS_PRODUCTS_VARIANTS);
      $Qdel->bindInt(':orders_id', $id);
      $Qdel->execute();

      $Qdel = $lC_Database->query('delete from :table_orders_products where orders_id = :orders_id');
      $Qdel->bindTable(':table_orders_products', TABLE_ORDERS_PRODUCTS);
      $Qdel->bindInt(':orders_id', $id);
      $Qdel->execute();

      $Qdel = $lC_Database->query('delete from :table_orders_status_history where orders_id = :orders_id');
      $Qdel->bindTable(':table_orders_status_history', TABLE_ORDERS_STATUS_HISTORY);
      $Qdel->bindInt(':orders_id', $id);
      $Qdel->execute();

      $Qdel = $lC_Database->query('delete from :table_orders_total where orders_id = :orders_id');
      $Qdel->bindTable(':table_orders_total', TABLE_ORDERS_TOTAL);
      $Qdel->bindInt(':orders_id', $id);
      $Qdel->execute();

      $Qdel = $lC_Database->query('delete from :table_orders where orders_id = :orders_id');
      $Qdel->bindTable(':table_orders', TABLE_ORDERS);
      $Qdel->bindInt(':orders_id', $id);
      $Qdel->execute();
    }

    if (isset($_SESSION['prepOrderID'])) {
      unset($_SESSION['prepOrderID']);
    }
  }

  public function insert($status = 1) {
    global $lC_Database, $lC_Customer, $lC_Language, $lC_Currencies, $lC_ShoppingCart, $lC_Coupons, $lC_Tax;
       
    if (isset($_SESSION['prepOrderID'])) {
      $_prep = explode('-', $_SESSION['prepOrderID']);

      if ($_prep[0] == $lC_ShoppingCart->getCartID()) {   
        return $_prep[1]; // order_id
      } else {
        if (lC_Order::getStatusID($_prep[1]) === $status) {
          lC_Order::remove($_prep[1]);
        }
      }
    }
    
    if ($lC_Customer->getID() == 0) {
      $customerName = 'New Customer';
    } else {
      if (isset($_SESSION['cartSync']['orderCreated']) && $_SESSION['cartSync']['orderCreated'] === TRUE) {
        return end(explode('-', $_SESSION['cartSync']['prepOrderID']));  // order id
      }
      $customerName = $lC_Customer->getName();
    }
    
    $customer_address = lC_AddressBook::getEntry($lC_Customer->getDefaultAddressID())->toArray();

    // ppec inject
    if ($lC_ShoppingCart->getBillingMethod('id') != NULL && $_GET['ppec'] != 'process') {
      $payment_method = $GLOBALS['lC_Payment_' . $lC_ShoppingCart->getBillingMethod('id')]->getCode();
    } else if (isset($_SESSION['cartSync']['paymentMethod']) && $_SESSION['cartSync']['paymentMethod'] != NULL) {
      $payment_method = $_SESSION['cartSync']['paymentMethod'];
    }    
    
    $Qorder = $lC_Database->query('insert into :table_orders (customers_id, customers_name, customers_company, customers_street_address, customers_suburb, customers_city, customers_postcode, customers_state, customers_state_code, customers_country, customers_country_iso2, customers_country_iso3, customers_telephone, customers_email_address, customers_address_format, customers_ip_address, delivery_name, delivery_company, delivery_street_address, delivery_suburb, delivery_city, delivery_postcode, delivery_state, delivery_state_code, delivery_country, delivery_country_iso2, delivery_country_iso3, delivery_address_format, billing_name, billing_company, billing_street_address, billing_suburb, billing_city, billing_postcode, billing_state, billing_state_code, billing_country, billing_country_iso2, billing_country_iso3, billing_address_format, payment_method, payment_module, date_purchased, orders_status, currency, currency_value) values (:customers_id, :customers_name, :customers_company, :customers_street_address, :customers_suburb, :customers_city, :customers_postcode, :customers_state, :customers_state_code, :customers_country, :customers_country_iso2, :customers_country_iso3, :customers_telephone, :customers_email_address, :customers_address_format, :customers_ip_address, :delivery_name, :delivery_company, :delivery_street_address, :delivery_suburb, :delivery_city, :delivery_postcode, :delivery_state, :delivery_state_code, :delivery_country, :delivery_country_iso2, :delivery_country_iso3, :delivery_address_format, :billing_name, :billing_company, :billing_street_address, :billing_suburb, :billing_city, :billing_postcode, :billing_state, :billing_state_code, :billing_country, :billing_country_iso2, :billing_country_iso3, :billing_address_format, :payment_method, :payment_module, now(), :orders_status, :currency, :currency_value)');
    $Qorder->bindTable(':table_orders', TABLE_ORDERS);
    $Qorder->bindInt(':customers_id', $lC_Customer->getID());
    $Qorder->bindValue(':customers_name', $customerName);
    $Qorder->bindValue(':customers_company', $customer_address['entry_company']);
    $Qorder->bindValue(':customers_street_address', $customer_address['entry_street_address']);
    $Qorder->bindValue(':customers_suburb', $customer_address['entry_suburb']);
    $Qorder->bindValue(':customers_city', $customer_address['entry_city']);
    $Qorder->bindValue(':customers_postcode', $customer_address['entry_postcode']);
    $Qorder->bindValue(':customers_state', $customer_address['entry_state']);
    $Qorder->bindValue(':customers_state_code', lC_Address::getZoneCode($customer_address['entry_zone_id']));
    $Qorder->bindValue(':customers_country', lC_Address::getCountryName($customer_address['entry_country_id']));
    $Qorder->bindValue(':customers_country_iso2', lC_Address::getCountryIsoCode2($customer_address['entry_country_id']));
    $Qorder->bindValue(':customers_country_iso3', lC_Address::getCountryIsoCode3($customer_address['entry_country_id']));
    $Qorder->bindValue(':customers_telephone', $customer_address['entry_telephone']);
    $Qorder->bindValue(':customers_email_address', $lC_Customer->getEmailAddress());
    $Qorder->bindValue(':customers_address_format', lC_Address::getFormat($customer_address['entry_country_id']));
    $Qorder->bindValue(':customers_ip_address', lc_get_ip_address());
    $Qorder->bindValue(':delivery_name', $lC_ShoppingCart->getShippingAddress('firstname') . ' ' . $lC_ShoppingCart->getShippingAddress('lastname'));
    $Qorder->bindValue(':delivery_company', $lC_ShoppingCart->getShippingAddress('company'));
    $Qorder->bindValue(':delivery_street_address', $lC_ShoppingCart->getShippingAddress('street_address'));
    $Qorder->bindValue(':delivery_suburb', $lC_ShoppingCart->getShippingAddress('suburb'));
    $Qorder->bindValue(':delivery_city', $lC_ShoppingCart->getShippingAddress('city'));
    $Qorder->bindValue(':delivery_postcode', $lC_ShoppingCart->getShippingAddress('postcode'));
    $Qorder->bindValue(':delivery_state', $lC_ShoppingCart->getShippingAddress('state'));
    $Qorder->bindValue(':delivery_state_code', $lC_ShoppingCart->getShippingAddress('zone_code'));
    $Qorder->bindValue(':delivery_country', $lC_ShoppingCart->getShippingAddress('country_title'));
    $Qorder->bindValue(':delivery_country_iso2', $lC_ShoppingCart->getShippingAddress('country_iso_code_2'));
    $Qorder->bindValue(':delivery_country_iso3', $lC_ShoppingCart->getShippingAddress('country_iso_code_3'));
    $Qorder->bindValue(':delivery_address_format', $lC_ShoppingCart->getShippingAddress('format'));
    $Qorder->bindValue(':billing_name', $lC_ShoppingCart->getBillingAddress('firstname') . ' ' . $lC_ShoppingCart->getBillingAddress('lastname'));
    $Qorder->bindValue(':billing_company', $lC_ShoppingCart->getBillingAddress('company'));
    $Qorder->bindValue(':billing_street_address', $lC_ShoppingCart->getBillingAddress('street_address'));
    $Qorder->bindValue(':billing_suburb', $lC_ShoppingCart->getBillingAddress('suburb'));
    $Qorder->bindValue(':billing_city', $lC_ShoppingCart->getBillingAddress('city'));
    $Qorder->bindValue(':billing_postcode', $lC_ShoppingCart->getBillingAddress('postcode'));
    $Qorder->bindValue(':billing_state', $lC_ShoppingCart->getBillingAddress('state'));
    $Qorder->bindValue(':billing_state_code', $lC_ShoppingCart->getBillingAddress('zone_code'));
    $Qorder->bindValue(':billing_country', $lC_ShoppingCart->getBillingAddress('country_title'));
    $Qorder->bindValue(':billing_country_iso2', $lC_ShoppingCart->getBillingAddress('country_iso_code_2'));
    $Qorder->bindValue(':billing_country_iso3', $lC_ShoppingCart->getBillingAddress('country_iso_code_3'));
    $Qorder->bindValue(':billing_address_format', $lC_ShoppingCart->getBillingAddress('format'));
    $Qorder->bindValue(':payment_method', $lC_ShoppingCart->getBillingMethod('title'));
    $Qorder->bindValue(':payment_module', $payment_method);
    $Qorder->bindInt(':orders_status', $status);
    $Qorder->bindValue(':currency', $lC_Currencies->getCode());
    $Qorder->bindValue(':currency_value', $lC_Currencies->value($lC_Currencies->getCode()));
    $Qorder->execute();
    
    $insert_id = $lC_Database->nextID();
    
    if (isset($_SESSION['cartSync']['orderCreated']) && $_SESSION['cartSync']['orderCreated'] === TRUE) $_SESSION['cartSync']['orderID'] = $insert_id;

    foreach ($lC_ShoppingCart->getOrderTotals() as $module) {
      $Qtotals = $lC_Database->query('insert into :table_orders_total (orders_id, title, text, value, class, sort_order) values (:orders_id, :title, :text, :value, :class, :sort_order)');
      $Qtotals->bindTable(':table_orders_total', TABLE_ORDERS_TOTAL);
      $Qtotals->bindInt(':orders_id', $insert_id);
      $Qtotals->bindValue(':title', $module['title']);
      $Qtotals->bindValue(':text', strip_tags(str_replace('&nbsp;', '', $module['text'])));
      $Qtotals->bindValue(':value', $module['value']);
      $Qtotals->bindValue(':class', $module['code']);
      $Qtotals->bindInt(':sort_order', $module['sort_order']);
      $Qtotals->execute();
    }    

    $Qstatus = $lC_Database->query('insert into :table_orders_status_history (orders_id, orders_status_id, date_added, customer_notified, comments) values (:orders_id, :orders_status_id, now(), :customer_notified, :comments)');
    $Qstatus->bindTable(':table_orders_status_history', TABLE_ORDERS_STATUS_HISTORY);
    $Qstatus->bindInt(':orders_id', $insert_id);
    $Qstatus->bindInt(':orders_status_id', 1);
    $Qstatus->bindInt(':customer_notified', '0');
    $Qstatus->bindValue(':comments', '');
    $Qstatus->execute();

    foreach ($lC_ShoppingCart->getProducts() as $products) {
      $Qproducts = $lC_Database->query('insert into :table_orders_products (orders_id, products_id, products_model, products_name, products_price, products_tax, products_quantity, products_simple_options_meta_data) values (:orders_id, :products_id, :products_model, :products_name, :products_price, :products_tax, :products_quantity, :products_simple_options_meta_data)');
      $Qproducts->bindTable(':table_orders_products', TABLE_ORDERS_PRODUCTS);
      $Qproducts->bindInt(':orders_id', $insert_id);
      $Qproducts->bindInt(':products_id', lc_get_product_id($products['id']));
      $Qproducts->bindValue(':products_model', $products['model']);
      $Qproducts->bindValue(':products_name', $products['name']);
      $Qproducts->bindValue(':products_price', $products['price']);
      $Qproducts->bindValue(':products_tax', $lC_Tax->getTaxRate($products['tax_class_id']));
      $Qproducts->bindInt(':products_quantity', $products['quantity']);
      $Qproducts->bindValue(':products_simple_options_meta_data', serialize($products['simple_options']));
      $Qproducts->execute();

      $order_products_id = $lC_Database->nextID();

      if ( $lC_ShoppingCart->isVariant($products['item_id']) ) {
        foreach ( $lC_ShoppingCart->getVariant($products['item_id']) as $variant ) {
          /* 
          if (DOWNLOAD_ENABLED == '1') {
            $Qattributes = $lC_Database->query('select popt.products_options_name, poval.products_options_values_name, pa.options_values_price, pa.price_prefix, pad.products_attributes_maxdays, pad.products_attributes_maxcount, pad.products_attributes_filename from :table_products_options popt, :table_products_options_values poval, :table_products_attributes pa left join :table_products_attributes_download pad on (pa.products_attributes_id = pad.products_attributes_id) where pa.products_id = :products_id and pa.options_id = :options_id and pa.options_id = popt.products_options_id and pa.options_values_id = :options_values_id and pa.options_values_id = poval.products_options_values_id and popt.language_id = :popt_language_id and poval.language_id = :poval_language_id');
            $Qattributes->bindTable(':table_products_options', TABLE_PRODUCTS_OPTIONS);
            $Qattributes->bindTable(':table_products_options_values', TABLE_PRODUCTS_OPTIONS_VALUES);
            $Qattributes->bindTable(':table_products_attributes', TABLE_PRODUCTS_ATTRIBUTES);
            $Qattributes->bindTable(':table_products_attributes_download', TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD);
            $Qattributes->bindInt(':products_id', $products['id']);
            $Qattributes->bindInt(':options_id', $attributes['options_id']);
            $Qattributes->bindInt(':options_values_id', $attributes['options_values_id']);
            $Qattributes->bindInt(':popt_language_id', $lC_Language->getID());
            $Qattributes->bindInt(':poval_language_id', $lC_Language->getID());
            $Qattributes->execute();
          }
          */

          $Qvariant = $lC_Database->query('insert into :table_orders_products_variants (orders_id, orders_products_id, group_title, value_title) values (:orders_id, :orders_products_id, :group_title, :value_title)');
          $Qvariant->bindTable(':table_orders_products_variants', TABLE_ORDERS_PRODUCTS_VARIANTS);
          $Qvariant->bindInt(':orders_id', $insert_id);
          $Qvariant->bindInt(':orders_products_id', $order_products_id);
          $Qvariant->bindValue(':group_title', $variant['group_title']);
          $Qvariant->bindValue(':value_title', $variant['value_title']);
          $Qvariant->execute();

          /*
          if ((DOWNLOAD_ENABLED == '1') && (strlen($Qattributes->value('products_attributes_filename')) > 0)) {
            $Qopd = $lC_Database->query('insert into :table_orders_products_download (orders_id, orders_products_id, orders_products_filename, download_maxdays, download_count) values (:orders_id, :orders_products_id, :orders_products_filename, :download_maxdays, :download_count)');
            $Qopd->bindTable(':table_orders_products_download', TABLE_ORDERS_PRODUCTS_DOWNLOAD);
            $Qopd->bindInt(':orders_id', $insert_id);
            $Qopd->bindInt(':orders_products_id', $order_products_id);
            $Qopd->bindValue(':orders_products_filename', $Qattributes->value('products_attributes_filename'));
            $Qopd->bindValue(':download_maxdays', $Qattributes->value('products_attributes_maxdays'));
            $Qopd->bindValue(':download_count', $Qattributes->value('products_attributes_maxcount'));
            $Qopd->execute();
          }
          */
        }
      }
    }

    $_SESSION['prepOrderID'] = $lC_ShoppingCart->getCartID() . '-' . $insert_id;

    return $insert_id;
  }
  
  public function process($order_id, $status_id = '') {
    global $lC_Database, $lC_Customer, $lC_Language, $lC_Currencies, $lC_ShoppingCart, $lC_Coupons, $lC_Tax;

    if (empty($status_id) || (is_numeric($status_id) === false)) {
      $status_id = DEFAULT_ORDERS_STATUS_ID;
    }
    
    if (isset($_SESSION['cartSync']['orderCreated']) && $_SESSION['cartSync']['orderCreated'] === TRUE) {
      if (isset($_SESSION['cartSync']['orderID']) && $_SESSION['cartSync']['orderID'] != NULL) $order_id = $_SESSION['cartSync']['orderID'];
      // update the order info
      
      if ($lC_Customer->getDefaultAddressID() == '') $lC_Customer->setCustomerData($lC_Customer->getID());
      $customer_address = lC_AddressBook::getEntry($lC_Customer->getDefaultAddressID())->toArray();
    
      $Qupdate = $lC_Database->query('update :table_orders set 
        customers_id = :customers_id,
        customers_name = :customers_name,
        customers_company = :customers_company,
        customers_street_address = :customers_street_address,
        customers_suburb = :customers_suburb,
        customers_city = :customers_city,
        customers_postcode = :customers_postcode,
        customers_state = :customers_state,
        customers_state_code = :customers_state_code,
        customers_country = :customers_country,
        customers_country_iso2 = :customers_country_iso2,
        customers_country_iso3 = :customers_country_iso3,
        customers_telephone = :customers_telephone,
        customers_email_address = :customers_email_address,
        customers_address_format = :customers_address_format,
        customers_ip_address = :customers_ip_address,
        delivery_name = :delivery_name,
        delivery_company = :delivery_company,
        delivery_street_address = :delivery_street_address,
        delivery_suburb = :delivery_suburb,
        delivery_city = :delivery_city,
        delivery_postcode = :delivery_postcode,
        delivery_state = :delivery_state,
        delivery_state_code = :delivery_state_code,
        delivery_country = :delivery_country,
        delivery_country_iso2 = :delivery_country_iso2,
        delivery_country_iso3 = :delivery_country_iso3,
        delivery_address_format = :delivery_address_format, 
        billing_company = :billing_company,
        billing_street_address = :billing_street_address,
        billing_suburb = :billing_suburb,
        billing_city = :billing_city,
        billing_postcode = :billing_postcode,
        billing_state = :billing_state,
        billing_state_code = :billing_state_code,
        billing_country = :billing_country,
        billing_country_iso2 = :billing_country_iso2,
        billing_country_iso3 = :billing_country_iso3,
        billing_address_format = :billing_address_format,   
        currency = :currency, 
        currency_value = :currency_value, 
        orders_status = :orders_status where orders_id = :orders_id');
                   
      $Qupdate->bindInt(':customers_id', $lC_Customer->getID());
      $Qupdate->bindValue(':customers_name', $lC_Customer->getName());
      $Qupdate->bindValue(':customers_company', $customer_address['entry_company']);
      $Qupdate->bindValue(':customers_street_address', $customer_address['entry_street_address']);
      $Qupdate->bindValue(':customers_suburb', $customer_address['entry_suburb']);
      $Qupdate->bindValue(':customers_city', $customer_address['entry_city']);
      $Qupdate->bindValue(':customers_postcode', $customer_address['entry_postcode']);
      $Qupdate->bindValue(':customers_state', $customer_address['entry_state']);
      $Qupdate->bindValue(':customers_state_code', lC_Address::getZoneCode($customer_address['entry_zone_id']));
      $Qupdate->bindValue(':customers_country', lC_Address::getCountryName($customer_address['entry_country_id']));
      $Qupdate->bindValue(':customers_country_iso2', lC_Address::getCountryIsoCode2($customer_address['entry_country_id']));
      $Qupdate->bindValue(':customers_country_iso3', lC_Address::getCountryIsoCode3($customer_address['entry_country_id']));
      $Qupdate->bindValue(':customers_telephone', $customer_address['entry_telephone']);
      $Qupdate->bindValue(':customers_email_address', $lC_Customer->getEmailAddress());
      $Qupdate->bindValue(':customers_address_format', lC_Address::getFormat($customer_address['entry_country_id']));
      $Qupdate->bindValue(':customers_ip_address', lc_get_ip_address());
      $Qupdate->bindValue(':delivery_name', ($lC_ShoppingCart->getShippingAddress('lastname') != NULL) ? $lC_ShoppingCart->getShippingAddress('firstname') . ' ' . $lC_ShoppingCart->getShippingAddress('lastname') : $lC_Customer->getName());
      $Qupdate->bindValue(':delivery_company', $lC_ShoppingCart->getShippingAddress('company'));
      $Qupdate->bindValue(':delivery_street_address', $lC_ShoppingCart->getShippingAddress('street_address'));
      $Qupdate->bindValue(':delivery_suburb', $lC_ShoppingCart->getShippingAddress('suburb'));
      $Qupdate->bindValue(':delivery_city', $lC_ShoppingCart->getShippingAddress('city'));
      $Qupdate->bindValue(':delivery_postcode', $lC_ShoppingCart->getShippingAddress('postcode'));
      $Qupdate->bindValue(':delivery_state', $lC_ShoppingCart->getShippingAddress('state'));
      $Qupdate->bindValue(':delivery_state_code', $lC_ShoppingCart->getShippingAddress('zone_code'));
      $Qupdate->bindValue(':delivery_country', $lC_ShoppingCart->getShippingAddress('country_title'));
      $Qupdate->bindValue(':delivery_country_iso2', $lC_ShoppingCart->getShippingAddress('country_iso_code_2'));
      $Qupdate->bindValue(':delivery_country_iso3', $lC_ShoppingCart->getShippingAddress('country_iso_code_3'));
      $Qupdate->bindValue(':delivery_address_format', $lC_ShoppingCart->getShippingAddress('format'));
      $Qupdate->bindValue(':billing_name', ($lC_ShoppingCart->getBillingAddress('lastname') != NULL) ? $lC_ShoppingCart->getBillingAddress('firstname') . ' ' . $lC_ShoppingCart->getBillingAddress('lastname') : $lC_Customer->getName());
      $Qupdate->bindValue(':billing_company', $lC_ShoppingCart->getBillingAddress('company'));
      $Qupdate->bindValue(':billing_street_address', $lC_ShoppingCart->getBillingAddress('street_address'));
      $Qupdate->bindValue(':billing_suburb', $lC_ShoppingCart->getBillingAddress('suburb'));
      $Qupdate->bindValue(':billing_city', $lC_ShoppingCart->getBillingAddress('city'));
      $Qupdate->bindValue(':billing_postcode', $lC_ShoppingCart->getBillingAddress('postcode'));
      $Qupdate->bindValue(':billing_state', $lC_ShoppingCart->getBillingAddress('state'));
      $Qupdate->bindValue(':billing_state_code', $lC_ShoppingCart->getBillingAddress('zone_code'));
      $Qupdate->bindValue(':billing_country', $lC_ShoppingCart->getBillingAddress('country_title'));
      $Qupdate->bindValue(':billing_country_iso2', $lC_ShoppingCart->getBillingAddress('country_iso_code_2'));
      $Qupdate->bindValue(':billing_country_iso3', $lC_ShoppingCart->getBillingAddress('country_iso_code_3'));
      $Qupdate->bindValue(':billing_address_format', $lC_ShoppingCart->getBillingAddress('format'));
      $Qupdate->bindValue(':currency', $lC_Currencies->getCode());
      $Qupdate->bindValue(':currency_value', $lC_Currencies->value($lC_Currencies->getCode()));
    
      $Qpt = $lC_Database->query('delete from :table_orders_total where orders_id = :orders_id');
      $Qpt->bindTable(':table_orders_total', TABLE_ORDERS_TOTAL);
      $Qpt->bindInt(':orders_id', $order_id); 
      $Qpt->execute(); 
      
      foreach ($lC_ShoppingCart->getOrderTotals() as $module) {
        $Qtotals = $lC_Database->query('insert into :table_orders_total (orders_id, title, text, value, class, sort_order) values (:orders_id, :title, :text, :value, :class, :sort_order)');
        $Qtotals->bindTable(':table_orders_total', TABLE_ORDERS_TOTAL);
        $Qtotals->bindInt(':orders_id', $order_id);
        $Qtotals->bindValue(':title', $module['title']);
        $Qtotals->bindValue(':text', strip_tags(str_replace('&nbsp;', '', $module['text'])));
        $Qtotals->bindValue(':value', $module['value']);
        $Qtotals->bindValue(':class', $module['code']);
        $Qtotals->bindInt(':sort_order', $module['sort_order']);
        $Qtotals->execute();
        
        if (defined('MODULE_SERVICES_INSTALLED') && in_array('coupons', explode(';', MODULE_SERVICES_INSTALLED)) && isset($lC_Coupons)) {
          if ($lC_Coupons->is_enabled) {
            preg_match('#\((.*?)\)#', $module['title'], $match);
            $lC_Coupons->redeem($match[1], $order_id); 
          }      
        }
      }      

      $Qpd = $lC_Database->query('delete from :table_orders_products where orders_id = :orders_id');
      $Qpd->bindTable(':table_orders_products', TABLE_ORDERS_PRODUCTS);
      $Qpd->bindInt(':orders_id', $order_id); 
      $Qpd->execute();     
           
      foreach ($lC_ShoppingCart->getProducts() as $products) {
        $Qproducts = $lC_Database->query('insert into :table_orders_products (orders_id, products_id, products_model, products_name, products_price, products_tax, products_quantity, products_simple_options_meta_data) values (:orders_id, :products_id, :products_model, :products_name, :products_price, :products_tax, :products_quantity, :products_simple_options_meta_data)');
        $Qproducts->bindTable(':table_orders_products', TABLE_ORDERS_PRODUCTS);
        $Qproducts->bindInt(':orders_id', $order_id);
        $Qproducts->bindInt(':products_id', lc_get_product_id($products['id']));
        $Qproducts->bindValue(':products_model', $products['model']);
        $Qproducts->bindValue(':products_name', $products['name']);
        $Qproducts->bindValue(':products_price', $products['price']);
        $Qproducts->bindValue(':products_tax', $lC_Tax->getTaxRate($products['tax_class_id']));
        $Qproducts->bindInt(':products_quantity', $products['quantity']);
        $Qproducts->bindValue(':products_simple_options_meta_data', serialize($products['simple_options']));
        $Qproducts->execute();

        $order_products_id = $lC_Database->nextID();

        if ( $lC_ShoppingCart->isVariant($products['item_id']) ) {
          foreach ( $lC_ShoppingCart->getVariant($products['item_id']) as $variant ) {

            $Qvariant = $lC_Database->query('insert into :table_orders_products_variants (orders_id, orders_products_id, group_title, value_title) values (:orders_id, :orders_products_id, :group_title, :value_title)');
            $Qvariant->bindTable(':table_orders_products_variants', TABLE_ORDERS_PRODUCTS_VARIANTS);
            $Qvariant->bindInt(':orders_id', $insert_id);
            $Qvariant->bindInt(':orders_products_id', $order_products_id);
            $Qvariant->bindValue(':group_title', $variant['group_title']);
            $Qvariant->bindValue(':value_title', $variant['value_title']);
            $Qvariant->execute();

          }
        }
      }
    } else {
      $Qupdate = $lC_Database->query('update :table_orders set orders_status = :orders_status where orders_id = :orders_id');
    }
    $Qupdate->bindTable(':table_orders', TABLE_ORDERS);
    $Qupdate->bindInt(':orders_status', $status_id);
    $Qupdate->bindInt(':orders_id', $order_id);
    $Qupdate->execute();
    
    $Qstatus = $lC_Database->query('insert into :table_orders_status_history (orders_id, orders_status_id, date_added, customer_notified, comments) values (:orders_id, :orders_status_id, now(), :customer_notified, :comments)');
    $Qstatus->bindTable(':table_orders_status_history', TABLE_ORDERS_STATUS_HISTORY);
    $Qstatus->bindInt(':orders_id', $order_id);
    $Qstatus->bindInt(':orders_status_id', $status_id);
    $Qstatus->bindInt(':customer_notified', (SEND_EMAILS == '1') ? '1' : '0');
    $Qstatus->bindValue(':comments', (isset($_SESSION['comments']) ? $_SESSION['comments'] : $_POST['comments']));
    $Qstatus->execute();
    
    $lC_ShoppingCart->synchronizeWithDatabase();
    
    $Qproducts = $lC_Database->query('select products_id, products_quantity from :table_orders_products where orders_id = :orders_id');
    $Qproducts->bindTable(':table_orders_products', TABLE_ORDERS_PRODUCTS);
    $Qproducts->bindInt(':orders_id', $order_id);
    $Qproducts->execute();

    while ($Qproducts->next()) {
      if (STOCK_LIMITED == '1') {

      /**** still uses logic from the shopping cart class
        if (DOWNLOAD_ENABLED == '1') {
          $Qstock = $lC_Database->query('select products_quantity, pad.products_attributes_filename from :table_products p left join :table_products_attributes pa on (p.products_id = pa.products_id) left join :table_products_attributes_download pad on (pa.products_attributes_id = pad.products_attributes_id) where p.products_id = :products_id');
          $Qstock->bindTable(':table_products', TABLE_PRODUCTS);
          $Qstock->bindTable(':table_products_attributes', TABLE_PRODUCTS_ATTRIBUTES);
          $Qstock->bindTable(':table_products_attributes_download', TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD);
          $Qstock->bindInt(':products_id', $Qproducts->valueInt('products_id'));

          // Will work with only one option for downloadable products otherwise, we have to build the query dynamically with a loop
          if ($lC_ShoppingCart->hasAttributes($products['id'])) {
            $products_attributes = $lC_ShoppingCart->getAttributes($products['id']);
            $products_attributes = array_shift($products_attributes);

            $Qstock->appendQuery('and pa.options_id = :options_id and pa.options_values_id = :options_values_id');
            $Qstock->bindInt(':options_id', $products_attributes['options_id']);
            $Qstock->bindInt(':options_values_id', $products_attributes['options_values_id']);
          }
        } else {
        *****/
          $Qstock = $lC_Database->query('select products_quantity from :table_products where products_id = :products_id');
          $Qstock->bindTable(':table_products', TABLE_PRODUCTS);
          $Qstock->bindInt(':products_id', $Qproducts->valueInt('products_id'));
        // }

        $Qstock->execute();

        if ($Qstock->numberOfRows() > 0) {
          $stock_left = $Qstock->valueInt('products_quantity');

            // do not decrement quantities if products_attributes_filename exists
          // if ((DOWNLOAD_ENABLED == '-1') || ((DOWNLOAD_ENABLED == '1') && (strlen($Qstock->value('products_attributes_filename')) < 1))) {
            $stock_left = $stock_left - $Qproducts->valueInt('products_quantity');

            $Qupdate = $lC_Database->query('update :table_products set products_quantity = :products_quantity where products_id = :products_id');
            $Qupdate->bindTable(':table_products', TABLE_PRODUCTS);
            $Qupdate->bindInt(':products_quantity', $stock_left);
            $Qupdate->bindInt(':products_id', $Qproducts->valueInt('products_id'));
            $Qupdate->execute();
          // }

          if ((STOCK_ALLOW_CHECKOUT == '-1') && ($stock_left < 1) && AUTODISABLE_OUT_OF_STOCK_PRODUCT == '1') {
            $Qupdate = $lC_Database->query('update :table_products set products_status = 0 where products_id = :products_id');
            $Qupdate->bindTable(':table_products', TABLE_PRODUCTS);
            $Qupdate->bindInt(':products_id', $Qproducts->valueInt('products_id'));
            $Qupdate->execute();
          }
        }
      }

      // Update products_ordered (for bestsellers list)
      $Qupdate = $lC_Database->query('update :table_products set products_ordered = products_ordered + :products_ordered where products_id = :products_id');
      $Qupdate->bindTable(':table_products', TABLE_PRODUCTS);
      $Qupdate->bindInt(':products_ordered', $Qproducts->valueInt('products_quantity'));
      $Qupdate->bindInt(':products_id', $Qproducts->valueInt('products_id'));
      $Qupdate->execute();
    }
    
    lC_Order::sendEmail($order_id);

    unset($_SESSION['prepOrderID']);
  }

  public function sendEmail($id) {
    global $lC_Database, $lC_Language, $lC_Currencies, $lC_ShoppingCart;
    
    $Qorder = $lC_Database->query('select * from :table_orders where orders_id = :orders_id limit 1');
    $Qorder->bindTable(':table_orders', TABLE_ORDERS);
    $Qorder->bindInt(':orders_id', $id);
    $Qorder->execute();
    
    if ($Qorder->numberOfRows() === 1) {
      
      $email_order = STORE_NAME . "\n" .
                     $lC_Language->get('email_order_separator') . "\n" .
                     sprintf($lC_Language->get('email_order_order_number'), $id) . "\n" .
                     sprintf($lC_Language->get('email_order_invoice_url'), lc_href_link(FILENAME_ACCOUNT, 'receipt=' . $id, 'SSL', false, true, true)) . "\n" .
                     sprintf($lC_Language->get('email_order_date_ordered'), lC_DateTime::getLong()) . "\n\n" .
                     $lC_Language->get('email_order_products') . "\n" .
                     $lC_Language->get('email_order_separator') . "\n";

      $Qproducts = $lC_Database->query('select orders_products_id, products_model, products_name, products_price, products_tax, products_quantity from :table_orders_products where orders_id = :orders_id order by orders_products_id');
      $Qproducts->bindTable(':table_orders_products', TABLE_ORDERS_PRODUCTS);
      $Qproducts->bindInt(':orders_id', $id);
      $Qproducts->execute();
      
      while ($Qproducts->next()) {
        $email_order .= $Qproducts->valueInt('products_quantity') . ' x ' . $Qproducts->value('products_name') . ' (' . $Qproducts->value('products_model') . ') = ' . $lC_Currencies->displayPriceWithTaxRate($Qproducts->value('products_price'), $Qproducts->value('products_tax'), $Qproducts->valueInt('products_quantity'), false, $Qorder->value('currency'), $Qorder->value('currency_value')) . "\n";

        $Qvariants = $lC_Database->query('select group_title, value_title from :table_orders_products_variants where orders_id = :orders_id and orders_products_id = :orders_products_id order by id');
        $Qvariants->bindTable(':table_orders_products_variants', TABLE_ORDERS_PRODUCTS_VARIANTS);
        $Qvariants->bindInt(':orders_id', $id);
        $Qvariants->bindInt(':orders_products_id', $Qproducts->valueInt('orders_products_id'));
        $Qvariants->execute();

        while ( $Qvariants->next() ) {
          $email_order .= "\t" . $Qvariants->value('group_title') . ': ' . $Qvariants->value('value_title') . "\n";
        }
      }
      
      unset($Qproducts);
      unset($Qvariants);

      $email_order .= $lC_Language->get('email_order_separator') . "\n";

      $Qtotals = $lC_Database->query('select title, text from :table_orders_total where orders_id = :orders_id order by sort_order');
      $Qtotals->bindTable(':table_orders_total', TABLE_ORDERS_TOTAL);
      $Qtotals->bindInt(':orders_id', $id);
      $Qtotals->execute();
      
      while ($Qtotals->next()) {
        $email_order .= strip_tags($Qtotals->value('title') . ' ' . $Qtotals->value('text')) . "\n";
      }
      
      unset($Qtotals);

      if ( (lc_empty($Qorder->value('delivery_name')) === false) && (lc_empty($Qorder->value('delivery_street_address')) === false) ) {
        $address = array('name' => $Qorder->value('delivery_name'),
                         'company' => $Qorder->value('delivery_company'),
                         'street_address' => $Qorder->value('delivery_street_address'),
                         'suburb' => $Qorder->value('delivery_suburb'),
                         'city' => $Qorder->value('delivery_city'),
                         'state' => $Qorder->value('delivery_state'),
                         'zone_code' => $Qorder->value('delivery_state_code'),
                         'country_title' => $Qorder->value('delivery_country'),
                         'country_iso2' => $Qorder->value('delivery_country_iso2'),
                         'country_iso3' => $Qorder->value('delivery_country_iso3'),
                         'postcode' => $Qorder->value('delivery_postcode'),
                         'format' => $Qorder->value('delivery_address_format'));

        $email_order .= "\n" . $lC_Language->get('email_order_delivery_address') . "\n" .
                        $lC_Language->get('email_order_separator') . "\n" .
                        lC_Address::format($address) . "\n";

        unset($address);
      }
      
      $address = array('name' => $Qorder->value('billing_name'),
                       'company' => $Qorder->value('billing_company'),
                       'street_address' => $Qorder->value('billing_street_address'),
                       'suburb' => $Qorder->value('billing_suburb'),
                       'city' => $Qorder->value('billing_city'),
                       'state' => $Qorder->value('billing_state'),
                       'zone_code' => $Qorder->value('billing_state_code'),
                       'country_title' => $Qorder->value('billing_country'),
                       'country_iso2' => $Qorder->value('billing_country_iso2'),
                       'country_iso3' => $Qorder->value('billing_country_iso3'),
                       'postcode' => $Qorder->value('billing_postcode'),
                       'format' => $Qorder->value('billing_address_format'));

      $email_order .= "\n" . $lC_Language->get('email_order_billing_address') . "\n" .
                      $lC_Language->get('email_order_separator') . "\n" .
                      lC_Address::format($address) . "\n\n";

      unset($address);
      
      $Qstatus = $lC_Database->query('select orders_status_name from :table_orders_status where orders_status_id = :orders_status_id and language_id = :language_id');
      $Qstatus->bindTable(':table_orders_status', TABLE_ORDERS_STATUS);
      $Qstatus->bindInt(':orders_status_id', $Qorder->valueInt('orders_status'));
      $Qstatus->bindInt(':language_id', $lC_Language->getID());
      $Qstatus->execute();
      
      $email_order .= sprintf($lC_Language->get('email_order_status'), $Qstatus->value('orders_status_name')) . "\n" .
                      $lC_Language->get('email_order_separator') . "\n";

      unset($Qstatus);

      $Qstatuses = $lC_Database->query('select date_added, comments from :table_orders_status_history where orders_id = :orders_id and comments != "" order by orders_status_history_id');
      $Qstatuses->bindTable(':table_orders_status_history', TABLE_ORDERS_STATUS_HISTORY);
      $Qstatuses->bindInt(':orders_id', $id);
      $Qstatuses->execute();

      while ($Qstatuses->next()) {
        $email_order .= lC_DateTime::getLong($Qstatuses->value('date_added')) . "\n\t" . wordwrap(str_replace("\n", "\n\t", $Qstatuses->value('comments')), 60, "\n\t", 1) . "\n\n";
      }

      unset($Qstatuses);

      if (is_object($lC_ShoppingCart)) {
        $email_order .= $lC_Language->get('email_order_payment_method') . "\n" .
                        $lC_Language->get('email_order_separator') . "\n";
        
        $email_order .= $lC_ShoppingCart->getBillingMethod('title') . "\n\n";
        
        /*if (isset($this->email_footer)) {
          $email_order .= $this->email_footer . "\n\n";
        }*/
      }
      
      lc_email($Qorder->value('customers_name'), $Qorder->value('customers_email_address'), $lC_Language->get('email_order_subject'), $email_order, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
      
      // send emails to other people
      if (SEND_EXTRA_ORDER_EMAILS_TO != '') {
        lc_email('', SEND_EXTRA_ORDER_EMAILS_TO, $lC_Language->get('email_order_subject'), $email_order, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
      }
    }
    unset($Qorder);
  }

  public function &getListing($limit = null, $page_keyword = 'page') {
    global $lC_Database, $lC_Customer, $lC_Language;

    $Qorders = $lC_Database->query('select o.orders_id, o.date_purchased, o.delivery_name, o.delivery_country, o.billing_name, o.billing_country, ot.text as order_total, s.orders_status_name from :table_orders o, :table_orders_total ot, :table_orders_status s where o.customers_id = :customers_id and o.orders_id = ot.orders_id and ot.class = "total" and o.orders_status = s.orders_status_id and s.language_id = :language_id order by orders_id desc');
    $Qorders->bindTable(':table_orders', TABLE_ORDERS);
    $Qorders->bindTable(':table_orders_total', TABLE_ORDERS_TOTAL);
    $Qorders->bindTable(':table_orders_status', TABLE_ORDERS_STATUS);
    $Qorders->bindInt(':customers_id', $lC_Customer->getID());
    $Qorders->bindInt(':language_id', $lC_Language->getID());

    if (is_numeric($limit)) {
      $Qorders->setBatchLimit(isset($_GET[$page_keyword]) && is_numeric($_GET[$page_keyword]) ? $_GET[$page_keyword] : 1, $limit);
    }

    $Qorders->execute();

    return $Qorders;
  }

  public function &getStatusListing($id = null) {
    global $lC_Database, $lC_Language;

    if ( ($id === null) && isset($this) ) {
      $id = $this->_id;
    }

    $Qstatus = $lC_Database->query('select os.orders_status_name, osh.date_added, osh.comments from :table_orders_status os, :table_orders_status_history osh where osh.orders_id = :orders_id and osh.orders_status_id = os.orders_status_id and os.language_id = :language_id order by osh.date_added');
    $Qstatus->bindTable(':table_orders_status', TABLE_ORDERS_STATUS);
    $Qstatus->bindTable(':table_orders_status_history', TABLE_ORDERS_STATUS_HISTORY);
    $Qstatus->bindInt(':orders_id', $id);
    $Qstatus->bindInt(':language_id', $lC_Language->getID());

    return $Qstatus;
  }

  public function getCustomerID($id = null) {
    global $lC_Database;

    if ( ($id === null) && isset($this) ) {
      $id = $this->_id;
    }

    $Qcustomer = $lC_Database->query('select customers_id from :table_orders where orders_id = :orders_id');
    $Qcustomer->bindTable(':table_orders', TABLE_ORDERS);
    $Qcustomer->bindInt(':orders_id', $id);
    $Qcustomer->execute();

    return $Qcustomer->valueInt('customers_id');
  }

  public function numberOfEntries() {
    global $lC_Database, $lC_Customer;
    static $total_entries;

    if (is_numeric($total_entries) === false) {
      if ($lC_Customer->isLoggedOn()) {
        $Qorders = $lC_Database->query('select count(*) as total from :table_orders where customers_id = :customers_id');
        $Qorders->bindTable(':table_orders', TABLE_ORDERS);
        $Qorders->bindInt(':customers_id', $lC_Customer->getID());
        $Qorders->execute();

        $total_entries = $Qorders->valueInt('total');
      } else {
        $total_entries = 0;
      }
    }

    return $total_entries;
  }

  public function numberOfProducts($id = null) {
    global $lC_Database;

    if ( ($id === null) && isset($this) ) {
      $id = $this->_id;
    }

    $Qproducts = $lC_Database->query('select count(*) as total from :table_orders_products where orders_id = :orders_id');
    $Qproducts->bindTable(':table_orders_products', TABLE_ORDERS_PRODUCTS);
    $Qproducts->bindInt(':orders_id', $id);
    $Qproducts->execute();

    return $Qproducts->valueInt('total');
  }

  public function exists($id, $customer_id = null) {
    global $lC_Database;

    $Qorder = $lC_Database->query('select orders_id from :table_orders where orders_id = :orders_id');

    if (isset($customer_id) && is_numeric($customer_id)) {
      $Qorder->appendQuery('and customers_id = :customers_id');
      $Qorder->bindInt(':customers_id', $customer_id);
    }

    $Qorder->appendQuery('limit 1');
    $Qorder->bindTable(':table_orders', TABLE_ORDERS);
    $Qorder->bindInt(':orders_id', $id);
    $Qorder->execute();

    return ($Qorder->numberOfRows() === 1);
  }

  public function query($order_id) {
    global $lC_Database, $lC_Language;

    $Qorder = $lC_Database->query('select * from :table_orders where orders_id = :orders_id');
    $Qorder->bindTable(':table_orders', TABLE_ORDERS);
    $Qorder->bindInt(':orders_id', $order_id);
    $Qorder->execute();

    $Qtotals = $lC_Database->query('select title, text, class from :table_orders_total where orders_id = :orders_id order by sort_order');
    $Qtotals->bindTable(':table_orders_total', TABLE_ORDERS_TOTAL);
    $Qtotals->bindInt(':orders_id', $order_id);
    $Qtotals->execute();

    $shipping_method_string = '';
    $order_total_string = '';

    while ($Qtotals->next()) {
      $this->totals[] = array('title' => $Qtotals->value('title'),
                              'text' => $Qtotals->value('text'));

      if ($Qtotals->value('class') == 'shipping') {
        $shipping_method_string = strip_tags($Qtotals->value('title'));

        if (substr($shipping_method_string, -1) == ':') {
          $shipping_method_string = substr($Qtotals->value('title'), 0, -1);
        }
      }

      if ($Qtotals->value('class') == 'total') {
        $order_total_string = strip_tags($Qtotals->value('text'));
      }
    }

    $Qstatus = $lC_Database->query('select orders_status_name from :table_orders_status where orders_status_id = :orders_status_id and language_id = :language_id');
    $Qstatus->bindTable(':table_orders_status', TABLE_ORDERS_STATUS);
    $Qstatus->bindInt(':orders_status_id', $Qorder->valueInt('orders_status'));
    $Qstatus->bindInt(':language_id', $lC_Language->getID());
    $Qstatus->execute();

    $this->info = array('currency' => $Qorder->value('currency'),
                        'currency_value' => $Qorder->value('currency_value'),
                        'payment_method' => $Qorder->value('payment_method'),
                        'date_purchased' => $Qorder->value('date_purchased'),
                        'orders_status' => $Qstatus->value('orders_status_name'),
                        'last_modified' => $Qorder->value('last_modified'),
                        'total' => $order_total_string,
                        'shipping_method' => $shipping_method_string);

    $this->customer = array('id' => $Qorder->valueInt('customers_id'),
                            'name' => $Qorder->valueProtected('customers_name'),
                            'company' => $Qorder->valueProtected('customers_company'),
                            'street_address' => $Qorder->valueProtected('customers_street_address'),
                            'suburb' => $Qorder->valueProtected('customers_suburb'),
                            'city' => $Qorder->valueProtected('customers_city'),
                            'postcode' => $Qorder->valueProtected('customers_postcode'),
                            'state' => $Qorder->valueProtected('customers_state'),
                            'zone_code' => $Qorder->value('customers_state_code'),
                            'country_title' => $Qorder->valueProtected('customers_country'),
                            'country_iso2' => $Qorder->value('customers_country_iso2'),
                            'country_iso3' => $Qorder->value('customers_country_iso3'),
                            'format' => $Qorder->value('customers_address_format'),
                            'telephone' => $Qorder->valueProtected('customers_telephone'),
                            'email_address' => $Qorder->valueProtected('customers_email_address'));

    $this->delivery = array('name' => $Qorder->valueProtected('delivery_name'),
                            'company' => $Qorder->valueProtected('delivery_company'),
                            'street_address' => $Qorder->valueProtected('delivery_street_address'),
                            'suburb' => $Qorder->valueProtected('delivery_suburb'),
                            'city' => $Qorder->valueProtected('delivery_city'),
                            'postcode' => $Qorder->valueProtected('delivery_postcode'),
                            'state' => $Qorder->valueProtected('delivery_state'),
                            'zone_code' => $Qorder->value('delivery_state_code'),
                            'country_title' => $Qorder->valueProtected('delivery_country'),
                            'country_iso2' => $Qorder->value('delivery_country_iso2'),
                            'country_iso3' => $Qorder->value('delivery_country_iso3'),
                            'format' => $Qorder->value('delivery_address_format'));

    if (empty($this->delivery['name']) && empty($this->delivery['street_address'])) {
      $this->delivery = false;
    }

    $this->billing = array('name' => $Qorder->valueProtected('billing_name'),
                           'company' => $Qorder->valueProtected('billing_company'),
                           'street_address' => $Qorder->valueProtected('billing_street_address'),
                           'suburb' => $Qorder->valueProtected('billing_suburb'),
                           'city' => $Qorder->valueProtected('billing_city'),
                           'postcode' => $Qorder->valueProtected('billing_postcode'),
                           'state' => $Qorder->valueProtected('billing_state'),
                           'zone_code' => $Qorder->value('billing_state_code'),
                           'country_title' => $Qorder->valueProtected('billing_country'),
                           'country_iso2' => $Qorder->value('billing_country_iso2'),
                           'country_iso3' => $Qorder->value('billing_country_iso3'),
                           'format' => $Qorder->value('billing_address_format'));

    $Qproducts = $lC_Database->query('select orders_products_id, products_id, products_name, products_model, products_price, products_tax, products_quantity from :table_orders_products where orders_id = :orders_id');
    $Qproducts->bindTable(':table_orders_products', TABLE_ORDERS_PRODUCTS);
    $Qproducts->bindInt(':orders_id', $order_id);
    $Qproducts->execute();

    $index = 0;

    while ($Qproducts->next()) {
      $subindex = 0;

      $this->products[$index] = array('qty' => $Qproducts->valueInt('products_quantity'),
                                      'id' => $Qproducts->valueInt('products_id'),
                                      'name' => $Qproducts->value('products_name'),
                                      'model' => $Qproducts->value('products_model'),
                                      'tax' => $Qproducts->value('products_tax'),
                                      'price' => $Qproducts->value('products_price'));

      $Qvariants = $lC_Database->query('select group_title, value_title from :table_orders_products_variants where orders_id = :orders_id and orders_products_id = :orders_products_id order by id');
      $Qvariants->bindTable(':table_orders_products_variants', TABLE_ORDERS_PRODUCTS_VARIANTS);
      $Qvariants->bindInt(':orders_id', $order_id);
      $Qvariants->bindInt(':orders_products_id', $Qproducts->valueInt('orders_products_id'));
      $Qvariants->execute();

      if ( $Qvariants->numberOfRows() ) {
        while ( $Qvariants->next() ) {
          $this->products[$index]['attributes'][$subindex] = array('option' => $Qvariants->value('group_title'),
                                                                   'value' => $Qvariants->value('value_title'));

          $subindex++;
        }
      }

      $this->info['tax_groups']["{$this->products[$index]['tax']}"] = '1';

      $index++;
    }
  }
}
?>