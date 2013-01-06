<?php
/*
  $Id: lc_cfg_use_get_order_status_title.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

  function lc_cfg_use_get_order_status_title($id) {
    global $lC_Database, $lC_Language;

    if ($id < 1) {
      return $lC_Language->get('default_entry');
    }

    $Qstatus = $lC_Database->query('select orders_status_name from :table_orders_status where orders_status_id = :orders_status_id and language_id = :language_id');
    $Qstatus->bindTable(':table_orders_status', TABLE_ORDERS_STATUS);
    $Qstatus->bindInt(':orders_status_id', $id);
    $Qstatus->bindInt(':language_id', $lC_Language->getID());
    $Qstatus->execute();

    return $Qstatus->value('orders_status_name');
  }
?>