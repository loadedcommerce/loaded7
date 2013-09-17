<?php
/*
  $Id: save.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

  class lC_Application_Categories_Actions_save extends lC_Application_Categories {
    public function __construct() {
      global $lC_Language, $lC_MessageStack;
      
      parent::__construct();
      
      $this->_page_contents = 'edit.php'; 

      if ( isset($_POST['subaction']) && ($_POST['subaction'] == 'confirm') ) {
        //echo '<pre>';
        //print_r($_POST);
        //echo '<pre>';
        //die('before $data');
        switch ( $_GET['action'] ) {
          case 'save':  
            $data = array('image' => (isset($_POST['categories_image']) ? $_POST['categories_image'] : null),
                          'parent_id' => $_POST['parent_id'],
                          'sort_order' => $_POST['sort_order'],
                          'mode' => $_POST['categories_mode'],
                          'link_target' => ($_POST['categories_link_target'] == 'on') ? 1 : 0,
                          'custom_url' => $_POST['categories_custom_url'],
                          'status' => ($_POST['categories_status'] == 'on') ? 1 : 0,
                          'nav' => ($_POST['categories_visibility_nav'] == 'on') ? 1 : 0,
                          'box' => ($_POST['categories_visibility_box'] == 'on') ? 1 : 0,
                          'name' => $_POST['categories_name'],
                          'menu_name' =>  $_POST['categories_menu_name'],
                          'blurb' =>  $_POST['categories_blurb'],
                          'description' =>  $_POST['categories_description'],
                          'permalink' =>  $_POST['categories_permalink'],
                          'tags' =>  $_POST['categories_tags']);
           /*
            * Save the category information
            *
            * @param integer $_GET['cid'] The categories id used on update, null on insert
            * @param array $data The categories information
            * @access public
            * @return boolean
            */
            if ( lC_Categories_Admin::save((isset($_GET['categories']) && is_numeric($_GET['categories']) ? $_GET['categories'] : null), $data) ) {
              lc_redirect_admin(lc_href_link_admin(FILENAME_DEFAULT, $this->_module . '=' . $data['parent_id'] . '&cid=' . $_GET['cid']));
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