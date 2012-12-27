<?php
/*
  $Id: categories.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

  class lC_Boxes_categories extends lC_Modules {
    var $_title,
        $_code = 'categories',
        $_author_name = 'Loaded Commerce',
        $_author_www = 'http://www.loadedcommerce.com',
        $_group = 'boxes';

    function lC_Boxes_categories() {
      global $lC_Language;
      
      if (function_exists($lC_Language->injectDefinitions)) $lC_Language->injectDefinitions('modules/' . $_GET['set'] . '/' . $this->_code . '.xml');

      $this->_title = $lC_Language->get('box_categories_heading');
    }

    function initialize() {
      global $lC_CategoryTree, $cPath;

      $lC_CategoryTree->reset();
      // added to control maximum level of categories infobox if desired :: maestro
      if (isset($_SESSION['setCategoriesMaximumLevel']) && $_SESSION['setCategoriesMaximumLevel'] != '') {
        $lC_CategoryTree->setMaximumLevel($_SESSION['setCategoriesMaximumLevel']);
      }
      $lC_CategoryTree->setCategoryPath($cPath, '', '');
      $lC_CategoryTree->setParentGroupStringTop('<ul id="ul-top-categories" class="category departments">', '</ul>');
      $lC_CategoryTree->setParentGroupString('<ul class="side_sub_menu">', '</ul>');
      $lC_CategoryTree->setChildStringWithChildren('<li class="menu_cont">', '</li>');
      $lC_CategoryTree->setUseAria(true);
      
      // $lC_CategoryTree->setParentString('<ul class="side_sub_menu">', '</ul>');
      // $lC_CategoryTree->setChildString('<li class="menu_cont">', '');
      // $lC_CategoryTree->setParentString('', '');
      // $lC_CategoryTree->setChildString('',  '<br />');
      // $lC_CategoryTree->setSpacerString('&nbsp;', 4);
      // $lC_CategoryTree->setBulletString(lc_image(DIR_WS_TEMPLATE_IMAGES . 'bullet_4.png'). '&nbsp;&nbsp;');
      
      $lC_CategoryTree->setShowCategoryProductCount((BOX_CATEGORIES_SHOW_PRODUCT_COUNT == '1') ? true : false);

      $this->_content = $lC_CategoryTree->getTree();
      
    }

    function install() {
      global $lC_Database;

      parent::install();

      $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Show Product Count', 'BOX_CATEGORIES_SHOW_PRODUCT_COUNT', '1', 'Show the amount of products each category has', '6', '0', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))', now())");
    }

    function getKeys() {
      if (!isset($this->_keys)) {
        $this->_keys = array('BOX_CATEGORIES_SHOW_PRODUCT_COUNT');
      }

      return $this->_keys;
    }
  }
?>