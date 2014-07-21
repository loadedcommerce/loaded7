<?php
/**
  @package    catalog::classes
  @author     Loaded Commerce, LLC
  @copyright  Copyright 2003-2013 Loaded Commerce Development Team
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: product.php v1.0 2013-08-08 datazen $
*/
global $lC_Vqmod;

require_once($lC_Vqmod->modCheck(DIR_FS_CATALOG . 'includes/classes/order.php'));

class lC_Order_b2b extends lC_Order {
 /*
  * Insert the purchase order number
  *
  * @access public
  * @return array
  */
  public static function insertPONumber($id = null) {
    global $lC_Database;

    if(isset($_POST['po_number']) && $_POST['po_number'] != '') {
      $po_number = $_POST['po_number'];
      $Qponumber = $lC_Database->query('insert into :table_po_number (po_number,orders_id) values (:po_number,:orders_id)');
      $Qponumber->bindTable(':table_po_number', DB_TABLE_PREFIX . "purchase_orders_service");
      $Qponumber->bindValue(':po_number', $po_number);
      $Qponumber->bindInt(':orders_id', $id); 
      $Qponumber->execute();
    }
  }
 /*
  * Get the purchase order number email string
  *
  * @access public
  * @return string
  */
  public static function getPONumber($id) {
    global $lC_Language;

    $email_order = $lC_Language->get('text_po_number_title') . "\n" .
                   $lC_Language->get('email_order_separator') . "\n";
    $email_order .=  self::retrievePONumber($id) . "\n\n";

    return $email_order;
  }
 /*
  * Get the purchase order number
  *
  * @access public
  * @return string
  */
  public static function retrievePONumber($id = 0) {
    global $lC_Database, $lC_Customer, $lC_Language;
    
    if( $id > 0) {
      $Qretorders = $lC_Database->query('select po_number_id, po_number from :table_po_number where orders_id = :orders_id ');
      $Qretorders->bindTable(':table_po_number', DB_TABLE_PREFIX . "purchase_orders_service");
      $Qretorders->bindInt(':orders_id', $id);
      $Qretorders->execute();    
      
      $id = $Qretorders->value('po_number');
      
      $Qretorders->freeResult();
      
      return $id;
    }

    return false;

  }
}
?>