<?php
/*
  $Id: administrators.php v1.0 2011-11-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html

  @function The lC_Application_Administrators class manages the administrators GUI
*/
require('includes/applications/administrators/classes/administrators.php');

class lC_Application_Administrators extends lC_Template_Admin {
 /*
  * Protected variables
  */
  protected $_module = 'administrators',
            $_page_title,
            $_page_contents,
            $_page_name;
 /*
  * Class constructor
  */
  public function __construct() {
    global $lC_Language;

    if ( !isset($_GET['set']) ) {
      $_GET['set'] = 'members';
    }

    $action = (isset($_GET['gid']) && !empty($_GET['gid'])) ? 'edit' : 'insert';

    switch ( $_GET['set'] ) {
      case 'groups':
        $this->_page_title = $lC_Language->get('heading_title_groups');
        $this->_page_name = 'groups';
        $this->_page_contents = 'groups.php';

        if (isset($_GET['process'])) {
          $result = array();
          switch (strtolower($_GET['process'])) {
            case 'edit' :
              $result = (lC_Administrators_Admin::saveGroup($_GET['gid'], $_POST));
              break;

            default :
              $result = (lC_Administrators_Admin::saveGroup(NULL, $_POST));
          }
          if ($result['rpcStatus'] != 1) {
            die('rpcStatus: ' . $result['rpcStatus']);
          }
          lc_redirect_admin(lc_href_link_admin(FILENAME_DEFAULT, $this->_module . '&set=groups'));
        }
        break;

      case 'access':
        if ($action == 'insert' && (int)$_SESSION['admin']['access'][$this->_module] < 2) lc_redirect(lc_href_link_admin(FILENAME_DEFAULT, 'error_pages&set=no_access'));
        if ($action != 'insert' && (int)$_SESSION['admin']['access'][$this->_module] < 3) lc_redirect(lc_href_link_admin(FILENAME_DEFAULT, 'error_pages&set=no_access'));
        $this->_page_title = ($action == 'insert') ? $lC_Language->get('heading_title_new_group') : $lC_Language->get('heading_title_edit_group');
        $this->_page_name = 'access';
        $this->_page_contents = 'access.php';
        break;

      case 'members':
      default:
        $this->_page_title = $lC_Language->get('heading_title');
        $this->_page_name = 'members';
        $this->_page_contents = 'main.php';
        break;
    }
  }
}
?>