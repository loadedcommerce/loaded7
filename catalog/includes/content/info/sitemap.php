<?php
/*
  $Id: sitemap.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

  class lC_Info_Sitemap extends lC_Template {

    /* Private variables */
    var $_module = 'sitemap',
        $_group = 'info',
        $_page_title,
        $_page_contents = 'info_sitemap.php',
        $_page_image = 'table_background_specials.gif';

    /* Class constructor */
    function lC_Info_Sitemap() {
      global $lC_Services, $lC_Language, $lC_Breadcrumb;

      $this->_page_title = $lC_Language->get('info_sitemap_heading');

      if ($lC_Services->isStarted('breadcrumb')) {
        $lC_Breadcrumb->add($lC_Language->get('breadcrumb_sitemap'), lc_href_link(FILENAME_INFO, $this->_module));
      }
    }
  }
?>