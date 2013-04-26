<?php
error_reporting(0);
/**
 * VQMod
 * @description Main Object used
 */
final class VQMod {
  private $_vqversion = '2.3.2';
  private $_modFileList = array();
  private $_mods = array();
  private $_filesModded = array();
  private $_cwd = '';
  private $_doNotMod = array();
  private $_folderChecks = false;
  private $_cachePathFull = '';
  private $_lastModifiedTime = 0;
  private $_devMode = true;

  public $logFolder = '../includes/work/logs/vqmod-admin/';
  public $vqCachePath = '../includes/work/cache/vqmod-admin/';
  public $modCache = 'external/vqmod/vqcache/vqmods.cache';
  public $protectedFilelist = 'external/vqmod/vqprotect.txt';
  public $pathReplaces = 'external/vqmod/pathReplaces.php';
  public $logging = true;
  public $log;
  public $fileModding = false;
  public $directorySeparator = '';

  /**
   * VQMod::__construct()
   *
   * @param bool $path File path to use
   * @param bool $logging Enable/disabled logging
   * @return null
   * @description Startup of VQMod
   */
  public function __construct($path = false, $logging = true) {
    if(!class_exists('DOMDocument')) {
      die('ERROR - YOU NEED DOMDocument INSTALLED TO USE VQMod');
    }
    
    $this->directorySeparator = defined('DIRECTORY_SEPARATOR') ? DIRECTORY_SEPARATOR : '/';

    if(!$path){
      $path = dirname(dirname(__FILE__));
    }
    $this->_setCwd($path);

    $this->logging = (bool) $logging;
    $this->log = new VQModLog($this);
    
    $replacesPath = $this->path($this->pathReplaces);
    $replaces = array();
    if($replacesPath) {
      include_once($replacesPath);
      $this->_lastModifiedTime = filemtime($replacesPath);
    }
    
    $this->_replaces = !is_array($replaces) ? array() : $replaces;
    $this->_getMods();
    $this->_loadProtected();
  }

  /**
   * VQMod::modCheck()
   *
   * @param string $sourceFile path for file
   * @return string
   * @description Checks if a file has modifications and applies them, returning cache files or the file name
   */
  public function modCheck($sourceFile) {

    if(!$this->_folderChecks) {

      if($this->logging) {
        // Create log folder if it doesn't exist
        $log_folder = $this->path($this->logFolder, true);
        $this->dirCheck($log_folder);
      }

      // Create cache folder if it doesn't exist
      $cache_folder = $this->path($this->vqCachePath, true);
      $this->dirCheck($cache_folder);

      // Store cache folder path to save on repeat checks for path validity
      $this->_cachePathFull = $this->path($this->vqCachePath);

      $this->_folderChecks = true;
    }

    if(!preg_match('%^([a-z]:)?[\\\\/]%i', $sourceFile)) {
      $sourcePath = $this->path($sourceFile);
    } else {
      $sourcePath = $this->_realpath($sourceFile);
    }

    if(!$sourcePath || is_dir($sourcePath) || in_array($sourcePath, $this->_doNotMod)) {
      return $sourceFile;
    }

    $stripped_filename = preg_replace('~^' . preg_quote($this->getCwd(), '~') . '~', '', $sourcePath);
    $cacheFile = $this->_cacheName($stripped_filename);
    $file_last_modified = filemtime($sourcePath);

    if(file_exists($cacheFile) && filemtime($cacheFile) >= $this->_lastModifiedTime && filemtime($cacheFile) >= $file_last_modified) {
      return $cacheFile;
    }

    if(isset($this->_filesModded[$sourcePath])) {
      return $this->_filesModded[$sourcePath]['cached'] ? $cacheFile : $sourceFile;
    }

    $changed = false;
    $fileHash = sha1_file($sourcePath);
    $fileData = file_get_contents($sourcePath);

    foreach($this->_mods as $modObject) {
      foreach($modObject->mods as $path => $mods) {
        if($this->_checkMatch($path, $sourcePath)) {
          $modObject->applyMod($mods, $fileData);
        }
      }
    }

    if (sha1($fileData) != $fileHash) {
      $writePath = $cacheFile;
      if(!file_exists($writePath) || is_writable($writePath)) {
        file_put_contents($writePath, $fileData);
        $changed = true;
      }
    }

    $this->_filesModded[$sourcePath] = array('cached' => $changed);
    $this->fileModding = false;
    return $changed ? $writePath : $sourcePath;
  }

  /**
   * VQMod::path()
   *
   * @param string $path File path
   * @param bool $skip_real If true path is full not relative
   * @return bool, string
   * @description Returns the full true path of a file if it exists, otherwise false
   */
  public function path($path, $skip_real = false) {
    $tmp = realpath($this->_cwd . '../') . '/' . $path;
    $realpath = $skip_real ? $tmp : $this->_realpath($tmp);

    if(!$realpath) {
      return false;
    }
        
    return $realpath;
  }

  /**
   * VQMod::getCwd()
   *
   * @return string
   * @description Returns current working directory
   */
  public function getCwd() {
    return $this->_cwd;
  }

  /**
   * VQMod::dirCheck()
   * 
   * @param string $path
   * @return null
   * @description Creates $path folder if it doesn't exist 
   */
  public function dirCheck($path) {
    if(!is_dir($path)) {
      if(!mkdir($path)) {
        die('ERROR! FOLDER CANNOT BE CREATED: ' . $path);
      }
    }
  }

  /**
   * VQMod::_getMods()
   *
   * @return null
   * @description Gets list of XML files in vqmod xml folder for processing
   */
  private function _getMods() {

    $this->_modFileList = glob($this->path('ext/vqmod/xml/', true) . '*.xml');

    foreach($this->_modFileList as $file) {
      if(file_exists($file)) {
        $lastMod = filemtime($file);
        if($lastMod > $this->_lastModifiedTime){
          $this->_lastModifiedTime = $lastMod;
        }
      }
    }

    $xml_folder_time = filemtime($this->path('ext/vqmod/xml'));
    if($xml_folder_time > $this->_lastModifiedTime){
      $this->_lastModifiedTime = $xml_folder_time;
    }

    $modCache = $this->path($this->modCache);
    if($this->_devMode || !file_exists($modCache)) {
      $this->_lastModifiedTime = time();
    } elseif(file_exists($modCache) && filemtime($modCache) >= $this->_lastModifiedTime) {
      $mods = file_get_contents($modCache);
      if(!empty($mods))
      $this->_mods = unserialize($mods);
      if($this->_mods !== false) {
        return;
      }
    }

    if($this->_modFileList) {
      $this->_parseMods();
    } else {
      $this->log->write('NO MODS IN USE');
    }
  }

  /**
   * VQMod::_parseMods()
   *
   * @return null
   * @description Loops through xml files and attempts to load them as VQModObject's
   */
  private function _parseMods() {

    $dom = new DOMDocument('1.0', 'UTF-8');
    foreach($this->_modFileList as $modFileKey => $modFile) {
      if(file_exists($modFile)) {
        if(@$dom->load($modFile)) {
          $mod = $dom->getElementsByTagName('modification')->item(0);
          $this->_mods[] = new VQModObject($mod, $modFile, $this);
        } else {
          $this->log->write('DOM UNABLE TO LOAD: ' . $modFile);
        }
      } else {
        $this->log->write('FILE NOT FOUND: ' . $modFile);
      }
    }

    $modCache = $this->path($this->modCache, true);
    $result = file_put_contents($modCache, serialize($this->_mods));
    if(!$result) {
      die('MODS CACHE PATH NOT WRITEABLE');
    }
  }

  /**
   * VQMod::_loadProtected()
   *
   * @return null
   * @description Loads protected list and adds them to _doNotMod array
   */
  private function _loadProtected() {
    $file = $this->path($this->protectedFilelist);
    if($file && is_file($file)) {
      $protected = file_get_contents($file);
      if(!empty($protected)) {
        $protected = preg_replace('~\r?\n~', "\n", $protected);
        $paths = explode("\n", $protected);
        foreach($paths as $path) {
          $fullPath = $this->path($path);
          if($fullPath && !in_array($fullPath, $this->_doNotMod)) {
            $this->_doNotMod[] = $fullPath;
          }
        }
      }
    }
  }

  /**
   * VQMod::_cacheName()
   *
   * @param string $file Filename to be converted to cache filename
   * @return string
   * @description Returns cache file name for a path
   */
  private function _cacheName($file) {
    return $this->_cachePathFull . 'vq2-' . preg_replace('~[/\\\\]+~', '_', $file);
  }

  /**
   * VQMod::_setCwd()
   *
   * @param string $path Path to be used as current working directory
   * @return null
   * @description Sets the current working directory variable
   */
  private function _setCwd($path) {
    $this->_cwd = $this->_realpath($path);
  }

  /**
   * VQMod::_realpath()
   * 
   * @param string $file
   * @return string
   * @description Returns real path of any path, adding directory slashes if necessary
   */
  private function _realpath($file) {
    $path = realpath($file);
    if(!file_exists($path)) {
      return false;
    }

    if(is_dir($path)) {
      $path = rtrim($path, $this->directorySeparator) . $this->directorySeparator;
    }

    return $path;
  }

  /**
   * VQMod::_checkMatch()
   *
   * @param string $modFilePath Modification path from a <file> node
   * @param string $checkFilePath File path
   * @return bool
   * @description Checks a modification path against a file path
   */
  private function _checkMatch($modFilePath, $checkFilePath) {
    $modFilePath = str_replace('\\', '/', $modFilePath);
    $checkFilePath = str_replace('\\', '/', $checkFilePath);
    
    if(strpos($modFilePath, '*') !== false) {
      $modFilePath = preg_replace('/([^*]+)/e', 'preg_quote("$1", "~")', $modFilePath);
      $modFilePath = str_replace('*', '[^/]*', $modFilePath);
      $return = (bool) preg_match('~^' . $modFilePath . '$~', $checkFilePath);
    } else {
      $return = $modFilePath == $checkFilePath;
    }
    
    return $return;

  }
}

/**
 * VQModLog
 * @description Object to log information to a file
 */
class VQModLog {
  private $_sep;
  private $_vqmod;
  private $_defhash = 'da39a3ee5e6b4b0d3255bfef95601890afd80709';
  private $_logs = array();

  /**
   * VQModLog::__construct()
   *
   * @param VQMod $vqmod VQMod main class as reference
   * @return null
   * @description Object instantiation method
   */
  public function __construct(VQMod $vqmod) {
    $this->_vqmod = $vqmod;
    $this->_sep = str_repeat('-', 70);
  }

  /**
   * VQModLog::__destruct()
   *
   * @return null
   * @description Logs any messages to the log file just before object is destroyed
   */
  public function __destruct() {
    if(empty($this->_logs) || $this->_vqmod->logging == false) {
      return;
    }

    $logPath = $this->_vqmod->path($this->_vqmod->logFolder . date('D') . '.log', true);
          
    $txt = array();
    $txt[] = str_repeat('-', 10) . ' Date: ' . date('Y-m-d H:i:s') . ' ~ IP : ' . (isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'N/A') . ' ' . str_repeat('-', 10);
    $txt[] = 'REQUEST URI : ' . $_SERVER['REQUEST_URI'];

    foreach($this->_logs as $count => $log) {
      if($log['obj']) {
        $vars = get_object_vars($log['obj']);
        $txt[] = 'MOD DETAILS:';
        foreach($vars as $k => $v) {
          if(is_string($v)) {
            $txt[] = '   ' . str_pad($k, 10, ' ', STR_PAD_RIGHT) . ': ' . $v;
          }
        }

      }

      foreach($log['log'] as $msg) {
        $txt[] = $msg;
      }

      if ($count > count($this->_logs)-1) {
        $txt[] = '';
      }
    }

    $txt[] = $this->_sep;
    $txt[] = str_repeat(PHP_EOL, 2);
    $append = true;

    if(!file_exists($logPath)) {
      $append = false;
    } else {
      $content = file_get_contents($logPath);
      if(!empty($content) && strpos($content, ' Date: ' . date('Y-m-d ')) === false) {
        $append = false;
      }
    }

    $result = @file_put_contents($logPath, implode(PHP_EOL, $txt), ($append ? FILE_APPEND : 0));
    if(!$result) {
      //die('LOG FILE COULD NOT BE WRITTEN');
    }
  }

  /**
   * VQModLog::write()
   *
   * @param string $data Text to be added to log file
   * @param VQModObject $obj Modification the error belongs to
   * @return null
   * @description Adds error to log object ready to be output
   */
  public function write($data, VQModObject $obj = NULL) {
    if($obj) {
      $hash = sha1($obj->id);
    } else {
      $hash = $this->_defhash;
    }

    if(empty($this->_logs[$hash])) {
      $this->_logs[$hash] = array(
        'obj' => $obj,
        'log' => array()
      );
    }

    if($this->_vqmod->fileModding) {
      $this->_logs[$hash]['log'][] = PHP_EOL . 'File Name    : ' . $this->_vqmod->fileModding;
    }

    $this->_logs[$hash]['log'][] = $data;

  }
}

/**
 * VQModObject
 * @description Object for the <modification> that orchestrates each applied modification
 */
class VQModObject {
  public $modFile = '';
  public $id = '';
  public $version = '';
  public $vqmver = '';
  public $author = '';
  public $mods = array();

  private $_vqmod;
  private $_skip = false;

  /**
   * VQModObject::__construct()
   *
   * @param DOMNode $node <modification> node
   * @param string $modFile File modification is from
   * @param VQMod $vqmod VQMod object as reference
   * @return null
   * @description Loads modification meta information
   */
  public function __construct(DOMNode $node, $modFile, VQmod $vqmod) {
    if($node->hasChildNodes()) {
      foreach($node->childNodes as $child) {
        $name = (string) $child->nodeName;
        if(isset($this->$name)) {
          $this->$name = (string) $child->nodeValue;
        }
      }
    }

    $this->modFile = $modFile;
    $this->_vqmod = $vqmod;
    $this->_parseMods($node);
  }

  /**
   * VQModObject::skip()
   *
   * @return bool
   * @description Returns the skip status of a modification
   */
  public function skip() {
    return $this->_skip;
  }

  /**
   * VQModObject::applyMod()
   *
   * @param array $mods Array of search add nodes
   * @param string $data File contents to be altered
   * @return null
   * @description Applies all modifications to the text data
   */
  public function applyMod($mods, &$data) {
    if($this->_skip) return;
    $tmp = $data;

    foreach($mods as $mod) {
      if(!empty($mod['ignoreif'])) {
        if($mod['ignoreif']->regex == 'true') {
          if (preg_match($mod['ignoreif']->getContent(), $tmp)) {
            continue;
          }
        } else {
          if (strpos($tmp, $mod['ignoreif']->getContent()) !== false) {
            continue;
          }
        }
      }
      
      $indexCount = 0;
      
      $tmp = $this->_explodeData($tmp);
      $lineMax = count($tmp) - 1;

      switch($mod['search']->position) {
        case 'top':
        $tmp[$mod['search']->offset] =  $mod['add']->getContent() . $tmp[$mod['search']->offset];
        break;

        case 'bottom':
        $offset = $lineMax - $mod['search']->offset;
        if($offset < 0){
          $tmp[-1] = $mod['add']->getContent();
        } else {
          $tmp[$offset] .= $mod['add']->getContent();
        }
        break;

        case 'all':
        $tmp = array($mod['add']->getContent());
        break;

        default:

        $changed = false;
        foreach($tmp as $lineNum => $line) {
          if(strlen($mod['search']->getContent()) == 0) {
            if($mod['error'] == 'log' || $mod['error'] == 'abort') {
              $this->_vqmod->log->write('EMPTY SEARCH CONTENT ERROR', $this);
            }
            break;
          }
          
          if($mod['search']->regex == 'true') {
            $pos = @preg_match($mod['search']->getContent(), $line);
            if($pos === false) {
              if($mod['error'] == 'log' || $mod['error'] == 'abort' ) {
                $this->_vqmod->log->write('INVALID REGEX ERROR - ' . $mod['search']->getContent(), $this);
              }
              continue 2;
            } elseif($pos == 0) {
              $pos = false;
            }
          } else {
            $pos = strpos($line, $mod['search']->getContent());
          }

          if($pos !== false) {
            $indexCount++;
            $changed = true;

            if(!$mod['search']->indexes() || ($mod['search']->indexes() && in_array($indexCount, $mod['search']->indexes()))) {

              switch($mod['search']->position) {
                case 'before':
                $offset = ($lineNum - $mod['search']->offset < 0) ? -1 : $lineNum - $mod['search']->offset;
                $tmp[$offset] = empty($tmp[$offset]) ? $mod['add']->getContent() : $mod['add']->getContent() . "\n" . $tmp[$offset];
                break;

                case 'after':
                $offset = ($lineNum + $mod['search']->offset > $lineMax) ? $lineMax : $lineNum + $mod['search']->offset;
                $tmp[$offset] = $tmp[$offset] . "\n" . $mod['add']->getContent();
                break;

                default:
                if(!empty($mod['search']->offset)) {
                  for($i = 1; $i <= $mod['search']->offset; $i++) {
                    if(isset($tmp[$lineNum + $i])) {
                      $tmp[$lineNum + $i] = '';
                    }
                  }
                }

                if($mod['search']->regex == 'true') {
                  $tmp[$lineNum] = preg_replace($mod['search']->getContent(), $mod['add']->getContent(), $line);
                } else {
                  $tmp[$lineNum] = str_replace($mod['search']->getContent(), $mod['add']->getContent(), $line);
                }
                break;
              }
            }
          }
        }

        if(!$changed) {
          $skip = ($mod['error'] == 'skip' || $mod['error'] == 'log') ? ' (SKIPPED)' : ' (ABORTING MOD)';

          if($mod['error'] == 'log' || $mod['error'] == 'abort') {
            $this->_vqmod->log->write('SEARCH NOT FOUND' . $skip . ': ' . $mod['search']->getContent(), $this);
          }

          if($mod['error'] == 'abort') {
            $this->_skip = true;
            return;
          }

        }

        break;
      }
      ksort($tmp);
      $tmp = $this->_implodeData($tmp);
    }

    $data = $tmp;
  }

  /**
   * VQModObject::_parseMods()
   *
   * @param DOMNode $node <modification> node to be parsed
   * @return null
   * @description Parses modifications in preparation for the applyMod method to work
   */
  private function _parseMods(DOMNode $node){
    $files = $node->getElementsByTagName('file');
    
    $replaces = $this->_vqmod->_replaces;

    foreach($files as $file) {
      $path = $file->getAttribute('path') ? $file->getAttribute('path') : '';
      $filesToMod = explode(',', $file->getAttribute('name'));
      
      foreach($filesToMod as $filename) {
        
        $fileToMod = $path . $filename;
        if(!empty($replaces)) {
          foreach($replaces as $r) {
            if(count($r) == 2) {
              $fileToMod = preg_replace($r[0], $r[1], $fileToMod);
            }
          }
        }
        
        $error = ($file->hasAttribute('error')) ? $file->getAttribute('error') : 'log';
        $fullPath = $this->_vqmod->path($fileToMod); 

        if(!$fullPath || !file_exists($fullPath)){
          if(strpos($fileToMod, '*') !== false) {
            $fullPath = $this->_vqmod->getCwd() . $fileToMod;
          } else {
            if ($error == 'log' || $error == 'abort') {
              $skip = ($error == 'log') ? ' (SKIPPED)' : ' (ABORTING MOD)';
              $this->_vqmod->log->write('Could not resolve path for [' . $fileToMod . ']' . $skip, $this);
            }
  
            if ($error == 'log' || $error == 'skip') {
              continue;
            } elseif ($error == 'abort') {
              return false;
            }
          }
        }
  
        $operations = $file->getElementsByTagName('operation');
  
        foreach($operations as $operation) {
          $error = ($operation->hasAttribute('error')) ? $operation->getAttribute('error') : 'abort';
          $ignoreif = $operation->getElementsByTagName('ignoreif')->item(0);
          
          if($ignoreif) {
            $ignoreif = new VQSearchNode($ignoreif);
          } else {
            $ignoreif = false;
          }
          
          
          $this->mods[$fullPath][] = array(
            'search'     => new VQSearchNode($operation->getElementsByTagName('search')->item(0)),
            'add'       => new VQAddNode($operation->getElementsByTagName('add')->item(0)),
            'ignoreif'    => $ignoreif,
            'error'       => $error
          );
        }
      }
    }
  }

  /**
   * VQModObject::_explodeData()
   *
   * @param string $data File contents
   * @return string
   * @description Splits a file into an array of individual lines
   */
  private function _explodeData($data) {
    return explode("\n", $data);
  }

  /**
   * VQModObject::_implodeData()
   *
   * @param array $data Array of lines
   * @return string
   * @description Joins an array of lines back into a text file
   */
  private function _implodeData($data) {
    return implode("\n", $data);
  }
}

/**
 * VQNode
 * @description Basic node object blueprint
 */
class VQNode {
  public $trim = 'false';

  private $_content = '';

  /**
   * VQNode::__construct()
   *
   * @param DOMNode $node Search/add node
   * @return null
   * @description Parses the node attributes and sets the node property
   */
  public function  __construct(DOMNode $node) {
    $this->_content = $node->nodeValue;

    if($node->hasAttributes()) {
      foreach($node->attributes as $attr) {
        $name = $attr->nodeName;
        if(isset($this->$name)) {
          $this->$name = $attr->nodeValue;
        }
      }
    }
  }

  /**
   * VQNode::getContent()
   *
   * @return string
   * @description Returns the content, trimmed if applicable
   */
  public function getContent() {
    $content = ($this->trim == 'true') ? trim($this->_content) : $this->_content;
    return $content;
  }
}

/**
 * VQSearchNode
 * @description Object for the <search> xml tags
 */
class VQSearchNode extends VQNode {
  public $position = 'replace';
  public $offset = 0;
  public $index = 'false';
  public $regex = 'false';
  public $trim = 'true';

  /**
   * VQSearchNode::indexes()
   *
   * @return bool, array
   * @description Returns the index values to use the search on, or false if none
   */
  public function indexes() {
    if($this->index == 'false') {
      return false;
    }
    $tmp = explode(',', $this->index);
    foreach($tmp as $k => $v) {
      if(!is_int($v)) {
        unset($k);
      }
    }
    $tmp = array_unique($tmp);
    return empty($tmp) ? false : $tmp;
  }
}

/**
 * VQAddNode
 * @description Object for the <add> xml tags
 */
class VQAddNode extends VQNode {
}