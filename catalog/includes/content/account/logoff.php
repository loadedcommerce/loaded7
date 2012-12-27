<?php
/*
  $Id: logoff.php v1.0 2011-11-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

  class lC_Account_Logoff extends lC_Template {

    /* Private variables */
    var $_module = 'logoff',
        $_group = 'account',
        $_page_title,
        $_page_contents = 'logoff.php';

    /* Class constructor */
    function lC_Account_Logoff() {
      global $lC_Language, $lC_Services, $lC_Breadcrumb;

      $this->_page_title = $lC_Language->get('sign_out_heading');

      if ($lC_Services->isStarted('breadcrumb')) {
        $lC_Breadcrumb->add($lC_Language->get('breadcrumb_sign_out'));
      }

      $this->_process();
    }

    /* Private methods */
    function _process() {
      global $lC_ShoppingCart, $lC_Customer;

      $lC_ShoppingCart->reset();

      $lC_Customer->reset();
    }
  }
?>
