<?php
/*
  $Id: receipt.php v1.0 2011-11-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

  class lC_Account_Receipt extends lC_Template {

    /* Private variables */
    var $_module = 'receipt',
        $_group = 'account',
        $_page_title ,
        $_page_contents = 'receipt.php',
        $_has_header = false,
        $_has_footer = false,
        $_has_box_modules = false,
        $_has_content_modules = false,
        $_show_debug_messages = false;

    /* Class constructor */
    function lC_Account_Receipt() {
      global $lC_Language, $lC_NavigationHistory;

      $this->_page_title = $lC_Language->get('receipt_heading');

      $lC_NavigationHistory->removeCurrentPage();
    }
  }
?>