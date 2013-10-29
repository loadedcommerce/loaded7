<?php
  /*
  $Id: product_variants.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
 
  @function The lC_Application_Product_variants class manages the product variants GUI
*/
global $lC_Vqmod;

require_once($lC_Vqmod->modCheck('includes/applications/products_import_export/classes/products_import_export.php'));

class lC_Application_Products_import_export extends lC_Template_Admin {  
 /*
  * Protected variables
  */
  protected $_module = 'products_import_export',
            $_page_title,
            $_page_contents = 'main.php';
 /*
  * Class constructor
  */
  function __construct() {
    global $lC_Language, $breadcrumb_string;

    $this->_page_title = $lC_Language->get('heading_title');

    $breadcrumb_array = array(lc_link_object(lc_href_link_admin(FILENAME_DEFAULT, $this->_module), $lC_Language->get('heading_title'))); 
    $breadcrumb_string = '<ul>';
    foreach ($breadcrumb_array as $key => $value) {
      $breadcrumb_string .= '<li>' . $value . '</li>';
    }  
    $breadcrumb_string .= '</ul>';     
  }
}
?>