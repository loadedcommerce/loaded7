<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: error_pages.php v1.0 2013-08-08 datazen $
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