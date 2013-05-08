<?php
/*
  $Id: lc_cfg_set_order_statuses_pull_down_menu.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

  function lc_cfg_set_order_statuses_pull_down_menu($default, $key = null) {
    global $lC_Database, $lC_Language;
    
    $css_class = 'class="input with-small-padding"';
    $args = func_get_args();
    if(count($args) > 2 &&  strpos($args[0], 'class') !== false ) {
      $css_class = $args[0];
      $default = $args[1];
      $key  = $args[2];
    }

    if (isset($_GET['plugins'])) {
      $name = (empty($key)) ? 'plugins_value' : 'plugins[' . $key . ']';
    } else {
      $name = (empty($key)) ? 'configuration_value' : 'configuration[' . $key . ']';
    }

    $statuses_array = array(array('id' => '0',
                                  'text' => $lC_Language->get('default_entry')));

    $Qstatuses = $lC_Database->query('select orders_status_id, orders_status_name from :table_orders_status where language_id = :language_id order by orders_status_name');
    $Qstatuses->bindTable(':table_orders_status', TABLE_ORDERS_STATUS);
    $Qstatuses->bindInt(':language_id', $lC_Language->getID());
    $Qstatuses->execute();

    while ($Qstatuses->next()) {
      $statuses_array[] = array('id' => $Qstatuses->valueInt('orders_status_id'),
                                'text' => $Qstatuses->value('orders_status_name'));
    }

    return lc_draw_pull_down_menu($name, $statuses_array, $default, $css_class);
  }
?>