<?php
/*
  $Id: orders.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
if ( !class_exists('lC_Summary') ) {
  include('includes/classes/summary.php');
}

class lC_Summary_orders extends lC_Summary {

  var $enabled = TRUE,
      $sort_order = 30;
  
  /* Class constructor */
  function __construct() {
    global $lC_Language;

    $lC_Language->loadIniFile('modules/summary/orders.php');
    $lC_Language->loadIniFile('orders.php');

    $this->_title = $lC_Language->get('summary_orders_title');
    $this->_title_link = lc_href_link_admin(FILENAME_DEFAULT, 'orders');

    if ( lC_Access::hasAccess('orders') ) {
      $this->_setData();
    }
  }

  /* Private methods */
  function _setData() {
    global $lC_Database, $lC_Language;
    
    if (!$this->enabled) {
      $this->_data = '';
    } else {      
      $this->_data = '<div class="four-columns six-columns-tablet twelve-columns-mobile">' .
                     '  <h2 class="relative thin">' . $this->_title . '</h2>' .
                     '  <ul class="list spaced">';      

      $Qorders = $lC_Database->query('select o.orders_id, o.customers_name, greatest(o.date_purchased, ifnull(o.last_modified, "1970-01-01")) as date_last_modified, s.orders_status_name, ot.text as order_total from :table_orders o, :table_orders_total ot, :table_orders_status s where o.orders_id = ot.orders_id and ot.class = "total" and o.orders_status = s.orders_status_id and s.language_id = :language_id order by date_last_modified desc limit 6');
      $Qorders->bindTable(':table_orders', TABLE_ORDERS);
      $Qorders->bindTable(':table_orders_total', TABLE_ORDERS_TOTAL);
      $Qorders->bindTable(':table_orders_status', TABLE_ORDERS_STATUS);
      $Qorders->bindInt(':language_id', $lC_Language->getID());
      $Qorders->execute();

      while ( $Qorders->next() ) {
        $this->_data .= '    <li>' .
                        '      <span class="list-link icon-price-tag icon-green" title="' . $lC_Language->get('orders') . '">' .  
                        '        <strong>' . strip_tags($Qorders->value('order_total'))  . '</strong> ' . $Qorders->value('customers_name') .
                        '      </span>' .
                        '      <div class="absolute-right compact show-on-parent-hover">' .
                        '        <a href="' . ((int)($_SESSION['admin']['access']['orders'] < 3) ? '#' : 'javascript://" onclick="editOrder(\'' . $Qorders->valueInt('orders_id') . '\')') . ';" class="button icon-pencil' . ((int)($_SESSION['admin']['access']['orders'] < 3) ? ' disabled' : NULL) . '">' .  $lC_Language->get('icon_view') . '</a>' . 
                        '        <!-- a href="' . ((int)($_SESSION['admin']['access']['orders'] < 4) ? '#' : 'javascript://" onclick="deleteOrder(\'' . $Qorders->valueInt('orders_id') . '\', \'' . urlencode($Qorders->value('customers_name')) . '\')') . ';" class="button icon-trash with-tooltip' . ((int)($_SESSION['admin']['access']['orders'] < 4) ? ' disabled' : NULL) . '" title="' . $lC_Language->get('icon_delete') . '"></a -->' . 
                        '      </div>' .
                        '    </li>';        
      }

      $this->_data .= '  </ul>' . 
                      '</div>';

      $Qorders->freeResult();
      
      $this->_data .= $this->loadModal();      
    }
  }
   
  function loadModal() {
    global $lC_Database, $lC_Language, $lC_Template;
    
    if ( is_dir('includes/applications/customers/modal') ) {
      if ( file_exists('includes/applications/orders/modal/edit.php') ) include_once('includes/applications/orders/modal/edit.php');
      //if ( file_exists('includes/applications/orders/modal/delete.php') ) include_once('includes/applications/orders/modal/delete.php');
    }
  }    
}
?>