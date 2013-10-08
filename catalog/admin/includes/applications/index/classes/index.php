<?php
/*
  $Id: banner_manager.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html

  @function The lC_Administrators_Index class manages newsletters
*/
global $lC_Vqmod;

class lC_Administrators_Index {
 /*
  * Returns the banners datatable data for listings
  *
  * @access public
  * @return array
  */
  public static function get_live_data( $arg ) {
    global $lC_Database;
    switch ( $arg ) {
      case 'Sessions':
        /*$QresultTotal = $lC_Database->query('SELECT DAY( date_account_created ) as day, COUNT( `customers_id` ) as total from :table_sessions where MONTH( date_account_created ) = MONTH( CURRENT_DATE ) AND YEAR( date_account_created ) = YEAR( CURRENT_DATE ) GROUP BY DAY( date_account_created )');
        $QresultTotal->bindTable(':table_sessions', TABLE_SESSIONS);*/
        $result= "150, 173, 104, 129, 146, 176, 139, 149, 218, 194, 196, 153, 173, 104, 129, 146, 176, 139, 149, 218, 194, 196, 153, 173, 104, 129, 146, 176, 139, 149";    
        return $result;
        break;
      case 'Customers':
        $QresultTotal = $lC_Database->query('SELECT DAY( date_account_created ) as day, COUNT( `customers_id` ) as total from :table_customers where MONTH( date_account_created ) = MONTH( CURRENT_DATE ) AND YEAR( date_account_created ) = YEAR( CURRENT_DATE ) GROUP BY DAY( date_account_created )');
        $QresultTotal->bindTable(':table_customers', TABLE_CUSTOMERS);
        break;
      case 'Carts':
        $QresultTotal = $lC_Database->query('SELECT DAY( date_added ) as day, COUNT( `customers_id` ) as total from :table_shopping_carts where MONTH( date_added ) = MONTH( CURRENT_DATE ) AND YEAR( date_added ) = YEAR( CURRENT_DATE ) GROUP BY DAY( date_added )');
        $QresultTotal->bindTable(':table_shopping_carts', TABLE_SHOPPING_CARTS);
        break;
      case 'Orders':
        $QresultTotal = $lC_Database->query('SELECT DAY( date_purchased ) as day, COUNT( `customers_id` ) as total from :table_orders where MONTH( date_purchased ) = MONTH( CURRENT_DATE ) AND YEAR( date_purchased ) = YEAR( CURRENT_DATE ) GROUP BY DAY( date_purchased )');
        $QresultTotal->bindTable(':table_orders', TABLE_ORDERS);
        break;
      default:
        return '';
    }
    $QresultTotal->execute();
  
    $result = "";
    $tmp_result = "";
    if ($QresultTotal->numberOfRows() > 0) {
      while ( $QresultTotal->next() ) {
        $day[] = $QresultTotal->valueInt('day');
        $tmp_result[$QresultTotal->valueInt('day')] = $QresultTotal->valueInt('total');
      }
      $QresultTotal->freeResult();
      for($i = 1 ; $i <= max($day) ;  $i++) {      
        $result .= (int)$tmp_result[$i].',';
      }
      $result = substr($result,0,-1);
    }        
    return $result;
  }

  public static function get_live_data_total( $arg ) {
    global $lC_Database;
    switch ( $arg ) {
      case 'Sessions':
        /*$QresultTotal = $lC_Database->query('SELECT DAY( date_account_created ) as day, COUNT( `customers_id` ) as total from :table_sessions where MONTH( date_account_created ) = MONTH( CURRENT_DATE ) AND YEAR( date_account_created ) = YEAR( CURRENT_DATE ) GROUP BY MONTH( date_account_created )');
        $QresultTotal->bindTable(':table_sessions', TABLE_SESSIONS);*/
        $result= "15";    
        return $result;
        break;
      case 'Customers':
        $QresultTotal = $lC_Database->query('SELECT DAY( date_account_created ) as day, COUNT( `customers_id` ) as total from :table_customers where MONTH( date_account_created ) = MONTH( CURRENT_DATE ) AND YEAR( date_account_created ) = YEAR( CURRENT_DATE ) GROUP BY MONTH( date_account_created )');
        $QresultTotal->bindTable(':table_customers', TABLE_CUSTOMERS);
        break;
      case 'Carts':
        $QresultTotal = $lC_Database->query('SELECT DAY( date_added ) as day, COUNT( `customers_id` ) as total from :table_shopping_carts where MONTH( date_added ) = MONTH( CURRENT_DATE ) AND YEAR( date_added ) = YEAR( CURRENT_DATE ) GROUP BY MONTH( date_added )');
        $QresultTotal->bindTable(':table_shopping_carts', TABLE_SHOPPING_CARTS);
        break;
      case 'Orders':
        $QresultTotal = $lC_Database->query('SELECT DAY( date_purchased ) as day, COUNT( `customers_id` ) as total from :table_orders where MONTH( date_purchased ) = MONTH( CURRENT_DATE ) AND YEAR( date_purchased ) = YEAR( CURRENT_DATE ) GROUP BY MONTH( date_purchased )');
        $QresultTotal->bindTable(':table_orders', TABLE_ORDERS);
        break;
      default:
        return '0';
    }
    $QresultTotal->execute();
    $result = 0;
    if ($QresultTotal->numberOfRows() > 0) {
      $result = $QresultTotal->valueInt('total');
    }        
    return $result;
  }

  public static function get_column_data( $arg = 'month') {
    global $lC_Database;

    switch ( $arg ) {
      case 'year':
        $result = "'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'";
        break;
      case 'month':
          $num = cal_days_in_month(CAL_GREGORIAN, date('n'), date('Y')); 
          for($i = 1; $i <= $num; $i++ ) {
            $result .= "'".$i."',";
          }
          $result = substr($result,0,-1);
          return $result;
        break;
      default:
        $result = "'1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '10', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24', '25', '26', '27', '28', '29', '30', '31'";
        break;

    }
  }


  public static function get_Sessions_data() {

    global $lC_Database;

    /* Total Records */    
    /*$Qsession = $lC_Database->query('SELECT DAY( date_account_created ) as day, COUNT( `customers_id` ) as total from :table_sessions where MONTH( date_account_created ) = MONTH( CURRENT_DATE ) AND YEAR( date_account_created ) = YEAR( CURRENT_DATE ) GROUP BY DAY( date_account_created )');
    $Qsession->bindTable(':table_sessions', TABLE_SESSIONS);
    $Qsession->execute();
    
    $result = "";
    $tmp_result = "";
    while ( $Qsession->next() ) {
      $day[] = $Qsession->valueInt('day');
      $tmp_result[$Qsession->valueInt('day')] = $Qsession->valueInt('total');
    }
    $Qsession->freeResult();
    for($i = 1 ; $i <= max($day) ;  $i++) {      
      $result .= (int)$tmp_result[$i].',';
    }      
    $result = substr($result,0,-1);
    return $result;
**/
    

    $result= "150, 173, 104, 129, 146, 176, 139, 149, 218, 194, 196, 153, 173, 104, 129, 146, 176, 139, 149, 218, 194, 196, 153, 173, 104, 129, 146, 176, 139, 149";    
    return $result;
  }
  public static function get_Customers_data() {
    global $lC_Database;

    /* Total Records */    
    $QresultTotal = $lC_Database->query('SELECT DAY( date_account_created ) as day, COUNT( `customers_id` ) as total from :table_customers where MONTH( date_account_created ) = MONTH( CURRENT_DATE ) AND YEAR( date_account_created ) = YEAR( CURRENT_DATE ) GROUP BY DAY( date_account_created )');
    $QresultTotal->bindTable(':table_customers', TABLE_CUSTOMERS);
    $QresultTotal->execute();
    
    $result = "";
    $tmp_result = "";
    while ( $QresultTotal->next() ) {
      $day[] = $QresultTotal->valueInt('day');
      $tmp_result[$QresultTotal->valueInt('day')] = $QresultTotal->valueInt('total');
    }
    $QresultTotal->freeResult();
    for($i = 1 ; $i <= max($day) ;  $i++) {      
      $result .= (int)$tmp_result[$i].',';
    }      
    $result = substr($result,0,-1);
    return $result;
  }

  public static function get_Carts_data() {

    global $lC_Database;

    /* Total Records */    
    $QresultTotal = $lC_Database->query('SELECT DAY( date_added ) as day, COUNT( `customers_id` ) as total from :table_shopping_carts where MONTH( date_added ) = MONTH( CURRENT_DATE ) AND YEAR( date_added ) = YEAR( CURRENT_DATE ) GROUP BY DAY( date_added )');
    $QresultTotal->bindTable(':table_shopping_carts', TABLE_SHOPPING_CARTS);
    $QresultTotal->execute();
    
    $result = "";
    $tmp_result = "";
    while ( $QresultTotal->next() ) {
      $day[] = $QresultTotal->valueInt('day');
      $tmp_result[$QresultTotal->valueInt('day')] = $QresultTotal->valueInt('total');
    }
    $QresultTotal->freeResult();
    for($i = 1 ; $i <= max($day) ;  $i++) {      
      $result .= (int)$tmp_result[$i].',';
    }      
    $result = substr($result,0,-1);
    return $result;
  }
  public static function get_Orders_data() {

    global $lC_Database;

    /* Total Records */    
    $QresultTotal = $lC_Database->query('SELECT DAY( date_purchased ) as day, COUNT( `customers_id` ) as total from :table_orders where MONTH( date_purchased ) = MONTH( CURRENT_DATE ) AND YEAR( date_purchased ) = YEAR( CURRENT_DATE ) GROUP BY DAY( date_purchased )');
    $QresultTotal->bindTable(':table_orders', TABLE_ORDERS);
    $QresultTotal->execute();
    
    $result = "";
    $tmp_result = "";
    while ( $QresultTotal->next() ) {
      $day[] = $QresultTotal->valueInt('day');
      $tmp_result[$QresultTotal->valueInt('day')] = $QresultTotal->valueInt('total');
    }
    $QresultTotal->freeResult();
    for($i = 1 ; $i <= max($day) ;  $i++) {      
      $result .= (int)$tmp_result[$i].',';
    }      
    $result = substr($result,0,-1);
    return $result;
  } 
}
?>