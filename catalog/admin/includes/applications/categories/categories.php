<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: categories.php v1.0 2013-08-08 datazen $
*/
global $lC_Vqmod;

require_once($lC_Vqmod->modCheck('includes/applications/categories/classes/categories.php'));
require_once($lC_Vqmod->modCheck('includes/applications/products/classes/products.php'));
require_once($lC_Vqmod->modCheck('includes/classes/category_tree.php'));

class lC_Application_Categories extends lC_Template_Admin {
 /*
  * Protected variables
  */
  protected $_module = 'categories',
            $_page_title,
            $_page_contents = 'main.php';
 /*
  * Class constructor
  */
  function __construct() {
    global $lC_Language, $breadcrumb_string;

    $this->_page_title = $lC_Language->get('heading_title');

    $current_category_id = 0;

    if ( is_numeric($_GET[$this->_module]) ) {
      $current_category_id = $_GET[$this->_module];
    }

    $lC_CategoryTree = new lC_CategoryTree_Admin();

    if ( !isset($_GET['action']) ) {
      $_GET['action'] = '';
    }

    // check if the categories image directory exists
    if ( is_dir('../images/categories') ) {
      if ( !is_writeable('../images/categories') ) {
        $_SESSION['error'] = true;
        $_SESSION['errmsg'] = sprintf($lC_Language->get('ms_error_image_directory_not_writable'), realpath('../images/categories'));
      }
    } else {
      $_SESSION['error'] = true;                                                                                            
      $_SESSION['errmsg'] = sprintf($lC_Language->get('ms_error_image_directory_non_existant'), realpath('../images/categories'));
    }
    
    // setup the breadcrumb
    $breadcrumb_array = array(lc_link_object(lc_href_link_admin(FILENAME_DEFAULT, $this->_module), $lC_Language->get('text_top')));
    foreach ( $lC_CategoryTree->getPathArray($current_category_id) as $category ) {
      $breadcrumb_array[] = lc_link_object(lc_href_link_admin(FILENAME_DEFAULT, $this->_module . '=' . $category['id']), $category['name']);
    } 
    $breadcrumb_string = '<ul>';
    foreach ($breadcrumb_array as $key => $value) {
      $breadcrumb_string .= '<li>' . $value . '</li>';
    }  
    $breadcrumb_string .= '</ul>';
  }
}
?>
