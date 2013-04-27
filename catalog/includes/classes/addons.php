<?php
/**
  $Id: addons.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
class lC_Addons {

  // class constructor
  public function lC_Addons() {
    ini_set('display_errors', 1);
    $this->_initialize();
  }
  
  private function _initialize() {
    global $lC_DirectoryListing;

    $lC_DirectoryListing = new lC_DirectoryListing(DIR_FS_CATALOG . 'addons');
    $lC_DirectoryListing->setRecursive(true);
    $lC_DirectoryListing->setIncludeDirectories(false);
    $lC_DirectoryListing->setAddDirectoryToFilename(true);
  //  $lC_DirectoryListing->setStats(true);
    $lC_DirectoryListing->setCheckExtension('php');
    
    $addons = array();
    foreach ( $lC_DirectoryListing->getFiles() as $addon ) { 
      $ao = utility::cleanArr($addon);
      if ($ao['name'] == 'inc/bootstrap.php') continue;
      $class = substr($ao['name'], 0, strpos($ao['name'], '/'));   
      if (file_exists(DIR_FS_CATALOG . 'addons/' . $ao['name'])) {
        include_once(DIR_FS_CATALOG . 'addons/' . $ao['name']);
        $GLOBALS[$class] = new $class();
      }
    }    
  }
}
?>