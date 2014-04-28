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

    $this->_header = array($lC_Language->get('statistics_sales_tax_table_heading_month'),
                           $lC_Language->get('statistics_sales_tax_table_heading_day'),
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
    $this->_resultset = $lC_Database->query('select o.orders_id, ot.value, op.products_price, op.products_price, MONTHNAME(o.date_purchased) as month from :table_orders o, :table_orders_products op, :table_orders_total ot where o.orders_id = op.orders_id and op.orders_id = ot.orders_id and ot.class = :class ');

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
             $this->_resultset->appendQuery(' and o.orders_status IN ( :orders_status )');          
             $this->_resultset->bindValue(':orders_status', $ids);

            break;

          default:
            if((int)$_GET['statusID'] > 0) {
              $this->_resultset->appendQuery(' and o.orders_status = :orders_status ');        
              $this->_resultset->bindInt(':orders_status', $_GET['statusID']);
          }
        }        
      } 

    $this->_resultset->appendQuery('order by o.date_purchased Desc');
    $this->_resultset->bindTable(':table_orders', TABLE_ORDERS);
    $this->_resultset->bindTable(':table_orders_products', TABLE_ORDERS_PRODUCTS);  
    $this->_resultset->bindTable(':table_orders_total', TABLE_ORDERS_TOTAL);        
    $this->_resultset->bindValue(':class', 'total');
    $this->_resultset->execute();    
    
    while($this->_resultset->next()) {
      // get order tax,shipping and coupon
      $orderTotal = $lC_Database->query('select o.orders_id ,ot.*  from :table_orders o, :table_orders_total ot where ot.orders_id = o.orders_id and o.orders_id = :orders_id');
      $orderTotal->bindTable(':table_orders', TABLE_ORDERS);
      $orderTotal->bindTable(':table_orders_total', TABLE_ORDERS_TOTAL); 
      $orderTotal->bindInt(':orders_id', $this->_resultset->value('orders_id')); 
      $orderTotal->execute();
      
      $tax = 0; 
      $shipping = 0; 
      $coupon = 0; 
      $taxed_sale = 0;
      $non_taxed_sale = $this->_resultset->valueInt('products_price');

      while($orderTotal->next()) {
        if($orderTotal->value('class') == "tax") {
          $taxed_sale = $this->_resultset->valueInt('products_price');
          $non_taxed_sale = 0;
          $tax = $orderTotal->value('value'); 
        } elseif($orderTotal->value('class') == "shipping") {
          $shipping = $orderTotal->value('value');
        } elseif($orderTotal->value('class') == "coupon") {
          $coupon = $orderTotal->value('value');  
        }
      } 

      $this->_data[] = array($this->_resultset->value('month'),
                           '-',
                           $lC_Currencies->format($this->_resultset->valueInt('value')),
                           $lC_Currencies->format($this->_resultset->valueInt('products_price')),
                           $lC_Currencies->format($non_taxed_sale),
                           $lC_Currencies->format($taxed_sale),
                           $lC_Currencies->format($tax),                           
                           $lC_Currencies->format($shipping),
                           $lC_Currencies->format($this->_resultset->valueInt('-')),
                           $lC_Currencies->format($coupon)
                           );
    }
  }
}
?>