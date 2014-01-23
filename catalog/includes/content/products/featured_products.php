<?php
/**
  @package    catalog::content
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: featured_products.php v1.0 2013-08-08 datazen $
*/
class lC_Products_Featured_products extends lC_Template {

  /* Private variables */
  var $_module = 'featured_products',
      $_group = 'products',
      $_page_title,
      $_page_contents = 'featured_products.php',
      $_page_image = 'table_background_products_featured.gif';

  /* Class constructor */
  public function lC_Products_Featured_products() {
    global $lC_Services, $lC_Language, $lC_Breadcrumb, $lC_Vqmod, $lC_Featured_products;

    require($lC_Vqmod->modCheck('includes/classes/featured_products.php'));
    
    $lC_Featured_products = new lC_Featured_products();
    //$lC_Featured_products->expireAll();
    
    $this->_page_title = $lC_Language->get('featured_products_heading');
    
    if ($lC_Services->isStarted('breadcrumb')) {
      $lC_Breadcrumb->add($lC_Language->get('breadcrumb_featured_products'), lc_href_link(FILENAME_PRODUCTS, $this->_module));
    }
  }
}
?>