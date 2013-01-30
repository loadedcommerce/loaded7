<?php
/*
  $Id: general.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
 
  @method The lC_Search_Admin class is for AJAX remote program control
*/

class lC_General_Admin {
 /*
  * Returns the search results from database
  *
  * @param string $_GET['q'] The search string
  * @access public
  * @return array
  */
  public static function find($search) {
    global $lC_Database, $lC_Language;
    
    $result['html'] = '<ul class="big-menu collapsible as-accoridion black-gradient">' . "\n";
    
    // return order data and create the array
    $Qorders = array();    
    $Qorders = $lC_Database->query("select o.orders_id,  
                                           o.customers_name, 
                                           o.customers_email_address, 
                                           ot.text 
                                      from :table_orders o 
                                 left join :table_orders_total ot 
                                        on (o.orders_id = ot.orders_id) 
                                     where (convert(`customers_id` using utf8) regexp '" . $search . "' 
                                        or convert(`customers_name` using utf8) regexp '" . $search . "' 
                                        or convert(`customers_company` using utf8) regexp '" . $search . "' 
                                        or convert(`customers_street_address` using utf8) regexp '" . $search . "' 
                                        or convert(`customers_suburb` using utf8) regexp '" . $search . "' 
                                        or convert(`customers_city` using utf8) regexp '" . $search . "' 
                                        or convert(`customers_postcode` using utf8) regexp '" . $search . "' 
                                        or convert(`customers_state` using utf8) regexp '" . $search . "' 
                                        or convert(`customers_country` using utf8) regexp '" . $search . "' 
                                        or convert(`customers_email_address` using utf8) regexp '" . $search . "' 
                                        or convert(`customers_telephone` using utf8) regexp '" . $search . "') group by orders_id;");
                                                                          
    $Qorders->bindTable(':table_orders', TABLE_ORDERS);
    $Qorders->bindTable(':table_orders_total', TABLE_ORDERS_TOTAL);
    $Qorders->execute();
    
    while($Qorders->next()) {
      $QorderResults[] = $Qorders->toArray();
    }
      
    // return customer data and create the array
    $Qcustomers = array();    
    $Qcustomers = $lC_Database->query("select customers_id, 
                                              customers_firstname, 
                                              customers_lastname, 
                                              customers_email_address 
                                         from :table_customers 
                                        where (convert(`customers_id` using utf8) regexp '" . $search . "' 
                                           or convert(`customers_firstname` using utf8) regexp '" . $search . "' 
                                           or convert(`customers_lastname` using utf8) regexp '" . $search . "' 
                                           or convert(`customers_email_address` using utf8) regexp '" . $search . "' 
                                           or convert(`customers_telephone` using utf8) regexp '" . $search . "' 
                                           or convert(`customers_fax` using utf8) regexp '" . $search . "');");
                                                                          
    $Qcustomers->bindTable(':table_customers', TABLE_CUSTOMERS);
    $Qcustomers->execute();
    
    while($Qcustomers->next()) {
      $QcustomerResults[] = $Qcustomers->toArray();
    }
    
    // return products data and create the array                                                                          
    
    // build orders <li>
    $result['html'] .= '  <li class="with-right-arrow black-gradient glossy" id="orderSearchResults">' . "\n";
    $result['html'] .= '    <span><span class="list-count">' . $Qorders->numberOfRows() . '</span><span class="icon-price-tag icon-white icon-pad-right"></span> Orders</span>' . "\n";
    $result['html'] .= '    <ul class="calendar-menu">' . "\n";
    
    foreach ($QorderResults as $key => $value) { 
      $result['html'] .= '      <li>' . "\n" . 
                         '        <a href="' . lc_href_link_admin(FILENAME_DEFAULT, 'orders&oID=' . (int)$value['orders_id']) . '">' . "\n" .
                         '          <span class="green float-right"><h4>' . $value['text'] . '</h4></span>' . "\n" . 
                         '          <time class="green" title="Order ID"><b>' . $value['orders_id'] . '</b></time>' . "\n" . 
                         '          ' . $value['customers_name'] . '<small>'  . $value['customers_email_address'] . '</small>' . "\n" . 
                         '        </a>' . "\n" .
                         '      </li>';
    }
    
    $result['html'] .= '    </ul>' . "\n";
    $result['html'] .= '  </li>' . "\n";   
    
    // build customers <li> 
    $result['html'] .= '  <li class="with-right-arrow black-gradient glossy" id="customerSearchResults">' . "\n";
    $result['html'] .= '    <span><span class="list-count">' . $Qcustomers->numberOfRows() . '</span><span class="icon-user icon-white icon-pad-right"></span> Customers</span>' . "\n";
    $result['html'] .= '    <ul class="calendar-menu">' . "\n";
    
    // setup orders count
    foreach ($QcustomerResults as $key => $value) { 
      $result['html'] .= '      <li>' . "\n" . 
                         '        <a href="' . lc_href_link_admin(FILENAME_DEFAULT, 'customers&cID=' . (int)$value['customers_id']) . '">' . "\n" .
                         '          <time class="green" title="[cID]"><b>' . $value['customers_id'] . '</b></time>' . "\n" . 
                         '          ' . $value['customers_firstname'] . ' '  . $value['customers_lastname'] . '<small>'  . $value['customers_email_address'] . '</small>' . "\n" . 
                         '        </a>' . "\n" .
                         '      </li>';
    }
    
    
    $result['html'] .= '    </ul>' . "\n";
    $result['html'] .= '  </li>' . "\n";
    
    // build products <li>    
    
    $result['html'] .= '</ul>' . "\n";
    
    return $result;
  }
}
?>