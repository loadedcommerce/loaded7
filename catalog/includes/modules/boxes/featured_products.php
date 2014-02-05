<?php
/**
  @package    catalog::modules::boxes
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: featured_products.php v1.0 2013-08-08 datazen $
*/
class lC_Boxes_featured_products extends lC_Modules {
  var $_title,
      $_code = 'featured_products',
      $_author_name = 'Loaded Commerce',
      $_author_www = 'http://www.loadedcommerce.com',
      $_group = 'boxes';

  public function lC_Boxes_featured_products() {
    global $lC_Language;
    
    if (function_exists($lC_Language->injectDefinitions)) $lC_Language->injectDefinitions('modules/' . $_GET['set'] . '/' . $this->_code . '.xml');

    $this->_title = $lC_Language->get('box_featured_products_heading');
  }

  public function initialize() {
    global $lC_Database, $lC_Currencies, $lC_Cache, $lC_Language, $lC_Image;

    $this->_title_link = lc_href_link(FILENAME_PRODUCTS, 'featured_products');

    $Qfeatured = $lC_Database->query('select products_id 
                                      from :table_featured_products 
                                     where str_to_date(expires_date, "%Y-%m-%d") >= str_to_date(now(), "%Y-%m-%d") 
                                       and status = 1 
                                     order by rand() 
                                     limit 1');
    $Qfeatured->bindTable(':table_featured_products', TABLE_FEATURED_PRODUCTS);
    $Qfeatured->execute();

    if ($Qfeatured->numberOfRows() > 0) {
      $lC_Product = new lC_Product($Qfeatured->valueInt('products_id'));
      
      $this->_content = '';
      if ($lC_Product->hasImage()) {
        $this->_content = '<li class="box-featured-products-image">' . lc_link_object(lc_href_link(FILENAME_PRODUCTS, $lC_Product->getKeyword()), $lC_Image->show($lC_Product->getImage(), $lC_Product->getTitle(), 'class="content-featured-products-image-src"')) . '</li>';
      }
      $this->_content .= '<li class="box-featured-products-name">' . lc_link_object(lc_href_link(FILENAME_PRODUCTS, $lC_Product->getKeyword()), $lC_Product->getTitle()) . '</li>';
      $this->_content .= '<li class="box-featured-products-price">' . $lC_Product->getPriceFormated(true) . '</li>';
      $this->_content .= '<li class="box-featured-products-buy-now"><button onclick="window.location.href=\'' . lc_href_link(FILENAME_PRODUCTS, $lC_Product->getKeyword() . '&action=cart_add') . '\'" type="button">' . $lC_Language->get('button_buy_now') . '</button>';
    }
  }

  public function install() {
    global $lC_Database;

    parent::install();

  }

  public function getKeys() {
  }
}
?>