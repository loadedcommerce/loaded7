<?php
/**
  @package    catalog::modules::content
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: specials.php v1.0 2013-08-08 datazen $
*/
class lC_Content_specials extends lC_Modules {
 /* 
  * Public variables 
  */   
  public $_title,
         $_code = 'specials',
         $_author_name = 'Loaded Commerce',
         $_author_www = 'http://www.loadedcommerce.com',
         $_group = 'content';
 /* 
  * Class constructor 
  */
  public function __construct() {
    global $lC_Language;
    
    if (function_exists($lC_Language->injectDefinitions)) $lC_Language->injectDefinitions('modules/' . $_GET['set'] . '/' . $this->_code . '.xml'); 

    $this->_title = $lC_Language->get('specials_title');
  }
 /*
  * Returns the specials HTML
  *
  * @access public
  * @return string
  */
  public function initialize() {
    global $lC_Database, $lC_Cache, $lC_Language, $lC_Currencies, $lC_Image, $current_category_id;

    $data = array();

    if ( ($lC_Cache->isEnabled() && MODULE_CONTENT_SPECIALS_CACHE > 0) && $lC_Cache->read('specials-' . $lC_Language->getCode() . '-' . $lC_Currencies->getCode() . '-' . $current_category_id, MODULE_CONTENT_SPECIALS_CACHE) ) {
      $data = $lC_Cache->getCache();
    } else {
      $Qproducts = $lC_Database->query('select distinct p.products_id, pi.image, pd.products_name from :table_products p, :table_products_images pi, :table_products_description pd, :table_specials s where p.products_status = :products_status and p.is_subproduct = :is_subproduct and s.status = :specials_status and p.products_id = s.products_id and pi.id = s.products_id and pd.products_id = s.products_id order by rand() limit :max_display_specials');
      $Qproducts->bindTable(':table_products', TABLE_PRODUCTS);
      $Qproducts->bindTable(':table_products_images', TABLE_PRODUCTS_IMAGES);
      $Qproducts->bindTable(':table_products_description', TABLE_PRODUCTS_DESCRIPTION);
      $Qproducts->bindTable(':table_specials', TABLE_SPECIALS);
      $Qproducts->bindInt(':products_status', 1);
      $Qproducts->bindInt(':is_subproduct', 0);
      $Qproducts->bindInt(':specials_status', 1);
      $Qproducts->bindInt(':max_display_specials', MODULE_CONTENT_SPECIALS_MAX_DISPLAY);
      $Qproducts->execute();

      while ( $Qproducts->next() ) {
        // VQMOD-hookpoint; DO NOT MODIFY OR REMOVE THE LINE BELOW
        $lC_Product = new lC_Product($Qproducts->valueInt('products_id'));

        $data[$lC_Product->getID()] = $lC_Product->getData();
        $data[$lC_Product->getID()]['display_price'] = $lC_Product->getPriceFormated(true);
        $data[$lC_Product->getID()]['display_image'] = $lC_Product->getImage();
      }

      if ($lC_Cache->isEnabled()) $lC_Cache->write($data);
    }

    if ( !empty($data) ) {

      $this->_content = '';
      foreach ( $data as $product ) {
        $this->_content .= '<div class="content-specials-container">' . "\n";
        $this->_content .= '  <div class="content-specials-image">' . lc_link_object(lc_href_link(FILENAME_PRODUCTS, $product['keyword']), $lC_Image->show($product['display_image'], $product['name'], 'class="content-specials-image-src"'))  . '</div>' . "\n" . 
                           '  <div class="content-specials-name">' . lc_link_object(lc_href_link(FILENAME_PRODUCTS, $product['keyword']), $product['name'])  . '</div>' . "\n" . 
                           '  <div class="content-specials-desc">' . ((strlen(lc_clean_html($product['description'])) > 65) ? substr(lc_clean_html($product['description']), 0, 62) . '...' : lc_clean_html($product['description'])) . '</div>' . "\n" . 
                           '  <div class="content-specials-price pricing-row">' . $product['display_price']. '</div>' . "\n" .
                           '  <div class="content-specials-button pricing-row buy-btn-div"><button class="content-specials-add-button" onclick="window.location.href=\'' . lc_href_link(FILENAME_PRODUCTS, $product['keyword'] . '&action=cart_add') . '\'" type="button">' . $lC_Language->get('specials_button_buy_now') . '</button></div>' . "\n";
        $this->_content .= '</div>' . "\n";
      }
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

    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Maximum Entries To Display', 'MODULE_CONTENT_SPECIALS_MAX_DISPLAY', '9', 'Maximum number of specials to display', '6', '0', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Cache Contents', 'MODULE_CONTENT_SPECIALS_CACHE', '60', 'Number of minutes to keep the contents cached (0 = no cache)', '6', '0', now())");
  }
 /*
  * Return the module keys
  *
  * @access public
  * @return array
  */
  public function getKeys() {
    if (!isset($this->_keys)) {
      $this->_keys = array('MODULE_CONTENT_SPECIALS_MAX_DISPLAY', 'MODULE_CONTENT_SPECIALS_CACHE');
    }

    return $this->_keys;
  }
}
?>