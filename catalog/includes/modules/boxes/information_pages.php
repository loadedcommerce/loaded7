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
    // added to control maximum level of information_pages infobox if desired
    if (isset($_SESSION['setInformationPagesMaximumLevel']) && $_SESSION['setInformationPagesMaximumLevel'] != '') {
      $lC_CategoryTree->setMaximumLevel(BOX_INFORMATION_MAX_LEVEL);
    }
    $lC_CategoryTree->setCategoryPath($cPath, '', '');
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
  }

  function getKeys() {
    if (!isset($this->_keys)) {
      $this->_keys = array('BOX_INFORMATION_PAGES_ROOT_CATEGORY');
    }

    return $this->_keys;
  }
}
?>