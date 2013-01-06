<?php
/*
  $Id: password_forgotten.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

  require('includes/classes/account.php');

  class lC_Account_Password_forgotten extends lC_Template {

    /* Private variables */
    var $_module = 'password_forgotten',
        $_group = 'account',
        $_page_title,
        $_page_contents = 'password_forgotten.php',
        $_page_image = 'table_background_password_forgotten.gif';

    /* Class constructor */
    function lC_Account_Password_forgotten() {
      global $lC_Language, $lC_Services, $lC_Breadcrumb;

      $this->_page_title = $lC_Language->get('password_forgotten_heading');

      $this->addJavascriptPhpFilename('includes/form_check.js.php');

      if ($lC_Services->isStarted('breadcrumb')) {
        $lC_Breadcrumb->add($lC_Language->get('breadcrumb_password_forgotten'), lc_href_link(FILENAME_ACCOUNT, $this->_module, 'SSL'));
      }

      if ($_GET[$this->_module] == 'process') {
        $this->_process();
      }
    }

    /* Private methods */
    function _process() {
      global $lC_MessageStack, $lC_Database, $lC_Language;

      $Qcheck = $lC_Database->query('select customers_id, customers_firstname, customers_lastname, customers_gender, customers_email_address, customers_password from :table_customers where customers_email_address = :customers_email_address limit 1');
      $Qcheck->bindTable(':table_customers', TABLE_CUSTOMERS);
      $Qcheck->bindValue(':customers_email_address', $_POST['email_address']);
      $Qcheck->execute();

      if ($Qcheck->numberOfRows() === 1) {
        $password = lc_create_random_string(ACCOUNT_PASSWORD);

        if (lC_Account::savePassword($password, $Qcheck->valueInt('customers_id'))) {
          if (ACCOUNT_GENDER > -1) {
             if ($data['gender'] == 'm') {
               $email_text = sprintf($lC_Language->get('email_addressing_gender_male'), $Qcheck->valueProtected('customers_lastname')) . "\n\n";
             } else {
               $email_text = sprintf($lC_Language->get('email_addressing_gender_female'), $Qcheck->valueProtected('customers_lastname')) . "\n\n";
             }
          } else {
            $email_text = sprintf($lC_Language->get('email_addressing_gender_unknown'), $Qcheck->valueProtected('customers_firstname') . ' ' . $Qcheck->valueProtected('customers_lastname')) . "\n\n";
          }

          $email_text .= sprintf($lC_Language->get('email_password_reminder_body'), getenv('REMOTE_ADDR'), STORE_NAME, $password, STORE_OWNER_EMAIL_ADDRESS);

          lc_email($Qcheck->valueProtected('customers_firstname') . ' ' . $Qcheck->valueProtected('customers_lastname'), $Qcheck->valueProtected('customers_email_address'), sprintf($lC_Language->get('email_password_reminder_subject'), STORE_NAME), $email_text, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);

          $lC_MessageStack->add('login', $lC_Language->get('success_password_forgotten_sent'), 'success');
        }

        lc_redirect(lc_href_link(FILENAME_ACCOUNT, 'login', 'SSL'));
      } else {
        $lC_MessageStack->add('password_forgotten', $lC_Language->get('error_password_forgotten_no_email_address_found'));
      }
    }
  }
?>