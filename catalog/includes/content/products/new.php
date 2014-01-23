<?php
/**
  @package    catalog::content
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: new.php v1.0 2013-08-08 datazen $
*/
class lC_Products_New extends lC_Template {

  /* Private variables */
  var $_module = 'new',
      $_group = 'products',
      $_page_title,
      $_page_contents = 'new.php',
      $_page_image = 'table_background_products_new.gif';

  /* Class constructor */
  public function lC_Products_New() {
    global $lC_Services, $lC_Language, $lC_Breadcrumb, $lC_Vqmod;

    require($lC_Vqmod->modCheck('includes/classes/products.php'));

    $this->_page_title = $lC_Language->get('new_products_heading');
    
    if ($lC_Services->isStarted('breadcrumb')) {
      $lC_Breadcrumb->add($lC_Language->get('breadcrumb_new_products'), lc_href_link(FILENAME_PRODUCTS, $this->_module));
    }
  }
}
?>