<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: products.php v1.0 2013-08-08 datazen $
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
  public function __construct() {
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