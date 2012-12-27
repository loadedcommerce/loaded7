<?php
/*
  $Id: order_history.php v1.0 2011-11-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

  class lC_Boxes_order_history extends lC_Modules {
    var $_title,
        $_code = 'order_history',
        $_author_name = 'LoadedCommerce',
        $_author_www = 'http://www.loadedcommerce.com',
        $_group = 'boxes';

    function lC_Boxes_order_history() {
      global $lC_Language;
      
      if (function_exists($lC_Language->injectDefinitions)) $lC_Language->injectDefinitions('modules/' . $_GET['set'] . '/' . $this->_code . '.xml');

      $this->_title = $lC_Language->get('box_order_history_heading');
    }

    function initialize() {
      global $lC_Database, $lC_Language, $lC_Customer;

      if ($lC_Customer->isLoggedOn()) {
        $Qorders = $lC_Database->query('select distinct op.products_id from :table_orders o, :table_orders_products op, :table_products p where o.customers_id = :customers_id and o.orders_id = op.orders_id and op.products_id = p.products_id and p.products_status = 1 group by products_id order by o.date_purchased desc limit :limit');
        $Qorders->bindTable(':table_orders', TABLE_ORDERS);
        $Qorders->bindTable(':table_orders_products', TABLE_ORDERS_PRODUCTS);
        $Qorders->bindTable(':table_products', TABLE_PRODUCTS);
        $Qorders->bindInt(':customers_id', $lC_Customer->getID());
        $Qorders->bindInt(':limit', BOX_ORDER_HISTORY_MAX_LIST);
        $Qorders->execute();

        if ($Qorders->numberOfRows()) {
          $product_ids = '';

          while ($Qorders->next()) {
            $product_ids .= $Qorders->valueInt('products_id') . ',';
          }

          $product_ids = substr($product_ids, 0, -1);
          $Qproducts = $lC_Database->query('select products_id, products_name, products_keyword from :table_products_description where products_id in (:products_id) and language_id = :language_id order by products_name');
          $Qproducts->bindTable(':table_products_description', TABLE_PRODUCTS_DESCRIPTION);
          $Qproducts->bindRaw(':products_id', $product_ids);
          $Qproducts->bindInt(':language_id', $lC_Language->getID());
          $Qproducts->execute();

          $this->_content = '<ul class="category departments">';

          while ($Qproducts->next()) {
            $this->_content .= '<li>' . lc_link_object(lc_href_link(FILENAME_PRODUCTS, $Qproducts->value('products_keyword')), $Qproducts->value('products_name')) . '</li>';
          }

          $this->_content .= '</ul>';
        }
      }
    }

    function install() {
      global $lC_Database;

      parent::install();

      $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Maximum List Size', 'BOX_ORDER_HISTORY_MAX_LIST', '5', 'Maximum amount of products to show in the listing', '6', '0', now())");
    }

    function getKeys() {
      if (!isset($this->_keys)) {
        $this->_keys = array('BOX_ORDER_HISTORY_MAX_LIST');
      }

      return $this->_keys;
    }
  }
?>
