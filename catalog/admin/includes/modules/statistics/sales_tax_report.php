<?php
/**
  @package    admin::modules
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: sales_tax_report.php v1.0 2013-08-08 datazen $
*/
global $lC_Vqmod;

if (!class_exists('lC_Statistics')) {
  include($lC_Vqmod->modCheck('includes/classes/statistics.php'));
}

class lC_Statistics_Sales_Tax_Report extends lC_Statistics {

  // Class constructor
  public function lC_Statistics_Sales_Tax_Report() {
    global $lC_Language, $lC_Currencies, $lC_Vqmod;

    $lC_Language->loadIniFile('modules/statistics/sales_tax_report.php');

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

    $this->_title = $lC_Language->get('statistics_sales_tax_report_title');
  }

  protected function _setHeader() {
    global $lC_Language;

    $lC_Language->loadIniFile('modules/statistics/sales_tax_report.php');

    $this->_header = array($lC_Language->get('statistics_sales_tax_table_heading_srno'),
                           $lC_Language->get('statistics_sales_tax_table_heading_month'),
                           $lC_Language->get('statistics_sales_tax_table_heading_year'),
                           $lC_Language->get('statistics_sales_tax_table_heading_gross_income'),
                           $lC_Language->get('statistics_sales_tax_table_heading_product_sales'),
                           $lC_Language->get('statistics_sales_tax_table_heading_non_taxed_sales'),
                           $lC_Language->get('statistics_sales_tax_table_heading_taxed_sales'),
                           $lC_Language->get('statistics_sales_tax_table_heading_taxes_collected'),
                           $lC_Language->get('statistics_sales_tax_table_heading_shipping_handling'),
                           $lC_Language->get('statistics_sales_tax_table_heading_tax_shipping'),
                           $lC_Language->get('statistics_sales_tax_table_heading_gift_vouchers')
                           );
  }

  protected function _setData() {
    global $lC_Database, $lC_Language, $lC_Currencies;
    
    $this->_data = array();
    $sel_month = isset($_GET['month']) ? $_GET['month'] : '0';

    // clear footer totals
    $footer_gross = 0;
    $footer_sales = 0;
    $footer_sales_nontaxed = 0;
    $footer_sales_taxed = 0;
    $footer_tax_coll = 0;
    $footer_shiphndl = 0;
    $footer_shipping_tax = 0;
    $footer_loworder = 0;
    $footer_other = 0;

    // create extra column so totals are comprehensively correct
    $class_val_subtotal = 'sub_total';
    $class_val_tax =      'tax';
    $class_val_shiphndl = 'shipping';
    $class_val_loworder = 'low_order_fee';
    $class_val_total =    'total';

    /****************  check for extra class ***************/
    $extra_class_query_raw = $lC_Database->query('select value from :table_orders_total ot where ot.class <> :class and ot.class <> :class and ot.class <> :class and ot.class <> :class and ot.class <> :class '); 
    
    $extra_class_query_raw->bindValue(':class', $class_val_subtotal);
    $extra_class_query_raw->bindValue(':class', $class_val_tax);
    $extra_class_query_raw->bindValue(':class', $class_val_shiphndl);
    $extra_class_query_raw->bindValue(':class', $class_val_loworder);
    $extra_class_query_raw->bindValue(':class', $class_val_total);
    $extra_class_query_raw->bindTable(':table_orders_total', TABLE_ORDERS_TOTAL);       
    $extra_class_query_raw->execute();         
    
    $extra_class = false;    
    
    if ($extra_class_query_raw->numberOfRows() > 0) { 
      $extra_class_query = $extra_class_query_raw->toArray(); 
      $extra_class = true;
    }
    /*******************************/

    $this->_resultset = $lC_Database->query('select sum(ot.value) as gross_sales, MONTHNAME(o.date_purchased) as row_month, YEAR(o.date_purchased) as row_year, MONTH(o.date_purchased) as i_month, DAYOFMONTH(o.date_purchased) row_day from :table_orders o left join :table_orders_total ot on (o.orders_id = ot.orders_id) where ot.class = :class'); 

    $orders_status_ids = '';
    if (isset($_GET['statusID'])) { 
      switch($_GET['statusID']) {
        case 'Pending': 
        case 'Approved':
        case 'Rejected':
          $query = $lC_Database->query('select orders_status_id, orders_status_name from :table_orders_status where orders_status_type = "' . $_GET['statusID'] . '"');
          $query->bindTable(':table_orders_status', TABLE_ORDERS_STATUS);
          $query->bindInt(':language_id', $lC_Language->getID());   
          $query->execute();          
                       
          while($query->next()) {
            $orders_status_ids .= $query->value('orders_status_id') . ",";
          }
          $orders_status_ids = substr($orders_status_ids, 0, -1);
          $this->_resultset->appendQuery(' and o.orders_status IN (' . $orders_status_ids . ')');
        break; 
      default:
        if ((int)$_GET['statusID'] > 0) {
          $orders_status_ids = $_GET['statusID'];
          $this->_resultset->appendQuery(' and o.orders_status = :orders_status ');        
          $this->_resultset->bindInt(':orders_status', $_GET['statusID']);
        }
      }        
    } 

    if ($sel_month <> 0)  {
      $this->_resultset->appendQuery(' and month(o.date_purchased) = :month_purchased ');
      $this->_resultset->bindInt(':month_purchased', $sel_month);
    }

    $this->_resultset->appendQuery('group by YEAR(o.date_purchased), MONTH(o.date_purchased)');
    if ($sel_month <> 0)  {
      $this->_resultset->appendQuery(', DAYOFMONTH(o.date_purchased)');
    }
    
    $this->_resultset->appendQuery('order by o.date_purchased Desc');
    $this->_resultset->bindTable(':table_orders', TABLE_ORDERS);  
    $this->_resultset->bindTable(':table_orders_total', TABLE_ORDERS_TOTAL);        
    $this->_resultset->bindValue(':class', $class_val_total);
    $this->_resultset->execute();

    $num_rows = $this->_resultset->numberOfRows();
    $srno = 0;
    
    while ($this->_resultset->next()) {
      $row_month = $this->_resultset->value('row_month');
      $month = $this->_resultset->value('i_month');
      $year = $this->_resultset->value('row_year');
      $day = $this->_resultset->value('row_day');
      $gross_sales = $this->_resultset->value('gross_sales');

      if ($sel_month == 0) { 
        $content = $year;
        $year_txt = $lC_Language->get('statistics_sales_tax_table_heading_year');
      } else {
        $content = $day;  
        $head_year = $lC_Language->get('statistics_sales_tax_table_heading_year');
        $head_day = $lC_Language->get('statistics_sales_tax_table_heading_day');
        $this->_header = str_replace($head_year, $head_day, $this->_header);
        $year_txt = $row_month;
      }
      
      $rows++;
      $srno++;

      if ($rows > 1 && $year <> $last_row_year) {
        $this->_data[] = array('<span class="sales-tax-year-total-row">' . $srno . '</span>', // SrNo
                               '<span class="sales-tax-year-total-row stytr">' . $year_txt . '</span>', // YEAR
                               '<span class="sales-tax-year-total-row">' . $last_row_year . '</span>', // YEAR
                               '<span class="sales-tax-year-total-row">' . $lC_Currencies->format($footer_gross) . '</span>', // GROSS INCOME
                               '<span class="sales-tax-year-total-row">' . $lC_Currencies->format($footer_sales) . '</span>', // TOTAL OF PRODUCT PRICE
                               '<span class="sales-tax-year-total-row">' . $lC_Currencies->format($footer_sales_nontaxed) . '</span>', // WITHOUT TAX PRODUCT PRICE
                               '<span class="sales-tax-year-total-row">' . $lC_Currencies->format($footer_sales_taxed) . '</span>', // WITH TAX PRODUCT PRICE
                               '<span class="sales-tax-year-total-row">' . $lC_Currencies->format($footer_tax_coll) . '</span>', // TAX ON PRODUCT
                               '<span class="sales-tax-year-total-row">' . $lC_Currencies->format($footer_shiphndl) . '</span>', // SHIPPING VALUE
                               '<span class="sales-tax-year-total-row">' . $lC_Currencies->format($footer_shipping_tax) . '</span>', // TAX ON SHIPPING
                               '<span class="sales-tax-year-total-row">' . $lC_Currencies->format($footer_other) . '</span>' // COUPON
                               );
        // clear footer totals
        $footer_gross = 0;
        $footer_sales = 0;
        $footer_sales_nontaxed = 0;
        $footer_sales_taxed = 0;
        $footer_tax_coll = 0;
        $footer_shiphndl = 0;
        $footer_shipping_tax = 0;
        $footer_loworder = 0;
        $footer_other = 0;
        $srno++;
      }
      
      // determine net sales for row
      // Retrieve totals for products that are zero VAT rated        
      $net_sales_query_raw = $lC_Database->query('select sum(op.products_price * op.products_quantity) as net_sales from :table_orders o left join :table_orders_products  op on (o.orders_id = op.orders_id) where op.products_tax = 0 and month(o.date_purchased) = :month_purchased and year(o.date_purchased) = :year_purchased'); 
      if ($orders_status_ids <> '') { 
        $net_sales_query_raw->appendQuery('and o.orders_status IN ('. $orders_status_ids . ')');
      }
      if ($sel_month <> 0) {
        $net_sales_query_raw->appendQuery('and dayofmonth(o.date_purchased) = :row_day');
        $net_sales_query_raw->bindInt(':row_day', $day);
      }
      $net_sales_query_raw->bindInt(':month_purchased', $month);
      $net_sales_query_raw->bindInt(':year_purchased', $year);
      $net_sales_query_raw->bindTable(':table_orders', TABLE_ORDERS);
      $net_sales_query_raw->bindTable(':table_orders_products', TABLE_ORDERS_PRODUCTS);       
      $net_sales_query_raw->execute();
      $net_sales_this_row = 0;            
      if ($net_sales_query_raw->numberOfRows() > 0) {
        $zero_rated_sales_this_row = $net_sales_query_raw->toArray();
      } 

      // Retrieve totals for products that are NOT zero VAT rated
      $net_sales_query_raw1 = $lC_Database->query('select sum(op.products_price * op.products_quantity) as net_sales, sum(op.products_price * op.products_quantity * (1 + (op.products_tax / 100.0))) as gross_sales, sum((op.products_price * op.products_quantity * (1 + (op.products_tax / 100.0))) - (op.products_price * op.products_quantity)) as tax from :table_orders o left join :table_orders_products op on (o.orders_id = op.orders_id) where op.products_tax <> 0 and month(o.date_purchased) = :month_purchased and year(o.date_purchased) = :year_purchased ');
      if ($orders_status_ids <> '') { 
        $net_sales_query_raw1->appendQuery('and o.orders_status IN (' . $orders_status_ids . ')');
      }            
      if ($sel_month <> 0) {
        $net_sales_query_raw1->appendQuery('and dayofmonth(o.date_purchased) = :row_day');
        $net_sales_query_raw1->bindInt(':row_day', $day);
      }          
      $net_sales_query_raw1->bindInt(':month_purchased', $month);
      $net_sales_query_raw1->bindInt(':year_purchased', $year);
      $net_sales_query_raw1->bindTable(':table_orders', TABLE_ORDERS);
      $net_sales_query_raw1->bindTable(':table_orders_products', TABLE_ORDERS_PRODUCTS);       
      $net_sales_query_raw1->execute();
      $net_sales_this_row = 0;          
      if ($net_sales_query_raw1->numberOfRows() > 0) { 
        $net_sales_this_row = $net_sales_query_raw1->toArray();
      }

      // Total tax. This is needed so we can calculate any tax that has been added to the postage
      $tax_coll_query_raw = $lC_Database->query('select sum(ot.value) as tax_coll from :table_orders o left join :table_orders_total ot on (o.orders_id = ot.orders_id) where  ot.class=:class and month(o.date_purchased) = :month_purchased and year(o.date_purchased) = :year_purchased ');
      if ($orders_status_ids <> '') { 
        $tax_coll_query_raw->appendQuery('and o.orders_status IN ('. $orders_status_ids .')');
      }            
      if ($sel_month <> 0) {
        $tax_coll_query_raw->appendQuery('and dayofmonth(o.date_purchased) = :row_day');
        $tax_coll_query_raw->bindInt(':row_day', $day);
      } 
      $tax_coll_query_raw->bindValue(':class', $class_val_tax);
      $tax_coll_query_raw->bindInt(':month_purchased', $month);
      $tax_coll_query_raw->bindInt(':year_purchased', $year);
      $tax_coll_query_raw->bindTable(':table_orders', TABLE_ORDERS);
      $tax_coll_query_raw->bindTable(':table_orders_total', TABLE_ORDERS_TOTAL);       
      $tax_coll_query_raw->execute();
      $tax_this_row = 0;
      if ($tax_coll_query_raw->numberOfRows() > 0) { 
        $tax_this_row = $tax_coll_query_raw->toArray();          
      } 

      // shipping and handling charges for row
      $shiphndl_query_raw = $lC_Database->query('select sum(ot.value) as shiphndl from :table_orders o left join :table_orders_total ot on (o.orders_id = ot.orders_id) where ot.class=:class and month(o.date_purchased) = :month_purchased and year(o.date_purchased) = :year_purchased ');
      if ($orders_status_ids <> '') { 
        $shiphndl_query_raw->appendQuery('and o.orders_status IN ('. $orders_status_ids .')');
      } 
      if ($sel_month <> 0) {
        $shiphndl_query_raw->appendQuery('and dayofmonth(o.date_purchased) = :row_day');
        $shiphndl_query_raw->bindInt(':row_day', $day);
      } 
      $shiphndl_query_raw->bindValue(':class', $class_val_shiphndl);
      $shiphndl_query_raw->bindInt(':month_purchased', $month);
      $shiphndl_query_raw->bindInt(':year_purchased', $year);
      $shiphndl_query_raw->bindTable(':table_orders', TABLE_ORDERS);
      $shiphndl_query_raw->bindTable(':table_orders_total', TABLE_ORDERS_TOTAL);       
      $shiphndl_query_raw->execute();
      $shiphndl_this_row = 0;
      if ($shiphndl_query_raw->numberOfRows() > 0) { 
        $shiphndl_this_row = $shiphndl_query_raw->toArray(); 
      }

      // low order fees for row       
      // if ($loworder) {
      if (1) {
        $loworder_query_raw = $lC_Database->query('select sum(ot.value) as loworder from :table_orders o left join :table_orders_total ot on (o.orders_id = ot.orders_id) where ot.class=:class and month(o.date_purchased) = :month_purchased and year(o.date_purchased) = :year_purchased ');
        if ($orders_status_ids <> '') { 
          $loworder_query_raw->appendQuery('and o.orders_status IN (' . $orders_status_ids . ')');
        }
        if ($sel_month <> 0) {
          $loworder_query_raw->appendQuery('and dayofmonth(o.date_purchased) = :row_day');
          $loworder_query_raw->bindInt(':row_day', $day);
        }
        $loworder_query_raw->bindValue(':class', $class_val_loworder);
        $loworder_query_raw->bindInt(':month_purchased', $month);
        $loworder_query_raw->bindInt(':year_purchased', $year);
        $loworder_query_raw->bindTable(':table_orders', TABLE_ORDERS);
        $loworder_query_raw->bindTable(':table_orders_total', TABLE_ORDERS_TOTAL);       
        $loworder_query_raw->execute();
        $loworder_this_row = 0;
        if ($loworder_query_raw->numberOfRows() > 0) { 
          $loworder_this_row = $loworder_query_raw->toArray();
        }
      }

      // additional column if extra class value in orders_total table  
      if ($extra_class) {
        $other_query_raw = $lC_Database->query('select sum(ot.value) as other from :table_orders o left join :table_orders_total ot on (o.orders_id = ot.orders_id) where ot.class<>:class and ot.class<>:class and ot.class<>:class and ot.class<>:class and ot.class<>:class and month(o.date_purchased) = :month_purchased and year(o.date_purchased) = :year_purchased ');
        if ($orders_status_ids <> '') { 
          $other_query_raw->appendQuery('and o.orders_status IN ('. $orders_status_ids .')');
        }
        if ($sel_month <> 0) {
          $other_query_raw->appendQuery('and dayofmonth(o.date_purchased) = :row_day');      $other_query_raw->bindInt(':row_day',$day);
        }
        $other_query_raw->bindValue(':class', $class_val_subtotal);
        $other_query_raw->bindValue(':class', $class_val_tax);
        $other_query_raw->bindValue(':class', $class_val_shiphndl);
        $other_query_raw->bindValue(':class', $class_val_loworder);
        $other_query_raw->bindValue(':class', $class_val_total);
        $other_query_raw->bindInt(':month_purchased', $month);
        $other_query_raw->bindInt(':year_purchased', $year);
        $other_query_raw->bindTable(':table_orders', TABLE_ORDERS);
        $other_query_raw->bindTable(':table_orders_total', TABLE_ORDERS_TOTAL);       
        $other_query_raw->execute(); 
        $other_this_row = 0;
        if ($other_query_raw->numberOfRows() > 0) { 
          $other_this_row = $other_query_raw->toArray();  
        }
      }

      // Correct any rounding errors
      $net_sales_this_row['net_sales'] = (floor(($net_sales_this_row['net_sales'] * 100) + 0.5)) / 100;
      $net_sales_this_row['tax'] = (floor(($net_sales_this_row['tax'] * 100) + 0.5)) / 100;
      $zero_rated_sales_this_row['net_sales'] = (floor(($zero_rated_sales_this_row['net_sales'] * 100) + 0.5)) / 100;
      $tax_this_row['tax_coll'] = (floor(($tax_this_row['tax_coll'] * 100) + 0.5)) / 100;
      $total_of_product_price = ($net_sales_this_row['net_sales'] + $zero_rated_sales_this_row['net_sales']);
      $tax_on_shipping = ($tax_this_row['tax_coll'] - $net_sales_this_row['tax']);
       
      // accumulate row results in footer
      $footer_gross += $gross_sales; // Gross Income
      $footer_sales += $total_of_product_price; // Product Sales
      $footer_sales_nontaxed += $zero_rated_sales_this_row['net_sales']; // Nontaxed Sales
      $footer_sales_taxed += $net_sales_this_row['net_sales']; // Taxed Sales
      $footer_tax_coll += $net_sales_this_row['tax']; // Taxes Collected
      $footer_shiphndl += $shiphndl_this_row['shiphndl']; // Shipping & handling
      $footer_shipping_tax += $tax_on_shipping; // Shipping Tax
      $footer_loworder += $loworder_this_row['loworder'];
      if ($extra_class) $footer_other += $other_this_row['other'];
     
      $this->_data[] = array($srno, // Serial No for proper Sorting
                             '<a href="' . lc_href_link_admin(FILENAME_DEFAULT, 'statistics&module=' . $_GET['module'] . '&month=' . $month) . '">' . $row_month . '</a>', // Month
                             $content, // YEAR
                             $lC_Currencies->format($gross_sales), // GROSS INCOME
                             $lC_Currencies->format($total_of_product_price), // TOTAL OF PRODUCT PRICE
                             $lC_Currencies->format($zero_rated_sales_this_row['net_sales']), // WITHOUT TAX PRODUCT PRICE
                             $lC_Currencies->format($net_sales_this_row['net_sales']), // WITH TAX PRODUCT PRICE
                             $lC_Currencies->format($net_sales_this_row['tax']), // TAX ON PRODUCT
                             $lC_Currencies->format($shiphndl_this_row['shiphndl']), // SHIPPING VALUE
                             $lC_Currencies->format($tax_on_shipping), // TAX ON SHIPPING
                             $lC_Currencies->format($other_this_row['other']) // COUPON
                            );


      $last_row_year = $year;
      if ($rows == $num_rows) {
        $srno++;
        $this->_data[] = array('<span style="color:red;font-weight:bold;">' . $srno . '</span>', // YEAR
                               '<span style="color:red;font-weight:bold;">' . $year_txt . '</span>', // YEAR
                               '<span style="color:red;font-weight:bold;">' . $last_row_year . '</span>', // YEAR
                               '<span style="color:red;font-weight:bold;">' . $lC_Currencies->format($footer_gross) . '</span>', // GROSS INCOME
                               '<span style="color:red;font-weight:bold;">' . $lC_Currencies->format($footer_sales) . '</span>', // TOTAL OF PRODUCT PRICE
                               '<span style="color:red;font-weight:bold;">' . $lC_Currencies->format($footer_sales_nontaxed) . '</span>', // WITHOUT TAX PRODUCT PRICE
                               '<span style="color:red;font-weight:bold;">' . $lC_Currencies->format($footer_sales_taxed) . '</span>', // WITH TAX PRODUCT PRICE
                               '<span style="color:red;font-weight:bold;">' . $lC_Currencies->format($footer_tax_coll) . '</span>', // TAX ON PRODUCT
                               '<span style="color:red;font-weight:bold;">' . $lC_Currencies->format($footer_shiphndl) . '</span>', // SHIPPING VALUE
                               '<span style="color:red;font-weight:bold;">' . $lC_Currencies->format($footer_shipping_tax) . '</span>', // TAX ON SHIPPING
                               '<span style="color:red;font-weight:bold;">' . $lC_Currencies->format($footer_other) . '</span>' // COUPON
                               );
        // clear footer totals
        $footer_gross = 0;
        $footer_sales = 0;
        $footer_sales_nontaxed = 0;
        $footer_sales_taxed = 0;
        $footer_tax_coll = 0;
        $footer_shiphndl = 0;
        $footer_shipping_tax = 0;
        $footer_loworder = 0;
        $footer_other = 0;
      }
    }
  }
}
?>