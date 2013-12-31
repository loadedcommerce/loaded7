<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: featured_products.php v1.0 2013-08-08 datazen $
*/
class lC_Featured_products_Admin {
 /**
  * Returns the featured products datatable data for listings
  *
  * @access public
  * @return array
  */
  public static function getAll() {
    global $lC_Database, $lC_Language, $lC_Currencies, $_module;

    $media = $_GET['media'];
    
    $Qfeatured = $lC_Database->query('select * from :table_featured_products');
    $Qfeatured->bindTable(':table_featured_products', TABLE_FEATURED_PRODUCTS);
    $Qfeatured->execute();
    
    $result = array('aaData' => array());
    while ( $Qfeatured->next() ) {
      $Qname = $lC_Database->query('select products_name from :table_products_description where products_id = :products_id');
      $Qname->bindTable(':table_products_description', TABLE_PRODUCTS_DESCRIPTION);
      $Qname->bindInt(':products_id', $Qfeatured->value('products_id'));
      $Qname->execute();
            
      $check = '<td><input class="batch" type="checkbox" name="batch[]" value="' . $Qfeatured->valueInt('id') . '" id="' . $Qfeatured->valueInt('id') . '"></td>';
      $name = '<td>' . $Qname->value('products_name') . '</td>';
      $expires = '<td><span' . ((date("Y-m-d H:i:s") > $Qfeatured->value('expires_date') && $Qfeatured->value('expires_date') != '0000-00-00 00:00:00') ? ' class="red bold with-tooltip" title="' . $lC_Language->get('text_featured_product_expired') . '"' : '') . '>' .  ($Qfeatured->value('expires_date') != '0000-00-00 00:00:00' ? lC_DateTime::getShort($Qfeatured->value('expires_date')) : $lC_Language->get('text_featured_product_no_expiration')) . '</span></td>';
      $status = '<td><span id="status_' . $Qfeatured->value('id') . '" onclick="updateStatus(\'' . $Qfeatured->valueInt('id') . '\', \'' . (($Qfeatured->valueInt('status') == 1) ? -1 : 1) . '\');">' . (($Qfeatured->valueInt('status') == 1) ? '<span class="icon-tick icon-size2 icon-green cursor-pointer with-tooltip" title="' . $lC_Language->get('text_disable') . '"></span>' : '<span class="icon-cross icon-size2 icon-red cursor-pointer with-tooltip" title="' . $lC_Language->get('text_enable') . '"></span>') . '</span></td>';
      $action = '<td class="align-right vertical-center"><span class="button-group compact">
                   <a href="' . ((int)($_SESSION['admin']['access'][$_module] < 3) ? '#' : lc_href_link_admin(FILENAME_DEFAULT, $_module . '=' . $Qfeatured->valueInt('id') . '&action=save')) . '" class="button icon-pencil' . ((int)($_SESSION['admin']['access'][$_module] < 3) ? ' disabled' : NULL) . '">' .  (($media === 'mobile-portrait' || $media === 'mobile-landscape') ? NULL : $lC_Language->get('icon_edit')) . '</a>
                   <a href="' . ((int)($_SESSION['admin']['access'][$_module] < 4) ? '#' : 'javascript://" onclick="deleteFeaturedProduct(\'' . $Qfeatured->valueInt('id') . '\', \'' . $Qname->value('products_name') . '\')') . '" class="button icon-trash with-tooltip' . ((int)($_SESSION['admin']['access'][$_module] < 4) ? ' disabled' : NULL) . '" title="' . $lC_Language->get('icon_delete') . '"></a>
                 </span></td>'; 
      $result['aaData'][] = array("$check", "$name", "$expires", "$status", "$action");
    }

    $Qfeatured->freeResult;

    return $result;
  }
 /**
  * Get the coupons information
  *
  * @param integer $id The coupons id
  * @access public
  * @return array
  */
  public static function get($id) {
    global $lC_Database, $lC_Language, $lC_Currencies, $lC_DateTime;

    $Qfeatured = $lC_Database->query('select * from :table_featured_products where id = :id limit 1');
    $Qfeatured->bindTable(':table_featured_products', TABLE_FEATURED_PRODUCTS);
    $Qfeatured->bindInt(':id', $id);
    $Qfeatured->execute();

    $data = $Qfeatured->toArray();
    
    $data['expires_date'] = ($Qfeatured->value('expires_date') != '0000-00-00 00:00:00') ? lC_DateTime::getShort($Qfeatured->value('expires_date')) : null;
    
    $Qfeatured->freeResult();

    return $data;
  }
 /**
  * Save the coupons information
  *
  * @param integer $id The coupons id used on update, null on insert
  * @param array $data An array containing the coupons information
  * @access public
  * @return array
  */
  public static function save($id = null, $data) {
    global $lC_Database, $lC_Language;
      
    $error = false;

    $lC_Database->startTransaction();

    if ( is_numeric($id) ) {
      $Qfeatured = $lC_Database->query('update :table_featured_products set expires_date = :expires_date, status = :status, last_modified = now() where id = :id');
      $Qfeatured->bindInt(':id', $id);
    } else {
      $Qfeatured = $lC_Database->query('insert into :table_featured_products (products_id, date_added, last_modified, expires_date, status) values (:products_id, now(), now(), :expires_date, :status)');
      $Qfeatured->bindInt(':products_id', $data['products_id']);
    }
     
    // insert/update the featured products table
    $Qfeatured->bindTable(':table_featured_products', TABLE_FEATURED_PRODUCTS);
    $Qfeatured->bindDate(':expires_date', (($data['expires_date'] != '0000-00-00 00:00:00') ? ((strstr($data['expires_date'], '/')) ? lC_DateTime::toDateTime($data['expires_date']) : $data['expires_date']) : '0000-00-00 00:00:00'));
    $Qfeatured->bindInt(':status', $data['status']);
    $Qfeatured->setLogging($_SESSION['module'], $id);
    $Qfeatured->execute();
    
    if ( $lC_Database->isError() ) {
      $error = true;
    }
    
    if ( $error === false ) {
      $lC_Database->commitTransaction();
      
      lC_Cache::clear('featured_products');

      return true;
    }

    $lC_Database->rollbackTransaction();

    return false;
  }
 /**
  * Delete the coupons record
  *
  * @param integer $id The coupons id to delete
  * @access public
  * @return boolean
  */
  public static function delete($id) {
    global $lC_Database;

    $error = false;

    $lC_Database->startTransaction();

    $Qdelete = $lC_Database->query('delete from :table_featured_products where id = :id');
    $Qdelete->bindTable(':table_featured_products', TABLE_FEATURED_PRODUCTS);
    $Qdelete->bindInt(':id', $id);
    $Qdelete->setLogging($_SESSION['module'], $id);
    $Qdelete->execute();
    
    if ( $lC_Database->isError() ) {
      $error = true;
      break;
    }
    
    if ( $error === false ) {
      $lC_Database->commitTransaction();

      return true;
    }

    $lC_Database->rollbackTransaction();

    return false;
  }
 /**
  * Batch delete coupons records
  *
  * @param array $batch The coupons id's to delete
  * @access public
  * @return boolean
  */
  public static function batchDelete($batch) {
    foreach ( $batch as $id ) {
      lC_Featured_products_Admin::delete($id);
    }
    return true;
  }
 /**
  * Update coupon status db entry
  * 
  * @access public
  * @return true or false
  */
  public static function updateStatus($id, $val) {
    global $lC_Database;
    
    $lC_Database->startTransaction();

    $Qupdate = $lC_Database->query('update :table_featured_products set status = :status where id = :id');
    $Qupdate->bindTable(':table_featured_products', TABLE_FEATURED_PRODUCTS);
    $Qupdate->bindInt(':status', $val);
    $Qupdate->bindInt(':id', $id);
    $Qupdate->setLogging($_SESSION['module'], $id);
    $Qupdate->execute();
    
    if ( !$lC_Database->isError() ) {
      $lC_Database->commitTransaction();

      return true;
    }

    $lC_Database->rollbackTransaction();

    return false;
  }
 /**
  * Get the featured products name
  *
  * @param integer $id The featured products id
  * @access public
  * @return array
  */
  public static function getFeaturedName($id) {
    global $lC_Database, $lC_Language;

    $Qname = $lC_Database->query('select * from :table_products_description where products_id = :products_id and language_id = :language_id');
    $Qname->bindTable(':table_products_description', TABLE_PRODUCTS_DESCRIPTION);
    $Qname->bindInt(':products_id', $id);
    $Qname->bindInt(':language_id', $_SESSION['admin']['language_id']);
    $Qname->execute();

    $name = $Qname->value('products_name');
    
    $Qname->freeResult();

    return $name;
  }
}
?>