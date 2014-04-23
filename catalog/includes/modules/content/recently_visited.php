<?php
/**
  @package    catalog::modules::content
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: recently_visted.php v1.0 2013-08-08 datazen $
*/
class lC_Content_recently_visited extends lC_Modules {
 /* 
  * Public variables 
  */    
  public $_title,
         $_code = 'recently_visited',
         $_author_name = 'Loaded Commerce',
         $_author_www = 'http://www.loadedcommerce.com',
         $_group = 'content';
 /*   
  * Class constructor 
  */
  public function __construct() {
    global $lC_Language;
    
    if (function_exists($lC_Language->injectDefinitions)) $lC_Language->injectDefinitions('modules/' . $_GET['set'] . '/' . $this->_code . '.xml'); 
    
    $this->_title = $lC_Language->get('recently_visited_title');
  }
 /*
  * Returns the recently visited HTML
  *
  * @access public
  * @return string
  */
  public function initialize() {
    global $lC_Services, $lC_RecentlyVisited, $lC_Language, $lC_Image, $lC_Product;
    
    if ($lC_Services->isStarted('recently_visited') && $lC_RecentlyVisited->hasHistory()) {
    
      $this->_content = '';
      if ($lC_RecentlyVisited->hasProducts()) {
        foreach ($lC_RecentlyVisited->getProducts() as $product) {
          // VQMOD-hookpoint; DO NOT MODIFY OR REMOVE THE LINE BELOW
          $lC_Product = new lC_Product($product['id']);
         
          $this->_content .= '<div class="content-recently-visited-container">' . "\n" . 
                             '  <div class="content-recently-visited-name">' . lc_link_object(lc_href_link(FILENAME_PRODUCTS, $lC_Product->getKeyword()), $lC_Product->getTitle()) . '</div>' . "\n";
          if ($lC_Product->hasImage()) {
            $this->_content .= '<div class="content-recently-visited-image">' . ((SERVICE_RECENTLY_VISITED_SHOW_PRODUCT_IMAGES == '1') ? lc_link_object(lc_href_link(FILENAME_PRODUCTS, $lC_Product->getKeyword()), $lC_Image->show($lC_Product->getImage(), $lC_Product->getTitle(), 'class="content-recently-visited-image-src"', 'small')) : NULL) . '</div>' . "\n";
          }
          $this->_content .= '  <div class="content-recently-visited-price pricing-row">' . $lC_Product->getPriceFormated(true) . '</div>' . "\n" . 
                             '  <div class="content-recently-visited-from">' . sprintf($lC_Language->get('recently_visited_item_in_category'), lc_link_object(lc_href_link(FILENAME_DEFAULT, 'cPath=' . $product['category_path']), $product['category_name'])) . '</div>' . "\n" .
                             '</div>' . "\n";
        }                                
      }
      
      if ($lC_RecentlyVisited->hasCategories()) {
        foreach ($lC_RecentlyVisited->getCategories() as $category) {
          // VQMOD-hookpoint; DO NOT MODIFY OR REMOVE THE LINE BELOW
          if (!empty($category['parent_id'])) { 
            $this->_content .= '<div class="content-recently-visited-container">' . "\n" . 
                               '  <div class="content-recently-visited-name">' . sprintf($lC_Language->get('recently_visited_item_in_category'), lc_link_object(lc_href_link(FILENAME_DEFAULT, 'cPath=' . $product['category_path']), $product['category_name'])). '</div>' . "\n";
            if (isset($category['image']) && empty($category['image']) === false) {
              $this->_content .= '<div class="content-recently-visited-image">' . ((SERVICE_RECENTLY_VISITED_SHOW_CATEGORY_IMAGES == '1') ? lc_link_object(lc_href_link(FILENAME_DEFAULT, 'cPath=' . $category['id']), lc_image('images/categories/' . $category['image'], $category['name'], null, null, 'class="content-recently-visited-image-src"')) : NULL) . '</div>' . "\n";
            }
            $this->_content .= '  <div class="content-recently-visited-price pricing-row"></div>' . "\n" . 
                               '  <div class="content-recently-visited-from">' . sprintf($lC_Language->get('recently_visited_item_in_category'), lc_link_object(lc_href_link(FILENAME_DEFAULT, 'cPath=' . $category['parent_id']), $category['parent_name'])) . '</div>' . "\n" . 
                               '</div>' . "\n";          
          }
        }
      }

      if ($lC_RecentlyVisited->hasSearches()) {

        foreach ($lC_RecentlyVisited->getSearches() as $searchphrase) {
//          $this->_content .= '<div class="content-recently-visited-searches">' . lc_link_object(lc_href_link(FILENAME_SEARCH, 'keywords=' . $searchphrase['keywords']), lc_output_string_protected($searchphrase['keywords'])) . ' <i>(' . number_format($searchphrase['results']) . ' results)</i></div>';
        }
      }
    }
  }
}
?>