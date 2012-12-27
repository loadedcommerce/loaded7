<?php
/*
  $Id: image_groups.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
 
  @function The lC_Application_Image_groups class manages image groups GUI
*/
require('includes/applications/image_groups/classes/image_groups.php');

class lC_Application_Image_groups extends lC_Template_Admin {
 /*
  * Protected variables
  */
  protected $_module = 'image_groups',
            $_page_title,
            $_page_contents = 'main.php';
 /*
  * Class constructor
  */
  function __construct() {
    global $lC_Language;

    $this->_page_title = $lC_Language->get('heading_title');
  }
}
?>