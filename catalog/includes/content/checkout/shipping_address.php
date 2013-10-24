<?php
/**
  $Id: shipping_address.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
  class lC_Checkout_Shipping_address extends lC_Template {

    /* Private variables */
    var $_module = 'shipping_address',
        $_group = 'checkout',
        $_page_title,
        $_page_contents = 'checkout_shipping_address.php',
        $_page_image = 'table_background_delivery.gif';

    /* Class constructor */
    function lC_Checkout_Shipping_address() {
      global $lC_Session, $lC_ShoppingCart, $lC_Customer, $lC_Services, $lC_Language, $lC_NavigationHistory, $lC_Breadcrumb, $lC_Vqmod;

      require_once($lC_Vqmod->modCheck('includes/classes/address_book.php'));

      if ($lC_Customer->isLoggedOn() === false) {
        $lC_NavigationHistory->setSnapshot();

        lc_redirect(lc_href_link(FILENAME_ACCOUNT, 'login', 'SSL'));
      }

      if ($lC_ShoppingCart->hasContents() === false) {
        lc_redirect(lc_href_link(FILENAME_CHECKOUT, null, 'SSL'));
      }

      $this->_page_title = $lC_Language->get('shipping_address_heading');

      $this->addJavascriptPhpFilename('templates/' . $this->getCode() . '/javascript/addressBookDetails.js.php');

      // if the order contains only virtual products, forward the customer to the billing page as
      // a shipping address is not needed
      if ($lC_ShoppingCart->getContentType() == 'virtual' || (defined('SKIP_CHECKOUT_SHIPPING_PAGE') && SKIP_CHECKOUT_SHIPPING_PAGE == '1')) {
        $lC_ShoppingCart->resetShippingAddress();
        $lC_ShoppingCart->resetShippingMethod();

        lc_redirect(lc_href_link(FILENAME_CHECKOUT, 'payment', 'SSL'));
      }

      // if no shipping destination address was selected, use their own address as default
      if ($lC_ShoppingCart->hasShippingAddress() === false) {
        $lC_ShoppingCart->setShippingAddress($lC_Customer->getDefaultAddressID());
      }

      if ($lC_Services->isStarted('breadcrumb')) {
        $lC_Breadcrumb->add($lC_Language->get('breadcrumb_checkout_shipping'), lc_href_link(FILENAME_CHECKOUT, 'shipping', 'SSL'));
        $lC_Breadcrumb->add($lC_Language->get('breadcrumb_checkout_shipping_address'), lc_href_link(FILENAME_CHECKOUT, $this->_module, 'SSL'));
      }

      if (($_GET[$this->_module] == 'process')) {
        $this->_process();
      }
    }

    function &getListing() {
      global $lC_Database, $lC_Customer;

      $Qaddresses = $lC_Database->query('select ab.address_book_id, ab.entry_firstname as firstname, ab.entry_lastname as lastname, ab.entry_company as company, ab.entry_street_address as street_address, ab.entry_suburb as suburb, ab.entry_city as city, ab.entry_postcode as postcode, ab.entry_state as state, ab.entry_zone_id as zone_id, ab.entry_country_id as country_id, z.zone_code as zone_code, c.countries_name as country_title from :table_address_book ab left join :table_zones z on (ab.entry_zone_id = z.zone_id), :table_countries c where ab.customers_id = :customers_id and ab.entry_country_id = c.countries_id');
      $Qaddresses->bindTable(':table_address_book', TABLE_ADDRESS_BOOK);
      $Qaddresses->bindTable(':table_zones', TABLE_ZONES);
      $Qaddresses->bindTable(':table_countries', TABLE_COUNTRIES);
      $Qaddresses->bindInt(':customers_id', $lC_Customer->getID());
      $Qaddresses->execute();

      return $Qaddresses;
    }

    /* Private methods */
    function _process() {
      global $lC_Database, $lC_Session, $lC_Language, $lC_Customer, $lC_ShoppingCart, $lC_MessageStack, $lC_Vqmod, $entry_state_has_zones;

      require_once($lC_Vqmod->modCheck('includes/classes/address_book.php'));

      // process a new shipping address
      if (($lC_Customer->hasDefaultAddress() === false) || (!empty($_POST['firstname']) && !empty($_POST['lastname']) && !empty($_POST['street_address'])) ) {
        if (ACCOUNT_GENDER > 0) {
          if (!isset($_POST['gender']) || (($_POST['gender'] != 'm') && ($_POST['gender'] != 'f'))) {
            $lC_MessageStack->add('checkout_address', $lC_Language->get('field_customer_gender_error'));
          }
        }

        if (!isset($_POST['firstname']) || (strlen(trim($_POST['firstname'])) < ACCOUNT_FIRST_NAME)) {
          $lC_MessageStack->add('checkout_address', sprintf($lC_Language->get('field_customer_first_name_error'), ACCOUNT_FIRST_NAME));
        }

        if (!isset($_POST['lastname']) || (strlen(trim($_POST['lastname'])) < ACCOUNT_LAST_NAME)) {
          $lC_MessageStack->add('checkout_address', sprintf($lC_Language->get('field_customer_last_name_error'), ACCOUNT_LAST_NAME));
        }

        if (ACCOUNT_COMPANY > 0) {
          if (!isset($_POST['company']) || (strlen(trim($_POST['company'])) < ACCOUNT_COMPANY)) {
            $lC_MessageStack->add('checkout_address', sprintf($lC_Language->get('field_customer_company_error'), ACCOUNT_COMPANY));
          }
        }

        if (!isset($_POST['street_address']) || (strlen(trim($_POST['street_address'])) < ACCOUNT_STREET_ADDRESS)) {
          $lC_MessageStack->add('checkout_address', sprintf($lC_Language->get('field_customer_street_address_error'), ACCOUNT_STREET_ADDRESS));
        }

        if (ACCOUNT_SUBURB > 0) {
          if (!isset($_POST['suburb']) || (strlen(trim($_POST['suburb'])) < ACCOUNT_SUBURB)) {
            $lC_MessageStack->add('checkout_address', sprintf($lC_Language->get('field_customer_suburb_error'), ACCOUNT_SUBURB));
          }
        }

        if (ACCOUNT_POST_CODE > 0) {
          if (!isset($_POST['postcode']) || (strlen(trim($_POST['postcode'])) < ACCOUNT_POST_CODE)) {
            $lC_MessageStack->add('checkout_address', sprintf($lC_Language->get('field_customer_post_code_error'), ACCOUNT_POST_CODE));
          }
        }

        if (!isset($_POST['city']) || (strlen(trim($_POST['city'])) < ACCOUNT_CITY)) {
          $lC_MessageStack->add('checkout_address', sprintf($lC_Language->get('field_customer_city_error'), ACCOUNT_CITY));
        }

        if (ACCOUNT_STATE > 0) {
          $zone_id = 0;

          $Qcheck = $lC_Database->query('select zone_id from :table_zones where zone_country_id = :zone_country_id limit 1');
          $Qcheck->bindTable(':table_zones', TABLE_ZONES);
          $Qcheck->bindInt(':zone_country_id', $_POST['country']);
          $Qcheck->execute();

          $entry_state_has_zones = ($Qcheck->numberOfRows() > 0);

          $Qcheck->freeResult();

          if ($entry_state_has_zones === true) {
            $Qzone = $lC_Database->query('select zone_id from :table_zones where zone_country_id = :zone_country_id and zone_code like :zone_code');
            $Qzone->bindTable(':table_zones', TABLE_ZONES);
            $Qzone->bindInt(':zone_country_id', $_POST['country']);
            $Qzone->bindValue(':zone_code', $_POST['state']);
            $Qzone->execute();

            if ($Qzone->numberOfRows() === 1) {
              $zone_id = $Qzone->valueInt('zone_id');
            } else {
              $Qzone = $lC_Database->query('select zone_id from :table_zones where zone_country_id = :zone_country_id and zone_name like :zone_name');
              $Qzone->bindTable(':table_zones', TABLE_ZONES);
              $Qzone->bindInt(':zone_country_id', $_POST['country']);
              $Qzone->bindValue(':zone_name', $_POST['state'] . '%');
              $Qzone->execute();

              if ($Qzone->numberOfRows() === 1) {
                $zone_id = $Qzone->valueInt('zone_id');
              } else {
                $lC_MessageStack->add('checkout_address', $lC_Language->get('field_customer_state_select_pull_down_error'));
              }
            }

            $Qzone->freeResult();
          } else {
            if (strlen(trim($_POST['state'])) < ACCOUNT_STATE) {
              $lC_MessageStack->add('checkout_address', sprintf($lC_Language->get('field_customer_state_error'), ACCOUNT_STATE));
            }
          }
        }

        if ( (is_numeric($_POST['country']) === false) || ($_POST['country'] < 1) ) {
          $lC_MessageStack->add('checkout_address', $lC_Language->get('field_customer_country_error'));
        }

        if (ACCOUNT_TELEPHONE > 0) {
          if (!isset($_POST['telephone']) || (strlen(trim($_POST['telephone'])) < ACCOUNT_TELEPHONE)) {
            $lC_MessageStack->add('checkout_address', sprintf($lC_Language->get('field_customer_telephone_number_error'), ACCOUNT_TELEPHONE));
          }
        }

        if (ACCOUNT_FAX > 0) {
          if (!isset($_POST['fax']) || (strlen(trim($_POST['fax'])) < ACCOUNT_FAX)) {
            $lC_MessageStack->add('checkout_address', sprintf($lC_Language->get('field_customer_fax_number_error'), ACCOUNT_FAX));
          }
        }

        if ($lC_MessageStack->size('checkout_address') === 0) {
          $Qab = $lC_Database->query('insert into :table_address_book (customers_id, entry_gender, entry_company, entry_firstname, entry_lastname, entry_street_address, entry_suburb, entry_postcode, entry_city, entry_state, entry_country_id, entry_zone_id, entry_telephone, entry_fax) values (:customers_id, :entry_gender, :entry_company, :entry_firstname, :entry_lastname, :entry_street_address, :entry_suburb, :entry_postcode, :entry_city, :entry_state, :entry_country_id, :entry_zone_id, :entry_telephone, :entry_fax)');
          $Qab->bindTable(':table_address_book', TABLE_ADDRESS_BOOK);
          $Qab->bindInt(':customers_id', $lC_Customer->getID());
          $Qab->bindValue(':entry_gender', (((ACCOUNT_GENDER > -1) && isset($_POST['gender']) && (($_POST['gender'] == 'm') || ($_POST['gender'] == 'f'))) ? $_POST['gender'] : ''));
          $Qab->bindValue(':entry_company', ((ACCOUNT_COMPANY > -1) ? trim($_POST['company']) : ''));
          $Qab->bindValue(':entry_firstname', trim($_POST['firstname']));
          $Qab->bindValue(':entry_lastname', trim($_POST['lastname']));
          $Qab->bindValue(':entry_street_address', trim($_POST['street_address']));
          $Qab->bindValue(':entry_suburb', ((ACCOUNT_SUBURB > -1) ? trim($_POST['suburb']) : ''));
          $Qab->bindValue(':entry_postcode', ((ACCOUNT_POST_CODE > -1) ? trim($_POST['postcode']) : ''));
          $Qab->bindValue(':entry_city', trim($_POST['city']));
          $Qab->bindValue(':entry_state', ((ACCOUNT_STATE > -1) ? (($zone_id > 0) ? '' : trim($_POST['state'])) : ''));
          $Qab->bindInt(':entry_country_id', $_POST['country']);
          $Qab->bindInt(':entry_zone_id', ((ACCOUNT_STATE > -1) ? (($zone_id > 0) ? $zone_id : 0) : ''));
          $Qab->bindValue(':entry_telephone', ((ACCOUNT_TELEPHONE > -1) ? trim($_POST['telephone']) : ''));
          $Qab->bindValue(':entry_fax', ((ACCOUNT_FAX > -1) ? trim($_POST['fax']) : ''));
          $Qab->execute();

          if ($Qab->affectedRows() === 1) {
            $address_book_id = $lC_Database->nextID();

            if ($lC_Customer->hasDefaultAddress() === false) {
              $Qcustomer = $lC_Database->query('update :table_customers set customers_default_address_id = :customers_default_address_id where customers_id = :customers_id');
              $Qcustomer->bindTable(':table_customers', TABLE_CUSTOMERS);
              $Qcustomer->bindInt(':customers_default_address_id', $address_book_id);
              $Qcustomer->bindInt(':customers_id', $lC_Customer->getID());
              $Qcustomer->execute();

              $lC_Customer->setCountryID($_POST['country']);
              $lC_Customer->setZoneID($zone_id);
              $lC_Customer->setDefaultAddressID($address_book_id);
            }

            $lC_ShoppingCart->setShippingAddress($address_book_id);

            lc_redirect(lc_href_link(FILENAME_CHECKOUT, 'shipping', 'SSL'));
          } else {
            $lC_MessageStack->add('checkout_address', 'Error inserting into address book table.');
          }
        }
        // process the selected shipping destination
      } elseif (isset($_POST['address'])) {
               
        $lC_ShoppingCart->setShippingAddress($_POST['address']);        

        $Qcheck = $lC_Database->query('select address_book_id from :table_address_book where address_book_id = :address_book_id and customers_id = :customers_id limit 1');
        $Qcheck->bindTable(':table_address_book', TABLE_ADDRESS_BOOK);
        $Qcheck->bindInt(':address_book_id', $lC_ShoppingCart->getShippingAddress('id'));
        $Qcheck->bindInt(':customers_id', $lC_Customer->getID());
        $Qcheck->execute();

        if ($Qcheck->numberOfRows() === 1) {          
          lc_redirect(lc_href_link(FILENAME_CHECKOUT, 'shipping', 'SSL'));
        } else {
          $lC_ShoppingCart->resetShippingAddress();
        }
      } else {
        $lC_ShoppingCart->setShippingAddress($lC_Customer->getDefaultAddressID());

        lc_redirect(lc_href_link(FILENAME_CHECKOUT, 'shipping', 'SSL'));
      }
    }
  }
?>