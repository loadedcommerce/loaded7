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
    
    $Qcoupons = $lC_Database->query('select c.coupons_id, c.coupons_code, c.coupons_reward, c.coupons_minimum_order, c.uses_per_coupon, c.uses_per_customer, c.restrict_to_products, c.restrict_to_categories, c.restrict_to_customers, c.coupons_status, cd.coupons_name from :table_coupons c, :table_coupons_description cd where c.coupons_id = cd.coupons_id and cd.language_id = :language_id order by c.date_created desc');
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
          $rtCatsString .= '<small class="tag white-bg no-wrap">' . $Qcatname->value('categories_name') . '</small> ';
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
          $rtCustString .= '' . $Qcustname->value('customers_firstname') . ' ' . $Qcustname->value('customers_lastname') . '';
        }
      }
      
      $check = '<td><input class="batch" type="checkbox" name="batch[]" value="' . $Qcoupons->valueInt('coupons_id') . '" id="' . $Qcoupons->valueInt('coupons_id') . '"></td>';
      $name = '<td>' . $Qcoupons->value('coupons_name') . '</td>';
      $code = '<td>' . $Qcoupons->value('coupons_code') . '</td>';
      $reward = '<td>' . $lC_Currencies->format($Qcoupons->value('coupons_reward')) . '</td>';
      $limits = '<td>' . (($Qcoupons->value('uses_per_customer') > 0 || $Qcoupons->value('uses_per_coupon') > 0) ? (($Qcoupons->value('uses_per_customer') > 0) ? '<small class="tag orange-bg no-wrap">' . $Qcoupons->value('uses_per_customer') . ' ' . $lC_Language->get('text_per_customer') .'</small>' : null) . ' ' . (($Qcoupons->value('uses_per_coupon') > 0) ? '<small class="tag red-bg no-wrap">' . $Qcoupons->value('uses_per_coupon') . ' ' . $lC_Language->get('text_per_coupon') . '</small>' : null) : '<small class="tag green-bg no-wrap">None</small>') . '</td>';
      $restrictions = '<td>' . ((!empty($rtProdsString) || !empty($rtCatsString) || !empty($rtCustString)) ? $rtProdsString . ' ' . $rtCatsString . ' ' . $rtCustString : '<small class="tag green-bg no-wrap">' . $lC_Language->get('text_none') . '</small>') . '</td>';
      $action = '<td class="align-right vertical-center"><span class="button-group compact">
                   <a href="' . ((int)($_SESSION['admin']['access'][$_module] < 3) ? '#' : 'javascript://" onclick="editCoupon(\'' . $Qcoupons->valueInt('coupons_id') . '\')') . '" class="button icon-pencil' . ((int)($_SESSION['admin']['access'][$_module] < 3) ? ' disabled' : NULL) . '">' . (($media === 'mobile-portrait' || $media === 'mobile-landscape') ? NULL : $lC_Language->get('icon_edit')) . '</a>
                   <a href="' . ((int)($_SESSION['admin']['access'][$_module] < 4) ? '#' : 'javascript://" onclick="deleteCoupon(\'' . $Qcoupons->valueInt('coupons_id') . '\')') . '" class="button icon-trash with-tooltip' . ((int)($_SESSION['admin']['access'][$_module] < 4) ? ' disabled' : NULL) . '" title="' . $lC_Language->get('icon_delete') . '"></a>
                 </span></td>';
      $result['aaData'][] = array("$check", "$name", "$code", "$reward", "$limits", "$restrictions", "$action");
      
    }

    $Qspecials->freeResult;

    return $result;
  }
 /*
  * Return the data used on the dialog forms
  *
  * @access public
  * @return array
  */
  public static function formData() {
    /*global $lC_Database, $lC_Language, $lC_Currencies;

    $result = array();
    $specials_array = array();
    $specials_array2 = array();
    $Qspecials = $lC_Database->query('select p.products_id, p.products_price, p.products_tax_class_id, pd.products_name, s.specials_new_products_price from :table_products p left join :table_specials s on (p.products_id = s.products_id), :table_products_description pd where p.products_id = pd.products_id and pd.language_id = :language_id and p.has_children = 0 order by pd.products_name');
    $Qspecials->bindTable(':table_products', TABLE_PRODUCTS);
    $Qspecials->bindTable(':table_products_description', TABLE_PRODUCTS_DESCRIPTION);
    $Qspecials->bindTable(':table_specials', TABLE_SPECIALS);
    $Qspecials->bindInt(':language_id', $lC_Language->getID());
    $Qspecials->execute();
    while ( $Qspecials->next() ) {
      if ( $Qspecials->valueDecimal('specials_new_products_price') < 1 ) {
        $specials_array[] = array('id' => $Qspecials->valueInt('products_id'),
                                  'text' => $Qspecials->value('products_name') . ' (' . $lC_Currencies->format($Qspecials->value('products_price')) . ')',
                                  'tax_class_id' => $Qspecials->valueInt('products_tax_class_id'));

      }
    }
    $result['specialsArray'] = $specials_array;

    $Qspecials->freeResult();

    return $result;*/
  }
 /*
  * Get the coupons information
  *
  * @param integer $id The coupons id
  * @access public
  * @return array
  */
  public static function getData($id) {
    /*global $lC_Database, $lC_Language, $lC_Currencies, $lC_DateTime;

    $Qspecial = $lC_Database->query('select p.products_id, pd.products_name, p.products_price, s.specials_id, s.specials_new_products_price, s.specials_date_added, s.specials_last_modified, s.start_date, s.expires_date, s.date_status_change, s.status from :table_products p, :table_specials s, :table_products_description pd where s.specials_id = :specials_id and s.products_id = p.products_id and p.products_id = pd.products_id and pd.language_id = :language_id limit 1');
    $Qspecial->bindTable(':table_specials', TABLE_SPECIALS);
    $Qspecial->bindTable(':table_products', TABLE_PRODUCTS);
    $Qspecial->bindTable(':table_products_description', TABLE_PRODUCTS_DESCRIPTION);
    $Qspecial->bindInt(':specials_id', $id);
    $Qspecial->bindInt(':language_id', $lC_Language->getID());
    $Qspecial->execute();

    $data = $Qspecial->toArray();

    $data['products_price_formatted'] = $lC_Currencies->format($Qspecial->value('products_price'));
    $data['start_date_formatted'] = lC_DateTime::getShort($Qspecial->value('start_date'));
    $data['expires_date_formatted'] = lC_DateTime::getShort($Qspecial->value('expires_date'));

    $Qspecial->freeResult();

    return $data;*/
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
    /*global $lC_Database, $lC_DateTime;

    $error = false;

    $Qproduct = $lC_Database->query('select products_price from :table_products where products_id = :products_id limit 1');
    $Qproduct->bindTable(':table_products', TABLE_PRODUCTS);
    $Qproduct->bindInt(':products_id', $data['products_id']);
    $Qproduct->execute();

    $specials_price = $data['specials_price'];

    if ( substr($specials_price, -1) == '%' ) {
      $specials_price = $Qproduct->valueDecimal('products_price') - (((double)$specials_price / 100) * $Qproduct->valueDecimal('products_price'));
    }
    if ( ( $specials_price < '0.00' ) || ( $specials_price >= $Qproduct->valueDecimal('products_price') ) ) {
      $result['rpcStatus'] = -1;
      $error = true;
    }

    if ( $data['expires_date'] < $data['start_date'] ) {
      $result['rpcStatus'] = -2;
      $error = true;
    }

    if ( $error === false ) {
      if ( is_numeric($id) ) {
        $Qspecial = $lC_Database->query('update :table_specials set specials_new_products_price = :specials_new_products_price, specials_last_modified = now(), expires_date = :expires_date, start_date = :start_date, status = :status where specials_id = :specials_id');
        $Qspecial->bindInt(':specials_id', $id);
      } else {
        $Qspecial = $lC_Database->query('insert into :table_specials (products_id, specials_new_products_price, specials_date_added, expires_date, start_date, status) values (:products_id, :specials_new_products_price, now(), :expires_date, :start_date, :status)');
        $Qspecial->bindInt(':products_id', $data['products_id']);
      }

      $Qspecial->bindTable(':table_specials', TABLE_SPECIALS);
      $Qspecial->bindValue(':specials_new_products_price', $specials_price);
      $Qspecial->bindDate(':expires_date', (strstr($data['specials_expires_date'], '/')) ? lC_DateTime::toDateTime($data['specials_expires_date']) : $data['specials_expires_date']);
      $Qspecial->bindDate(':start_date', (strstr($data['specials_start_date'], '/')) ? lC_DateTime::toDateTime($data['specials_start_date']) : $data['specials_start_date']);
      $Qspecial->bindInt(':status', $data['specials_status']);
      $Qspecial->setLogging($_SESSION['module'], $id);
      $Qspecial->execute();

      if ( $lC_Database->isError() ) {
        $result['rpcStatus'] = -3;
      }
    }

    return $result;*/
  }
 /*
  * Delete the coupons record
  *
  * @param integer $id The coupons id to delete
  * @access public
  * @return boolean
  */
  public static function delete($id) {
    /*global $lC_Database;

    $Qspecial = $lC_Database->query('delete from :table_specials where specials_id = :specials_id');
    $Qspecial->bindTable(':table_specials', TABLE_SPECIALS);
    $Qspecial->bindInt(':specials_id', $id);
    $Qspecial->setLogging($_SESSION['module'], $id);
    $Qspecial->execute();

    if ( !$lC_Database->isError() ) {
      return true;
    }

    return false;*/
  }
 /*
  * Batch delete coupons records
  *
  * @param array $batch The coupons id's to delete
  * @access public
  * @return boolean
  */
  public static function batchDelete($batch) {
    /*foreach ( $batch as $id ) {
      lC_Specials_Admin::delete($id);
    }
    return true;*/
  }
 /*
  * Return the tax rate for a coupon
  *
  * @param integer $id The product id
  * @access public
  * @return array
  */
  public static function getTax($id) {
    /*global $lC_Database, $lC_Tax, $lC_Vqmod;

    require_once($lC_Vqmod->modCheck('includes/classes/tax.php'));
    $lC_Tax = new lC_Tax_Admin();

    $result = array();
    $Qspecials = $lC_Database->query('select products_tax_class_id from :table_products where products_id = :products_id');
    $Qspecials->bindTable(':table_products', TABLE_PRODUCTS);
    $Qspecials->bindInt(':products_id', $id);
    $Qspecials->execute();

    $result['taxClassId'] = $Qspecials->valueInt('products_tax_class_id');
    $result['taxClassRate'] = $lC_Tax->getTaxRate($Qspecials->valueInt('products_tax_class_id'));

    $Qspecials->freeResult();

    return $result;*/
  }
}
?>