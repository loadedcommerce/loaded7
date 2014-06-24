<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: backup.php v1.0 2013-08-08 datazen $
*/
  global $lC_Vqmod;

  require_once($lC_Vqmod->modCheck('includes/applications/backup/classes/backup.php'));

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

          case 'restoreLocal':
            if (lC_Backup_Admin::restore()) {
              $_SESSION['restoreLocal'] = true;
              return true;
            } else {
              return false;
            }
            break;
        }
      }
    }
  }
?>