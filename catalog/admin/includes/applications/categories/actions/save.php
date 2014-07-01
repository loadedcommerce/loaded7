<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: save.php v1.0 2013-08-08 datazen $
*/
class lC_Application_Categories_Actions_save extends lC_Application_Categories {
  public function __construct() {
    global $lC_Language, $lC_MessageStack;
    
    parent::__construct();
    
    $this->_page_contents = 'edit.php'; 

    if ( isset($_POST['subaction']) && ($_POST['subaction'] == 'confirm') ) {
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
                        
          // access levels
          if (isset($_POST['access_levels'])) $data['access_levels'] = $_POST['access_levels'];                        
          if (isset($_POST['sync_all_products'])) $data['sync_all_products'] = $_POST['sync_all_products'];                        
          if (isset($_POST['sync_all_children'])) $data['sync_all_children'] = $_POST['sync_all_children'];                        
          
         /*
          * Save the category information
          *
          * @param integer $_GET['cid'] The categories id used on update, null on insert
          * @param array $data The categories information
          * @access public
          * @return boolean
          */         
          $id = lC_Categories_Admin::save((isset($_GET['categories']) && is_numeric($_GET['categories']) ? $_GET['categories'] : null), $data);

          if ( is_numeric($id) ) {
            if ( empty($_POST['save_close']) ) {
              lc_redirect_admin(lc_href_link_admin(FILENAME_DEFAULT, $this->_module . '=' . $id . '&cid=' . $_GET['cid'] . '&action=save'));
            } else {
              lc_redirect_admin(lc_href_link_admin(FILENAME_DEFAULT, $this->_module . '=' . $data['parent_id']));
            }
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