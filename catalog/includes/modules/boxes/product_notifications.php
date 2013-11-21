<?php
/**
  @package    catalog::modules::boxes
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: product_notifications.php v1.0 2013-08-08 datazen $
*/
class lC_Boxes_product_notifications extends lC_Modules {
  var $_title,
      $_code = 'product_notifications',
      $_author_name = 'LoadedCommerce',
      $_author_www = 'http://www.loadedcommerce.com',
      $_group = 'boxes';

  public function lC_Boxes_product_notifications() {
    global $lC_Language;
    
    if (function_exists($lC_Language->injectDefinitions)) $lC_Language->injectDefinitions('modules/' . $_GET['set'] . '/' . $this->_code . '.xml');

    $this->_title = $lC_Language->get('box_product_notifications_heading');
  }

  public function initialize() {
    global $lC_Database, $lC_Language, $lC_Product, $lC_Customer;

    $this->_title_link = lc_href_link(FILENAME_ACCOUNT, 'notifications', 'SSL');

    if (isset($lC_Product) && is_a($lC_Product, 'lC_Product')) {
      if ($lC_Customer->isLoggedOn()) {
        $Qcheck = $lC_Database->query('select global_product_notifications from :table_customers where customers_id = :customers_id');
        $Qcheck->bindTable(':table_customers', TABLE_CUSTOMERS);
        $Qcheck->bindInt(':customers_id', $lC_Customer->getID());
        $Qcheck->execute();

        if ($Qcheck->valueInt('global_product_notifications') === 0) {
          $Qcheck = $lC_Database->query('select products_id from :table_products_notifications where products_id = :products_id and customers_id = :customers_id limit 1');
          $Qcheck->bindTable(':table_products_notifications', TABLE_PRODUCTS_NOTIFICATIONS);
          $Qcheck->bindInt(':products_id', $lC_Product->getID());
          $Qcheck->bindInt(':customers_id', $lC_Customer->getID());
          $Qcheck->execute();

          if ($Qcheck->numberOfRows() > 0) {
            $this->_content = '<li class="box-product-notifications-remove-image"><span class="box-product_notifications-image-container"></span>' . lc_link_object(lc_href_link(basename($_SERVER['SCRIPT_FILENAME']), lc_get_all_get_params(array('action')) . '&action=notify_remove', 'AUTO'), sprintf($lC_Language->get('box_product_notifications_remove'), $lC_Product->getTitle())) . '</li>';
          } else {
            $this->_content = '<li class="box-product-notifications-add-image"><span class="box-product_notifications-image-container"></span>' . lc_link_object(lc_href_link(basename($_SERVER['SCRIPT_FILENAME']), lc_get_all_get_params(array('action')) . '&action=notify_add', 'AUTO'), sprintf($lC_Language->get('box_product_notifications_add'), $lC_Product->getTitle())) . '</li>';
          }
        }
      }
    }
  }
}
?>