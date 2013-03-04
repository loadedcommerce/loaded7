<?php
/**
  $Id: address_book.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
class lC_Account_Address_book extends lC_Template {

  /* Private variables */
  var $_module = 'address_book',
      $_group = 'account',
      $_page_title,
      $_page_contents = 'address_book.php',
      $_page_image = 'table_background_address_book.gif';

  /* Class constructor */
  function lC_Account_Address_book() {
    global $lC_Language, $lC_Services, $lC_Breadcrumb, $lC_Customer, $lC_MessageStack, $lC_Vqmod;

    require_once($lC_Vqmod->modCheck('includes/classes/address_book.php'));

    $this->_page_title = $lC_Language->get('address_book_heading');

    if ($lC_Services->isStarted('breadcrumb')) {
      $lC_Breadcrumb->add($lC_Language->get('breadcrumb_address_book'), lc_href_link(FILENAME_ACCOUNT, $this->_module, 'SSL'));
    }

    if ($lC_Customer->hasDefaultAddress() === false) {
      $this->_page_title = $lC_Language->get('address_book_add_entry_heading');
      $this->_page_contents = 'address_book_process.php';

      //$this->addJavascriptPhpFilename('includes/form_check.js.php');
    } elseif (isset($_GET['new'])) {
      if ($lC_Services->isStarted('breadcrumb')) {
        $lC_Breadcrumb->add($lC_Language->get('breadcrumb_address_book_add_entry'), lc_href_link(FILENAME_ACCOUNT, $this->_module . '&new', 'SSL'));
      }

      $this->_page_title = $lC_Language->get('address_book_add_entry_heading');
      $this->_page_contents = 'address_book_process.php';

      $this->addJavascriptPhpFilename('templates/' . $this->getCode() . '/javascript/addressBookDetails.js.php');
    } elseif (isset($_GET['edit']) && is_numeric($_GET[$this->_module])) {
      if (!lC_AddressBook::checkEntry($_GET['address_book'])) {
        $lC_MessageStack->add('address_book', $lC_Language->get('error_address_book_entry_non_existing'), 'error');
      }

      if ($lC_MessageStack->size('address_book') == 0) {
        if ($lC_Services->isStarted('breadcrumb')) {
          $lC_Breadcrumb->add($lC_Language->get('breadcrumb_address_book_edit_entry'), lc_href_link(FILENAME_ACCOUNT, $this->_module . '=' . $_GET[$this->_module] . '&edit', 'SSL'));
        }

        $this->_page_title = $lC_Language->get('address_book_edit_entry_heading');
        $this->_page_contents = 'address_book_process.php';

        $this->addJavascriptPhpFilename('templates/' . $this->getCode() . '/javascript/addressBookDetails.js.php');
      }
    } elseif (isset($_GET['delete']) && is_numeric($_GET[$this->_module])) {
      if ($_GET['address_book'] == $lC_Customer->getDefaultAddressID()) {
        $lC_MessageStack->add('address_book', $lC_Language->get('warning_primary_address_deletion'), 'warning');
      } else {
        if (!lC_AddressBook::checkEntry($_GET['address_book'])) {
          $lC_MessageStack->add('address_book', $lC_Language->get('error_address_book_entry_non_existing'), 'error');
        }
      }

      if ($lC_MessageStack->size('address_book') == 0) {
        if ($lC_Services->isStarted('breadcrumb')) {
          $lC_Breadcrumb->add($lC_Language->get('breadcrumb_address_book_delete_entry'), lc_href_link(FILENAME_ACCOUNT, $this->_module . '=' . $_GET[$this->_module] . '&delete', 'SSL'));
        }

        $this->_page_title = $lC_Language->get('address_book_delete_entry_heading');
        $this->_page_contents = 'address_book_delete.php';
      }
    }

    if (isset($_GET['new']) && ($_GET['new'] == 'save')) {
      if (lC_AddressBook::numberOfEntries() >= MAX_ADDRESS_BOOK_ENTRIES) {
        $lC_MessageStack->add('address_book', $lC_Language->get('error_address_book_full'));

        $this->_page_title = $lC_Language->get('address_book_heading');
        $this->_page_contents = 'address_book.php';
      } else {
        $this->_process();
      }
    } elseif (isset($_GET['edit']) && ($_GET['edit'] == 'save')) {
      $this->_process($_GET[$this->_module]);
    } elseif (isset($_GET['delete']) && ($_GET['delete'] == 'confirm') && is_numeric($_GET[$this->_module])) {
      $this->_delete($_GET[$this->_module]);
    }
  }

  /* Private methods */
  function _process($id = '') {
    global $lC_MessageStack, $lC_Database, $lC_Language, $lC_Customer, $lC_Vqmod, $entry_state_has_zones;

    require_once($lC_Vqmod->modCheck('includes/classes/address_book.php'));

    $data = array();

    if (ACCOUNT_GENDER >= 0) {
      if (isset($_POST['gender']) && (($_POST['gender'] == 'm') || ($_POST['gender'] == 'f'))) {
        $data['gender'] = $_POST['gender'];
      } else {
        $lC_MessageStack->add('address_book', $lC_Language->get('field_customer_gender_error'));
      }
    }

    if (isset($_POST['firstname']) && (strlen(trim($_POST['firstname'])) >= ACCOUNT_FIRST_NAME)) {
      $data['firstname'] = $_POST['firstname'];
    } else {
      $lC_MessageStack->add('address_book', sprintf($lC_Language->get('field_customer_first_name_error'), ACCOUNT_FIRST_NAME));
    }

    if (isset($_POST['lastname']) && (strlen(trim($_POST['lastname'])) >= ACCOUNT_LAST_NAME)) {
      $data['lastname'] = $_POST['lastname'];
    } else {
      $lC_MessageStack->add('address_book', sprintf($lC_Language->get('field_customer_last_name_error'), ACCOUNT_LAST_NAME));
    }

    if (ACCOUNT_COMPANY > -1) {
      if (isset($_POST['company']) && (strlen(trim($_POST['company'])) >= ACCOUNT_COMPANY)) {
        $data['company'] = $_POST['company'];
      } else {
        $lC_MessageStack->add('address_book', sprintf($lC_Language->get('field_customer_company_error'), ACCOUNT_COMPANY));
      }
    }

    if (isset($_POST['street_address']) && (strlen(trim($_POST['street_address'])) >= ACCOUNT_STREET_ADDRESS)) {
      $data['street_address'] = $_POST['street_address'];
    } else {
      $lC_MessageStack->add('address_book', sprintf($lC_Language->get('field_customer_street_address_error'), ACCOUNT_STREET_ADDRESS));
    }

    if (ACCOUNT_SUBURB >= 0) {
      if (isset($_POST['suburb']) && (strlen(trim($_POST['suburb'])) >= ACCOUNT_SUBURB)) {
        $data['suburb'] = $_POST['suburb'];
      } else {
        $lC_MessageStack->add('address_book', sprintf($lC_Language->get('field_customer_suburb_error'), ACCOUNT_SUBURB));
      }
    }

    if (ACCOUNT_POST_CODE > -1) {
      if (isset($_POST['postcode']) && (strlen(trim($_POST['postcode'])) >= ACCOUNT_POST_CODE)) {
        $data['postcode'] = $_POST['postcode'];
      } else {
        $lC_MessageStack->add('address_book', sprintf($lC_Language->get('field_customer_post_code_error'), ACCOUNT_POST_CODE));
      }
    }

    if (isset($_POST['city']) && (strlen(trim($_POST['city'])) >= ACCOUNT_CITY)) {
      $data['city'] = $_POST['city'];
    } else {
      $lC_MessageStack->add('address_book', sprintf($lC_Language->get('field_customer_city_error'), ACCOUNT_CITY));
    }

    if (ACCOUNT_STATE >= 0) {
      $Qcheck = $lC_Database->query('select zone_id from :table_zones where zone_country_id = :zone_country_id limit 1');
      $Qcheck->bindTable(':table_zones', TABLE_ZONES);
      $Qcheck->bindInt(':zone_country_id', $_POST['country']);
      $Qcheck->execute();

      $entry_state_has_zones = ($Qcheck->numberOfRows() > 0);

      if ($entry_state_has_zones === true) {
        $Qzone = $lC_Database->query('select zone_id from :table_zones where zone_country_id = :zone_country_id and zone_code like :zone_code');
        $Qzone->bindTable(':table_zones', TABLE_ZONES);
        $Qzone->bindInt(':zone_country_id', $_POST['country']);
        $Qzone->bindValue(':zone_code', $_POST['state']);
        $Qzone->execute();

        if ($Qzone->numberOfRows() === 1) {
          $data['zone_id'] = $Qzone->valueInt('zone_id');
        } else {
          $Qzone = $lC_Database->query('select zone_id from :table_zones where zone_country_id = :zone_country_id and zone_name like :zone_name');
          $Qzone->bindTable(':table_zones', TABLE_ZONES);
          $Qzone->bindInt(':zone_country_id', $_POST['country']);
          $Qzone->bindValue(':zone_name', $_POST['state'] . '%');
          $Qzone->execute();

          if ($Qzone->numberOfRows() === 1) {
            $data['zone_id'] = $Qzone->valueInt('zone_id');
          } else {
            $lC_MessageStack->add('address_book', $lC_Language->get('field_customer_state_select_pull_down_error'));
          }
        }
      } else {
        if (strlen(trim($_POST['state'])) >= ACCOUNT_STATE) {
          $data['state'] = $_POST['state'];
        } else {
          $lC_MessageStack->add('address_book', sprintf($lC_Language->get('field_customer_state_error'), ACCOUNT_STATE));
        }
      }
    } else {
      if (strlen(trim($_POST['state'])) >= ACCOUNT_STATE) {
        $data['state'] = $_POST['state'];
      } else {
        $lC_MessageStack->add('address_book', sprintf($lC_Language->get('field_customer_state_error'), ACCOUNT_STATE));
      }
    }

    if (isset($_POST['country']) && is_numeric($_POST['country']) && ($_POST['country'] >= 1)) {
      $data['country'] = $_POST['country'];
    } else {
      $lC_MessageStack->add('address_book', $lC_Language->get('field_customer_country_error'));
    }

    if (ACCOUNT_TELEPHONE >= 0) {
      if (isset($_POST['telephone']) && (strlen(trim($_POST['telephone'])) >= ACCOUNT_TELEPHONE)) {
        $data['telephone'] = $_POST['telephone'];
      } else {
        $lC_MessageStack->add('address_book', sprintf($lC_Language->get('field_customer_telephone_number_error'), ACCOUNT_TELEPHONE));
      }
    }

    if (ACCOUNT_FAX >= 0) {
      if (isset($_POST['fax']) && (strlen(trim($_POST['fax'])) >= ACCOUNT_FAX)) {
        $data['fax'] = $_POST['fax'];
      } else {
        $lC_MessageStack->add('address_book', sprintf($lC_Language->get('field_customer_fax_number_error'), ACCOUNT_FAX));
      }
    }

    if ( ($lC_Customer->hasDefaultAddress() === false) || (isset($_POST['primary']) && ($_POST['primary'] == 'on')) ) {
      $data['primary'] = true;
    }

    if ($lC_MessageStack->size('address_book') === 0) {
      if (lC_AddressBook::saveEntry($data, $id)) {
        $lC_MessageStack->add('address_book', $lC_Language->get('success_address_book_entry_updated'), 'success');
      }

      lc_redirect(lc_href_link(FILENAME_ACCOUNT, 'address_book', 'SSL'));
    }
  }

  function _delete($id) {
    global $lC_MessageStack, $lC_Language, $lC_Customer;

    if ($id != $lC_Customer->getDefaultAddressID()) {
      if (lC_AddressBook::deleteEntry($id)) {
        $lC_MessageStack->add('address_book', $lC_Language->get('success_address_book_entry_deleted'), 'success');
      }
    } else {
      $lC_MessageStack->add('address_book', $lC_Language->get('warning_primary_address_deletion'), 'warning');
    }

    lc_redirect(lc_href_link(FILENAME_ACCOUNT, 'address_book', 'SSL'));
  }
}
?>