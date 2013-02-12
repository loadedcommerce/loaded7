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
    global $lC_Database, $lC_Language, $lC_Currencies;
    
    if ($search) {
      // start building the main <ul>
      $result['html'] = '' . "\n";
      
      // return order data
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
                                          or convert(`customers_telephone` using utf8) regexp '" . $search . "') 
                                    group by orders_id;");
                                                                            
      $Qorders->bindTable(':table_orders', TABLE_ORDERS);
      $Qorders->bindTable(':table_orders_total', TABLE_ORDERS_TOTAL);
      $Qorders->execute();
      
      // set the orders data results as an array
      while($Qorders->next()) {
        $QorderResults[] = $Qorders->toArray();
      }
      
      // build the orders results <li> html for output
      // return <li> only if greater than 0 results from orders query 
      if ($QorderResults > 0) {
        $result['html'] .= '    <ul class="title-menu">
                                  <li>Orders</li>
                                </ul>' . "\n";
        $result['html'] .= '    <ul class="orders-menu">' . "\n";
        function unique_id() {
          return substr(md5(uniqid(mt_rand(), true)), 0, 4);
        }        
        foreach ($QorderResults as $key => $value) {
 
          $result['html'] .= '      <li class="bevel" title="' . $lC_Language->get('order_view_details') . ' ' . $value['orders_id'] . '">' . "\n" . 
                             '        <a href="' . lc_href_link_admin(FILENAME_DEFAULT, 'orders&oID=' . $value['orders_id']) . '">' . "\n" .
                             '          <span class="float-right align-right" style="">' . "\n" . 
                             '            <b class="green">' . $value['orders_id'] . '</b><br />' . "\n" . 
                             '            ' . $value['text'] . "\n" . 
                             '          </span>' . "\n" . 
                             '          <span><b class="green">' . strtoupper(unique_id()) . '-' . $value['orders_id'] . '</b></span><small>'  . $value['customers_name'] . '</small>' . "\n" . 
                             '        </a>' . "\n" .
                             '      </li>';
        }
        
        $result['html'] .= '    </ul>' . "\n";
      } else {
        $result['html'] .= '';
      }
        
      // return customer data
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
      
      // set the customers data results as an array
      while($Qcustomers->next()) {
        $QcustomerResults[] = $Qcustomers->toArray();
      }   
       
      // build the customers results <li> html for output
      // return <li> only if greater than 0 results from customers query  
      if ($QcustomerResults > 0) {
        $result['html'] .= '    <ul class="title-menu">
                                  <li>Customers</li>
                                </ul>' . "\n";
        $result['html'] .= '    <ul class="customers-menu">' . "\n";
        foreach ($QcustomerResults as $key => $value) { 
          $result['html'] .= '      <li class="bevel" title="' . $lC_Language->get('customer_view_details') . ' ' . $value['customers_firstname'] . ' '  . $value['customers_lastname'] . '">' . "\n" . 
                             '        <a href="' . lc_href_link_admin(FILENAME_DEFAULT, 'customers&cID=' . $value['customers_id']) . '">' . "\n" .
                             '          <span class="float-right">' . $value['customers_id'] . '</span>' . "\n" . 
                             '          <span class="green"><b>' . $value['customers_firstname'] . ' '  . $value['customers_lastname'] . '</b></span><small>'  . $value['customers_email_address'] . '</small>' . "\n" . 
                             '        </a>' . "\n" .
                             '      </li>';
        }
        
        $result['html'] .= '    </ul>' . "\n";
      } else {
        $result['html'] .= '';
      }
      
      // return products data
      $Qproducts = $lC_Database->query("select p.products_id, 
                                               p.parent_id, 
                                               p.products_price, 
                                               p.products_model, 
                                               p.has_children, 
                                               pd.products_name, 
                                               pd.products_keyword 
                                          from :table_products p  
                                     left join :table_products_description pd 
                                            on (p.products_id = pd.products_id) 
                                         where (convert(`products_name` using utf8) regexp '" . $search . "' 
                                            or convert(`products_model` using utf8) regexp '" . $search . "' 
                                            or convert(`products_keyword` using utf8) regexp '" . $search . "') 
                                      order by pd.products_name;");

      $Qproducts->bindTable(':table_products', TABLE_PRODUCTS);
      $Qproducts->bindTable(':table_products_description', TABLE_PRODUCTS_DESCRIPTION);
      $Qproducts->bindInt(':language_id', $lC_Language->getID());
      $Qproducts->execute();

      // set the products data results as an array
      while ($Qproducts->next()) {
        $QproductResults[] = $Qproducts->toArray();
      }
      
      // build the products results <li> html for output
      // return <li> only if greater than 0 results from products query  
      if ($QproductResults > 0) {
        $result['html'] .= '    <ul class="title-menu">
                                  <li>Products</li>
                                </ul>' . "\n";
        $result['html'] .= '    <ul class="products-menu">' . "\n";
        foreach ($QproductResults as $key => $value) {
          // check for product variants if product has children
          if ($value['has_children'] > 0) {
            $Qvariants = array();
            $QvariantsCount = $lC_Database->query("select count(products_id) as variants from :table_products where parent_id = '" . $value['products_id'] . "'");
            $QvariantsCount->bindTable(':table_products', TABLE_PRODUCTS);
            $QvariantsCount->execute();
            
            // set the variants count results as an array
            while ($QvariantsCount->next()) {
              $Qvariants[] = $QvariantsCount->toArray();
            }
          }
          
          $result['html'] .= '      <li class="bevel" title="' . $lC_Language->get('product_view_details') . ' ' . $value['products_name'] . '">' . "\n" . 
                             '        <a href="' . lc_href_link_admin(FILENAME_DEFAULT, 'products=' . $value['products_id'] . '&action=save') . '">' . "\n" .
                             '          <span class="float-right">' . "\n" . 
                             '            ' . ($value['has_children'] != 0 ? '<span title="This product has ' . $Qvariants[0]['variants'] . ' variants">(' . $Qvariants[0]['variants'] . ') <span class="icon-path"></span></span>' : $lC_Currencies->format($value['products_price'])) . '<br />' . "\n" . 
                             '          </span>' . "\n" . 
                             '          <time><span class="icon-bag icon-size2 icon-grey icon-pad-left"></span></time>' . "\n" . 
                             '          <span class="green" title="' . $value['products_name'] . '"><b>' . substr($value['products_name'], 0, 20) . '</b>...</span>' . ($value['has_children'] != 0 ? '' : '<small>Model: '  . $value['products_model'] . '</small>') . "\n" . 
                             '        </a>' . "\n" .
                             '      </li>';
                             
          $Qvariants = null;
        }
        
        $result['html'] .= '    </ul>' . "\n";
      } else {
        $result['html'] .= '';
      }
       
      return $result;
      
    } else {
      
      // we have nothing being sent from search field 
      $result['html'] = '' . "\n";
      
      return $result;
      
    }
  }
}
?>