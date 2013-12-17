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
class lC_Application_Orders_Actions_save extends lC_Application_Orders {

  public function __construct() {
    global $lC_Language, $lC_MessageStack;

    parent::__construct();

    $this->_page_contents = 'edit.php'; 

    if(isset($_GET['neworder'])) {
      if($order_insert_id = lC_Orders_Admin::createOrder($_GET['cID'])) {
        lc_redirect_admin(lc_href_link_admin(FILENAME_DEFAULT, $this->_module . '=' . $order_insert_id . '&action=save'));
      }
    }

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