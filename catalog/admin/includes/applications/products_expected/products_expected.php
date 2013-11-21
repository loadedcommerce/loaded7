<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: products_expected.php v1.0 2013-08-08 datazen $
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
  public function __construct() {
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