<?php
/*
  $Id: countries.php v1.0 2011-11-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html

  @function The lC_Application_Countries class manages the countries GUI
*/
require('includes/applications/countries/classes/countries.php');

class lC_Application_Countries extends lC_Template_Admin {
 /*
  * Protected variables
  */
  protected $_module = 'countries',
            $_page_title,
            $_page_contents = 'main.php';
 /*
  * Class constructor
  */
  public function __construct() {
    global $lC_Language;

    $this->_page_title = $lC_Language->get('heading_title');

    if ( !empty($_GET[$this->_module]) && is_numeric($_GET[$this->_module]) ) {
      $this->_page_contents = 'zones.php';
      $this->_page_title = lC_Address::getCountryName($_GET[$this->_module]);
    }
  }
}
?>