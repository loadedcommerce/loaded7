<?php
/*
  $Id: file_manager.php v1.0 2011-11-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html

  @function The lC_File_manager_Admin class manages the file manager
*/
class lC_File_manager_Admin {
 /*
  * Returns the file manager datatable data for listings
  *
  * @access public
  * @return array
  */
  public static function getAll() { 
    global $lC_Language;

    if (!defined('LC_ADMIN_FILE_MANAGER_ROOT_PATH')) define('LC_ADMIN_FILE_MANAGER_ROOT_PATH', substr(DIR_FS_CATALOG, 0, -1)); 

    $media = $_GET['media'];
    
    $goto_array = array(array('id' => '',
                              'text' => $lC_Language->get('top_level')));
    if ( $_SESSION['fm_directory'] != LC_ADMIN_FILE_MANAGER_ROOT_PATH ) {
      $path_array = explode('/', substr($_SESSION['fm_directory'], strlen(LC_ADMIN_FILE_MANAGER_ROOT_PATH)+1));
      foreach ( $path_array as $value ) {
        if ( sizeof($goto_array) < 2 ) {
          $goto_array[] = array('id' => $value,
                                'text' => $value);
        } else {
          $parent = end($goto_array);
          $goto_array[] = array('id' => $parent['id'] . '/' . $value,
                                'text' => $parent['id'] . '/' . $value);
        }
      }
    }
    $lC_DirectoryListing = new lC_DirectoryListing($_SESSION['fm_directory']);
    $lC_DirectoryListing->setStats(true);

    $result = array('aaData' => array());

    if ( $_SESSION['fm_directory'] != LC_ADMIN_FILE_MANAGER_ROOT_PATH ) {
      $files = '<td>' . lc_link_object(lc_href_link_admin(FILENAME_DEFAULT, 'file_manager&goto=' . $goto_array[sizeof($goto_array)-2]['id']), '<span class="icon-up-fat icon-blue">&nbsp;' . $lC_Language->get('parent_level')) . '</td>';
      $result['aaData'][] = array("$files", "", "", "", "", "", "", "");  
    }

    $cnt = 0;
    foreach ( $lC_DirectoryListing->getFiles() as $file ) {
      $file_owner = posix_getpwuid($file['user_id']);
      $group_owner = posix_getgrgid($file['group_id']);

      if ( $file['is_directory'] === true ) {
        $entry_url = lc_href_link_admin(FILENAME_DEFAULT, 'file_manager&directory=' . $file['name']);
        $files = '<td>' . lc_link_object($entry_url, '<span class="icon-folder icon-orange">&nbsp;' . $file['name']) . '</td>';
      } else {
        $entry_url = lc_href_link_admin(FILENAME_DEFAULT, 'file_manager&entry=' . $file['name'] . '&action=save');
        $files = '<td><a href="javascript://" onclick="editEntry(\'' . $file['name'] . '\')">' . '<span class="icon-page-list icon-blue">&nbsp;' . $file['name'] . '</a></td>';
      }
      $size = '<td>' . number_format($file['size']) . '</td>';
      $perms = '<td>' . lc_get_file_permissions($file['permissions']) . '</td>';
      $user = '<td>' . $file_owner['name'] . '</td>';
      $group = '<td>' . $group_owner['name'] . '</td>';
      $write = '<td>' . is_writable($lC_DirectoryListing->getDirectory() . '/' . $file['name']) ? '<span class="icon-tick icon-green">' : '<span class="icon-cross icon-red">' . '</td>';
      $last = '<td>' . lC_DateTime::getShort(@date('Y-m-d H:i:s', $file['last_modified']), true) . '</td>';

      if ( $file['is_directory'] === false ) {
        $action_links = '<a href="' . ((int)($_SESSION['admin']['access']['file_manager'] < 3) ? '#' : 'javascript://" onclick="editEntry(\'' . $file['name'] . '\')') . '" class="button icon-pencil' . ((int)($_SESSION['admin']['access']['file_manager'] < 3) ? ' disabled' : NULL) . '">' . (($media === 'mobile-portrait' || $media === 'mobile-landscape') ? NULL : $lC_Language->get('icon_edit')) . '</a>' .
                        '<a href="' . ((int)($_SESSION['admin']['access']['file_manager'] < 2) ? '#' : lc_href_link_admin(FILENAME_DEFAULT, 'file_manager&entry=' . $file['name'] . '&action=download')) . '" class="button icon-download with-tooltip' . ((int)($_SESSION['admin']['access']['file_manager'] < 2) ? ' disabled' : NULL) . '" title="' .  $lC_Language->get('icon_download') . '"></a>' . 
                        '<a href="' . ((int)($_SESSION['admin']['access']['file_manager'] < 4) ? '#' : 'javascript://" onclick="deleteEntry(\'' . $file['name'] . '\', \'' . urlencode($file['name']) . '\')"') . '" class="button icon-trash with-tooltip' . ((int)($_SESSION['admin']['access']['file_manager'] < 4) ? ' disabled' : NULL) . '" title="' . $lC_Language->get('icon_delete') . '"></a>';
      } else {
        //$action_links = lc_image('images/pixel_trans.gif') . '&nbsp;' .  lc_image('images/pixel_trans.gif') . '&nbsp;';
        $action_links = '<a href="' . ((int)($_SESSION['admin']['access']['file_manager'] < 4) ? '#' : 'javascript://" onclick="deleteEntry(\'' . $file['name'] . '\', \'' . urlencode($file['name']) . '\')"') . '" class="button icon-trash' . ((int)($_SESSION['admin']['access']['file_manager'] < 4) ? ' disabled' : NULL) . '" title="' . $lC_Language->get('icon_delete') . '"></a>';
      }

      $action = '<td class="align-right vertical-center"><span class="button-group compact">
                   ' . $action_links . '
                 </span></td>';   
      $result['aaData'][] = array("$files", "$size", "$perms", "$user", "$group", "$write", "$last", "$action");      
      $cnt++;
    }
    $result['total'] = $cnt;

    return $result; 
  }
 /*
  * Return the file information
  *
  * @param string $id The file name
  * @access public
  * @return array
  */
  public static function getEntry($id) {
    global $lC_Language; 
    
    $result = array();
    $contents = '';
    $target = $_SESSION['fm_directory'] . '/' . basename($id);
    if ( !is_writeable($target) ) {
      $result['error'] = true;
    } else {
      $contents = @file_get_contents($target);     
      $result['contents'] = $contents;
    }
    $result['target'] = $_SESSION['fm_directory'] . '/';
     
    return $result;
  }
 /*
  * Check that the path is writeable
  *
  * @param string $path The directory path
  * @access public
  * @return boolean
  */  
  public static function isWriteable($path) {
    if ( is_writeable($path) ) {
      return true;
    }
    return false;
  }    
 /*
  * Create a directory
  *
  * @param string $name The directory name
  * @param string $path The directory path
  * @access public
  * @return boolean
  */ 
  public static function createDirectory($name, $path) {
    if ( is_writeable($path) ) {
      $new_directory = $path . '/' . basename($name);

      if ( !is_dir($new_directory) ) {
        if ( mkdir($new_directory, 0777) ) {
          return true;
        }
      }
    }

    return false;
  }
 /*
  * Save the file
  *
  * @param string $filename The file name
  * @param string $contents The file contents
  * @param string $directory The directory path
  * @access public
  * @return boolean
  */ 
  public static function saveFile($filename, $contents, $directory) {
    if ( $fp = fopen($directory . '/' . $filename, 'w+') ) {
      fputs($fp, $contents);
      fclose($fp);

      return true;
    }

    return false;
  }
 /*
  * Upload a file
  *
  * @param string $file The file name
  * @param string $directory The directory path
  * @access public
  * @return boolean
  */ 
  public static function storeFileUpload($file, $directory) {
    if ( is_writeable($directory) ) {
      $upload = new upload($file, $directory);

      if ( $upload->exists() && $upload->parse() && $upload->save() ) {
        return true;
      }
    }

    return false;
  }
 /*
  * Delete a file or directory
  *
  * @param string $entry The file name or directory name
  * @param string $path The directory path
  * @access public
  * @return boolean
  */ 
  public static function delete($entry, $directory) {
    $target = $directory . '/' . basename($entry);

    if ( is_writeable($target) ) {
      lc_remove($target);

      return true;
    }

    return false;
  }
}
?>