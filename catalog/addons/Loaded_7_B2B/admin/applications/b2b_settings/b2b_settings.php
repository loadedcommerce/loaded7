<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce, LLC
  @copyright  Copyright 2003-2013 Loaded Commerce Development Team
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: b2b_settings.php v1.0 2013-08-08 datazen $
*/
require(DIR_FS_CATALOG . 'addons/Loaded_7_B2B/admin/applications/b2b_settings/classes/b2b_settings.php');

class lC_Application_B2b_settings extends lC_Template_Admin {
 /*
  * Protected variables
  */
  protected $_module = 'b2b_settings',
            $_page_title,
            $_page_contents = 'main.php';
 /*
  * Class constructor
  */
  function __construct() {
    global $lC_Language;

    $this->_page_title = $lC_Language->get('heading_title');
    
    $action = (isset($_GET['action']) && $_GET['action'] != NULL) ? $_GET['action'] : NULL;
    
    switch ($action) {
      case 'save' :
      
        $data = array('allow_self_register' => (isset($_POST['allow_self_register']) ? $_POST['allow_self_register'] : null),
                      'guest_catalog_access' => (isset($_POST['guest_catalog_access']) ? $_POST['guest_catalog_access'] : null));
                        
        lC_B2b_settings_Admin::save($data);
                        
        lc_redirect_admin(lc_href_link_admin(FILENAME_DEFAULT, $this->_module));
        break;
    }
  }
}
?>