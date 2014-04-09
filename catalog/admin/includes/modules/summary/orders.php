<?php
/**
  @package    admin::modules
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: orders.php v1.0 2013-08-08 datazen $
*/
if ( !class_exists('lC_Summary') ) {
  include($lC_Vqmod->modCheck('includes/classes/summary.php'));
}

class lC_Summary_orders extends lC_Summary {

  var $enabled = TRUE,
      $sort_order = 30;
  
  /* Class constructor */
  public function __construct() {
    global $lC_Language;

    $lC_Language->loadIniFile('modules/summary/orders.php');
    $lC_Language->loadIniFile('orders.php');

    $this->_title = $lC_Language->get('summary_orders_title');
    $this->_title_link = lc_href_link_admin(FILENAME_DEFAULT, 'orders');

    if ( lC_Access::hasAccess('orders') ) {
      $this->_setData();
    }
  }
  
  public function loadModal() {
    global $lC_Database, $lC_Language, $lC_Template, $lC_Vqmod;
    
    if ( is_dir('includes/applications/orders/modal') ) {
      if ( file_exists('includes/applications/orders/modal/edit.php') ) include_once($lC_Vqmod->modCheck('includes/applications/orders/modal/edit.php'));
    }
    
    if ( is_dir('includes/applications/orders/js') ) {
      if ( file_exists('includes/applications/orders/js/orders.js.php') ) include_once($lC_Vqmod->modCheck('includes/applications/orders/js/orders.js.php'));
    } 
    
    if ( is_dir('includes/applications/orders/classes') ) {
      if ( file_exists('includes/applications/orders/classes/orders.php') ) include_once($lC_Vqmod->modCheck('includes/applications/orders/classes/orders.php'));
    }       
  }  

  /* Private methods */
  protected function _setData() {
    global $lC_Database, $lC_Language;
    
    if (!$this->enabled) {
      $this->_data = '';
    } else {      
      $this->_data = '<div class="four-columns six-columns-tablet twelve-columns-mobile">' .
                     '  <h2 class="relative thin">' . $this->_title . '</h2>' .
                     '  <ul class="list spaced">';      

      $Qorders = $lC_Database->query('select o.orders_id, o.customers_name, o.date_purchased, s.orders_status_name, ot.text as order_total from :table_orders o, :table_orders_total ot, :table_orders_status s where o.orders_id = ot.orders_id and ot.class = "total" and o.orders_status = s.orders_status_id and s.language_id = :language_id order by o.date_purchased desc limit 7');
      $Qorders->bindTable(':table_orders', TABLE_ORDERS);
      $Qorders->bindTable(':table_orders_total', TABLE_ORDERS_TOTAL);
      $Qorders->bindTable(':table_orders_status', TABLE_ORDERS_STATUS);
      $Qorders->bindInt(':language_id', $lC_Language->getID());
      $Qorders->execute();

      while ( $Qorders->next() ) {
        $this->_data .= '    <li>' .
                        '      <a href="' . ((int)($_SESSION['admin']['access']['orders'] < 3) ? '#' : lc_href_link_admin(FILENAME_DEFAULT, 'orders=' . $Qorders->valueInt('orders_id') . '&action=save')) . '"' . 
                        '        <span class="list-link icon-price-tag icon-green" title="oID[' . $Qorders->value('orders_id') . ']">' .  
                        '          <strong><span class="tag green-bg"><small class="white">' . strip_tags($Qorders->value('order_total'))  . '</small></span></strong> &nbsp; <span class="anthracite"><strong>' . $Qorders->value('customers_name') . '</strong> &nbsp; ' . lC_DateTime::getShort($Qorders->value('date_purchased')) . '</span>' . 
                        '        </span>' .                                                                    
                        '        <div class="absolute-right compact show-on-parent-hover">' .
                        '          <span class="button icon-pencil' . ((int)($_SESSION['admin']['access']['orders'] < 3) ? ' disabled' : NULL) . '">' .  $lC_Language->get('icon_view') . '</span>' . 
                        '          <!-- a href="' . ((int)($_SESSION['admin']['access']['orders'] < 4) ? '#' : lc_href_link_admin(FILENAME_DEFAULT, 'orders=' . $Qorders->valueInt('orders_id') . '&action=save')) . '" class="button icon-trash with-tooltip' . ((int)($_SESSION['admin']['access']['orders'] < 4) ? ' disabled' : NULL) . '" title="' . $lC_Language->get('icon_delete') . '"></a -->' . 
                        '        </div>' .
                        '      </a>' .
                        '    </li>';        
      }

      $this->_data .= '  </ul>' . 
                      '</div>';

      $Qorders->freeResult();
      
      $this->_data .= $this->loadModal();      
    }
  }    
}
?>