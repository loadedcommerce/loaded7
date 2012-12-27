<?php
/*
  $Id: templates_modules.php v1.0 2011-11-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html

  @function The lC_Application_Templates_modules class manages the templates modules GUI
*/
class lC_Application_Templates_modules extends lC_Template_Admin {
 /*
  * Protected variables
  */
  protected $_module = 'templates_modules',
            $_page_title,
            $_page_contents = 'main.php';
 /*
  * Class constructor
  */
  function __construct() {
    global $lC_Language;
    
    if ( !isset($_GET['set']) ) {
      $_GET['set'] = '';
    }

    $lC_DirectoryListing = new lC_DirectoryListing('../includes/modules/' . $_GET['set']);
    $lC_DirectoryListing->setIncludeDirectories(false);      

    $_SESSION['modules_location'] = $lC_DirectoryListing->getDirectory();
          
    switch ( $_GET['set'] ) {
      case 'content':
        $this->_page_title = $lC_Language->get('heading_title_content');

        break;

      case 'boxes':
      default:
        $_GET['set'] = 'boxes';
        $this->_page_title = $lC_Language->get('heading_title_boxes');

        break;
    }
  }
}
?>