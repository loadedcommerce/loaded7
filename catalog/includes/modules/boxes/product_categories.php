<?php
/**
  @package    catalog::modules::boxes
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: product_categories.php v1.0 2013-08-08 datazen $
*/
class lC_Boxes_product_categories extends lC_Modules {
  var $_title,
      $_code = 'product_categories',
      $_author_name = 'Loaded Commerce',
      $_author_www = 'http://www.loadedcommerce.com',
      $_group = 'boxes';

  function lC_Boxes_product_categories() {
    global $lC_Language;
    
    if (function_exists($lC_Language->injectDefinitions)) $lC_Language->injectDefinitions('modules/' . $_GET['set'] . '/' . $this->_code . '.xml');

    $this->_title = $lC_Language->get('box_product_categories_heading');
  }

  function initialize() {
    global $lC_CategoryTree, $cPath;
    
    $lC_CategoryTree->reset();
    if (BOX_PRODUCT_CATEGORIES_MAX_LEVEL == 'None') {
      $bcml = 1;
    } else if (BOX_PRODUCT_CATEGORIES_MAX_LEVEL == 'All') {
      $bcml = 0;
    } else if (BOX_PRODUCT_CATEGORIES_MAX_LEVEL == '1') {
      $bcml = 2;
    } else if (BOX_PRODUCT_CATEGORIES_MAX_LEVEL == '2') {
      $bcml = 3;
    } else if (BOX_PRODUCT_CATEGORIES_MAX_LEVEL == '3') {
      $bcml = 4;
    }    
    $lC_CategoryTree->setMaximumLevel($bcml);
    $lC_CategoryTree->setCategoryPath($cPath, '<span class="active-cpath">', '</span>');
    $lC_CategoryTree->setParentGroupStringTop('<ul class="box-product-categories-ul-top">', '</ul>');
    $lC_CategoryTree->setParentGroupString('<ul class="box-product-categories-ul">', '</ul>');
    $lC_CategoryTree->setChildStringWithChildren('<li>', '</li>');
    $lC_CategoryTree->setUseAria(true);
    $lC_CategoryTree->setShowCategoryProductCount((BOX_PRODUCT_CATEGORIES_SHOW_PRODUCT_COUNT == '1') ? true : false);
    $lC_CategoryTree->setRootCategoryID(BOX_PRODUCT_CATEGORIES_ROOT_CATEGORY);

    $this->_content = $lC_CategoryTree->getTree();
    
  }

  function install() {
    global $lC_Database;

    parent::install();

    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Show Product Count', 'BOX_PRODUCT_CATEGORIES_SHOW_PRODUCT_COUNT', '-1', 'Show the amount of products each category has', '6', '0', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) values ('Top Category', 'BOX_PRODUCT_CATEGORIES_ROOT_CATEGORY', 1, 'Select the Top Category of the Product Categories Infobox', 6, 0, now(), now(), 'lc_cfg_set_categories_top_category(BOX_PRODUCT_CATEGORIES_ROOT_CATEGORY)', 'lc_cfg_set_categories_top_category')");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) values ('Expand Menu Item', 'BOX_PRODUCT_CATEGORIES_MAX_LEVEL', 'All', 'How many levels to expand the category tree.', 6, 0, now(), now(), null, 'lc_cfg_set_boolean_value(array(''None'', ''1'', ''2'', ''3'', ''All''))')");
  }

  function getKeys() {
    if (!isset($this->_keys)) {
      $this->_keys = array('BOX_PRODUCT_CATEGORIES_SHOW_PRODUCT_COUNT',
                           'BOX_PRODUCT_CATEGORIES_ROOT_CATEGORY',
                           'BOX_PRODUCT_CATEGORIES_MAX_LEVEL');
    }

    return $this->_keys;
  }
}
?>