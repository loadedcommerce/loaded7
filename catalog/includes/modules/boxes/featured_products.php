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
      $_author_name = 'LoadedCommerce',
      $_author_www = 'http://www.loadedcommerce.com',
      $_group = 'boxes';

  public function lC_Boxes_featured_products() {
    global $lC_Language;
    
    if (function_exists($lC_Language->injectDefinitions)) $lC_Language->injectDefinitions('modules/' . $_GET['set'] . '/' . $this->_code . '.xml');

    $this->_title = $lC_Language->get('box_featured_products_heading');
  }

  public function initialize() {
    global $lC_Database, $lC_Services, $lC_Currencies, $lC_Cache, $lC_Language, $lC_Image;

    $this->_title_link = lc_href_link(FILENAME_PRODUCTS, 'featured_products');

    if ((BOX_FEATURED_PRODUCTS_CACHE > 0) && $lC_Cache->read('box-featured-' . $lC_Language->getCode() . '-' . $lC_Currencies->getCode(), BOX_FEATURED_PRODUCTS_CACHE)) {
      $data = $lC_Cache->getCache();
    } else {
      /*$Qfeatured = $lC_Database->query('select p.products_id, p.products_price, p.products_tax_class_id, pd.products_name, pd.products_keyword, s.specials_new_products_price, i.image from :table_products p left join :table_products_images i on (p.products_id = i.products_id and i.default_flag = :default_flag), :table_products_description pd, :table_specials s where s.status = 1 and s.products_id = p.products_id and p.products_status = 1 and p.products_id = pd.products_id and pd.language_id = :language_id order by s.specials_date_added desc limit :max_random_select_specials');
      $Qfeatured->bindTable(':table_products', TABLE_PRODUCTS);
      $Qfeatured->bindTable(':table_products_images', TABLE_PRODUCTS_IMAGES);
      $Qfeatured->bindTable(':table_products_description', TABLE_PRODUCTS_DESCRIPTION);
      $Qfeatured->bindInt(':default_flag', 1);
      $Qfeatured->bindTable(':table_specials', TABLE_SPECIALS);
      $Qfeatured->bindInt(':language_id', $lC_Language->getID());
      $Qfeatured->bindInt(':max_random_select_specials', BOX_SPECIALS_RANDOM_SELECT);
      $Qfeatured->executeRandomMulti();

      $data = array();

      if ($Qfeatured->numberOfRows()) {
        $data = $Qfeatured->toArray();

        $data['products_price'] = '<s>' . $lC_Currencies->displayPrice($Qfeatured->valueDecimal('products_price'), $Qfeatured->valueInt('products_tax_class_id')) . '</s>&nbsp;<span class="box-specials-price">' . $lC_Currencies->displayPrice($Qfeatured->valueDecimal('specials_new_products_price'), $Qfeatured->valueInt('products_tax_class_id')) . '</span>';

        $lC_Cache->write($data);
      }*/
    }

    if (empty($data) === false) {      
      $this->_content = '';
      if (empty($data['image']) === false) {
        $this->_content = '<li class="box-featured-image">' . lc_link_object(lc_href_link(FILENAME_PRODUCTS, $data['products_keyword']), $lC_Image->show($data['image'], $data['products_name'], 'class="box-featured-image-src"')) . '</li>';
      }
      $this->_content .= '<li class="box-featured-name">' . lc_link_object(lc_href_link(FILENAME_PRODUCTS, $data['products_keyword']), $data['products_name']) . '</li>';
      $this->_content .= '<li class="box-featured-price">' . $data['products_price'] . '</li>';
      $this->_content .= '<li class="box-featured-buy-now"><button onclick="window.location.href=\'' . lc_href_link(FILENAME_PRODUCTS, $data['products_keyword'] . '&action=cart_add') . '\'" title="" type="button">' . $lC_Language->get('button_buy_now') . '</button>';
    }
  }

  public function install() {
    global $lC_Database;

    parent::install();

    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Random Featured Product Selection', 'BOX_FEATURED_PRODUCTS_RANDOM_SELECT', '10', 'Select a random product from the featured products available.', '6', '0', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Cache Contents', 'BOX_FEATURED_PRODUCTS_CACHE', '1', 'Number of minutes to keep the contents cached (0 = no cache)', '6', '0', now())");
  }

  public function getKeys() {
    if (!isset($this->_keys)) {
      $this->_keys = array('BOX_FEATURED_PRODUCTS_RANDOM_SELECT', 'BOX_FEATURED_PRODUCTS_CACHE');
    }

    return $this->_keys;
  }
}
?>