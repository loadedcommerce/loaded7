<?php
/*
  $Id: account.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

  require('includes/classes/order.php');

  class lC_Account_Account extends lC_Template {

    /* Private variables */
    var $_module = 'account',
        $_group = 'account',
        $_page_title,
        $_page_contents = 'account.php',
        $_page_image = 'table_background_account.gif';

    function lC_Account_Account() {
      global $lC_Language;

      $this->_page_title = $lC_Language->get('account_heading');
    }
  }
?>
