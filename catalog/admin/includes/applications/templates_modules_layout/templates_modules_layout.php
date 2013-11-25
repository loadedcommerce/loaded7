<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: templates_modules_layout.php v1.0 2013-08-08 datazen $
*/
global $lC_Vqmod;

require_once($lC_Vqmod->modCheck('includes/applications/templates_modules_layout/classes/templates_modules_layout.php'));

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
  public function __construct() {
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