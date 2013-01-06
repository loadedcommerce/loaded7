<?php
/*
  $Id: info.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

  class lC_Info_Info extends lC_Template {

    /* Private variables */
    var $_module = 'info',
        $_group = 'info',
        $_page_title,
        $_page_contents = 'info.php',
        $_page_image = 'table_background_account.gif';

    function lC_Info_Info() {
      global $lC_Language;

      $this->_page_title = $lC_Language->get('info_heading');
    }
  }
?>