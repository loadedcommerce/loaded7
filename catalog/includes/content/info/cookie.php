<?php
/**
  @package    catalog::content
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: edit.php v1.0 2013-08-08 datazen $
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