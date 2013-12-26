<?php
/**
  $Id: manufacturers.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
global $lC_Vqmod;

require($lC_Vqmod->modCheck('includes/applications/manufacturers/classes/manufacturers.php'));
require($lC_Vqmod->modCheck('includes/applications/products/classes/products.php'));
require($lC_Vqmod->modCheck('includes/classes/image.php'));

class lC_Application_Manufacturers extends lC_Template_Admin {

  /* Protected variables */
  protected $_module = 'manufacturers',
  $_page_title,
  $_page_contents = 'main.php';

  /* Class constructor */
  function __construct() {
    global $lC_Language, $lC_Image;

    $this->_page_title = $lC_Language->get('heading_title');

    if ( !isset($_GET['action']) ) {
      $_GET['action'] = '';
    }

    // check if the manufacturers image directory exists
    if ( is_dir('../images/manufacturers') ) {
      if ( !is_writeable('../images/manufacturers') ) {
        $_SESSION['error'] = true;
        $_SESSION['errmsg'] = sprintf($lC_Language->get('ms_error_image_directory_not_writable'), realpath('../images/manufacturers'));
      }
    } else {
      $_SESSION['error'] = true;                                                                                            
      $_SESSION['errmsg'] = sprintf($lC_Language->get('ms_error_image_directory_non_existant'), realpath('../images/manufacturers'));
    }

    $lC_Image = new lC_Image_Admin();

    if ( !empty($_GET['action']) ) {
      switch ( $_GET['action'] ) {
        case 'save':
          /*
          * Save the manufacturer information
          *
          * @param integer $_GET['mID'] The manufacturer id
          * @param array $data The manufacturer information
          * @access public
          * @return boolean
          */
          $data = array('name' => $_POST['manufacturers_name'],
            'image' => (isset($_FILES['image']) ? $_FILES['image'] : null),
            'url' => $_POST['manufacturers_url']);

          if ( lC_Manufacturers_Admin::save((isset($_GET['mID']) && is_numeric($_GET['mID']) ? $_GET['mID'] : null), $data) ) { 
            lc_redirect_admin(lc_href_link_admin(FILENAME_DEFAULT, $this->_module));
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