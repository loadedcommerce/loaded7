<?php
/**
  @package    catalog::content
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: login.php v1.0 2013-08-08 datazen $
*/
class lC_Account_Login extends lC_Template {

  /* Private variables */
  var $_module = 'login',
      $_group = 'account',
      $_page_title,
      $_page_contents = 'login.php',
      $_page_image = 'table_background_login.gif';

  /* Class constructor */
  public function lC_Account_Login() {
    global $lC_Language, $lC_Services, $lC_Breadcrumb;

    // redirect the customer to a friendly cookie-must-be-enabled page if cookies are disabled (or the session has not started)
    if (lc_empty(session_id())) {
      lc_redirect(lc_href_link(FILENAME_INFO, 'cookie', 'AUTO'));
    }

    $this->_page_title = $lC_Language->get('sign_in_heading');

    if ($lC_Services->isStarted('breadcrumb')) {
      $lC_Breadcrumb->add($lC_Language->get('breadcrumb_sign_in'), lc_href_link(FILENAME_ACCOUNT, $this->_module, 'SSL'));
    }

    if ($_GET[$this->_module] == 'process') {
      $this->_process();
    }
  }

  /* Private methods */
  protected function _process() {
    global $lC_Database, $lC_Session, $lC_Language, $lC_ShoppingCart, $lC_MessageStack, $lC_Customer, $lC_NavigationHistory, $lC_Vqmod;

    require($lC_Vqmod->modCheck('includes/classes/account.php'));
    
    if (lC_Account::checkEntry($_POST['email_address'])) {
      if (lC_Account::checkPassword($_POST['password'], $_POST['email_address'])) {
        if (SERVICE_SESSION_REGENERATE_ID == '1') {
          $lC_Session->recreate();
        }

        $lC_Customer->setCustomerData(lC_Account::getID($_POST['email_address']));

        $Qupdate = $lC_Database->query('update :table_customers set date_last_logon = :date_last_logon, number_of_logons = number_of_logons+1 where customers_id = :customers_id');
        $Qupdate->bindTable(':table_customers', TABLE_CUSTOMERS);
        $Qupdate->bindRaw(':date_last_logon', 'now()');
        $Qupdate->bindInt(':customers_id', $lC_Customer->getID());
        $Qupdate->execute();
        
        if ($lC_ShoppingCart->hasContents() === true) {
          $lC_ShoppingCart->synchronizeWithDatabase();
        }

        $lC_NavigationHistory->removeCurrentPage();

        lC_Cache::clearAll();
        
        if ($lC_NavigationHistory->hasSnapshot()) {
          $lC_NavigationHistory->redirectToSnapshot();
        } else {
          lc_redirect(lc_href_link(FILENAME_DEFAULT, null, 'AUTO'));
        }
      } else {
        $lC_MessageStack->add('login', $lC_Language->get('error_login_no_match'));
      }
    } else {
      $lC_MessageStack->add('login', $lC_Language->get('error_login_no_match'));
    }
  }
}
?>