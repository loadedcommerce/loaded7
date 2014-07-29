<?php
/**
  @package    admin::modules
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: low_stock_report.php v1.0 2013-08-08 datazen $
*/
global $lC_Vqmod;

if ( !class_exists('lC_Statistics') ) {
  include($lC_Vqmod->modCheck('includes/classes/statistics.php'));
}

class lC_Statistics_Low_Stock_Report extends lC_Statistics {

  // Class constructor
  public function lC_Statistics_Low_Stock_Report() {
    global $lC_Language;

    $lC_Language->loadIniFile('modules/statistics/low_stock_report.php');

    $this->_setIcon();
    $this->_setTitle();
  }

  // Private methods
  protected function _setIcon() {
    $this->_icon = lc_icon_admin('reports.png');
  }

  protected function _setTitle() {
    global $lC_Language;

    $this->_title = $lC_Language->get('statistics_low_stock_report_title');
  }

  protected function _setHeader() {
    global $lC_Language;

    $this->_header = array($lC_Language->get('statistics_low_stock_table_heading_products'),
                           $lC_Language->get('statistics_low_stock_table_heading_qty_available'),
                           $lC_Language->get('statistics_low_stock_table_heading_model'),
                           $lC_Language->get('statistics_low_stock_table_heading_sales'),
                           $lC_Language->get('statistics_low_stock_table_heading_est_stock'),
                           $lC_Language->get('statistics_low_stock_table_heading_status'));
  }

  protected function _setData() {
    global $lC_Database, $lC_Language;

    $this->_data = array();

    $this->_resultset = $lC_Database->query('select p.products_id, pd.products_name, p.products_quantity, p.products_model, count( op.products_quantity ) AS ordertotal, p.products_status from :table_products p, :table_products_description pd, :table_orders_products op LEFT JOIN :table_orders o on(op.orders_id = o.orders_id ) WHERE p.products_id = pd.products_id and pd.products_id = op.products_id and pd.language_id =1 and o.date_purchased >= DATE_SUB( CURDATE( ),INTERVAL 60 DAY) and p.products_quantity <=5 group by p.products_quantity desc');
    $this->_resultset->bindTable(':table_products', TABLE_PRODUCTS);
    $this->_resultset->bindTable(':table_products_description', TABLE_PRODUCTS_DESCRIPTION);
    $this->_resultset->bindTable(':table_orders_products', TABLE_ORDERS_PRODUCTS);
    $this->_resultset->bindTable(':table_orders', TABLE_ORDERS);
    $this->_resultset->bindInt(':language_id', $lC_Language->getID());
    $this->_resultset->bindInt(':stock_reorder_level', STOCK_REORDER_LEVEL);    
    $this->_resultset->execute();

    while ( $this->_resultset->next() ) {
      $products_status = (($this->_resultset->valueInt('products_status') == 1) ? '<span class="icon-tick icon-size2 icon-green cursor-pointer with-tooltip" title="' . $lC_Language->get('text_disable_product') . '"></span>' : '<span class="icon-cross icon-size2 icon-red cursor-pointer with-tooltip" title="' . $lC_Language->get('text_enable_product') . '"></span>');

      $this->_data[] = array($this->_resultset->value('products_name'),
                             $this->_resultset->valueInt('products_quantity'),
                             $this->_resultset->value('products_model'),
                             $this->_resultset->valueInt('ordertotal'),
                             '-',
                             $products_status );
    }
  }
}
?>