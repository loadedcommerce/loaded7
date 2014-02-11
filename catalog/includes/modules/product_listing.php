<?php
/**
  @package    catalog::modules
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: product_listing.php v1.0 2013-08-08 datazen $
*/
// create column list
$define_list = array('PRODUCT_LIST_MODEL' => PRODUCT_LIST_MODEL,
                     'PRODUCT_LIST_NAME' => PRODUCT_LIST_NAME,
                     'PRODUCT_LIST_MANUFACTURER' => PRODUCT_LIST_MANUFACTURER,
                     'PRODUCT_LIST_PRICE' => PRODUCT_LIST_PRICE,
                     'PRODUCT_LIST_QUANTITY' => PRODUCT_LIST_QUANTITY,
                     'PRODUCT_LIST_WEIGHT' => PRODUCT_LIST_WEIGHT,
                     'PRODUCT_LIST_IMAGE' => PRODUCT_LIST_IMAGE,
                     'PRODUCT_LIST_BUY_NOW' => PRODUCT_LIST_BUY_NOW);

asort($define_list);

$column_list = array();
reset($define_list);
while (list($key, $value) = each($define_list)) {
  if ($value > 0) $column_list[] = $key;
}

if ($Qlisting->numberOfRows() > 0) {
  
  $output = '';      
  while ($Qlisting->next()) {
    
    $lC_Product = new lC_Product($Qlisting->valueInt('products_id'));

    $output .= '<div class="product-listing-module-items">';
    
    for ($col=0, $n=sizeof($column_list); $col<$n; $col++) {
      switch ($column_list[$col]) {
        case 'PRODUCT_LIST_MODEL':
          $output .= '<div class="product-listing-module-model">' . $lC_Product->getModel() . '</div>' . "\n";
          break;

        case 'PRODUCT_LIST_NAME':
          if (isset($_GET['manufacturers'])) {
            $output .= '<div class="product-listing-module-name">' . lc_link_object(lc_href_link(FILENAME_PRODUCTS, $lC_Product->getKeyword() . '&manufacturers=' . $_GET['manufacturers']), $lC_Product->getTitle()) . '</div>' . "\n" .
                      '<div class="product-listing-module-description">' . ((strlen(lc_clean_html($lC_Product->getDescription())) > 65) ? substr(lc_clean_html($lC_Product->getDescription()), 0, 62) . '...' : lc_clean_html($lC_Product->getDescription())) . '</div>' . "\n";
          } else {
            $output .= '<div class="product-listing-module-name">' . lc_link_object(lc_href_link(FILENAME_PRODUCTS, $lC_Product->getKeyword() . ($cPath ? '&cPath=' . $cPath : '')), $lC_Product->getTitle()) . '</div>' . "\n" . 
                      '<div class="product-listing-module-description">' . ((strlen(lc_clean_html($lC_Product->getDescription())) > 65) ? substr(lc_clean_html($lC_Product->getDescription()), 0, 62) . '...' : lc_clean_html($lC_Product->getDescription())) . '</div>' . "\n";
          }
          break;

        case 'PRODUCT_LIST_MANUFACTURER':
          if ( $lC_Product->hasManufacturer() ) {
            $output .= '<div class="product-listing-module-manufacturer' . lc_link_object(lc_href_link(FILENAME_DEFAULT, 'manufacturers=' . $lC_Product->getManufacturerID()), $lC_Product->getManufacturer()) . '</div>' . "\n";
          } else {
            $output .= '<div class="product-listing-module-manufacturer"></div>' . "\n";
          }
          break;

        case 'PRODUCT_LIST_PRICE':
          $output .= '<div class="product-listing-module-price">' . $lC_Product->getPriceFormated(true) . '</div>' . "\n";
          break;

        case 'PRODUCT_LIST_QUANTITY':
          $output .= '<div class="product-listing-module-quantity"' . $lC_Product->getQuantity() . '</div>' . "\n";
          break;

        case 'PRODUCT_LIST_WEIGHT':
          $output .= '<div class="product-listing-module-weight"' . $lC_Product->getWeight() . '</div>' . "\n";
          break; 

        case 'PRODUCT_LIST_IMAGE':
          if (isset($_GET['manufacturers'])) {
            $output .= '<div class="product-listing-module-image">' . lc_link_object(lc_href_link(FILENAME_PRODUCTS, $lC_Product->getKeyword() . '&manufacturers=' . $_GET['manufacturers']), $lC_Image->show($lC_Product->getImage(), $lC_Product->getTitle(), 'class="product-listing-module-image-src"')) . '</div>' . "\n";
          } else {
            $output .= '<div class="product-listing-module-image">' . lc_link_object(lc_href_link(FILENAME_PRODUCTS, $lC_Product->getKeyword() . ($cPath ? '&cPath=' . $cPath : '')), $lC_Image->show($lC_Product->getImage(), $lC_Product->getTitle(), 'class="product-listing-module-image-src"')) . '</div>' . "\n";
          }
          break;
          
        case 'PRODUCT_LIST_BUY_NOW':
          if (DISABLE_ADD_TO_CART == 1 && $lC_Product->getQuantity() < 1) {
            $output .= '<div class="product-listing-module-buy-now"><button class="product-listing-module-buy-now-button" disabled>' . $lC_Language->get('out_of_stock') . '</button></div>' . "\n"; 
          } else {
            $output .= '<div class="product-listing-module-buy-now"><form action="' . lc_href_link(basename($_SERVER['SCRIPT_FILENAME']), $lC_Product->getKeyword() . '&' . lc_get_all_get_params(array('action', 'new')) . '&action=cart_add') . '" method="post"><button onclick="$(this).closest(\'form\').submit();" type="submit" class="product-listing-module-buy-now-button">' . $lC_Language->get('button_buy_now') . '</button></form></div>' . "\n"; 
          }
          break;
      }
    }
    $output .= '</div>' . "\n";
    
  }     
} else {
  $output .= '<div class="product-listing-module-no-products"><p>' . $lC_Language->get('no_products_in_category') . '</p></div>';
}

echo $output;
?>