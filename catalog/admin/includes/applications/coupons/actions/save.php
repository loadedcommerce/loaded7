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
      global $lC_Language, $lC_MessageStack, $lC_Currencies, $lC_DateTime;

      parent::__construct();
    
      $this->_page_contents = 'edit.php';

      if ( isset($_POST['subaction']) && ($_POST['subaction'] == 'confirm') ) {
        
        $error = false;

        $data = array('coupons_type' => $_POST['coupons_type'],
                      'coupons_mode' => $_POST['coupons_mode'],
                      'coupons_code' => $_POST['coupons_code'],
                      'coupons_reward' => str_replace("$", "", $_POST['coupons_reward']),
                      'coupons_purchase_over' => ($_POST['coupons_purchase_over'] != '') ? str_replace("$", "", $_POST['coupons_purchase_over']) : null,                      
                      'coupons_start_date' => ((strstr($_POST['coupons_start_date'], '/')) ? lC_DateTime::toDateTime($_POST['coupons_start_date']) : $_POST['coupons_start_date']),                      
                      'coupons_expires_date' => ((strstr($_POST['coupons_expires_date'], '/')) ? lC_DateTime::toDateTime($_POST['coupons_expires_date']) : $_POST['coupons_expires_date']),
                      'uses_per_coupon' => $_POST['uses_per_coupon'],
                      'uses_per_customer' => $_POST['uses_per_customer'],
                      'restrict_to_products' => $_POST['restrict_to_products'],
                      'restrict_to_categories' => $_POST['restrict_to_categories'],
                      'restrict_to_customers' => $_POST['restrict_to_customers'],
                      'coupons_status' => (isset($_POST['coupons_status']) && $_POST['coupons_status'] == 'on') ? 1 : 0,
                      'coupons_sale_exclude' => (isset($_POST['coupons_sale_exclude']) && $_POST['coupons_sale_exclude'] == 'on') ? 1 : 0,
                      'coupons_name' => $_POST['coupons_name'],
                      'coupons_description' => $_POST['coupons_description']);

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