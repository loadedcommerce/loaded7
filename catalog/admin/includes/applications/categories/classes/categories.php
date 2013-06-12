<?php
/*
  $Id: categories.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
class lC_Categories_Admin {
 /*
  * Returns the categories datatable data for listings
  *
  * @access public
  * @return array
  */
  public static function getAll($id = null) {
    global $_module, $lC_Database, $lC_Language, $current_category_id;

    $media = $_GET['media'];
    
    $result = array('entries' => array());
    $result = array('aaData' => array());

    if ( !is_numeric($id) ) {
      if ( isset($current_category_id) && is_numeric($current_category_id) ) {
        $id = $current_category_id;
      } else {
        $id = 0;
      }
    }

    $Qcategories = $lC_Database->query('select c.*, cd.categories_name from :table_categories c, :table_categories_description cd where c.categories_id = cd.categories_id and cd.language_id = :language_id and c.parent_id = :parent_id order by c.sort_order, cd.categories_name');
    $Qcategories->bindTable(':table_categories', TABLE_CATEGORIES);
    $Qcategories->bindTable(':table_categories_description', TABLE_CATEGORIES_DESCRIPTION);
    $Qcategories->bindInt(':language_id', $lC_Language->getID());
    $Qcategories->bindInt(':parent_id', $id);
    $Qcategories->execute();

    while ( $Qcategories->next() ) {
      $check = '<td><input class="batch" type="checkbox" name="batch[]" value="' . $Qcategories->value('categories_id') . '" id="' . $Qcategories->value('categories_id') . '"></td>';
      $category = '<td><span class="icon-list icon-size2" title="' . $lC_Language->get('text_sort') . '" style="cursor:move;"></span><a href="' . lc_href_link_admin(FILENAME_DEFAULT, $_module . '=' . $Qcategories->value('categories_id')) . '"><span class="icon-' . lC_Categories_Admin::getCategoryIcon($Qcategories->value('categories_mode')) . ' margin-left"></span>&nbsp;' . $Qcategories->value('categories_name') . '</a></td>';
      $show = '<td><center id="show_in_listings_' . $Qcategories->value('categories_id') . '" onclick="updateShowInListings(\'' . $Qcategories->value('categories_id') . '\', \'' . (($Qcategories->value('categories_show_in_listings') == 1) ? 0 : 1) . '\');">' . (($Qcategories->valueInt('categories_show_in_listings') == 1) ? '<span class="icon-list icon-size2 icon-green cursor-pointer"></span>' : '<span class="icon-forbidden icon-size2 icon-red cursor-pointer"></span>') . '</center></td>';
      $mode = '<td>' . $lC_Language->get('text_mode_' . $Qcategories->value('categories_mode')) . '</td>';
      $sort = '<td>' . $Qcategories->valueInt('sort_order') . '<input type="hidden" name="sort_order_' . $Qcategories->value('categories_id') . '" value="' . $Qcategories->valueInt('sort_order') . '" class="sort" /></td>';
      $action = '<td class="align-right vertical-center"><span class="button-group compact" style="white-space:nowrap;">
                   <a href="' . ((int)($_SESSION['admin']['access'][$_module] < 3) ? '#' : lc_href_link_admin(FILENAME_DEFAULT, $_module . '=' . $Qcategories->value('categories_id') . '&cid=' . (($_GET['categories']) ? $_GET['categories'] : 0) . '&action=save')) . '" class="button icon-pencil' . ((int)($_SESSION['admin']['access'][$_module] < 3) ? ' disabled' : NULL) . '">' .  (($media === 'mobile-portrait' || $media === 'mobile-landscape') ? NULL : $lC_Language->get('icon_edit')) . '</a>
                   <a href="' . ((int)($_SESSION['admin']['access']['languages'] < 4) ? '#' : 'javascript://" onclick="moveCategory(\'' . $Qcategories->value('categories_id') . '\', \'' . urlencode($Qcategories->valueProtected('categories_name')) . '\')"') . '" class="button icon-cloud-upload with-tooltip ' . ((int)($_SESSION['admin']['access']['languages'] < 4) ? 'disabled' : NULL) . '" title="' . $lC_Language->get('icon_move') . '"></a>
                   <a href="' . ((int)($_SESSION['admin']['access']['languages'] < 4) ? '#' : 'javascript://" onclick="deleteCategory(\'' . $Qcategories->value('categories_id') . '\', \'' . urlencode($Qcategories->valueProtected('categories_name')) . '\')"') . '" class="button icon-trash with-tooltip ' . ((int)($_SESSION['admin']['access']['languages'] < 4) ? 'disabled' : NULL) . '" title="' . $lC_Language->get('icon_delete') . '"></a>
                 </span></td>';
      $result['aaData'][] = array("$check", "$category", "$show", "$mode", "$sort", "$action");
      $result['entries'][] = $Qcategories->toArray();
    }

    $Qcategories->freeResult();

    return $result;
  }
 /*
  * Returns the data used on the dialog forms
  *
  * @param integer $id The category id
  * @access public
  * @return array
  */
  public static function formData($id = null, $parent = null) {
    global $lC_Language, $_module;
    
    $lC_Language->loadIniFile('categories.php');
    $lC_CategoryTree = new lC_CategoryTree_Admin();
    
    $result = array();
    $categories_array = array('0' => $lC_Language->get('top_category'));
    foreach ( $lC_CategoryTree->getArray() as $value ) {
      $cid = explode('_', $value['id']);
      $count = count($cid);
      $cid = end($cid);
      if ($cid != $id && !in_array($cid, lC_Categories_Admin::getChildren($id))) {
        $categories_array[$cid] = str_repeat("&nbsp;&nbsp;&nbsp;&nbsp;", $count-1) . ' ' . $value['title'];
      }
    }
    $result['categoriesArray'] = $categories_array;

    if (isset($id) && is_numeric($id)) {
      $result['cData'] = lC_Categories_Admin::get($id, $lC_Language->getID());

      $result['categoryImage'] = '';
      $lC_ObjectInfo = new lC_ObjectInfo(lC_Categories_Admin::get($id));
      if ( !lc_empty($lC_ObjectInfo->get('categories_image')) ) {
        $result['categoryImage'] = '<div><p>' . lc_image('../' . DIR_WS_IMAGES . 'categories/' . $lC_ObjectInfo->get('categories_image'), $lC_ObjectInfo->get('categories_name'), HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT) . '<br />' . DIR_WS_CATALOG . 'images/categories/' . $lC_ObjectInfo->getProtected('categories_image') . '</p></div>';
      }
    }

    $category_names = '';
    foreach ( $lC_Language->getAll() as $l ) {
      if (isset($id) && is_numeric($id)) {
        $category_names .= '<span class="input" style="width:88%"><label for="categories_name[' . $l['id'] . ']" class="button silver-gradient glossy">' . $lC_Language->showImage($l['code']) . '</label>' . lc_draw_input_field('categories_name[' . $l['id'] . ']', $result['cData']['categories_name'], 'class="input-unstyled"') . '</span><br />';
      } else {
        $category_names .= '<span class="input" style="width:88%"><label for="categories_name[' . $l['id'] . ']" class="button silver-gradient glossy">' . $lC_Language->showImage($l['code']) . '</label>' . lc_draw_input_field('categories_name[' . $l['id'] . ']', null, 'class="input-unstyled"') . '</span><br />';
      }
    }
    $result['categoryNames'] = $category_names;

    $result['parentCategory'] = (isset($parent) && $parent != null) ? $parent : 0;

    return $result;
  }
 /*
  * Returns the category information
  *
  * @param integer $id The category id
  * @param integer $language_id The language id
  * @param integer $key The category key
  * @access public
  * @return array
  */
  public static function get($id, $language_id = null, $key = null) {
    global $lC_Database, $lC_Language;

    if ( empty($language_id) ) {
      $language_id = $lC_Language->getID();
    }

    $lC_CategoryTree = new lC_CategoryTree_Admin();

    $Qcategories = $lC_Database->query('select c.*, cd.* from :table_categories c, :table_categories_description cd where c.categories_id = :categories_id and c.categories_id = cd.categories_id and cd.language_id = :language_id');
    $Qcategories->bindTable(':table_categories', TABLE_CATEGORIES);
    $Qcategories->bindTable(':table_categories_description', TABLE_CATEGORIES_DESCRIPTION);
    $Qcategories->bindInt(':categories_id', $id);
    $Qcategories->bindInt(':language_id', $language_id);
    $Qcategories->execute();

    $data = $Qcategories->toArray();
    
    $data['childs_count'] = sizeof($lC_CategoryTree->getChildren($Qcategories->valueInt('categories_id'), $dummy = array()));
    $data['products_count'] = $lC_CategoryTree->getNumberOfProducts($Qcategories->valueInt('categories_id'));

    $Qcategories->freeResult();

    if ( !empty($key) && isset($data[$key]) ) {
      $data = $data[$key];
    }

    return $data;
  }
 /*
  * Save the category record
  *
  * @param integer $id The category id on update, null on insert
  * @param array $data The category information
  * @access public
  * @return boolean
  */
  public static function save($id = null, $data) {
    global $lC_Database, $lC_Language;

    $category_id = '';
    $error = false;

    $lC_Database->startTransaction();

    if ( is_numeric($id) ) {
      $Qcat = $lC_Database->query('update :table_categories set categories_image = :categories_image, parent_id = :parent_id, sort_order = :sort_order, categories_mode = :categories_mode, categories_link_target = :categories_link_target, categories_custom_url = :categories_custom_url, categories_show_in_listings = :categories_show_in_listings, last_modified = now() where categories_id = :categories_id');
      $Qcat->bindInt(':categories_id', $id);
    } else {
      $Qcat = $lC_Database->query('insert into :table_categories (categories_image, parent_id, sort_order, categories_mode, categories_link_target, categories_custom_url, categories_show_in_listings, date_added) values (:categories_image, :parent_id, :sort_order, :categories_mode, :categories_link_target, :categories_custom_url, :categories_show_in_listings, now())');
      $Qcat->bindInt(':parent_id', $data['parent_id']);
    }

    $Qcat->bindTable(':table_categories', TABLE_CATEGORIES);
    $Qcat->bindValue(':categories_image', $data['image']);
    $Qcat->bindInt(':parent_id', $data['parent_id']);
    $Qcat->bindInt(':sort_order', $data['sort_order']);
    $Qcat->bindValue(':categories_mode', $data['mode']);
    $Qcat->bindInt(':categories_link_target', $data['link_target']);
    $Qcat->bindValue(':categories_custom_url', $data['custom_url']);
    $Qcat->bindInt(':categories_show_in_listings', $data['show_in_listings']);
    $Qcat->setLogging($_SESSION['module'], $id);
    $Qcat->execute();
    
    if ( !$lC_Database->isError() ) {
      $category_id = (is_numeric($id)) ? $id : $lC_Database->nextID();

      foreach ( $lC_Language->getAll() as $l ) {
        if ( is_numeric($id) ) {
          $Qcd = $lC_Database->query('update :table_categories_description set categories_name = :categories_name, categories_menu_name = :categories_menu_name, categories_blurb = :categories_blurb, categories_description = :categories_description, categories_tags = :categories_tags where categories_id = :categories_id and language_id = :language_id');
        } else {
          $Qcd = $lC_Database->query('insert into :table_categories_description (categories_id, language_id, categories_name, categories_menu_name, categories_blurb, categories_description, categories_tags) values (:categories_id, :language_id, :categories_name, :categories_menu_name, :categories_blurb, :categories_description, :categories_tags)');
        }

        $Qcd->bindTable(':table_categories_description', TABLE_CATEGORIES_DESCRIPTION);
        $Qcd->bindInt(':categories_id', $category_id);
        $Qcd->bindInt(':language_id', $l['id']);
        $Qcd->bindValue(':categories_name', $data['name'][$l['id']]);
        $Qcd->bindValue(':categories_menu_name', $data['menu_name'][$l['id']]);
        $Qcd->bindValue(':categories_blurb', $data['blurb'][$l['id']]);
        $Qcd->bindValue(':categories_description', $data['description'][$l['id']]);
        $Qcd->bindValue(':categories_tags', $data['tags'][$l['id']]);
        $Qcd->setLogging($_SESSION['module'], $category_id);
        $Qcd->execute();

        if ( $lC_Database->isError() ) {
          $error = true;
          break;
        }
      }
    }

    if ( $error === false ) {
      $lC_Database->commitTransaction();

      lC_Cache::clear('categories');
      lC_Cache::clear('category_tree');
      lC_Cache::clear('also_purchased');

      return true;
    }

    $lC_Database->rollbackTransaction();

    return false;
  }
 /*
  * Delete the category record and associated children
  *
  * @param integer $id The category id to delete
  * @access public
  * @return boolean
  */
  public static function delete($id) {
    global $lC_Database;

    $lC_CategoryTree = new lC_CategoryTree_Admin();

    if ( is_numeric($id) ) {
      $lC_CategoryTree->setBreadcrumbUsage(false);

      $categories = array_merge(array(array('id' => $id, 'text' => '')), $lC_CategoryTree->getArray($id));
      $products = array();
      $products_delete = array();

      foreach ( $categories as $category ) {
        $Qproducts = $lC_Database->query('select products_id from :table_products_to_categories where categories_id = :categories_id');
        $Qproducts->bindTable(':table_products_to_categories', TABLE_PRODUCTS_TO_CATEGORIES);
        $Qproducts->bindInt(':categories_id', $category['id']);
        $Qproducts->execute();

        while ( $Qproducts->next() ) {
          $products[$Qproducts->valueInt('products_id')]['categories'][] = $category['id'];
        }
      }

      foreach ( $products as $key => $value ) {
        $Qcheck = $lC_Database->query('select categories_id from :table_products_to_categories where products_id = :products_id and categories_id not in :categories_id limit 1');
        $Qcheck->bindTable(':table_products_to_categories', TABLE_PRODUCTS_TO_CATEGORIES);
        $Qcheck->bindInt(':products_id', $key);
        $Qcheck->bindRaw(':categories_id', '("' . implode('", "', $value['categories']) . '")');
        $Qcheck->execute();

        if ( $Qcheck->numberOfRows() === 0 ) {
          $products_delete[$key] = $key;
        }
      }

      lc_set_time_limit(0);

      foreach ( $categories as $category) {
        $lC_Database->startTransaction();

        $Qimage = $lC_Database->query('select categories_image from :table_categories where categories_id = :categories_id');
        $Qimage->bindTable(':table_categories', TABLE_CATEGORIES);
        $Qimage->bindInt(':categories_id', $category['id']);
        $Qimage->execute();

        $Qc = $lC_Database->query('delete from :table_categories where categories_id = :categories_id');
        $Qc->bindTable(':table_categories', TABLE_CATEGORIES);
        $Qc->bindInt(':categories_id', $category['id']);
        $Qc->setLogging($_SESSION['module'], $id);
        $Qc->execute();

        if ( !$lC_Database->isError() ) {
          $Qcd = $lC_Database->query('delete from :table_categories_description where categories_id = :categories_id');
          $Qcd->bindTable(':table_categories_description', TABLE_CATEGORIES_DESCRIPTION);
          $Qcd->bindInt(':categories_id', $category['id']);
          $Qcd->setLogging($_SESSION['module'], $id);
          $Qcd->execute();

          if ( !$lC_Database->isError() ) {
            $Qp2c = $lC_Database->query('delete from :table_products_to_categories where categories_id = :categories_id');
            $Qp2c->bindTable(':table_products_to_categories', TABLE_PRODUCTS_TO_CATEGORIES);
            $Qp2c->bindInt(':categories_id', $category['id']);
            $Qp2c->setLogging($_SESSION['module'], $id);
            $Qp2c->execute();

            if ( !$lC_Database->isError() ) {
              $lC_Database->commitTransaction();

              lC_Cache::clear('categories');
              lC_Cache::clear('category_tree');
              lC_Cache::clear('also_purchased');

              if ( !lc_empty($Qimage->value('categories_image')) ) {
                $Qcheck = $lC_Database->query('select count(*) as total from :table_categories where categories_image = :categories_image');
                $Qcheck->bindTable(':table_categories', TABLE_CATEGORIES);
                $Qcheck->bindValue(':categories_image', $Qimage->value('categories_image'));
                $Qcheck->execute();

                if ( $Qcheck->numberOfRows() === 0 ) {
                  if (file_exists(realpath('../' . DIR_WS_IMAGES . 'categories/' . $Qimage->value('categories_image')))) {
                    @unlink(realpath('../' . DIR_WS_IMAGES . 'categories/' . $Qimage->value('categories_image')));
                  }
                }
              }
            } else {
              $lC_Database->rollbackTransaction();
            }
          } else {
            $lC_Database->rollbackTransaction();
          }
        } else {
          $lC_Database->rollbackTransaction();
        }
      }

      foreach ( $products_delete as $id ) {
        lC_Products_Admin::remove($id);
      }

      lC_Cache::clear('categories');
      lC_Cache::clear('category_tree');
      lC_Cache::clear('also_purchased');

      return true;
    }

    return false;
  }
 /*
  * Batch delete product categories
  *
  * @param array $batch An array of product category id's to delete
  * @access public
  * @return array
  */
  public static function batchDelete($batch) {
    foreach ( $batch as $id ) {
      lC_Categories_Admin::delete($id);
    }
    return true;
  }
 /*
  * Move a product category
  *
  * @param integer $id The current category id (moving from)
  * @param integer $new_id The new category id (moving to)
  * @access public
  * @return array
  */
  public static function move($id, $new_id) {
    global $lC_Database;

    $category_array = explode('_', $new_id);

    if ( in_array($id, $category_array)) {
      return false;
    }

    $Qupdate = $lC_Database->query('update :table_categories set parent_id = :parent_id, last_modified = now() where categories_id = :categories_id');
    $Qupdate->bindTable(':table_categories', TABLE_CATEGORIES);
    $Qupdate->bindInt(':parent_id', end($category_array));
    $Qupdate->bindInt(':categories_id', $id);
    $Qupdate->setLogging($_SESSION['module'], $id);
    $Qupdate->execute();

    lC_Cache::clear('categories');
    lC_Cache::clear('category_tree');
    lC_Cache::clear('also_purchased');

    return true;
  }
 /*
  * Batch move product categories
  *
  * @param array $batch An array of product category id's to move
  * @param integer $new_id The new category id (moving to)
  * @access public
  * @return array
  */
  public static function batchMove($batch, $new_id) {
    foreach ( $batch as $id ) {
      lC_Categories_Admin::move($id, $new_id);
    }
    return true;
  }
 /*
  * Get category parent ID
  *
  * @param integer $id The category id
  * @access public
  * @return integer
  */
  public static function getParent($id) {
    global $lC_Database;
    $Qcategories = $lC_Database->query('select c.parent_id from :table_categories c where c.categories_id = :categories_id');
    $Qcategories->bindTable(':table_categories', TABLE_CATEGORIES);
    $Qcategories->bindInt(':categories_id', $id);
    $Qcategories->execute();

    $parentID = $Qcategories->value('parent_id');

    $Qcategories->freeResult();

    return $parentID;
  }
 /*
  * Get final category parent ID
  *
  * @param integer $id The final parent category id
  * @access public
  * @return integer
  */
  public static function get_final_parent($id = 0) {
    global $lC_Database;
    $loop = true;
    while ($loop === true) {
      $Qpath = $lC_Database->query('select parent_id from :table_categories where categories_id = :categories_id');
      $Qpath->bindTable(':table_categories', TABLE_CATEGORIES);
      $Qpath->bindInt(':categories_id', $id);
      $Qpath->execute();
      if ($Qpath->value('parent_id') > 0) {
        $cPath .= '_' . $Qpath->value('parent_id');
        $id = $Qpath->value('parent_id');
        continue;
      }
      $Qpath->freeResult();
      $loop = false;
      break;
    }
    $cPath = array_reverse(explode("_", $cPath));
    return $cPath[0];
  }
  
  public static function getChild($id){
    global $lC_Database;
    
    $Qchild = $lC_Database->query('select categories_id from :table_categories where parent_id = :parent_id');
    $Qchild->bindTable(':table_categories', TABLE_CATEGORIES);
    $Qchild->bindInt(':parent_id', $id);
    $Qchild->execute();
    
    $cat_id = array();
    if ($Qchild->numberOfRows !== 0) {
      $cat_id[] = $Qchild->value('categories_id');
    }
    
    return $cat_id;
  }

  public static function getChildren($parent_id) {
    $tree = array();
    
    if (!empty($parent_id)) {
      $tree = lC_Categories_Admin::getChild($parent_id);
      
      foreach ($tree as $val) {
        $ids = lC_Categories_Admin::getChildren($val);
        $tree = array_merge($tree, $ids);
      }
    }
    
    return $tree;
  }
 /*
  * Update category sorting
  * 
  * @access public
  * @return array
  */
  public static function cSort($data) {
    global $lC_Database;
    $cnt = 10;
    foreach ($_GET as $key => $value) {
      if ($key != 'categories' && $key != 'action' && $key != 'dataTable_length' && $key != 'selectAction' && $key != 'lCAdminID') { 
        $keyParts = explode('_', $key);
        
        $Qupdate = $lC_Database->query('update :table_categories set sort_order = :sort_order, last_modified = now() where categories_id = :categories_id');
        $Qupdate->bindTable(':table_categories', TABLE_CATEGORIES);
        $Qupdate->bindInt(':sort_order', $cnt);
        $Qupdate->bindInt(':categories_id', $keyParts[2]);
        $Qupdate->setLogging($_SESSION['module'], $id);
        $Qupdate->execute();
        $cnt = $cnt + 10;
      }
    }
    return true;
  }
 /*
  * get next category sort for new entry
  * 
  * @access public
  * @return int
  */
  public static function nextSort() {
    global $lC_Database;
    
    $Qupdate = $lC_Database->query('select sort_order from :table_categories where parent_id = :parent_id order by sort_order desc limit 1');
    $Qupdate->bindTable(':table_categories', TABLE_CATEGORIES);
    $Qupdate->bindInt(':parent_id', ($_GET['categories'] != '') ? $_GET['categories'] : 0);
    $Qupdate->execute();
    
    $nextsort = ($Qupdate->value('sort_order') + 10);

    $Qupdate->freeResult();

    return $nextsort;
  }
 /*
  * update category show in listings db entry
  * 
  * @access public
  * @return true or fales
  */
  public static function updateShowInListings($id, $val) {
    global $lC_Database;
    
    $Qupdate = $lC_Database->query('update :table_categories set categories_show_in_listings = :categories_show_in_listings where categories_id = :categories_id');
    $Qupdate->bindTable(':table_categories', TABLE_CATEGORIES);
    $Qupdate->bindInt(':categories_show_in_listings', $val);
    $Qupdate->bindInt(':categories_id', $id);
    $Qupdate->execute();

    return true;
  }
 /*
  * get category icon
  * 
  * @access public
  * @return string
  */
  public static function getCategoryIcon($type) {
    
    if ($type == 'category') {
      $icon = 'folder icon-orange';
    } else if ($type == 'page') {
      $icon = 'page-list icon-black';
    } else if ($type == 'specials') {
      $icon = 'price-tag icon-red';
    } else if ($type == 'featured') {
      $icon = 'star icon-orange';
    } else if ($type == 'new') {
      $icon = 'new icon-green';
    } else if ($type == 'search') {
      $icon = 'search icon-black';
    } else if ($type == 'cart') {
      $icon = 'bag icon-orange';
    } else if ($type == 'account') {
      $icon = 'user icon-anthracite';
    } else if ($type == 'info') {
      $icon = 'question-round icon-blue';
    } else if ($type == 'override') {
      $icon = 'globe icon-blue';
    }

    return $icon;
  }
                          
}
?>