<?php
/**
  @package    catalog::templates::classes
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: output.php v1.0 2013-08-08 datazen $
*/
//error_reporting(0);        
class lC_Template_output { 

 /**
  * Return the countries dropdown array
  *
  * @access public
  * @return json
  */
  public static function getZonesDropdownHtml($countries_id, $zone = null) {
    global $lC_Database, $lC_Language;

    $Qzones = $lC_Database->query('select zone_name from :table_zones where zone_country_id = :zone_country_id order by zone_name');
    $Qzones->bindTable(':table_zones', TABLE_ZONES);
    $Qzones->bindInt(':zone_country_id', $countries_id);
    $Qzones->execute();

    $result = array();
    if ($Qzones->numberOfRows() > 0) {
      $zones_array = array();
      while ($Qzones->next()) {
        $zones_array[] = array('id' => $Qzones->value('zone_name'), 'text' => $Qzones->value('zone_name'));
      }
      $zone_name = (isset($zone) && is_numeric($zone) && $zone != 0) ? lC_Address::getZoneName($zone) : NULL;
      $result['zonesHtml'] = lc_draw_label('', null, 'state') . lc_draw_pull_down_menu('state', $zones_array, $zone_name);
      $result['single'] = '0';

    } else {                      
      $result['zonesHtml'] = lc_draw_label('', null, 'state') . ' ' . lc_draw_input_field('state', (($zone != 'undefined') ? $zone : null), 'placeholder="' . $lC_Language->get('field_customer_state') ./* '" onfocus="this.placeholder = \'\'" onblur="this.placeholder = \'' . $lC_Language->get('field_customer_state') . '\'" */'"class="required" style="width:99.7%;"');
      $result['single'] = '1';
    }
    
    return $result;
  }
 /*
  * Returns the new arrival listing data
  *
  * @access public
  * @return string
  */  
  public static function newArrivalsListing() {
    global $lC_Vqmod; 
    
    include_once($lC_Vqmod->modCheck('includes/classes/products.php'));

    $lC_Products = new lC_Products();
    $Qlisting = $lC_Products->execute();
    $cnt = 0;
    $listing = '';
    while ($Qlisting->next()) {
      $lC_Product = new lC_Product($Qlisting->valueInt('products_id'));
      $listing .= '<li>' . lc_link_object(lc_href_link(FILENAME_PRODUCTS, $lC_Product->getKeyword()), substr($lC_Product->getTitle(), 0, 20)) . '</li>';
      $cnt++;
      if ($cnt == 5) break;
    }    
       
    return $listing;
  }
 /*
  * Returns the manufacturer dropdown array
  *
  * @access public
  * @return array
  */  
  public static function getManufacturerDropdownArray() {
    global $lC_Database, $lC_Language;
    
    $manufacturers_array = array(array('id' => '', 'text' => $lC_Language->get('filter_all_manufacturers')));
    $Qmanufacturers = $lC_Database->query('select manufacturers_id, manufacturers_name from :table_manufacturers order by manufacturers_name');
    $Qmanufacturers->bindTable(':table_manufacturers', TABLE_MANUFACTURERS);
    $Qmanufacturers->execute();
    while ($Qmanufacturers->next()) {
      $manufacturers_array[] = array('id' => $Qmanufacturers->valueInt('manufacturers_id'),
                                     'text' => $Qmanufacturers->value('manufacturers_name'));
    }
    
    return $manufacturers_array;    
  }
 /*
  * Returns the categories dropdown array
  *
  * @access public
  * @return array
  */
  public static function getCategoriesDropdownArray() {
    global $lC_CategoryTree, $lC_Language;
    
    $lC_CategoryTree->setSpacerString('&nbsp;', 2);                                                                      
    $categories_array = array(array('id' => '', 'text' => $lC_Language->get('filter_all_categories')));
    foreach ($lC_CategoryTree->buildBranchArray(0) as $category) {
      $categories_array[] = array('id' => $category['id'],
                                  'text' => $category['title']);
    }
    
    return $categories_array;    
  }  
 /*
  * Returns the product listing SQL
  *
  * @param string $search The search string 
  * @access public
  * @return resource
  */  
  public static function getProductsListingSql() {
    $pArr = self::_getProductsListingData();
    
    return $pArr['Qlisting'];    
  }
 /*
  * Returns the manufacturer filter Html
  *
  * @access public
  * @return array
  */  
  public static function getManufacturerFilter() {
    $pArr = self::_getProductsListingData();
    
    return $pArr['mfgFilter'];
  } 
 /*
  * Returns the category listing Html
  *
  * @access public
  * @return array
  */  
  public static function getCategoryListing() {
    global $lC_Database, $lC_Language, $lC_Products, $lC_CategoryTree, $lC_Vqmod, $cPath, $cPath_array, $current_category_id;
    
    include_once($lC_Vqmod->modCheck('includes/classes/products.php'));
    
    if (isset($cPath) && strpos($cPath, '_')) {
      // check to see if there are deeper categories within the current category
      $category_links = array_reverse($cPath_array);
      for($i=0, $n=sizeof($category_links); $i<$n; $i++) {
        $Qcategories = $lC_Database->query('select count(*) as total from :table_categories c, :table_categories_description cd where c.parent_id = :parent_id and c.categories_id = cd.categories_id and cd.language_id = :language_id and c.categories_status = 1');
        $Qcategories->bindTable(':table_categories', TABLE_CATEGORIES);
        $Qcategories->bindTable(':table_categories_description', TABLE_CATEGORIES_DESCRIPTION);
        $Qcategories->bindInt(':parent_id', $category_links[$i]);
        $Qcategories->bindInt(':language_id', $lC_Language->getID());
        $Qcategories->execute();

        if ($Qcategories->valueInt('total') < 1) {
          // do nothing, go through the loop
        } else {
          $Qcategories = $lC_Database->query('select c.categories_id, cd.categories_name, c.categories_image, c.parent_id from :table_categories c, :table_categories_description cd where c.parent_id = :parent_id and c.categories_id = cd.categories_id and cd.language_id = :language_id and c.categories_status = 1 order by sort_order, cd.categories_name');
          $Qcategories->bindTable(':table_categories', TABLE_CATEGORIES);
          $Qcategories->bindTable(':table_categories_description', TABLE_CATEGORIES_DESCRIPTION);
          $Qcategories->bindInt(':parent_id', $category_links[$i]);
          $Qcategories->bindInt(':language_id', $lC_Language->getID());
          $Qcategories->execute();
          break; // we've found the deepest category the customer is in
        }
      }
    } else {
      $Qcategories = $lC_Database->query('select c.categories_id, cd.categories_name, c.categories_image, c.parent_id, c.categories_mode, c.categories_link_target, c.categories_custom_url from :table_categories c, :table_categories_description cd where c.parent_id = :parent_id and c.categories_id = cd.categories_id and cd.language_id = :language_id and c.categories_status = 1 order by sort_order, cd.categories_name');
      $Qcategories->bindTable(':table_categories', TABLE_CATEGORIES);
      $Qcategories->bindTable(':table_categories_description', TABLE_CATEGORIES_DESCRIPTION);
      $Qcategories->bindInt(':parent_id', $current_category_id);
      $Qcategories->bindInt(':language_id', $lC_Language->getID());
      $Qcategories->execute();
    }
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
      $output .= '  <div class="content-categories-name">' . lc_link_object(lc_href_link($url), $Qcategories->value('categories_name'))  . '</div>' . "\n" . 
                 '</div>' . "\n";      
    }    
    
    return $output;
  } 
 /*
  * Returns the current category information (i.e. description, blurb, meta data etc)
  *
  * @access public
  * @return array
  */  
  public static function getCategoryDescription() {
    global $lC_Database, $lC_Language, $current_category_id;
    
    $Qcategory = $lC_Database->query('select categories_description from :table_categories_description where categories_id = :categories_id and language_id = :language_id');
    $Qcategory->bindTable(':table_categories_description', TABLE_CATEGORIES_DESCRIPTION);
    $Qcategory->bindInt(':categories_id', $current_category_id);
    $Qcategory->bindInt(':language_id', $lC_Language->getID());
    $Qcategory->execute();
    
    $output = '';
    if ($Qcategory->value('categories_description') != '') {
      $output .= $Qcategory->value('categories_description');
    }
    
    return $output;
  } 
 /*
  * Returns the zones dropdown field
  *
  * @access public
  * @return string
  */
  public static function getZonesField() {
    global $lC_Database, $lC_Language, $lC_Template, $entry_state_has_zones;

    if ( (isset($_GET['new']) && ($_GET['new'] == 'save')) || (isset($_GET['edit']) && ($_GET['edit'] == 'save')) || (isset($_GET[$lC_Template->getModule()]) && ($_GET[$lC_Template->getModule()] == 'process')) ) {
      if ($entry_state_has_zones === true) {
        $Qzones = $lC_Database->query('select zone_name from :table_zones where zone_country_id = :zone_country_id order by zone_name');
        $Qzones->bindTable(':table_zones', TABLE_ZONES);
        $Qzones->bindInt(':zone_country_id', $_POST['country']);
        $Qzones->execute();

        $zones_array = array();
        while ($Qzones->next()) {
          $zones_array[] = array('id' => $Qzones->value('zone_name'), 'text' => $Qzones->value('zone_name'));
        }
        $output = lc_draw_pull_down_menu('state', $zones_array);
      } else {
        $output = lc_draw_input_field('state');
      }
    } else {
      if (isset($Qentry)) {
        $zone = $Qentry->value('entry_state');
        if ($Qentry->valueInt('entry_zone_id') > 0) {
          $zone = lC_Address::getZoneName($Qentry->valueInt('entry_zone_id'));
        }
      }
      $output = lc_draw_input_field('state', (isset($Qentry) ? $zone : null));
    }    

    return $output;    
  }
 /*
  * Returns the product listing data
  *
  * @access private
  * @return array
  */  
  private static function _getProductsListingData() {
    global $lC_Database, $lC_Language, $lC_Products, $lC_Vqmod;
    
    include_once($lC_Vqmod->modCheck('includes/classes/products.php'));
    
    // optional Product List Filter
    $output = '';
    $result = array();

    if (isset($_GET['manufacturers']) && !empty($_GET['manufacturers'])) {
      $filterlist_sql = "select distinct c.categories_id as id, cd.categories_name as name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd, " . TABLE_TEMPLATES_BOXES . " tb, " . TABLE_PRODUCT_ATTRIBUTES . " pa where p.products_status = '1' and p.products_id = p2c.products_id and p2c.categories_id = c.categories_id and p2c.categories_id = cd.categories_id and cd.language_id = '" . (int)$lC_Language->getID() . "' and tb.code = 'manufacturers' and tb.id = pa.id and pa.products_id = p.products_id and pa.value = '" . (int)$_GET['manufacturers'] . "' order by cd.categories_name";
    } else {
      $filterlist_sql = "select distinct m.manufacturers_id as id, m.manufacturers_name as name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_MANUFACTURERS . " m where p.products_status = '1' and p.manufacturers_id = m.manufacturers_id and p.products_id = p2c.products_id and p2c.categories_id = '" . (int)$current_category_id . "' order by m.manufacturers_name";
    }
    $Qfilterlist = $lC_Database->query($filterlist_sql);
    $Qfilterlist->execute();
    if ($Qfilterlist->numberOfRows() > 1) {
      $output .= '<p><form name="filter" action="' . lc_href_link(FILENAME_DEFAULT) . '" method="get">' . $lC_Language->get('filter_show') . '&nbsp;';
      if (isset($_GET['manufacturers']) && !empty($_GET['manufacturers'])) {
        $output .=  lc_draw_hidden_field('manufacturers', $_GET['manufacturers']);
        $options = array(array('id' => '', 'text' => $lC_Language->get('filter_all_categories')));
      } else {
        $output .=  lc_draw_hidden_field('cPath', $cPath);
        $options = array(array('id' => '', 'text' => $lC_Language->get('filter_all_manufacturers')));
      }
      if (isset($_GET['sort'])) {
        $output .=  lc_draw_hidden_field('sort', $_GET['sort']);
      }
      while ($Qfilterlist->next()) {
        $options[] = array('id' => $Qfilterlist->valueInt('id'), 'text' => $Qfilterlist->value('name'));
      }
      $output .=  lc_draw_pull_down_menu('filter', $options, (isset($_GET['filter']) ? $_GET['filter'] : null), 'onchange="this.form.submit()"');
      $output .=  lc_draw_hidden_session_id_field() . '</form></p>' . "\n";
    }

    if (isset($_GET['manufacturers']) && !empty($_GET['manufacturers'])) {
      $lC_Products->setManufacturer($_GET['manufacturers']);
    }
    $Qlisting = $lC_Products->execute();    
    
    $result['mfgFilter'] = $output;
    $result['Qlisting'] = $Qlisting;
    
    return $result;
  }
 /*
  * return the top cats for nav
  *
  * @access public
  * @return array
  */
  /*
  public static function getTopCategories() {
    global $lC_Database, $lC_Language;
    
    $Qcategories = $lC_Database->query('select c.categories_id, cd.categories_name, cd.categories_menu_name, c.categories_link_target, c.categories_custom_url, c.categories_mode from :table_categories c, :table_categories_description cd where c.parent_id = 0 and c.categories_id = cd.categories_id and cd.language_id = :language_id and c.categories_status = 1 and c.categories_visibility_nav = 1 order by sort_order, cd.categories_name');
    $Qcategories->bindTable(':table_categories', TABLE_CATEGORIES);
    $Qcategories->bindTable(':table_categories_description', TABLE_CATEGORIES_DESCRIPTION);
    $Qcategories->bindInt(':language_id', $lC_Language->getID());
    $Qcategories->execute();
    while ( $Qcategories->next() ) {
      $topCategories[] = array('id' => $Qcategories->value('categories_id'),
                               'name' => ($Qcategories->value('categories_menu_name') != '') ? $Qcategories->value('categories_menu_name') : $Qcategories->value('categories_name'),
                               'link_target' => $Qcategories->value('categories_link_target'),
                               'custom_url' => $Qcategories->value('categories_custom_url'),
                               'mode' => $Qcategories->value('categories_mode'));
    }
    
    return $topCategories;   
  }
  */
 /*
  * return the top cats for nav
  *
  * @access public
  * @return array
  */
  public static function getCategoriesStatus($id) {
    global $lC_Database;
    
    $Qcategories = $lC_Database->query('select categories_status from :table_categories where categories_id = :categories_id');
    $Qcategories->bindTable(':table_categories', TABLE_CATEGORIES);
    $Qcategories->bindInt(':id', $id);
    $Qcategories->execute();
    
    return $result;   
  }
 /*
  * Return the language selections 
  *
  * @access public
  * @return array
  */  
  public function getTemplateLanguageSelection($include_image = true, $include_name = false, $params = '') {
    global $lC_Language;
    
    $text = '';
    $output = '';
    foreach ($lC_Language->getAll() as $value) {
      if ($include_name === true && $include_image === true) {
        $text = '<span class="locale-dropdown-lang-image">' . $lC_Language->showImage($value['code']) . '</span> <span class="locale-dropdown-lang-title">' . $value['name'] . '</span>';
      } else if ($include_name === true && $include_image === false) {
        $text = $value['name'];
      } else {
        $text = $lC_Language->showImage($value['code'], null, null, $params);
      }
      $output .= '<li>' . lc_link_object(lc_href_link(basename($_SERVER['SCRIPT_FILENAME']), lc_get_all_get_params(array('language', 'currency')) . '&language=' . $value['code'], 'AUTO'), $text) . '</li>';
    }
    
    return $output;
  }
 /*
  * Return the currency selections
  *
  * @access public
  * @return array
  */  
  public function getTemplateCurrenciesSelection($include_symbol = true, $include_name = false, $params = '') {
    global $lC_Currencies;
    
    $currency_data = array();
    foreach ($lC_Currencies->currencies as $key => $value) {
      $currency_data[] = array('id' => $key, 'text' => $value['title']);
    }
    foreach ($currency_data as $currency) {
      if ($include_name === true && $include_symbol === true) {
        $text = '<span class="locale-dropdown-cur-title">' . $currency['text'] . '</span> <span class="locale-dropdown-cur-symbol">(' . $currency['id'] . ')</span>';
      } else if ($include_name === true && $include_symbol === false) {
        $text = '<span class="locale-dropdown-cur-title">' . $currency['text'] . '</span>';
      } else {
        $text = '<span class="locale-dropdown-cur-symbol">' . $currency['id'] . '</span>';
      }
      echo '<li>
              <a href="' . lc_href_link(basename($_SERVER['SCRIPT_FILENAME']), lc_get_all_get_params(array('language', 'currency')) . '&currency=' . $currency['id'], 'AUTO') . '">
                ' . $text . '
              </a>
            </li>';
    }
    
    return $output;
  }   
}
?>