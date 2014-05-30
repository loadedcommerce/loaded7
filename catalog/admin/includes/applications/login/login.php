<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: login.php v1.0 2013-08-08 datazen $
*/
global $lC_Vqmod;

include_once($lC_Vqmod->modCheck('includes/applications/login/classes/login.php')); 

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
    global $lC_Language, $lC_Api, $ioncube_check;

    $this->_page_title = $lC_Language->get('heading_title');
    $this->_has_wrapper = false;
    $this->_has_header = false;
    $this->_has_footer = false;
    
    // added for ioncube check
    $ioncube_check = utility::ioncubeCheck();
  }
}
?>