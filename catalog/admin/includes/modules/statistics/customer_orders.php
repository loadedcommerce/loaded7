<?php
/**
  @package    admin::modules
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    
  https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: customer_orders.php v1.0 2013-08-08 datazen $
*/
global $lC_Vqmod;

if ( !class_exists('lC_Statistics') ) {
  include($lC_Vqmod->modCheck('includes/classes/statistics.php'));
}

class lC_Statistics_Customer_Orders extends lC_Statistics {

  // Class constructor
  public function lC_Statistics_Customer_Orders() {
    global $lC_Language, $lC_Currencies, $lC_Vqmod;

    $lC_Language->loadIniFile('modules/statistics/customer_orders.php');

    if ( !isset($lC_Currencies) ) {
      if ( !class_exists('lC_Currencies') ) {
        include($lC_Vqmod->modCheck('../includes/classes/currencies.php'));
      }

      $lC_Currencies = new lC_Currencies();
    }

    $this->_setIcon();
    $this->_setTitle();
  }

  // Private methods
  protected function _setIcon() {
    $this->_icon = lc_icon_admin('reports.png');
  }

  protected function _setTitle() {
    global $lC_Language;

    $this->_title = $lC_Language->get('statistics_customer_orders_title');
  }

  protected function _setHeader() {
    global $lC_Language;

    $this->_header = 
    array($lC_Language->get('statistics_customers_table_heading_customers_name'),
      $lC_Language->get('statistics_customers_table_heading_group'),
      $lC_Language->get('statistics_customers_table_heading_order_total')
      );
  }

  protected function _setData() {
    global $lC_Database, $lC_Language, $lC_Currencies;

    $this->_data = array();
    
    $this->_resultset = $lC_Database->query('select c.customers_id, c.customers_firstname,      cg.customers_group_name, sum(ot.value) as orders_total from :table_customers c, :table_customers_groups cg, :table_orders o, :table_orders_total ot where cg.customers_group_id = c.customers_group_id and c.customers_id = o.customers_id and o.orders_id = ot.orders_id and ot.class = "total" ');

    if (isset($_GET['statusID'])) { 
        
        switch($_GET['statusID']) {

          case 'Pending':           
          case 'Approved':
          case 'Rejected':
            $query = $lC_Database->query('select orders_status_id, orders_status_name from :table_orders_status where orders_status_type = "'.$_GET['statusID'].'"' );
             $query->bindTable(':table_orders_status', TABLE_ORDERS_STATUS);
             $query->bindInt(':language_id', $lC_Language->getID());   
             $query->execute();

             $ids = '';             
             while($query->next()) {
               $ids .= $query->value('orders_status_id').",";
             }
             $ids = substr($ids, 0, -1);
             $this->_resultset->appendQuery(' and o.orders_status IN ('. $ids .')');     
             //$this->_resultset->bindValue(':orders_status', $ids);

            break;

          default:
            if((int)$_GET['statusID'] > 0) {
              $this->_resultset->appendQuery(' and o.orders_status = :orders_status ');        
              $this->_resultset->bindInt(':orders_status', $_GET['statusID']);
            }
        }            
      } 

    $this->_resultset->appendQuery(' group by o.customers_id order by o.customers_id ');

    $this->_resultset->bindTable(':table_customers', TABLE_CUSTOMERS);
		$this->_resultset->bindTable(':table_customers_groups', 
    TABLE_CUSTOMERS_GROUPS);
		$this->_resultset->bindTable(':table_orders', TABLE_ORDERS);
		$this->_resultset->bindTable(':table_orders_total', TABLE_ORDERS_TOTAL);
		
    $this->_resultset->setBatchLimit($_GET['page'], 
    MAX_DISPLAY_SEARCH_RESULTS);
    $this->_resultset->execute();

    while ( $this->_resultset->next() ) {
      $this->_data[] = 
      array($this->_resultset->value('customers_firstname'),
        $this->_resultset->value('customers_group_name'),
        $lC_Currencies->format($this->_resultset->valueInt('orders_total')));
    }
  }
}
?>