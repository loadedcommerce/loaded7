<?php
/*
  $Id: edit.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
class lC_Account_Edit extends lC_Template {

  /* Private variables */
  var $_module = 'edit',
      $_group = 'account',
      $_page_title,
      $_page_contents = 'account_edit.php',
      $_page_image = 'table_background_account.gif';

  /* Class constructor */
  function lC_Account_Edit() {
    global $lC_Language, $lC_Services, $lC_Breadcrumb, $lC_Vqmod;

    require_once($lC_Vqmod->modCheck('includes/classes/account.php'));

    $this->_page_title = $lC_Language->get('account_edit_heading');

    $this->addJavascriptPhpFilename('templates/' . $this->getCode() . '/javascript/account/account_edit.js.php');

    if ($lC_Services->isStarted('breadcrumb')) {
      $lC_Breadcrumb->add($lC_Language->get('breadcrumb_edit_account'), lc_href_link(FILENAME_ACCOUNT, $this->_module, 'SSL'));
    }

    if ($_GET[$this->_module] == 'save') {
      $this->_process();
    }
  }

  /* Private methods */
  function _process() {
    global $lC_MessageStack, $lC_Database, $lC_Language, $lC_Customer, $lC_Vqmod;

    require_once($lC_Vqmod->modCheck('includes/classes/account.php'));
    
    $data = array();

    if (ACCOUNT_GENDER >= 0) {
      if (isset($_POST['gender']) && (($_POST['gender'] == 'm') || ($_POST['gender'] == 'f'))) {
        $data['gender'] = $_POST['gender'];
      } else {
        $lC_MessageStack->add('account_edit', $lC_Language->get('field_customer_gender_error'));
      }
    }

    if (isset($_POST['firstname']) && (strlen(trim($_POST['firstname'])) >= ACCOUNT_FIRST_NAME)) {
      $data['firstname'] = $_POST['firstname'];
    } else {
      $lC_MessageStack->add('account_edit', sprintf($lC_Language->get('field_customer_first_name_error'), ACCOUNT_FIRST_NAME));
    }

    if (isset($_POST['lastname']) && (strlen(trim($_POST['lastname'])) >= ACCOUNT_LAST_NAME)) {
      $data['lastname'] = $_POST['lastname'];
    } else {
      $lC_MessageStack->add('account_edit', sprintf($lC_Language->get('field_customer_last_name_error'), ACCOUNT_LAST_NAME));
    }

    if (ACCOUNT_DATE_OF_BIRTH == '1') {
      $dateParts = explode("/", $_POST['dob']);
      if (isset($_POST['dob']) && checkdate($dateParts[0], $dateParts[1], $dateParts[2])) {
        $data['dob'] = @mktime(0, 0, 0, $dateParts[0], $dateParts[1], $dateParts[2]);
      } else {
        $lC_MessageStack->add('account_edit', $lC_Language->get('field_customer_date_of_birth_error'));
      }
    }

    if (isset($_POST['email_address']) && (strlen(trim($_POST['email_address'])) >= ACCOUNT_EMAIL_ADDRESS)) {
      if (lc_validate_email_address($_POST['email_address'])) {
        if (lC_Account::checkDuplicateEntry($_POST['email_address']) === false) {
          $data['email_address'] = $_POST['email_address'];
        } else {
          $lC_MessageStack->add('account_edit', $lC_Language->get('field_customer_email_address_exists_error'));
        }
      } else {
        $lC_MessageStack->add('account_edit', $lC_Language->get('field_customer_email_address_check_error'));
      }
    } else {
      $lC_MessageStack->add('account_edit', sprintf($lC_Language->get('field_customer_email_address_error'), ACCOUNT_EMAIL_ADDRESS));
    }

    if ($lC_MessageStack->size('account_edit') === 0) {
      if (lC_Account::saveEntry($data)) {
        // reset the session variables
        if (ACCOUNT_GENDER > -1) {
          $lC_Customer->setGender($data['gender']);
        }
        $lC_Customer->setFirstName(trim($data['firstname']));
        $lC_Customer->setLastName(trim($data['lastname']));
        $lC_Customer->setEmailAddress($data['email_address']);

        $lC_MessageStack->add('account', $lC_Language->get('success_account_updated'), 'success');
      }

      lc_redirect(lc_href_link(FILENAME_ACCOUNT, null, 'SSL'));
    }
  }
}
?>