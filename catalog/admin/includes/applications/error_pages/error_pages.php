<?php
/*
  $Id: error_pages.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html

  @function The lC_Application_Error_pages class manages the error_pages GUI
*/
class lC_Application_Error_pages extends lC_Template_Admin {
 /*
  * Protected variables
  */
  protected $_module = 'error_pages',
            $_page_title,
            $_page_contents,
            $_page_name;
 /*
  * Class constructor
  */
  public function __construct() {
    global $lC_Language;

    if ( !isset($_GET['set']) ) {
      $_GET['set'] = 'no_access';
    }

    switch ( $_GET['set'] ) {
      case 'no_access':
      default:
        $this->_page_title = $lC_Language->get('heading_title_no_access');
        $this->_page_name = 'no_access';
        $this->_page_contents = 'main.php';
    }
  }
}
?>