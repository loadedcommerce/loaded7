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

  class lC_Application_Coupons_Actions_save extends lC_Application_Coupons {
    public function __construct() {
      global $lC_Language, $lC_MessageStack;

      parent::__construct();
    
      $this->_page_contents = 'edit.php';

      if ( isset($_POST['subaction']) && ($_POST['subaction'] == 'confirm') ) {
        
        echo "<pre>";
        print_r($_POST);
        echo "</pre>";
        die('end run');
          
        $error = false;

        $data = array('_name' => $_POST['products_name'],
                      '_description' => $_POST['products_description']);

        if ( $error === false ) {
          if ( lC_Coupons_Admin::save((isset($_GET[$this->_module]) && is_numeric($_GET[$this->_module]) ? $_GET[$this->_module] : null), $data) ) {
          } else {
            $_SESSION['error'] = true;                                                                                            
            $_SESSION['errmsg'] = $lC_Language->get('ms_error_action_not_performed');
          }

          lc_redirect_admin(lc_href_link_admin(FILENAME_DEFAULT, $this->_module));
        }
      }
    }
  }
?>