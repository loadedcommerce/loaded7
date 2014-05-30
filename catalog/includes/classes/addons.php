<?php
/**
  @package    catalog::classes
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: addons.php v1.0 2013-08-08 datazen $
*/
require_once(DIR_FS_CATALOG . 'includes/classes/addon.php');

class lC_Addons {
  
  private $_data;

  // class contructor 
  public function __construct() {
    
    if (empty($_GET) === false) {
      $first_array = array_slice($_GET, 0, 1);
      $module = lc_sanitize_string(basename(key($first_array)));    
    
      if (array_key_exists('login', $_GET)) return false;
    }

    if ( !isset($_SESSION['lC_Addons_data']) ) {
      $this->_initialize();
    }
  } 
  
  public function getAddons($flag = '') {   
    if (!is_array($this->_data)) {
      $this->_initialize();
    }
    
    if ($flag == 'enabled') {
      $dArr = array();
      foreach($this->_data as $ao => $aoData) {
        if ($aoData['enabled'] == true) $dArr[$ao] = $aoData;
      } 
      $data = $dArr;
    } else {
      $data = $this->_data; 
    }
    
    return $data;
  }
  
  // private methods
  protected function _initialize() {
    global $lC_Vqmod;
    
    $lC_DirectoryListing = new lC_DirectoryListing(DIR_FS_CATALOG . 'addons');
    $lC_DirectoryListing->setRecursive(true);
    $lC_DirectoryListing->setIncludeDirectories(false);
    $lC_DirectoryListing->setCheckExtension('php');
    $lC_DirectoryListing->setStats(true);
      
    $enabled = '';
    $lC_Addons_data = array();
    foreach ( $lC_DirectoryListing->getFiles() as $addon ) { 
      $ao = utility::cleanArr($addon);  
      
      if ($ao['name'] != 'controller.php') continue;

      $nameArr = explode('/', $ao['path']);
      $class = $nameArr[count($nameArr)-2];

      if (file_exists($ao['path'])) {
        if (isset($aoData)) { 
        } else {
          include_once($lC_Vqmod->modCheck($ao['path']));
          $aoData = new $class();
        } 
        
        $_SESSION['lC_Addons_data'][$class] = array('type' => $aoData->getAddonType(),
                                                    'title' => $aoData->getAddonTitle(),
                                                    'description' => $aoData->getAddonDescription(),
                                                    'rating' => $aoData->getAddonRating(),
                                                    'author' => $aoData->getAddonAuthor(),
                                                    'authorWWW' => $aoData->getAddonAuthorWWW(),
                                                    'thumbnail' => $aoData->getAddonThumbnail(),
                                                    'version' => $aoData->getAddonVersion(),
                                                    'compatibility' => $aoData->getCompatibility(),
                                                    'installed' => $aoData->isInstalled(),
                                                    'mobile' => $aoData->isMobileEnabled(),
                                                    'enabled' => $aoData->isEnabled());  
        
        if ($aoData->isEnabled()) $enabled .= $addon['path'] . ';';
      }
    }   
       
    if ($enabled != '') $enabled = substr($enabled, 0, -1);
    if (!file_exists(DIR_FS_WORK . 'cache/addons.cache')) {
      file_put_contents(DIR_FS_WORK . 'cache/addons.cache', serialize($enabled));
    }
    
    $this->_data = $_SESSION['lC_Addons_data'];
  }
}
?>