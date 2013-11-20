<?php
/**
  @package    catalog::content
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: specials.php v1.0 2013-08-08 datazen $
*/
class lC_Products_Specials extends lC_Template {

  /* Private variables */
  var $_module = 'specials',
      $_group = 'products',
      $_page_title,
      $_page_contents = 'specials.php',
      $_page_image = 'table_background_specials.gif';

  /* Class constructor */
  public function lC_Products_Specials() {
    global $lC_Services, $lC_Language, $lC_Breadcrumb, $lC_Specials;

    $this->_page_title = $lC_Language->get('specials_heading');

    if ($lC_Services->isStarted('breadcrumb')) {
      $lC_Breadcrumb->add($lC_Language->get('breadcrumb_specials'), lc_href_link(FILENAME_PRODUCTS, $this->_module));
    }
  }
}
?>