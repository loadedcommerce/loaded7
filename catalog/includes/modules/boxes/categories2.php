<?php
/*
  $Id: categories2.php v1.0 2013-01-01 maestro $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

  class lC_Boxes_categories2 extends lC_Modules {
    var $_title,
        $_code = 'categories2',
        $_author_name = 'Loaded Commerce',
        $_author_www = 'http://www.loadedcommerce.com',
        $_group = 'boxes';

    function lC_Boxes_categories2() {
      global $lC_Language;
      
      if (function_exists($lC_Language->injectDefinitions)) $lC_Language->injectDefinitions('modules/' . $_GET['set'] . '/' . $this->_code . '.xml');
      
      $this->_title = $lC_Language->get('box_categories_heading2');
    }

    function initialize() {
      global $lC_CategoryTree, $cPath;

      $lC_CategoryTree->reset();
      // added to control maximum level of categories infobox if desired :: maestro
      if (isset($_SESSION['setCategoriesMaximumLevel']) && $_SESSION['setCategoriesMaximumLevel'] != '') {
        $lC_CategoryTree->setMaximumLevel($_SESSION['setCategoriesMaximumLevel']);
      }
      $lC_CategoryTree->setCategoryPath($cPath, '', '');
      $lC_CategoryTree->setParentGroupStringTop('<ul id="top_categories" class="top-categories">', '</ul>');
      $lC_CategoryTree->setParentGroupString('<ul class="categories-sub">', '</ul>');
      $lC_CategoryTree->setChildStringWithChildren('<li class="categories-li">', '</li>');
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
  }
?>