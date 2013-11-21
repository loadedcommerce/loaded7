<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: specials.php v1.0 2013-08-08 datazen $
*/
class lC_Specials_Admin {
 /*
  * Returns the specials datatable data for listings
  *
  * @access public
  * @return array
  */
  public static function getAll() {
    global $lC_Database, $lC_Language, $lC_Currencies, $_module;

    $media = $_GET['media'];
    
    $Qspecials = $lC_Database->query('select p.products_id, pd.products_name, p.products_price, s.specials_id, s.specials_new_products_price, s.specials_date_added, s.specials_last_modified, s.expires_date, s.date_status_change, s.status from :table_products p, :table_specials s, :table_products_description pd where p.products_id = pd.products_id and pd.language_id = :language_id and p.products_id = s.products_id order by pd.products_name');
    $Qspecials->bindTable(':table_specials', TABLE_SPECIALS);
    $Qspecials->bindTable(':table_products', TABLE_PRODUCTS);
    $Qspecials->bindTable(':table_products_description', TABLE_PRODUCTS_DESCRIPTION);
    $Qspecials->bindInt(':language_id', $lC_Language->getID());
    $Qspecials->execute();

    $result = array('aaData' => array());
    while ( $Qspecials->next() ) {
      $check = '<td><input class="batch" type="checkbox" name="batch[]" value="' . $Qspecials->valueInt('specials_id') . '" id="' . $Qspecials->valueInt('specials_id') . '"></td>';
      $product = '<td>' . $Qspecials->value('products_name') . '</td>';
      $price = '<td><s>' . $lC_Currencies->format($Qspecials->value('products_price')) . '</s>&nbsp;<font color="red">' . $lC_Currencies->format($Qspecials->value('specials_new_products_price')) . '</font></td>';
      $action = '<td class="align-right vertical-center"><span class="button-group compact">
                   <a href="' . ((int)($_SESSION['admin']['access'][$_module] < 3) ? '#' : 'javascript://" onclick="editSpecial(\'' . $Qspecials->valueInt('specials_id') . '\')') . '" class="button icon-pencil' . ((int)($_SESSION['admin']['access'][$_module] < 3) ? ' disabled' : NULL) . '">' . (($media === 'mobile-portrait' || $media === 'mobile-landscape') ? NULL : $lC_Language->get('icon_edit')) . '</a>
                   <a href="' . ((int)($_SESSION['admin']['access'][$_module] < 4) ? '#' : 'javascript://" onclick="deleteSpecial(\'' . $Qspecials->valueInt('specials_id') . '\', \'' . urlencode($Qspecials->valueProtected('products_name')) . '\')') . '" class="button icon-trash with-tooltip' . ((int)($_SESSION['admin']['access'][$_module] < 4) ? ' disabled' : NULL) . '" title="' . $lC_Language->get('icon_delete') . '"></a>
                 </span></td>';
      $result['aaData'][] = array("$check", "$product", "$price", "$action");
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
    global $lC_Database, $lC_Language, $lC_Currencies;

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

    return $result;
  }
 /*
  * Get the specials information
  *
  * @param integer $id The specials id
  * @access public
  * @return array
  */
  public static function getData($id) {
    global $lC_Database, $lC_Language, $lC_Currencies, $lC_DateTime;

    $Qspecial = $lC_Database->query('select p.products_id, pd.products_name, p.products_price, s.specials_id, s.specials_new_products_price, s.specials_date_added, s.specials_last_modified, s.start_date, s.expires_date, s.date_status_change, s.status from :table_products p, :table_specials s, :table_products_description pd where s.specials_id = :specials_id and s.products_id = p.products_id and p.products_id = pd.products_id and pd.language_id = :language_id limit 1');
    $Qspecial->bindTable(':table_specials', TABLE_SPECIALS);
    $Qspecial->bindTable(':table_products', TABLE_PRODUCTS);
    $Qspecial->bindTable(':table_products_description', TABLE_PRODUCTS_DESCRIPTION);
    $Qspecial->bindInt(':specials_id', $id);
    $Qspecial->bindInt(':language_id', $lC_Language->getID());
    $Qspecial->execute();

    $data = $Qspecial->toArray();

    $data['products_price_formatted'] = $lC_Currencies->format($Qspecial->value('products_price'));
    if ($Qspecial->value('start_date') != null) {
      $data['start_date_formatted'] = lC_DateTime::getShort($Qspecial->value('start_date'));
    }
    if ($Qspecial->value('expires_date') != null) {
      $data['expires_date_formatted'] = lC_DateTime::getShort($Qspecial->value('expires_date'));
    }

    $Qspecial->freeResult();

    return $data;
  }
 /*
  * Save the specials information
  *
  * @param integer $id The specials id used on update, null on insert
  * @param array $data An array containing the specials information
  * @access public
  * @return array
  */
  public static function save($id = null, $data) {
    global $lC_Database, $lC_DateTime;
    
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
    
    if ( $data['specials_expires_date'] < $data['specials_start_date'] ) {
      $result['rpcStatus'] = -2;
      $error = true;
    }
    
    if ( $error === false ) {
      if ( $id > 0 ) {
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
    return $result;
  }
 /*
  * Delete the specials record
  *
  * @param integer $id The specials id to delete
  * @access public
  * @return boolean
  */
  public static function delete($id) {
    global $lC_Database;

    $Qspecial = $lC_Database->query('delete from :table_specials where specials_id = :specials_id');
    $Qspecial->bindTable(':table_specials', TABLE_SPECIALS);
    $Qspecial->bindInt(':specials_id', $id);
    $Qspecial->setLogging($_SESSION['module'], $id);
    $Qspecial->execute();

    if ( !$lC_Database->isError() ) {
      return true;
    }

    return false;
  }
 /*
  * Batch delete specials records
  *
  * @param array $batch The specials id's to delete
  * @access public
  * @return boolean
  */
  public static function batchDelete($batch) {
    foreach ( $batch as $id ) {
      lC_Specials_Admin::delete($id);
    }
    return true;
  }
 /*
  * Return the tax rate for a product
  *
  * @param integer $id The product id
  * @access public
  * @return array
  */
  public static function getTax($id) {
    global $lC_Database, $lC_Tax, $lC_Vqmod;

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

    return $result;
  }
}
?>