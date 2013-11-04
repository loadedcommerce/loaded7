<?php
/**
  $Id: password.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
class lC_Account_Password extends lC_Template {

  /* Private variables */
  var $_module = 'password',
      $_group = 'account',
      $_page_title,
      $_page_contents = 'account_password.php',
      $_page_image = 'table_background_account.gif';

  /* Class constructor */
  function lC_Account_Password() {
    global $lC_Language, $lC_Services, $lC_Breadcrumb, $lC_Vqmod;
                            
    require_once($lC_Vqmod->modCheck('includes/classes/account.php'));

    $this->_page_title = $lC_Language->get('account_password_heading');
    
    $this->addJavascriptPhpFilename('templates/' . $this->getCode() . '/javascript/form_check.js.php');
    $this->addJavascriptPhpFilename('templates/' . $this->getCode() . '/javascript/account/account_password.js.php');

    if ($lC_Services->isStarted('breadcrumb')) {
      $lC_Breadcrumb->add($lC_Language->get('breadcrumb_edit_password'), lc_href_link(FILENAME_ACCOUNT, $this->_module, 'SSL'));
    }

    if ($_GET[$this->_module] == 'save') {
      $this->_process();
    }
  }

  /* Private methods */
  function _process() {
    global $lC_MessageStack, $lC_Database, $lC_Language, $lC_Vqmod;   

    require_once($lC_Vqmod->modCheck('includes/classes/account.php'));
    
    if (!isset($_POST['password_current']) || (strlen(trim($_POST['password_current'])) < ACCOUNT_PASSWORD)) {
      $lC_MessageStack->add('account_password', sprintf($lC_Language->get('field_customer_password_current_error'), ACCOUNT_PASSWORD));
    } elseif (!isset($_POST['password_new']) || (strlen(trim($_POST['password_new'])) < ACCOUNT_PASSWORD)) {
      $lC_MessageStack->add('account_password', sprintf($lC_Language->get('field_customer_password_new_error'), ACCOUNT_PASSWORD));
    } elseif (!isset($_POST['password_confirmation']) || (trim($_POST['password_new']) != trim($_POST['password_confirmation']))) {
      $lC_MessageStack->add('account_password', $lC_Language->get('field_customer_password_new_mismatch_with_confirmation_error'));
    }

    if ($lC_MessageStack->size('account_password') === 0) {
      if (lC_Account::checkPassword(trim($_POST['password_current']))) {
        if (lC_Account::savePassword(trim($_POST['password_new']))) {
          lc_redirect(lc_href_link(FILENAME_ACCOUNT, 'success=' . urlencode($lC_Language->get('success_password_updated')), 'SSL'));
        } else {
          $lC_MessageStack->add('account_password', sprintf($lC_Language->get('field_customer_password_new_error'), ACCOUNT_PASSWORD));
        }
      } else {
        $lC_MessageStack->add('account_password', $lC_Language->get('error_current_password_not_matching'));
      }
    }
  }
}
?>