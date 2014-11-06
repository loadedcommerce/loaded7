<?php
/**
  @package    catalog::modules::boxes
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: categories.php v1.0 2013-08-08 datazen $
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
    global $lC_CategoryTree, $cPath, $cPath_array, $lC_Services, $lC_Database, $lC_Language;
    
    // Added for support of box expansion when SEO URL's are enabled - START
    if ($_GET['cPath'] == '' && strpos($_SERVER['SCRIPT_NAME'], 'index.php') && isset($lC_Services) && $lC_Services->isStarted('seo')) {
      foreach ($_GET as $cats => $values) { 
        $fcat = end(explode("/", $cats));
        if (defined('SERVICE_SEO_URL_ADD_CATEGORY_PARENT') && SERVICE_SEO_URL_ADD_CATEGORY_PARENT == 1) {
          foreach ($fcat as $cat) {
            $Qcid = $lC_Database->query('select item_id from :table_permalinks where permalink = :permalink and type = 1 and language_id = :language_id');
            $Qcid->bindTable(':table_permalinks', TABLE_PERMALINKS);
            $Qcid->bindValue(':permalink', $cat);
            $Qcid->bindInt(':language_id', $lC_Language->getID());
            $Qcid->execute();
            
            $cPath_array[] = $Qcid->valueInt('item_id');
          }
          $cPath = implode("_", $cPath_array);
        } else {
          $Qcid = $lC_Database->query('select query from :table_permalinks where permalink = :permalink and type = 1 and language_id = :language_id');
          $Qcid->bindTable(':table_permalinks', TABLE_PERMALINKS);
          $Qcid->bindValue(':permalink', $cats);
          $Qcid->bindInt(':language_id', $lC_Language->getID());
          $Qcid->execute();
          
          $cPath = substr($Qcid->value('query'), 6);
        }
      }
    }
    // Added for support of box expansion when SEO URL's are enabled - END
    
    $lC_CategoryTree->reset();
    // added to control maximum level of categories infobox if desired
    if (isset($_SESSION['setCategoriesMaximumLevel']) && $_SESSION['setCategoriesMaximumLevel'] != '') {
      $lC_CategoryTree->setMaximumLevel($_SESSION['setCategoriesMaximumLevel']);
    }
    $lC_CategoryTree->setCategoryPath($cPath, '<span class="active-cpath">', '</span>');
    $lC_CategoryTree->setParentGroupStringTop('<ul class="box-categories-ul-top">', '</ul>');
    $lC_CategoryTree->setParentGroupString('<ul class="box-categories-ul">', '</ul>');
    $lC_CategoryTree->setChildStringWithChildren('<li>', '</li>');
    $lC_CategoryTree->setUseAria(true);
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