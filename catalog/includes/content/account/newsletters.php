<?php
/*
  $Id: newsletters.php v1.0 2011-11-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

  class lC_Account_Newsletters extends lC_Template {

    /* Private variables */
    var $_module = 'newsletters',
        $_group = 'account',
        $_page_title ,
        $_page_contents = 'account_newsletters.php',
        $_page_image = 'table_background_account.gif';

    /* Class constructor */
    function lC_Account_Newsletters() {
      global $lC_Language, $lC_Services, $lC_Breadcrumb, $lC_Database, $lC_Customer, $Qnewsletter;

      $this->_page_title = $lC_Language->get('newsletters_heading');

      if ($lC_Services->isStarted('breadcrumb')) {
        $lC_Breadcrumb->add($lC_Language->get('breadcrumb_newsletters'), lc_href_link(FILENAME_ACCOUNT, $this->_module, 'SSL'));
      }

      //#### Should be moved to the customers class!
      $Qnewsletter = $lC_Database->query('select customers_newsletter from :table_customers where customers_id = :customers_id');
      $Qnewsletter->bindTable(':table_customers', TABLE_CUSTOMERS);
      $Qnewsletter->bindInt(':customers_id', $lC_Customer->getID());
      $Qnewsletter->execute();

      if ($_GET[$this->_module] == 'save') {
        $this->_process();
      }
    }

    /* Private methods */
    function _process() {
      global $lC_MessageStack, $lC_Database, $lC_Language, $lC_Customer, $Qnewsletter;

      if (isset($_POST['newsletter_general']) && is_numeric($_POST['newsletter_general'])) {
        $newsletter_general = $_POST['newsletter_general'];
      } else {
        $newsletter_general = '0';
      }

      if ($newsletter_general != $Qnewsletter->valueInt('customers_newsletter')) {
        $newsletter_general = (($Qnewsletter->value('customers_newsletter') == '1') ? '0' : '1');

        $Qupdate = $lC_Database->query('update :table_customers set customers_newsletter = :customers_newsletter where customers_id = :customers_id');
        $Qupdate->bindTable(':table_customers', TABLE_CUSTOMERS);
        $Qupdate->bindInt(':customers_newsletter', $newsletter_general);
        $Qupdate->bindInt(':customers_id', $lC_Customer->getID());
        $Qupdate->execute();

        if ($Qupdate->affectedRows() === 1) {
          $lC_MessageStack->add('account', $lC_Language->get('success_newsletter_updated'), 'success');
        }
      }

      lc_redirect(lc_href_link(FILENAME_ACCOUNT, null, 'SSL'));
    }
  }
?>