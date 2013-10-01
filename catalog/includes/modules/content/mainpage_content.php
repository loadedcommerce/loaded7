<?php
/*
  $Id: mainpage_content.php v1.0 2011-11-04 kiran $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
class lC_Content_mainpage_content extends lC_Modules {
 /* 
  * Public variables 
  */  
  public $_title,
         $_code = 'mainpage_content',
         $_author_name = 'Loaded Commerce',
         $_author_www = 'http://www.loadedcommerce.com',
         $_group = 'content';
 /* 
  * Class constructor 
  */
  public function __construct() {
    global $lC_Language;           

    if (function_exists($lC_Language->injectDefinitions)) $lC_Language->injectDefinitions('modules/' . $_GET['set'] . '/' . $this->_code . '.xml');      
    
    $this->_title = $lC_Language->get('mainpage_content_title');
  }
 /*
  * Returns the also puchased HTML
  *
  * @access public
  * @return string
  */
  public function initialize() {
    if (defined('MODULE_CONTENT_HOMEPAGE_HTML_CONTENT')) {
      $this->_content = MODULE_CONTENT_HOMEPAGE_HTML_CONTENT;
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

    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, use_function, set_function) values ('Home Page Content', 'MODULE_CONTENT_HOMEPAGE_HTML_CONTENT', '<h2>Welcome to Loaded Commerce!</h2><br>The Most Complete Open Source Shopping Cart Software.', 'The text area allows you to add / edit home page HTML content block.', '6', '0', now(),  'lc_cfg_set_textarea_field', 'lc_cfg_set_textarea_field')");

  }
 /*
  * Return the module keys
  *
  * @access public
  * @return array
  */
  public function getKeys() {
    if (!isset($this->_keys)) {
      $this->_keys = array('MODULE_CONTENT_HOMEPAGE_HTML_CONTENT');
    }

    return $this->_keys;
  }
}
?>