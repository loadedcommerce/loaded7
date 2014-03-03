<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: categories.php v1.0 2013-08-08 datazen $
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
      $category = '<td><span class="icon-list icon-size2 dragsort" title="' . $lC_Language->get('text_sort') . '" style="cursor:move;"></span><a href="' . lc_href_link_admin(FILENAME_DEFAULT, $_module . '=' . $Qcategories->value('categories_id')) . '"><span class="icon-' . lC_Categories_Admin::getCategoryIcon($Qcategories->value('categories_mode')) . ' margin-left"></span><span class="mid-margin-left">' . $Qcategories->value('categories_name') . '</span></a></td>';
      $status = '<td><span class="align-center" id="status_' . $Qcategories->value('categories_id') . '" onclick="updateStatus(\'' . $Qcategories->value('categories_id') . '\', \'' . (($Qcategories->value('categories_status') == 1) ? 0 : 1) . '\');">' . (($Qcategories->valueInt('categories_status') == 1) ? '<span class="icon-tick icon-size2 icon-green cursor-pointer with-tooltip" title="' . $lC_Language->get('text_disable_category') . '"></span>' : '<span class="icon-cross icon-size2 icon-red cursor-pointer with-tooltip" title="' . $lC_Language->get('text_enable_category') . '"></span>') . '</span></td>';
      $visibility = '<td>' .
                    (($Qcategories->valueInt('parent_id') == 0) ? 
                    '  <span class="align-center margin-right" id="nav_' . $Qcategories->value('categories_id') . '" onclick="updateVisibilityNav(\'' . $Qcategories->value('categories_id') . '\', \'' . (($Qcategories->value('categories_visibility_nav') == 1) ? 0 : 1) . '\');">' . 
                         (($Qcategories->valueInt('categories_visibility_nav') == 1) ? '<span class="icon-directions icon-size2 icon-green cursor-pointer with-tooltip" title="' . $lC_Language->get('text_hide_in_nav') . '"></span>' : '<span class="icon-directions icon-size2 icon-silver cursor-pointer with-tooltip" title="' . $lC_Language->get('text_show_in_nav') . '"></span>') . 
                    '  </span>' : '') . 
                    '  <span class="align-center" id="box_' . $Qcategories->value('categories_id') . '" onclick="updateVisibilityBox(\'' . $Qcategories->value('categories_id') . '\', \'' . (($Qcategories->value('categories_visibility_box') == 1) ? 0 : 1) . '\');">' . 
                         (($Qcategories->valueInt('categories_visibility_box') == 1) ? '<span class="icon-browser icon-size2 icon-green cursor-pointer with-tooltip" title="' . $lC_Language->get('text_hide_in_box') . '"></span>' : '<span class="icon-browser icon-size2 icon-silver cursor-pointer with-tooltip" title="' . $lC_Language->get('text_show_in_box') . '"></span>') . 
                    '  </span>' .  
                    '</td>';
      $mode = '<td>' . $lC_Language->get('text_mode_' . $Qcategories->value('categories_mode')) . '</td>';
      $sort = '<td>' . $Qcategories->valueInt('sort_order') . '<input type="hidden" name="sort_order_' . $Qcategories->value('categories_id') . '" value="' . $Qcategories->valueInt('sort_order') . '" class="sort" /></td>';
      $action = '<td class="align-right vertical-center">
                   <span class="button-group" style="white-space:nowrap;">
                     <a href="' . ((int)($_SESSION['admin']['access'][$_module] < 3) ? '#' : lc_href_link_admin(FILENAME_DEFAULT, $_module . '=' . $Qcategories->value('categories_id') . '&cid=' . (($_GET['categories']) ? $_GET['categories'] : 0) . '&action=save')) . '" class="button icon-pencil' . ((int)($_SESSION['admin']['access'][$_module] < 3) ? ' disabled' : NULL) . '">' .  (($media === 'mobile-portrait' || $media === 'mobile-landscape') ? NULL : $lC_Language->get('icon_edit')) . '</a>
                     <a href="' . ((int)($_SESSION['admin']['access']['languages'] < 4) ? '#' : 'javascript://" onclick="moveCategory(\'' . $Qcategories->value('categories_id') . '\', \'' . urlencode($Qcategories->valueProtected('categories_name')) . '\')"') . '" class="button icon-cloud-upload with-tooltip ' . ((int)($_SESSION['admin']['access']['languages'] < 4) ? 'disabled' : NULL) . '" title="' . $lC_Language->get('icon_move') . '"></a>
                   </span>
                   <span class="button-group">
                     <a href="' . ((int)($_SESSION['admin']['access']['languages'] < 4) ? '#' : 'javascript://" onclick="deleteCategory(\'' . $Qcategories->value('categories_id') . '\', \'' . urlencode($Qcategories->valueProtected('categories_name')) . '\')"') . '" class="button icon-trash with-tooltip ' . ((int)($_SESSION['admin']['access']['languages'] < 4) ? 'disabled' : NULL) . '" title="' . $lC_Language->get('icon_delete') . '"></a>
                   </span>
                 </td>';
      $result['aaData'][] = array("$check", "$category", "$status", "$visibility", "$mode", "$sort", "$action");
      $result['entries'][] = $Qcategories->toArray();
    }

    $Qcategories->freeResult();

    return $result;
  }
  
 /*
  * Returns all child categories
  *
  * @param int $parent_id The category parent id
  * @access public
  * @return array
  */
  public static function getAllChildren($parent_id) {
    global $lC_Database;
    
    $Qchildren = $lC_Database->query('select categories_id from :table_categories where parent_id = :parent_id');
    $Qchildren->bindTable(':table_categories', TABLE_CATEGORIES);
    $Qchildren->bindInt(':parent_id', $parent_id);
    $Qchildren->execute();
      
    $children = array();    
    if ($Qchildren->numberOfRows !== 0) {
      while ( $Qchildren->next() ) {
        $children[] = $Qchildren->valueInt('categories_id');
        $arr = self::getAllChildren($Qchildren->valueInt('categories_id'));
        if (count($arr) > 0) {
          $children[] = self::getAllChildren($Qchildren->value('categories_id'));
        }
      }      
    }
    return $children;
  }
  
 /*
  * Returns result of multidimensional array search
  *
  * @param $needle
  * @param $haystack
  * @param $strict
  * @access public
  * @return boolean true or false
  */
  public static function in_array_r($needle, $haystack, $strict = false) {
    foreach ($haystack as $item) {
      if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && self::in_array_r($needle, $item, $strict))) {
        return true;
      }
    }
    return false;
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
      $acArr = lC_Categories_Admin::getAllChildren($id);
      if ($cid != $id && !lC_Categories_Admin::in_array_r($cid, $acArr)) {
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

    $Qcategories = $lC_Database->query('select * from :table_categories where categories_id = :categories_id');
    $Qcategories->bindTable(':table_categories', TABLE_CATEGORIES);
    $Qcategories->bindInt(':categories_id', $id);
    $Qcategories->execute();

    $data = $Qcategories->toArray();
    
    $data['childs_count'] = sizeof($lC_CategoryTree->getChildren($Qcategories->valueInt('categories_id'), $dummy = array()));
    $data['products_count'] = $lC_CategoryTree->getNumberOfProducts($Qcategories->valueInt('categories_id'));

    $Qdescription = $lC_Database->query('select * from :table_categories_description where categories_id = :categories_id');
    $Qdescription->bindTable(':table_categories_description', TABLE_CATEGORIES_DESCRIPTION);
    $Qdescription->bindInt(':categories_id', $id);
    $Qdescription->execute();
    
    while ($Qdescription->next()) {
      $data[$Qdescription->valueInt('language_id')]['categories_name'] = $Qdescription->value('categories_name');  
      $data[$Qdescription->valueInt('language_id')]['categories_menu_name'] = $Qdescription->value('categories_menu_name');  
      $data[$Qdescription->valueInt('language_id')]['categories_blurb'] = $Qdescription->value('categories_blurb');  
      $data[$Qdescription->valueInt('language_id')]['categories_description'] = $Qdescription->value('categories_description');  
      $data[$Qdescription->valueInt('language_id')]['categories_tags'] = $Qdescription->value('categories_tags');  
    }
    
    $Qpermalink = $lC_Database->query('select language_id, permalink from :table_permalinks where item_id = :item_id and type = 1');
    $Qpermalink->bindTable(':table_permalinks', TABLE_PERMALINKS);
    $Qpermalink->bindInt(':item_id', $id);
    $Qpermalink->execute();
    
    while ($Qpermalink->next()) {
      $data[$Qpermalink->valueInt('language_id')]['permalink'] = $Qpermalink->value('permalink');
    }
    
    $Qcategories->freeResult(); 
    $Qdescription->freeResult(); 
    $Qpermalink->freeResult(); 
    
    if (!empty($key) && isset($data[$key])) {
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

    //echo '<pre>';
    //print_r($data);
    //echo '<pre>';
    //die('after $data');
        
    if ( is_numeric($id) ) {
      $Qcat = $lC_Database->query('update :table_categories set categories_image = :categories_image, parent_id = :parent_id, sort_order = :sort_order, categories_mode = :categories_mode, categories_link_target = :categories_link_target, categories_custom_url = :categories_custom_url, categories_status = :categories_status, categories_visibility_nav = :categories_visibility_nav, categories_visibility_box = :categories_visibility_box, date_added = :date_added, last_modified = now() where categories_id = :categories_id');
      $Qcat->bindInt(':categories_id', $id);
      $Qcat->bindValue(':date_added', $data['date_added']);
    } else {
      $Qcat = $lC_Database->query('insert into :table_categories (categories_image, parent_id, sort_order, categories_mode, categories_link_target, categories_custom_url, categories_status, categories_visibility_nav, categories_visibility_box, date_added) values (:categories_image, :parent_id, :sort_order, :categories_mode, :categories_link_target, :categories_custom_url, :categories_status, :categories_visibility_nav, :categories_visibility_box, now())');
      $Qcat->bindInt(':parent_id', $data['parent_id']);
    }

    $Qcat->bindTable(':table_categories', TABLE_CATEGORIES);
    $Qcat->bindValue(':categories_image', $data['image']);
    $Qcat->bindInt(':parent_id', $data['parent_id']);
    $Qcat->bindInt(':sort_order', $data['sort_order']);
    $Qcat->bindValue(':categories_mode', $data['mode']);
    $Qcat->bindInt(':categories_link_target', $data['link_target']);
    $Qcat->bindValue(':categories_custom_url', $data['custom_url']);
    $Qcat->bindInt(':categories_status', $data['status']);
    $Qcat->bindInt(':categories_visibility_nav', $data['nav']);
    $Qcat->bindInt(':categories_visibility_box', $data['box']);
    $Qcat->setLogging($_SESSION['module'], $id);
    $Qcat->execute();
    
    if ( !$lC_Database->isError() ) {
      $category_id = (is_numeric($id)) ? $id : $lC_Database->nextID();
      $lC_CategoryTree = new lC_CategoryTree_Admin();
      $cPath = ($data['parent_id'] != 0) ? $lC_CategoryTree->getcPath($data['parent_id']) . '_' . $category_id : $category_id;
        
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
        
        // added for permalink
        if (!empty($data['permalink'][$l['id']])) {
          if ($data['permalink'][$l['id']] != 'no-permalink') {
            if (is_numeric($id) && lC_Categories_Admin::validatePermalink(array($data['permalink'][$l['id']]), $category_id, 1) == 1) {
              $Qpl = $lC_Database->query('update :table_permalinks set permalink = :permalink where item_id = :item_id and type = :type and language_id = :language_id');
            } else {
              $Qpl = $lC_Database->query('insert into :table_permalinks (item_id, language_id, type, query, permalink) values (:item_id, :language_id, :type, :query, :permalink)');
            }
            $Qpl->bindTable(':table_permalinks', TABLE_PERMALINKS);
            $Qpl->bindInt(':item_id', $category_id);
            $Qpl->bindInt(':language_id', $l['id']);
            $Qpl->bindInt(':type', 1);
            $Qpl->bindValue(':query', 'cPath=' . $cPath);
            $Qpl->bindValue(':permalink', $data['permalink'][$l['id']]);
            $Qpl->execute();

            if ( $lC_Database->isError() ) {
              $error = true;
              break;
            }
          } else {
            $Qpl = $lC_Database->query('delete from :table_permalinks where item_id = :item_id and type = :type and language_id = :language_id');
            $Qpl->bindTable(':table_permalinks', TABLE_PERMALINKS);
            $Qpl->bindInt(':item_id', $category_id);
            $Qpl->bindInt(':language_id', $l['id']);
            $Qpl->bindInt(':type', 1);
            $Qpl->execute();
          }
        }
      }
    }
    
    if ( $error === false ) {
      $lC_Database->commitTransaction();

      lC_Cache::clear('categories');
      lC_Cache::clear('category_tree');
      lC_Cache::clear('also_purchased');

      return $category_id; // used for the save_close buttons
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
              // permalink
              $Qpb = $lC_Database->query('delete from :table_permalinks where item_id = :item_id');
              $Qpb->bindTable(':table_permalinks', TABLE_PERMALINKS);
              $Qpb->bindInt(':item_id', $category['id']);
              $Qpb->execute();

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
    
    $Qcategories = $lC_Database->query('select parent_id from :table_categories where categories_id = :categories_id');
    $Qcategories->bindTable(':table_categories', TABLE_CATEGORIES);
    $Qcategories->bindInt(':categories_id', $id);
    $Qcategories->execute();

    $parentID = $Qcategories->valueInt('parent_id');

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
    global $lC_Database;
    
    if (!empty($parent_id)) {
      $Qchildren = $lC_Database->query('select categories_id from :table_categories where parent_id = :parent_id');
      $Qchildren->bindTable(':table_categories', TABLE_CATEGORIES);
      $Qchildren->bindInt(':parent_id', $parent_id);
      $Qchildren->execute();
      
      $cat_ids = array();
      if ($Qchildren->numberOfRows !== 0) {
        while ( $Qchildren->next() ) {  
          $cat_ids[] = $Qchildren->value('categories_id');
        }
      }
    }
    
    return $cat_ids;
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
  * update category status db entry
  * 
  * @access public
  * @return true or false
  */
  public static function updateStatus($id, $val) {
    global $lC_Database;
    
    $Qupdate = $lC_Database->query('update :table_categories set categories_status = :categories_status where categories_id = :categories_id');
    $Qupdate->bindTable(':table_categories', TABLE_CATEGORIES);
    $Qupdate->bindInt(':categories_status', $val);
    $Qupdate->bindInt(':categories_id', $id);
    $Qupdate->execute();
      
    return true;
  }
  
 /*
  * update category show in top nav db entry
  * 
  * @access public
  * @return true or false
  */
  public static function updateVisibilityNav($id, $val) {
    global $lC_Database;
    
    $Qupdate = $lC_Database->query('update :table_categories set categories_visibility_nav = :categories_visibility_nav where categories_id = :categories_id');
    $Qupdate->bindTable(':table_categories', TABLE_CATEGORIES);
    $Qupdate->bindInt(':categories_visibility_nav', $val);
    $Qupdate->bindInt(':categories_id', $id);
    $Qupdate->execute();
      
    return true;
  }
  
 /*
  * update category show in infobox db entry
  * 
  * @access public
  * @return true or false
  */
  public static function updateVisibilityBox($id, $val) {
    global $lC_Database;
    
    $Qupdate = $lC_Database->query('update :table_categories set categories_visibility_box = :categories_visibility_box where categories_id = :categories_id');
    $Qupdate->bindTable(':table_categories', TABLE_CATEGORIES);
    $Qupdate->bindInt(':categories_visibility_box', $val);
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
    } else if ($type == 'info_category') {
      $icon = 'info-round icon-blue';
    } else if ($type == 'faq_category') {
      $icon = 'numbered-list icon-anthracite';
    } else if ($type == 'article_category') {
      $icon = 'newspaper icon-anthracite';
    } else if ($type == 'page') {
      $icon = 'page-list icon-black';
    } else if ($type == 'specials') {
      $icon = 'star icon-red';
    } else if ($type == 'featured') {
      $icon = 'star icon-orange';
    } else if ($type == 'new') {
      $icon = 'new icon-green';
    } else if ($type == 'search') {
      $icon = 'search icon-black';
    } else if ($type == 'cart') {
      $icon = 'cart icon-orange';
    } else if ($type == 'account') {
      $icon = 'user icon-anthracite';
    } else if ($type == 'info') {
      $icon = 'question-round icon-blue';
    } else if ($type == 'override') {
      $icon = 'globe icon-blue';
    }

    return $icon;
  }
   
 /*
  * Return the number of permalinks for a category
  *
  * @param string $permalink The permalink string to count
  * @access public
  * @return integer
  */
  public static function getPermalinkCount($permalink, $cid = null, $type = null) {
    global $lC_Database;

    $Qpermalinks = $lC_Database->query('select count(*) as total, item_id, permalink from :table_permalinks where permalink = :permalink');

    if (is_numeric($cid)) {
      $Qpermalinks->appendQuery('and item_id != :item_id');
      $Qpermalinks->bindInt(':item_id', $cid);
    }

    $Qpermalinks->bindTable(':table_permalinks', TABLE_PERMALINKS);
    $Qpermalinks->bindValue(':permalink', $permalink);
    $Qpermalinks->execute();
    
    if ($cid == $Qpermalinks->valueInt('item_id') && $permalink == $Qpermalinks->value('permalink')) {
      $permalink_count = 0;
    } else {  
      $permalink_count = $Qpermalinks->valueInt('total');
    }
    
    return $permalink_count;
  }
  
 /*
  * Validate the category permalink
  *
  * @param string $permalink The category permalink
  * @access public
  * @return array
  */
  public static function validatePermalink($permalink_array, $cid = null, $type = null) {
    $validated = true;
    
    if (is_array($permalink_array)) {
      foreach($permalink_array as $permalink) {
        if ( preg_match('/^[a-z0-9_-]+$/iD', $permalink) !== 1 ) $validated = false;
        if ( lC_Categories_Admin::getPermalinkCount($permalink, $cid, $type) > 0) $validated = false;
      }
    } else {
      if ( preg_match('/^[a-z0-9_-]+$/iD', $permalink) !== 1 ) $validated = false;
      if ( lC_Categories_Admin::getPermalinkCount($permalink, $cid, $type) > 0) $validated = false;
    }
    
    return $validated;
  }

 /*
  * Delete Categories Image
  * 
  * @access public
  * @return json
  */
  public static function deleteCatImage($_image, $_id = null) {
    global $lC_Database;
    
    // added to check for other categories using same image and do not delete
    $Qci = $lC_Database->query('select id from :table_categories where categories_image = :categories_image');
    $Qci->bindTable(':table_categories', TABLE_CATEGORIES);
    $Qci->bindInt(':categories_image', $_image);
    $Qci->execute();
        
    if ($Qci->numberOfRows() < 2) {
      if (file_exists('../images/categories/' . $_image)){
        unlink('../images/categories/' . $_image);
      }
    }
    
    $Qci->freeResult();
    
    if (is_numeric($_id)) {
      $Qcategoriesimage = $lC_Database->query('update :table_categories set categories_image = "" where categories_id = :categories_id');
      $Qcategoriesimage->bindTable(':table_categories', TABLE_CATEGORIES);
      $Qcategoriesimage->bindInt(':categories_id', $_id);
      $Qcategoriesimage->execute();
    }
    
    if ( !$lC_Database->isError() ) {
      lC_Cache::clear('categories');
      lC_Cache::clear('category_tree');
      lC_Cache::clear('template');
      lC_Cache::clear('also_purchased');
    }
      
    return true;
  }
  
 /*
  * Return the category mode(s) array
  *
  * @access public
  * @return array
  */
  public static function modeSelect($mode = null) {
    global $lC_Language;
    
    $modes_array = array(
                         array('text' => $lC_Language->get('text_product_category'), 'value' => 'category'),
                         array('text' => $lC_Language->get('text_info_category'), 'value' => 'info_category'),
                         //array('text' => $lC_Language->get('text_faq_category'), 'value' => 'faq_category'),
                         //array('text' => $lC_Language->get('text_article_category'), 'value' => 'article_category'),
                         array('text' => $lC_Language->get('text_page'), 'value' => 'page'),
                         array('text' => $lC_Language->get('link_to_specials'), 'value' => 'specials'),
                         array('text' => $lC_Language->get('link_to_featured'), 'value' => 'featured'),
                         array('text' => $lC_Language->get('link_to_new'), 'value' => 'new'),
                         array('text' => $lC_Language->get('link_to_search'), 'value' => 'search'),
                         array('text' => $lC_Language->get('link_to_cart'), 'value' => 'cart'),
                         array('text' => $lC_Language->get('link_to_account'), 'value' => 'account'),
                         array('text' => $lC_Language->get('link_to_info'), 'value' => 'info'),
                         array('text' => $lC_Language->get('text_custom_link'), 'value' => 'override')
                         );
                         
    foreach ($modes_array as $mode) {
      $modes .= '<option value="' . $mode['value'] . '"' . (($mode == $mode['value']) ? ' selected' : '') . '>' . $mode['text'] . '</option>'; 
    }
    
    return $modes;
  }                      
}
?>