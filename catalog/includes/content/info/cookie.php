<?php
/*
  $Id: cookie.php v1.0 2011-11-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

  class lC_Info_Cookie extends lC_Template {

    /* Private variables */
    var $_module = 'cookie',
        $_group = 'info',
        $_page_title,
        $_page_contents = 'cookie.php',
        $_page_image = 'table_background_specials.gif';

    /* Class constructor */
    function lC_Info_Cookie() {
      global $lC_Services, $lC_Language, $lC_Breadcrumb;

      $this->_page_title = $lC_Language->get('info_cookie_usage_heading');

      if ($lC_Services->isStarted('breadcrumb')) {
        $lC_Breadcrumb->add($lC_Language->get('breadcrumb_cookie_usage'), lc_href_link(FILENAME_INFO, $this->_module));
      }
    }
  }
?>
