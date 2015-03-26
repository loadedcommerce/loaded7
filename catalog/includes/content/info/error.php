<?php
/**
  @package    catalog::content
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: error.php v1.0 2013-08-08 datazen $
*/
class lC_Info_Error extends lC_Template {

  /* Private variables */
  var $_module = 'error',
      $_group = 'info',
      $_page_title,
      $_page_contents = 'error.php',
      $_page_image = 'none.gif';

  public function lC_Info_Error() {
    global $lC_Language, $lC_Services, $lC_Breadcrumb;

    $this->_page_title = $lC_Language->get('error_heading');

    if ($lC_Services->isStarted('breadcrumb')) {
      $lC_Breadcrumb->add($lC_Language->get('breadcrumb_error'), lc_href_link(FILENAME_INFO, $this->_module));
    }
  }
}
?>