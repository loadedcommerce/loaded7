<?php
  /*
  $Id: products_import_export.php v1.0 2013-12-03 resultsonlyweb $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
 
  @function The lC_Application_Product_variants class manages the product variants GUI
*/
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
    global $lC_Language;

    $this->_page_title = $lC_Language->get('heading_title'); 
  }
}
?>