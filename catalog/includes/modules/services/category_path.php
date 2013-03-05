<?php
/**
  $Id: category_path.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
class lC_Services_category_path {

  function start() {
    global $lC_CategoryTree, $lC_Vqmod;

    lC_Services_category_path::process();

    include($lC_Vqmod->modCheck('includes/classes/category_tree.php'));
    $lC_CategoryTree = new lC_CategoryTree();

    return true;
  }

  function process($id = null) {
    global $cPath, $cPath_array, $current_category_id, $lC_CategoryTree;

    $cPath = '';
    $cPath_array = array();
    $current_category_id = 0;

    if (isset($_GET['cPath'])) {
      $cPath = $_GET['cPath'];
    } elseif (!empty($id)) {
      $cPath = $lC_CategoryTree->buildBreadcrumb($id);
    }

    if (!empty($cPath)) {
      $cPath_array = array_unique(array_filter(explode('_', $cPath), 'is_numeric'));
      $cPath = implode('_', $cPath_array);
      $current_category_id = end($cPath_array);
    }
  }

  function stop() {
    return true;
  }
}
?>