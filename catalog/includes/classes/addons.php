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
    $this->_initialize();
  }
  
  protected function _initialize() {
    $lC_DirectoryListing = new lC_DirectoryListing(DIR_FS_CATALOG . 'addons');
    $lC_DirectoryListing->setRecursive(false);
    $lC_DirectoryListing->setIncludeDirectories(false);
  //  $lC_DirectoryListing->setAddDirectoryToFilename(true);
    $lC_DirectoryListing->setCheckExtension('php');
    $lC_DirectoryListing->setStats(true);
    
    $enabled = '';
    foreach ( $lC_DirectoryListing->getFiles() as $addon ) { 
      $ao = utility::cleanArr($addon);  

      if ($ao['name'] == 'addon.inc.php') continue;
      if ($ao['name'] != 'controller.php') continue;

      $nameArr = explode('/', $ao['path']);
      $class = $nameArr[count($nameArr)-2];

      if (file_exists($ao['path'])) {
        include_once($ao['path']);
        $GLOBALS[$class] = new $class();
        $addon['code'] = substr($ao['name'], 0, strpos($ao['name'], '/'));
        $addon['type'] = $GLOBALS[$class]->getAddonType();
        $addon['title'] = $GLOBALS[$class]->getAddonTitle();
        $addon['description'] = $GLOBALS[$class]->getAddonDescription();
        $addon['rating'] = $GLOBALS[$class]->getAddonRating();
        $addon['author'] = $GLOBALS[$class]->getAddonAuthor();
        $addon['thumbnail'] = $GLOBALS[$class]->getAddonThumbnail();
        $addon['version'] = $GLOBALS[$class]->getAddonVersion();
        $addon['installed'] = $GLOBALS[$class]->isInstalled();
        $addon['enabled'] = $GLOBALS[$class]->isEnabled();
        $addon['valid'] = $GLOBALS[$class]->isValid();        
        
        if ($addon['enabled']) {
          $enabled .= $addon['path'] . ';';
        }
      }
    }
    if ($enabled != '') $enabled = substr($enabled, 0, -1);
    if (!file_exists(DIR_FS_WORK . 'cache/addons.cache')) {
      file_put_contents(DIR_FS_WORK . 'cache/addons.cache', serialize($enabled));
    }    
  }
}
?>