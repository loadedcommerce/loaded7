<?php
/**
  $Id: updates.php v1.0 2011-11-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html

  @function The lC_Updater_Admin class manages zM services
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
    
 //   lC_Cache::clear('configuration'); 

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
        if ( substr($file, 0, 8) == 'catalog/' ) {
          $custom = false;

          $result['entries'][] = array('key' => $counter,
                                       'name' => $file,
                                       'exists' => file_exists(realpath(DIR_FS_CATALOG . '../../') . '/' . $file),
                                       'writable' => self::isWritable(realpath(DIR_FS_CATALOG . '../../') . '/' . $file) && self::isWritable(realpath(DIR_FS_CATALOG . '../../') . '/' . dirname($file)),
                                       'custom' => $custom,
                                       'to_delete' => false);

          $counter++;
        }
      }
    }

    $meta = $phar->getMetadata();

    if ( isset($meta['delete']) ) {
      $files = array();

      foreach ( $meta['delete'] as $file ) {
        if ( substr($file, 0, 8) == 'catalog/' ) {
          if ( file_exists(realpath(DIR_FS_CATALOG . '../../') . '/' . $file) ) {
            if ( is_dir(realpath(DIR_FS_CATALOG . '../../') . '/' . $file) ) {
              $DL = new DirectoryListing(realpath(DIR_FS_CATALOG . '../../') . '/' . $file);
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

        if ( substr($d, 0, 8) == 'catalog/' ) {
          $writable = self::isWritable(realpath(DIR_FS_CATALOG . '../../') . '/' . $d) && self::isWritable(realpath(DIR_FS_CATALOG . '../../') . '/' . dirname($d));
        }

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
  public static function applyPackage() {
    
    $phar_can_open = true;

    $meta = array();
    $pro_hart = array();

    try {
      $phar = $phar = new Phar(DIR_FS_WORK . 'updates/update.phar', 0);

      $meta = $phar->getMetadata();     

      self::$_to_version = $meta['version_to'];

      // reset the log
      if ( file_exists(DIR_FS_WORK . 'logs/update-' . self::$_to_version . '.txt') && is_writable(DIR_FS_WORK . 'logs/update-' . self::$_to_version . '.txt') ) {
        unlink(DIR_FS_WORK . 'logs/update-' . self::$_to_version . '.txt');
      }

      self::log('##### UPDATE TO ' . self::$_to_version . ' STARTED');

      // first delete files before extracting new files
      if ( isset($meta['delete']) ) {
        foreach ( $meta['delete'] as $file ) {
          $directory = realpath(DIR_FS_CATALOG . '../../');

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
          $directory = realpath(DIR_FS_CATALOG) . '/';
          
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

    if ( $phar_can_open === true ) {
      if ( isset($meta['run']) && method_exists('osCommerce\\OM\\Work\\CoreUpdate\\' . $meta['run'] . '\\Controller', 'runAfter') ) {
        $results = call_user_func(array(DIR_FS_WORK . 'updates/' . $meta['run'] . '/controller', 'runAfter'));

        if ( !empty($results) ) {
          self::log('##### RAN AFTER');

          foreach ( $results as $r ) {
            self::log($r);
          }
        }

        self::log('##### CLEANUP');

        if ( self::rmdir_r(DIR_FS_WORK . 'updates/' . $meta['run']) ) {
          self::log('Deleted: catalog/includes/work/updates/' . $meta['run']);
        } else {
          self::log('*** Could Not Delete: catalog/includes/work/updates/' . $meta['run']);
        }
      }

      self::log('##### UPDATE TO ' . self::$_to_version . ' COMPLETE');
    }
    
    return $phar_can_open;
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
  protected static function log($message) {
    if ( is_writable(DIR_FS_WORK . 'logs') ) {
      file_put_contents(DIR_FS_WORK . 'logs/update-' . self::$_to_version . '.txt', '[' . lC_DateTime::getNow('d-M-Y H:i:s') . '] ' . $message . "\n", FILE_APPEND);
    }
  }
 /**
  * Remove files
  *  
  * @param string $message  The message to log
  * @access protected      
  * @return boolean
  */ 
  protected static function rmdir_r($dir) {
    foreach ( scandir($dir) as $file ) {
      if ( !in_array($file, array('.', '..')) ) {
        if ( is_dir($dir . '/' . $file) ) {
          self::rmdir_r($dir . '/' . $file);
        } else {
          unlink($dir . '/' . $file);
        }
      }
    }

    return rmdir($dir);
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
    try {
      exec(CFG_APP_ZIP . ' -r ' . DIR_FS_WORK . 'updates/' . $backup_file . ' ' . DIR_FS_CATALOG . '* -x \*.zip\*');
    } catch ( Exception $e ) {  
      return false;
    } 

    return true;
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
      try {
        // unzip the archive into work/updates/
        exec(CFG_APP_UNZIP . ' ' . DIR_FS_WORK . 'updates/' . $restore_file .  ' -d ' . DIR_FS_WORK . 'updates/');
        //copy the files
        exec('\cp -fr ' . DIR_FS_WORK . 'updates' . DIR_FS_CATALOG . '* ' . DIR_FS_CATALOG); 
        // cleanup
        if (is_dir(DIR_FS_WORK . 'updates/' . $parent_dir[1])) self::rmdir_r(DIR_FS_WORK . 'updates/' . $parent_dir[1]);
      } catch ( Exception $e ) {  
        return false;
      }
      return true;
    } else {
      return false;
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
  * Set Maintenance Mode
  *  
  * @access public      
  * @return boolean
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
}
?>