<?php
/**
  @package    admin::modules
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: margin_report_sales.php v1.0 2013-08-08 datazen $
*/
global $lC_Vqmod;

if ( !class_exists('lC_Statistics') ) {
  include($lC_Vqmod->modCheck('includes/classes/statistics.php'));
}

class lC_Statistics_Margin_Report_Sales extends lC_Statistics {

  // Class constructor
  public function lC_Statistics_Margin_Report_Sales() {
    global $lC_Language, $lC_Currencies, $lC_Vqmod;

    $lC_Language->loadIniFile('modules/statistics/margin_report_sales.php');

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

    $this->_title = $lC_Language->get('statistics_margin_title');
  }

  protected function _setHeader() {
    global $lC_Language;

    $this->_header = array($lC_Language->get('statistics_margin_table_heading_order_id'),
      $lC_Language->get('statistics_margin_table_heading_item_sold'),
      $lC_Language->get('statistics_margin_table_heading_sales_amount'),
      $lC_Language->get('statistics_margin_table_heading_cost'),
      $lC_Language->get('statistics_margin_table_heading_margin($)'),
      $lC_Language->get('statistics_margin_table_heading_margin(%)'));
  }

  protected function _setData() {
    global $lC_Database, $lC_Language, $lC_Currencies;

    $this->_data = array();

    $this->_resultset = $lC_Database->query('select 
    o.orders_id, op.products_quantity,p.products_price,p.products_cost, p.products_price-p.products_cost as margin,((p.products_price-
    p.products_cost)/p.products_cost)*100 as margin_percent from 
    :table_orders o ,:table_products p, :table_orders_products op 
    where p.products_id = op.products_id and op.orders_id = o.orders_id ');
   
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
             
             $this->_resultset->appendQuery(' and o.orders_status IN ( :orders_status )');   $this->_resultset->bindValue(':orders_status', $ids);

             break;

             default:
               if((int)$_GET['statusID'] > 0) {
                  $this->_resultset->appendQuery(' and o.orders_status IN ( :orders_status )'); $this->_resultset->bindValue(':orders_status', $ids);
             }
        }
      }
   
    if(isset($_GET['manufacturerID']) && (int)$_GET['manufacturerID'] > 0) {
      $this->_resultset->appendQuery(' and p.manufacturers_id = :manufacturers_id ');          
      $this->_resultset->bindInt(':manufacturers_id', $_GET['manufacturerID']);
    }
    /*if(isset($_GET['supplierID']) && (int)$_GET['supplierID'] > 0) {
      $this->_resultset->appendQuery(' and o.orders_status = :orders_status ');          
      $this->_resultset->bindInt(':orders_status', $_GET['statusID']);
    }*/
    
    
    
      $s_date_arr = explode('/', $_GET['startDate']);
      $start = $s_date_arr['2']."-".$s_date_arr['0']."-".$s_date_arr['1'];

      $e_date_arr = explode('/', $_GET['expiresDate']);
      $end = $e_date_arr['2']."-".$e_date_arr['0']."-".$e_date_arr['1'];
/*

    print("_GET <xmp>");
    print_r($s_date_arr);
    print("</xmp>");
    print("s_date  : ".$s_date.'<br>');


    $s_date = str_replace('/', '-', $_GET['startDate']);
    $start = date('Y-m-d', strtotime($s_date));

    $e_date = str_replace('/', '-', $_GET['expiresDate']);
    $end = date('Y-m-d', strtotime($e_date));
*/
    if(isset($_GET['startDate']) && !empty($_GET['startDate']) ) {
      $this->_resultset->appendQuery(' and o.date_purchased >= :start ');          
      $this->_resultset->bindvalue(':start', $start);
    }
    if(isset($_GET['expiresDate']) && !empty($_GET['expiresDate']) ) {
      $this->_resultset->appendQuery(' and o.date_purchased <= :end');          
      $this->_resultset->bindvalue(':end', $end);
    }
    $this->_resultset->appendQuery(' order by o.orders_id asc ');

    $this->_resultset->bindTable(':table_orders', TABLE_ORDERS);
    $this->_resultset->bindTable(':table_products', TABLE_PRODUCTS);
    $this->_resultset->bindTable(':table_orders_products', TABLE_ORDERS_PRODUCTS);
    //$this->_resultset->setBatchLimit($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS);
    $this->_resultset->execute();  

  
    /*print("<xmp>");
    print_r($this->_resultset);
    print("</xmp>");
    die('11111');*/

    while ( $this->_resultset->next() ) {
      $this->_data[] = 
				array($this->_resultset->value('orders_id'),
							$this->_resultset->valueInt('products_quantity'),
							$lC_Currencies->format($this->_resultset->value('products_price')),
							$lC_Currencies->format($this->_resultset->value('products_cost')),
							$lC_Currencies->format($this->_resultset->value('margin')),
							$this->_resultset->valueInt('margin_percent'));
    }
  }
}
?>