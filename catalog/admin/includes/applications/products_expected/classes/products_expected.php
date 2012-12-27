<?php
/*
  $Id: products expected.php v1.0 2011-11-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html

  @function The lC_Products_expected_Admin class manages products expected
*/
class lC_Products_expected_Admin {
 /*
  * Returns the products expected datatable data for listings
  *
  * @access public
  * @return array
  */
  public static function getAll() {
    global $lC_Database, $lC_Language;

    $media = $_GET['media'];
    
    $Qproducts = $lC_Database->query('select p.products_id, pd.products_name, str_to_date(pa.value, "%Y-%m-%d") as products_date_available from :table_products p, :table_products_description pd, :table_product_attributes pa, :table_templates_boxes tb where tb.code = :code and tb.modules_group = :modules_group and tb.id = pa.id and pa.products_id = p.products_id and p.products_id = pd.products_id and pd.language_id = :language_id order by products_date_available');
    $Qproducts->bindTable(':table_products', TABLE_PRODUCTS);
    $Qproducts->bindTable(':table_products_description', TABLE_PRODUCTS_DESCRIPTION);
    $Qproducts->bindTable(':table_product_attributes', TABLE_PRODUCT_ATTRIBUTES);
    $Qproducts->bindTable(':table_templates_boxes', TABLE_TEMPLATES_BOXES);
    $Qproducts->bindValue(':code', 'date_available');
    $Qproducts->bindValue(':modules_group', 'product_attributes');
    $Qproducts->bindInt(':language_id', $lC_Language->getID());
    $Qproducts->execute();

    $result = array('aaData' => array());
    while ( $Qproducts->next() ) {
      $product = '<td>' . $Qproducts->value('products_name') . '</td>';
      $date = '<td>' . lC_DateTime::getShort($Qproducts->value('products_date_available')) . '</td>';
      $action = '<td class="align-right vertical-center"><span class="button-group compact">
                   <a href="' . ((int)($_SESSION['admin']['access']['languages'] < 3) ? '#' : 'javascript://" onclick="editEntry(\'' . $Qproducts->valueInt('products_id') . '\')') . '" class="button icon-pencil' . ((int)($_SESSION['admin']['access']['languages'] < 3) ? ' disabled' : NULL) . '">' . (($media === 'mobile-portrait' || $media === 'mobile-landscape') ? NULL : $lC_Language->get('icon_edit')) . '</a>
                 </span></td>';
      $result['aaData'][] = array("$product", "$date", "$action");
    }

    $Qproducts->freeResult;

    return $result;
  }
 /*
  * Return the data used on the dialog forms
  *
  * @access public
  * @return array
  */
  public static function formData($id = null) {
    global $lC_Database, $lC_DateTime;

    $result = array();
    if (isset($id) && is_numeric($id)) {
      $lC_ObjectInfo = new lC_ObjectInfo(lC_Products_Admin::get($id));
      $Qdata = $lC_Database->query('select str_to_date(pa.value, "%Y-%m-%d") as products_date_available from :table_product_attributes pa, :table_templates_boxes tb where tb.code = :code and tb.modules_group = :modules_group and tb.id = pa.id');
      $Qdata->bindTable(':table_product_attributes', TABLE_PRODUCT_ATTRIBUTES);
      $Qdata->bindTable(':table_templates_boxes', TABLE_TEMPLATES_BOXES);
      $Qdata->bindValue(':code', 'date_available');
      $Qdata->bindValue(':modules_group', 'product_attributes');
      $Qdata->execute();
      $lC_ObjectInfo->set('products_date_available', $Qdata->value('products_date_available'));

      $result['pDate'] = lC_DateTime::getShort($lC_ObjectInfo->get('products_date_available'));

      $Qdata->freeResult();
    }

    return $result;
  }
 /*
  * Save the products expected information
  *
  * @param integer $id The products expected id used on update
  * @param array $data An array containing the products expected information
  * @access public
  * @return array
  */
  public static function save($id, $data) {
    global $lC_DateTime;

    $update = array('date_available' => (strstr($data['products_date_available'], '/')) ? lC_DateTime::toDateTime($data['products_date_available']) : $data['products_date_available']);

    if ( lC_Products_Admin::setDateAvailable($id, $update) ) {
      return true;
    } else {
      return false;
    }
  }
}
?>