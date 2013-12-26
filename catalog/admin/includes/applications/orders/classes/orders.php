<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: orders.php v1.0 2013-08-08 datazen $
*/
class lC_Orders_Admin {
 /*
  * Returns the orders datatable data for listings
  *
  * @access public
  * @return array
  */ 
  public static function getAll() { 
    global $lC_Language, $lC_Database, $lC_Currencies, $_module;
    
    $media = $_GET['media'];
    $f_filter_c = '';
    $f_filter   = '';    
    if(isset($_GET['filter']) && $_GET['filter'] != NULL && $_GET['filter'] != '0') {
      $f_filter_c = " where orders_status = '".$_GET['filter']."' ";
      $f_filter = " o.orders_status = '".$_GET['filter']."' ";      
    }
    
    $result = array('aaData' => array());
    
    /* Total Records */
    $QresultTotal = $lC_Database->query('SELECT count(orders_id) as total from :table_orders'.$f_filter_c);
    $QresultTotal->bindTable(':table_orders', TABLE_ORDERS);
    $QresultTotal->execute();         
    $result['iTotalRecords'] = $QresultTotal->valueInt('total');
    $QresultTotal->freeResult();      
    
    /* Paging */
    $sLimit = "";
    if (isset($_GET['iDisplayStart'])) {
      if ($_GET['iDisplayLength'] != -1) {
        $sLimit = " LIMIT " . $_GET['iDisplayStart'] . ", " . $_GET['iDisplayLength'];
      }
    }

    /* Ordering */
    if (isset($_GET['iSortCol_0'])) {
      $sOrder = " ORDER BY ";
      for ($i=0 ; $i < (int)$_GET['iSortingCols'] ; $i++ ) {
        $sOrder .= lC_Orders_Admin::fnColumnToField($_GET['iSortCol_'.$i] ) . " " . $_GET['sSortDir_'.$i] .", ";
      }
      $sOrder = substr_replace( $sOrder, "", -2 );
    }

    /* Filtering */
    $sWhere = "";
    if ($_GET['sSearch'] != "") {
      $sWhere = " WHERE o.customers_id LIKE '%" . $_GET['sSearch'] . "%' OR " .
                       "o.customers_name LIKE '%" . $_GET['sSearch'] . "%' OR " .
                       "o.orders_id LIKE '%" . $_GET['sSearch'] . "%' OR " . 
                       "ot.value LIKE '%" . $_GET['sSearch'] . "%' OR " . 
                       "s.orders_status_name LIKE '%" . $_GET['sSearch'] . "%' ";
    } else if (isset($_GET['cSearch']) && $_GET['cSearch'] != null) {
      $sWhere = " WHERE o.customers_id = '" . $_GET['cSearch'] . "' ";
    } else if (isset($_GET['oSearch']) && $_GET['oSearch'] != null) {
      $sWhere = " WHERE o.orders_id = '" . $_GET['oSearch'] . "' ";
    }
    if($sWhere != '' && $f_filter != '') {      
      $sWhere .= " and ".$f_filter;
    } else if($sWhere == '' && $f_filter != '') {      
      $sWhere .= " WHERE ".$f_filter;
    }

    /* Total Filtered Records */
    $QresultFilterTotal = $lC_Database->query("SELECT count(o.orders_id) as total  
                                                 from :table_orders o 
                                               LEFT JOIN :table_orders_total ot 
                                                 on (o.orders_id = ot.orders_id and ot.class = 'total')
                                               LEFT JOIN :table_orders_status s 
                                                 on (s.orders_status_id = o.orders_status and s.language_id = :language_id) " . 
                                               $sWhere);
    
    $QresultFilterTotal->bindTable(':table_orders', TABLE_ORDERS);
    $QresultFilterTotal->bindTable(':table_orders_total', TABLE_ORDERS_TOTAL);
    $QresultFilterTotal->bindTable(':table_orders_status', TABLE_ORDERS_STATUS);
    $QresultFilterTotal->bindInt(':language_id', $lC_Language->getID());
    $QresultFilterTotal->execute();         
    $result['iTotalDisplayRecords'] = $QresultFilterTotal->valueInt('total'); 
    $QresultFilterTotal->freeResult();      
    
    /* Main Listing Query */
    $Qresult = $lC_Database->query("SELECT o.orders_id, o.customers_id, o.customers_ip_address, o.customers_name, o.payment_method, o.date_purchased, o.last_modified, o.customers_country_iso3, greatest(date_purchased, coalesce(last_modified, date_purchased)) as date_sort, o.currency, o.currency_value, ot.value as order_total, s.orders_status_name, s.orders_status_type     
                                      from :table_orders o 
                                 LEFT JOIN :table_orders_total ot 
                                        on (o.orders_id = ot.orders_id and ot.class = 'total')
                                 LEFT JOIN :table_orders_status s 
                                        on (s.orders_status_id = o.orders_status and s.language_id = :language_id) " . 
                                    $sWhere . $sOrder  . $sLimit); 
                                  
    $Qresult->bindTable(':table_orders', TABLE_ORDERS);
    $Qresult->bindTable(':table_orders_total', TABLE_ORDERS_TOTAL);
    $Qresult->bindTable(':table_orders_products', TABLE_ORDERS_PRODUCTS);
    $Qresult->bindTable(':table_orders_status', TABLE_ORDERS_STATUS);
    $Qresult->bindInt(':language_id', $lC_Language->getID());
    $Qresult->execute();   
      
    while ($Qresult->next()) { 
      $check = '<td><input class="batch" type="checkbox" name="batch[]" value="' . $Qresult->valueInt('orders_id') . '" id="' . $Qresult->valueInt('orders_id') . '"></td>';
      $oid = '<td><a href="' . ((int)($_SESSION['admin']['access'][$_module] < 3) ? '#' : lc_href_link_admin(FILENAME_DEFAULT, $_module . '=' . $Qresult->valueInt('orders_id') . '&action=save')) . '"><span class="icon-price-tag icon-red"></span>&nbsp;' . $Qresult->valueInt('orders_id') . '</a></td>';
      $name = '<td>' . $Qresult->valueProtected('customers_name') . '</td>';
      $country = '<td>' . $Qresult->valueProtected('customers_country_iso3') . '</td>';
      
      $Qresult_items = $lC_Database->query("SELECT sum(products_quantity) as items from :table_orders_products where orders_id = '".$Qresult->valueInt('orders_id')."' ");
      $Qresult_items->bindTable(':table_orders_products', TABLE_ORDERS_PRODUCTS);
      $Qresult_items->execute();
      $items = '<td>' . $Qresult_items->valueInt('items') . '</td>';
      
      $total = '<td>' . $lC_Currencies->format($Qresult->value('order_total')) . '</td>';
      number_format($Qresult->value('order_total'), DECIMAL_PLACES) . '</td>';
      $date = '<td>' . self::getTextDate($Qresult->value('date_purchased')) . '</td>';
      $time = '<td>' . self::get12HourTime($Qresult->value('date_purchased')) . '</td>';
      $status = '<td><span class="tag with-min-padding ' . (($Qresult->valueProtected('orders_status_type') == 'Approved') ? 'green-bg' : (($Qresult->valueProtected('orders_status_type') == 'Pending') ? 'orange-bg' : 'red-bg')) . '">' . $Qresult->valueProtected('orders_status_name') . '</span></td>'; 
      $action = '<td class="align-right vertical-center"><span class="button-group compact">
                   <a href="' . ((int)($_SESSION['admin']['access'][$_module] < 3) ? '#' : lc_href_link_admin(FILENAME_DEFAULT, $_module . '=' . $Qresult->valueInt('orders_id') . '&action=save')) . '" class="button icon-pencil' . ((int)($_SESSION['admin']['access'][$_module] < 3) ? ' disabled' : NULL) . '">' . (($media === 'mobile-portrait' || $media === 'mobile-landscape') ? NULL : $lC_Language->get('icon_edit')) . '</a>
                   <!--<a href="' . ((int)($_SESSION['admin']['access'][$_module] < 4) ? '#' : 'javascript://" onclick="editOrder(\'' . $Qresult->valueInt('orders_id') . '\')') . '" class="button icon-pencil with-tooltip' . ((int)($_SESSION['admin']['access'][$_module] < 3) ? ' disabled' : NULL) . '" title="' . $lC_Language->get('icon_edit') . '"></a>-->
                   <a href="' . ((int)($_SESSION['admin']['access'][$_module] < 4) ? '#' : 'javascript://" onclick="deleteOrder(\'' . $Qresult->valueInt('orders_id') . '\', \'' . urlencode($Qresult->value('customers_name')) . '\')"') . '" class="button icon-trash with-tooltip' . ((int)($_SESSION['admin']['access'][$_module] < 4) ? ' disabled' : NULL) . '" title="' . $lC_Language->get('icon_delete') . '"></a>
                 </span></td>';         

      $result['aaData'][] = array("$check", "$oid", "$name", "$country", "$items", "$total", "$date", "$time", "$status", "$action");      
    }
    $result['sEcho'] = intval($_GET['sEcho']);

    $Qresult->freeResult(); 

    return $result;
  }   
 /*
  * Delete the order record
  *
  * @param integer $id The order id to delete
  * @param boolean $restock True = restock product inventory
  * @access public
  * @return boolean
  */  
  public static function delete($id, $restock = false) {
    global $lC_Database;

    $error = false;

    $lC_Database->startTransaction();

    if ($restock === true) {
      $Qproducts = $lC_Database->query('select products_id, products_quantity from :table_orders_products where orders_id = :orders_id');
      $Qproducts->bindTable(':table_orders_products', TABLE_ORDERS_PRODUCTS);
      $Qproducts->bindInt(':orders_id', $id);
      $Qproducts->execute();

      while ($Qproducts->next()) {
        $Qupdate = $lC_Database->query('update :table_products set products_quantity = products_quantity + :products_quantity, products_ordered = products_ordered - :products_ordered where products_id = :products_id');
        $Qupdate->bindTable(':table_products', TABLE_PRODUCTS);
        $Qupdate->bindInt(':products_quantity', $Qproducts->valueInt('products_quantity'));
        $Qupdate->bindInt(':products_ordered', $Qproducts->valueInt('products_quantity'));
        $Qupdate->bindInt(':products_id', $Qproducts->valueInt('products_id'));
        $Qupdate->setLogging($_SESSION['module'], $id);
        $Qupdate->execute();

        if ($lC_Database->isError() === true) {
          $error = true;
          break;
        }

        $Qcheck = $lC_Database->query('select products_quantity from :table_products where products_id = :products_id and products_Status = 0');
        $Qcheck->bindTable(':table_products', TABLE_PRODUCTS);
        $Qcheck->bindInt(':products_id', $Qproducts->valueInt('products_id'));
        $Qcheck->execute();

        if (($Qcheck->numberOfRows() === 1) && ($Qcheck->valueInt('products_quantity') > 0)) {
          $Qstatus = $lC_Database->query('update :table_products set products_status = 1 where products_id = :products_id');
          $Qstatus->bindTable(':table_products', TABLE_PRODUCTS);
          $Qstatus->bindInt(':products_id', $Qproducts->valueInt('products_id'));
          $Qstatus->setLogging($_SESSION['module'], $id);
          $Qstatus->execute();

          if ($lC_Database->isError() === true) {
            $error = true;
            break;
          }
        }
      }
    }

    if ($error === false) {
      $Qvariants = $lC_Database->query('delete from :table_orders_products_variants where orders_id = :orders_id');
      $Qvariants->bindTable(':table_orders_products_variants', TABLE_ORDERS_PRODUCTS_VARIANTS);
      $Qvariants->bindInt(':orders_id', $id);
      $Qvariants->setLogging($_SESSION['module'], $id);
      $Qvariants->execute();

      if ($lC_Database->isError() === true) {
        $error = true;
      }
    }

    if ($error === false) {
      $Qop = $lC_Database->query('delete from :table_orders_products where orders_id = :orders_id');
      $Qop->bindTable(':table_orders_products', TABLE_ORDERS_PRODUCTS);
      $Qop->bindInt(':orders_id', $id);
      $Qop->setLogging($_SESSION['module'], $id);
      $Qop->execute();

      if ($lC_Database->isError() === true) {
        $error = true;
      }
    }

    if ($error === false) {
      $Qosh = $lC_Database->query('delete from :table_orders_transactions_history where orders_id = :orders_id');
      $Qosh->bindTable(':table_orders_transactions_history', TABLE_ORDERS_TRANSACTIONS_HISTORY);
      $Qosh->bindInt(':orders_id', $id);
      $Qosh->setLogging($_SESSION['module'], $id);
      $Qosh->execute();

      if ($lC_Database->isError() === true) {
        $error = true;
      }
    }

    if ($error === false) {
      $Qosh = $lC_Database->query('delete from :table_orders_status_history where orders_id = :orders_id');
      $Qosh->bindTable(':table_orders_status_history', TABLE_ORDERS_STATUS_HISTORY);
      $Qosh->bindInt(':orders_id', $id);
      $Qosh->setLogging($_SESSION['module'], $id);
      $Qosh->execute();

      if ($lC_Database->isError() === true) {
        $error = true;
      }
    }

    if ($error === false) {
      $Qot = $lC_Database->query('delete from :table_orders_total where orders_id = :orders_id');
      $Qot->bindTable(':table_orders_total', TABLE_ORDERS_TOTAL);
      $Qot->bindInt(':orders_id', $id);
      $Qot->setLogging($_SESSION['module'], $id);
      $Qot->execute();

      if ($lC_Database->isError() === true) {
        $error = true;
      }
    }

    if ($error === false) {
      $Qo = $lC_Database->query('delete from :table_orders where orders_id = :orders_id');
      $Qo->bindTable(':table_orders', TABLE_ORDERS);
      $Qo->bindInt(':orders_id', $id);
      $Qo->setLogging($_SESSION['module'], $id);
      $Qo->execute();

      if ($lC_Database->isError() === true) {
        $error = true;
      }
    }

    if ($error === false) {
      $lC_Database->commitTransaction();

      return true;
    } else {
      $lC_Database->rollbackTransaction();

      return false;
    }
  }
 /*
  * Batch delete order records
  *
  * @param array $batch The order id's to delete
  * @param boolean $restock True = restock product quantity
  * @access public
  * @return boolean
  */  
  public static function batchDelete($batch, $restock) {
    foreach ( $batch as $id ) {
      lC_Orders_Admin::delete($id, $restock);
    }
    return true;
  }    
 /*
  * Return the order info used on the batch delete dialog form
  *
  * @param array $_GET['batch'] The order id's
  * @access public
  * @return array
  */ 
  public static function batchInfo() {
    global $lC_Database;
    
    $Qorders = $lC_Database->query('select orders_id, customers_name from :table_orders where orders_id in (":orders_id") order by orders_id');
    $Qorders->bindTable(':table_orders', TABLE_ORDERS);
    $Qorders->bindRaw(':orders_id', implode('", "', array_unique(array_filter(array_slice($_GET['batch'], 0, MAX_DISPLAY_SEARCH_RESULTS), 'is_numeric'))));
    $Qorders->execute();
    $names_string = '';
    while ( $Qorders->next() ) {
      $names_string .= '<b>#' . $Qorders->valueInt('orders_id') . ': ' . $Qorders->valueProtected('customers_name') . '</b>, ';
    }
    if ( !empty($names_string) ) {
      $names_string = substr($names_string, 0, -2);
    }
    
    return array('info' => $names_string);    
  }
 /*
  * Return the order information
  *
  * @param array $id The order id
  * @access public
  * @return array
  */ 
  public static function getInfo($id) {
    global $lC_Language, $lC_Database, $lC_Vqmod;

    $lC_Language->loadIniFile('orders.php');

    require_once($lC_Vqmod->modCheck('../includes/classes/currencies.php'));
    $lC_Currencies = new lC_Currencies();
    require_once($lC_Vqmod->modCheck('includes/classes/tax.php'));
    $lC_Tax = new lC_Tax_Admin();
    require_once($lC_Vqmod->modCheck('includes/classes/order.php'));
    $lC_Order = new lC_Order($id);

    if ( !$lC_Order->isValid() ) {
      return array('error' => true, 'errmsg' => sprintf(ERROR_ORDER_DOES_NOT_EXIST, $id));
    }
    
   
    $result['customerId'] = $lC_Order->getCustomer('id');
    $result['customerAddress'] = lC_Address::format($lC_Order->getCustomer(), '<br />');
    $result['deliveryAddress'] = lC_Address::format($lC_Order->getDelivery(), '<br />');
    $result['billingAddress'] = lC_Address::format($lC_Order->getBilling(), '<br />');
    $result['paymentMethod'] = '<span>' . $lC_Order->getPaymentMethod() . '</span>';
        
    if ($lC_Order->isValidCreditCard()) {
      $result['paymentMethod'] .= '<table border="0" cellspacing="0" cellpadding="0">
                                     <tr>
                                       <td style="padding-left:15px;">' . $lC_Language->get('credit_card_type') . '</td>
                                       <td>' . $lC_Order->getCreditCardDetails('type') . '</td>
                                     </tr>
                                     <tr>
                                       <td style="padding-left:15px;>' . $lC_Language->get('credit_card_owner_name') . '</td>
                                       <td>' . $lC_Order->getCreditCardDetails('owner') . '</td>
                                     </tr>
                                     <tr>
                                       <td style="padding-left:15px;>' . $lC_Language->get('credit_card_number') . '</td>
                                       <td>' . $lC_Order->getCreditCardDetails('number') . '</td>
                                     </tr>
                                     <tr>
                                       <td style="padding-left:15px;>' . $lC_Language->get('credit_card_expiry_date') . '</td>
                                       <td>' . $lC_Order->getCreditCardDetails('expires') . '</td>
                                     </tr>
                                   </table>';
    }
    $result['orderTelephone'] = '<span>' . $lC_Order->getCustomer('telephone') . '</span>';
    $result['orderEmail'] = '<span>' . $lC_Order->getCustomer('email_address') . '</span>';
    $result['orderStatus'] = '<span>' . $lC_Order->getStatus() . '<br />' . ($lC_Order->getDateLastModified() > $lC_Order->getDateCreated() ? lC_DateTime::getShort($lC_Order->getDateLastModified(), true) : lC_DateTime::getShort($lC_Order->getDateCreated(), true)) . '</span>';
    $result['orderComments'] = '<span>' . $lC_Language->get('number_of_comments') . ' ' . $lC_Order->getNumberOfComments() . '</span>';
    $result['orderTotal'] = '<span>' . $lC_Order->getTotal() . '</span>';
    $result['numberProducts'] = '<span>' . $lC_Language->get('number_of_products') . ' ' . $lC_Order->getNumberOfProducts() . '<br />' . $lC_Language->get('number_of_items') . ' ' . $lC_Order->getNumberOfItems . '</span>';
    // build the product string  
    $result['orderProducts'] = ''; 
    foreach ( $lC_Order->getProducts() as $products ) {
      $result['orderProducts'] .= '<tr class="bbottom-grey">
                                     <td valign="top" align="left" class="orders-products-listing-td hide-below-480">' . $products['model'] . '</td>
                                     <td valign="top" align="left" class="orders-products-listing-td">' . $products['name'];
      if ( isset($products['attributes']) && is_array($products['attributes']) && ( sizeof($products['attributes']) > 0 ) ) {
        foreach ( $products['attributes'] as $attributes ) {
          $result['orderProducts'] .= '<br /><nobr>&nbsp;&nbsp;- <span style="font-size:.9em;"><i>' . $attributes['option'] . ': ' . $attributes['value'] . '</i></span></nobr>';
        }
      }
      
      if ( isset($products['options']) && is_array($products['options']) && ( sizeof($products['options']) > 0 ) ) {
        foreach ( $products['options'] as $key => $val ) {
          $result['orderProducts'] .= '<br /><nobr>&nbsp;&nbsp;- <span class="small"><i>' . $val['group_title'] . ': ' . $val['value_title'] . '</i></span></nobr>';
        }
      }
            
      $result['orderProducts'] .= '  </td>
                                     <td valign="top" align="right" class="orders-products-listing-td hide-below-480">' . $products['quantity'] . '</td>
                                     <td valign="top" align="right" class="orders-products-listing-td hide-below-480">' . $lC_Currencies->format($products['price'] * $products['quantity'], $lC_Order->getCurrency(), $lC_Order->getCurrencyValue()) . '</td>
                                     <td align="right" class="orders-products-listing-td show-below-480"><i title="Product Details" class="icon-info-round icon-blue mid-margin-right cursor-pointer" onclick="orderProductDetails(\'' . $id . '\', \'' . $products['products_id'] . '\');"></i></td>
                                     <!-- hidden for now 
                                     <td valign="top" align="right">' . $lC_Tax->displayTaxRateValue($products['tax']) . '</td>
                                     <td valign="top" align="right">' . $lC_Currencies->format($products['price'], $lC_Order->getCurrency(), $lC_Order->getCurrencyValue()) . '</td>
                                     <td valign="top" align="right">' . $lC_Currencies->displayPriceWithTaxRate($products['price'], $products['tax'], 1, true, $lC_Order->getCurrency(), $lC_Order->getCurrencyValue()) . '</td>
                                     <td valign="top" align="right">' . $lC_Currencies->displayPriceWithTaxRate($products['price'], $products['tax'], $products['quantity'], true, $lC_Order->getCurrency(), $lC_Order->getCurrencyValue()) . '</td>
                                     -->
                                   </tr>';
    }
    // build the order totals string
    $result['orderTotals'] = '';
    // enhanced order admin additions
    $result['orderTotalsData'] = '';
    $otcnt = 0; 
    foreach ( $lC_Order->getTotals() as $totals ) {
      if ($totals['class'] == 'sub_total') {
        $result['orderSubTotal'] = '<span>' . $totals['text'] . '</span>';
      }
      $result['orderTotals'] .= '<tr><td align="right" class="small-padding-bottom' . (($totals['class'] == 'total') ? ' bolder btop-anthracite small-padding-top' : null) . '">' . (($totals['class'] == 'total') ? $totals['title'] = $lC_Language->get('text_grand_total') : $totals['title']) . '</td><td align="right" width="100px" class="small-padding-bottom' . (($totals['class'] == 'total') ? ' bolder btop-anthracite small-padding-top' : null) . '">' . $totals['text'] . '</td></tr>';
      // enhanced order admin additions
      $result['orderTotalsData'][$otcnt]['title'] = $totals['title'];
      $result['orderTotalsData'][$otcnt]['text'] = $totals['text'];
      $otcnt++;
    }
    // build the transaction history string
    $result['transactionHistory'] = ''; 
    // enhanced order admin additions
    $result['transactionHistoryData'] = ''; 
    $thcnt = 0;
    foreach ( $lC_Order->getTransactionHistory() as $history ) {
      $result['transactionHistory'] .= '<tr>
                                          <td valign="top">' . lC_DateTime::getShort($history['date_added'], true) . '</td>
                                          <td valign="top">' . ((!empty($history['status'])) ? $history['status'] : $history['status_id']) . '</td>
                                          <td align="center" valign="top"><span class="' . (($history['return_status'] === 1) ? 'icon-tick icon-green' : 'icon-cross icon-red') . '"><span></td>
                                          <td valign="top">' . nl2br($history['return_value']) . '</td>
                                        </tr>';
      // enhanced order admin additions
      $result['transactionHistoryData'][$thcnt]['date_added'] = lC_DateTime::getShort($history['date_added'], true);
      $result['transactionHistoryData'][$thcnt]['status'] = ((!empty($history['status'])) ? $history['status'] : $history['status_id']);
      $result['transactionHistoryData'][$thcnt]['return_status'] = '<span class="' . (($history['return_status'] === 1) ? 'icon-tick icon-green' : 'icon-cross icon-red') . '"><span>';
      $result['transactionHistoryData'][$thcnt]['return_value'] = nl2br($history['return_value']);
      $thcnt++;
    }
    $postTransactionActions = array(); 
    if ($lC_Order->hasPostTransactionActions()) {
      $postTransactionActions = $lC_Order->getPostTransactionActions();  
      // convert the array
      $actions = array();
      foreach($postTransactionActions as $value) {
        $actions[$value['id']] = $value['text'];
      }
      $result['transactionActions'] = $actions;
    }
    // build the order status history string
    $result['orderStatusHistory'] = '';
    // enhanced order admin additions
    $result['orderStatusHistoryData'] = '';
    $oshcnt = 0; 
    foreach ( $lC_Order->getStatusHistory() as $status_history ) {
      $result['orderStatusHistory'] .= '<tr>
                                          <td align="left" valign="top">' . lC_DateTime::getShort($status_history['date_added'], true) . '</td>
                                          <td align="left" valign="top">' . $status_history['status'] . '</td>
                                          <td align="left" valign="top">' . nl2br($status_history['comment']) . '</td>
                                          <td align="center" valign="top"><span class="' . (($status_history['customer_notified'] === 1) ? 'icon-tick icon-green' : 'icon-cross icon-red') . '"><span></td>
                                        </tr>';
      // enhanced order admin additions
      $result['orderStatusHistoryData'][$oshcnt]['date_added'] = lC_DateTime::getShort($status_history['date_added'], false);
      $result['orderStatusHistoryData'][$oshcnt]['status'] = $status_history['status'];
      $result['orderStatusHistoryData'][$oshcnt]['comment'] = nl2br($status_history['comment']);
      $result['orderStatusHistoryData'][$oshcnt]['customer_notified'] = $status_history['customer_notified'];
      $result['orderStatusHistoryData'][$oshcnt]['admin_name'] = $status_history['admin_name'];
      $result['orderStatusHistoryData'][$oshcnt]['admin_image'] = $status_history['admin_image'];
      $result['orderStatusHistoryData'][$oshcnt]['admin_id'] = $status_history['admin_id'];
      $result['orderStatusHistoryData'][$oshcnt]['append_comment'] = $status_history['append_comment'];
      $oshcnt++;
    }
    // build the order status array
    $orders_status_array = array();

    $Qstatuses = $lC_Database->query('select orders_status_id, orders_status_name from :table_orders_status where language_id = :language_id');
    $Qstatuses->bindTable(':table_orders_status', TABLE_ORDERS_STATUS);
    $Qstatuses->bindInt(':language_id', $lC_Language->getID());
    $Qstatuses->execute();

    while ($Qstatuses->next()) {
      $orders_status_array[$Qstatuses->valueInt('orders_status_id')] = $Qstatuses->value('orders_status_name');
    }
    $result['orderStatusID'] = $lC_Order->getStatusID();
    $result['ordersStatusArray'] = $orders_status_array; 

    $Qstatuses->freeResult;
    
    $Qstatustype = $lC_Database->query('select orders_status_type from :table_orders_status where orders_status_id = :orders_status_id');
    $Qstatustype->bindTable(':table_orders_status', TABLE_ORDERS_STATUS);
    $Qstatustype->bindInt(':orders_status_id', $lC_Order->getStatusID());
    $Qstatustype->execute();     
    $result['ordersStatusType'] = $Qstatustype->value('orders_status_type');    
    $Qstatuses->freeResult;  
    
    return $result;
  }
 /*
  * Update the order status
  *
  * @param integer $oid The order id
  * @param array $data The order information
  * @access public
  * @return boolean
  */ 
  public static function updateStatus($oid, $data) {
    $data = array('status_id' => $data['status'],
                  'comment' => $data['comment'],
                  'notify_customer' => $data['notify_customer'],
                  'append_comment' => $data['append_comment']);

    $result = lC_Orders_Admin::_updateStatus($oid, $data);
    if ($result === false) {
      return false;
    } else {
      return $result;
    }
  }
 /*
  * Execute a post transaction
  *
  * @param integer $oid The order id
  * @param string $transaction The payment module call function
  * @access public
  * @return boolean
  */ 
  public static function doPostTransaction($oid, $transaction) {
    $result = lC_Orders_Admin::_updateTransaction($oid, $transaction);

    return $result;
  }
 /*
  * Execute a post transaction
  *
  * @param integer $id The order id
  * @param string $call_function The payment module call function
  * @access private
  * @return boolean
  */ 
  private static function _updateTransaction($id, $call_function) {
    global $lC_Database, $lC_Vqmod;

    $Qorder = $lC_Database->query('select payment_module from :table_orders where orders_id = :orders_id limit 1');
    $Qorder->bindTable(':table_orders', TABLE_ORDERS);
    $Qorder->bindInt(':orders_id', $id);
    $Qorder->execute();

    if ( ( $Qorder->numberOfRows() === 1) && !lc_empty($Qorder->value('payment_module')) ) {
      if ( file_exists('includes/modules/payment/' . $Qorder->value('payment_module') . '.php') ) {
        include($lC_Vqmod->modCheck('includes/classes/payment.php'));
        include($lC_Vqmod->modCheck('includes/modules/payment/' . $Qorder->value('payment_module') . '.php'));
        if ( is_callable(array('lC_Payment_' . $Qorder->value('payment_module'), $call_function)) ) {
          $payment_module = 'lC_Payment_' . $Qorder->value('payment_module');
          $payment_module = new $payment_module();
          $payment_module->$call_function($id);
          // the following static call won't work due to using $this->_gateway_url in the class method
          // call_user_func(array('lC_Payment_' . $Qorder->value('payment_module'), $call_function), $id);
          return true;
        }
      }
    }
    return false;
  }
 /*
  * Update the order status
  *
  * @param integer $id The order id
  * @param array $data The order information
  * @access private
  * @return boolean
  */
  private static function _updateStatus($id, $data) {
    global $lC_Database, $lC_Language, $lC_Vqmod;

    $lC_Language->loadIniFile('orders.php');

    // build the order status array
    require_once($lC_Vqmod->modCheck('includes/classes/order.php'));
    $lC_Order = new lC_Order($id);

    $orders_status_array = array();

    $Qstatuses = $lC_Database->query('select orders_status_id, orders_status_name from :table_orders_status where language_id = :language_id');
    $Qstatuses->bindTable(':table_orders_status', TABLE_ORDERS_STATUS);
    $Qstatuses->bindInt(':language_id', $lC_Language->getID());
    $Qstatuses->execute();

    while ($Qstatuses->next()) {
      $orders_status_array[$Qstatuses->valueInt('orders_status_id')] = $Qstatuses->value('orders_status_name');
    }

    // update order status
    $error = false;

    $lC_Database->startTransaction();

    $Qorder = $lC_Database->query('select customers_name, customers_email_address, orders_status, date_purchased from :table_orders where orders_id = :orders_id');
    $Qorder->bindTable(':table_orders', TABLE_ORDERS);
    $Qorder->bindInt(':orders_id', $id);
    $Qorder->execute();

    $Qupdate = $lC_Database->query('update :table_orders set orders_status = :orders_status, last_modified = now() where orders_id = :orders_id');
    $Qupdate->bindTable(':table_orders', TABLE_ORDERS);
    $Qupdate->bindInt(':orders_status', $data['status_id']);
    $Qupdate->bindInt(':orders_id', $id);
    $Qupdate->setLogging($_SESSION['module'], $id);
    $Qupdate->execute();

    if ( !$lC_Database->isError() ) {
      if ( $data['notify_customer'] === true ) {
        $email_body = sprintf($lC_Language->get('email_body'), STORE_NAME) . "\n" . $lC_Language->get('email_underline') . "\n";
        $email_body .= sprintf($lC_Language->get('email_order_number'), $id) . "\n";
        $email_body .= sprintf($lC_Language->get('email_detailed_invoice'), lc_href_link(FILENAME_CATALOG_ACCOUNT, 'receipt=' . $id, 'SSL', false, false, true)) . "\n";
        $email_body .= sprintf($lC_Language->get('email_date_ordered'), lC_DateTime::getLong($Qorder->value('date_purchased'))) . "\n\n";
        $email_body .= sprintf($lC_Language->get('email_order_status'), $orders_status_array[$data['status_id']]) . "\n\n";

        if ( $data['append_comment'] === true ) {
          $email_body .= sprintf($lC_Language->get('email_order_comment'), $data['comment']) . "\n" . $lC_Language->get('email_underline') . "\n\n";
        }
        $email_body .= $lC_Language->get('email_body_contact');

        lc_email($Qorder->value('customers_name'), $Qorder->value('customers_email_address'), sprintf($lC_Language->get('email_subject'), STORE_NAME), $email_body, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
      }

      $Qupdate = $lC_Database->query('insert into :table_orders_status_history (orders_id, orders_status_id, date_added, customer_notified, comments, administrators_id, append_comment) values (:orders_id, :orders_status_id, now(), :customer_notified, :comments, :administrators_id, :append_comment)');
      $Qupdate->bindTable(':table_orders_status_history', TABLE_ORDERS_STATUS_HISTORY);
      $Qupdate->bindInt(':orders_id', $id);
      $Qupdate->bindInt(':orders_status_id', $data['status_id']);
      $Qupdate->bindInt(':customer_notified', ( $data['notify_customer'] === true ? '1' : '0'));
      $Qupdate->bindValue(':comments', $data['comment']);
      $Qupdate->bindInt(':administrators_id', $_SESSION['admin']['id']);
      $Qupdate->bindInt(':append_comment', $data['append_comment']);
      $Qupdate->setLogging($_SESSION['module'], $id);
      $Qupdate->execute();

      if ( $lC_Database->isError() ) {
        $error = true;
      }
    } else {
      $error = true;
    }
    if ( $error === false ) {
      $lC_Database->commitTransaction();
      // build and return the udpated status history
      $history = '';
      $result = array();
      foreach ( $lC_Order->getStatusHistory() as $status_history ) {
        $history .= '<tr>
                       <td align="left" valign="top">' . lC_DateTime::getShort($status_history['date_added'], true) . '</td>
                       <td align="left" valign="top">' . $status_history['status'] . '</td>
                       <td align="left" valign="top">' . nl2br($status_history['comment']) . '</td>
                       <td align="center" valign="top"><span class="' . (($status_history['customer_notified'] === 1) ? 'icon-tick icon-green' : 'icon-cross icon-red') . '"><span></td>
                     </tr>';
      }
      $result['orderStatusHistory'] = $history;
      return $result;
    }

    $lC_Database->rollbackTransaction();

    return false;
  }
 /*
  * Return the datatable sort column 
  *
  * @param integer $i The column id
  * @access private
  * @return string
  */ 
  private static function fnColumnToField($i) {
   if ( $i == 1 )
    return "o.orders_id";
   else if ( $i == 2 )
    return "o.customers_name";
   else if ( $i == 3 )
    return "o.customers_id";
   else if ( $i == 4 )
    return "ot.value";
   else if ( $i == 5 )
    return "o.date_purchased"; 
   else if ( $i == 6 )
    return "s.orders_status_name";      
  }
 /*
  * Return the orders comments
  *
  * @access public
  * @return array
  */ 
  public static function getOrderComments($id = null) {
    global $lC_Language;
    $data = lC_Orders_Admin::getInfo($id);
    foreach ($data['orderStatusHistoryData'] as $oshData) {
      if ($oshData['comment'] != '') {
        $ocData .= '<div class="with-small-padding bbottom-anthracite' . (($oshData['admin_id'] == null) ? ' silver-bg' : (($oshData['append_comment'] == 1) ? '' : ' grey-bg')) . '">
                      <div class="small-margin-top">
                        <span class="float-right with-min-padding small-margin-right' . (($oshData['admin_id'] == null) ? ' green-bg' : (($oshData['append_comment'] == 1) ? ' orange-bg' : ' anthracite-bg')) . '">' . (($oshData['admin_id'] == null) ? $lC_Language->get('text_order_comment') : (($oshData['append_comment'] == 1) ? $lC_Language->get('text_customer_message') : $lC_Language->get('text_admin_note'))) . '</span>
                        <span class="small-margin-left float-left">
                          ' . (($oshData['admin_image'] != '' && file_exists('images/avatar/' . $oshData['admin_image'])) ? '<img src="images/avatar/' . $oshData['admin_image'] . '" width="24" title="Status Update by ' . $oshData['admin_name'] . '" alt="Comment by ' . $oshData['admin_name'] . '" />' : '<span class="icon-user icon-size2 icon-anthracite small-margin-left small-margin-right" title="Status Update by ' . $oshData['admin_name'] . '"></span>') . '
                        </span>
                        <span class="anthracite mid-margin-left">' . (($oshData['admin_id'] != null) ? $oshData['admin_name'] : $lC_Language->get('text_customer_comment')) . '</span><small class="anthracite small-margin-left">' . $oshData['date_added'] . '</small><span class="anthracite mid-margin-left">(' . $oshData['status'] . ')</span>
                      </div>
                      <p class="with-small-padding margin-left-order-comments">' . $oshData['comment'] . '</p>
                    </div>';
      }
    }
    return $ocData;
  }
 /*
  * Return the orders status history
  *
  * @access public
  * @return array
  */ 
  public static function getOrderStatusHistory($id = null) {
    global $lC_Language;
    $data = lC_Orders_Admin::getInfo($id);
    foreach ($data['orderStatusHistoryData'] as $oshData) {
      $osHistory .= '<div class="with-small-padding bbottom-anthracite' . (($oshData['admin_id'] == null) ? ' silver-bg' : (($oshData['append_comment'] == 1) ? '' : ' grey-bg')) . '">
                      <div class="small-margin-top">
                        <span class="float-right with-min-padding small-margin-right' . (($oshData['admin_id'] == null) ? ' green-bg' : (($oshData['append_comment'] == 1) ? ' orange-bg' : ' anthracite-bg')) . '">' . (($oshData['admin_id'] == null) ? $lC_Language->get('text_order_comment') : (($oshData['append_comment'] == 1) ? $lC_Language->get('text_customer_message') : $lC_Language->get('text_admin_note'))) . '</span>
                        <span class="small-margin-left float-left">
                          ' . (($oshData['admin_image'] != '' && file_exists('images/avatar/' . $oshData['admin_image'])) ? '<img src="images/avatar/' . $oshData['admin_image'] . '" width="24" title="Status Update by ' . $oshData['admin_name'] . '" alt="Comment by ' . $oshData['admin_name'] . '" />' : '<span class="icon-user icon-size2 icon-anthracite small-margin-left small-margin-right" title="Status Update by ' . $oshData['admin_name'] . '"></span>') . '
                        </span>
                        <span class="anthracite mid-margin-left">' . $oshData['admin_name'] . '</span><small class="anthracite small-margin-left">' . $oshData['date_added'] . '</small><span class="anthracite mid-margin-left">(' . $oshData['status'] . ')</span>
                      </div>
                      <p class="with-small-padding margin-left-order-comments">' . $oshData['comment'] . '</p>
                    </div>';
    }
    return $osHistory;
  }
 /*
  * Return the order information
  *
  * @param array $id The order id
  * @access public
  * @return array
  */ 
  public static function getProduct($oid = null, $pid = null) {
    global $lC_Language, $lC_Database, $lC_Vqmod;

    $lC_Language->loadIniFile('orders.php');

    require_once($lC_Vqmod->modCheck('../includes/classes/currencies.php'));
    $lC_Currencies = new lC_Currencies();
    require_once($lC_Vqmod->modCheck('includes/classes/tax.php'));
    $lC_Tax = new lC_Tax_Admin();
    require_once($lC_Vqmod->modCheck('includes/classes/order.php'));
    $lC_Order = new lC_Order($oid);

    if ( !$lC_Order->isValid() ) {
      return array('error' => true, 'errmsg' => sprintf(ERROR_ORDER_DOES_NOT_EXIST, $id));
    }
    // build a single product string  
    $result['orderProduct'] = ''; 
    foreach ( $lC_Order->getProduct($oid, $pid) as $product ) {
      $result['orderProduct'] .= '<div class="mid-padding-bottom">
                                    <label class="label small-padding-bottom" for="products_model">Model: </label>
                                    <span id="products_model" class="bolder">' . $product['model'] . '</span>
                                  </div>
                                  <div class="mid-padding-bottom">
                                    <label class="label small-padding-bottom" for="products_name">Name: </label>
                                    <span id="products_name"><span class="bolder">' . $product['name'] . '</span>';
      if ( isset($product['attributes']) && is_array($product['attributes']) && ( sizeof($product['attributes']) > 0 ) ) {
        foreach ( $product['attributes'] as $attributes ) {
          $result['orderProduct'] .= '<br /><nobr>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- <span class="large-margin-left"><i>' . $attributes['option'] . ': ' . $attributes['value'] . '</i></span></nobr>';
        }
      }
      
      if ( isset($product['options']) && is_array($product['options']) && ( sizeof($product['options']) > 0 ) ) {
        foreach ( $product['options'] as $key => $val ) {
          $result['orderProduct'] .= '<br /><nobr>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- <span class="small" class="large-margin-left"><i>' . $val['group_title'] . ': ' . $val['value_title'] . '</i></span></nobr>';
        }
      }
            
      $result['orderProduct'] .= '  </span>
                                  </div>
                                  <div class="mid-padding-bottom">
                                    <label class="label small-padding-bottom" for="products_quantity">Qty: </label>
                                    <span id="products_quantity" class="bolder">' . $product['quantity'] . '</span>
                                  </div>
                                  <div class="mid-padding-bottom">
                                    <label class="label small-padding-bottom" for="products_total">Total: </label>
                                    <span id="products_total" class="bolder">' . $lC_Currencies->format($product['price'] * $product['quantity'], $lC_Order->getCurrency(), $lC_Order->getCurrencyValue()) . '</span>
                                  </div>';
    }
    
    return $result;
  }
 /*
  * Return the orders comments
  *
  * @access public
  * @return array
  */ 
  public static function drawOrderStatusDropdown($oid, $classes = null) {
    $data = lC_Orders_Admin::getInfo($oid);
    
    $osDropdown = '<select class="select withClearFunctions' . ((!empty($classes)) ? ' ' . $classes : null) . '" style="min-width:150px" id="order_statuses" name="status">';
    foreach ($data['ordersStatusArray'] as $id => $val) {
      $osDropdown .= '<option value="' . $id . '"';
      if ($data['orderStatusID'] == $id) {
        $osDropdown .= ' selected="selected"';
      }
      $osDropdown .= '>' . $val . '</option>';
    }
    $osDropdown .= '</select>';
    
    return $osDropdown;
  }
 /*
  * Return the orders address matching results
  *
  * @access public
  * @return string (text define for same or mixed)
  */ 
  public static function getAddressMatching($oid) {
    global $lC_Language;
    
    $data = lC_Orders_Admin::getInfo($oid);
    
    if ($data['customerAddress'] == $data['billingAddress'] && $data['customerAddress'] == $data['deliveryAddress'] && $data['billingAddress'] == $data['deliveryAddress']) {
      $oAddMatch = $lC_Language->get('text_address_same');
    } else {
      $oAddMatch = $lC_Language->get('text_address_mixed');
    }
    
    return $oAddMatch;
  }
 /*
  * Return the orders balance state (paid or due)
  *
  * @access public
  * @return string (text define for paid or due)
  */ 
  public static function getBalanceState($oid) {
    global $lC_Language;
    
    $data = lC_Orders_Admin::getInfo($oid);
    if ($data['ordersStatusType'] == 'Approved') {
      $oBalState = $lC_Language->get('text_balance_paid');
    } else {
      $oBalState = $lC_Language->get('text_balance_due');
    }
    
    return $oBalState;
  }
 /*
  * Return the 12 hour formatted time for the orders listing
  *
  * @access public
  * @return string
  */
  public static function get12HourTime($datetime) {
    $time = substr(lC_DateTime::getShort($datetime, true), -8);
    $parts = explode(":", $time);
    if ($parts[0] > 12) {
      $h = ($parts[0] - 12);
      $ampm = 'pm';
    } else {
      $h = $parts[0];
      $ampm = 'am';
    }
    return $h . ':' . $parts[1] . '<small>&nbsp;</small>' . $ampm;
  }
 /*
  * Return the text/word formatted time for the orders listing
  *
  * @access public
  * @return string
  */
  public static function getTextDate($datetime) {
    $date = substr(lC_DateTime::getShort($datetime, true), 0, -8);
    return @date("M jS Y", @strtotime($date));
  }
 /*
  * Return the orders transaction history
  *
  * @access public
  * @return array
  */ 
  public static function getOrderTransactions($id = null) {
    global $lC_Language;
    $data = lC_Orders_Admin::getInfo($id);
    $cnt = 1;
    
    if ($data['transactionHistoryData'] != null) {
      foreach ($data['transactionHistoryData'] as $thData) {
        $tData .= '<tr>' .
                  '  <td>' . lC_DateTime::getShort($thData['date_added'], false) . '</td>' .
                  '  <td>' . $thData['status'] . '&nbsp;&nbsp;' . $thData['return_status'] . '</td>' .
                  '  <td class="cursor-pointer transCommentsTrigger">More <span class="icon-triangle-down"></span></td>' .
                  '</tr>' . 
                  '<tr style="display:none;">' . 
                  '  <td colspan="3" class="force-text-break">' . $thData['return_value'] . '</td>' . 
                  '</tr>';
        $cnt++;
      }
    }
    return $tData;
  }
  
  public static function getOrderStatusArray() {
    global $lC_Language, $lC_Database;
    
    // build the order status array
    $orders_status_array = array();
    $Qstatuses = $lC_Database->query('select orders_status_id, orders_status_name from :table_orders_status where language_id = :language_id');
    $Qstatuses->bindTable(':table_orders_status', TABLE_ORDERS_STATUS);
    $Qstatuses->bindInt(':language_id', $lC_Language->getID());
    $Qstatuses->execute();
    
    while ($Qstatuses->next()) {
      $orders_status_array[$Qstatuses->valueInt('orders_status_id')] = $Qstatuses->value('orders_status_name');
    }
    
    $orderStatusArray = array();
    $orderStatusArray[] = array('id' => 0, 'text' => $lC_Language->get('text_all'));
    foreach($orders_status_array as $id => $text) {
      $orderStatusArray[] = array('id' => $id, 'text' => $text);
    }
    
    return $orderStatusArray;
  }      
}
?>
