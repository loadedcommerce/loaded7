<?php
/*
  $Id: new_products.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
class lC_Content_new_products extends lC_Modules {
 /* 
  * Public variables 
  */   
  public $_title,
         $_code = 'new_products',
         $_author_name = 'Loaded Commerce',
         $_author_www = 'http://www.loadedcommerce.com',
         $_group = 'content';
 /* 
  * Class constructor 
  */
  public function __construct() {
    global $lC_Language;
    
    if (function_exists($lC_Language->injectDefinitions)) $lC_Language->injectDefinitions('modules/' . $_GET['set'] . '/' . $this->_code . '.xml'); 

    $this->_title = $lC_Language->get('new_products_title');
  }
 /*
  * Returns the new products HTML
  *
  * @access public
  * @return string
  */
  public function initialize() {
    global $lC_Database, $lC_Cache, $lC_Language, $lC_Currencies, $lC_Image, $current_category_id;

    $data = array();

    if ( (MODULE_CONTENT_NEW_PRODUCTS_CACHE > 0) && $lC_Cache->read('new_products-' . $lC_Language->getCode() . '-' . $lC_Currencies->getCode() . '-' . $current_category_id, MODULE_CONTENT_NEW_PRODUCTS_CACHE) ) {
      $data = $lC_Cache->getCache();
    } else {
      if ( $current_category_id < 1 ) {
        $Qproducts = $lC_Database->query('select products_id from :table_products where products_status = :products_status and parent_id = :parent_id order by products_date_added desc limit :max_display_new_products');
      } else {
        $Qproducts = $lC_Database->query('select distinct p2c.products_id from :table_products p, :table_products_to_categories p2c, :table_categories c where c.parent_id = :category_parent_id and c.categories_id = p2c.categories_id and p2c.products_id = p.products_id and p.products_status = :products_status and p.parent_id = :parent_id order by p.products_date_added desc limit :max_display_new_products');
        $Qproducts->bindTable(':table_products_to_categories', TABLE_PRODUCTS_TO_CATEGORIES);
        $Qproducts->bindTable(':table_categories', TABLE_CATEGORIES);
        $Qproducts->bindInt(':category_parent_id', $current_category_id);
      }

      $Qproducts->bindTable(':table_products', TABLE_PRODUCTS);
      $Qproducts->bindInt(':products_status', 1);
      $Qproducts->bindInt(':parent_id', 0);
      $Qproducts->bindInt(':max_display_new_products', MODULE_CONTENT_NEW_PRODUCTS_MAX_DISPLAY);
      $Qproducts->execute();

      while ( $Qproducts->next() ) {
        $lC_Product = new lC_Product($Qproducts->valueInt('products_id'));

        $data[$lC_Product->getID()] = $lC_Product->getData();
        $data[$lC_Product->getID()]['display_price'] = $lC_Product->getPriceFormated(true);
        $data[$lC_Product->getID()]['display_image'] = $lC_Product->getImage();
      }

      $lC_Cache->write($data);
    }

    if ( !empty($data) ) {
      $this->_content = '<ul id="first-carousel" class="first-and-second-carousel jcarousel-skin-tango">';

      foreach ( $data as $product ) {
        $this->_content .= '<li>' . lc_link_object(lc_href_link(FILENAME_PRODUCTS, $product['keyword']), $lC_Image->show($product['display_image'], $product['name']), 'class="product_image"')  . '
                              <div class="product_info">
                                <h3>' . lc_link_object(lc_href_link(FILENAME_PRODUCTS, $product['keyword']), $product['name'])  . '</h3>
                              <small>' . substr($product['description'], 0, 60) . '...</small></div>
                              <div class="price_info" style="margin-top:10px;"> <a href="javascript://" onclick="alert(\'' . $lC_Language->get('feature_not_available') . '\'); return false;">+ ' . $lC_Language->get('add_to_wishlist') . '</a>
                                <button onclick="window.location.href=\'' . lc_href_link(FILENAME_PRODUCTS, $product['keyword'] . '&action=cart_add') . '\'" class="price_add" title="" type="button"><span class="pr_price">' . $product['display_price']. '</span><span class="pr_add">' . $lC_Language->get('button_add_to_cart') . '</span></button>
                              </div>
                            </li>';
      }
                    
      $this->_content .= '</ul>';
    }
  }
 /*
  * Install the module
  *
  * @access public
  * @return void
  */
  public function install() {
    global $lC_Database;

    parent::install();

    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Maximum Entries To Display', 'MODULE_CONTENT_NEW_PRODUCTS_MAX_DISPLAY', '9', 'Maximum number of new products to display', '6', '0', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Cache Contents', 'MODULE_CONTENT_NEW_PRODUCTS_CACHE', '60', 'Number of minutes to keep the contents cached (0 = no cache)', '6', '0', now())");
  }
 /*
  * Return the module keys
  *
  * @access public
  * @return array
  */
  public function getKeys() {
    if (!isset($this->_keys)) {
      $this->_keys = array('MODULE_CONTENT_NEW_PRODUCTS_MAX_DISPLAY', 'MODULE_CONTENT_NEW_PRODUCTS_CACHE');
    }

    return $this->_keys;
  }
}
?>