<?php
/*
  $Id: modules_shipping.php v1.0 2011-11-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html

  @function The lC_Application_Modules_shipping class manages the shipping modules GUI
*/
class lC_Application_Modules_shipping extends lC_Template_Admin {
  /*
  * Protected variables
  */
  protected $_module = 'modules_shipping',
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