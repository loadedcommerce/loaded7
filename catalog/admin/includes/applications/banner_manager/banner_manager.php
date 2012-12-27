<?php
/*
  $Id: banner_manager.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html

  @function The lC_Application_Banner_manager class manages the banners GUI
*/
require_once('includes/applications/banner_manager/classes/banner_manager.php');

class lC_Application_Banner_manager extends lC_Template_Admin {
 /*
  * Public variables
  */
  var $image_extension;
 /*
  * Protected variables
  */
  protected $_module = 'banner_manager',
            $_page_title,
            $_page_contents = 'main.php';
 /*
  * Class constructor
  */
  function __construct() {
    global $lC_Language, $lC_MessageStack;

    $this->_page_title = $lC_Language->get('heading_title');

    if ( !isset($_GET['action']) ) {
      $_GET['action'] = '';
    }

    $this->image_extension = lc_dynamic_image_extension();

    // check if the graphs directory exists
    $_SESSION['errArr'] = array();
    if ( !empty($this->image_extension) ) {
      if ( is_dir('images/graphs') ) {
        if ( !is_writeable('images/graphs') ) {
          $_SESSION['errArr']['error'] = true;
          $_SESSION['errArr']['errmsg'] = sprintf($lC_Language->get('ms_error_graphs_directory_not_writable'), realpath('images'));
        }
      } else {
        $_SESSION['errArr']['error'] = true;                                                                                            
        $_SESSION['errArr']['errmsg'] = sprintf($lC_Language->get('ms_error_graphs_directory_non_existant'), realpath('images'));
      }
    }

    if ( !empty($_GET['action']) && !($_SESSION['error']) ) {
      switch ( $_GET['action'] ) {
         case 'save':  
           $data = array('title' => $_POST['title'],
                         'url' => $_POST['url'],
                         'group' => (isset($_POST['group']) ? $_POST['group'] : null),
                         'group_new' => $_POST['group_new'],
                         'image' => (isset($_FILES['image']) ? $_FILES['image'] : null),
                         'image_local' => $_POST['image_local'],
                         'image_target' => $_POST['image_target'],
                         'html_text' => $_POST['html_text'],
                         'date_scheduled' => $_POST['date_scheduled'],
                         'date_expires' => $_POST['date_expires'],
                         'expires_impressions' => $_POST['expires_impressions'],
                         'status' => (isset($_POST['status']) && ($_POST['status'] == 'on') ? true : false));
         /*
          * Save the banner information
          *
          * @param integer $_GET['bid'] The banner id
          * @param array $data The banner information
          * @access public
          * @return boolean
          */           
          if ( lC_Banner_manager_Admin::save((isset($_GET['bid']) && is_numeric($_GET['bid']) ? $_GET['bid'] : null), $data) ) {
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