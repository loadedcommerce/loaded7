<?php
/*
  $Id: products.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html

  @function The lC_Application_Products class manages the products GUI
*/
global $lC_Vqmod;

require_once($lC_Vqmod->modCheck('includes/applications/products/classes/products.php'));
require_once($lC_Vqmod->modCheck('includes/applications/product_attributes/classes/product_attributes.php'));
require_once($lC_Vqmod->modCheck('../includes/classes/variants.php'));

class lC_Application_Products extends lC_Template_Admin {
 /*
  * Protected variables
  */
  protected $_module = 'products',
            $_page_title,
            $_page_contents = 'main.php';
 /*
  * Class constructor
  */
  function __construct() {
    global $lC_Language, $lC_MessageStack, $lC_Currencies, $lC_Tax, $lC_CategoryTree, $lC_Image, $current_category_id, $lC_Vqmod;

    $this->_page_title = $lC_Language->get('heading_title');

    $current_category_id = 0;  

    if ( isset($_GET['cID']) && is_numeric($_GET['cID']) ) {
      $current_category_id = $_GET['cID'];
    } else {
      $_GET['cID'] = $current_category_id;
    }

    require($lC_Vqmod->modCheck('../includes/classes/currencies.php'));
    $lC_Currencies = new lC_Currencies();

    require($lC_Vqmod->modCheck('includes/classes/tax.php'));
    $lC_Tax = new lC_Tax_Admin();

    require($lC_Vqmod->modCheck('includes/classes/category_tree.php'));
    $lC_CategoryTree = new lC_CategoryTree_Admin();
    $lC_CategoryTree->setSpacerString('&nbsp;', 2);

    require($lC_Vqmod->modCheck('includes/classes/image.php'));
    $lC_Image = new lC_Image_Admin();    

    // check if the products image directory exists and is writeable
    if ( is_dir('../images/products') ) {
      if ( !is_writeable('../images/products') ) {
        $_SESSION['error'] = true;
        $_SESSION['errmsg'] = sprintf($lC_Language->get('ms_error_image_directory_not_writable'), realpath('../images/products'));
      }
    } else {
      $_SESSION['error'] = true;                                                                                            
      $_SESSION['errmsg'] = sprintf($lC_Language->get('ms_error_image_directory_non_existant'), realpath('../images/products'));
    }      
  }
}
?>