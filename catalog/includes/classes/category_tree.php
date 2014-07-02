<?php
/**
  @package    catalog::classes
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: category_tree.php v1.0 2013-08-08 datazen $
*/

class lC_CategoryTree {
 /**
  * Flag to control if the total number of products in a category should be calculated
  *
  * @var boolean
  * @access protected
  */

  protected $_show_total_products = false;

 /**
  * Array containing the category structure relationship data
  *
  * @var array
  * @access protected
  */

  protected $_data = array();

  var $root_category_id = 0,
  $max_level = 0,
  $start_string = '',
  $end_string = '',
  $root_start_string = '',
  $root_end_string = '', 
  $parent_start_string = '',
  $parent_end_string = '',
  $parent_group_start_string_0 = '<ul>',
  $parent_group_end_string_0 = '</ul>',        
  $parent_group_start_string = '<ul>',
  $parent_group_end_string = '</ul>',
  $child_start_string_with_children = '<li>',
  $child_end_string_with_children = '</li>',
  $child_start_string = '<li>',
  $child_end_string = '</li>',
  $breadcrumb_separator = '_',
  $breadcrumb_usage = true,
  $spacer_string = '',
  $bullet_string = '',
  $spacer_multiplier = 1,
  $follow_cpath = false,
  $cpath_array = array(),
  $cpath_start_string = '',
  $cpath_end_string = '',
  $category_product_count_start_string = '&nbsp;(',
  $category_product_count_end_string = ')',
  $use_aria = false;

 /**
  * Constructor; load the category structure relationship data from the database
  *
  * @access public
  */

  public function __construct() {
    global $lC_Database, $lC_Cache, $lC_Language;

    if ( SERVICES_CATEGORY_PATH_CALCULATE_PRODUCT_COUNT == '1' ) {
      $this->_show_total_products = true;
    }

    if ( $lC_Cache->read('category_tree-' . $lC_Language->getCode(), 720) ) {
      $this->_data = $lC_Cache->getCache();
    } else {
      $Qcategories = $lC_Database->query('select c.categories_id, c.categories_image, c.parent_id, c.categories_mode, c.categories_link_target, c.categories_custom_url, c.categories_status, c.categories_visibility_nav, c.categories_visibility_box, cd.categories_name, cd.categories_menu_name from :table_categories c, :table_categories_description cd where c.categories_status = 1 and c.categories_id = cd.categories_id and cd.language_id = :language_id order by c.parent_id, c.sort_order, cd.categories_name, cd.categories_menu_name');
      $Qcategories->bindTable(':table_categories', TABLE_CATEGORIES);
      $Qcategories->bindTable(':table_categories_description', TABLE_CATEGORIES_DESCRIPTION);
      $Qcategories->bindInt(':language_id', $lC_Language->getID());
      $Qcategories->execute();

      while ( $Qcategories->next() ) {
        // added to grab permalink if exists
        $Qpermalink = $lC_Database->query('select item_id, query, permalink from :table_permalinks where item_id = :item_id and language_id = :language_id and type = 1 limit 1');
        $Qpermalink->bindTable(':table_permalinks', TABLE_PERMALINKS);
        $Qpermalink->bindInt(':item_id', $Qcategories->valueInt('categories_id'));
        $Qpermalink->bindInt(':language_id', $lC_Language->getID());
        $Qpermalink->execute();

        $this->_data[$Qcategories->valueInt('parent_id')][$Qcategories->valueInt('categories_id')] = array('item_id' => $Qpermalink->valueInt('item_id'), 
                                                                                                           'name' => $Qcategories->value('categories_name'),
                                                                                                           'menu_name' => $Qcategories->value('categories_menu_name'),
                                                                                                           'query' => $Qpermalink->value('query'),
                                                                                                           'permalink' => $Qpermalink->value('permalink'),
                                                                                                           'image' => $Qcategories->value('categories_image'),
                                                                                                           'count' => 0,
                                                                                                           'mode' => $Qcategories->value('categories_mode'),
                                                                                                           'link_target' => $Qcategories->valueInt('categories_link_target'),
                                                                                                           'custom_url' => $Qcategories->value('categories_custom_url'),
                                                                                                           'status' => $Qcategories->valueInt('categories_status'),
                                                                                                           'nav' => $Qcategories->valueInt('categories_visibility_nav'),
                                                                                                           'box' => $Qcategories->valueInt('categories_visibility_box')
                                                                                                           );
      }

      if ( $this->_show_total_products === true ) {
        $this->_calculateProductTotals();
      }

      $lC_Cache->write($this->_data);
    }
  }

  function reset() {
    $this->root_category_id = 0;
    $this->max_level = 0;
    $this->root_start_string = '';
    $this->root_end_string = '';
    $this->parent_start_string = '';
    $this->parent_end_string = '';
    $this->parent_group_start_string_0 = '<ul>';
    $this->parent_group_end_string_0 = '</ul>';
    $this->parent_group_start_string = '<ul>';
    $this->parent_group_end_string = '</ul>';
    $this->child_start_string_with_children = '<li>';
    $this->child_end_string_with_children = '</li>';
    $this->child_start_string = '<li>';
    $this->child_end_string = '</li>';      
    $this->breadcrumb_separator = '_';
    $this->breadcrumb_usage = true;
    $this->spacer_string = '';
    $this->bullet_string = '';
    $this->spacer_multiplier = 1;
    $this->follow_cpath = false;
    $this->cpath_array = array();
    $this->cpath_start_string = '';
    $this->cpath_end_string = '';
    $this->_show_total_products = (SERVICES_CATEGORY_PATH_CALCULATE_PRODUCT_COUNT == '1') ? true : false;
    $this->category_product_count_start_string = '&nbsp;(';
    $this->category_product_count_end_string = ')';
    $this->use_aria = false;
  }

 /**
  * Return a formated string representation of a category and its subcategories
  *
  * @param int $parent_id The parent ID of the category to build from
  * @param int $level Internal flag to note the depth of the category structure
  * @access protected
  * @return string
  */

  protected function _buildBranch($parent_id, $level = 0) {    
    $result = ($parent_id == 0) ? $this->parent_group_start_string_0 : $this->parent_group_start_string;
    if ( isset($this->_data[$parent_id]) ) {
      foreach ( $this->_data[$parent_id] as $category_id => $category ) {
        if ($category['status'] === 1 && $category['box'] === 1) {
          if ( $this->breadcrumb_usage === true ) {
            $category_link = $this->buildBreadcrumb($category_id);
          } else {
            $category_link = $category_id;
          }

          $result .= ($this->hasChildren($category_id)) ? $this->child_start_string_with_children : $this->child_start_string;

          if ( isset($this->_data[$category_id]) ) {
            $result .= $this->parent_start_string;
          }

          if ( $level === 0 ) {
            $result .= $this->root_start_string;
          }

          if ( ($this->follow_cpath === true) && in_array($category_id, $this->cpath_array) ) {
            $link_title = $this->cpath_start_string . (($category['menu_name'] != '') ? $category['menu_name'] : $category['name']) . (($category['mode'] == 'category') ? (( $this->_show_total_products === true ) ? $this->category_product_count_start_string . $category['count'] . $this->category_product_count_end_string . $this->cpath_end_string : null) : null);
          } else {
            $link_title = (($category['menu_name'] != '') ? $category['menu_name'] : $category['name']) . (($category['mode'] == 'category') ? (( $this->_show_total_products === true ) ? $this->category_product_count_start_string . $category['count'] . $this->category_product_count_end_string : null) : null);
          }

          if ($category['custom_url']) {
            $result .= str_repeat($this->spacer_string, $this->spacer_multiplier * $level) . $this->bullet_string . lc_link_object(lc_href_link($category['custom_url']), $link_title, ($category['link_target'] === 1) ? 'target="_blank"' : '');
          } else {
            $result .= str_repeat($this->spacer_string, $this->spacer_multiplier * $level) . $this->bullet_string . lc_link_object(lc_href_link(FILENAME_DEFAULT, 'cPath=' . $category_link), $link_title, ($category['link_target'] === 1) ? 'target="_blank"' : '');
          }

          if ( $level === 0 ) {
            $result .= $this->root_end_string;
          }

          if ( isset($this->_data[$category_id]) ) {
            $result .= $this->parent_end_string;
          }     

          if ($this->use_aria === false) $result .= ($this->hasChildren($category_id)) ? $this->child_end_string_with_children : $this->child_end_string;

          if ( isset($this->_data[$category_id]) && (($this->max_level == '0') || ($this->max_level > $level+1)) ) {
            if ( $this->follow_cpath === true ) {
              // commented out below due to hindering the loading of the full category tree store side
              if ( in_array($category_id, explode("_", $_GET['cPath'])) ) {
                $result .= $this->_buildBranch($category_id, $level+1);
              }
            } else {
              $result .= $this->_buildBranch($category_id, $level+1);
            }
          }

          if ($this->use_aria === true) $result .= ($this->hasChildren($category_id)) ? $this->child_end_string_with_children : $this->child_end_string;
        }
      }
    }

    $result .= ($parent_id == 0) ? $this->parent_group_end_string_0 : $this->parent_group_end_string;

    return $result;
  }

  function buildBranchArray($parent_id, $level = 0, $result = '') {
    if (empty($result)) {
      $result = array();
    }

    if (isset($this->_data[$parent_id])) {
      foreach ($this->_data[$parent_id] as $category_id => $category) {
        if ($this->breadcrumb_usage == true) {
          $category_link = $this->buildBreadcrumb($category_id);
        } else {
          $category_link = $category_id;
        }

        $result[] = array('id' => $category_link,
          'title' => str_repeat($this->spacer_string, $this->spacer_multiplier * $level) . $category['name'],
          'mode' => $category['mode']);

        if (isset($this->_data[$category_id]) && (($this->max_level == '0') || ($this->max_level > $level+1))) {
          if ($this->follow_cpath === true) {
            if (in_array($category_id, $this->cpath_array)) {
              $result = $this->buildBranchArray($category_id, $level+1, $result);
            }
          } else {
            $result = $this->buildBranchArray($category_id, $level+1, $result);
          }
        }
      }
    }

    return $result;
  }

  function buildBreadcrumb($category_id, $level = 0) {
    $breadcrumb = '';

    foreach ($this->_data as $parent => $categories) {
      foreach ($categories as $id => $info) {
        if ($id == $category_id) {
          if ($level < 1) {
            $breadcrumb = $id;
          } else {
            $breadcrumb = $id . $this->breadcrumb_separator . $breadcrumb;
          }

          if ($parent != $this->root_category_id) {
            $breadcrumb = $this->buildBreadcrumb($parent, $level+1) . $breadcrumb;
          }
        }
      }
    }

    return $breadcrumb;
  }

 /**
  * Return a formated string representation of the category structure relationship data
  *
  * @access public
  * @return string
  */

  public function getTree($aria = 1) { 
    return $this->_start_string . $this->_buildBranch($this->root_category_id, null, $aria) . $this->_end_string;
  } 

 /**
  * Return a formated string representation of the FULL category structure relationship data
  *
  * @access public
  * @return string
  */

  public function getFullTree() {
    return $this->_buildFullTree($this->root_category_id);
  }

 /**
  * Magic function; return a formated string representation of the category structure relationship data
  *
  * This is used when echoing the class object, eg:
  *
  * echo $lC_CategoryTree;
  *
  * @access public
  * @return string
  */

  public function __toString() {
    return $this->getTree();
  }

  function getArray($parent_id = '') {
    return $this->buildBranchArray((empty($parent_id) ? $this->root_category_id : $parent_id));
  }

  function exists($id) {
    foreach ($this->_data as $parent => $categories) {
      foreach ($categories as $category_id => $info) {
        if ($id == $category_id) {
          return true;
        }
      }
    }

    return false;
  }

  function getChildren($category_id, &$array) {
    foreach ($this->_data as $parent => $categories) {
      if ($parent == $category_id) {
        foreach ($categories as $id => $info) {
          $array[] = $id;
          $this->getChildren($id, $array);
        }
      }
    }

    return $array;
  }

  function hasChildren($category_id) {
    foreach ($this->_data as $parent => $categories) {
      if ($parent == $category_id) {
        return TRUE;
      }
    }

    return FALSE;
  }

  function getStatus($id) {
    foreach ($this->_data as $parent => $categories) {
      foreach ($categories as $category_id => $info) {
        if ($id == $category_id) {
          $result = $info['status'];
        }
      }
    }
    return $result;
  }

  function getData($id) {
    foreach ($this->_data as $parent => $categories) {
      foreach ($categories as $category_id => $info) {
        if ($id == $category_id) {
          return array('id' => $id,
                       'name' => $info['name'],
                       'item_id' => $info['item_id'],
                       'query' => $info['query'],
                       'permalink' => $info['permalink'],
                       'parent_id' => $parent,
                       'image' => $info['image'],
                       'status' => $info['status'],
                       'count' => $info['count']
          );
        }
      }
    }

    return false;
  }

  function getID($permalink) {
    foreach ($this->_data as $parent => $categories) {
      foreach ($categories as $category_id => $info) {
        if ($permalink == $info['permalink']) {
          return $info['item_id'];
        } 
      }
    }

    return false;
  }

 /**
  * Calculate the number of products in each category
  *
  * @access protected
  */

  protected function _calculateProductTotals($filter_active = true) {
    global $lC_Database;

    $totals = array();

    $Qtotals = $lC_Database->query('select p2c.categories_id, count(*) as total from :table_products p, :table_products_to_categories p2c where p2c.products_id = p.products_id');

    if ( $filter_active === true ) {
      $Qtotals->appendQuery('and p.products_status = :products_status');
      $Qtotals->bindInt(':products_status', 1);
    }

    $Qtotals->appendQuery('group by p2c.categories_id');
    $Qtotals->bindTable(':table_products', TABLE_PRODUCTS);
    $Qtotals->bindTable(':table_products_to_categories', TABLE_PRODUCTS_TO_CATEGORIES);
    $Qtotals->execute();

    while ( $Qtotals->next() ) {
      $totals[$Qtotals->valueInt('categories_id')] = $Qtotals->valueInt('total');
    }

    foreach ( $this->_data as $parent => $categories ) {
      foreach ( $categories as $id => $info ) {
        if ( isset($totals[$id]) && ($totals[$id] > 0) ) {
          $this->_data[$parent][$id]['count'] = $totals[$id];

          $parent_category = $parent;

          while ( $parent_category != $this->root_category_id ) {
            foreach ( $this->_data as $parent_parent => $parent_categories ) {
              foreach ( $parent_categories as $parent_category_id => $parent_category_info ) {
                if ( $parent_category_id == $parent_category ) {
                  $this->_data[$parent_parent][$parent_category_id]['count'] += $this->_data[$parent][$id]['count'];

                  $parent_category = $parent_parent;

                  break 2;
                }
              }
            }
          }
        }
      }
    }
  }

  function getNumberOfProducts($id) {
    foreach ($this->_data as $parent => $categories) {
      foreach ($categories as $category_id => $info) {
        if ($id == $category_id) {
          return $info['count'];
        }
      }
    }

    return false;
  }

  function setRootCategoryID($root_category_id) {
    $this->root_category_id = $root_category_id;
  }

  function setMaximumLevel($max_level) {
    $this->max_level = $max_level;
  }

  function setRootString($root_start_string, $root_end_string) {
    $this->root_start_string = $root_start_string;
    $this->root_end_string = $root_end_string;
  } 

  function setParentString($parent_start_string, $parent_end_string) {
    $this->parent_start_string = $parent_start_string;
    $this->parent_end_string = $parent_end_string;
  }

  function setParentGroupString($parent_group_start_string, $parent_group_end_string) {
    $this->parent_group_start_string = $parent_group_start_string;
    $this->parent_group_end_string = $parent_group_end_string;
  }

  function setParentGroupStringTop($parent_group_start_string_0, $parent_group_end_string_0) {
    $this->parent_group_start_string_0 = $parent_group_start_string_0;
    $this->parent_group_end_string_0 = $parent_group_end_string_0;
  }    

  function setChildString($child_start_string, $child_end_string) {
    $this->child_start_string = $child_start_string;
    $this->child_end_string = $child_end_string;
  }

  function setChildStringWithChildren($child_start_string_with_children, $child_end_string_with_children) {
    $this->child_start_string_with_children = $child_start_string_with_children;
    $this->child_end_string_with_children = $child_end_string_with_children;
  }    

  function setBreadcrumbSeparator($breadcrumb_separator) {
    $this->breadcrumb_separator = $breadcrumb_separator;
  }

  function setBreadcrumbUsage($breadcrumb_usage) {
    if ($breadcrumb_usage === true) {
      $this->breadcrumb_usage = true;
    } else {
      $this->breadcrumb_usage = false;
    }
  }

  function setSpacerString($spacer_string, $spacer_multiplier = 2) {
    $this->spacer_string = $spacer_string;
    $this->spacer_multiplier = $spacer_multiplier;
  }

  function setBulletString($bullet_string) {
    $this->bullet_string = $bullet_string;
  }    

  function setCategoryPath($cpath, $cpath_start_string = '', $cpath_end_string = '') {
    $this->follow_cpath = true;
    $this->cpath_array = explode($this->breadcrumb_separator, $cpath);
    $this->cpath_start_string = $cpath_start_string;
    $this->cpath_end_string = $cpath_end_string;
  }

  function setFollowCategoryPath($follow_cpath) {
    if ($follow_cpath === true) {
      $this->follow_cpath = true;
    } else {
      $this->follow_cpath = false;
    }
  }

  function setCategoryPathString($cpath_start_string, $cpath_end_string) {
    $this->cpath_start_string = $cpath_start_string;
    $this->cpath_end_string = $cpath_end_string;
  }

  function setShowCategoryProductCount($show_category_product_count) {
    if ($show_category_product_count === true) {
      $this->_show_total_products = true;
    } else {
      $this->_show_total_products = false;
    }
  }

  function setCategoryProductCountString($category_product_count_start_string, $category_product_count_end_string) {
    $this->category_product_count_start_string = $category_product_count_start_string;
    $this->category_product_count_end_string = $category_product_count_end_string;
  }

  function setUseAria($use_aria) {
    $this->use_aria = $use_aria;
  }
}
?>