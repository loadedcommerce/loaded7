<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: store.php v1.0 2013-08-08 datazen $
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