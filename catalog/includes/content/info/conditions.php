<?php
/*
  $Id: conditions.php v1.0 2011-11-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

  class lC_Info_Conditions extends lC_Template {

    /* Private variables */
    var $_module = 'conditions',
        $_group = 'info',
        $_page_title,
        $_page_contents = 'info_conditions.php',
        $_page_image = 'table_background_specials.gif';

    /* Class constructor */
    function lC_Info_Conditions() {
      global $lC_Services, $lC_Language, $lC_Breadcrumb;

      $this->_page_title = $lC_Language->get('info_conditions_heading');

      if ($lC_Services->isStarted('breadcrumb')) {
        $lC_Breadcrumb->add($lC_Language->get('breadcrumb_conditions'), lc_href_link(FILENAME_INFO, $this->_module));
      }
    }
  }
?>