<?php
/*
  $Id: categories.php v1.0 2011-11-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html

  @function The lC_Application_Categories class manages the categories GUI
*/
require_once('includes/applications/categories/classes/categories.php');
require_once('includes/applications/products/classes/products.php');
require_once('includes/classes/category_tree.php');

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
    
    if ( !empty($_GET['action']) ) {
      switch ( $_GET['action'] ) {
        case 'save':  
          $data = array('name' => $_POST['categories_name'],
                        'parent_id' => $_POST['parent_id'],
                        'image' => (isset($_FILES['categories_image']) ? $_FILES['categories_image'] : null),
                        'sort_order' => $_POST['sort_order']);
         /*
          * Save the category information
          *
          * @param integer $_GET['cid'] The categories id used on update, null on insert
          * @param array $data The categories information
          * @access public
          * @return boolean
          */
          if ( lC_Categories_Admin::save((isset($_GET['cid']) && is_numeric($_GET['cid']) ? $_GET['cid'] : null), $data) ) {
            lc_redirect_admin(lc_href_link_admin(FILENAME_DEFAULT, $this->_module . '=' . $_GET[$this->_module]));
          } else {
            $_SESSION['error'] = true;
            $_SESSION['errmsg'] = $lC_Language->get('ms_error_action_not_performed');
          }
          break;
      }
    }
  }
}
?>
