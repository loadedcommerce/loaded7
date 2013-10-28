<?php
/**
  $Id: new.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
class lC_Products_New extends lC_Template {

  /* Private variables */
  var $_module = 'new',
      $_group = 'products',
      $_page_title,
      $_page_contents = 'new.php',
      $_page_image = 'table_background_products_new.gif';

  /* Class constructor */
  function lC_Products_New() {
    global $lC_Services, $lC_Language, $lC_Breadcrumb, $lC_Vqmod;

    require($lC_Vqmod->modCheck('includes/classes/products.php'));

    $this->_page_title = $lC_Language->get('new_products_heading');
    
    if ($lC_Services->isStarted('breadcrumb')) {
      $lC_Breadcrumb->add($lC_Language->get('breadcrumb_new_products'), lc_href_link(FILENAME_PRODUCTS, $this->_module));
    }
  }
}
?>