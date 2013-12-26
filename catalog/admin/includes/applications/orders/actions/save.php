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

  class lC_Application_Orders_Actions_save extends lC_Application_Orders {
    public function __construct() {
      global $lC_Language, $lC_MessageStack;
      
      parent::__construct();
      
      $this->_page_contents = 'edit.php'; 

      if ( isset($_POST['subaction']) && ($_POST['subaction'] == 'confirm') ) {
        switch ( $_GET['action'] ) {
          case 'save':  
           $data = array('oid' => $_POST['oid'],
                         'status' => $_POST['status'],
                         'comment' => $_POST['comment'],
                         'notify_customer' => (isset($_POST['notify_customer']) && $_POST['notify_customer'] == 1 ? true : false),
                         'append_comment' => (isset($_POST['append_comment']) && $_POST['append_comment'] == 1 ? true : false));
                          
           /*
            * Update the order status
            *
            * @param integer $data['oid'] The orders id used on status update
            * @param array $data The order status information
            * @access public
            * @return boolean
            */
            if ( lC_Orders_Admin::updateStatus($data['oid'], $data) ) {
              lc_redirect_admin(lc_href_link_admin(FILENAME_DEFAULT, $this->_module . '=' . $data['oid'] . '&action=save'));
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