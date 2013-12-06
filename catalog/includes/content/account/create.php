<?php
/**
  @package    catalog::content
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: create.php v1.0 2013-08-08 datazen $
*/
class lC_Account_Create extends lC_Template {

  /* Private variables */
  var $_module = 'create',
      $_group = 'account',
      $_page_title,
      $_page_contents = 'create.php',
      $_page_image = 'table_background_account.gif';

  /* Class constructor */
  public function lC_Account_Create() {
    global $lC_Language, $lC_Services, $lC_Breadcrumb;

    $this->_page_title = $lC_Language->get('create_account_heading');

    if ($_GET[$this->_module] == 'success') {
      if ($lC_Services->isStarted('breadcrumb')) {
        $lC_Breadcrumb->add($lC_Language->get('breadcrumb_create_account'));
      }

      $this->_page_title = $lC_Language->get('create_account_success_heading');
      $this->_page_contents = 'create_success.php';
    } else {
      if ($lC_Services->isStarted('breadcrumb')) {
        $lC_Breadcrumb->add($lC_Language->get('breadcrumb_create_account'), lc_href_link(FILENAME_ACCOUNT, $this->_module, 'SSL'));
      }
      
      $this->addJavascriptPhpFilename('templates/' . $this->getCode() . '/javascript/form_check.js.php');
    }

    if ($_GET[$this->_module] == 'save') {
      $this->_process();
    }
  }

  /* Private methods */
  protected function _process() {
    global $lC_MessageStack, $lC_Database, $lC_Language, $lC_Customer, $lC_Vqmod;

    require($lC_Vqmod->modCheck('includes/classes/account.php'));
    
    $data = array();
    
    if (DISPLAY_PRIVACY_CONDITIONS == '1') {
      if (isset($_POST['privacy_conditions']) && ($_POST['privacy_conditions'] == '1' || $_POST['privacy_conditions'] == 'on'))  {
      } else {
        $lC_MessageStack->add($this->_module, $lC_Language->get('error_privacy_statement_not_accepted'));
      }
    }

    if (ACCOUNT_GENDER >= 0) {
      if (isset($_POST['gender']) && (($_POST['gender'] == 'm') || ($_POST['gender'] == 'f'))) {
        $data['gender'] = $_POST['gender'];
      } else {
        $lC_MessageStack->add($this->_module, $lC_Language->get('field_customer_gender_error'));
      }
    }

    if (isset($_POST['firstname']) && (strlen(trim($_POST['firstname'])) >= ACCOUNT_FIRST_NAME)) {
      $data['firstname'] = $_POST['firstname'];
    } else {
      $lC_MessageStack->add($this->_module, sprintf($lC_Language->get('field_customer_first_name_error'), ACCOUNT_FIRST_NAME));
    }

    if (isset($_POST['lastname']) && (strlen(trim($_POST['lastname'])) >= ACCOUNT_LAST_NAME)) {
      $data['lastname'] = $_POST['lastname'];
    } else {
      $lC_MessageStack->add($this->_module, sprintf($lC_Language->get('field_customer_last_name_error'), ACCOUNT_LAST_NAME));
    }

    if (isset($_POST['newsletter']) && $_POST['newsletter'] == '1'){
      $data['newsletter'] = 1;
    }else{
      $data['newsletter'] = '';
    }

    if (ACCOUNT_DATE_OF_BIRTH == '1') {     
      if (isset($_POST['dob']) && $_POST['dob'] != NULL) {
        $dateParts = explode("/", $_POST['dob']);
      } else {
        $dateParts = array($_POST['dob_days'], $_POST['dob_months'], $_POST['dob_years']);
      }
      if (isset($dateParts[1]) && isset($dateParts[0]) && isset($dateParts[2]) && checkdate($dateParts[0], $dateParts[1], $dateParts[2])) {
        $data['dob'] = @mktime(0, 0, 0, $dateParts[0], $dateParts[1], $dateParts[2]);
      } else {
        $lC_MessageStack->add($this->_module, $lC_Language->get('field_customer_date_of_birth_error'));
      }
    } 

    if (isset($_POST['email_address']) && (strlen(trim($_POST['email_address'])) >= ACCOUNT_EMAIL_ADDRESS)) {
      if (lc_validate_email_address($_POST['email_address'])) {
        if (lC_Account::checkDuplicateEntry($_POST['email_address']) === false) {
          $data['email_address'] = $_POST['email_address'];
        } else {
          $lC_MessageStack->add($this->_module, $lC_Language->get('field_customer_email_address_exists_error'));
        }
      } else {
        $lC_MessageStack->add($this->_module, $lC_Language->get('field_customer_email_address_check_error'));
      }
    } else {
      $lC_MessageStack->add($this->_module, sprintf($lC_Language->get('field_customer_email_address_error'), ACCOUNT_EMAIL_ADDRESS));
    }

    if ( (isset($_POST['password']) === false) || (isset($_POST['password']) && (strlen(trim($_POST['password'])) < ACCOUNT_PASSWORD)) ) {
      $lC_MessageStack->add($this->_module, sprintf($lC_Language->get('field_customer_password_error'), ACCOUNT_PASSWORD));
    } elseif ( (isset($_POST['confirmation']) === false) || (isset($_POST['confirmation']) && (trim($_POST['password']) != trim($_POST['confirmation']))) ) {
      $lC_MessageStack->add($this->_module, $lC_Language->get('field_customer_password_mismatch_with_confirmation'));
    } else {
      $data['password'] = $_POST['password'];
    }

    if ($lC_MessageStack->size($this->_module) === 0) {
      if (lC_Account::createEntry($data)) {
        $lC_MessageStack->add('create', $lC_Language->get('success_account_updated'), 'success');
      }
       
      // added to redirect to keep customer in checkout stream
      foreach ($_SESSION['lC_NavigationHistory_data'] as $data) {
        foreach ($data as $page) {
          foreach ($page as $key => $value) {
            if ($key == 'shipping') {
              $checkout = true;
            }
          }
        }                        
      }
      
      if ($checkout == true) {
        lc_redirect(lc_href_link(FILENAME_CHECKOUT, 'shipping&account_created=true', 'SSL'));
      } else {
        lc_redirect(lc_href_link(FILENAME_ACCOUNT, 'create=success', 'SSL'));
      }
    }
  }
}
?>