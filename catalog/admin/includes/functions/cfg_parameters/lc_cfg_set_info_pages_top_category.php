<?php
/**
  @package    admin::functions
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: lc_cfg_set_info_pages_top_category.php v1.0 2013-08-08 datazen $
*/
function lc_cfg_set_info_pages_top_category($default = 0, $key = null) {
  global $lC_Database, $lC_Language, $lC_Vqmod;
  
  include_once($lC_Vqmod->modCheck(DIR_FS_ADMIN . 'includes/classes/category_tree.php'));
  include_once($lC_Vqmod->modCheck(DIR_FS_ADMIN . 'includes/applications/categories/classes/categories.php'));
  
  $lC_Language->loadIniFile('categories.php');
  $lC_CategoryTree = new lC_CategoryTree_Admin();
    
  $categories = array('0' => $lC_Language->get('top_category'));
  foreach ( $lC_CategoryTree->getArray() as $value ) {
    // added switch for only category mode categories in selection dropdown.
    if ($value['mode'] == 'info_category') {
      $cid = explode('_', $value['id']);
      $count = count($cid);
      $cid = end($cid);
      $acArr = lC_Categories_Admin::getAllChildren($id);
      $categories[$cid] = str_repeat("&nbsp;&nbsp;&nbsp;&nbsp;", $count-1) . ' ' . $value['title'];
    }
  }
  
  $css_class = 'class="input with-small-padding mid-margin-top"';
  
  $name = (empty($key)) ? 'configuration_value' : 'configuration[' . $key . ']';

  $array = array();
  $array[] = array('id' => '',
                   'text' => $lC_Language->get('text_select_category'));
  foreach ( $categories as $key => $value ) {
    $array[] = array('id' => $key,
                     'text' => $value);
  }
  
  return lc_draw_pull_down_menu($name, $array, $default, $css_class);
}
?>