<?php
/**
  @package    catalog::modules::content
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: featured_products.php v1.0 2013-08-08 datazen $
*/
class lC_Content_featured_products extends lC_Modules {
 /* 
  * Public variables 
  */    
  public $_title,
         $_code = 'featured_products',
         $_author_name = 'LoadedCommerce',
         $_author_www = 'http://www.loadedcommerce.com',
         $_group = 'content';
 /*   
  * Class constructor 
  */
  public function lC_Content_featured_products() {
    global $lC_Language;
    
    if (function_exists($lC_Language->injectDefinitions)) $lC_Language->injectDefinitions('modules/' . $_GET['set'] . '/' . $this->_code . '.xml'); 

    $this->_title = $lC_Language->get('featured_products_title');
  }
 /*
  * Returns the featured products HTML
  *
  * @access public
  * @return string
  */
  public function initialize() {
    global $lC_Database, $lC_Language, $lC_Currencies, $lC_Image;

    if (MODULE_CONTENT_FEATURED_PRODUCTS_MAX_DISPLAY > 0) {
      $limit = ' limit ' . MODULE_CONTENT_FEATURED_PRODUCTS_MAX_DISPLAY;
    }
    
    $Qfeatured = $lC_Database->query('select products_id 
                                        from :table_featured_products 
                                       where str_to_date(expires_date, "%Y-%m-%d") >= str_to_date(now(), "%Y-%m-%d") 
                                         and status = 1 
                                    order by rand() asc' . $limit);
    $Qfeatured->bindTable(':table_featured_products', TABLE_FEATURED_PRODUCTS);
    $Qfeatured->bindInt(':max_display_featured_products', MODULE_CONTENT_FEATURED_PRODUCTS_MAX_DISPLAY);
    $Qfeatured->execute();
    
    if (MODULE_CONTENT_FEATURED_PRODUCTS_CACHE > 0) {
      $Qfeatured->setCache('featured_products-' . $lC_Language->getCode() . '-' . $lC_Currencies->getCode(), MODULE_CONTENT_FEATURED_PRODUCTS_CACHE);
    }

    if ($Qfeatured->numberOfRows() > 0) {
      $this->_content = '';
      while ($Qfeatured->next()) {
        $lC_Product = new lC_Product($Qfeatured->valueInt('products_id'));

        $this->_content .= '<div class="content-featured-products-container">' . "\n" . 
                           '  <div class="content-featured-products-name">' . lc_link_object(lc_href_link(FILENAME_PRODUCTS, $lC_Product->getKeyword()), $lC_Product->getTitle()) . '</div>' . "\n";
        
        if ($lC_Product->hasImage()) {
          $this->_content .= '  <div class="content-featured-products-image">' . lc_link_object(lc_href_link(FILENAME_PRODUCTS, $lC_Product->getKeyword()), $lC_Image->show($lC_Product->getImage(), $lC_Product->getTitle(), 'class="content-featured-products-image-src"', 'small')) . '</div>' . "\n";
        }
        $this->_content .= '  <div class="content-featured-products-price">' . $lC_Product->getPriceFormated(true) . '</div>' . "\n" . 
                           '</div>' . "\n";
      }
    }

    $Qfeatured->freeResult();
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

    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Maximum Entries To Display', 'MODULE_CONTENT_FEATURED_PRODUCTS_MAX_DISPLAY', '10', 'Maximum number of featured products to display', '6', '0', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Cache Contents', 'MODULE_CONTENT_FEATURED_PRODUCTS_CACHE', '1440', 'Number of minutes to keep the contents cached (0 = no cache)', '6', '0', now())");
  }
 /*
  * Return the module keys
  *
  * @access public
  * @return array
  */
  public function getKeys() {
    if (!isset($this->_keys)) {
      $this->_keys = array('MODULE_CONTENT_FEATURED_PRODUCTS_MAX_DISPLAY', 'MODULE_CONTENT_FEATURED_PRODUCTS_CACHE');
    }

    return $this->_keys;
  }
}
?>