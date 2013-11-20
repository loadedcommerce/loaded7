<?php
/**
  @package    catalog::modules::content
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: product_on_homepage.php v1.0 2013-08-08 datazen $
*/
require_once($lC_Vqmod->modCheck(DIR_FS_CATALOG . 'includes/classes/transport.php'));
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
    global $lC_Product;
    
    if (defined('MODULE_CONTENT_PRODUCT_ON_HOMEPAGE')) {
      $lC_Product = new lC_Product(MODULE_CONTENT_PRODUCT_ON_HOMEPAGE);

      $product_url = HTTP_SERVER . DIR_WS_HTTP_CATALOG . 'products.php?' . $lC_Product->getKeyword();
      $resultHTML = transport::getResponse(array('url' => $product_url, 'method' => 'get'));
      
      $content = substr($resultHTML, strpos($resultHTML, '<!--content/products/info.php start'));
      $this->_content = substr($content, strpos($content, '<!--content/products/info.php start'), strpos($content, 'content/products/info.php end-->')+32);
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