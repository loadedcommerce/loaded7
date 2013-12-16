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
class lC_Application_Featured_products_Actions_save extends lC_Application_Featured_products {
 /*
  * Class constructor
  */
  public function __construct() {
    global $lC_Language, $lC_MessageStack, $lC_Currencies, $lC_DateTime;

    parent::__construct();
  
    $this->_page_contents = 'edit.php';

    if ( isset($_POST['subaction']) && ($_POST['subaction'] == 'confirm') ) {
      
      // validate the input
      $status = (isset($_POST['status']) && $_POST['status'] == 'on') ? 1 : 0;
      $expires_date = (isset($_POST['expires_date']) && $_POST['expires_date'] != NULL) ? preg_replace('/[^0-9\s\/]/', '', $_POST['expires_date']) : NULL;
      
      $data = array('products_id' => $_POST['products_id'],
                    'status' => $status,
                    'expires_date' => $expires_date);

      if (lC_Featured_products_Admin::save((isset($_GET[$this->_module]) && is_numeric($_GET[$this->_module]) ? $_GET[$this->_module] : null), $data)) {
      } else {
        $lC_MessageStack->add($this->_module, $lC_Language->get('ms_error_action_not_performed'), 'error');
      }

      lc_redirect_admin(lc_href_link_admin(FILENAME_DEFAULT, $this->_module));
    }
  }
}
?>