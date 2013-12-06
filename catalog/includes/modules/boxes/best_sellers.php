<?php
/**
  @package    catalog::modules::boxes
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: best_sellers.php v1.0 2013-08-08 datazen $
*/
class lC_Boxes_best_sellers extends lC_Modules {
  var $_title,
  $_code = 'best_sellers',
  $_author_name = 'Loaded Commerce',
  $_author_www = 'http://www.loadedcommerce.com',
  $_group = 'boxes';

  public function lC_Boxes_best_sellers() {
    global $lC_Language;

    if (function_exists($lC_Language->injectDefinitions)) $lC_Language->injectDefinitions('modules/' . $_GET['set'] . '/' . $this->_code . '.xml');

    $this->_title = $lC_Language->get('box_best_sellers_heading');
  }

  public function initialize() {
    global $lC_Database, $lC_Language, $current_category_id;

    if (isset($current_category_id) && ($current_category_id > 0)) {
      $Qbestsellers = $lC_Database->query('select distinct p.products_id, pd.products_name, pd.products_keyword from :table_products p, :table_products_description pd, :table_products_to_categories p2c, :table_categories c where p.products_status = 1 and p.products_ordered > 0 and p.products_id = pd.products_id and pd.language_id = :language_id and p.products_id = p2c.products_id and p2c.categories_id = c.categories_id and :current_category_id in (c.categories_id, c.parent_id) order by p.products_ordered desc, pd.products_name limit :max_display_bestsellers');
      $Qbestsellers->bindTable(':table_products', TABLE_PRODUCTS);
      $Qbestsellers->bindTable(':table_products_description', TABLE_PRODUCTS_DESCRIPTION);
      $Qbestsellers->bindTable(':table_products_to_categories', TABLE_PRODUCTS_TO_CATEGORIES);
      $Qbestsellers->bindTable(':table_categories', TABLE_CATEGORIES);
      $Qbestsellers->bindInt(':language_id', $lC_Language->getID());
      $Qbestsellers->bindInt(':current_category_id', $current_category_id);
      $Qbestsellers->bindInt(':max_display_bestsellers', BOX_BEST_SELLERS_MAX_LIST);

      if (BOX_BEST_SELLERS_CACHE > 0) {
        $Qbestsellers->setCache('box_best_sellers-' . $current_category_id . '-' . $lC_Language->getCode(), BOX_BEST_SELLERS_CACHE);
      }

      $Qbestsellers->execute();
    } else {
      $Qbestsellers = $lC_Database->query('select p.products_id, pd.products_name, pd.products_keyword from :table_products p, :table_products_description pd where p.products_status = 1 and p.products_ordered > 0 and p.products_id = pd.products_id and pd.language_id = :language_id order by p.products_ordered desc, pd.products_name limit :max_display_bestsellers');
      $Qbestsellers->bindTable(':table_products', TABLE_PRODUCTS);
      $Qbestsellers->bindTable(':table_products_description', TABLE_PRODUCTS_DESCRIPTION);
      $Qbestsellers->bindInt(':language_id', $lC_Language->getID());
      $Qbestsellers->bindInt(':max_display_bestsellers', BOX_BEST_SELLERS_MAX_LIST);

      if (BOX_BEST_SELLERS_CACHE > 0) {
        $Qbestsellers->setCache('box_best_sellers-0-' . $lC_Language->getCode(), BOX_BEST_SELLERS_CACHE);
      }

      $Qbestsellers->execute();
    }

    if ($Qbestsellers->numberOfRows() >= BOX_BEST_SELLERS_MIN_LIST) {

      $this->_content = '';
      while ($Qbestsellers->next()) {
        $this->_content .= '<li class="box-best-sellers-name">' . lc_link_object(lc_href_link(FILENAME_PRODUCTS, $Qbestsellers->value('products_keyword')), $Qbestsellers->value('products_name')) . '</li>';
      }
    }

    $Qbestsellers->freeResult();
  }

  public function install() {
    global $lC_Database;

    parent::install();

    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Minimum List Size', 'BOX_BEST_SELLERS_MIN_LIST', '3', 'Minimum amount of products that must be shown in the listing', '6', '0', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Maximum List Size', 'BOX_BEST_SELLERS_MAX_LIST', '10', 'Maximum amount of products to show in the listing', '6', '0', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Cache Contents', 'BOX_BEST_SELLERS_CACHE', '60', 'Number of minutes to keep the contents cached (0 = no cache)', '6', '0', now())");
  }

  public function getKeys() {
    if (!isset($this->_keys)) {
      $this->_keys = array('BOX_BEST_SELLERS_MIN_LIST',
                           'BOX_BEST_SELLERS_MAX_LIST',
                           'BOX_BEST_SELLERS_CACHE');
    }

    return $this->_keys;
  }
}
?>
