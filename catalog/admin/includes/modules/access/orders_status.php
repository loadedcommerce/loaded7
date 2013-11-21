<?php
/**
  @package    admin::modules
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: orders_status.php v1.0 2013-08-08 datazen $
*/
class lC_Access_Orders_status extends lC_Access {
  var $_module = 'orders_status',
      $_group = 'orders',
      $_icon = 'orders.png',
      $_title,
      $_sort_order = 200;

  public function lC_Access_Orders_status() {
    global $lC_Language;

    $this->_title = $lC_Language->get('access_orders_status_title');
  }
}
?>