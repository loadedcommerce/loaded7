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
ini_set('error_reporting', 0);

global $lC_Vqmod;

require_once($lC_Vqmod->modCheck('../includes/classes/transport.php'));  
require_once($lC_Vqmod->modCheck('includes/applications/backup/classes/backup.php')); 

class lC_Updates_Admin { 
  
  protected static $_to_version;  
  
  /**
  * Check to see if there are updates availablw
  *  
  * @access public      
  * @return boolean
  */ 
  public static function hasUpdatesAvailable() {
    global $lC_Database, $lC_Language;
    
    $lC_Language->loadIniFile('updates.php');

    $result = array();
    $available = self::getAvailablePackages();
    
    $result['hasUpdates'] = ($available['total'] > 0);
    $lastChecked = date("Y-m-d H:i:s");
    $result['lastChecked'] = $lC_Language->get('text_last_checked') . ' ' . lC_DateTime::getLong($lastChecked, TRUE);
    $result['total'] = $available['total'];
    
    if ($result['hasUpdates']) {
      $to_version = 0;
      $to_version_date = '';
      foreach ($available['entries'] as $k => $v) {
        if (version_compare($to_version, $v['version'], '<')) { 
          $to_version = $v['version'];
          $to_version_date = $v['date'];
        }
      }
    } else {
      $to_version = utility::getVersion();
      $to_version_date = utility::getVersionDate();
    } 
    $result['toVersion'] = $to_version;   
    $result['toVersionDate'] = $to_version_date;   
    
    // update last checked value
    $lC_Database->startTransaction();

    if (!defined('UPDATE_LAST_CHECKED')) {
      $Qupdate = $lC_Database->query('insert into :table_configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, last_modified) values (:configuration_title, :configuration_key, :configuration_value, :configuration_description, :configuration_group_id, :last_modified)');
      $Qupdate->bindValue(':configuration_title', 'Update Last Checked');
      $Qupdate->bindValue(':configuration_key', 'UPDATE_LAST_CHECKED');
      $Qupdate->bindValue(':configuration_value', 'See Last Modified');
      $Qupdate->bindValue(':configuration_description', 'Update Last Checked');      
      $Qupdate->bindValue(':configuration_group_id', '6');      
    } else {
      $Qupdate = $lC_Database->query("update :table_configuration set last_modified = :last_modified where configuration_key = 'UPDATE_LAST_CHECKED'");
    }    
    $Qupdate->bindTable(':table_configuration', TABLE_CONFIGURATION);
    $Qupdate->bindValue(':last_modified', $lastChecked);  
    $Qupdate->setLogging($_SESSION['module']); 
    $Qupdate->execute();  

    if (!$lC_Database->isError()) {
      $lC_Database->commitTransaction();
      $result['rpcStatus'] = 1;
    } else {
      $lC_Database->rollbackTransaction();
      $result['rpcStatus'] = -1;
    }
    
    return $result;
  }  
  /**
  * Find available update packages based on $search 
  *  
  * @param array  $search The version to search for
  * @access public      
  * @return array
  */  
  public static function findAvailablePackages($search) {
    $result = self::getAvailablePackages();

    foreach ( $result['entries'] as $k => $v ) {
      if ( strpos($v['version'], $search) === false ) {
        unset($result['entries'][$k]);
      }
    }

    $result['total'] = count($result['entries']);

    return $result;    
  }
  /**
  * Get all available update packages
  *  
  * @access public      
  * @return array
  */ 
  public static function getAvailablePackages() {
    global $lC_Api;
      
    $result = array('entries' => array());
    $versions = transport::getResponse(array('url' => 'https://api.loadedcommerce.com/1_0/updates/available/?ref=' . $_SERVER['SCRIPT_FILENAME'], 'method' => 'get'));
    $versions_array = utility::xml2arr($versions); 

    $counter = 0;
    foreach ( $versions_array['data'] as $l => $v ) {
      if ( version_compare(utility::getVersion(), $v['version'], '<') ) {
        $result['entries'][] = array('key' => $counter,
                                     'version' => $v['version'],
                                     'date' => lC_DateTime::getShort(lC_DateTime::fromUnixTimestamp(lC_DateTime::getTimestamp($v['dateCreated'], 'Ymd'))),
                                     'announcement' => $v['newsLink'],
                                     'update_package' => (isset($v['pharLink']) ? $v['pharLink'] . '?ref=' . urlencode($_SERVER['SCRIPT_FILENAME']) : null));
        $counter++;
      }
    }

    @usort($result['entries'], function ($a, $b) {
      return version_compare($a['version'], $b['version'], '>');
    });

    $result['total'] = count($result['entries']);
    
    return $result;
  }   
  /**
  * Get available package info
  *  
  * @access public      
  * @return mixed
  */  
  public static function getAvailablePackageInfo($key = null) {
    $versions = self::getAvailablePackages();

    if ( !empty($versions['entries']) ) {
      if ( !empty($key) && isset($versions['entries'][0][$key]) ) {
        return $versions['entries'][0][$key];
      } else {
        return $versions['entries'][0];
      }
    }

    return false;
  }
  /**
  * Check if a package exists
  *  
  * @access public      
  * @return boolean
  */   
  public static function packageExists($version) {
    $versions = self::getAvailablePackages();

    foreach ( $versions['entries'] as $v ) {
      if ( $v['version'] == $version ) {
        return true;
      }
    }

    return false;
  } 
  /**
  * Download the package
  *  
  * @access public      
  * @return mixed
  */
  public static function downloadPackage($version = null, $type = null) {

    if ( empty($version) ) {
      $link = self::getAvailablePackageInfo('update_package');
    } else {
      $versions = self::getAvailablePackages();

      foreach ( $versions['entries'] as $v ) {
        if ( $v['version'] == $version ) {
          $link = $v['update_package'];
          break;
        }
      }
    }
    
    if ($link == null) $link = 'https://api.loadedcommerce.com/1_0/get/' . str_replace(".", "", $version) . '?ref=' . urlencode($_SERVER['SCRIPT_FILENAME']);
    if ($type != null) $link .= '&type=' . $type;
    
    $response = file_get_contents($link);

    return file_put_contents(DIR_FS_WORK . 'updates/update.phar', $response);
  } 
  /**
  * Does the local package exist?
  *  
  * @access public      
  * @return booolean
  */  
  public static function localPackageExists() {
    return file_exists(DIR_FS_WORK . 'updates/update.phar');
  } 
  /**
  * Read the Phar package info (metadata)
  *  
  * @param array  $key  The version to read
  * @access public      
  * @return mixed
  */ 
  public static function getPackageInfo($key = null) {
    $phar_can_open = true;

    try {
      $phar = new Phar(DIR_FS_WORK . 'updates/update.phar', 0);
    } catch ( Exception $e ) {
      $phar_can_open = false;

      trigger_error($e->getMessage());
    }

    if ( $phar_can_open === true ) {
      $result = $phar->getMetadata();

      if ( isset($key) ) {
        $result = $result[$key] ?: null;
      }

      return $result;
    }

    return false;
  } 
 /**
  * Find package contents in the Phar package
  *  
  * @param array  $search The search parameter
  * @access public      
  * @return array
  */ 
  public static function findPackageContents($search) {

    $result = self::getPackageContents();

    foreach ( $result['entries'] as $k => $v ) {
      if ( stripos($v['name'], $search) === false ) {
        unset($result['entries'][$k]);
      }
    }

    $result['total'] = count($result['entries']);

    return $result;
  }  
 /**
  * Read the Phar package contents
  *  
  * @param array  $key  The version to read
  * @access public      
  * @return mixed
  */   
  public static function getPackageContents() {

    $result = array('entries' => array());
    $phar_can_open = true;

    try {
      $phar = new Phar(DIR_FS_WORK . 'updates/update.phar');
    } catch ( Exception $e ) {
      $phar_can_open = false;

      trigger_error($e->getMessage());
    }

    if ( $phar_can_open === true ) {
      $update_pkg = array();

      foreach ( new RecursiveIteratorIterator($phar) as $iteration ) {
        if ( ($pos = strpos($iteration->getPathName(), 'update.phar')) !== false ) {
          $update_pkg[] = substr($iteration->getPathName(), $pos+12);
        }
      }

      natcasesort($update_pkg);
      
      $counter = 0;

      foreach ( $update_pkg as $file ) {
        $custom = false;
        
        // update the path with admin config value to account for a different admin/ dir
        $file = str_replace('admin/', DIR_WS_ADMIN, $file);        

        $result['entries'][] = array('key' => $counter,
                                     'name' => $file,
                                     'exists' => file_exists(realpath(DIR_FS_CATALOG) . '/' . $file),
                                     'writable' => self::isWritable(realpath(DIR_FS_CATALOG) . '/' . $file) && self::isWritable(realpath(DIR_FS_CATALOG) . '/' . dirname($file)),
                                     'custom' => $custom,
                                     'to_delete' => false);

        $counter++;
      }
    }

    $meta = $phar->getMetadata();
    
    if ( isset($meta['delete']) ) {
      $files = array();

      if (is_array($meta['delete']) && count($meta['delete']) > 0) {
        foreach ( $meta['delete'] as $file ) {
          
          // update the path with admin config value to account for a different admin/ dir
          $file = str_replace('admin/', DIR_WS_ADMIN, $file);
          
          if ( file_exists(realpath(DIR_FS_CATALOG) . '/' . $file) ) {
            if ( is_dir(realpath(DIR_FS_CATALOG) . '/' . $file) ) {
              $DL = new DirectoryListing(realpath(DIR_FS_CATALOG) . '/' . $file);
              $DL->setRecursive(true);
              $DL->setAddDirectoryToFilename(true);
              $DL->setIncludeDirectories(false);

              foreach ( $DL->getFiles() as $f ) {
                $files[] = $file . '/' . $f['name'];
              }
            } else {
              $files[] = $file;
            }
          }
        }
      }
      
      natcasesort($files);

      foreach ( $files as $d ) {
        $writable = false;
        $custom = false;

        $writable = self::isWritable(realpath(DIR_FS_CATALOG) . '/' . $d) && self::isWritable(realpath(DIR_FS_CATALOG) . '/' . dirname($d));

        $result['entries'][] = array('key' => $counter,
                                     'name' => $d,
                                     'exists' => true,
                                     'writable' => $writable,
                                     'custom' => $custom,
                                     'to_delete' => true);
        $counter++;
      }
    }
    $result['total'] = count($result['entries']);

    return $result;
  }
 /**
  * Check if the location is writeable
  *  
  * @param array  $location  The file location to check
  * @access public      
  * @return boolean
  */   
  public static function isWritable($location) {
    if ( !file_exists($location) ) {
      while ( true ) {
        $location = dirname($location);

        if ( file_exists($location) ) {
          break;
        }
      }
    }

    return is_writable($location);
  }
 /**
  * Apply the update
  *  
  * @access public      
  * @return boolean
  */    
  public static function applyPackage($pharWithPath = null, $pharType = 'addon') {
    $phar_can_open = true;

    $meta = array();
    $pro_hart = array();

    try {
      if ($pharWithPath == null) {
        $pharFile = 'update.phar';
        $phar = new Phar(DIR_FS_WORK . 'updates/' . $pharFile, 0);
        $meta = $phar->getMetadata();     
        self::$_to_version = $meta['version_to'];
        // reset the log
        if ( file_exists(DIR_FS_WORK . 'logs/update-' . self::$_to_version . '.txt') && is_writable(DIR_FS_WORK . 'logs/update-' . self::$_to_version . '.txt') ) {
          unlink(DIR_FS_WORK . 'logs/update-' . self::$_to_version . '.txt');
        }   
        self::log('##### UPDATE TO ' . self::$_to_version . ' STARTED');
      } else {
        $pharFile = end(explode('/', $pharWithPath));
        $phar = new Phar(DIR_FS_WORK . 'addons/update.phar', 0);
        $meta = $phar->getMetadata();     
        // reset the log
        $pharCode = str_replace('.phar', '', $pharFile);
        if ( file_exists(DIR_FS_WORK . 'logs/addon-' . $pharCode . '.txt') && is_writable(DIR_FS_WORK . 'logs/addon-' . $pharCode . '.txt') ) {
          unlink(DIR_FS_WORK . 'logs/addon-' . $pharCode . '.txt');
        }        
        self::log('##### ADDON INSTALL ' . $pharCode . ' STARTED', $pharCode);
      }

      // first delete files before extracting new files
      if (is_array($meta['delete']) && count($meta['delete']) > 0) {
        foreach ( $meta['delete'] as $file ) {
          $directory = realpath(DIR_FS_CATALOG) . '/';
          if ( file_exists($directory . $file) ) {
            if ( is_dir($directory . $file) ) {
              if ( rename($directory . $file, $directory . dirname($file) . '/.CU_' . basename($file)) ) {
                $pro_hart[] = array('type' => 'directory',
                                    'where' => $directory,
                                    'path' => dirname($file) . '/.CU_' . basename($file),
                                    'log' => true);
              }
            } else {
              if ( rename($directory . $file, $directory . dirname($file) . '/.CU_' . basename($file)) ) {
                $pro_hart[] = array('type' => 'file',
                                    'where' => $directory,
                                    'path' => dirname($file) . '/.CU_' . basename($file),
                                    'log' => true);
              }
            }
          }
        }
      }
      // loop through each file individually as extractTo() does not work with
      // directories (see http://bugs.php.net/bug.php?id=54289)
      foreach ( new RecursiveIteratorIterator($phar) as $iteration ) {
        if ( ($pos = strpos($iteration->getPathName(), 'update.phar')) !== false ) {
          
          $file = substr($iteration->getPathName(), $pos+12);
          
          if ($pharWithPath == null) {
            $directory = realpath(DIR_FS_CATALOG) . '/';
          } else {
            if ($pharType == 'template') {
              $directory = realpath(DIR_FS_CATALOG) . '/';
            } else {
              if (is_array($meta['api_version']) && $meta['api_version'] == '1.0') {
                $directory = realpath(DIR_FS_CATALOG) . '/';
              } else {
                $directory = realpath(DIR_FS_CATALOG) . '/addons/' . $pharCode . '/';
              }
            }
          }
          
          if ( file_exists($directory . $file) ) {
            if ( rename($directory . $file, $directory . dirname($file) . '/.CU_' . basename($file)) ) {
              $pro_hart[] = array('type' => 'file',
                                  'where' => $directory,
                                  'path' => dirname($file) . '/.CU_' . basename($file),
                                  'log' => false);
            }
          }

          if ( $phar->extractTo($directory, $file, true) ) {
            self::log('Extracted: ' . $file);
          } else {
            self::log('*** Could Not Extract: ' . $file);
          }
        }
      }

      self::log('##### CLEANUP');

      foreach ( array_reverse($pro_hart, true) as $mess ) {
        if ( $mess['type'] == 'directory' ) {
          if ( self::rmdir_r($mess['where'] . $mess['path']) ) {
            if ( $mess['log'] === true ) {
              self::log('Deleted: ' . str_replace('/.CU_', '/', $mess['path']));
            }
          } else {
            if ( $mess['log'] === true ) {
              self::log('*** Could Not Delete: ' . str_replace('/.CU_', '/', $mess['path']));
            }
          }
        } else {
          if ( unlink($mess['where'] . $mess['path']) ) {
            if ( $mess['log'] === true ) {
              self::log('Deleted: ' . str_replace('/.CU_', '/', $mess['path']));
            }
          } else {
            if ( $mess['log'] === true ) {
              self::log('*** Could Not Delete: ' . str_replace('/.CU_', '/', $mess['path']));
            }
          }
        }
      }
    } catch ( Exception $e ) {
      $phar_can_open = false;

      self::log('##### ERROR: ' . $e->getMessage());

      self::log('##### REVERTING STARTED');

      foreach ( array_reverse($pro_hart, true) as $mess ) {
        if ( $mess['type'] == 'directory' ) {
          if ( file_exists($mess['where'] . str_replace('/.CU_', '/', $mess['path'])) ) {
            self::rmdir_r($mess['where'] . str_replace('/.CU_', '/', $mess['path']));
          }
        } else {
          if ( file_exists($mess['where'] . str_replace('/.CU_', '/', $mess['path'])) ) {
            unlink($mess['where'] . str_replace('/.CU_', '/', $mess['path']));
          }
        }

        if ( file_exists($mess['where'] . $mess['path']) ) {
          rename($mess['where'] . $mess['path'], $mess['where'] . str_replace('/.CU_', '/', $mess['path']));
        }

        self::log('Reverted: ' . str_replace('/.CU_', '/', $mess['path']));
      }

      self::log('##### REVERTING COMPLETE');
      self::log('##### UPDATE TO ' . self::$_to_version . ' FAILED');

      trigger_error($e->getMessage());
      trigger_error('Please review the update log at: ' . DIR_FS_WORK . 'logs/update-' . self::$_to_version . '.txt');
    }
    
    if ($pharWithPath == null) {
      // execute run after process
      self::doRunAfter();
      
      // verify 644 permissions on PHP files on Linux systems
      if (utility::execEnabled() === true && utility::isLinux() === true) {
        try {
          exec('\find ' . DIR_FS_CATALOG . ' \( -type f -exec chmod 644 {} \; \);');
          self::log('##### UPDATED Permissions on PHP files/directories');
        } catch ( Exception $e ) {  
          self::log('*** Could NOT Set Permissions on PHP files/directories');
        } 
        self::log('##### UPDATE TO ' . self::$_to_version . ' COMPLETE');
      } else {
        try {
          self::chmod_r(DIR_FS_CATALOG);
          self::log('##### UPDATED Permissions on PHP files/directories');
        } catch ( Exception $e ) {  
          self::log('*** Could NOT Set Permissions on PHP files/directories');
        } 
        // remove the update phar
        if (file_exists(DIR_FS_WORK . 'updates/update.phar')) unlink(DIR_FS_WORK . 'updates/update.phar');          
        self::log('##### UPDATE TO ' . self::$_to_version . ' COMPLETE');      }
    } else {
      // remove the update phar
      if (file_exists(DIR_FS_WORK . 'addons/update.phar')) unlink(DIR_FS_WORK . 'addons/update.phar');
      self::log('##### ADDON INSTALL ' . $code . ' COMPLETE');
    }

    return $phar_can_open;
  }
 /**
  * Execute the runAfter process
  *  
  * @access public      
  * @return boolean
  */   
  public static function doRunAfter() {
    if (file_exists(DIR_FS_WORK . 'updates/runAfter/controller.php')) {
      try {
        include_once(DIR_FS_WORK . 'updates/runAfter/controller.php');
        lC_Updates_Admin_run_after::process();
        return true;
      } catch ( Exception $e ) {      
        return false;
      }
    }
  }
 /**
  * Make an update-log history entry
  *  
  * @param string $action The update action
  * @param string $result The update result
  * @access public      
  * @return boolean
  */ 
  public static function writeHistory($action = 'update', $result = null) {
    global $lC_Database;
    
    $lC_Database->startTransaction();
        
    $Qhistory = $lC_Database->query('insert into :table_updates_log (action, result, user, dateCreated) values (:action, :result, :user, :dateCreated)');
    $Qhistory->bindTable(':table_updates_log', TABLE_UPDATES_LOG);
    $Qhistory->bindValue(':action', ucwords($action));
    $Qhistory->bindValue(':result', $result);
    $Qhistory->bindValue(':user', $_SESSION['admin']['firstname'] . ' ' . $_SESSION['admin']['lastname']);
    $Qhistory->bindValue(':dateCreated', date("Y-m-d H:i:s"));      
    $Qhistory->setLogging($_SESSION['module']); 
    $Qhistory->execute();  

    if (!$lC_Database->isError()) {
      $lC_Database->commitTransaction();
      return true;
    } else {
      $lC_Database->rollbackTransaction();
      return false;
    }  
  }  
 /**
  * Make a log entry
  *  
  * @param string $message  The message to log
  * @access protected      
  * @return void
  */ 
  protected static function log($message, $code = null) {
    if ( is_writable(DIR_FS_WORK . 'logs') ) {
      if ($code == null) {
        file_put_contents(DIR_FS_WORK . 'logs/update-' . self::$_to_version . '.txt', '[' . lC_DateTime::getNow('d-M-Y H:i:s') . '] ' . $message . "\n", FILE_APPEND);
      } else {
        file_put_contents(DIR_FS_WORK . 'logs/addon-' . $code . '.txt', '[' . lC_DateTime::getNow('d-M-Y H:i:s') . '] ' . $message . "\n", FILE_APPEND);
      }
    }
  }
 /**
  * Remove files
  *  
  * @param string $message  The message to log
  * @access protected      
  * @return boolean
  */ 
  protected static function rmdir_r($path) {
    $i = new DirectoryIterator($path);
    foreach($i as $f) {
      if($f->isFile()) {
        @unlink($f->getRealPath());
      } else if(!$f->isDot() && $f->isDir()) {
        self::rmdir_r($f->getRealPath());
        @rmdir($f->getRealPath());
      }
    }
    @rmdir($path);
    
    return true;
  }  
 /**
  * Recursive set permissions on files and folders
  *  
  * @param string $path The parent path to start from
  * @access protected      
  * @return boolean
  */
  protected static function chmod_r($path, $filePerm=0644, $dirPerm=0755) {
    // Check if the path exists
    if (!file_exists($path)) {
      return(false);
    }

    if (is_file($path)) {
      @chmod($path, $filePerm);
    } elseif (is_dir($path)) {
      $foldersAndFiles = @scandir($path);
      $entries = @array_slice($foldersAndFiles, 2);
      foreach ($entries as $entry) {
        self::chmod_r($path."/".$entry, $filePerm, $dirPerm);
      }
      @chmod($path, $dirPerm);
    }

    return true;
  }
 /**
  * Check if a log exists
  *  
  * @param string $log  The log file to check
  * @access public      
  * @return boolean
  */
  public static function logExists($log) {
    $log = basename($log);

    if ( substr($log, 0, -4) != '.txt' ) {
      $log .= '.txt';
    }

    return file_exists(DIR_FS_WORK . 'logs/' . $log);
  }  
 /**
  * Get the log file names
  *  
  * @access public      
  * @return array
  */  
  public static function getLogs() {
    $result = array();

    $it = new GlobIterator(DIR_FS_WORK . 'logs/update-*.txt');

    foreach ( $it as $f ) {
      $result[] = $f->getFilename();
    }

    return $result;
  }
 /**
  * Retrieve log data
  *  
  * @param string $log  The log file to retrieve
  * @access public      
  * @return array
  */
  public static function getLog($log) {
    $log = basename($log);

    if ( substr($log, 0, -4) != '.txt' ) {
      $log .= '.txt';
    }

    $result = array('entries' => array());

    foreach ( file(DIR_FS_WORK . 'logs/' . $log) as $l ) {
      if ( preg_match('/^\[([0-9]{2})-([A-Za-z]{3})-([0-9]{4}) ([0-9]{2}):([0-5][0-9]):([0-5][0-9])\] (.*)$/', $l) ) {
        $result['entries'][] = array('date' => lC_DateTime::getShort(lC_DateTime::fromUnixTimestamp(lC_DateTime::getTimestamp(substr($l, 1, 20), 'd-M-Y H:i:s')), true),
                                     'message' => substr($l, 23));
      }
    }

    $result['total'] = count($result['entries']);

    return $result;
  }  
 /**
  * Search for pattern within a log file
  *  
  * @param string $log    The log file to search in
  * @param string $search The data to search for
  * @access public      
  * @return array
  */
  public static function findLog($log, $search) {
    $data = self::getLog($log);

    $result = array('entries' => array());

    foreach ( $data['entries'] as $l ) {
      if ( stripos($l['message'], $search) !== false ) {
        $result['entries'][] = $l;
      }
    }

    $result['total'] = count($result['entries']);

    return $result;
  } 
 /**
  * Create a zip archive of the entire fileset
  *  
  * @access public      
  * @return boolean
  */
  public static function fullBackup() {

    $ext = '.zip';
    
    $backup_file = 'full-backup-' . str_replace('.','', utility::getVersion()) . $ext;
    
    // remove the old backup
    if (file_exists(DIR_FS_WORK . 'updates/' . $backup_file)) unlink(DIR_FS_WORK . 'updates/' . $backup_file);
         
    // create full file backup
    if (utility::execEnabled() === true && utility::isLinux() === true) {
      try {
        exec(CFG_APP_ZIP . ' -r ' . DIR_FS_WORK . 'updates/' . $backup_file . ' ' . DIR_FS_CATALOG . '* -x \*.zip\*');
      } catch ( Exception $e ) {  
        return array('rpcStatus' => 0);
      } 
      
      return array('rpcStatus' => 1);
      
    } else if (extension_loaded('zip')) {
      try {
        self::_makeZip(DIR_FS_CATALOG, DIR_FS_WORK . 'updates/' . $backup_file);  
      } catch ( Exception $e ) {  
        return array('rpcStatus' => 0);
      }       
      
      return array('rpcStatus' => 1);
      
    } else {  
      return array('rpcStatus' => -1);
    }
  }
  
 
 /**
  * Restore from full file backup zip
  *  
  * @access public      
  * @return boolean
  */
  public static function fullFileRestore($version) {

    $ext = '.zip';
    
    $restore_file = $version . $ext;
    
    if (file_exists(DIR_FS_WORK . 'updates/' . $restore_file)) {
      // remove old zip extraction  if any
      $parent_dir = explode('/', DIR_FS_CATALOG);
      if (is_dir(DIR_FS_WORK . 'updates/' . $parent_dir[1])) self::rmdir_r(DIR_FS_WORK . 'updates/' . $parent_dir[1]);

      // restore full file backup
      if (utility::execEnabled() === true && utility::isLinux() === true) {      
        try {
          // unzip the archive into work/updates/
          exec(CFG_APP_UNZIP . ' ' . DIR_FS_WORK . 'updates/' . $restore_file .  ' -d ' . DIR_FS_WORK . 'updates/');
          //copy the files
          exec('\cp -fr ' . DIR_FS_WORK . 'updates' . DIR_FS_CATALOG . '* ' . DIR_FS_CATALOG); 
          // cleanup
          if (is_dir(DIR_FS_WORK . 'updates/' . $parent_dir[1])) self::rmdir_r(DIR_FS_WORK . 'updates/' . $parent_dir[1]);
          return array('rpcStatus' => 1);
        } catch ( Exception $e ) {  
          return array('rpcStatus' => 0);
        }
      } else if (extension_loaded('zip')) {
        try {
          self::_extractZip(DIR_FS_WORK . 'updates/' . $restore_file, dirname(DIR_FS_CATALOG) );  
          if (is_dir(DIR_FS_WORK . 'updates/' . $parent_dir[1])) self::rmdir_r(DIR_FS_WORK . 'updates/' . $parent_dir[1]);
          return array('rpcStatus' => 1);          
        } catch ( Exception $e ) {  
          return array('rpcStatus' => 0);
        }        
      } else {
        return array('rpcStatus' => -1);
      }
    } else {
      return array('rpcStatus' => -2);
    }
  }  
 /**
  * Get backups listing
  *  
  * @access public      
  * @return array
  */
  public static function getBackups() {
    $lC_DirectoryListing = new lC_DirectoryListing(DIR_FS_WORK . 'updates');
    $lC_DirectoryListing->setIncludeDirectories(false);  
    $lC_DirectoryListing->setCheckExtension('zip');
    //$lC_DirectoryListing->setCheckExtension('sql');
    //$lC_DirectoryListing->setCheckExtension('gz');
    
    $backups = array();
    foreach ( $lC_DirectoryListing->getFiles() as $file ) {
      $backups[] = array('id' => substr($file['name'], 0, strrpos($file['name'], '.')), 'text' => substr($file['name'], 0, strrpos($file['name'], '.')) );
    }    
    
    return $backups;
  }  
 /**
  * Restore from last DB backup
  *  
  * @access public      
  * @return boolean
  */
  public static function lastDBRestore() {
    
    $lastBackup = self::__getLastDBBackup();
    
    try {
      lC_Backup_Admin::restore($lastBackup['name']);
    } catch ( Exception $e ) {
      return false;
    }
    
    return true;
  }  
 /*
  * Returns the update history datatable data
  *
  * @access public
  * @return array
  */
  public static function getHistory() { 
    global $lC_Language, $lC_Database, $_module;

    $media = $_GET['media'];
    
    $Qhistory = $lC_Database->query('select * from :table_updates_log order by dateCreated');
    $Qhistory->bindTable(':table_updates_log', TABLE_UPDATES_LOG);
    $Qhistory->execute();

    $result = array('aaData' => array());
    while ( $Qhistory->next() ) {
      
      $uAction = '<th scope"row">' .  $Qhistory->value('action') . '</th>';
      $uResult = '<td>' . $Qhistory->value('result') . '</td>';
      $uUser = '<td>' . $Qhistory->value('user') . '</td>';
      $uDate = '<td>' . lC_DateTime::getShort($Qhistory->value('dateCreated'), true) . '</td>';
      
  //    $log = '<td class="align-right vertical-center"><span class="button-group compact">
  //                 <a href="' . ((int)($_SESSION['admin']['access']['definitions'] < 3) ? '#' : 'javascript://" onclick="editGroup(\'' . $Qgroups->valueInt('customers_group_id') . '\')') . '" class="button icon-pencil' . ((int)($_SESSION['admin']['access']['definitions'] < 3) ? ' disabled' : NULL) . '">' .  (($media === 'mobile-portrait' || $media === 'mobile-landscape') ? NULL : $lC_Language->get('icon_edit')) . '</a>
  //                 <a href="' . ((int)($_SESSION['admin']['access']['definitions'] < 4 || $Qgroups->valueInt('customers_group_id') == DEFAULT_CUSTOMERS_GROUP_ID) ? '#' : 'javascript://" onclick="deleteGroup(\'' . $Qgroups->valueInt('customers_group_id') . '\', \'' . urlencode($Qgroups->valueProtected('title')) . '\')') . '" class="button icon-trash with-tooltip' . ((int)($_SESSION['admin']['access']['definitions'] < 4 || $Qgroups->valueInt('customers_group_id') == DEFAULT_CUSTOMERS_GROUP_ID ) ? ' disabled' : NULL) . '" title="' . $lC_Language->get('icon_delete') . '"></a>
  //               </span></td>';
  
      $result['aaData'][] = array("$uAction", "$uResult", "$uUser", "$uDate");
      $result['entries'][] = $Qhistory->toArray();
    }

    $Qhistory->freeResult();

    return $result;
  } 
 /**
  * Set Maintenance Mode
  *  
  * @access public      
  * @return boolean
  */
  public static function setMaintenanceMode($mode) {
    global $lC_Database;
    
    if ($mode == 'on') {
      $lC_Database->simpleQuery("update " . TABLE_CONFIGURATION . " set configuration_value = '1' where configuration_key = 'STORE_DOWN_FOR_MAINTENANCE'");
    } else {
      $lC_Database->simpleQuery("update " . TABLE_CONFIGURATION . " set configuration_value = '-1' where configuration_key = 'STORE_DOWN_FOR_MAINTENANCE'");
    }
    
    lC_Cache::clear('configuration');
    
    return true;
  }  
 /**
  * Get the last database backup
  *  
  * @access public      
  * @return array
  */
  private static function __getLastDBBackup() {
    $lC_DirectoryListing = new lC_DirectoryListing(DIR_FS_BACKUP);
    $lC_DirectoryListing->setIncludeDirectories(false);
    $lC_DirectoryListing->setStats(true);
    $lC_DirectoryListing->setCheckExtension('zip');
    $lC_DirectoryListing->setCheckExtension('sql');
    $lC_DirectoryListing->setCheckExtension('gz');
   
    $latest = 0;   
    foreach ( $lC_DirectoryListing->getFiles() as $file ) {
      if ((int)$latest < (int)$file['last_modified']) $latest = $file;
    }

    return $file;
  } 
 /**
  * Create a ZIP archive using PHP ZipArchive method
  *  
  * @access private      
  * @return boolean
  */
  private static function _makeZip($source, $destination, $include_dir = true) {

    if (!extension_loaded('zip') || !file_exists($source)) {
      return false;
    }

    if (file_exists($destination)) {
      unlink ($destination);
    }

    $zip = new ZipArchive();
    if (!$zip->open($destination, ZIPARCHIVE::CREATE)) {
      return false;
    }
    $source = str_replace('\\', '/', realpath($source));

    if (is_dir($source) === true) {

      $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);

      if ($include_dir) {

        $arr = explode("/",$source);
        $maindir = $arr[count($arr)- 1];

        $source = "";
        for ($i=0; $i < count($arr) - 1; $i++) { 
          $source .= '/' . $arr[$i];
        }

        $source = substr($source, 1);

        $zip->addEmptyDir($maindir);
      }

      foreach ($files as $file) {
        $file = str_replace('\\', '/', $file);

        // Ignore "." and ".." folders
        if( in_array(substr($file, strrpos($file, '/')+1), array('.', '..')) ) continue;

        $file = realpath($file);

        if (is_dir($file) === true) {
          $zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
        } else if (is_file($file) === true) {
          $zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
        }
      }
    } else if (is_file($source) === true) {
      $zip->addFromString(basename($source), file_get_contents($source));
    }

    return $zip->close();
  }
 /**
  * Extract a ZIP archive using PHP ZipArchive method
  *  
  * @access private      
  * @return boolean
  */
  private static function _extractZip($source, $destination) {
    
    $zip = new ZipArchive;
    
    $status = false;
    if ($zip->open($source)) {
      if ($zip->extractTo($destination)) {
        $status = true;
      }
      $zip->close();
    }    

    return $status;
  }     
}
?>