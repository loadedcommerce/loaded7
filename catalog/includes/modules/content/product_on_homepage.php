<?php
/*
  $Id: product_on_homepage.php v1.0 2011-11-04 kiran $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
class lC_Content_product_on_homepage extends lC_Modules {
 /* 
  * Public variables 
  */  
  public $_title,
         $_code = 'product_on_homepage',
         $_author_name = 'Loaded Commerce',
         $_author_www = 'http://www.loadedcommerce.com',
         $_group = 'content';
 /* 
  * Class constructor 
  */
  public function __construct() {
    global $lC_Language;           

    if (function_exists($lC_Language->injectDefinitions)) $lC_Language->injectDefinitions('modules/' . $_GET['set'] . '/' . $this->_code . '.xml');      
    
    $this->_title = $lC_Language->get('product_on_homepage_title');
  }
 /*
  * Returns the also puchased HTML
  *
  * @access public
  * @return string
  */
  public function initialize() {
    global $lC_Session, $lC_Language, $lC_Product, $lC_Image, $lC_Currencies,$lC_ShoppingCart, $lC_Reviews;
    
    if (defined('MODULE_CONTENT_PRODUCT_ON_HOMEPAGE')) {
      $lC_Product = new lC_Product(MODULE_CONTENT_PRODUCT_ON_HOMEPAGE);
      
      $product_title = $lC_Product->getTitle();
      $product_image_large = $lC_Image->getAddress($lC_Product->getImage(), 'large');
      $product_image_responsive = (file_exists(DIR_FS_CATALOG . $lC_Image->getAddress($lC_Product->getImage(), 'originals'))) ? lc_href_link($lC_Image->getAddress($lC_Product->getImage(), 'originals')) : lc_href_link(DIR_WS_IMAGES . 'no_image.png');
      $product_add_url = lc_href_link(FILENAME_PRODUCTS, $lC_Product->getKeyword() . '&action=cart_add');
      $product_description = ($lC_Product->getDescription() != null) ? $lC_Product->getDescription() : $lC_Language->get('no_description_available');
      $product_price = $lC_Product->getPriceFormated(true);
      $product_review_rating = lc_image(DIR_WS_TEMPLATE_IMAGES . 'stars_' . $lC_Product->getData('reviews_average_rating') . '.png', sprintf($lC_Language->get('rating_of_5_stars'), $lC_Product->getData('reviews_average_rating')));
      $product_shipping = lc_link_object(lc_href_link(FILENAME_INFO, 'shipping'), $lC_Language->get('more_information'), 'target="_blank"');

      $lang_avarage_rating = $lC_Language->get('average_rating');
      $lang_button_close = $lC_Language->get('button_close');
      $lang_add_to_cart_qty = $lC_Language->get('text_add_to_cart_quantity');
      $lang_buy_now = $lC_Language->get('button_buy_now');
      $lang_enlarge_image = $lC_Language->get('enlarge_image');

      $availability = ( (STOCK_CHECK == '1') && ($lC_ShoppingCart->isInStock($lC_Product->getID()) === false) ) ? '<span class="product-out-of-stock red">' . STOCK_MARK_PRODUCT_OUT_OF_STOCK . '</span>' : $lC_Product->getAttribute('shipping_availability');
      if ($lC_Product->getAttribute('manufacturers') != null || $lC_Product->hasModel()) {
        $content_products_info_manuf_model = '<div class="content-products-info-manuf-model">' . "\n" . 
             '  <!--span class="content-products-info-manuf small-margin-right">' . $lC_Product->getAttribute('manufacturers') . ':</span -->' . "\n" .
             '  <span class="content-products-info-model">' . $lC_Product->getModel() . '</span>' . "\n" . 
             '</div>' . "\n";
      }
     
      $Qreviews = lC_Reviews::getListing($lC_Product->getID());
      if ($lC_Reviews->getTotal($lC_Product->getID()) > 0) {
        $product_reviews =  '<span><a href="' . lc_href_link(FILENAME_PRODUCTS, 'reviews&' . $lC_Product->getKeyword()) . '" target="_blank">(' . $lC_Language->get('more_information') . ')</a></span>' . "\n";
      } else {            
        $product_reviews =  '<span><a href="' . lc_href_link(FILENAME_PRODUCTS, 'reviews=new&' . $lC_Product->getKeyword()) . '" target="_blank">(' . $lC_Language->get('text_write_review_first') . '</a>)</span>' . "\n";
      }
      
      
      $this->_content = <<<EOF
      <div class="row">
        <div class="col-sm-4 col-lg-4 clearfix">
          <div class="thumbnail large-margin-top no-margin-bottom text-center">
            <a data-toggle="modal" href="#popup-image-modal" title="$product_title"><img class="img-responsive" src="$product_image_large" title="$product_title" alt="$product_title" /></a>
          </div>  
          <!-- Button trigger modal -->
          <p class="text-center no-margin-top no-margin-bottom"><a data-toggle="modal" href="#popup-image-modal" class="btn normal">$lang_enlarge_image</a></p>
          <!-- Modal -->
          <div class="modal fade" id="popup-image-modal">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                  <h4 class="modal-title">$product_title</h4>
                </div>
                <div class="modal-body">
                  <img class="img-responsive" alt="$product_title" src="$product_image_responsive">
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">$lang_button_close</button>
                </div>
              </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
          </div><!-- /.modal -->
          <hr>
      <!-- Extra images -->
        </div>
        <form role="form" class="form-horizontal" name="cart_quantity" id="cart_quantity" action="$product_add_url" method="post">
          <div class="col-sm-8 col-lg-8 clearfix">
	        $content_products_info_manuf_model
            <h1>$product_title</h1>
            <hr>
            <p class="content-products-info-desc">$product_description</p>
            <div class="well large-margin-top margin-bottom">
              <div class="content-products-info-price-container clearfix">
                <span class="content-products-info-price pull-left lt-blue">$product_price</span>
                <span class="content-products-info-avail with-padding-no-top-bottom">$availability</span> ($product_shipping)
              </div>
              <div class="content-products-info-reviews-container">
                <label class="content-products-info-reviews-rating-label with-padding-no-top-bottom">$lang_avarage_rating</label>
                <span class="content-products-info-reviews-rating margin-right">$product_review_rating</span>
                  $product_reviews    
              </div>  
              <!-- options and varients -->
            </div>
          </div>
          <div class="row">
            <div class="col-sm-6 col-lg-6 align-right mid-margin-top">
              <div class="form-group">
                <label class="content-products-info-qty-label">$lang_add_to_cart_qty</label>
                <input type="text" name="content-products-info-qty-input" onfocus="this.select();" class="form-control content-products-info-qty-input" value="1">
              </div>
            </div>
            <div class="col-sm-6 col-lg-6">
              <p class="margin-top"><button onclick="$('#cart_quantity').submit();" class="btn btn-block btn-lg btn-success">$lang_buy_now</button></p>
            </div>
          </div> 
        </form>
      </div>
EOF;
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

    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, use_function, set_function) values ('Product On Home Page', 'MODULE_CONTENT_PRODUCT_ON_HOMEPAGE', '', 'Select a product to show on Home Page.', '6', '0', now(), 'lc_cfg_set_product_on_homepage', 'lc_cfg_set_product_on_homepage')");

  }
 /*
  * Return the module keys
  *
  * @access public
  * @return array
  */
  public function getKeys() {
    if (!isset($this->_keys)) {
      $this->_keys = array('MODULE_CONTENT_PRODUCT_ON_HOMEPAGE');
    }

    return $this->_keys;
  }
}
?>