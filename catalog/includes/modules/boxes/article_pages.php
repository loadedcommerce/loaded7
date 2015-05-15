<?php
/**
  @package    catalog::modules::boxes
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: article_pages.php v1.0 2013-08-08 datazen $
*/
class lC_Boxes_article_pages extends lC_Modules {
  var $_title,
      $_code = 'article_pages',
      $_author_name = 'Loaded Commerce',
      $_author_www = 'http://www.loadedcommerce.com',
      $_group = 'boxes';

  function lC_Boxes_article_pages() {
    global $lC_Language;
    
    if (function_exists($lC_Language->injectDefinitions)) $lC_Language->injectDefinitions('modules/' . $_GET['set'] . '/' . $this->_code . '.xml');

    $this->_title = $lC_Language->get('box_article_pages_heading');
  }

  function initialize() {
    global $lC_CategoryTree, $cPath;
    
    $lC_CategoryTree->reset();
    if (BOX_ARTICLE_PAGES_MAX_LEVEL == 'None') {
      $baml = 1;
    } else if (BOX_ARTICLE_PAGES_MAX_LEVEL == 'All') {
      $baml = 0;
    } else if (BOX_ARTICLE_PAGES_MAX_LEVEL == '1') {
      $baml = 2;
    } else if (BOX_ARTICLE_PAGES_MAX_LEVEL == '2') {
      $baml = 3;
    } else if (BOX_ARTICLE_PAGES_MAX_LEVEL == '3') {
      $baml = 4;
    }    
    $lC_CategoryTree->setMaximumLevel($baml);
    $lC_CategoryTree->setCategoryPath($cPath, '<span class="active-cpath">', '</span>');
    $lC_CategoryTree->setParentGroupStringTop('<ul class="box-article_pages-ul-top">', '</ul>');
    $lC_CategoryTree->setParentGroupString('<ul class="box-article_pages-ul">', '</ul>');
    $lC_CategoryTree->setChildStringWithChildren('<li>', '</li>');
    $lC_CategoryTree->setUseAria(true);
    $lC_CategoryTree->setShowCategoryProductCount(false);
    $lC_CategoryTree->setRootCategoryID(BOX_ARTICLE_PAGES_ROOT_CATEGORY);

    $this->_content = $lC_CategoryTree->getTree();
    
  }

  function install() {
    global $lC_Database;

    parent::install();

    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) values ('Top Article Category', 'BOX_ARTICLE_PAGES_ROOT_CATEGORY', 2, 'Select the Top Category of the Article Pages Infobox', 6, 0, now(), now(), 'lc_cfg_set_article_pages_top_category(BOX_ARTICLE_PAGES_ROOT_CATEGORY)', 'lc_cfg_set_article_pages_top_category')");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) values ('Expand Menu Item', 'BOX_ARTICLE_PAGES_MAX_LEVEL', 'All', 'How many levels to expand the article pages tree.', 6, 0, now(), now(), null, 'lc_cfg_set_boolean_value(array(''None'', ''1'', ''2'', ''3'', ''All''))')");
  }

  function getKeys() {
    if (!isset($this->_keys)) {
      $this->_keys = array('BOX_ARTICLE_PAGES_ROOT_CATEGORY',
                           'BOX_ARTICLE_PAGES_MAX_LEVEL' );
    }

    return $this->_keys;
  }
}
?>