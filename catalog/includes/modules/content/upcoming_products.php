<?php
/**
  @package    catalog::templates::content
  @author     Loaded Commerce, LLC
  @copyright  Copyright 2003-2013 Loaded Commerce Development Team
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on DevKit http://www.bootstraptor.com under GPL license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: upcoming.php v1.0 2013-08-08 datazen $
*/
class lC_Content_upcoming_products extends lC_Modules {
 /* 
  * Public variables 
  */    
  public $_title,
         $_code = 'upcoming_products',
         $_author_name = 'LoadedCommerce',
         $_author_www = 'http://www.loadedcommerce.com',
         $_group = 'content';
 /*   
  * Class constructor 
  */
  public function lC_Content_upcoming_products() {
    global $lC_Language;
    
    if (function_exists($lC_Language->injectDefinitions)) $lC_Language->injectDefinitions('modules/' . $_GET['set'] . '/' . $this->_code . '.xml'); 

    $this->_title = $lC_Language->get('upcoming_products_title');
  }
 /*
  * Returns the upcoming products HTML
  *
  * @access public
  * @return string
  */
  public function initialize() {
    global $lC_Database, $lC_Language, $lC_Currencies, $lC_Image;

    $Qupcoming = $lC_Database->query('select p.products_id, pa.value as date_expected from :table_products p, :table_templates_boxes tb, :table_product_attributes pa where tb.code = :code and tb.id = pa.id and to_days(str_to_date(pa.value, "%Y-%m-%d")) >= to_days(now()) and pa.products_id = p.products_id and p.products_status = :products_status order by pa.value limit :max_display_upcoming_products');
    $Qupcoming->bindTable(':table_products', TABLE_PRODUCTS);
    $Qupcoming->bindTable(':table_templates_boxes', TABLE_TEMPLATES_BOXES);
    $Qupcoming->bindTable(':table_product_attributes', TABLE_PRODUCT_ATTRIBUTES);
    $Qupcoming->bindValue(':code', 'date_available');
    $Qupcoming->bindInt(':products_status', 1);
    $Qupcoming->bindInt(':max_display_upcoming_products', MODULE_CONTENT_UPCOMING_PRODUCTS_MAX_DISPLAY);
    
    if (MODULE_CONTENT_UPCOMING_PRODUCTS_CACHE > 0) {
      $Qupcoming->setCache('upcoming_products-' . $lC_Language->getCode() . '-' . $lC_Currencies->getCode(), MODULE_CONTENT_UPCOMING_PRODUCTS_CACHE);
    }

    $Qupcoming->execute();

    if ($Qupcoming->numberOfRows() > 0) {
      
      $this->_content = '';
      while ($Qupcoming->next()) {
        $lC_Product = new lC_Product($Qupcoming->valueInt('products_id'));

        $this->_content .= '<div class="content-upcoming-products-container">' . "\n" . 
                           '<div class="content-upcoming-products-name">' . lc_link_object(lc_href_link(FILENAME_PRODUCTS, $lC_Product->getKeyword()), $lC_Product->getTitle()) . '</div>' . "\n";
        
        if ($lC_Product->hasImage()) {
          $this->_content .= '<div class="content-upcoming-products-image">' . lc_link_object(lc_href_link(FILENAME_PRODUCTS, $lC_Product->getKeyword()), $lC_Image->show($lC_Product->getImage(), $lC_Product->getTitle(), 'class="content-upcoming-products-image-src"', 'small')) . '</div>' . "\n";
        }
        $this->_content .= '<div class="content-upcoming-products-price">' . $lC_Product->getPriceFormated(true) . '</div>' . "\n" . 
                           '<div class="content-upcoming-products-date">' . lC_DateTime::getShort($Qupcoming->value('date_expected')) . '</div>' . "\n" .
                           '</div>' . "\n";
      }
    }

    $Qupcoming->freeResult();
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

    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Maximum Entries To Display', 'MODULE_CONTENT_UPCOMING_PRODUCTS_MAX_DISPLAY', '10', 'Maximum number of upcoming products to display', '6', '0', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Cache Contents', 'MODULE_CONTENT_UPCOMING_PRODUCTS_CACHE', '1440', 'Number of minutes to keep the contents cached (0 = no cache)', '6', '0', now())");
  }
 /*
  * Return the module keys
  *
  * @access public
  * @return array
  */
  public function getKeys() {
    if (!isset($this->_keys)) {
      $this->_keys = array('MODULE_CONTENT_UPCOMING_PRODUCTS_MAX_DISPLAY', 'MODULE_CONTENT_UPCOMING_PRODUCTS_CACHE');
    }

    return $this->_keys;
  }
}
?>