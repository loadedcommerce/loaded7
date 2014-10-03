<?php
/**
  @package    catalog::modules::services
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: category_path.php v1.0 2013-08-08 datazen $
*/
class lC_Services_category_path {

  function start() {
    global $lC_CategoryTree, $lC_Vqmod;

    lC_Services_category_path::process();

    include_once($lC_Vqmod->modCheck('includes/classes/category_tree.php'));
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