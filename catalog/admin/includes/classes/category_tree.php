<?php
/**
  $Id: category_tree.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
global $lC_Vqmod;

require($lC_Vqmod->modCheck('../includes/classes/category_tree.php'));

class lC_CategoryTree_Admin extends lC_CategoryTree {
  protected $_show_total_products = true;

  function __constructor() {
    global $lC_Database, $lC_Language;

    $Qcategories = $lC_Database->query('select c.categories_id, c.parent_id, c.categories_image, cd.categories_name from :table_categories c, :table_categories_description cd where c.categories_id = cd.categories_id and cd.language_id = :language_id order by c.parent_id, c.sort_order, cd.categories_name');
    $Qcategories->bindTable(':table_categories', TABLE_CATEGORIES);
    $Qcategories->bindTable(':table_categories_description', TABLE_CATEGORIES_DESCRIPTION);
    $Qcategories->bindInt(':language_id', $lC_Language->getID());
    $Qcategories->execute();

    $this->_data = array();

    while ($Qcategories->next()) {
      $this->_data[$Qcategories->valueInt('parent_id')][$Qcategories->valueInt('categories_id')] = array('name' => $Qcategories->value('categories_name'), 'image' => $Qcategories->value('categories_image'), 'count' => 0);
    }

    $Qcategories->freeResult();

    if ($this->_show_total_products === true) {
      $this->_calculateProductTotals(false);
    }
  }

  function getPath($category_id, $level = 0, $separator = ' ') {
    $path = '';

    foreach ($this->_data as $parent => $categories) {
      foreach ($categories as $id => $info) {
        if ($id == $category_id) {
          if ($level < 1) {
            $path = $info['name'];
          } else {
            $path = $info['name'] . $separator . $path;
          }

          if ($parent != $this->root_category_id) {
            $path = $this->getPath($parent, $level+1, $separator) . $path;
          }
        }
      }
    }

    return $path;
  }

  function getPathArray($category_id) {
    static $path = array();

    foreach ( $this->_data as $parent => $categories ) {
      foreach ( $categories as $id => $info ) {
        if ( $id == $category_id ) {
          $path[] = array('id' => $id,
                          'name' => $info['name']);

          if ( $parent != $this->root_category_id ) {
            $this->getPathArray($parent);
          }
        }
      }
    }

    return array_reverse($path);
  }

  function getcPath($category_id) {
    
    $getCpathArray = $this->getPathArray($category_id);
    $cPath = '';
    foreach ($getCpathArray as $key => $value) {
      $cPath .= $value['id'] . '_';
    }
    
    return substr($cPath, 0, -1);
  }
}
?>