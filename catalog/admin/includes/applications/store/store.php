<?php
/*
  $Id: store.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
global $lC_Vqmod;

require($lC_Vqmod->modCheck('includes/applications/store/classes/store.php'));

class lC_Application_Store extends lC_Template_Admin {
  /*
  * Protected variables
  */
  protected $_module = 'store',
            $_page_title,
            $_page_contents = 'main.php';
  /*
  * Class constructor
  */
  public function __construct() {
    global $lC_Language;

    $this->_page_title = $lC_Language->get('heading_title');
    
    if (isset($_SESSION['deleteAddon']) && empty($_SESSION['deleteAddon']) === false) {
      $this->_rmdir_r(DIR_FS_CATALOG . 'addons/' . $_SESSION['deleteAddon'] . '/');
      unset($_SESSION['deleteAddon']);
      lC_Cache::clear('modules-addons');
      lC_Cache::clear('configuration');
      lC_Cache::clear('templates');
      lC_Cache::clear('addons');
      lC_Cache::clear('vqmoda');
      lC_Cache::clear('vqmods');      
    }
  }
  
 /**
  * Recursive remove files and directories
  *  
  * @param string $path  The path to delete
  * @access public      
  * @return boolean
  */ 
  protected function _rmdir_r($path) {
    $i = new DirectoryIterator($path);
    foreach($i as $f) {
      if($f->isFile()) {
        @unlink($f->getRealPath());
      } else if(!$f->isDot() && $f->isDir()) {
        $this->_rmdir_r($f->getRealPath());
        @rmdir($f->getRealPath());
      }
    }
    @rmdir($path);
    
    return true;
  }  
}
?>