<?php
/*
  $Id: orders.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html

  @function The lC_Application_Orders class manages the orders GUI
*/
require_once('includes/applications/orders/classes/orders.php');
require_once('includes/classes/order.php');

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
  function __construct() {
    global $lC_Database, $lC_Language;

    $this->_page_title = $lC_Language->get('heading_title');

    if ( !isset($_GET['action']) ) {
      $_GET['action'] = '';
    }

    if (isset($_GET['cID']) && $_GET['cID'] != null) {
      $_SESSION['cIDFilter'] = $_GET['cID'];
    } else if (isset($_SESSION['cIDFilter'])) {
      unset($_SESSION['cIDFilter']);
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