<?php
/*
  $Id: default.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
 
  @function The lC_Default class manages default template functions
*/
include_once('includes/classes/products.php');

class lC_Default {
 /*
  * Returns the live search data
  *
  * @param string $search The search string 
  * @access public
  * @return array
  */
  public static function find($search) {
    global $lC_Database, $lC_Language, $lC_Currencies, $lC_Image;

    $Qproducts = $lC_Database->query('select SQL_CALC_FOUND_ROWS p.*, pd.products_name, pd.products_description, pd.products_keyword from :table_products p, :table_products_description pd where p.parent_id = 0 and p.products_id = pd.products_id and pd.language_id = :language_id');

    $Qproducts->appendQuery('and (pd.products_name like :products_name or pd.products_keyword like :products_keyword) order by pd.products_name');
    $Qproducts->bindTable(':table_products', TABLE_PRODUCTS);
    $Qproducts->bindTable(':table_products_description', TABLE_PRODUCTS_DESCRIPTION);
    $Qproducts->bindInt(':language_id', $lC_Language->getID());
    $Qproducts->bindValue(':products_name', '%' . $search . '%');
    $Qproducts->bindValue(':products_keyword', '%' . $search . '%');

    $Qproducts->execute();

    $cnt = 0;
    $result = '<table id="liveSearchTable" border="0" width="100%" cellspacing="0" cellpadding="2" onMouseover="bgcolor:#cccccc;">';
    while ( $Qproducts->next() ) {
      $price = $lC_Currencies->format($Qproducts->value('products_price'));
      $products_status = ($Qproducts->valueInt('products_status') === 1);
      $products_quantity = $Qproducts->valueInt('products_quantity');
      $products_name = $Qproducts->value('products_name');
      $products_description = $Qproducts->value('products_description');
      $products_keyword = $Qproducts->value('products_keyword');

      if ( $Qproducts->valueInt('has_children') === 1 ) {
        $Qvariants = $lC_Database->query('select min(products_price) as min_price, max(products_price) as max_price, sum(products_quantity) as total_quantity, min(products_status) as products_status from :table_products where parent_id = :parent_id');
        $Qvariants->bindTable(':table_products', TABLE_PRODUCTS);
        $Qvariants->bindInt(':parent_id', $Qproducts->valueInt('products_id'));
        $Qvariants->execute();

        $products_status = ($Qvariants->valueInt('products_status') === 1);
        $products_quantity = '(' . $Qvariants->valueInt('total_quantity') . ')';

        $price = $lC_Currencies->format($Qvariants->value('min_price'));

        if ( $Qvariants->value('min_price') != $Qvariants->value('max_price') ) {
          $price .= '&nbsp;-&nbsp;' . $lC_Currencies->format($Qvariants->value('max_price'));
        }
      }

      $Qimage = $lC_Database->query("select image from :table_products_images where products_id = '" . $Qproducts->valueInt('products_id') . "'");
      $Qimage->bindTable(':table_products_images', TABLE_PRODUCTS_IMAGES);
      $Qimage->execute();

      $products_image = $Qimage->value('image');
      $products_link = lc_href_link(FILENAME_PRODUCTS, $products_keyword);

      $rowClass = ($cnt & 1) ? 'liveSearchRowOdd' : 'liveSearchRowEven';
      $result .= '<tr onclick="window.location=\'' . $products_link . '\';" class="' . $rowClass . '"><td valign="top">' .
                 '  <ol class="liveSearchListing">' .
                 '    <li>' .
                 '      <span class="liveSearchListingSpan" style="width: ' . $lC_Image->getWidth('mini') . 'px;">' . lc_link_object(lc_href_link(FILENAME_PRODUCTS, $products_keyword), $lC_Image->show($products_image, $products_name, null, 'mini')) . '</span>' .
                 '      <div class="liveSearchListingDiv">' . lc_link_object(lc_href_link(FILENAME_PRODUCTS, $products_keyword), $products_name) . '</div>' .
                 '      <div class="liveSearchListingPrice">' . $price . '</div>' .
                 '      <div style="clear: both;"></div>' .
                 '    </li>' .
                 '  </ol>' .
                 '</td></tr></a>';
      $cnt++;
    }
    $result .= '</table>';

    $Qproducts->freeResult();

    return $result;
  }
 /*
  * Removes an item from the shopping cart
  *
  * @param string $search The search string 
  * @access public
  * @return array
  */  
  public static function removeItem($item_id) {
    global $lC_Database, $lC_Language, $lC_Currencies, $lC_Customer, $lC_ShoppingCart, $lC_Image;

    $result = array();
    
    $lC_ShoppingCart->remove($item_id);
   
    // crete the new order total text
    $otText = '';
    foreach ($lC_ShoppingCart->getOrderTotals() as $module) {
      $otText .= '<tr>' .
                 ' <td class="align_left' . (($module['code'] == 'sub_total') ? ' sc_sub_total' : null) . (($module['code'] == 'total') ? ' sc_total' : null) . '" style="padding-right:10px;">' . $module['title'] . '</td>' .
                 ' <td class="align_right' . (($module['code'] == 'sub_total') ? ' sc_sub_total' : null) . (($module['code'] == 'total') ? ' sc_total' : null) . '">' . $module['text'] . '</td>' .
                 '</tr>';
    }
    
    $result['otText'] = $otText;
    
    // create the new mini-cart text
    $mcText = '';
    if ($lC_ShoppingCart->hasContents()) {
      $mcText .= '<a href="#" class="minicart_link">' . 
                  '  <span class="item"><b>' . $lC_ShoppingCart->numberOfItems() . '</b> ' . ($lC_ShoppingCart->numberOfItems() > 1 ? strtoupper($lC_Language->get('text_cart_items')) : strtoupper($lC_Language->get('text_cart_item'))) . ' /</span> <span class="price"><b>' . $lC_Currencies->format($lC_ShoppingCart->getSubTotal()) . '</b></span>' . 
                  '</a>' .
                  '<div class="cart_drop">' .
                  '  <span class="darw"></span>' .
                  '  <ul>';

      foreach ($lC_ShoppingCart->getProducts() as $products) {
        $mcText .= '<li>' . $lC_Image->show($products['image'], $products['name'], null, 'mini') . lc_link_object(lc_href_link(FILENAME_PRODUCTS, $products['keyword']), '(' . $products['quantity'] . ') x ' . $products['name']) . ' <span class="price">' . $lC_Currencies->format($products['price']) . '</span></li>';
      }           
      
      $mcText .= '</ul>' .
            '<div class="cart_bottom">' .
              '<div class="subtotal_menu">' .
                '<small>' . $lC_Language->get('box_shopping_cart_subtotal') . '</small>' .
                '<big>' . $lC_Currencies->format($lC_ShoppingCart->getSubTotal()) . '</big>' .
              '</div>' .
              '<a href="' . lc_href_link(FILENAME_CHECKOUT, null, 'SSL') . '">' . $lC_Language->get('text_checkout') . '</a>' .
            '</div>' .
          '</div>';
      $result['redirect'] = '0';
    } else {
      $mcText .= $lC_Language->get('box_shopping_cart_empty');
      $result['redirect'] = '1';
    } 
    
    $result['mcText'] = $mcText;
   
    
    return $result;
  }   
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
      $result['zonesHtml'] = lc_draw_label('', null, 'state') . ' ' . lc_draw_input_field('state', (($zone != 'undefined') ? $zone : null), 'placeholder="' . $lC_Language->get('field_customer_state') . '" onfocus="this.placeholder = \'\'" onblur="this.placeholder = \'' . $lC_Language->get('field_customer_state') . '\'" class="required" style="width:103%;"');
      $result['single'] = '1';
    }
    
    return $result;
  }
  
  public static function newArrivalsListing() {
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
  
  public static function getProductsListingSql() {
    $pArr = self::__getProductsListingData();
    
    return $pArr['Qlisting'];    
  }
  
  public static function getManufacturerFilter() {
    $pArr = self::__getProductsListingData();
    
    return $pArr['mfgFilter'];
  } 
  
  public static function getCategoryListing() {
    global $lC_Database, $lC_Language, $lC_Products, $lC_CategoryTree, $cPath, $cPath_array;
    
    if (isset($cPath) && strpos($cPath, '_')) {
      // check to see if there are deeper categories within the current category
      $category_links = array_reverse($cPath_array);
      for($i=0, $n=sizeof($category_links); $i<$n; $i++) {
        $Qcategories = $lC_Database->query('select count(*) as total from :table_categories c, :table_categories_description cd where c.parent_id = :parent_id and c.categories_id = cd.categories_id and cd.language_id = :language_id');
        $Qcategories->bindTable(':table_categories', TABLE_CATEGORIES);
        $Qcategories->bindTable(':table_categories_description', TABLE_CATEGORIES_DESCRIPTION);
        $Qcategories->bindInt(':parent_id', $category_links[$i]);
        $Qcategories->bindInt(':language_id', $lC_Language->getID());
        $Qcategories->execute();

        if ($Qcategories->valueInt('total') < 1) {
          // do nothing, go through the loop
        } else {
          $Qcategories = $lC_Database->query('select c.categories_id, cd.categories_name, c.categories_image, c.parent_id from :table_categories c, :table_categories_description cd where c.parent_id = :parent_id and c.categories_id = cd.categories_id and cd.language_id = :language_id order by sort_order, cd.categories_name');
          $Qcategories->bindTable(':table_categories', TABLE_CATEGORIES);
          $Qcategories->bindTable(':table_categories_description', TABLE_CATEGORIES_DESCRIPTION);
          $Qcategories->bindInt(':parent_id', $category_links[$i]);
          $Qcategories->bindInt(':language_id', $lC_Language->getID());
          $Qcategories->execute();
          break; // we've found the deepest category the customer is in
        }
      }
    } else {
      $Qcategories = $lC_Database->query('select c.categories_id, cd.categories_name, c.categories_image, c.parent_id from :table_categories c, :table_categories_description cd where c.parent_id = :parent_id and c.categories_id = cd.categories_id and cd.language_id = :language_id order by sort_order, cd.categories_name');
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
      $rows++;
      $width = (int)(100 / MAX_DISPLAY_CATEGORIES_PER_ROW) . '%';
      $exists = ($Qcategories->value('categories_image') != null) ? true : false;
      $output .= '    <td style="text-align:center;" class="categoryListing" width="' . $width . '" valign="top">' . lc_link_object(lc_href_link(FILENAME_DEFAULT, 'cPath=' . $lC_CategoryTree->buildBreadcrumb($Qcategories->valueInt('categories_id'))), ( ($exists === true) ? lc_image(DIR_WS_IMAGES . 'categories/' . $Qcategories->value('categories_image'), $Qcategories->value('categories_name')) : lc_image(DIR_WS_TEMPLATE_IMAGES . 'no_image.png', $lC_Language->get('image_not_found')) ) . '<br />' . $Qcategories->value('categories_name')) . '</td>' . "\n";
      if ((($rows / MAX_DISPLAY_CATEGORIES_PER_ROW) == floor($rows / MAX_DISPLAY_CATEGORIES_PER_ROW)) && ($rows != $number_of_categories)) {
        $output .= '  </tr>' . "\n";
        $output .= '  <tr>' . "\n";
      }
    }    
    
    return $output;
  } 
  
  public static function getZonesField(){
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
  
  public static function getGenderArray() {
      return array(array('id' => 'm', 'text' => $lC_Language->get('gender_male')),
                   array('id' => 'f', 'text' => $lC_Language->get('gender_female')));
  }
  
  public static function getCountriesDropdownArray() {
    global $lC_Language;
    
    $countries_array = array(array('id' => '',
                                   'text' => $lC_Language->get('pull_down_default')));
    foreach (lC_Address::getCountries() as $country) {
      $countries_array[] = array('id' => $country['id'],
                                 'text' => $country['name']);
    } 
    
    return $countries_array;   
  }
  
  private static function __getProductsListingData() {
    global $lC_Database, $lC_Language, $lC_Products;
    
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
  
}
?>