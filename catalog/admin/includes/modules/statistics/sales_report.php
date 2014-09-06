<?php
  /**
  @package    admin::modules
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: sales_report.php v1.0 2013-08-08 datazen $
  */
  global $lC_Vqmod;

  if (!class_exists('lC_Statistics')) {
    include($lC_Vqmod->modCheck('includes/classes/statistics.php'));
  }

  class lC_Statistics_Sales_Report extends lC_Statistics {

    // Class constructor
    public function lC_Statistics_Sales_Report() {
      global $lC_Language, $lC_Currencies, $lC_Vqmod;

      $lC_Language->loadIniFile('modules/statistics/sales_report.php');

      if (!isset($lC_Currencies)) {
        if (!class_exists('lC_Currencies')) {
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

      $this->_title = $lC_Language->get('statistics_sales_title');
    }

    protected function _setHeader() {
      global $lC_Language;

      $this->_header = array($lC_Language->get('statistics_sales_table_heading_date'),
                             $lC_Language->get('statistics_sales_table_heading_orders'),
                             $lC_Language->get('statistics_sales_table_heading_items'),
                             $lC_Language->get('statistics_sales_table_heading_revenue'),
                             $lC_Language->get('statistics_sales_table_heading_shipping'),
                             $lC_Language->get('statistics_sales_table_heading_discount')
                             );
    }

    protected function _setData() {
      global $lC_Database, $lC_Language, $lC_Currencies;

      $this->_data = array();      

      $orderDate_qry = $lC_Database->query('select o.date_purchased , count(*) as orderCount from :table_orders o where 1 ');


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
             $orderDate_qry->appendQuery(' and o.orders_status IN ( :orders_status )');          
             $orderDate_qry->bindValue(':orders_status', $ids);

            break;

          default:
            if((int)$_GET['statusID'] > 0) {
              $orderDate_qry->appendQuery(' and o.orders_status = :orders_status ');          
              $orderDate_qry->bindInt(':orders_status', $_GET['statusID']);
            }
        }            
      } 

      $s_date_arr = explode('/', $_GET['startDate']);
      $start = $s_date_arr['2']."-".$s_date_arr['0']."-".$s_date_arr['1'];

      $e_date_arr = explode('/', $_GET['expiresDate']);
      $end = $e_date_arr['2']."-".$e_date_arr['0']."-".$e_date_arr['1'];

      if(isset($_GET['startDate']) && !empty($_GET['startDate']) ) {
      $orderDate_qry->appendQuery(' and o.date_purchased >= :start ');          
      $orderDate_qry->bindvalue(':start', $start);
    }
    if(isset($_GET['expiresDate']) && !empty($_GET['expiresDate']) ) {
      $orderDate_qry->appendQuery(' and o.date_purchased <= :end');          
      $orderDate_qry->bindvalue(':end', $end);
    }
      
      $orderDate_qry->appendQuery(' group by o.date_purchased ');

      $orderDate_qry->bindTable(':table_orders', TABLE_ORDERS);	      
      $orderDate_qry->execute();


      while($orderDate_qry->next()) {

        $orderCount   = 0;
        $itemCount    = 0;
        $revenueTotal = 0;
        $shippingTotal= 0;
        $couponTotal  = 0;

        $datePurchased  =	 lC_DateTime::getShort($orderDate_qry->value('date_purchased'));
        $orderCount     =  $orderDate_qry->value('orderCount');

        $order_qry = $lC_Database->query('select orders_id from :table_orders where date_purchased = :date_purchased ');

        $order_qry->bindTable(':table_orders', TABLE_ORDERS);	
        $order_qry->bindValue(':date_purchased',$orderDate_qry->value('date_purchased'));
        $order_qry->setBatchLimit($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS);
        $order_qry->execute();

        while($order_qry->next()) {
          $tmp_arry = $this->_getOrdersProductsDetails_sales($order_qry->value('orders_id'));
          foreach($tmp_arry as $k => $v) {
            switch($k) {
              case 'itemCount':
                $itemCount += $v;
                break;
              case 'revenueTotal':
                $revenueTotal += $v;
                break;
              case 'shippingTotal':
                $shippingTotal += $v;
                break;
              case 'couponTotal':
                $couponTotal += $v;
                break;
            }
          }
        }
        $this->_data[] = array($datePurchased,
                               $orderCount,
                               $itemCount,
                               $lC_Currencies->format($revenueTotal),
                               $lC_Currencies->format($shippingTotal),
                               $lC_Currencies->format($couponTotal)
                               );      
      } 
    }

    function _getOrdersProductsDetails_sales($orders_id) {
      global $lC_Database;        

      $orderProducts_qry = $lC_Database->query('select ((p.products_price-p.products_cost)* op.products_quantity) as revenue, op.products_quantity  from :table_products p, :table_orders_products op where p.products_id = op.products_id and op.orders_id = :orders_id ');
      $orderProducts_qry->bindTable(':table_products', TABLE_PRODUCTS);
      $orderProducts_qry->bindTable(':table_orders_products', TABLE_ORDERS_PRODUCTS);
      $orderProducts_qry->bindValue(':orders_id', $orders_id);	

      while($orderProducts_qry->next()) {
        $qty += $orderProducts_qry->value('products_quantity');
        $revenue += $orderProducts_qry->value('revenue');
      }

      $orderTotals_qry = $lC_Database->query('select title,text,value,class from :table_orders_total where orders_id = :orders_id and (class=:class or class=:class )');
      $orderTotals_qry->bindTable(':table_orders_total', TABLE_ORDERS_TOTAL);
      $orderTotals_qry->bindValue(':class', 'shipping');
      $orderTotals_qry->bindValue(':class', 'coupon');
      $orderTotals_qry->bindValue(':orders_id', $orders_id);	

      while($orderTotals_qry->next()) {
        if ($orderTotals_qry->value('class') == 'shipping') {
          $shipping += $orderTotals_qry->value('value');       
        } else if ($orderTotals_qry->value('class') == 'coupon' ) {
          $coupon += $orderTotals_qry->value('value');
        }
      }

      $return_arr = array('itemCount'     => $qty,
                          'revenueTotal'  => $revenue,
                          'shippingTotal' => $shipping,
                          'couponTotal'   => $coupon
                          );      

      return $return_arr;
    }
  }
?>