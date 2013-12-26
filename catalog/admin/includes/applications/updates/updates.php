<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: updates.php v1.0 2013-08-08 datazen $
*/
global $lC_Vqmod;

require($lC_Vqmod->modCheck('includes/applications/updates/classes/updates.php'));

class lC_Application_Updates extends lC_Template_Admin {
  /*
  * Protected variables
  */
  protected $_module = 'updates',
            $_page_title,
            $_page_contents = 'main.php';
  /*
  * Class constructor
  */
  public function __construct() {
    global $lC_Language;

    $this->_page_title = $lC_Language->get('heading_title');
  }
}
?>