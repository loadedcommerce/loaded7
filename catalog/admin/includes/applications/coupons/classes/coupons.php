<?php
/*
  $Id: coupons.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html

  @function The lC_Coupons_Admin class manages coupons
*/
class lC_Coupons_Admin {
 /*
  * Returns the coupons datatable data for listings
  *
  * @access public
  * @return array
  */
  public static function getAll() {
    global $lC_Database, $lC_Language, $lC_Currencies, $_module;

    $media = $_GET['media'];
    
    $Qcoupons = $lC_Database->query('select c.coupons_id, c.coupons_code, c.coupons_reward, c.coupons_purchase_over, c.coupons_start_date, c.coupons_expires_date, c.uses_per_coupon, c.uses_per_customer, c.restrict_to_products, c.restrict_to_categories, c.restrict_to_customers, c.coupons_status, cd.coupons_name from :table_coupons c, :table_coupons_description cd where c.coupons_id = cd.coupons_id and cd.language_id = :language_id order by c.date_created desc');
    $Qcoupons->bindTable(':table_coupons', TABLE_COUPONS);
    $Qcoupons->bindTable(':table_coupons_description', TABLE_COUPONS_DESCRIPTION);
    $Qcoupons->bindInt(':language_id', $lC_Language->getID());
    $Qcoupons->execute();
    
    $result = array('aaData' => array());
    while ( $Qcoupons->next() ) {
      
      // get restrictions arrays
      $restrictToProdsArr = explode(",", $Qcoupons->value('restrict_to_products'));
      $restrictToCatsArr = explode(",", $Qcoupons->value('restrict_to_categories'));
      $restrictToCustArr = explode(",", $Qcoupons->value('restrict_to_customers'));
      
      // set products restrictions array string
      $rtProdsString = '';
      foreach ($restrictToProdsArr as $rtProdsID) {
        $Qprodname = $lC_Database->query('select products_name from :table_products_description where products_id = :products_id and language_id = :language_id limit 1');
        $Qprodname->bindTable(':table_products_description', TABLE_PRODUCTS_DESCRIPTION);
        $Qprodname->bindInt(':products_id', $rtProdsID);
        $Qprodname->bindInt(':language_id', $lC_Language->getID());
        $Qprodname->execute();
    
        while ( $Qprodname->next() ) {
          $rtProdsString .= '<small class="tag blue-bg no-wrap">' . $Qprodname->value('products_name') . '</small> ';
        }
      }
      
      // set categories restrictions array string
      $rtCatsString = '';
      foreach ($restrictToCatsArr as $rtCatsID) {
        $Qcatname = $lC_Database->query('select categories_name from :table_categories_description where categories_id = :categories_id and language_id = :language_id limit 1');
        $Qcatname->bindTable(':table_categories_description', TABLE_CATEGORIES_DESCRIPTION);
        $Qcatname->bindInt(':categories_id', $rtCatsID);
        $Qcatname->bindInt(':language_id', $lC_Language->getID());
        $Qcatname->execute();
    
        while ( $Qcatname->next() ) {
          $rtCatsString .= '<small class="tag silver-bg no-wrap">' . $Qcatname->value('categories_name') . '</small> ';
        }
      }
      
      // set customers restrictions array string
      $rtCustString = '';
      foreach ($restrictToCustArr as $rtCustID) {
        $Qcustname = $lC_Database->query('select customers_firstname, customers_lastname from :table_customers where customers_id = :customers_id limit 1');
        $Qcustname->bindTable(':table_customers', TABLE_CUSTOMERS);
        $Qcustname->bindInt(':customers_id', $rtCustID);
        $Qcustname->execute();
    
        while ( $Qcustname->next() ) {
          $rtCustString .= '<small class="tag orange-bg no-wrap">' . $Qcustname->value('customers_firstname') . ' ' . $Qcustname->value('customers_lastname') . '</small> ';
        }
      }
      
      $check = '<td><input class="batch" type="checkbox" name="batch[]" value="' . $Qcoupons->valueInt('coupons_id') . '" id="' . $Qcoupons->valueInt('coupons_id') . '"></td>';
      $name = '<td>' . $Qcoupons->value('coupons_name') . '</td>';
      $status = '<td><span id="status_' . $Qcoupons->value('coupons_id') . '" onclick="updateStatus(\'' . $Qcoupons->value('coupons_id') . '\', \'' . (($Qcoupons->value('coupons_status') == 1) ? 0 : 1) . '\');">' . (($Qcoupons->valueInt('coupons_status') == 1) ? '<span class="icon-tick icon-size2 icon-green cursor-pointer with-tooltip" title="' . $lC_Language->get('text_disable_coupon') . '"></span>' : '<span class="icon-cross icon-size2 icon-red cursor-pointer with-tooltip" title="' . $lC_Language->get('text_enable_coupon') . '"></span>') . '</span></td>';
      $code = '<td>' . $Qcoupons->value('coupons_code') . '</td>';
      $reward = '<td>' . $lC_Currencies->format($Qcoupons->value('coupons_reward')) . '</td>';
      $limits = '<td>' . (($Qcoupons->value('coupons_minimum_order') > 0 || $Qcoupons->value('uses_per_customer') > 0 || $Qcoupons->value('uses_per_coupon') > 0 || $Qcoupons->value('coupons_start_date') != '0000-00-00 00:00:00' || $Qcoupons->value('coupons_expires_date') != '0000-00-00 00:00:00') ? (($Qcoupons->value('coupons_minimum_order') > 0) ? '<small class="tag purple-bg no-wrap">' . $lC_Language->get('text_minimum_order') . ': ' . $lC_Currencies->format($Qcoupons->value('coupons_minimum_order')) .'</small>' : null) . ' ' . (($Qcoupons->value('uses_per_customer') > 0) ? '<small class="tag orange-bg no-wrap">' . $Qcoupons->value('uses_per_customer') . ' ' . $lC_Language->get('text_per_customer') .'</small>' : null) . ' ' . (($Qcoupons->value('uses_per_coupon') > 0) ? '<small class="tag red-bg no-wrap">' . $Qcoupons->value('uses_per_coupon') . ' ' . $lC_Language->get('text_per_coupon') . '</small>' : null) . ' ' . (($Qcoupons->value('coupons_start_date') != '0000-00-00 00:00:00') ? '<small class="tag grey-bg no-wrap">' . $lC_Language->get('text_start_date') . ': ' . lC_DateTime::getShort($Qcoupons->value('coupons_start_date')) . '</small>' : null) . ' ' . (($Qcoupons->value('coupons_expires_date') != '0000-00-00 00:00:00') ? '<small class="tag grey-bg no-wrap">' . $lC_Language->get('text_expire_date') . ': ' . lC_DateTime::getShort($Qcoupons->value('coupons_expires_date')) . '</small>' : null) : '<small class="tag green-bg no-wrap" title="' . $lC_Language->get('text_no_restrictions') . '">' . $lC_Language->get('text_none') . '</small>') . '</td>';
      $restrictions = '<td>' . ((!empty($rtProdsString) || !empty($rtCatsString) || !empty($rtCustString)) ? $rtProdsString . ' ' . $rtCatsString . ' ' . $rtCustString : '<small class="tag green-bg no-wrap">' . $lC_Language->get('text_none') . '</small>') . '</td>';
      $action = '<td class="align-right vertical-center"><span class="button-group compact">
                   <a href="' . ((int)($_SESSION['admin']['access'][$_module] < 3) ? '#' : lc_href_link_admin(FILENAME_DEFAULT, $_module . '=' . $Qcoupons->valueInt('coupons_id') . '&action=save')) . '" class="button icon-pencil' . ((int)($_SESSION['admin']['access'][$_module] < 3) ? ' disabled' : NULL) . '">' .  (($media === 'mobile-portrait' || $media === 'mobile-landscape') ? NULL : $lC_Language->get('icon_edit')) . '</a>
                   <a href="' . ((int)($_SESSION['admin']['access'][$_module] < 4) ? '#' : 'javascript://" onclick="copyCoupon(\'' . $Qcoupons->valueInt('coupons_id') . '\')') . '" class="button icon-pages with-tooltip' . ((int)($_SESSION['admin']['access'][$_module] < 4) ? ' disabled' : NULL) . '" title="' . $lC_Language->get('icon_copy') . '"></a>
                   <a href="' . ((int)($_SESSION['admin']['access'][$_module] < 4) ? '#' : 'javascript://" onclick="deleteCoupon(\'' . $Qcoupons->valueInt('coupons_id') . '\', \'' . $Qcoupons->value('coupons_name') . '\')') . '" class="button icon-trash with-tooltip' . ((int)($_SESSION['admin']['access'][$_module] < 4) ? ' disabled' : NULL) . '" title="' . $lC_Language->get('icon_delete') . '"></a>
                 </span></td>';
      $result['aaData'][] = array("$check", "$name", "$status", "$code", "$reward", "$limits", "$restrictions", "$action");
      
    }

    $Qcoupons->freeResult;

    return $result;
  }
 /*
  * Get the coupons information
  *
  * @param integer $id The coupons id
  * @access public
  * @return array
  */
  public static function get($id) {
    global $lC_Database, $lC_Language, $lC_Currencies, $lC_DateTime;

    $Qcoupon = $lC_Database->query('select * from :table_coupons where coupons_id = :coupons_id limit 1');
    $Qcoupon->bindTable(':table_coupons', TABLE_COUPONS);
    $Qcoupon->bindInt(':coupons_id', $id);
    $Qcoupon->execute();

    $data = $Qcoupon->toArray();

    $data['coupons_reward'] = $lC_Currencies->format($Qcoupon->value('coupons_reward'));
    $data['coupons_purchase_over'] = $lC_Currencies->format($Qcoupon->value('coupons_purchase_over'));
    $data['coupons_start_date'] = lC_DateTime::getShort($Qcoupon->value('coupons_start_date'));
    $data['coupons_expires_date'] = lC_DateTime::getShort($Qcoupon->value('coupons_expires_date'));
    
    $Qcoupon->freeResult();

    return $data;
  }
 /*
  * Save the coupons information
  *
  * @param integer $id The coupons id used on update, null on insert
  * @param array $data An array containing the coupons information
  * @access public
  * @return array
  */
  public static function save($id = null, $data) {
    global $lC_Database, $lC_Language;
    
    $coupon_id = '';
    $error = false;

    $lC_Database->startTransaction();

    if ( is_numeric($id) ) {
      $Qcoupon = $lC_Database->query('update :table_coupons set coupons_type = :coupons_type, coupons_mode = :coupons_mode, coupons_code = :coupons_code, coupons_reward = :coupons_reward, coupons_purchase_over = :coupons_purchase_over, coupons_start_date = :coupons_start_date, coupons_expires_date = :coupons_expires_date, uses_per_coupon = :uses_per_coupon, uses_per_customer = :uses_per_customer, restrict_to_products = :restrict_to_products, restrict_to_categories = :restrict_to_categories, restrict_to_customers = :restrict_to_customers, coupons_status = :coupons_status, date_modified = now(), coupons_sale_exclude = :coupons_sale_exclude where coupons_id = :coupons_id');
      $Qcoupon->bindInt(':coupons_id', $id);
    } else {
      $Qcoupon = $lC_Database->query('insert into :table_coupons (coupons_type, coupons_mode, coupons_code, coupons_reward, coupons_purchase_over, coupons_start_date, coupons_expires_date, uses_per_coupon, uses_per_customer, restrict_to_products, restrict_to_categories, restrict_to_customers, coupons_status, date_created, date_modified, coupons_sale_exclude) values (:coupons_type, :coupons_mode, :coupons_code, :coupons_reward, :coupons_purchase_over, :coupons_start_date, :coupons_expires_date, :uses_per_coupon, :uses_per_customer, :restrict_to_products, :restrict_to_categories, :restrict_to_customers, :coupons_status, now(), now(), :coupons_sale_exclude)');
    }
     
    // insert/update the coupons table
    $Qcoupon->bindTable(':table_coupons', TABLE_COUPONS);
    $Qcoupon->bindValue(':coupons_type', $data['coupons_type']);
    $Qcoupon->bindValue(':coupons_mode', $data['coupons_mode']);
    $Qcoupon->bindValue(':coupons_code', $data['coupons_code']);
    $Qcoupon->bindValue(':coupons_reward', $data['coupons_reward']);
    $Qcoupon->bindValue(':coupons_purchase_over', $data['coupons_purchase_over']);
    $Qcoupon->bindDate(':coupons_start_date', $data['coupons_start_date']);
    $Qcoupon->bindDate(':coupons_expires_date', $data['coupons_expires_date']);
    $Qcoupon->bindInt(':uses_per_coupon', $data['uses_per_coupon']);
    $Qcoupon->bindInt(':uses_per_customer', $data['uses_per_customer']);
    $Qcoupon->bindValue(':restrict_to_products', $data['restrict_to_products']);
    $Qcoupon->bindValue(':restrict_to_categories', $data['restrict_to_categories']);
    $Qcoupon->bindValue(':restrict_to_customers', $data['restrict_to_customers']);
    $Qcoupon->bindInt(':coupons_status', $data['coupons_status']);
    $Qcoupon->bindInt(':coupons_sale_exclude', $data['coupons_sale_exclude']);
    $Qcoupon->setLogging($_SESSION['module'], $id);
    $Qcoupon->execute();
    
    // insert/update the coupons description table
    if ( !$lC_Database->isError() ) {
      $coupon_id = (is_numeric($id)) ? $id : $lC_Database->nextID();
      
      foreach ( $lC_Language->getAll() as $l ) {
        if ( is_numeric($id) ) {
          $Qcoupondescription = $lC_Database->query('update :table_coupons_description set coupons_name = :coupons_name, coupons_description = :coupons_description where coupons_id = :coupons_id and language_id = :language_id');
          $Qcoupondescription->bindInt(':coupons_id', $coupon_id);
        } else {
          $Qcoupondescription = $lC_Database->query('insert into :table_coupons_description (coupons_id, language_id, coupons_name, coupons_description) values (:coupons_id, :language_id, :coupons_name, :coupons_description)');
          $Qcoupondescription->bindInt(':coupons_id', $coupon_id);
        }

        $Qcoupondescription->bindTable(':table_coupons_description', TABLE_COUPONS_DESCRIPTION);
        $Qcoupondescription->bindInt(':language_id', $l['id']);
        $Qcoupondescription->bindValue(':coupons_name', $data['coupons_name'][$l['id']]);
        $Qcoupondescription->bindValue(':coupons_description', $data['coupons_description'][$l['id']]);
        $Qcoupondescription->setLogging($_SESSION['module'], $coupon_id);
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

    return false;
  }
 /*
  * Delete the coupons record
  *
  * @param integer $id The coupons id to delete
  * @access public
  * @return boolean
  */
  public static function delete($id) {
    global $lC_Database;

    $Qcoupon = $lC_Database->query('delete from :table_coupons where coupons_id = :coupons_id');
    $Qcoupon->bindTable(':table_coupons', TABLE_COUPONS);
    $Qcoupon->bindInt(':coupons_id', $id);
    $Qcoupon->setLogging($_SESSION['module'], $id);
    $Qcoupon->execute();

    $Qcoupon = $lC_Database->query('delete from :table_coupons_description where coupons_id = :coupons_id');
    $Qcoupon->bindTable(':table_coupons_description', TABLE_COUPONS_DESCRIPTION);
    $Qcoupon->bindInt(':coupons_id', $id);
    $Qcoupon->setLogging($_SESSION['module'], $id);
    $Qcoupon->execute();

    if ( !$lC_Database->isError() ) {
      return true;
    }

    return false;
  }
 /*
  * Batch delete coupons records
  *
  * @param array $batch The coupons id's to delete
  * @access public
  * @return boolean
  */
  public static function batchDelete($batch) {
    foreach ( $batch as $id ) {
      lC_Coupons_Admin::delete($id);
    }
    return true;
  }
 /*
  * update coupon status db entry
  * 
  * @access public
  * @return true or false
  */
  public static function updateStatus($id, $val) {
    global $lC_Database;
    
    $Qupdate = $lC_Database->query('update :table_coupons set coupons_status = :coupons_status where coupons_id = :coupons_id');
    $Qupdate->bindTable(':table_coupons', TABLE_COUPONS);
    $Qupdate->bindInt(':coupons_status', $val);
    $Qupdate->bindInt(':coupons_id', $id);
    $Qupdate->execute();
      
    return true;
  }
}
?>