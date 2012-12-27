<?php
/*
  $Id: templates_modules_layout.php v1.0 2011-11-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html

  @function The lC_Application_Modules_addons class manages the addons GUI
*/
require_once('includes/applications/templates_modules_layout/classes/templates_modules_layout.php');

class lC_Application_Templates_modules_layout extends lC_Template_Admin {
 /*
  * Protected variables
  */
  protected $_module = 'templates_modules_layout',
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