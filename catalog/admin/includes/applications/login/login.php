<?php
/*
  $Id: login.php v1.0 2011-11-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html

  @function The lC_Application_Login class manages the login GUI
*/
class lC_Application_Login extends lC_Template_Admin {
 /*
  * Protected variables
  */
  protected $_module = 'login',
            $_page_title,
            $_page_contents = 'main.php';
 /*
  * Class constructor
  */
  public function __construct() {
    global $lC_Language, $lC_Api;

    $this->_page_title = $lC_Language->get('heading_title');
    $this->_has_wrapper = false;
    $this->_has_header = false;
    $this->_has_footer = false;
    
    $lC_Api->healthCheck();
  }
}
?>
