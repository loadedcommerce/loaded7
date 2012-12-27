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

  if ( !class_exists('lC_Statistics') ) {
    include('includes/classes/statistics.php');
  }

  class lC_Statistics_Orders extends lC_Statistics {

    // Class constructor
    function lC_Statistics_Orders() {
      global $lC_Language, $lC_Currencies;

      $lC_Language->loadIniFile('modules/statistics/orders.php');

      if ( !isset($lC_Currencies) ) {
        if ( !class_exists('lC_Currencies') ) {
          include('../includes/classes/currencies.php');
        }

        $lC_Currencies = new lC_Currencies();
      }

      $this->_setIcon();
      $this->_setTitle();
    }

    // Private methods
    function _setIcon() {
      $this->_icon = lc_icon_admin('orders.png');
    }

    function _setTitle() {
      global $lC_Language;

      $this->_title = $lC_Language->get('statistics_orders_title');
    }

    function _setHeader() {
      global $lC_Language;

      $this->_header = array($lC_Language->get('statistics_orders_table_heading_customers'),
                             $lC_Language->get('statistics_orders_table_heading_total'));
    }

    function _setData() {
      global $lC_Database, $lC_Currencies;

      $this->_data = array();

      $this->_resultset = $lC_Database->query('select o.orders_id, o.customers_name, ot.value from :table_orders o, :table_orders_total ot where o.orders_id = ot.orders_id and ot.class = :class order by value desc');
      $this->_resultset->bindTable(':table_orders', TABLE_ORDERS);
      $this->_resultset->bindTable(':table_orders_total', TABLE_ORDERS_TOTAL);
      $this->_resultset->bindValue(':class', 'total');
      $this->_resultset->setBatchLimit($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS);
      $this->_resultset->execute();

      while ( $this->_resultset->next() ) {
        $this->_data[] = array(lc_link_object(lc_href_link_admin(FILENAME_DEFAULT, 'orders&oID=' . $this->_resultset->value('orders_id') . '&action=save'), $this->_icon . '&nbsp;' . $this->_resultset->value('customers_name')),
                               $lC_Currencies->format($this->_resultset->valueInt('value')));
      }
    }
  }
?>
