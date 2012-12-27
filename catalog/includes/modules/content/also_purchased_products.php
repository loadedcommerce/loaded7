<?php
/*
  $Id: also_purchased_products.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
class lC_Content_also_purchased_products extends lC_Modules {
 /* 
  * Public variables 
  */  
  public $_title,
         $_code = 'also_purchased_products',
         $_author_name = 'Loaded Commerce',
         $_author_www = 'http://www.loadedcommerce.com',
         $_group = 'content';
 /* 
  * Class constructor 
  */
  public function __construct() {
    global $lC_Language;           

    if (function_exists($lC_Language->injectDefinitions)) $lC_Language->injectDefinitions('modules/' . $_GET['set'] . '/' . $this->_code . '.xml');      
    
    $this->_title = $lC_Language->get('customers_also_purchased_title');
  }
 /*
  * Returns the also puchased HTML
  *
  * @access public
  * @return string
  */
  public function initialize() {
    global $lC_Database, $lC_Language, $lC_Product, $lC_Image;
    
    if (isset($lC_Product)) {
      $Qorders = $lC_Database->query('select p.products_id, p.products_price, pd.products_name, pd.products_description, pd.products_keyword, i.image from :table_orders_products opa, :table_orders_products opb, :table_orders o, :table_products p left join :table_products_images i on (p.products_id = i.products_id and i.default_flag = :default_flag), :table_products_description pd where opa.products_id = :products_id and opa.orders_id = opb.orders_id and opb.products_id != :products_id and opb.products_id = p.products_id and opb.orders_id = o.orders_id and p.products_status = 1 and p.products_id = pd.products_id and pd.language_id = :language_id group by p.products_id order by o.date_purchased desc limit :limit');
      $Qorders->bindTable(':table_orders_products', TABLE_ORDERS_PRODUCTS);
      $Qorders->bindTable(':table_orders_products', TABLE_ORDERS_PRODUCTS);
      $Qorders->bindTable(':table_orders', TABLE_ORDERS);
      $Qorders->bindTable(':table_products', TABLE_PRODUCTS);
      $Qorders->bindTable(':table_products_images', TABLE_PRODUCTS_IMAGES);
      $Qorders->bindTable(':table_products_description', TABLE_PRODUCTS_DESCRIPTION);
      $Qorders->bindInt(':default_flag', 1);
      $Qorders->bindInt(':products_id', $lC_Product->getID());
      $Qorders->bindInt(':products_id', $lC_Product->getID());
      $Qorders->bindInt(':language_id', $lC_Language->getID());
      $Qorders->bindInt(':limit', MODULE_CONTENT_ALSO_PURCHASED_MAX_DISPLAY);
      
      if (MODULE_CONTENT_ALSO_PURCHASED_PRODUCTS_CACHE > 0) {
        $Qorders->setCache('also_purchased-' . $lC_Product->getID(), MODULE_CONTENT_ALSO_PURCHASED_PRODUCTS_CACHE);
      }
      
      $Qorders->execute();

      if ($Qorders->numberOfRows() >= MODULE_CONTENT_ALSO_PURCHASED_MIN_DISPLAY) {

        $this->_content = '<ul id="first-carousel" class="first-and-second-carousel jcarousel-skin-tango">';

        while ($Qorders->next()) {
          $this->_content .= '<li>' . lc_link_object(lc_href_link(FILENAME_PRODUCTS, $Qorders->value('products_keyword')), $lC_Image->show($Qorders->value('image'), $Qorders->value('products_name')), 'class="product_image"')  . '
                                <div class="product_info">
                                  <h3>' . lc_link_object(lc_href_link(FILENAME_PRODUCTS, $Qorders->value('products_keyword')), $Qorders->value('products_name'))  . '</h3>
                                <small>' . substr($Qorders->value('products_description'), 0, 60) . '...</small></div>
                                <div class="price_info">
                                  <button onclick="window.location.href=\'' . lc_href_link(FILENAME_PRODUCTS, $Qorders->value('products_keyword') . '&action=cart_add') . '\'" class="price_add" title="" type="button"><span class="pr_price">' . $lC_Product->getPriceFormated(true) . '</span><span class="pr_add">' . $lC_Language->get('button_add_to_cart') . '</span></button>
                                </div>
                              </li>';
        }

        $this->_content .= '</ul>';
      }

      $Qorders->freeResult();
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

    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Minimum Entries To Display', 'MODULE_CONTENT_ALSO_PURCHASED_MIN_DISPLAY', '1', 'Minimum number of also purchased products to display', '6', '0', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Maximum Entries To Display', 'MODULE_CONTENT_ALSO_PURCHASED_MAX_DISPLAY', '6', 'Maximum number of also purchased products to display', '6', '0', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Cache Contents', 'MODULE_CONTENT_ALSO_PURCHASED_PRODUCTS_CACHE', '60', 'Number of minutes to keep the contents cached (0 = no cache)', '6', '0', now())");
  }
 /*
  * Return the module keys
  *
  * @access public
  * @return array
  */
  public function getKeys() {
    if (!isset($this->_keys)) {
      $this->_keys = array('MODULE_CONTENT_ALSO_PURCHASED_MIN_DISPLAY', 'MODULE_CONTENT_ALSO_PURCHASED_MAX_DISPLAY', 'MODULE_CONTENT_ALSO_PURCHASED_PRODUCTS_CACHE');
    }

    return $this->_keys;
  }
}
?>