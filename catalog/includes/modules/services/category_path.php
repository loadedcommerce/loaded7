<?php
/*
  $Id$

  LoadedCommerce, Open Source E-Commerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2007 LoadedCommerce

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/

  class lC_Services_category_path {
    function start() {
      global $lC_CategoryTree;

      lC_Services_category_path::process();

      include('includes/classes/category_tree.php');
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
