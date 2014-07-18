<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: products.php v1.0 2013-08-08 datazen $
*/
global $lC_Vqmod;   

if (!defined('DIR_FS_ADMIN')) return false;
if (!defined('DIR_FS_CATALOG')) return false;

include_once($lC_Vqmod->modCheck(DIR_FS_ADMIN . 'includes/applications/customer_groups/classes/customer_groups.php'));
include_once($lC_Vqmod->modCheck(DIR_FS_ADMIN . 'includes/applications/product_variants/classes/product_variants.php'));
include_once($lC_Vqmod->modCheck(DIR_FS_ADMIN . 'includes/applications/specials/classes/specials.php'));
include_once($lC_Vqmod->modCheck(DIR_FS_ADMIN . 'includes/applications/categories/classes/categories.php'));
include_once($lC_Vqmod->modCheck(DIR_FS_ADMIN . 'includes/applications/tax_classes/classes/tax_classes.php'));
include_once($lC_Vqmod->modCheck(DIR_FS_ADMIN . 'includes/applications/zone_groups/classes/zone_groups.php'));
include_once($lC_Vqmod->modCheck(DIR_FS_CATALOG . 'includes/classes/addons.php'));

class lC_Products_Admin {
 /*
  * Returns the products datatable data for listings
  *
  * @param string $category_id The category id used to filter
  * @access public
  * @return array
  */
  public static function getAll($category_id = null) {
    global $lC_Database, $lC_Language, $lC_Currencies, $_module;

    if ( !is_numeric($category_id) ) {
      $category_id = 0;
    }

    $lC_Language->loadIniFile('products.php');
    
    $media = $_GET['media'];
    
    $result = array('aaData' => array());

    /* Total Records */
    $QresultTotal = $lC_Database->query('SELECT count(p.products_id) as total from :table_products p where p.parent_id = 0');
    $QresultTotal->bindTable(':table_products', TABLE_PRODUCTS);
    $QresultTotal->execute();
    $result['iTotalRecords'] = $QresultTotal->valueInt('total');
    $QresultTotal->freeResult();
    
    /* Paging */
    $sLimit = " LIMIT 0,25 ";
    if (isset($_GET['iDisplayStart'])) {
      if ($_GET['iDisplayLength'] != -1) {
        $sLimit = " LIMIT " . $_GET['iDisplayStart'] . ", " . $_GET['iDisplayLength'];
      }
    }

    /* Ordering */
    if (isset($_GET['iSortCol_0'])) {
      $sOrder = " ORDER BY ";
      for ($i=0 ; $i < (int)$_GET['iSortingCols'] ; $i++ ) {
        $sOrder .= lC_Products_Admin::_fnColumnToField($_GET['iSortCol_'.$i] ) . " " . $_GET['sSortDir_'.$i] .", ";
      }
      $sOrder = substr_replace( $sOrder, "", -2 );
    }

    /* Filtering */
    $sWhere = " WHERE p.parent_id = 0 ";
    if ($_GET['sSearch'] != "") {
      $sWhere .= " and (pd.products_name LIKE '%" . $_GET['sSearch'] . "%' or p.products_model LIKE '%" . $_GET['sSearch'] . "%' or p.products_sku LIKE '%" . $_GET['sSearch'] . "%'  or p.products_id = '" . $_GET['sSearch'] . "')"; 
    } 

    /* Main Listing Query */
    if ( $category_id > 0 ) {
      $lC_CategoryTree = new lC_CategoryTree_Admin();
      $lC_CategoryTree->setBreadcrumbUsage(false);

      $in_categories = array($category_id);

      foreach ( $lC_CategoryTree->getArray($category_id) as $category ) {
        $in_categories[] = $category['id'];
      }
      
      /* Total Filtered Records */
      $QresultFilterTotal = $lC_Database->query('SELECT count(p.products_id) as total, pd.products_name from :table_products p LEFT JOIN :table_products_description pd on (pd.products_id = p.products_id and pd.language_id = :language_id) LEFT JOIN :table_products_to_categories p2c on (p.products_id = p2c.products_id)' . $sWhere . ' and p2c.categories_id in (:categories_id) ' . $sOrder);
      // $QresultFilterTotal = $lC_Database->query("SELECT count(p.products_id) as total, pd.products_name from :table_products p LEFT JOIN :table_products_description pd on (pd.products_id = p.products_id and pd.language_id = :language_id) " . $sWhere . ' and p2c.categories_id in (:categories_id) ' . $sOrder);
      $QresultFilterTotal->bindTable(':table_products', TABLE_PRODUCTS);
      $QresultFilterTotal->bindTable(':table_products_description', TABLE_PRODUCTS_DESCRIPTION);
      $QresultFilterTotal->bindTable(':table_products_to_categories', TABLE_PRODUCTS_TO_CATEGORIES);
      $QresultFilterTotal->bindInt(':language_id', $lC_Language->getID());
      $QresultFilterTotal->bindRaw(':categories_id', implode(',', $in_categories));
      $QresultFilterTotal->execute();
      $result['iTotalDisplayRecords'] = $QresultFilterTotal->valueInt('total');
      $QresultFilterTotal->freeResult();      

      $Qproducts = $lC_Database->query('SELECT p.*, pd.products_name, pd.products_keyword from :table_products p LEFT JOIN :table_products_description pd on (p.products_id = pd.products_id and pd.language_id = :language_id) LEFT JOIN :table_products_to_categories p2c on (p.products_id = p2c.products_id)' . $sWhere . ' and p2c.categories_id in (:categories_id) ' . $sOrder . $sLimit);
      $Qproducts->bindTable(':table_products_to_categories', TABLE_PRODUCTS_TO_CATEGORIES);
      $Qproducts->bindRaw(':categories_id', implode(',', $in_categories));
    } else {
      /* Total Filtered Records */
      $QresultFilterTotal = $lC_Database->query("SELECT count(p.products_id) as total, pd.products_name from :table_products p LEFT JOIN :table_products_description pd on (pd.products_id = p.products_id and pd.language_id = :language_id) " . $sWhere . $sOrder);
      $QresultFilterTotal->bindTable(':table_products', TABLE_PRODUCTS);
      $QresultFilterTotal->bindTable(':table_products_description', TABLE_PRODUCTS_DESCRIPTION);
      $QresultFilterTotal->bindInt(':language_id', $lC_Language->getID());
      $QresultFilterTotal->execute();
      $result['iTotalDisplayRecords'] = $QresultFilterTotal->valueInt('total');
      $QresultFilterTotal->freeResult();

      $Qproducts = $lC_Database->query('SELECT p.*, pd.products_name, pd.products_keyword from :table_products p LEFT JOIN :table_products_description pd on (p.products_id = pd.products_id and pd.language_id = :language_id) ' . $sWhere . $sOrder . $sLimit);
    }
    
    $Qproducts->bindTable(':table_products', TABLE_PRODUCTS);
    $Qproducts->bindTable(':table_products_description', TABLE_PRODUCTS_DESCRIPTION);
    $Qproducts->bindInt(':language_id', $lC_Language->getID());
    $Qproducts->execute();   
    
    while ( $Qproducts->next() ) {
      $Qproductscategories = $lC_Database->query('select p2c.categories_id, cd.categories_name, c.categories_status from :table_products_to_categories p2c left join :table_categories c on (p2c.categories_id = c.categories_id) left join lc_categories_description cd on (p2c.categories_id = cd.categories_id) where p2c.products_id = :products_id and cd.language_id = :language_id');
      $Qproductscategories->bindTable(':table_categories', TABLE_CATEGORIES);
      $Qproductscategories->bindTable(':table_categories_description', TABLE_CATEGORIES_DESCRIPTION);
      $Qproductscategories->bindTable(':table_products_to_categories', TABLE_PRODUCTS_TO_CATEGORIES);
      $Qproductscategories->bindRaw(':products_id', $Qproducts->valueInt('products_id'));
      $Qproductscategories->bindInt(':language_id', $lC_Language->getID());
      $Qproductscategories->execute();
      
      $catCount = ($Qproductscategories->numberOfRows()-1);
      while ( $Qproductscategories->next() ) {
        $Qcategories[] = array('name' => $Qproductscategories->value('categories_name'),
                               'status' => $Qproductscategories->valueInt('categories_status'));
      }
      $cnt = 0;
      $catArr = (is_array($Qcategories)) ? $Qcategories : array();

      foreach ($catArr as $cat) {
        $categories .= '<small title="' . $cat['name'] . '" class="with-tooltip cursor-pointer no-wrap tag mid-margin-right glossy ' . (($cat['status'] == 1) ? ' green-gradient' : ' red-gradient') . '">' . ((strlen($cat['name']) < 15) ? $cat['name'] : trim(substr($cat['name'], 0, 12)) . '...') . '</small>';
        if ($cnt > 2) {
          $categories .= '<br />';
          $cnt = 0;
        } 
        $cnt++;
      }
      $Qcategories = null;      

      $lC_Image = new lC_Image_Admin();
      $Qimage = $lC_Database->query('select image from :table_products_images where products_id = :products_id and default_flag = 1');
      $Qimage->bindTable(':table_products_images', TABLE_PRODUCTS_IMAGES);
      $Qimage->bindInt(':products_id', $Qproducts->valueInt('products_id'));
      $Qimage->execute();
      
      $cost = $lC_Currencies->format($Qproducts->value('products_cost'));
      $msrp = $lC_Currencies->format($Qproducts->value('products_msrp'));
      $products_status = ($Qproducts->valueInt('products_status') === 1);
      $products_keyword  = $Qproducts->value('products_keyword');

      // VQMOD-hookpoint; DO NOT MODIFY OR REMOVE THE LINE BELOW
      $products_quantity = lC_Products_Admin::getProductsListingQty($Qproducts->toArray());
     
      // VQMOD-hookpoint; DO NOT MODIFY OR REMOVE THE LINE BELOW
      $price = lC_Products_Admin::getProductsListingPrice($Qproducts->toArray());
      
      // VQMOD-hookpoint; DO NOT MODIFY OR REMOVE THE LINE BELOW
      $icons = lC_Products_Admin::getlistingIcons($Qproducts->valueInt('products_id'));    

      $extra_data = array('products_cost_formatted' => $cost,
                          'products_price_formatted' => $price,
                          'products_msrp_formatted' => $msrp,
                          'products_status' => $products_status,
                          'products_quantity' => $products_quantity,
                          'products_keyword' => $products_keyword);

      $check = '<td><input class="batch" type="checkbox" name="batch[]" value="' . $Qproducts->valueInt('products_id') . '" id="' . $Qproducts->valueInt('products_id') . '"></td>';
      $products = '<td><div class="products-listing-thumb">' . $lC_Image->show($Qimage->value('image'), '', 'class="mid-margin-right float-left" width="28" height="28"', 'mini') . '</div><div class="products-listing-name-model"><a href="javascript:void(0);" onclick="showPreview(\'' . $Qproducts->valueInt('products_id') . '\')" class="bold">' . $Qproducts->value('products_name') . '</a><br /><span class="small grey mid-margin-right">' . $Qproducts->value('products_model') . '</span><span class="mid-margin-right">' . $icons. '</span></div></td>';
      $cats = '<td>' . $categories . '</td>';
      $categories = null;
      $class = '<td>' . $lC_Language->get('text_common') . '</td>';
      $price = '<td><div class="no-wrap">' . $price . '</div></td>';
      $qty = '<td>' . $products_quantity . '</td>';
      $status = '<td><span class="align-center" id="status_' . $Qproducts->valueInt('products_id') . '" onclick="updateStatus(\'' . $Qproducts->valueInt('products_id') . '\', \'' . (($Qproducts->valueInt('products_status') == 1) ? 0 : 1) . '\');">' . (($Qproducts->valueInt('products_status') == 1) ? '<span class="icon-tick icon-size2 icon-green cursor-pointer with-tooltip" title="' . $lC_Language->get('text_disable_product') . '"></span>' : '<span class="icon-cross icon-size2 icon-red cursor-pointer with-tooltip" title="' . $lC_Language->get('text_enable_product') . '"></span>') . '</span></td>'; 

      $action = '<td class="align-right vertical-center">
                   <span class="button-group">
                     <a href="' . ((int)($_SESSION['admin']['access'][$_module] < 3) ? '#' : lc_href_link_admin(FILENAME_DEFAULT, $_module . '=' . $Qproducts->valueInt('products_id') . '&cID=' . $category_id . '&action=save')) . '" class="button icon-pencil' . ((int)($_SESSION['admin']['access'][$_module] < 3) ? ' disabled' : NULL) . '">' .  (($media === 'mobile-portrait' || $media === 'mobile-landscape') ? NULL : $lC_Language->get('icon_edit')) . '</a>
                     <a href="' . ((int)($_SESSION['admin']['access'][$_module] < 3) ? '#' : 'javascript://" onclick="copyProduct(\'' . $Qproducts->valueInt('products_id') . '\', \'' . urlencode($Qproducts->value('products_name')) . '\')') . '" class="button icon-pages with-tooltip' . ((int)($_SESSION['admin']['access'][$_module] < 3) ? ' disabled' : NULL) . '" title="' . $lC_Language->get('icon_copy') . '"></a>
                     <a target="_blank" href="' . lc_href_link(FILENAME_PRODUCTS, $Qproducts->value('products_keyword'), 'NONSSL', null, null, true) . '" class="button icon-monitor with-tooltip" title="' . $lC_Language->get('icon_view_in_catalog') . '"></a>
                   </span>
                   <span class="button-group">
                     <a href="' . ((int)($_SESSION['admin']['access'][$_module] < 4) ? '#' : 'javascript://" onclick="deleteProduct(\'' . $Qproducts->valueInt('products_id') . '\', \'' . urlencode($Qproducts->value('products_name')) . '\')') . '" class="button icon-trash with-tooltip' . ((int)($_SESSION['admin']['access'][$_module] < 4) ? ' disabled' : NULL) . '" title="' . $lC_Language->get('icon_delete') . '"></a>
                   </span>
                 </td>';
      $result['aaData'][] = array("$check", "$products", "$cats", "$class", "$price", "$qty", "$status", "$action");
      $result['entries'][] = array_merge($Qproducts->toArray(), $extra_data);      
      
      $Qproductscategories->freeResult();
      $Qimage->freeResult();
    }
    $result['sEcho'] = intval($_GET['sEcho']);

    $Qproducts->freeResult();

    return $result;
  }
 /*
  * Returns the icons used in the product listing
  *
  * @param integer $id The products id
  * @access public
  * @return string
  */
  public static function getlistingIcons($products_id) {
    global $lC_Language;
    
    $icons = '';
    if (self::hasSimpleOptions($products_id)) {
      $icons .= '<span class="icon-flow-branch icon-purple mid-margin-right with-tooltip" style="cursor:pointer;" title="' . $lC_Language->get('text_has_simple_options') . '"></span>';
    }
      
    return $icons;
  }
 /*
  * Returns the price info used in the product listing
  *
  * @param integer $data  The product data array
  * @access public
  * @return string
  */  
  public function getProductsListingPrice($data) {
    global $lC_Database, $lC_Language, $lC_Currencies;

    if ($customer_group_id == 0) $customer_group_id = DEFAULT_CUSTOMERS_GROUP_ID;
    
    $price = $lC_Currencies->format($data['products_price']);
    
    $Qspecials = $lC_Database->query('select specials_new_products_price, status from :table_specials where products_id = :products_id');
    $Qspecials->bindTable(':table_specials', TABLE_SPECIALS);
    $Qspecials->bindInt(':products_id', $data['products_id']);
    $Qspecials->execute();
    
    if ($Qspecials->numberOfRows() > 0) {
      $price = $price . ' <span class="tag glossy with-tooltip cursor-pointer' . (($Qspecials->valueInt('status') == 1) ? ' red-gradient' : ' grey-gradient') . '" title="' . (($Qspecials->valueInt('status') == 1) ? $lC_Language->get('text_special_enabled') : $lC_Language->get('text_special_disabled')) . '">' . $lC_Currencies->format($Qspecials->value('specials_new_products_price'), DECIMAL_PLACES) . '</span>';
    }
    
    $Qspecials->freeResult();
    
    if(DISPLAY_PRICE_WITH_TAX == 1) {
      $tax_data = lC_Tax_classes_Admin::getEntry($data['products_tax_class_id']);
      $price = ($data['products_price'] + ($tax_data['tax_rate']/100)*$data['products_price']);
      $price = $lC_Currencies->format($price, DECIMAL_PLACES);

      //$data['products_cost_with_tax'] = $data['products_cost'] + ($tax_data['tax_rate']/100)*$data['products_cost'];
      //$data['products_msrp_with_tax'] = ($data['products_msrp'] + ($tax_data['tax_rate']/100)*$data['products_msrp']);
    }

    return $price;
  }
 /*
  * Returns the qty info used in the product listing
  *
  * @param integer $data  The product data array
  * @access public
  * @return string
  */  
  public function getProductsListingQty($data) {
    return $data['products_quantity'];
  }  
  
 /*
  * Returns the data used on the dialog forms
  *
  * @param integer $id The products id
  * @access public
  * @return array
  */
  public static function getProductFormData($id = null) {
    global $_module, $lC_Database, $lC_Language;

    $lC_Language->loadIniFile('products.php');
    $lC_CategoryTree = new lC_CategoryTree_Admin();

    $result = array();

    if (isset($id) && is_numeric($id)) {

      $Qcategories = $lC_Database->query('select categories_id from :table_products_to_categories where products_id = :products_id');
      $Qcategories->bindTable(':table_products_to_categories', TABLE_PRODUCTS_TO_CATEGORIES);
      $Qcategories->bindInt(':products_id', $id);
      $Qcategories->execute();

      $in_categories = array();
      while ( $Qcategories->next() ) {
        $in_categories[] = $Qcategories->valueInt('categories_id');
      }

      $cnt = 0;
      $in_categories_path = '';
      foreach ( $in_categories as $category_id ) {
        $in_categories_path .= $lC_CategoryTree->getPath($category_id, 0, ' &raquo; ') . '<br />';
        if ($category_id == 0) {
          $categories_array[] = array('id' => $category_id,
                                      'text' => $lC_Language->get('top_category'));
        } else {
          $categories_array[] = array('id' => $category_id,
                                      'text' => $lC_CategoryTree->getPath($category_id, 0, ' &raquo; '));
        }
        $cnt++;
      }
      $result['inCategoriesCount'] = $cnt;
      $result['inCategoriesCheckbox'] = lc_draw_checkbox_field('product_categories[]', $categories_array, true, null, '&nbsp;<br />');

      if ( !empty($in_categories_path) ) {
        $in_categories_path = substr($in_categories_path, 0, -6);
        if (substr($in_categories_path, 0, 6) == '<br />') $in_categories_path = $lC_Language->get('top_category') . '<br />' . $in_categories_path;
      }
      $result['categoryPath'] = $in_categories_path;
    }

    $categories_array = array('0' => '-- ' . $lC_Language->get('top_category') . ' --');
    foreach ( $lC_CategoryTree->getArray() as $value ) {
      $pid = end(explode('_', $value['id']));
      if (lC_Categories_Admin::getParent($pid) != 0) {
        foreach (explode('_', $value['id']) as $cats) {
          if ($pid != $cats) {
            $Qcpn = $lC_Database->query('select categories_name from :table_categories_description where categories_id = :categories_id and language_id = :language_id');
            $Qcpn->bindTable(':table_categories_description', TABLE_CATEGORIES_DESCRIPTION);
            $Qcpn->bindInt(':language_id', $lC_Language->getID());
            $Qcpn->bindInt(':categories_id', $cats);
            $Qcpn->execute();
            
            $titlestr .= $Qcpn->value('categories_name') . ' &raquo; ';
          }
        }
        $title = $titlestr .  $value['title'];
        unset($titlestr);
      } else {
        $title = $value['title'];
      }
      $categories_array[$value['id']] = $title;
    }
    $result['categoriesArray'] = $categories_array;

    return $result;
  }
  /*function createPathNames($id) {
    global $lC_Database;
    
    $Qcpn = $lC_Database->query('select categories_name from :table_categories_description where parent_id = :categories_id and language_id = :language_id');
    $Qcpn->bindTable(':table_categories_description', TABLE_CATEGORIES_DESCRIPTION);
    $Qcpn->bindInt(':language_id', $lC_Language->getID());
    $Qcpn->bindInt(':categories_id', $id);
    $Qcpn->execute();
    
    $result = $Qcpn->toArray();
    
    if ($result['categoryId'] == 0) {
      $name = '<a href="index.php?action=listContent&categoryId=' . $result['id'] . '">' . $result['title'] . '</a>';  
      return $name;
    } else {
      $name = ' > <a href="index.php?action=listContent&categoryId=' . $result['id'] . '">' . $result['title'] . '</a>';
      return createPathNames($result['categoryId']) . " " . $name;
    }
  }*/
 /*
  * Return the data used on the preview dialog form
  *
  * @param integer $id The products id
  * @access public
  * @return array
  */
  public static function preview($id) {
    global $lC_Database, $lC_Language, $lC_Currencies;

    $lC_Image = new lC_Image_Admin();
    $lC_Language->loadIniFile('products.php');

    $result = array();

    $Qp = $lC_Database->query('select p.products_id, p.products_quantity, p.products_cost, p.products_price, p.products_msrp, p.products_model, p.products_sku, p.products_weight, p.products_weight_class, p.products_date_added, p.products_last_modified, p.products_status, p.products_tax_class_id, p.manufacturers_id, i.image from :table_products p left join :table_products_images i on (p.products_id = i.products_id and default_flag = :default_flag) where p.products_id = :products_id');
    $Qp->bindTable(':table_products', TABLE_PRODUCTS);
    $Qp->bindTable(':table_products_images', TABLE_PRODUCTS_IMAGES);
    $Qp->bindInt(':products_id', $id);
    $Qp->bindInt(':default_flag', 1);
    $Qp->execute();

    $Qpd = $lC_Database->query('select products_name, products_description, products_url, language_id from :table_products_description where products_id = :products_id');
    $Qpd->bindTable(':table_products_description', TABLE_PRODUCTS_DESCRIPTION);
    $Qpd->bindInt(':products_id', $id);
    $Qpd->execute();

    $pd_extra = array();
    while ( $Qpd->next() ) {
      $pd_extra['products_name'][$Qpd->valueInt('language_id')] = $Qpd->valueProtected('products_name');
      $pd_extra['products_description'][$Qpd->valueInt('language_id')] = $Qpd->value('products_description');
      $pd_extra['products_url'][$Qpd->valueInt('language_id')] = $Qpd->valueProtected('products_url');
    }

    $lC_ObjectInfo = new lC_ObjectInfo(array_merge($Qp->toArray(), $pd_extra));

    $products_name = $lC_ObjectInfo->get('products_name');
    $products_description = $lC_ObjectInfo->get('products_description');
    $products_url = $lC_ObjectInfo->get('products_url');

    $result['previewHtml'] = '<div>';
    foreach ( $lC_Language->getAll() as $l ) {
      $result['previewHtml'] .= '<span id="lang_' . $l['code'] . '"' . (($l['code'] == $lC_Language->getCode()) ? ' class="highlight"' : '') . '><a href="javascript:toggleDivBlocks(\'pName_\', \'pName_' . $l['code'] . '\'); toggleClass(\'lang_\', \'lang_' . $l['code'] . '\', \'highlight\', \'span\');">' . $lC_Language->showImage($l['code']) . '</a></span>&nbsp;&nbsp;';
    }
    $result['previewHtml'] .= '</div>';
    foreach ( $lC_Language->getAll() as $l ) {
      $result['previewHtml'] .= '<div id="pName_' . $l['code'] . '" ' . (($l['code'] != $lC_Language->getCode()) ? ' style="display: none;"' : '') . '>';
      $result['previewHtml'] .= '  <table border="0" width="100%" cellspacing="0" cellpadding="2">';
      $result['previewHtml'] .= '    <tr>';
      $result['previewHtml'] .= '      <td><h1>' . lc_output_string_protected($products_name[$l['id']]) . (!lc_empty($lC_ObjectInfo->get('products_model')) ? '<br /><span>' . $lC_ObjectInfo->getProtected('products_model') . '</span>': '') . '</h1></td>';
      $result['previewHtml'] .= '      <td align="right"><h1>' . $lC_Currencies->format($lC_ObjectInfo->get('products_price')) . '</h1></td>';
      $result['previewHtml'] .= '    </tr>';
      $result['previewHtml'] .= '  </table>';
      $result['previewHtml'] .= '  <p>' . $lC_Image->show($lC_ObjectInfo->get('image'), $products_name[$l['id']], 'align="right" hspace="5" vspace="5"', 'product_info') . $products_description[$l['id']] . '</p>';
      if ( !empty($products_url[$l['id']]) ) {
        $result['previewHtml'] .= '<p>' . sprintf($lC_Language->get('text_more_product_information'), lc_output_string_protected($products_url[$l['id']])) . '</p>';
      }
      $result['previewHtml'] .= '<p align="center">' . sprintf($lC_Language->get('text_product_date_added'), lC_DateTime::getLong($lC_ObjectInfo->get('products_date_added'))) . '</p>';
      $result['previewHtml'] .= '</div>';
    }

    return $result;
  }
 /*
  * Returns an array of product categories
  *
  * @param integer $id The categories id
  * @access public
  * @return array
  */
  public static function getCategoriesArray($id = null) {
    global $lC_Language;

    $lC_Language->loadIniFile('products.php');
    $lC_CategoryTree = new lC_CategoryTree();

    $result = array();
    $categories_array = array('' => $lC_Language->get('all_products'));
    foreach ( $lC_CategoryTree->getArray() as $value ) {
      $categories_array[end(explode('_', $value['id']))] = $value['title'];
    }
    $result['categoriesArray'] = $categories_array;


    return $result;
  }
 /*
  * Returns the product information
  *
  * @param integer $id The products id
  * @access public
  * @return array
  */
  public static function get($id) {
    global $lC_Database, $lC_Language, $lC_Currencies;

    $Qproducts = $lC_Database->query('select p.*, pd.* from :table_products p, :table_products_description pd where p.products_id = :products_id and p.products_id = pd.products_id and pd.language_id = :language_id');
    $Qproducts->bindTable(':table_products', TABLE_PRODUCTS);
    $Qproducts->bindTable(':table_products_description', TABLE_PRODUCTS_DESCRIPTION);
    $Qproducts->bindInt(':products_id', $id);
    $Qproducts->bindInt(':language_id', $lC_Language->getID());
    $Qproducts->execute();

    $data = array_merge(array('products_id' => $id), (array)$Qproducts->toArray());
    
    $Qproducts->freeResult(); 
    
    $Qspecials = $lC_Database->query('select * from :table_specials where products_id = :products_id');
    $Qspecials->bindTable(':table_specials', TABLE_SPECIALS);
    $Qspecials->bindInt(':products_id', $id);
    $Qspecials->execute();    
    
    $data['products_special_status'] = $Qspecials->valueInt('status');
    $data['products_special_price'] = $Qspecials->valueDecimal('specials_new_products_price');
    if ($Qspecials->value('start_date') != null) {
      $data['products_special_start_date'] = lC_DateTime::getShort($Qspecials->value('start_date'));
    }
    if ($Qspecials->value('expires_date') != null) {
      $data['products_special_expires_date'] = lC_DateTime::getShort($Qspecials->value('expires_date'));  
    }
    
    $Qspecials->freeResult();  
    
    $Qfeatured = $lC_Database->query('select status from :table_featured_products where products_id = :products_id limit 1');
    $Qfeatured->bindTable(':table_featured_products', TABLE_FEATURED_PRODUCTS);
    $Qfeatured->bindInt(':products_id', $id);
    $Qfeatured->execute();    
    
    $data['products_featured'] = $Qfeatured->valueInt('status');
    $Qfeatured->freeResult();  

    $variants_array = array();

    if ( $data['has_children'] == '1' ) {
      $QmultiSKUProducts = $lC_Database->query('select * from :table_products where parent_id = :parent_id');
      $QmultiSKUProducts->bindTable(':table_products', TABLE_PRODUCTS);
      $QmultiSKUProducts->bindInt(':parent_id', $data['products_id']);
      $QmultiSKUProducts->execute();

      while ( $QmultiSKUProducts->next() ) {
        $variants_array[$QmultiSKUProducts->valueInt('products_id')]['data'] = array('cost' => $QmultiSKUProducts->value('products_cost'),
		                                                                                 'price' => $QmultiSKUProducts->value('products_price'),
		                                                                                 'msrp' => $QmultiSKUProducts->value('products_msrp'),
		                                                                                 'tax_class_id' => $QmultiSKUProducts->valueInt('products_tax_class_id'),
		                                                                                 'model' => $QmultiSKUProducts->value('products_model'),
		                                                                                 'sku' => $QmultiSKUProducts->value('products_sku'),
		                                                                                 'quantity' => $QmultiSKUProducts->value('products_quantity'),
		                                                                                 'weight' => $QmultiSKUProducts->value('products_weight'),
		                                                                                 'weight_class_id' => $QmultiSKUProducts->valueInt('products_weight_class'),
		                                                                                 'availability_shipping' => 1,
																																										 'status' => $QmultiSKUProducts->valueInt('products_status'));

        $Qvariants = $lC_Database->query('select pv.default_combo, pv.default_visual, pvg.id as group_id, pvg.title as group_title, pvg.module, pvv.id as value_id, pvv.title as value_title, pvv.visual as visual, pvv.sort_order as value_sort_order from :table_products_variants pv, :table_products_variants_groups pvg, :table_products_variants_values pvv where pv.products_id = :products_id and pv.products_variants_values_id = pvv.id and pvv.languages_id = :languages_id and pvv.products_variants_groups_id = pvg.id and pvg.languages_id = :languages_id order by pvg.sort_order, pvg.title');
        $Qvariants->bindTable(':table_products_variants', TABLE_PRODUCTS_VARIANTS);
        $Qvariants->bindTable(':table_products_variants_groups', TABLE_PRODUCTS_VARIANTS_GROUPS);
        $Qvariants->bindTable(':table_products_variants_values', TABLE_PRODUCTS_VARIANTS_VALUES);
        $Qvariants->bindInt(':products_id', $QmultiSKUProducts->valueInt('products_id'));
        $Qvariants->bindInt(':languages_id', $lC_Language->getID());
        $Qvariants->bindInt(':languages_id', $lC_Language->getID());
        $Qvariants->execute();

        while ( $Qvariants->next() ) {
          $variants_array[$QmultiSKUProducts->valueInt('products_id')]['values'][$Qvariants->valueInt('group_id')][$Qvariants->valueInt('value_id')] = array('value_id' => $Qvariants->valueInt('value_id'),
                                                                                                                                                             'group_title' => $Qvariants->value('group_title'),
                                                                                                                                                             'value_title' => $Qvariants->value('value_title'),
                                                                                                                                                             'sort_order' => $Qvariants->value('value_sort_order'),
                                                                                                                                                             'default' => (bool)$Qvariants->valueInt('default_combo'),
                                                                                                                                                             'default_visual' => $Qvariants->valueInt('default_visual'),
                                                                                                                                                             'module' => $Qvariants->value('module'),
                                                                                                                                                             'visual' => $Qvariants->value('visual'));
        }
      }
      $QmultiSKUProducts->freeResult();
    }

    $data['variants'] = $variants_array;

    $Qattributes = $lC_Database->query('select id, value from :table_product_attributes where products_id = :products_id and languages_id in (0, :languages_id)');
    $Qattributes->bindTable(':table_product_attributes', TABLE_PRODUCT_ATTRIBUTES);
    $Qattributes->bindInt(':products_id', $id);
    $Qattributes->bindInt(':languages_id', $lC_Language->getID());
    $Qattributes->execute();

    $attributes_array = array();

    while ( $Qattributes->next() ) {
      // if the value is date, reformat for datepicker
      $value = (substr($Qattributes->value('value'), 4, 1) == '-') ? lC_DateTime::getShort($Qattributes->value('value')) : $Qattributes->value('value');      
      $attributes_array[$Qattributes->valueInt('id')] = $value;
    }

    $data['attributes'] = $attributes_array;
    
    $Qattributes->freeResult(); 
    
    $Qimages = $lC_Database->query('select id, image, default_flag from :table_products_images where products_id = :products_id order by sort_order');
    $Qimages->bindTable(':table_products_images', TABLE_PRODUCTS_IMAGES);
    $Qimages->bindInt(':products_id', $id);
    $Qimages->execute();

    while ($Qimages->next()) {
      if ($Qimages->valueInt('default_flag') == '1') {
        $data['image'] = $Qimages->value('image');
      }
    }
    
    $Qimages->freeResult();
    
    // load subproducts
    $Qsubproducts = $lC_Database->query('select p.*, pd.* from :table_products p, :table_products_description pd where p.parent_id = :products_id and p.products_id = pd.products_id and pd.language_id = :language_id');
    $Qsubproducts->bindTable(':table_products', TABLE_PRODUCTS);
    $Qsubproducts->bindTable(':table_products_description', TABLE_PRODUCTS_DESCRIPTION);
    $Qsubproducts->bindInt(':products_id', $id);
    $Qsubproducts->bindInt(':language_id', $lC_Language->getID());    
    $Qsubproducts->execute();
    
    $subproducts_array = array();
    while ( $Qsubproducts->next() ) {
      // subproduct images
      $Qimages = $lC_Database->query('select id, image, default_flag from :table_products_images where products_id = :sub_products_id order by sort_order');
      $Qimages->bindTable(':table_products_images', TABLE_PRODUCTS_IMAGES);
      $Qimages->bindInt(':sub_products_id', $Qsubproducts->valueInt('products_id'));
      $Qimages->execute();

      $subproducts_array[] = array_merge((array)$Qsubproducts->toArray(), array('image' => $Qimages->value('image')));
      
      $Qimages->freeResult();      
    }    
    $data['subproducts'] = $subproducts_array;
    if (sizeof($data['subproducts']) > 0) $data['has_subproducts'] = 1;
    
    $Qsubproducts->freeResult();      
    
    // load simple options
    $Qoptions = $lC_Database->query('select so.options_id, so.products_id, so.sort_order, so.status, vg.title, vg.module from :table_products_simple_options so left join :table_products_variants_groups vg on (so.options_id = vg.id) where so.products_id = :products_id and vg.languages_id = :languages_id order by so.sort_order');
    $Qoptions->bindTable(':table_products_simple_options', TABLE_PRODUCTS_SIMPLE_OPTIONS);
    $Qoptions->bindTable(':table_products_variants_groups', TABLE_PRODUCTS_VARIANTS_GROUPS);
    $Qoptions->bindInt(':products_id', $id);
    $Qoptions->bindInt(':languages_id', $lC_Language->getID());
    $Qoptions->execute();
    
    while ($Qoptions->next()) {
      $data['simple_options'][] = $Qoptions->toArray();      
      
      $Qvalues = $lC_Database->query('select sov.products_id, sov.options_id, sov.values_id, sov.price_modifier, sov.customers_group_id, vv.title from :table_products_simple_options_values sov left join :table_products_variants_values vv on (sov.values_id = vv.id) where sov.options_id = :options_id and vv.languages_id = :languages_id');
      $Qvalues->bindTable(':table_products_simple_options_values', TABLE_PRODUCTS_SIMPLE_OPTIONS_VALUES);
      $Qvalues->bindTable(':table_products_variants_values', TABLE_PRODUCTS_VARIANTS_VALUES);
      $Qvalues->bindInt(':options_id', $Qoptions->valueInt('options_id'));
      $Qvalues->bindInt(':languages_id', $lC_Language->getID());
      $Qvalues->execute();
      
      while ($Qvalues->next()) {
        $data['simple_options']['values'][] = $Qvalues->toArray();      
      }
      $Qvalues->freeResult();    
    }
    $Qoptions->freeResult();
    
    if(DISPLAY_PRICE_WITH_TAX == 1) {
      $tax_data = lC_Tax_classes_Admin::getEntry($data['products_tax_class_id']);
      $data['products_price_with_tax'] = ($data['products_price'] + ($tax_data['tax_rate']/100)*$data['products_price']);
      //$data['products_cost_with_tax'] = $data['products_cost'] + ($tax_data['tax_rate']/100)*$data['products_cost'];
      $data['products_msrp_with_tax'] = ($data['products_msrp'] + ($tax_data['tax_rate']/100)*$data['products_msrp']);
    }
    return $data;
  }
 /*
  * Save the product
  *
  * @param integer $id The products id to update, null on insert
  * @param array $data The products information
  * @access public
  * @return boolean
  */
  public static function save($id = null, $data) {
    global $lC_Database, $lC_Language, $lC_Image, $lC_CategoryTree;

    $error = false;
    
    $lC_Database->startTransaction();

    if ( is_numeric($id) ) {
      $Qproduct = $lC_Database->query('update :table_products set parent_id = :parent_id, products_quantity = :products_quantity, products_cost = :products_cost, products_price = :products_price, products_msrp = :products_msrp, products_model = :products_model, products_sku = :products_sku, products_weight = :products_weight, products_weight_class = :products_weight_class, products_status = :products_status, products_tax_class_id = :products_tax_class_id, products_last_modified = now() where products_id = :products_id');
      $Qproduct->bindInt(':products_id', $id);
    } else {
      $Qproduct = $lC_Database->query('insert into :table_products (parent_id, products_quantity, products_cost, products_price, products_msrp, products_model, products_sku, products_weight, products_weight_class, products_status, products_tax_class_id, products_ordered, products_date_added) values (:parent_id, :products_quantity, :products_cost, :products_price, :products_msrp, :products_model, :products_sku, :products_weight, :products_weight_class, :products_status, :products_tax_class_id, :products_ordered, :products_date_added)');
      $Qproduct->bindRaw(':products_date_added', 'now()');
      $Qproduct->bindInt(':products_ordered', $data['products_ordered']);
    }
    
    // set parent status
    if ( isset($_POST['products_status']) && $_POST['products_status'] == 'active' ) $data['status'] = 1;
    if ( isset($_POST['products_status']) && $_POST['products_status'] == 'inactive' ) $data['status'] = -1;
    if ( isset($_POST['products_status']) && $_POST['products_status'] == 'recurring' ) $data['status'] = 0;
    
    $Qproduct->bindTable(':table_products', TABLE_PRODUCTS);
    $Qproduct->bindInt(':parent_id', $data['parent_id']);
    $Qproduct->bindInt(':products_quantity', $data['quantity']);
    $Qproduct->bindFloat(':products_cost', $data['cost']);
    $Qproduct->bindFloat(':products_price', $data['price']);
    $Qproduct->bindFloat(':products_msrp', $data['msrp']);
    $Qproduct->bindValue(':products_model', $data['model']);
    $Qproduct->bindValue(':products_sku', $data['sku']);
    $Qproduct->bindFloat(':products_weight', $data['weight']);
    $Qproduct->bindInt(':products_weight_class', $data['weight_class']);
    $Qproduct->bindInt(':products_tax_class_id', $data['tax_class_id']);   
    $Qproduct->bindInt(':products_status', $data['status']);
    $Qproduct->setLogging($_SESSION['module'], $id);
    $Qproduct->execute();
      
    if ( is_numeric($id) ) {
      $products_id = $id;
    } else {
      $products_id = $lC_Database->nextID();
    }

    // products to categories
    if ( $lC_Database->isError() ) {
      $error = true;
    } else {

      $Qcategories = $lC_Database->query('delete from :table_products_to_categories where products_id = :products_id');
      $Qcategories->bindTable(':table_products_to_categories', TABLE_PRODUCTS_TO_CATEGORIES);
      $Qcategories->bindInt(':products_id', $products_id);
      $Qcategories->setLogging($_SESSION['module'], $products_id);
      $Qcategories->execute();

      if ( $lC_Database->isError() ) { 
        $error = true;
      } else {
        if ( isset($data['categories']) && !empty($data['categories']) ) {
          foreach ($data['categories'] as $category_id) {
            $Qp2c = $lC_Database->query('insert into :table_products_to_categories (products_id, categories_id) values (:products_id, :categories_id)');
            $Qp2c->bindTable(':table_products_to_categories', TABLE_PRODUCTS_TO_CATEGORIES);
            $Qp2c->bindInt(':products_id', $products_id);
            $Qp2c->bindInt(':categories_id', $category_id);
            $Qp2c->setLogging($_SESSION['module'], $products_id);
            $Qp2c->execute();

            if ( $lC_Database->isError() ) {
              $error = true;
              break;
            }
          }
        }
      }
    }
    
    // product images
    if ( $error === false ) {
      $images = array();

      $products_image = new upload('products_image');
      
      $products_image->set_extensions(array('gif', 'jpg', 'jpeg', 'png'));

      if ( $products_image->exists() ) {
        $products_image->set_destination(realpath('../images/products/originals'));

        if ( $products_image->parse() && $products_image->save() ) {
          $images[] = $products_image->filename;
        }
      }

      if ( isset($data['localimages']) ) {
        foreach ($data['localimages'] as $image) {
          $image = basename($image);

          if (@file_exists('../images/products/_upload/' . $image)) {
            copy('../images/products/_upload/' . $image, '../images/products/originals/' . $image);
            @unlink('../images/products/_upload/' . $image);

            $images[] = $image;
          }
        }
      }

      $default_flag = 1;
      
      foreach ($images as $image) {
        $Qimage = $lC_Database->query('insert into :table_products_images (products_id, image, default_flag, sort_order, date_added) values (:products_id, :image, :default_flag, :sort_order, :date_added)');
        $Qimage->bindTable(':table_products_images', TABLE_PRODUCTS_IMAGES);
        $Qimage->bindInt(':products_id', $products_id);
        $Qimage->bindValue(':image', $image);
        $Qimage->bindInt(':default_flag', $default_flag);
        $Qimage->bindInt(':sort_order', 0);
        $Qimage->bindRaw(':date_added', 'now()');
        $Qimage->setLogging($_SESSION['module'], $products_id);
        $Qimage->execute();

        if ( $lC_Database->isError() ) {
          $error = true;
        } else {
          foreach ($lC_Image->getGroups() as $group) {
            if ($group['id'] != '1') {
              $lC_Image->resize($image, $group['id']);
            }
          }
        }

        $default_flag = 0;
      }
    }
    
    // product description
    if ( $error === false ) {
      if ( isset($data['categories']) && !empty($data['categories']) ) {
        $cPath = $lC_CategoryTree->getcPath($data['categories'][0]);
      } else {
        $cPath = ($category_id != '') ? $lC_CategoryTree->getcPath($category_id) : 0;
      }
      foreach ($lC_Language->getAll() as $l) {
        // this code will be revisited
        // if (self::validatePermalink($data['products_keyword'][$l['id']], $id, 2, $l['id']) != 1) {
        //   $data['products_keyword'][$l['id']] = $data['products_keyword'][$l['id']] . '-link';
        // }
        
        if (is_numeric($id)) {
          $Qpd = $lC_Database->query('update :table_products_description set products_name = :products_name, products_description = :products_description, products_keyword = :products_keyword, products_tags = :products_tags, products_url = :products_url where products_id = :products_id and language_id = :language_id');
        } else {
          $Qpd = $lC_Database->query('insert into :table_products_description (products_id, language_id, products_name, products_description, products_keyword, products_tags, products_url) values (:products_id, :language_id, :products_name, :products_description, :products_keyword, :products_tags, :products_url)');
        }
        
        $Qpd->bindTable(':table_products_description', TABLE_PRODUCTS_DESCRIPTION);
        $Qpd->bindInt(':products_id', $products_id);
        $Qpd->bindInt(':language_id', $l['id']);
        $Qpd->bindValue(':products_name', $data['products_name'][$l['id']]);
        $Qpd->bindValue(':products_description', $data['products_description'][$l['id']]);
        $Qpd->bindValue(':products_keyword', $data['products_keyword'][$l['id']]);
        $Qpd->bindValue(':products_tags', $data['products_tags'][$l['id']]);
        $Qpd->bindValue(':products_url', $data['products_url'][$l['id']]);
        $Qpd->setLogging($_SESSION['module'], $products_id);
        $Qpd->execute();

        if ( $lC_Database->isError() ) {
          $error = true;
          break;
        }
        
        // added for permalink
        if (is_numeric($id)) {
          $Qpl = $lC_Database->query('update :table_permalinks set permalink = :permalink, query = :query where item_id = :item_id and type = :type and language_id = :language_id');
        } else {
          $Qpl = $lC_Database->query('insert into :table_permalinks (item_id, language_id, type, query, permalink) values (:item_id, :language_id, :type, :query, :permalink)');
        }
        
        $Qpl->bindTable(':table_permalinks', TABLE_PERMALINKS);
        $Qpl->bindInt(':item_id', $products_id);
        $Qpl->bindInt(':language_id', $l['id']);
        $Qpl->bindInt(':type', 2);
        $Qpl->bindValue(':query', 'cPath=' . $cPath);
        $Qpl->bindValue(':permalink', $data['products_keyword'][$l['id']]);
        $Qpl->setLogging($_SESSION['module'], $products_id);
        $Qpl->execute();

        if ( $lC_Database->isError() ) {
          $error = true;
          break;
        }
      }
    }
    
    // product attributes
    if ( $error === false ) {
      if ( isset($data['attributes']) && !empty($data['attributes']) ) {

        foreach ( $data['attributes'] as $attributes_id => $value ) {

          if ( is_array($value) ) {
          } elseif ( !empty($value) && $value != 'NULL') {
            $Qcheck = $lC_Database->query('select id from :table_product_attributes where products_id = :products_id and id = :id limit 1');
            $Qcheck->bindTable(':table_product_attributes', TABLE_PRODUCT_ATTRIBUTES);
            $Qcheck->bindInt(':products_id', $products_id);
            $Qcheck->bindInt(':id', $attributes_id);
            $Qcheck->execute();

            if ( $Qcheck->numberOfRows() === 1 ) {
              $Qattribute = $lC_Database->query('update :table_product_attributes set value = :value where products_id = :products_id and id = :id');
            } else {
              $Qattribute = $lC_Database->query('insert into :table_product_attributes (id, products_id, languages_id, value) values (:id, :products_id, :languages_id, :value)');
              $Qattribute->bindInt(':languages_id', $lC_Language->getID());
            }
            
            $Qattribute->bindTable(':table_product_attributes', TABLE_PRODUCT_ATTRIBUTES);
            $Qattribute->bindValue(':value', $value);
            $Qattribute->bindInt(':products_id', $products_id);
            $Qattribute->bindInt(':id', $attributes_id);
            $Qattribute->execute();

            if ( $lC_Database->isError() ) {
              $error = true;
              break;
            }
          }
        }
      }
    }
 

    // simple options
    if ( $error === false ) {
      
      // delete the simple options
      $Qdel = $lC_Database->query('delete from :table_products_simple_options where products_id = :products_id');
      $Qdel->bindTable(':table_products_simple_options', TABLE_PRODUCTS_SIMPLE_OPTIONS);
      $Qdel->bindInt(':products_id', $products_id);
      $Qdel->setLogging($_SESSION['module'], $products_id);
      $Qdel->execute();                    
      
      // delete the simple options values
      $Qdel = $lC_Database->query('delete from :table_products_simple_options_values where products_id = :products_id');
      $Qdel->bindTable(':table_products_simple_options_values', TABLE_PRODUCTS_SIMPLE_OPTIONS_VALUES);
      $Qdel->bindInt(':products_id', $products_id);
      $Qdel->setLogging($_SESSION['module'], $products_id);
      $Qdel->execute();

      // if values are set, save them
      if ( isset($data['simple_options_group_name']) && !empty($data['simple_options_group_name']) ) {   
        foreach ( $data['simple_options_group_name'] as $group_id => $value ) {
          
          // add the new option
          $Qoptions = $lC_Database->query('insert into :table_products_simple_options (options_id, products_id, sort_order, status) values (:options_id, :products_id, :sort_order, :status)');
          $Qoptions->bindTable(':table_products_simple_options', TABLE_PRODUCTS_SIMPLE_OPTIONS);
          $Qoptions->bindInt(':options_id', $group_id);
          $Qoptions->bindInt(':products_id', $products_id);
          $Qoptions->bindInt(':sort_order', $data['simple_options_group_sort_order'][$group_id]);
          $Qoptions->bindInt(':status', $data['simple_options_group_status'][$group_id]);
          $Qoptions->setLogging($_SESSION['module'], $products_id);
          $Qoptions->execute();

          if ( $lC_Database->isError() ) {
            $error = true;
            break;
          }  
          
          // add the new option values
          if (is_array($data['simple_options_entry_price_modifier'])) {
            foreach ( $data['simple_options_entry_price_modifier'] as $customers_group_id => $options ) {
              foreach ( $options as $options_id => $option_value ) {
                if ($options_id == $group_id) {
                  foreach ( $option_value as $values_id => $price_modifier ) {
                    $Qoptval = $lC_Database->query('insert into :table_products_simple_options_values (products_id, values_id, options_id, customers_group_id, price_modifier) values (:products_id, :values_id, :options_id, :customers_group_id, :price_modifier)');
                    $Qoptval->bindTable(':table_products_simple_options_values', TABLE_PRODUCTS_SIMPLE_OPTIONS_VALUES);
                    $Qoptval->bindInt(':products_id', $products_id);
                    $Qoptval->bindInt(':values_id', $values_id);
                    $Qoptval->bindInt(':options_id', $options_id);
                    $Qoptval->bindInt(':customers_group_id', $customers_group_id);
                    $Qoptval->bindFloat(':price_modifier', (float)$price_modifier);
                    $Qoptval->setLogging($_SESSION['module'], $products_id);
                    $Qoptval->execute();

                    if ( $lC_Database->isError() ) {
                      $error = true;
                      break 4;
                    }            
                  }
                }
              }
            }
          }
        }
      }      
    }
    
    // specials pricing
    if ( $error === false ) {
      if ($data['specials_pricing_switch'] == 1) {
        $specials_id = self::hasSpecial($products_id);
        $specials_data = array('specials_id' => (int)$specials_id,
                               'products_id' => (int)$products_id,
                               'specials_price' => $data['products_special_price1'],
                               'specials_start_date' => $data['products_special_start_date1'],
                               'specials_expires_date' => $data['products_special_expires_date1'],
                               'specials_status' => (($data['products_special_pricing_enable1'] != '') ? 1 : 0));
        lC_Specials_Admin::save((int)$specials_id, $specials_data);
      }
    }   
    
    if ( $error === false ) {
      $lC_Database->commitTransaction();
      
      lC_Cache::clear('categories');
      lC_Cache::clear('category_tree');
      lC_Cache::clear('also_purchased');
      
      return $products_id; // Return the products id for use with the save_close buttons
    }

    $lC_Database->rollbackTransaction();

    return false;
  }
 /*
  * Duplicate / Link a product
  *
  * @param integer $id The products id
  * @param integer $category_id The categories id
  * @param string $type The copy type; duplicate or link
  * @access public
  * @return boolean
  */
  public static function copy($id, $category_id, $type) {
    global $lC_Database, $lC_CategoryTree;

    $category_array = explode('_', $category_id);

    if ( $type == 'link' ) {
      $Qcheck = $lC_Database->query('select count(*) as total from :table_products_to_categories where products_id = :products_id and categories_id = :categories_id');
      $Qcheck->bindTable(':table_products_to_categories', TABLE_PRODUCTS_TO_CATEGORIES);
      $Qcheck->bindInt(':products_id', $id);
      $Qcheck->bindInt(':categories_id', end($category_array));
      $Qcheck->execute();

      if ( $Qcheck->valueInt('total') < 1 ) {
        $Qcat = $lC_Database->query('insert into :table_products_to_categories (products_id, categories_id) values (:products_id, :categories_id)');
        $Qcat->bindTable(':table_products_to_categories', TABLE_PRODUCTS_TO_CATEGORIES);
        $Qcat->bindInt(':products_id', $id);
        $Qcat->bindInt(':categories_id', end($category_array));
        $Qcat->setLogging($_SESSION['module'], $id);
        $Qcat->execute();

        if ( $Qcat->affectedRows() ) {
          return true;
        }
      }
    } elseif ( $type == 'duplicate' ) {
      $Qproduct = $lC_Database->query('select * from :table_products where products_id = :products_id');
      $Qproduct->bindTable(':table_products', TABLE_PRODUCTS);
      $Qproduct->bindInt(':products_id', $id);
      $Qproduct->execute();

      if ( $Qproduct->numberOfRows() === 1 ) {
        $error = false;

        $lC_Database->startTransaction();

        $Qnew = $lC_Database->query('insert into :table_products (products_quantity, products_cost, products_price, products_msrp, products_model, products_sku, products_date_added, products_weight, products_weight_class, products_status, products_tax_class_id, manufacturers_id) values (:products_quantity, :products_cost, :products_price, :products_msrp, :products_model, :products_sku, now(), :products_weight, :products_weight_class, 0, :products_tax_class_id, :manufacturers_id)');
        $Qnew->bindTable(':table_products', TABLE_PRODUCTS);
        $Qnew->bindInt(':products_quantity', $Qproduct->valueInt('products_quantity'));
        $Qnew->bindValue(':products_cost', $Qproduct->value('products_cost'));
        $Qnew->bindValue(':products_price', $Qproduct->value('products_price'));
        $Qnew->bindValue(':products_msrp', $Qproduct->value('products_msrp'));
        $Qnew->bindValue(':products_model', $Qproduct->value('products_model'));
        $Qnew->bindValue(':products_sku', $Qproduct->value('products_sku'));
        $Qnew->bindValue(':products_weight', $Qproduct->value('products_weight'));
        $Qnew->bindInt(':products_weight_class', $Qproduct->valueInt('products_weight_class'));
        $Qnew->bindInt(':products_tax_class_id', $Qproduct->valueInt('products_tax_class_id'));
        $Qnew->bindInt(':manufacturers_id', $Qproduct->valueInt('manufacturers_id'));
        $Qnew->setLogging($_SESSION['module']);
        $Qnew->execute();

        if ( $Qnew->affectedRows() ) {
          $new_product_id = $lC_Database->nextID();

          $Qdesc = $lC_Database->query('select * from :table_products_description where products_id = :products_id');
          $Qdesc->bindTable(':table_products_description', TABLE_PRODUCTS_DESCRIPTION);
          $Qdesc->bindInt(':products_id', $id);
          $Qdesc->execute();

          while ( $Qdesc->next() ) {
            $Qnewdesc = $lC_Database->query('insert into :table_products_description (products_id, language_id, products_name, products_description, products_keyword, products_tags, products_url, products_viewed) values (:products_id, :language_id, :products_name, :products_description, :products_keyword, :products_tags, :products_url, 0)');
            $Qnewdesc->bindTable(':table_products_description', TABLE_PRODUCTS_DESCRIPTION);
            $Qnewdesc->bindInt(':products_id', $new_product_id);
            $Qnewdesc->bindInt(':language_id', $Qdesc->valueInt('language_id'));
            $Qnewdesc->bindValue(':products_name', $Qdesc->value('products_name') . '_Copy');
            $Qnewdesc->bindValue(':products_description', $Qdesc->value('products_description'));
            $Qnewdesc->bindValue(':products_keyword', $Qdesc->value('products_keyword') . '-copy');
            $Qnewdesc->bindValue(':products_tags', $Qdesc->value('products_tags'));
            $Qnewdesc->bindValue(':products_url', $Qdesc->value('products_url'));
            $Qnewdesc->setLogging($_SESSION['module'], $new_product_id);
            $Qnewdesc->execute();

            if ( $lC_Database->isError() ) {
              $error = true;
              break;
            }
            
            // permalink addition
            $lC_CategoryTree = new lC_CategoryTree_Admin();
            $cPath = (end($category_array) != 0) ? $lC_CategoryTree->getcPath(end($category_array)) : 0;
            $Qpl = $lC_Database->query('insert into :table_permalinks (item_id, language_id, type, query, permalink) values (:item_id, :language_id, :type, :query, :permalink)');
            $Qpl->bindTable(':table_permalinks', TABLE_PERMALINKS);
            $Qpl->bindInt(':item_id', $new_product_id);
            $Qpl->bindInt(':language_id', $Qdesc->valueInt('language_id'));
            $Qpl->bindInt(':type', 2);
            $Qpl->bindValue(':query', 'cPath=' . $cPath);
            $Qpl->bindValue(':permalink', $Qdesc->value('products_keyword') . '-copy');
            $Qpl->execute();

            if ( $lC_Database->isError() ) {
              $error = true;
              break;
            }
          }

          $Qpb = $lC_Database->query('select * from :table_products_pricing where products_id = :products_id');
          $Qpb->bindTable(':table_products_pricing', TABLE_PRODUCTS_PRICING);
          $Qpb->bindInt(':products_id', $id);
          $Qpb->execute();

          while ( $Qpb->next() ) {
            $Qnewpb = $lC_Database->query('insert into :table_products_pricing (products_id, group_id, tax_class_id, qty_break, price_break, date_added) values (:products_id, :group_id, :tax_class_id, :qty_break, :price_break, :date_added)');
            $Qnewpb->bindTable(':table_products_pricing', TABLE_PRODUCTS_PRICING);
            $Qnewpb->bindInt(':products_id', $new_product_id);
            $Qnewpb->bindInt(':group_id', $Qpb->valueInt('group_id'));
            $Qnewpb->bindInt(':tax_class_id', $Qpb->valueInt('tax_class_id'));
            $Qnewpb->bindInt(':qty_break', $Qpb->valueInt('qty_break'));
            $Qnewpb->bindValue(':price_break', $Qpb->value('price_break'));
            $Qnewpb->bindRaw(':date_added', 'now()');
            $Qnewpb->setLogging($_SESSION['module'], $new_product_id);
            $Qnewpb->execute();

            if ( $lC_Database->isError() ) {
              $error = true;
              break;
            }
          }

          if ( $error === false ) {
            $Qp2c = $lC_Database->query('insert into :table_products_to_categories (products_id, categories_id) values (:products_id, :categories_id)');
            $Qp2c->bindTable(':table_products_to_categories', TABLE_PRODUCTS_TO_CATEGORIES);
            $Qp2c->bindInt(':products_id', $new_product_id);
            $Qp2c->bindInt(':categories_id', end($category_array));
            $Qp2c->setLogging($_SESSION['module'], $new_product_id);
            $Qp2c->execute();

            if ( $lC_Database->isError() ) {
              $error = true;
            }
          }

          if ( $error === false ) {
            $Qproductimages = $lC_Database->query('select * from :table_products_images where products_id = :products_id');
            $Qproductimages->bindTable(':table_products_images', TABLE_PRODUCTS_IMAGES);
            $Qproductimages->bindInt(':products_id', $id);
            $Qproductimages->execute();

            while ( $Qproductimages->next() ) {
              $Qpi = $lC_Database->query('insert into :table_products_images (products_id, image, default_flag, sort_order, date_added) values (:products_id, :image, :default_flag, :sort_order, :date_added)');
              $Qpi->bindTable(':table_products_images', TABLE_PRODUCTS_IMAGES);
              $Qpi->bindInt(':products_id', $new_product_id);
              $Qpi->bindValue(':image', $Qproductimages->value('image'));
              $Qpi->bindInt(':default_flag', $Qproductimages->value('default_flag'));
              $Qpi->bindInt(':sort_order', $Qproductimages->value('sort_order'));
              $Qpi->bindRaw(':date_added', 'now()');
              $Qpi->execute();

              if ( $lC_Database->isError() ) {
                $error = true;
                break;
              }
            }
          }
        } else {
          $error = true;
        }

        if ( $error === false ) {
          $lC_Database->commitTransaction();

          lC_Cache::clear('categories');
          lC_Cache::clear('category_tree');
          lC_Cache::clear('also_purchased');

          return true;
        } else {
          $lC_Database->rollbackTransaction();
        }
      }
    }

    return false;
  }
 /*
  * Batch copy products
  *
  * @param array $batch An array of product id's
  * @param integer $new_category_id The new category id
  * @param integer $type Copy as duplicate or link
  * @access public
  * @return boolean
  */
  public static function batchCopy($batch, $new_category_id, $type) {
    foreach ( $batch as $id ) {
      lC_Products_Admin::copy($id, $new_category_id, $type);
    }
    return true;
  }
 /*
  * Delete a product
  *
  * @param integer $id The products id to delete
  * @param array $categories An array of category id's, null = delete from all categories
  * @access public
  * @return boolean
  */
  public static function delete($id, $categories = null) {
    global $lC_Database;

    $lC_Image = new lC_Image_Admin();

    $delete_product = true;
    $error = false;

    $lC_Database->startTransaction();

    if ( is_array($categories) && !empty($categories) ) {
      $Qpc = $lC_Database->query('delete from :table_products_to_categories where products_id = :products_id and categories_id in :categories_id');
      $Qpc->bindTable(':table_products_to_categories', TABLE_PRODUCTS_TO_CATEGORIES);
      $Qpc->bindInt(':products_id', $id);
      $Qpc->bindRaw(':categories_id', '("' . implode('", "', $categories) . '")');
      $Qpc->setLogging($_SESSION['module'], $id);
      $Qpc->execute();

      if ( !$lC_Database->isError() ) {
        $Qcheck = $lC_Database->query('select products_id from :table_products_to_categories where products_id = :products_id limit 1');
        $Qcheck->bindTable(':table_products_to_categories', TABLE_PRODUCTS_TO_CATEGORIES);
        $Qcheck->bindInt(':products_id', $id);
        $Qcheck->execute();

        if ( $Qcheck->numberOfRows() > 0 ) {
          $delete_product = false;
        }
      } else {
        $error = true;
      }
    }

    if ( ($error === false) && ($delete_product === true) ) {
      $Qvariants = $lC_Database->query('select products_id from :table_products where parent_id = :parent_id');
      $Qvariants->bindTable(':table_products', TABLE_PRODUCTS);
      $Qvariants->bindInt(':parent_id', $id);
      $Qvariants->execute();

      while ( $Qvariants->next() ) {
        $Qsc = $lC_Database->query('delete from :table_shopping_carts where products_id = :products_id');
        $Qsc->bindTable(':table_shopping_carts', TABLE_SHOPPING_CARTS);
        $Qsc->bindInt(':products_id', $Qvariants->valueInt('products_id'));
        $Qsc->execute();

        if ( $lC_Database->isError() ) {
          $error = true;
        }

        if ( $error === false ) {
          $Qsccvv = $lC_Database->query('delete from :table_shopping_carts_custom_variants_values where products_id = :products_id');
          $Qsccvv->bindTable(':table_shopping_carts_custom_variants_values', TABLE_SHOPPING_CARTS_CUSTOM_VARIANTS_VALUES);
          $Qsccvv->bindInt(':products_id', $Qvariants->valueInt('products_id'));
          $Qsccvv->execute();

          if ( $lC_Database->isError() ) {
            $error = true;
          }
        }

        if ( $error === false ) {
          $Qpa = $lC_Database->query('delete from :table_products_variants where products_id = :products_id');
          $Qpa->bindTable(':table_products_variants', TABLE_PRODUCTS_VARIANTS);
          $Qpa->bindInt(':products_id', $Qvariants->valueInt('products_id'));
          $Qpa->setLogging($_SESSION['module'], $id);
          $Qpa->execute();

          if ( $lC_Database->isError() ) {
            $error = true;
          }
        }

        if ( $error === false ) {
          $Qp = $lC_Database->query('delete from :table_products where products_id = :products_id');
          $Qp->bindTable(':table_products', TABLE_PRODUCTS);
          $Qp->bindInt(':products_id', $Qvariants->valueInt('products_id'));
          $Qp->setLogging($_SESSION['module'], $id);
          $Qp->execute();

          if ( $lC_Database->isError() ) {
            $error = true;
          }
        }

        // QPB
        if ( $error === false ) {
          $Qpb = $lC_Database->query('delete from :table_products_pricing where products_id = :products_id');
          $Qpb->bindTable(':table_products_pricing', TABLE_PRODUCTS_PRICING);
          $Qpb->bindInt(':products_id', $Qvariants->valueInt('products_id'));
          $Qpb->setLogging($_SESSION['module'], $id);
          $Qpb->execute();

          if ( $lC_Database->isError() ) {
            $error = true;
          }
        }

      }

      if ( $error === false ) {
        $Qr = $lC_Database->query('delete from :table_reviews where products_id = :products_id');
        $Qr->bindTable(':table_reviews', TABLE_REVIEWS);
        $Qr->bindInt(':products_id', $id);
        $Qr->setLogging($_SESSION['module'], $id);
        $Qr->execute();

        if ( $lC_Database->isError() ) {
          $error = true;
        }
      }

      if ( $error === false ) {
        $Qsc = $lC_Database->query('delete from :table_shopping_carts where products_id = :products_id');
        $Qsc->bindTable(':table_shopping_carts', TABLE_SHOPPING_CARTS);
        $Qsc->bindInt(':products_id', $id);
        $Qsc->execute();

        if ( $lC_Database->isError() ) {
          $error = true;
        }
      }

      if ( $error === false ) {
        $Qsccvv = $lC_Database->query('delete from :table_shopping_carts_custom_variants_values where products_id = :products_id');
        $Qsccvv->bindTable(':table_shopping_carts_custom_variants_values', TABLE_SHOPPING_CARTS_CUSTOM_VARIANTS_VALUES);
        $Qsccvv->bindInt(':products_id', $id);
        $Qsccvv->execute();

        if ( $lC_Database->isError() ) {
          $error = true;
        }
      }

      if ( $error === false ) {
        $Qp2c = $lC_Database->query('delete from :table_products_to_categories where products_id = :products_id');
        $Qp2c->bindTable(':table_products_to_categories', TABLE_PRODUCTS_TO_CATEGORIES);
        $Qp2c->bindInt(':products_id', $id);
        $Qp2c->setLogging($_SESSION['module'], $id);
        $Qp2c->execute();

        if ( $lC_Database->isError() ) {
          $error = true;
        }
      }

      if ( $error === false ) {
        $Qs = $lC_Database->query('delete from :table_specials where products_id = :products_id');
        $Qs->bindTable(':table_specials', TABLE_SPECIALS);
        $Qs->bindInt(':products_id', $id);
        $Qs->setLogging($_SESSION['module'], $id);
        $Qs->execute();

        if ( $lC_Database->isError() ) {
          $error = true;
        }
      }

      if ( $error === false ) {
        $Qpa = $lC_Database->query('delete from :table_products_variants where products_id = :products_id');
        $Qpa->bindTable(':table_products_variants', TABLE_PRODUCTS_VARIANTS);
        $Qpa->bindInt(':products_id', $id);
        $Qpa->setLogging($_SESSION['module'], $id);
        $Qpa->execute();

        if ( $lC_Database->isError() ) {
          $error = true;
        }
      }

      if ( $error === false ) {
        $Qpd = $lC_Database->query('delete from :table_products_description where products_id = :products_id');
        $Qpd->bindTable(':table_products_description', TABLE_PRODUCTS_DESCRIPTION);
        $Qpd->bindInt(':products_id', $id);
        $Qpd->setLogging($_SESSION['module'], $id);
        $Qpd->execute();

        if ( $lC_Database->isError() ) {
          $error = true;
        }
      }

      if ( $error === false ) {
        $Qp = $lC_Database->query('delete from :table_products where products_id = :products_id');
        $Qp->bindTable(':table_products', TABLE_PRODUCTS);
        $Qp->bindInt(':products_id', $id);
        $Qp->setLogging($_SESSION['module'], $id);
        $Qp->execute();

        if ( $lC_Database->isError() ) {
          $error = true;
        }
      }
      if ( $error === false ) {
        $Qim = $lC_Database->query('select id, image from :table_products_images where products_id = :products_id');
        $Qim->bindTable(':table_products_images', TABLE_PRODUCTS_IMAGES);
        $Qim->bindInt(':products_id', $id);
        $Qim->execute();
        
        // added to check for other products using same image and do not delete
        $Qop = $lC_Database->query('select id from :table_products_images where image = :image');
        $Qop->bindTable(':table_products_images', TABLE_PRODUCTS_IMAGES);
        $Qop->bindInt(':image', $Qim->value('image'));
        $Qop->execute();
        
        if ($Qop->numberOfRows() < 2) {
          while ($Qim->next()) {
            $lC_Image->delete($Qim->valueInt('id'));
          }
        }
      }
      
      // QPB
      if ( $error === false ) {
        $Qpb = $lC_Database->query('delete from :table_products_pricing where products_id = :products_id');
        $Qpb->bindTable(':table_products_pricing', TABLE_PRODUCTS_PRICING);
        $Qpb->bindInt(':products_id', $id);
        $Qpb->setLogging($_SESSION['module'], $id);
        $Qpb->execute();

        if ( $lC_Database->isError() ) {
          $error = true;
        }
      }
      
      // permalink
      if ( $error === false ) {
        $Qpb = $lC_Database->query('delete from :table_permalinks where item_id = :item_id');
        $Qpb->bindTable(':table_permalinks', TABLE_PERMALINKS);
        $Qpb->bindInt(':item_id', $id);
        $Qpb->execute();

        if ( $lC_Database->isError() ) {
          $error = true;
        }
      }
    }
    
    // delete subproducts
    if ( ($error === false) && ($delete_product === true) ) {
      $Qcheck = $lC_Database->query('select products_id from :table_products where parent_id = :parent_id');
      $Qcheck->bindTable(':table_products', TABLE_PRODUCTS);
      $Qcheck->bindInt(':parent_id', $id);
      $Qcheck->execute();  
      
      while ( $Qcheck->next() ) {
        // delete the description data
        $Qdel = $lC_Database->query('delete from :table_products_description where products_id = :products_id');
        $Qdel->bindTable(':table_products_description', TABLE_PRODUCTS_DESCRIPTION);
        $Qdel->bindInt(':products_id', $Qcheck->valueInt('products_id'));
        $Qdel->setLogging($_SESSION['module'], $id);
        $Qdel->execute();
        
        // delete the image data
        $Qdel = $lC_Database->query('delete from :table_products_images where products_id = :products_id');
        $Qdel->bindTable(':table_products_images', TABLE_PRODUCTS_IMAGES);
        $Qdel->bindInt(':products_id', $Qcheck->valueInt('products_id'));
        $Qdel->setLogging($_SESSION['module'], $id);
        $Qdel->execute();        
      }     
      // delete the subproduct
      $Qdel = $lC_Database->query('delete from :table_products where products_id = :products_id');
      $Qdel->bindTable(':table_products', TABLE_PRODUCTS);
      $Qdel->bindInt(':products_id', $Qcheck->valueInt('products_id'));
      $Qdel->setLogging($_SESSION['module'], $id);
      $Qdel->execute();                      
      
      if ( $lC_Database->isError() ) {
        $error = true;
      }         
    }
    
    // delete simple options
    if ( ($error === false) && ($delete_product === true) ) {
      $Qcheck = $lC_Database->query('select options_id from :table_products_simple_options where products_id = :products_id');
      $Qcheck->bindTable(':table_products_simple_options', TABLE_PRODUCTS_SIMPLE_OPTIONS);
      $Qcheck->bindInt(':products_id', $id);
      $Qcheck->execute();
      // delete the simple options values
      while ( $Qcheck->next() ) {
        $Qdel = $lC_Database->query('delete from :table_products_simple_options_values where options_id = :options_id');
        $Qdel->bindTable(':table_products_simple_options_values', TABLE_PRODUCTS_SIMPLE_OPTIONS_VALUES);
        $Qdel->bindInt(':options_id', $Qcheck->valueInt('options_id'));
        $Qdel->setLogging($_SESSION['module'], $id);
        $Qdel->execute();
      } 
      // delete the simple option
      $Qdel = $lC_Database->query('delete from :table_products_simple_options where products_id = :products_id');
      $Qdel->bindTable(':table_products_simple_options', TABLE_PRODUCTS_SIMPLE_OPTIONS);
      $Qdel->bindInt(':products_id', $id);
      $Qdel->setLogging($_SESSION['module'], $id);
      $Qdel->execute();                    
      
      $Qcheck->freeResult();
      
      if ( $lC_Database->isError() ) {
        $error = true;
      }
    }
    
    if ( $error === false ) {
      $lC_Database->commitTransaction();

      lC_Cache::clear('categories');
      lC_Cache::clear('category_tree');
      lC_Cache::clear('also_purchased');
      lC_Cache::clear('box-whats_new');

      return true;
    }

    $lC_Database->rollbackTransaction();

    return false;
  }
 /*
  * Batch delete products
  *
  * @param array $batch An array of product id's to delete
  * @access public
  * @return boolean
  */
  public static function batchDelete($batch) {
    foreach ( $batch as $id ) {
      lC_Products_Admin::delete($id);
    }
    return true;
  }
 /*
  * Set the product date available
  *
  * @param integer $id The products id
  * @param array $data The products data
  * @access public
  * @return boolean
  */
  public static function setDateAvailable($id, $data) {
    global $lC_Database;

    $Qattribute = $lC_Database->query('select pa.id from :table_product_attributes pa, :table_templates_boxes tb where tb.code = :code and tb.modules_group = :modules_group and tb.id = pa.id and products_id = :products_id');
    $Qattribute->bindTable(':table_product_attributes', TABLE_PRODUCT_ATTRIBUTES);
    $Qattribute->bindTable(':table_templates_boxes', TABLE_TEMPLATES_BOXES);
    $Qattribute->bindValue(':code', 'date_available');
    $Qattribute->bindValue(':modules_group', 'product_attributes');
    $Qattribute->bindInt(':products_id', $id);
    $Qattribute->execute();

    $Qupdate = $lC_Database->query('update :table_product_attributes set value = :value where id = :id and products_id = :products_id');
    $Qupdate->bindTable(':table_product_attributes', TABLE_PRODUCT_ATTRIBUTES);
    $Qupdate->bindDate(':value', $data['date_available']);
    $Qupdate->bindInt(':id', $Qattribute->valueInt('id'));
    $Qupdate->bindInt(':products_id', $id);
    $Qupdate->setLogging($_SESSION['module'], $id);
    $Qupdate->execute();

    return ( $Qupdate->affectedRows() > 0 );
  }
 /*
  * Return the number of permalinks for a product
  *
  * @param string $permalink The permalink string to count
  * @access public
  * @return integer
  */
  public static function getPermalinkCount($permalink, $pid = null, $type = null, $lid = null) {
    global $lC_Database;
    
    $Qpermalinks = $lC_Database->query('select count(*) as total, item_id, permalink from :table_permalinks where permalink = :permalink');

    if (is_numeric($pid)) {
      $Qpermalinks->appendQuery('and item_id != :item_id');
      $Qpermalinks->bindInt(':item_id', $iid);
    }
    
    if (is_numeric($lid)) {
      $Qpermalinks->appendQuery('and language_id == :language_id');
      $Qpermalinks->bindInt(':language_id', $lid);
    }

    $Qpermalinks->bindTable(':table_permalinks', TABLE_PERMALINKS);
    $Qpermalinks->bindValue(':permalink', $permalink);
    $Qpermalinks->execute();
    
    if ($iid == $Qpermalinks->valueInt('item_id') && $permalink == $Qpermalinks->value('permalink')) {
      $permalink_count = 0;
    } else {  
      $permalink_count = $Qpermalinks->valueInt('total');
    }
    
    return $permalink_count;
  }
 /*
  * Validate the product permalink
  *
  * @param string $permalink The product permalink
  * @access public
  * @return array
  */
  public static function validatePermalink($permalink_array, $pid = null, $type = null, $lid = null) {
    $validated = true;
    
    if (is_array($permalink_array)) {
      foreach($permalink_array as $permalink) {
        if ( preg_match('/^[a-z0-9_-]+$/iD', $permalink) !== 1 ) $validated = false;
        if ( lC_Products_Admin::getPermalinkCount($permalink, $pid, $type, $lid) > 0) $validated = false;
      }
    } else {
      if ( preg_match('/^[a-z0-9_-]+$/iD', $permalink_array) !== 1 ) $validated = false;
      if ( lC_Products_Admin::getPermalinkCount($permalink_array, $pid, $type, $lid) > 0) $validated = false;
    }
    
    return $validated;
  }
 /*
  * Get the product attribute modules HTML
  *
  * @param string $section The product page section to display in
  * @access public
  * @return string
  */  
  public static function getProductAttributeModules($section = '') {
    global $lC_Database, $lC_Language, $_module;

    $aInfo = new lC_ObjectInfo(lC_Products_Admin::get($_GET[$_module]));
    $attributes = $aInfo->get('attributes');  
    
    $output = '';
    
    $Qattributes = $lC_Database->query('select id, code from :table_templates_boxes where modules_group = :modules_group order by code desc');
    $Qattributes->bindTable(':table_templates_boxes');
    $Qattributes->bindValue(':modules_group', 'product_attributes');
    $Qattributes->execute();
    while ( $Qattributes->next() ) {
      $module = basename($Qattributes->value('code'));
      if ( !class_exists('lC_ProductAttributes_' . $module) ) {
        if ( file_exists(DIR_FS_ADMIN . 'includes/modules/product_attributes/' . $module . '.php') ) {
          include(DIR_FS_ADMIN . 'includes/modules/product_attributes/' . $module . '.php');
        } else if (lC_Addons_Admin::hasAdminAddonsProductAttributesModule($module)) {
          include(lC_Addons_Admin::getAdminAddonsProductAttributesModulePath($module));
        }
      }
      if ( class_exists('lC_ProductAttributes_' . $module) ) {
        $module = 'lC_ProductAttributes_' . $module;
        $module = new $module();
        if ($module->getSection() == $section) {
          if (file_exists(DIR_FS_ADMIN . 'includes/languages/' . $lC_Language->getCode() . '/modules/product_attributes/' . $module->getCode() . '.php')) {
            $lC_Language->loadIniFile('/modules/product_attributes/' . $module->getCode() . '.php');
          } else {
            lC_Addons_Admin::loadAdminAddonsProductAttributesDefinitions($module->getCode());
          }
          
          $output .= '<div class="new-row-mobile six-columns six-columns-tablet twelve-columns-mobile no-margin-bottom">
                        <div class="twelve-columns strong mid-margin-bottom">
                          <span>' . $lC_Language->get('product_attributes_' . $module->getCode() . '_title') . '</span>' . lc_show_info_bubble($lC_Language->get('info_bubble_attributes_' . $module->getCode() . '_text'), null, 'info-spot on-left grey float-right mid-margin-bottom') . '
                        </div>
                        <div class="twelve-columns product-module-content margin-bottom">
                          ' . $module->setFunction((isset($attributes[$Qattributes->valueInt('id')]) ? $attributes[$Qattributes->valueInt('id')] : null)) . '
                        </div>
                      </div>';
        }
      }
    }    
    
    return $output;
  }
 /*
  * Return the product variant group data for options modal
  *
  * @access public
  * @return array
  */
  public static function getSimpleOptionData() {
    return lC_Product_variants_Admin::getVariantGroups();
  } 
 /*
  * Return the product variant entry data for options wizard modal 
  *
  * @access public
  * @return array
  */
  public static function getSimpleOptionEntryData($eData) { 
    global $lC_Database;
    
    $veData = lC_Product_variants_Admin::getVariantEntries($eData['group']);
    
    $optionsArr = array();
    foreach($veData as $key => $value) {
      $Qoption = $lC_Database->query('select price_modifier from :table_products_simple_options_values where options_id = :options_id limit 1');
      $Qoption->bindTable(':table_products_simple_options_values', TABLE_PRODUCTS_SIMPLE_OPTIONS_VALUES);
      $Qoption->bindValue(':options_id', $value['id']);
      $Qoption->bindValue(':languages_id', $value['languages_id']);
      $Qoption->execute();      
      
      $optionsArr[$key] = array('id' => $value['id'],
                                'languages_id' => $value['languages_id'],
                                'products_variants_groups_id' => $value['products_variants_groups_id'],
                                'title' => $value['title'],
                                'visual' => $value['visual'],
                                'price_modifier' => $Qoption->valueDecimal('price_modifier'));
      $Qoption->freeResult();
    }
    
    if (empty($optionsArr[0])) $optionsArr['rpcStatus'] = '-2';
    
    return $optionsArr;
  } 
 /*
  *  Return the product simple options accordian listing content
  *
  * @access public
  * @return array
  */
  public static function getSimpleOptionsContent($options = array()) {
    $content = '';
    
    $content .= self::_getSimpleOptionsTbody($options);
    
    return $content;
  }     
 /*
  * Return the product simple options tbody content
  *
  * @param array $options The product simple options array
  * @access public
  * @return string
  */  
  private static function _getSimpleOptionsTbody($options) {
    $tbody = '';    
    if (isset($options) && !empty($options)) {
      foreach ($options as $key => $so) {
        if ( (isset($so['title']) && $so['title'] != NULL)  ){
          $items = '';
          $itemsInput = '';
          if (is_array($options['values'])) {
            foreach ($options['values'] as $k => $v) {    
              if (($v['options_id'] == $so['options_id']) && $v['customers_group_id'] == DEFAULT_CUSTOMERS_GROUP_ID && $so['products_id'] == $v['products_id']) {
                $items .= '<div class="small"><span class="icon-right icon-blue with-small-padding"></span>' . $v['title'] . '</div>';
                $itemsInput .= '<input type="hidden" id="simple_options_entry_' . $v['options_id'] . '_' . $v['values_id'] . '" name="simple_options_entry[' . $v['options_id'] . '][' . $v['values_id'] . ']" value="' . $v['title'] . '">';
              }
            }
          }
          
          $statusIcon = (isset($so['status']) && $so['status'] == '1') ? '<span class="icon-tick icon-size2 icon-green"></span>' : '<span class="icon-cross icon-size2 icon-red"></span>';
          
          
          $tbody .= '<tr id="tre-' . $so['options_id'] .'">' .
                    '  <td width="16px" style="cursor:move;" class="dragsort"><span class="icon-list icon-grey icon-size2"></span></td>' .
                    '  <td width="16px" style="cursor:pointer;" onclick="toggleSimpleOptionsRow(\'#drope' . $so['options_id'] . '\');"><span id="drope' . $so['options_id'] . '_span" class="toggle-icon icon-squared-plus icon-grey icon-size2"></span></td>' .
                    '  <td width="40%">' . $so['title'] . '<div class="small-margin-top dropall" id="drope' . $so['options_id'] . '" style="display:none;"><span>' . $items . '</span></div></td>' .
                    '  <td width="30%" class="hide-below-480">' . $so['module'] . '</td>' .
                    '  <td width="10%" class="sort hide-below-480"></td>' .
                    '  <td width="15%" align="center" style="cursor:pointer;" onclick="toggleSimpleOptionsStatus(this, \'' . $so['options_id'] . '\');">' . $statusIcon . '</td>' .
                    '  <td width="15%" align="right">
                         <span class="icon-pencil icon-orange icon-size2 margin-right with-tooltip" data-tooltip-options=\'{"classes":["grey-gradient"],"position":"left"}\' title="Edit Entry" style="cursor:pointer;" onclick="addSimpleOption(\'' . $so['options_id'] . '\')"></span>
                         <span class="icon-trash icon-size2 icon-red with-tooltip" data-tooltip-options=\'{"classes":["grey-gradient"],"position":"right"}\' title="Remove Entry" style="cursor:pointer;" onclick="removeSimpleOptionsRow(\'' . $so['options_id'] . '\');"></span>
                       </td>' .
                    '  <input type="hidden" name="simple_options_group_name[' . $so['options_id'] . ']" value="' . $so['title'] . '">' .
                    '  <input type="hidden" name="simple_options_group_type[' . $so['options_id'] . ']" value="' . $so['module'] . '">' .
                    '  <input class="sort" type="hidden" name="simple_options_group_sort_order[' . $so['options_id'] . ']" value="' . $so['sort_order'] . '">' .
                    '  <input type="hidden" id="simple_options_group_status_' . $so['options_id'] . '" name="simple_options_group_status[' . $so['options_id'] . ']" value="' . $so['status'] . '">' . $itemsInput  .
                    '</tr>';
        }
      }
    }
    
    return $tbody;    
  }
 /*
  *  Return the product simple options accordian price listing content
  *
  * @access public
  * @return array
  */
  public static function getOptionsPricingContent() {
    global $lC_Language, $pInfo;  
    
    $content = '';
    $groups = lC_Customer_groups_Admin::getAll();
    foreach($groups['entries'] as $key => $value) {
      $content .= '<dt id="dt-' . $value['customers_group_id'] . '"><span class="strong">' . $value['customers_group_name'] . '</span></dt>' .
                  '<dd id="dd-' . $value['customers_group_id'] . '">' .
                  '  <div class="with-padding" id="options-pricing-entries-div-' . $value['customers_group_id'] . '">';
                  
      if (isset($pInfo) && is_array($pInfo->get('simple_options'))) {                  
        $content .= '<div class="simple-options-pricing-container">' .
                    '  <div class="big-text underline margin-top" style="padding-bottom:8px;">' . $lC_Language->get('text_simple_options') . '</div>' .
                    '  <table class="simple-table simple-options-pricing-table">' .
                    '    <tbody id="tbody-simple-options-pricing-' . $value['customers_group_id'] . '">' . lC_Products_Admin::getSimpleOptionsPricingTbody($pInfo->get('simple_options'), $value['customers_group_id']) . '</tbody>' .
                    '  </table>' . 
                    '</div>';
                    
      } else {
        $content .= '<table class="simple-table no-options-defined"><tbody id="tbody-options-pricing-' . $value['customers_group_id'] . '"><tr id="no-options-' . $value['customers_group_id'] . '"><td>' . $lC_Language->get('text_no_options_defined') . '</td></tr></tbody></table>'; 
      }
                
      $content .= '  </div>' .
                  '</dd>';  
    }
    
    return $content;
  }   
 /*
  * Determine the min/max product values based on key
  *
  * @param  integer  $id   The product id
  * @param  string   $key  The product column key
  * @access public
  * @return array
  */   
  public static function getMinMax($id, $key = 'products_quantity') {
    global $lC_Database;

    $Qproducts = $lC_Database->query('select MAX(' . $key . ') as high, MIN(' . $key . ') as low from :table_products where parent_id = :parent_id and is_subproduct > :is_subproduct');
    $Qproducts->bindTable(':table_products', TABLE_PRODUCTS);
    $Qproducts->bindInt(':parent_id', $id);
    $Qproducts->bindInt(':is_subproduct', 0);
    $Qproducts->execute();
   
    $result = $Qproducts->toArray();
    
    $Qproducts->freeResult();
    
    return $result;
  }  
 /*
  * Return the product simple options pricing content
  *
  * @param array $options The product simple options array
  * @access public
  * @return string
  */  
  public static function getSimpleOptionsPricingTbody($options, $customers_group_id) {
    global $lC_Currencies, $pInfo;
    
    if ($customers_group_id == '') return false;
    
    $gData = lC_Customer_groups_Admin::getData($customers_group_id);
    $baselineDiscount = (float)$gData['baseline_discount'];
    $basePrice = (isset($pInfo)) ? (float)$pInfo->get('products_price') : 0.00;

    $tbody = '';  
    if (isset($options) && !empty($options)) {
      foreach ($options as $key => $so) {

        if ((isset($so['title']) && $so['title'] != NULL)) {
          $items = '';
          if (is_array($options['values'])) {
            foreach ($options['values'] as $k => $v) {
              if ($v['options_id'] == $so['options_id'] && $v['products_id'] == $pInfo->get('products_id') && $customers_group_id == $v['customers_group_id']) {
                if ($customers_group_id == DEFAULT_CUSTOMERS_GROUP_ID) {
                  $mod = (isset($v['price_modifier']) && !empty($v['price_modifier'])) ? number_format($v['price_modifier'], DECIMAL_PLACES) : '0.00';
                } else {
                  $mod = number_format(round(($basePrice * $baselineDiscount) * .01, DECIMAL_PLACES), DECIMAL_PLACES);
                }
                $items .= '<tr class="trp-' . $v['options_id'] . '">' .
                          '  <td class="element">' . $v['title'] . '</td>' . 
                          '  <td>' .
                          '    <div id="div_' . $v['customers_group_id'] . '_' . $v['options_id'] . '_' . $v['values_id'] . '" class="icon-plus-round icon-green icon-size2" style="display:inline;">' .
                          '      <div class="inputs' . (($customers_group_id != DEFAULT_CUSTOMERS_GROUP_ID) ? ' disabled' : '') . '" style="display:inline; padding:8px 0;">' .
                          '        <span class="mid-margin-left no-margin-right">' . $lC_Currencies->getSymbolLeft() . '</span>' .
                          '        <input type="text" class="input-unstyled" onfocus="$(this).select()" value="' . $mod . '" onblur="showSimpleOptionsPricingSymbol(this, \'' . $v['customers_group_id'] . '_' . $v['options_id'] . '_' . $v['values_id'] . '\');" id="simple_options_entry_price_modifier_' . $v['customers_group_id'] . '_' . $v['options_id'] . '_' . $v['values_id'] . '" name="simple_options_entry_price_modifier[' . $v['customers_group_id'] . '][' . $v['options_id'] . '][' . $v['values_id'] . ']" ' . (($customers_group_id != DEFAULT_CUSTOMERS_GROUP_ID) ? ' DISABLED' : '') . '>' .
                          '      </div>' .
                          '    </div>' .
                          '  </td>' .
                          '</tr>';
              }
            }
          }
                   
          $tbody .= '<tr id="trp-' . $customers_group_id . '-' . $so['options_id'] . '" class="trp-' . $so['options_id'] . '"><td width="100px" class="strong">' . $so['title'] . '</td></tr>' . $items;

        }
      }
    }
    
    return $tbody;    
  }  
 /*
  *  Return the product simple options accordian price listing content
  *
  * @access public
  * @return array
  */
  public static function getGroupPricingContent($base_price) {
    global $lC_Language, $lC_Currencies;
    
    $content = '';
    $groups = lC_Customer_groups_Admin::getAll();
    foreach($groups['entries'] as $key => $value) {

      $discount = round((float)$base_price * ((float)$value['baseline_discount'] * .01), DECIMAL_PLACES); 
      $discounted_price = $base_price - $discount;
     
      $content .= '<div>' .
                  '  <label for="" class="label margin-right"><b>'. $value['customers_group_name'] .'</b></label>' .
                  '  <input type="checkbox" name="enable_group_pricing[' . $value['customers_group_id'] . ']" class="switch medium margin-right" disabled />' .
                  '    <div class="inputs grey" style="display:inline; padding:8px 0;">' .
                  '      <span class="mid-margin-left no-margin-right">' . $lC_Currencies->getSymbolLeft() . '</span>' .
                  '      <input type="text" onfocus="this.select();" name="group_price[' . $value['customers_group_id'] . ']" id="group_price_' . $value['customers_group_id'] . '" value="' . number_format($discounted_price, DECIMAL_PLACES) . '" class="input-unstyled small-margin-right grey disabled" style="width:60px;" READONLY/>' .
                  '    </div>' .
                  '  <small class="input-info mid-margin-left no-wrap">' . $lC_Language->get('text_price') . '<span class="tag glossy mid-margin-left">-' . number_format($value['baseline_discount'], DECIMAL_PLACES) . '%</span><!-- if specials enabled /Special--></small>' . 
                  '</div>';
    }
    
    return $content;
  }  
 /*
  *  Return the product simple options accordian price listing content
  *
  * @access public
  * @return array
  */
  public static function getSpecialPricingContent() {
    global $lC_Language, $lC_Currencies, $pInfo;
    
    $content = '';
    $groups = lC_Customer_groups_Admin::getAll();
    foreach($groups['entries'] as $key => $value) {
      if ($value['customers_group_id'] == 1) { // remove this line when specials per group is reintroduced
        $base = (isset($pInfo)) ? (float)$pInfo->get('products_price') : 0.00;
        $special = (isset($pInfo)) ? (float)$pInfo->get('products_special_price') : 0.00;
        $discount = (isset($base) && $base > 0.00) ? round( ((($base - $special) / $base) * 100), DECIMAL_PLACES) : 0.00; 
       
        $content .= '<!--<label for="products_special_pricing_enable' . $value['customers_group_id'] . '" class="label margin-right"><b>'. $value['customers_group_name'] .'</b></label>-->' .
                    '<div class="columns">' .
                    '  <div class="new-row-mobile twelve-columns twelve-columns-mobile mid-margin-bottom">' .
                    '    <input id="products_special_pricing_enable' . $value['customers_group_id'] . '" name="products_special_pricing_enable' . $value['customers_group_id'] . '" type="checkbox" class="margin-right medium switch"' . (($pInfo->get('products_special_status') != 0 && $value['customers_group_id'] == '1') ? ' checked' : '') . ' />' .
                    '    <div class="inputs' . (($value['customers_group_id'] == '1') ? '' : ' disabled grey') . '" style="display:inline; padding:8px 0;">' .
                    '      <span class="mid-margin-left no-margin-right">' . $lC_Currencies->getSymbolLeft() . '</span>' .
                    '      <input type="text" onfocus="this.select();" onchange="updatePricingDiscountDisplay();" name="products_special_price[' . $value['customers_group_id'] . ']" id="products_special_price' . $value['customers_group_id'] . '" value="' . (($value['customers_group_id'] == '1') ? number_format($pInfo->get('products_special_price'), DECIMAL_PLACES) : '0.00') . '" class="sprice input-unstyled small-margin-right' . (($value['customers_group_id'] == '1') ? '' : ' grey disabled') . '" style="width:60px;"' . (($value['customers_group_id'] == '1') ? '' : ' READONLY') . '/>' .
                    '    </div>' .
                    '    <small class="input-info mid-margin-left no-wrap">' . $lC_Language->get('text_special_price') . (($value['customers_group_id'] == '1') ? '<span class="disctag tag glossy mid-margin-left">-' . number_format($discount, DECIMAL_PLACES) . '%</span>' : '') . '</small>' .
                    '  </div>' .
                    '  <div class="new-row-mobile twelve-columns twelve-columns-mobile">' .
                    '    <span class="nowrap margin-right">' .
                    '      <span class="input small-margin-top">' .
                    '        <input name="products_special_start_date[' . $value['customers_group_id'] . ']" id="products_special_start_date' . $value['customers_group_id'] . '" type="text" placeholder="Start" class="input-unstyled datepicker' . (($value['customers_group_id'] == '1') ? '' : ' disabled') . '" value="' . $pInfo->get('products_special_start_date') . '" style="width:97px;" />' .
                    '      </span>' .
                    '      <span class="icon-calendar icon-size2 small-margin-left"></span>' .
                    '    </span>' .
                    '    <span class="nowrap">' .
                    '      <span class="input small-margin-top">' .
                    '        <input name="products_special_expires_date[' . $value['customers_group_id'] . ']" id="products_special_expires_date' . $value['customers_group_id'] . '" type="text" placeholder="End" class="input-unstyled datepicker' . (($value['customers_group_id'] == '1') ? '' : ' disabled') . '" value="' . $pInfo->get('products_special_expires_date') . '" style="width:97px;" />' .
                    '      </span>' .
                    '      <span class="icon-calendar icon-size2 small-margin-left"></span>' .
                    '    </span>' .
                    '  </div>' .
                    '</div>';
      } // remove this line when specials per group is reintroduced
    }
    
    return $content;
  }  
 /*
  *  Return true if product has special in db, false if none
  *
  * @access public
  * @return boolean true or false
  */  
  public static function hasSpecial($id) {
    global $lC_Database;

    $Qspecial = $lC_Database->query('select specials_id from :table_specials where products_id = :products_id');
    $Qspecial->bindTable(':table_specials', TABLE_SPECIALS);
    $Qspecial->bindInt(':products_id', $id);
    $Qspecial->execute();
    
    if ( $Qspecial->numberOfRows() > 0 ) {
      return $Qspecial->value('specials_id');
    }

    return false;
  }  
 /*
  * update product status db entry
  * 
  * @access public
  * @return true or false
  */
  public static function updateStatus($id, $val) {
    global $lC_Database;
    
    $Qupdate = $lC_Database->query('update :table_products set products_status = :products_status where products_id = :products_id');
    $Qupdate->bindTable(':table_products', TABLE_PRODUCTS);
    $Qupdate->bindInt(':products_status', $val);
    $Qupdate->bindInt(':products_id', $id);
    $Qupdate->execute();
      
    return true;
  }
 /*
  * get assignedCategoryTreeSelect options
  * 
  * @access public
  * @return html
  */
  public static function assignedCategoryTreeSelect($spacer = 0) {
    $assignedCategoryTree = new lC_CategoryTree();
    $assignedCategoryTree->setBreadcrumbUsage(false);
    $assignedCategoryTree->setSpacerString('&nbsp;', 3);
    
    $assignedCategoryTreeSelect = '';
    foreach ($assignedCategoryTree->getArray() as $value) {
      if ($value['mode'] == 'category') {
        $assignedCategoryTreeSelect .= '<option value="' . $value['id'] . '">' . $value['title'] . '</option>' . "\n";
      }
    }
      
    return $assignedCategoryTreeSelect;
  }  
 /*
  * Returns an array of product 
  *
  * @param integer $id The product id
  * @access public
  * @return array
  */
  public static function getProductsArray($pID = null, $ppID = 0) {
    global $lC_Database, $lC_Language, $lC_Currencies, $_module;
    
    $result = array();
    
    $Qproducts = $lC_Database->query('select SQL_CALC_FOUND_ROWS p.*, pd.products_name, pd.products_keyword from :table_products p, :table_products_description pd where p.parent_id = :products_parent_id and p.products_id = pd.products_id and pd.language_id = :language_id');
    
    if (is_numeric($pID)) {
      $Qproducts->appendQuery('and p.products_id = :products_id');
      $Qproducts->bindInt(':products_id', $pID);      
    }
    
    $Qproducts->appendQuery('order by pd.products_name');
    $Qproducts->bindTable(':table_products', TABLE_PRODUCTS);
    $Qproducts->bindTable(':table_products_description', TABLE_PRODUCTS_DESCRIPTION);
    $Qproducts->bindInt(':language_id', $lC_Language->getID());
    $Qproducts->bindInt(':products_parent_id', $ppID);
    $Qproducts->execute();
    
    if ($Qproducts->numberOfRows()) {
      while ($Qproducts->next()) {
        $result[] = $Qproducts->toArray();
      }
    }
    
    return $result;
  }
 /*
  * Returns an array of product for dropdown list
  *
  * @param integer $id The product id
  * @access public
  * @return array
  */
  public static function getProductsDropdownArray($exclude = array()) {
    global $lC_Database, $lC_Language, $lC_Currencies, $_module;
    
    $result = array();

    $Qproducts = $lC_Database->query('select SQL_CALC_FOUND_ROWS p.products_id, pd.products_name from :table_products p, :table_products_description pd where p.products_id = pd.products_id and pd.language_id = :language_id');
    $Qproducts->appendQuery('order by p.products_id');
    $Qproducts->bindTable(':table_products', TABLE_PRODUCTS);
    $Qproducts->bindTable(':table_products_description', TABLE_PRODUCTS_DESCRIPTION);
    $Qproducts->bindInt(':language_id', $lC_Language->getID());
    $Qproducts->execute();
    if ($Qproducts->numberOfRows()) {
      while ( $Qproducts->next() ) {
        if (!in_array($Qproducts->value('products_id'), $exclude)) {  
          $result[] = array('id' => $Qproducts->value('products_id'),
                            'text' => $Qproducts->value('products_name'));
        }
      }
    }
    return $result;
  }
 /*
  * Determine if the product has simple options
  *
  * @param integer $id The product id
  * @access public
  * @return boolean
  */
  public static function hasSimpleOptions($id) {
    global $lC_Database;      

    $Qchk = $lC_Database->query('select id from :table_products_simple_options where products_id = :products_id limit 1');
    $Qchk->bindTable(':table_products_simple_options', TABLE_PRODUCTS_SIMPLE_OPTIONS);
    $Qchk->bindInt(':products_id', $id);
    $Qchk->execute();

    return ( $Qchk->numberOfRows() === 1 );
  }  
 /*
  * Return the field name for the selected column (used for datatable ordering)
  *
  * @param integer $i The datatable column id
  * @access private
  * @return string
  */
  private static function _fnColumnToField($i) {
   if ( $i == 0 )
    return "pd.products_name";
   else if ( $i == 1 )
    return "pd.products_name";
   else if ( $i == 4 )
    return "p.products_price";    
   else if ( $i == 5 )
    return "p.products_quantity";  
   else if ( $i == 6 )
    return "p.products_status";      
  }  
 /*
  * get the last product id
  * 
  * @access private
  * @return integer
  */  
  protected static function _getLastID() {
    global $lC_Database;
    
    $Qchk = $lC_Database->query('select products_id from :table_products order by products_id desc limit 1');
    $Qchk->bindTable(':table_products', TABLE_PRODUCTS);
    $Qchk->execute();    
    
    return $Qchk->valueInt('products_id');
  }  
 /*
  * Determine if the product has subproducts
  *
  * @param integer $id The product id
  * @access private
  * @return boolean
  */
  private static function _hasSubProducts($id) {
    global $lC_Database;

    $Qchk = $lC_Database->query('select products_id from :table_products where parent_id = :parent_id and is_subproduct > :is_subproduct limit 1');
    $Qchk->bindTable(':table_products', TABLE_PRODUCTS);
    $Qchk->bindInt(':parent_id', $id);
    $Qchk->bindInt(':is_subproduct', 0);
    $Qchk->execute();

    return ( $Qchk->numberOfRows() === 1 );
  }   
}
?>