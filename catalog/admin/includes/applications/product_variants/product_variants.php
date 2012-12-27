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
require_once('includes/applications/product_variants/classes/product_variants.php');

class lC_Application_Product_variants extends lC_Template_Admin {  
 /*
  * Protected variables
  */
  protected $_module = 'product_variants',
            $_page_title,
            $_page_contents = 'main.php';
 /*
  * Class constructor
  */
  function __construct() {
    global $lC_Language, $breadcrumb_string;

    $this->_page_title = $lC_Language->get('heading_title');

    $breadcrumb_array = array(lc_link_object(lc_href_link_admin(FILENAME_DEFAULT, $this->_module), $lC_Language->get('heading_title'))); 
    if (!empty($_GET[$this->_module]) && is_numeric($_GET[$this->_module])) {
      $this->_page_contents = 'entries.php';
      $this->_page_title = lC_Product_variants_Admin::getData($_GET[$this->_module], null, 'title');
      $breadcrumb_array[] = lc_link_object(lc_href_link_admin(FILENAME_DEFAULT, $this->_module . '=' . $this->_page_contents ), $this->_page_title);
    }
    $breadcrumb_string = '<ul>';
    foreach ($breadcrumb_array as $key => $value) {
      $breadcrumb_string .= '<li>' . $value . '</li>';
    }  
    $breadcrumb_string .= '</ul>';     
  }
}
?>