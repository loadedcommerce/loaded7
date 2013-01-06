<?php
/*
  $Id: help.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

  class lC_Search_Help extends lC_Template {

    /* Private variables */
    var $_module = 'help',
        $_group = 'search',
        $_page_title ,
        $_page_contents = 'help.php',
        $_has_header = false,
        $_has_footer = false,
        $_has_box_modules = false,
        $_has_content_modules = false,
        $_show_debug_messages = false;

    /* Class constructor */
    function lC_Search_Help() {
      global $lC_Language, $lC_NavigationHistory;

      $this->_page_title = $lC_Language->get('search_heading');

      $lC_NavigationHistory->removeCurrentPage();
    }
  }
?>