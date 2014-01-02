<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: orders.php v1.0 2013-08-08 datazen $
*/
global $lC_Vqmod;

require_once($lC_Vqmod->modCheck('../includes/classes/currencies.php'));
require_once($lC_Vqmod->modCheck('includes/applications/orders/classes/orders.php'));
require_once($lC_Vqmod->modCheck('includes/applications/products/classes/products.php'));
require_once($lC_Vqmod->modCheck('includes/classes/order.php'));

class lC_Application_Orders extends lC_Template_Admin {
 /*
  * Protected variables
  */
  protected $_module = 'orders',
            $_page_title,
            $_page_contents = 'main.php';
 /*
  * Class constructor
  */
  public function __construct() {
    global $lC_Database, $lC_Language, $lC_Template, $lC_Currencies;
    
    $lC_Currencies = new lC_Currencies();
    
    $this->_page_title = $lC_Language->get('heading_title');

    if ( !isset($_GET['action']) ) {
      $_GET['action'] = '';
    }

    if (isset($_GET['cID']) && $_GET['cID'] != null) {
      $_SESSION['cIDFilter'] = $_GET['cID'];
    } else if (isset($_SESSION['cIDFilter'])) {
      unset($_SESSION['cIDFilter']);
    }
    
    if (isset($_GET['action']) && $_GET['action'] == "quick_add") {
      if($order_insert_id = lC_Orders_Admin::createOrder($_GET['cID'])) {
        lc_redirect_admin(lc_href_link_admin(FILENAME_DEFAULT, $this->_module . '=' . $order_insert_id . '&action=save&editProduct=1'));
      }
    } else if (isset($_GET['action']) && $_GET['action'] == "add_product") {
      lC_Orders_Admin::addOrderProductData();
      lc_redirect_admin(lc_href_link_admin(FILENAME_DEFAULT, $this->_module . '=' . $_GET['oID'] . '&action=save&editProduct=1'));
    }

    // for temporary use
    if (isset($_POST['action_order_total']) && $_POST['action_order_total'] == 'save_order_total') {
      lC_Orders_Admin::saveOrderTotal(); 
      lc_redirect_admin(lc_href_link_admin(FILENAME_DEFAULT, $this->_module . '=' . $_POST['oid'] . '&action=save&orderstotal=1'));
    }

    if ( !empty($_GET['action']) ) {
      switch ( $_GET['action'] ) {
        case 'invoice':
            $this->_page_contents = 'invoice.php';
            $this->_has_header = false;
            $this->_has_footer = false;
            $this->_has_wrapper = false;
          break;

        case 'packaging_slip':
            $this->_page_contents = 'packaging_slip.php';
            $this->_has_header = false;
            $this->_has_footer = false;
            $this->_has_wrapper = false;
          break;
      }
    }
  }
}
?>