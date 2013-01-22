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
      $result['zonesHtml'] = lc_draw_label('', null, 'state') . lc_draw_pull_down_menu('state', $zones_array, $zone_name, 'style="padding-top:5px"');
      $result['single'] = '0';

    } else {                      
      $result['zonesHtml'] = lc_draw_label('', null, 'state') . ' ' . lc_draw_input_field('state', (($zone != 'undefined') ? $zone : null), 'placeholder="' . $lC_Language->get('field_customer_state') . '" onfocus="this.placeholder = \'\'" onblur="this.placeholder = \'' . $lC_Language->get('field_customer_state') . '\'" class="required" style="width:103%;"');
      $result['single'] = '1';
    }
    
    return $result;
  }
}
?>