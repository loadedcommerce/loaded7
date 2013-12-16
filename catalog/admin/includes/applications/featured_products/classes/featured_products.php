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
      $expires = '<td>' . lC_DateTime::getShort($Qfeatured->value('expires_date')) . '</td>';
      $status = '<td><span id="status_' . $Qfeatured->value('id') . '" onclick="updateStatus(\'' . $Qfeatured->valueInt('id') . '\', \'' . (($Qfeatured->valueInt('status') == 1) ? 0 : 1) . '\');">' . (($Qfeatured->valueInt('status') == 1) ? '<span class="icon-tick icon-size2 icon-green cursor-pointer with-tooltip" title="' . $lC_Language->get('text_disable') . '"></span>' : '<span class="icon-cross icon-size2 icon-red cursor-pointer with-tooltip" title="' . $lC_Language->get('text_enable') . '"></span>') . '</span></td>';
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

    /*$Qcoupon = $lC_Database->query('select * from :table_coupons where coupons_id = :coupons_id limit 1');
    $Qcoupon->bindTable(':table_coupons', TABLE_COUPONS);
    $Qcoupon->bindInt(':coupons_id', $id);
    $Qcoupon->execute();

    $data = $Qcoupon->toArray();

    if ($Qcoupon->value('type') == 'T' || $Qcoupon->value('type') == 'R') {
      $data['reward'] = ($Qcoupon->value('type') == 'T') ? number_format($Qcoupon->value('reward'), DECIMAL_PLACES) . '%' : $lC_Currencies->format($Qcoupon->value('reward'));
    }
    $data['purchase_over'] = ($Qcoupon->value('purchase_over') > 0) ? number_format($Qcoupon->value('purchase_over'), DECIMAL_PLACES) : null;
    $data['start_date'] = ($Qcoupon->value('start_date') != null) ? lC_DateTime::getShort($Qcoupon->value('start_date')) : null;
    $data['expires_date'] = ($Qcoupon->value('expires_date') != null) ? lC_DateTime::getShort($Qcoupon->value('expires_date')) : null;
    
    $Qcoupon->freeResult();

    return $data; */
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
    
    /*$coupon_id = '';
    $error = false;

    $lC_Database->startTransaction();

    if ( is_numeric($id) ) {
      $Qcoupon = $lC_Database->query('update :table_coupons set type = :type, mode = :mode, code = :code, reward = :reward, purchase_over = :purchase_over, start_date = :start_date, expires_date = :expires_date, uses_per_coupon = :uses_per_coupon, uses_per_customer = :uses_per_customer, restrict_to_products = :restrict_to_products, restrict_to_categories = :restrict_to_categories, restrict_to_customers = :restrict_to_customers, status = :status, date_modified = now(), sale_exclude = :sale_exclude, notes = :notes where coupons_id = :coupons_id');
      $Qcoupon->bindInt(':coupons_id', $id);
    } else {
      $Qcoupon = $lC_Database->query('insert into :table_coupons (type, mode, code, reward, purchase_over, start_date, expires_date, uses_per_coupon, uses_per_customer, restrict_to_products, restrict_to_categories, restrict_to_customers, status, date_created, date_modified, sale_exclude, notes) values (:type, :mode, :code, :reward, :purchase_over, :start_date, :expires_date, :uses_per_coupon, :uses_per_customer, :restrict_to_products, :restrict_to_categories, :restrict_to_customers, :status, now(), now(), :sale_exclude, :notes)');
    }
     
    // insert/update the coupons table
    $Qcoupon->bindTable(':table_coupons', TABLE_COUPONS);
    $Qcoupon->bindValue(':type', $data['type']);
    $Qcoupon->bindValue(':mode', $data['mode']);
    $Qcoupon->bindValue(':code', $data['code']);
    $Qcoupon->bindValue(':reward', $data['reward']);
    $Qcoupon->bindInt(':purchase_over', (($data['purchase_over'] > 0) ? str_replace('$', '', $data['purchase_over']) : 0.00));
    $Qcoupon->bindDate(':start_date', (($data['start_date'] != '') ? ((strstr($data['start_date'], '/')) ? lC_DateTime::toDateTime($data['start_date']) : $data['start_date']) : null));
    $Qcoupon->bindDate(':expires_date', (($data['expires_date'] != '') ? ((strstr($data['expires_date'], '/')) ? lC_DateTime::toDateTime($data['expires_date']) : $data['expires_date']) : null));
    $Qcoupon->bindInt(':uses_per_coupon', $data['uses_per_coupon']);
    $Qcoupon->bindInt(':uses_per_customer', $data['uses_per_customer']);
    $Qcoupon->bindValue(':restrict_to_products', $data['restrict_to_products']);
    $Qcoupon->bindValue(':restrict_to_categories', $data['restrict_to_categories']);
    $Qcoupon->bindValue(':restrict_to_customers', $data['restrict_to_customers']);
    $Qcoupon->bindInt(':status', $data['status']);
    $Qcoupon->bindInt(':sale_exclude', $data['sale_exclude']);
    $Qcoupon->bindValue(':notes', $data['notes']);
    $Qcoupon->setLogging($_SESSION['module'], $id);
    $Qcoupon->execute();
    
    if ( $lC_Database->isError() ) {
      $error = true;
    }
    
    // insert/update the coupons description table
    if ( !$lC_Database->isError() ) {
      $coupon_id = (is_numeric($id)) ? $id : $lC_Database->nextID();
      
      foreach ( $lC_Language->getAll() as $l ) {
        if ( is_numeric($id) ) {
          $Qcoupondescription = $lC_Database->query('update :table_coupons_description set name = :name where coupons_id = :coupons_id and language_id = :language_id');
          $Qcoupondescription->bindInt(':coupons_id', $coupon_id);
        } else {
          $Qcoupondescription = $lC_Database->query('insert into :table_coupons_description (coupons_id, language_id, name) values (:coupons_id, :language_id, :name)');
          $Qcoupondescription->bindInt(':coupons_id', $coupon_id);
        }

        $Qcoupondescription->bindTable(':table_coupons_description', TABLE_COUPONS_DESCRIPTION);
        $Qcoupondescription->bindInt(':language_id', $l['id']);
        $Qcoupondescription->bindValue(':name', $data['name'][$l['id']]);
        $Qcoupondescription->execute();
        
        if ( $lC_Database->isError() ) {
          $error = true;
          break;
        }
      }
    }
    
    if ( $error === false ) {
      $lC_Database->commitTransaction();

      return true;
    }

    $lC_Database->rollbackTransaction();

    return false;*/
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

    $Qcoupon = $lC_Database->query('delete from :table_featured_products where id = :id');
    $Qcoupon->bindTable(':table_featured_products', TABLE_FEATURED_PRODUCTS);
    $Qcoupon->bindInt(':id', $id);
    $Qcoupon->setLogging($_SESSION['module'], $id);
    $Qcoupon->execute();
    
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
}
?>