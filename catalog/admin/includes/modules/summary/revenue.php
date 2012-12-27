<?php
/*
  $Id: revenue.php v1.0 2011-11-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
if ( !class_exists('lC_Summary') ) {
  include('includes/classes/summary.php');
}

class lC_Summary_revenue extends lC_Summary {

  var $enabled = TRUE,
      $sort_order = 20;
  
  /* Class constructor */
  function __construct() {
    global $lC_Language;

    $lC_Language->loadIniFile('modules/summary/revenue.php');

    $this->_title = $lC_Language->get('summary_revenue_title');
    $this->_title_link = lc_href_link_admin(FILENAME_DEFAULT, 'revenue');

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

      require_once('../includes/classes/currencies.php');
      $lC_Currencies = new lC_Currencies();
    
      $lastMonth = date("m",strtotime("-1 month"));
      $currentDay = date("d");
      $currentMonth = date("m");
      $currentYear = date("Y");
           
      $Qorders = $lC_Database->query('select o.orders_id, ot.value as order_total, greatest(o.date_purchased, ifnull(o.last_modified, "1970-01-01")) as date_last_modified from :table_orders o, :table_orders_total ot where o.orders_id = ot.orders_id and ot.class = "total" order by date_last_modified desc limit 6');
      $Qorders->bindTable(':table_orders', TABLE_ORDERS);
      $Qorders->bindTable(':table_orders_total', TABLE_ORDERS_TOTAL);
      $Qorders->bindTable(':table_orders_status', TABLE_ORDERS_STATUS);
      $Qorders->bindInt(':language_id', $lC_Language->getID());
      $Qorders->execute();

      $last = 0;
      $today = 0;
      $current = 0;
      $annual = 0;
      while ($Qorders->next()) { 
        
        $year = substr($Qorders->value('date_last_modified'), 0, 4);
        $month = substr($Qorders->value('date_last_modified'), 5, 2);
        $day = substr($Qorders->value('date_last_modified'), 8, 2);
                
        if ($currentYear == $year) {
          $annual += $Qorders->valueDecimal('order_total');
          if ($month == $currentMonth) {
            $current += $Qorders->valueDecimal('order_total');
            if ($day == $currentDay) {
              $today += $Qorders->valueDecimal('order_total');
            }            
          }          
          if ($month == $lastMonth) {
            $last += $Qorders->valueDecimal('order_total');
          }
        }
      }
      
      $llast = ($last > 0) ? $last : .01;
      $growth = (($current - $last) / $llast);
      $ribbonText = ($growth > 0) ? '<span class="ribbon-inner green-gradient glossy"><span class="fact-progress">+' . number_format($growth, 1) . '% ▲</span></span>' : '<span class="ribbon-inner red-gradient glossy"><span class="fact-progress">-' . number_format($growth, 1) . '% ▼</span></span>';

      $this->_data = '<div class="new-row-mobile four-columns six-columns-tablet twelve-columns-mobile">' . 
                     '  <style>#revenueTable h3, h4 { margin:0; }</style>' . 
                     '  <div class="block large-margin-bottom">' .
                     '    <h3 class="block-title">' . $this->_title. '</h3>' .
                     '    <span class="ribbon">' . $ribbonText . '</span>' .
                     '    <div class="with-padding">' .
                     '      <table width="100%" id="revenueTable" class="responsive-table">' .
                     '        <tr>' .
                     '          <td height="24px;" width="99px" class="orange" style="border-right:1px solid #ccc; padding:8px 0 0 10px;"><h3>' . $lC_Currencies->format($today) . '</h3></td>' .
                     '          <td height="24px;" style="padding:12px 0 0 10px;"><h4>' . $lC_Language->get('summary_revenue_text_todays') . ' ' . $lC_Language->get('summary_revenue_text_totals') . '</h4></td>' .
                     '        </tr>' .
                     '        <tr><td colspan="2"><hr></td></tr>' .
                     '        <tr>' .
                     '          <td height="24px;" width="99px" style="border-right:1px solid #ccc; padding:8px 0 0 10px;"><h3>' . $lC_Currencies->format($current) . '</h3></td>' .
                     '          <td height="24px;" style="padding:12px 0 0 10px;"><h4>' . $lC_Language->get('month_' . $currentMonth) . ' ' . $lC_Language->get('summary_revenue_text_totals') . '</h4></td>' .
                     '        </tr>' .
                     '        <tr><td colspan="2"><hr></td></tr>' .
                     '        <tr>' .
                     '          <td height="24px;" width="99px" style="border-right:1px solid #ccc; padding:8px 0 0 10px;"><h3>' . $lC_Currencies->format($last) . '</h3></td>' .
                     '          <td height="24px;" style="padding:12px 0 0 10px;"><h4>' . $lC_Language->get('month_' . $lastMonth) . ' ' . $lC_Language->get('summary_revenue_text_totals') . '</h4></td>' .
                     '        </tr>' .
                     '        <tr><td colspan="2"><hr></td></tr>' .                     
                     '        <tr>' .
                     '          <td height="24px;" width="99px" style="border-right:1px solid #ccc; padding:8px 0 0 10px;"><h3>' . $lC_Currencies->format($annual) . '</h3></td>' .
                     '          <td height="24px;" style="padding:12px 0 0 10px;"><h4>' . $currentYear . ' ' . $lC_Language->get('summary_revenue_heading_yearly') . ' ' . $lC_Language->get('summary_revenue_text_totals') . '</h4></td>' .
                     '        </tr>' .
                     '      </table>' .
                     '    </div>' .
                     '  </div>' .
                     '</div>';

      $Qorders->freeResult();      
    }
  }
}
?>