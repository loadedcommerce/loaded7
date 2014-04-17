<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: file_manager.php v1.0 2013-08-08 datazen $
*/
global $lC_Vqmod;

require($lC_Vqmod->modCheck('includes/applications/file_manager/classes/file_manager.php'));

define('LC_ADMIN_FILE_MANAGER_ROOT_PATH', realpath('../'));

class lC_Application_File_manager extends lC_Template_Admin {
 /*
  * Protected variables
  */
  protected $_module = 'file_manager',
            $_page_title,
            $_page_contents = 'main.php';
 /*
  * Class constructor
  */
  function __construct() {
    global $lC_Language, $breadcrumb_string;

    $this->_page_title = $lC_Language->get('heading_title');

    if (!defined('LC_ADMIN_FILE_MANAGER_ROOT_PATH')) define('LC_ADMIN_FILE_MANAGER_ROOT_PATH', substr(DIR_FS_CATALOG, 0, -1));

    if ( !isset($_SESSION['fm_directory']) ) {
      $_SESSION['fm_directory'] = LC_ADMIN_FILE_MANAGER_ROOT_PATH;
    }

    if ( isset($_GET['directory']) ) {
      $_SESSION['fm_directory'] .= '/' . $_GET['directory'];
    } elseif ( isset($_GET['goto']) ) {
      $_SESSION['fm_directory'] = LC_ADMIN_FILE_MANAGER_ROOT_PATH . '/' . urldecode($_GET['goto']);
    }
    $_SESSION['fm_directory'] = realpath($_SESSION['fm_directory']);
    
    if ( ( substr($_SESSION['fm_directory'], 0, strlen(LC_ADMIN_FILE_MANAGER_ROOT_PATH)) != LC_ADMIN_FILE_MANAGER_ROOT_PATH ) || !is_dir($_SESSION['fm_directory']) ) {
      $_SESSION['fm_directory'] = LC_ADMIN_FILE_MANAGER_ROOT_PATH;
    }

    if ( !isset($_GET['action']) ) {
      $_GET['action'] = '';
    }

    if ( !empty($_GET['action']) ) {
      switch ( $_GET['action'] ) {
       /*
        * Upload a file
        *
        * @access public
        * @return boolean
        */
        case 'upload':
          $error = false; 
          for ( $i = 0; $i < sizeof($_FILES['fmFile']['name']); $i++ ) {
            $_FILES['file_' . $i] = array('name' => $_FILES['fmFile']['name'][$i],
                                               'type' => $_FILES['fmFile']['type'][$i],
                                               'tmp_name' => $_FILES['fmFile']['tmp_name'][$i],
                                               'error' => $_FILES['fmFile']['error'][$i],
                                               'size' => $_FILES['fmFile']['size'][$i]);
          }
          unset($_FILES['fmFile']);

          for ( $i = 0; $i < sizeof($_FILES); $i++ ) {
              if ( is_uploaded_file($_FILES['file_' . $i]['tmp_name']) ) {
                if ( !lC_File_manager_Admin::storeFileUpload('file_' . $i, $_SESSION['fm_directory']) ) {
                  $error = true;
                  break;
                }
              }             
          }
          if ( $error === false ) {
          } else {
            $_SESSION['error'] = true;
            $_SESSION['errmsg'] = $lC_Language->get('new_directory_error_not_writable');
          }
          break;
       /*
        * Download a file
        *
        * @access public
        * @return file
        */
        case 'download':
          $filename = basename($_GET['entry']);
          if ( file_exists($_SESSION['fm_directory'] . '/' . $filename) ) {
            header('Content-type: application/x-octet-stream');
            header('Content-disposition: attachment; filename=' . urldecode($filename));
            readfile($_SESSION['fm_directory'] . '/' . $filename);
            exit;
          }
          $_SESSION['error'] = true;
          $_SESSION['errmsg'] = $lC_Language->get('ms_error_download_link_invalid');
          break;
      }
    }
    
    // setup the breadcrumb
    $breadcrumb_array = array(lc_link_object(lc_href_link_admin(FILENAME_DEFAULT, $this->_module . '&goto='), $lC_Language->get('text_top')));
    $path = explode("/", substr(str_replace(LC_ADMIN_FILE_MANAGER_ROOT_PATH, '', $_SESSION['fm_directory']), 1));
    
    foreach ($path as $key => $value) {
      $goto_path .= $value . '/';
      $breadcrumb_array[] = lc_link_object(lc_href_link_admin(FILENAME_DEFAULT, $this->_module . '&goto=' . $goto_path), $value);
    }
    
    // build the breadcrumb html string 
    $breadcrumb_string = '<ul class="fm-breadcrumb">';
    foreach ($breadcrumb_array as $key => $value) {
      $breadcrumb_string .= '<li>' . $value . '</li>';
    }  
    $breadcrumb_string .= '</ul>';
  }
}
?>