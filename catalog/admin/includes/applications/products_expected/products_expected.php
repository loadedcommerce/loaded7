<?php
/*
  $Id: products_expected.php v1.0 2011-11-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html

  @function The lC_Application_Products_expected class manages the products expected GUI
*/
class lC_Application_Products_expected extends lC_Template_Admin {
 /*
  * Protected variables
  */
  protected $_module = 'products_expected',
            $_page_title,
            $_page_contents = 'main.php';
 /*
  * Class constructor
  */
  function __construct() {
    global $lC_Database, $lC_Language;

    $this->_page_title = $lC_Language->get('heading_title');

    $Qcheck = $lC_Database->query('select pa.* from :table_product_attributes pa, :table_templates_boxes tb where tb.code = :code and tb.modules_group = :modules_group and tb.id = pa.id and unix_timestamp(now()) > unix_timestamp(str_to_date(pa.value, "%Y-%m-%d"))');
    $Qcheck->bindTable(':table_product_attributes', TABLE_PRODUCT_ATTRIBUTES);
    $Qcheck->bindTable(':table_templates_boxes', TABLE_TEMPLATES_BOXES);
    $Qcheck->bindValue(':code', 'date_available');
    $Qcheck->bindValue(':modules_group', 'product_attributes');
    $Qcheck->execute();

    if ($Qcheck->numberOfRows()) {
      $Qdelete = $lC_Database->query('delete from :table_product_attributes where id = :id and products_id = :products_id');
      $Qdelete->bindTable(':table_product_attributes', TABLE_PRODUCT_ATTRIBUTES);
      $Qdelete->bindInt(':id', $Qcheck->valueInt('id'));
      $Qdelete->bindInt(':products_id', $Qcheck->valueInt('products_id'));
      $Qdelete->execute();
    }
  }
}
?>