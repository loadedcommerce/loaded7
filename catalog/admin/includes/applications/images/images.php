<?php
/*
  $Id: images.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html

  @function The lC_Application_Images class manages the image manager GUI
*/
class lC_Application_Images extends lC_Template_Admin {
 /*
  * Protected variables
  */
  protected $_module = 'images',
            $_page_title,
            $_page_contents = 'main.php';
 /*
  * Class constructor
  */
  function __construct() {
    global $lC_Language;

    $this->_page_title = $lC_Language->get('heading_title');

    if ( !isset($_GET['module']) ) {
      $_GET['module'] = '';
    }

    if ( !empty($_GET['module']) && !file_exists('includes/modules/image/' . $_GET['module'] . '.php') ) {
      $_GET['module'] = '';
    }

    if ( empty($_GET['module']) ) {
      $this->_page_contents = 'main.php';
    }
  }
}
?>