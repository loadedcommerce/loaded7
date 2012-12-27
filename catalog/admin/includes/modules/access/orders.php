<?php
/*
  $Id: orders.php v1.0 2011-11-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

  class lC_Access_Orders extends lC_Access {
    var $_module = 'orders',
        $_group = 'sales',
        $_icon = 'orders.png',
        $_title,
        $_sort_order = 100;

    function lC_Access_Orders() {
      global $lC_Language;

      $this->_title = $lC_Language->get('access_orders_title');
    }
  }
?>