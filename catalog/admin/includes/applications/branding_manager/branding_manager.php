<?php
/*
  $Id: branding_manager.php v1.0 2011-11-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
 
  @function The lC_Application_Branding_manager class manages the store branding data
*/
require('includes/applications/branding_manager/classes/branding_manager.php');

class lC_Application_Branding_manager extends lC_Template_Admin {
 /*
  * Protected variables
  */
  protected $_module = 'branding_manager',
            $_page_title,
            $_page_contents = 'edit.php';
 /*
  * Class constructor
  */
  function __construct() {
    global $lC_Language;

    $this->_page_title = $lC_Language->get('heading_title');
  }
}
?>