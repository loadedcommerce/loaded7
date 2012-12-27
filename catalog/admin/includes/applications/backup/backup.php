<?php
/*
  $Id: backup.php v1.0 2011-11-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html

  @function The lC_Application_Banner_manager class manages the banners GUI
*/
require('includes/applications/backup/classes/backup.php');

class lC_Application_Backup extends lC_Template_Admin {
 /*
  * Protected variables
  */
  protected $_module = 'backup',
            $_page_title,
            $_page_contents = 'main.php';
 /*
  * Class constructor
  */
  function __construct() {
    global $lC_Language;

    $this->_page_title = $lC_Language->get('heading_title');

    if ( !isset($_GET['action']) ) {
      $_GET['action'] = '';
    }

    if ( !empty($_GET['action']) ) {
      switch ( $_GET['action'] ) {

        case 'backup':
          if (lC_Backup_Admin::backup($_GET['compression'], (isset($_GET['download_only']) && ($_GET['download_only'] == 'yes') ? true : false))) {
            return true;
          } else {
            return false;
          }
          break;

        case 'download':
          $filename = basename($_GET['file']);
          $extension = substr($filename, -3);

          if ( ( $extension == 'zip' ) || ( $extension == '.gz' ) || ( $extension == 'sql' ) ) {
            if ( file_exists(DIR_FS_BACKUP . $filename) ) {
              if ( $fp = fopen(DIR_FS_BACKUP . $filename, 'rb') ) {
                $buffer = fread($fp, filesize(DIR_FS_BACKUP . $filename));
                fclose($fp);

                header('Content-type: application/x-octet-stream');
                header('Content-disposition: attachment; filename=' . $filename);

                echo $buffer;

                exit;
              }
            }
          }
          break;
      }
    }
  }
}
?>