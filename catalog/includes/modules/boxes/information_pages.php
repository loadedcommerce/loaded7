<?php
/**
  @package    catalog::modules::boxes
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: information_pages.php v1.0 2013-08-08 datazen $
*/
class lC_Boxes_information_pages extends lC_Modules {
  var $_title,
      $_code = 'information_pages',
      $_author_name = 'Loaded Commerce',
      $_author_www = 'http://www.loadedcommerce.com',
      $_group = 'boxes';

  function lC_Boxes_information_pages() {
    global $lC_Language;
    
    if (function_exists($lC_Language->injectDefinitions)) $lC_Language->injectDefinitions('modules/' . $_GET['set'] . '/' . $this->_code . '.xml');

    $this->_title = $lC_Language->get('box_information_pages_heading');
  }

  function initialize() {
    global $lC_CategoryTree, $cPath;
    
    $lC_CategoryTree->reset();
    if (BOX_INFORMATION_PAGES_MAX_LEVEL == 'None') {
      $biml = 1;
    } else if (BOX_INFORMATION_PAGES_MAX_LEVEL == 'All') {
      $biml = 0;
    } else if (BOX_INFORMATION_PAGES_MAX_LEVEL == '1') {
      $biml = 2;
    } else if (BOX_INFORMATION_PAGES_MAX_LEVEL == '2') {
      $biml = 3;
    } else if (BOX_INFORMATION_PAGES_MAX_LEVEL == '3') {
      $biml = 4;
    }    
    $lC_CategoryTree->setMaximumLevel($biml);
    $lC_CategoryTree->setCategoryPath($cPath, '<span class="active-cpath">', '</span>');
    $lC_CategoryTree->setParentGroupStringTop('<ul class="box-information_pages-ul-top">', '</ul>');
    $lC_CategoryTree->setParentGroupString('<ul class="box-information_pages-ul">', '</ul>');
    $lC_CategoryTree->setChildStringWithChildren('<li>', '</li>');
    $lC_CategoryTree->setUseAria(true);
    $lC_CategoryTree->setShowCategoryProductCount(false);
    $lC_CategoryTree->setRootCategoryID(BOX_INFORMATION_PAGES_ROOT_CATEGORY);

    $this->_content = $lC_CategoryTree->getTree();
    
  }

  function install() {
    global $lC_Database;

    parent::install();

    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) values ('Top Information Category', 'BOX_INFORMATION_PAGES_ROOT_CATEGORY', 2, 'Select the Top Category of the Information Pages Infobox', 6, 0, now(), now(), 'lc_cfg_set_info_pages_top_category(BOX_INFORMATION_PAGES_ROOT_CATEGORY)', 'lc_cfg_set_info_pages_top_category')");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) values ('Expand Menu Item', 'BOX_INFORMATION_PAGES_MAX_LEVEL', 'All', 'How many levels to expand the information pages tree.', 6, 0, now(), now(), null, 'lc_cfg_set_boolean_value(array(''None'', ''1'', ''2'', ''3'', ''All''))')");
  }

  function getKeys() {
    if (!isset($this->_keys)) {
      $this->_keys = array('BOX_INFORMATION_PAGES_ROOT_CATEGORY',
                           'BOX_INFORMATION_PAGES_MAX_LEVEL' );
    }

    return $this->_keys;
  }
}
?>