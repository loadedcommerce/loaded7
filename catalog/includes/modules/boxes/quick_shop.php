<?php
/*
  $Id: quick_shop.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

  class lC_Boxes_quick_shop extends lC_Modules {
    var $_title,
        $_code = 'quick_shop',
        $_author_name = 'LoadedCommerce',
        $_author_www = 'http://www.loadedcommerce.com',
        $_group = 'boxes';

    function lC_Boxes_quick_shop() {
      global $lC_Language;
      
      if (function_exists($lC_Language->injectDefinitions)) $lC_Language->injectDefinitions('modules/' . $_GET['set'] . '/' . $this->_code . '.xml');

      $this->_title = $lC_Language->get('box_quick_shop_heading');
    }

    function initialize() {
      global $lC_CategoryTree, $cPath;

      $lC_CategoryTree->reset();
      $lC_CategoryTree->setUseAria(true);
      $lC_CategoryTree->setShowCategoryProductCount((BOX_QUICK_SHOP_SHOW_PRODUCT_COUNT == '1') ? true : false);

      $this->_content .= $lC_CategoryTree->getTree();
    }

    function install() {
      global $lC_Database;

      parent::install();

      $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Show Product Count', 'BOX_QUICK_SHOP_SHOW_PRODUCT_COUNT', '1', 'Show the amount of products each category has', '6', '0', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))', now())");
    }

    function getKeys() {
      if (!isset($this->_keys)) {
        $this->_keys = array('BOX_QUICK_SHOP_SHOW_PRODUCT_COUNT');
      }

      return $this->_keys;
    }
  }
?>