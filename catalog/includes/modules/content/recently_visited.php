<?php
/*
  $Id: recently_visited.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
class lC_Content_recently_visited extends lC_Modules {
 /* 
  * Public variables 
  */    
  public $_title,
         $_code = 'recently_visited',
         $_author_name = 'LoadedCommerce',
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
    global $lC_Services, $lC_RecentlyVisited, $lC_Language, $lC_Image;
    if ($lC_Services->isStarted('recently_visited') && $lC_RecentlyVisited->hasHistory()) {
      $this->_content = '<ul id="first-carousel" class="first-and-second-carousel jcarousel-skin-tango">';
                        
      if ($lC_RecentlyVisited->hasProducts()) {
        foreach ($lC_RecentlyVisited->getProducts() as $product) {
          $this->_content .= '<li>' . ((SERVICE_RECENTLY_VISITED_SHOW_PRODUCT_IMAGES == '1') ? lc_link_object(lc_href_link(FILENAME_PRODUCTS, $product['keyword']), $lC_Image->show($product['image'], $product['name']), 'class="product_image"') : NULL)  .
                               '<div class="product_info">' . 
                                 '<h3>' . lc_link_object(lc_href_link(FILENAME_PRODUCTS, $product['keyword']), $product['name'])  . '</h3>' . 
                               '<small><i>(' . sprintf($lC_Language->get('recently_visited_item_in_category'), lc_link_object(lc_href_link(FILENAME_DEFAULT, 'cPath=' . $product['category_path']), $product['category_name'])) . ')</i></small></div>' .
                               ((SERVICE_RECENTLY_VISITED_SHOW_PRODUCT_PRICES == '1') ? 
                               '<div class="price_info"> <!-- a href="#">+ Add to wishlist</a -->' . 
                                 '<button onclick="window.location.href=\'' . lc_href_link(FILENAME_PRODUCTS, $product['keyword']) . '\'" class="price_add" title="" type="button"><span class="pr_price">' . $product['price']. '</span><span class="pr_add">' . $lC_Language->get('button_add_to_cart') . '</span></button>' . 
                               '</div>' : NULL) . 
                             '</li>';
        }                                
      }
      
      if ($lC_RecentlyVisited->hasCategories()) {
        foreach ($lC_RecentlyVisited->getCategories() as $category) {
          if (!empty($category['parent_id'])) {  
            $this->_content .= '<li>' . ((SERVICE_RECENTLY_VISITED_SHOW_CATEGORY_IMAGES == '1') ? lc_link_object(lc_href_link(FILENAME_DEFAULT, 'cPath=' . $category['id']), lc_image('images/categories/' . $category['image'], $category['name']), 'class="product_image"') : NULL)  .
                                 '<div class="product_info">' . 
                                   '<h3>' . lc_link_object(lc_href_link(FILENAME_DEFAULT, 'cPath=' . $category['path']), $category['name'])  . '</h3>';
            $this->_content .=     '<small><i>(' . sprintf($lC_Language->get('recently_visited_item_in_category'), lc_link_object(lc_href_link(FILENAME_DEFAULT, 'cPath=' . $category['parent_id']), $category['parent_name'])) . ')</i></small></div>';
            $this->_content .= '</li>';          
          }
        }
      }

      if ($lC_RecentlyVisited->hasSearches()) {

        foreach ($lC_RecentlyVisited->getSearches() as $searchphrase) {
          $this->_content .= '<li>' . lc_link_object(lc_href_link(FILENAME_SEARCH, 'keywords=' . $searchphrase['keywords']), lc_output_string_protected($searchphrase['keywords'])) . ' <i>(' . number_format($searchphrase['results']) . ' results)</i></li>';
        }
      }

      $this->_content .= '  </ul>';
    }
  }
}
?>