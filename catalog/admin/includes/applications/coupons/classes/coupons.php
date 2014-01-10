<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: coupons.php v1.0 2013-08-08 datazen $
*/
class lC_Coupons_Admin {
 /**
  * Returns the coupons datatable data for listings
  *
  * @access public
  * @return array
  */
  public static function getAll() {
    global $lC_Database, $lC_Language, $lC_Currencies, $_module;

    $media = $_GET['media'];
    
    $Qcoupons = $lC_Database->query('select c.coupons_id, c.type, c.code, c.reward, c.purchase_over, c.start_date, c.expires_date, c.uses_per_coupon, c.uses_per_customer, c.restrict_to_products, c.restrict_to_categories, c.restrict_to_customers, c.status, c.notes, cd.name from :table_coupons c, :table_coupons_description cd where c.coupons_id = cd.coupons_id and cd.language_id = :language_id order by c.date_created desc');
    $Qcoupons->bindTable(':table_coupons', TABLE_COUPONS);
    $Qcoupons->bindTable(':table_coupons_description', TABLE_COUPONS_DESCRIPTION);
    $Qcoupons->bindInt(':language_id', $lC_Language->getID());
    $Qcoupons->execute();
    
    $result = array('aaData' => array());
    while ( $Qcoupons->next() ) {
      
      if ($Qcoupons->value('type') == 'T') { // percen(T)
        $rewardStr = number_format($Qcoupons->value('reward'), DECIMAL_PLACES) . '%';         
      } else if ($Qcoupons->value('type') == 'R') { // cash (R)eward
        $rewardStr = $lC_Currencies->format($Qcoupons->value('reward'));
      } else if ($Qcoupons->value('type') == 'S') { // free (S)hipping
        $rewardStr = $lC_Language->get('text_free_shipping');
      } else if ($Qcoupons->value('type') == 'P') { // free (P)roduct
        $rewardStr = $lC_Language->get('text_free_product');
      } 
      
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
      $name = '<td>' . $Qcoupons->value('name') . '<br /><small>' . $Qcoupons->value('notes') . '</small></td>';
      $status = '<td><span id="status_' . $Qcoupons->value('coupons_id') . '" onclick="updateStatus(\'' . $Qcoupons->value('coupons_id') . '\', \'' . (($Qcoupons->value('status') == 1) ? 0 : 1) . '\');">' . (($Qcoupons->valueInt('status') == 1) ? '<span class="icon-tick icon-size2 icon-green cursor-pointer with-tooltip" title="' . $lC_Language->get('text_disable_coupon') . '"></span>' : '<span class="icon-cross icon-size2 icon-red cursor-pointer with-tooltip" title="' . $lC_Language->get('text_enable_coupon') . '"></span>') . '</span></td>';
      $code = '<td>' . $Qcoupons->value('code') . '</td>';
      $reward = '<td>' . $rewardStr . '</td>';
      $limits = '<td>' . (($Qcoupons->value('purchase_over') > 0 || $Qcoupons->value('uses_per_customer') > 0 || $Qcoupons->value('uses_per_coupon') > 0 || $Qcoupons->value('start_date') != null || $Qcoupons->value('expires_date') != null) ? (($Qcoupons->value('purchase_over') > 0) ? '<small class="tag purple-bg no-wrap">' . $lC_Language->get('text_purchase_over') . ': ' . $lC_Currencies->format($Qcoupons->value('purchase_over')) .'</small>' : null) . ' ' . (($Qcoupons->value('uses_per_customer') > 0) ? '<small class="tag orange-bg no-wrap">' . $Qcoupons->value('uses_per_customer') . ' ' . $lC_Language->get('text_per_customer') .'</small>' : null) . ' ' . (($Qcoupons->value('uses_per_coupon') > 0) ? '<small class="tag red-bg no-wrap">' . $Qcoupons->value('uses_per_coupon') . ' ' . $lC_Language->get('text_per_coupon') . '</small>' : null) . ' ' . (($Qcoupons->value('start_date') != null) ? '<small class="tag grey-bg no-wrap">' . $lC_Language->get('text_start_date') . ': ' . lC_DateTime::getShort($Qcoupons->value('start_date')) . '</small>' : null) . ' ' . (($Qcoupons->value('expires_date') != null) ? '<small class="tag grey-bg no-wrap">' . $lC_Language->get('text_expire_date') . ': ' . lC_DateTime::getShort($Qcoupons->value('expires_date')) . '</small>' : null) : '<small class="tag green-bg no-wrap" title="' . $lC_Language->get('text_no_restrictions') . '">' . $lC_Language->get('text_none') . '</small>') . '</td>';
  //    $restrictions = '<td>' . ((!empty($rtProdsString) || !empty($rtCatsString) || !empty($rtCustString)) ? $rtProdsString . ' ' . $rtCatsString . ' ' . $rtCustString : '<small class="tag green-bg no-wrap">' . $lC_Language->get('text_none') . '</small>') . '</td>';
      $action = '<td class="align-right vertical-center">
                   <span class="button-group">
                     <a href="' . ((int)($_SESSION['admin']['access'][$_module] < 3) ? '#' : lc_href_link_admin(FILENAME_DEFAULT, $_module . '=' . $Qcoupons->valueInt('coupons_id') . '&action=save')) . '" class="button icon-pencil' . ((int)($_SESSION['admin']['access'][$_module] < 3) ? ' disabled' : NULL) . '">' .  (($media === 'mobile-portrait' || $media === 'mobile-landscape') ? NULL : $lC_Language->get('icon_edit')) . '</a>
                     <a href="' . ((int)($_SESSION['admin']['access'][$_module] < 4) ? '#' : 'javascript://" onclick="copyCoupon(\'' . $Qcoupons->valueInt('coupons_id') . '\', \'' . $Qcoupons->value('name') . '\', \'' . $Qcoupons->value('code') . '\')') . '" class="button icon-pages with-tooltip' . ((int)($_SESSION['admin']['access'][$_module] < 4) ? ' disabled' : NULL) . '" title="' . $lC_Language->get('icon_copy') . '"></a>
                   </span>
                   <span class="button-group">
                     <a href="' . ((int)($_SESSION['admin']['access'][$_module] < 4) ? '#' : 'javascript://" onclick="deleteCoupon(\'' . $Qcoupons->valueInt('coupons_id') . '\', \'' . $Qcoupons->value('name') . '\')') . '" class="button icon-trash with-tooltip' . ((int)($_SESSION['admin']['access'][$_module] < 4) ? ' disabled' : NULL) . '" title="' . $lC_Language->get('icon_delete') . '"></a>
                   </span>
                 </td>';
      $result['aaData'][] = array("$check", "$name", "$status", "$code", "$reward", "$limits", "$action");
      
    }

    $Qcoupons->freeResult;

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

    $Qcoupon = $lC_Database->query('select * from :table_coupons where coupons_id = :coupons_id limit 1');
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
    
    $coupon_id = '';
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

      return $coupon_id; // Return the coupon ID to with the save_close button
    }

    $lC_Database->rollbackTransaction();

    return false;
  }
 /**
  * Copy the coupon
  *
  * @param integer $id The coupons id used on copy
  * @param array $data An array containing the coupons information
  * @access public
  * @return array
  */
  public static function copyCoupon($id) {
    global $lC_Database, $lC_Language;
    
    $error = false;

    $lC_Database->startTransaction();

    // copy the data from the desired coupon into a new row
    $Qcoupon = $lC_Database->query('insert into :table_coupons (type, mode, code, reward, purchase_over, start_date, expires_date, uses_per_coupon, uses_per_customer, restrict_to_products, restrict_to_categories, restrict_to_customers, status, date_created, date_modified, sale_exclude, notes) select type, mode, code, reward, purchase_over, start_date, expires_date, uses_per_coupon, uses_per_customer, restrict_to_products, restrict_to_categories, restrict_to_customers, status, date_created, date_modified, sale_exclude, notes from :table_coupons_from where coupons_id = :coupons_id');
    $Qcoupon->bindTable(':table_coupons', TABLE_COUPONS);
    $Qcoupon->bindTable(':table_coupons_from', TABLE_COUPONS);
    $Qcoupon->bindInt(':coupons_id', $id);
    $Qcoupon->setLogging($_SESSION['module'], $lC_Database->nextID());
    $Qcoupon->execute();
    
    if ( $lC_Database->isError() ) {
      $error = true;
      break;
    }
    
    $new_id = $lC_Database->nextID();
    
    // get the coupons code to update
    $Qoldcode = $lC_Database->query('select code from :table_coupons where coupons_id = :coupons_id');
    $Qoldcode->bindTable(':table_coupons', TABLE_COUPONS);
    $Qoldcode->bindInt(':coupons_id', $new_id);
    $Qoldcode->execute();
    
    if ( $lC_Database->isError() ) {
      $error = true;
      break;
    }
     
    // update the new coupons code
    $Qcouponcode = $lC_Database->query('update :table_coupons set code = :code where coupons_id = :coupons_id');
    $Qcouponcode->bindTable(':table_coupons', TABLE_COUPONS);
    $Qcouponcode->bindInt(':coupons_id', $new_id);
    $Qcouponcode->bindValue(':code', $Qoldcode->value('code') . '_1');
    $Qcouponcode->execute();
    
    if ( $lC_Database->isError() ) {
      $error = true;
      break;
    }
    
    // update the coupons description table
    if ( !$lC_Database->isError() ) {
      foreach ( $lC_Language->getAll() as $l ) {
        // get values to copy from
        $Qolddescription = $lC_Database->query('select name from :table_coupons_description where coupons_id = :coupons_id and language_id = :language_id');
        $Qolddescription->bindTable(':table_coupons_description', TABLE_COUPONS_DESCRIPTION);
        $Qolddescription->bindInt(':coupons_id', $id);
        $Qolddescription->bindInt(':language_id', $l['id']);
        $Qolddescription->execute();
    
        if ( $lC_Database->isError() ) {
          $error = true;
          break;
        }
         
        // insert to new coupon description
        $Qcoupondescription = $lC_Database->query('insert into :table_coupons_description (coupons_id, language_id, name) values (:coupons_id, :language_id, :name)');
        $Qcoupondescription->bindTable(':table_coupons_description', TABLE_COUPONS_DESCRIPTION);
        $Qcoupondescription->bindInt(':coupons_id', $new_id);
        $Qcoupondescription->bindInt(':language_id', $l['id']);
        $Qcoupondescription->bindValue(':name', $Qolddescription->value('name') . '_1');
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

    $Qcoupon = $lC_Database->query('delete from :table_coupons where coupons_id = :coupons_id');
    $Qcoupon->bindTable(':table_coupons', TABLE_COUPONS);
    $Qcoupon->bindInt(':coupons_id', $id);
    $Qcoupon->setLogging($_SESSION['module'], $id);
    $Qcoupon->execute();
    
    if ( $lC_Database->isError() ) {
      $error = true;
      break;
    }

    $Qcoupon = $lC_Database->query('delete from :table_coupons_description where coupons_id = :coupons_id');
    $Qcoupon->bindTable(':table_coupons_description', TABLE_COUPONS_DESCRIPTION);
    $Qcoupon->bindInt(':coupons_id', $id);
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
      lC_Coupons_Admin::delete($id);
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

    $Qupdate = $lC_Database->query('update :table_coupons set status = :status where coupons_id = :coupons_id');
    $Qupdate->bindTable(':table_coupons', TABLE_COUPONS);
    $Qupdate->bindInt(':status', $val);
    $Qupdate->bindInt(':coupons_id', $id);
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