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
      $lC_CategoryTree->setMaximumLevel($_SESSION['setInformationPagesMaximumLevel']);
    }
    $lC_CategoryTree->setCategoryPath($cPath, '', '');
    $lC_CategoryTree->setParentGroupStringTop('<ul class="box-information_pages-ul-top">', '</ul>');
    $lC_CategoryTree->setParentGroupString('<ul class="box-information_pages-ul">', '</ul>');
    $lC_CategoryTree->setChildStringWithChildren('<li>', '</li>');
    $lC_CategoryTree->setUseAria(true);
    $lC_CategoryTree->setShowCategoryProductCount((BOX_INFORMATION_PAGES_SHOW_PAGE_COUNT == '1') ? true : false);
    $lC_CategoryTree->setRootCategoryID(BOX_INFORMATION_PAGES_CPATH);

    $this->_content = $lC_CategoryTree->getTree();
    
  }

  function install() {
    global $lC_Database;

    parent::install();

    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Top Information Category ID (cPath)', 'BOX_INFORMATION_PAGES_CPATH', '0', 'Set the Category ID of the Information Pages \"Top\" Category', '6', '0', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Show Pages Count', 'BOX_INFORMATION_PAGES_SHOW_PAGE_COUNT', '-1', 'Show the amount of pages each category has', '6', '0', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))', now())");
  }

  function getKeys() {
    if (!isset($this->_keys)) {
      $this->_keys = array('BOX_INFORMATION_PAGES_CPATH', 'BOX_INFORMATION_PAGES_SHOW_PAGE_COUNT');
    }

    return $this->_keys;
  }
}
?>