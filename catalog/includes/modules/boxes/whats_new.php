<?php
/**
  @package    catalog::modules::boxes
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: whats_new.php v1.0 2013-08-08 datazen $
*/
class lC_Boxes_whats_new extends lC_Modules {
  var $_title,
      $_code = 'whats_new',
      $_author_name = 'Loaded Commerce',
      $_author_www = 'http://www.loadedcommerce.com',
      $_group = 'boxes';

  public function __construct() {
    global $lC_Language;
    
    if (function_exists($lC_Language->injectDefinitions)) $lC_Language->injectDefinitions('modules/' . $_GET['set'] . '/' . $this->_code . '.xml');

    $this->_title = $lC_Language->get('box_whats_new_heading');
  }

  public function initialize() {
    global $lC_Cache, $lC_Database, $lC_Services, $lC_Currencies, $lC_Specials, $lC_Language, $lC_Image;

    $this->_title_link = lc_href_link(FILENAME_PRODUCTS, 'new');

    $data = array();

    if ( (BOX_WHATS_NEW_CACHE > 0) && $lC_Cache->read('box-whats_new-' . $lC_Language->getCode() . '-' . $lC_Currencies->getCode(), BOX_WHATS_NEW_CACHE) ) {
      $data = $lC_Cache->getCache();
    } else {
      $Qnew = $lC_Database->query('select products_id from :table_products where products_status = :products_status order by products_date_added desc limit :max_random_select_new');
      $Qnew->bindTable(':table_products', TABLE_PRODUCTS);
      $Qnew->bindInt(':products_status', 1);
      $Qnew->bindInt(':max_random_select_new', BOX_WHATS_NEW_RANDOM_SELECT);
      $Qnew->executeRandomMulti();

      if ( $Qnew->numberOfRows() ) {
        // VQMOD-hookpoint; DO NOT MODIFY OR REMOVE THE LINE BELOW
        $lC_Product = new lC_Product($Qnew->valueInt('products_id'));

        $data = $lC_Product->getData();

        $data['display_price'] = $lC_Product->getPriceFormated(true);
        $data['display_image'] = $lC_Product->getImage();
      }

      if (isset($lC_Cache)) $lC_Cache->write($data);
    }

    if ( !empty($data) ) {
      $this->_content = '';
      if (empty($data['display_image']) === false) {
        $this->_content = '<li class="box-whats-new-image"><div class="thumbnail">' . lc_link_object(lc_href_link(FILENAME_PRODUCTS, $data['keyword']), $lC_Image->show($data['display_image'], $data['name'], 'class="box-whats-new-image-src"')) . '</div></li>';
      }
      $this->_content .= '<li class="box-whats-new-name">' . lc_link_object(lc_href_link(FILENAME_PRODUCTS, $data['keyword']), $data['name']) . '</li>';
      $this->_content .= '<li class="box-whats-new-price pricing-row">' . $data['display_price'] . '</li>';
      $this->_content .= '<li class="box-whats-new-buy-now pricing-row buy-btn-div"><button onclick="window.location.href=\'' . lc_href_link(FILENAME_PRODUCTS, $data['keyword'] . '&action=cart_add') . '\'" title="" type="button">' . $lC_Language->get('button_buy_now') . '</button>';
    }
  }

  public function install() {
    global $lC_Database;

    parent::install();

    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Random New Product Selection', 'BOX_WHATS_NEW_RANDOM_SELECT', '10', 'Select a random new product from this amount of the newest products available', '6', '0', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Cache Contents', 'BOX_WHATS_NEW_CACHE', '1', 'Number of minutes to keep the contents cached (0 = no cache)', '6', '0', now())");
  }

  public function getKeys() {
    if ( !isset($this->_keys) ) {
      $this->_keys = array('BOX_WHATS_NEW_RANDOM_SELECT', 'BOX_WHATS_NEW_CACHE');
    }

    return $this->_keys;
  }
}
?>