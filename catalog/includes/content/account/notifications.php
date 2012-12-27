<?php
/*
  $Id: notifications.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

  class lC_Account_Notifications extends lC_Template {

    /* Private variables */
    var $_module = 'notifications',
        $_group = 'account',
        $_page_title,
        $_page_contents = 'account_notifications.php',
        $_page_image = 'table_background_account.gif';

    /* Class constructor */
    function lC_Account_Notifications() {
      global $lC_Language, $lC_Services, $lC_Breadcrumb, $lC_Database, $lC_Customer, $Qglobal;

      $this->_page_title = $lC_Language->get('notifications_heading');

      if ($lC_Services->isStarted('breadcrumb')) {
        $lC_Breadcrumb->add($lC_Language->get('breadcrumb_notifications'), lc_href_link(FILENAME_ACCOUNT, $this->_module, 'SSL'));
      }

      //#### Should be moved to the customers class!
      $Qglobal = $lC_Database->query('select global_product_notifications from :table_customers where customers_id = :customers_id');
      $Qglobal->bindTable(':table_customers', TABLE_CUSTOMERS);
      $Qglobal->bindInt(':customers_id', $lC_Customer->getID());
      $Qglobal->execute();

      if ($_GET[$this->_module] == 'save') {
        $this->_process();
      }
    }

    /* Public methods */
    function &getListing() {
      global $lC_Database, $lC_Session, $lC_Customer, $lC_Language;

      $Qproducts = $lC_Database->query('select pd.products_id, pd.products_name from :table_products_description pd, :table_products_notifications pn where pn.customers_id = :customers_id and pn.products_id = pd.products_id and pd.language_id = :language_id order by pd.products_name');
      $Qproducts->bindTable(':table_products_description', TABLE_PRODUCTS_DESCRIPTION);
      $Qproducts->bindTable(':table_products_notifications', TABLE_PRODUCTS_NOTIFICATIONS);
      $Qproducts->bindInt(':customers_id', $lC_Customer->getID());
      $Qproducts->bindInt(':language_id', $lC_Language->getID());
      $Qproducts->execute();

      return $Qproducts;
    }

    function hasCustomerProductNotifications($id) {
      global $lC_Database;

      $Qcheck = $lC_Database->query('select count(*) as total from :table_products_notifications where customers_id = :customers_id');
      $Qcheck->bindTable(':table_products_notifications', TABLE_PRODUCTS_NOTIFICATIONS);
      $Qcheck->bindInt(':customers_id', $id);
      $Qcheck->execute();

      return ($Qcheck->valueInt('total') > 0);
    }

    /* Private methods */
    function _process() {
      global $lC_MessageStack, $lC_Database, $lC_Language, $lC_Customer, $Qglobal;

      $updated = false;

      if (isset($_POST['product_global']) && is_numeric($_POST['product_global'])) {
        $product_global = $_POST['product_global'];
      } else {
        $product_global = '0';
      }

      if (isset($_POST['products'])) {
        (array)$products = $_POST['products'];
      } else {
        $products = array();
      }

      if ($product_global != $Qglobal->valueInt('global_product_notifications')) {
        $product_global = (($Qglobal->valueInt('global_product_notifications') == '1') ? '0' : '1');

        $Qupdate = $lC_Database->query('update :table_customers set global_product_notifications = :global_product_notifications where customers_id = :customers_id');
        $Qupdate->bindTable(':table_customers', TABLE_CUSTOMERS);
        $Qupdate->bindInt(':global_product_notifications', $product_global);
        $Qupdate->bindInt(':customers_id', $lC_Customer->getID());
        $Qupdate->execute();

        if ($Qupdate->affectedRows() == 1) {
          $updated = true;
        }
      } elseif (sizeof($products) > 0) {
        $products_parsed = array_filter($products, 'is_numeric');

        if (sizeof($products_parsed) > 0) {
          $Qcheck = $lC_Database->query('select count(*) as total from :table_products_notifications where customers_id = :customers_id and products_id not in :products_id');
          $Qcheck->bindTable(':table_products_notifications', TABLE_PRODUCTS_NOTIFICATIONS);
          $Qcheck->bindInt(':customers_id', $lC_Customer->getID());
          $Qcheck->bindRaw(':products_id', '(' . implode(',', $products_parsed) . ')');
          $Qcheck->execute();

          if ($Qcheck->valueInt('total') > 0) {
            $Qdelete = $lC_Database->query('delete from :table_products_notifications where customers_id = :customers_id and products_id not in :products_id');
            $Qdelete->bindTable(':table_products_notifications', TABLE_PRODUCTS_NOTIFICATIONS);
            $Qdelete->bindInt(':customers_id', $lC_Customer->getID());
            $Qdelete->bindRaw(':products_id', '(' . implode(',', $products_parsed) . ')');
            $Qdelete->execute();

            if ($Qdelete->affectedRows() > 0) {
              $updated = true;
            }
          }
        }
      } else {
        $Qcheck = $lC_Database->query('select count(*) as total from :table_products_notifications where customers_id = :customers_id');
        $Qcheck->bindTable(':table_products_notifications', TABLE_PRODUCTS_NOTIFICATIONS);
        $Qcheck->bindInt(':customers_id', $lC_Customer->getID());
        $Qcheck->execute();

        if ($Qcheck->valueInt('total') > 0) {
          $Qdelete = $lC_Database->query('delete from :table_products_notifications where customers_id = :customers_id');
          $Qdelete->bindTable(':table_products_notifications', TABLE_PRODUCTS_NOTIFICATIONS);
          $Qdelete->bindInt(':customers_id', $lC_Customer->getID());
          $Qdelete->execute();

          if ($Qdelete->affectedRows() > 0) {
            $updated = true;
          }
        }
      }

      if ($updated === true) {
        $lC_MessageStack->add('account', $lC_Language->get('success_notifications_updated'), 'success');
      }

      lc_redirect(lc_href_link(FILENAME_ACCOUNT, null, 'SSL'));
    }
  }
?>