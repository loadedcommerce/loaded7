<?php
/**
  @package    catalog::modules::content
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: mainpage_banner.php v1.0 2013-08-08 Kiran $
*/
class lC_Content_mainpage_categories extends lC_Modules {
 /* 
  * Public variables 
  */  
  public $_title,
         $_code = 'mainpage_categories',
         $_author_name = 'Loaded Commerce',
         $_author_www = 'http://www.loadedcommerce.com',
         $_group = 'content';
 /* 
  * Class constructor 
  */
  public function __construct() {
    global $lC_Language;           

    if (function_exists($lC_Language->injectDefinitions)) $lC_Language->injectDefinitions('modules/' . $_GET['set'] . '/' . $this->_code . '.xml');      
    
    $this->_title = $lC_Language->get('mainpage_categories_title');
  }
 /*
  * Returns the also puchased HTML
  *
  * @access public
  * @return string
  */
  public function initialize() {
	global $lC_Database, $lC_Language, $lC_CategoryTree;  
      
      $Qcategories = $lC_Database->query('select c.categories_id, cd.categories_name, c.categories_image, c.parent_id, c.categories_mode, c.categories_link_target, c.categories_custom_url from :table_categories c, :table_categories_description cd where c.parent_id = :parent_id and c.categories_id = cd.categories_id and cd.language_id = :language_id and c.categories_status = 1 and c.categories_visibility_box = 1 order by sort_order, cd.categories_name');
      $Qcategories->bindTable(':table_categories', TABLE_CATEGORIES);
      $Qcategories->bindTable(':table_categories_description', TABLE_CATEGORIES_DESCRIPTION);
      $Qcategories->bindInt(':parent_id', 0);
      $Qcategories->bindInt(':language_id', $lC_Language->getID());
      $Qcategories->execute();
      
      $number_of_categories = $Qcategories->numberOfRows();
      $rows = 0;
      $output = '';
      while ($Qcategories->next()) {
        
        $url = ($Qcategories->value('categories_custom_url') != null) ? $Qcategories->value('categories_custom_url') : FILENAME_DEFAULT . '?cPath=' . $lC_CategoryTree->buildBreadcrumb($Qcategories->valueInt('categories_id'));
        $image = ($Qcategories->value('categories_image') != null) ? $Qcategories->value('categories_image') : 'no_image.png';

        $output .= '<div class="content-categories-container">' . "\n";
        if (file_exists(DIR_WS_IMAGES . 'categories/' . $image)) {
          $output .=  '  <div class="content-categories-image">' . lc_link_object(lc_href_link($url), lc_image(DIR_WS_IMAGES . 'categories/' . $image, $Qcategories->value('categories_name'), null, null, 'class="content-categories-image-src padding-top"')) . '</div>' . "\n";
        }
        $output .= '  <div class="content-categories-name">' . lc_link_object(lc_href_link($url), $Qcategories->value('categories_name'))  . '</div>' . "\n" . '</div>' . "\n";
      }
      $this->_content = $output;
  }

 /*
  * Install the module
  *
  * @access public
  * @return void
  */
  public function install() {
    global $lC_Database;

    parent::install();

  

  }
 /*
  * Return the module keys
  *
  * @access public
  * @return array
  */
  public function getKeys() {

    return $this->_keys;
  }
}
?>