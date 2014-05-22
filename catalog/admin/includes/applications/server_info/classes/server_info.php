<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: server_info.php v1.0 2013-08-08 datazen $
*/
global $lC_Vqmod;
include_once($lC_Vqmod->modCheck('includes/applications/store/classes/store.php'));

class lC_Server_info_Admin {
 /*
  * Delete the customer group record
  *
  * @param integer $id The customer group id to delete
  * @access public
  * @return boolean
  */
  public static function updateInstallID($id) {
    global $lC_Database;
    
    $Qchk = $lC_Database->query('select * from :table_configuration where configuration_key = :configuration_key');
    $Qchk->bindTable(':table_configuration', TABLE_CONFIGURATION);
    $Qchk->bindValue(':configuration_key', 'INSTALLATION_ID');
    $Qchk->execute();
    
    if ($Qchk->numberOfRows() > 0) {
      $Qupdate = $lC_Database->query('update :table_configuration set configuration_title = :configuration_title, configuration_key = :configuration_key, configuration_value = :configuration_value, configuration_description = :configuration_description, configuration_group_id = :configuration_group_id, date_added = :date_added where configuration_key = :configuration_key');
      $Qupdate->bindValue(':date_added', date("Y-m-d H:m:s")); 
      $Qupdate->bindValue(':configuration_key', 'INSTALLATION_ID');
    } else {
      $Qupdate = $lC_Database->query('insert into :table_configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, last_modified) values (:configuration_title, :configuration_key, :configuration_value, :configuration_description, :configuration_group_id, :last_modified)');
      $Qupdate->bindValue(':last_modified', date("Y-m-d H:m:s"));   
    }

    $Qupdate->bindTable(':table_configuration', TABLE_CONFIGURATION);
    $Qupdate->bindValue(':configuration_title', 'Installation ID');
    $Qupdate->bindValue(':configuration_key', 'INSTALLATION_ID');
    $Qupdate->bindValue(':configuration_value', $id);
    $Qupdate->bindValue(':configuration_description', 'Installation ID');      
    $Qupdate->bindValue(':configuration_group_id', '6');      
    $Qupdate->execute();  

    $Qchk->freeResult();
    
    lC_Cache::clear('configuration');

    if ( $lC_Database->isError() ) {
      return false;
    } 
    
    return true;
  }
 /*
  * Get the addons list
  *
  * @access public
  * @return string
  */  
  public static function listAddons() {
    $list = '';
    foreach(lC_Store_Admin::getInstalledAddons() as $key => $val) {
      $list .= '<li><strong>' . $val['title'] . '</strong> v' . $val['version'] . '</li>';
    }
    
    return $list;
  }
 /*
  * Check and list the vqmod hooks 
  *
  * @access public
  * @return string
  */  
  public static function checkHooks() {
    global $lC_Language;
    
    $list = '';
    foreach(lC_Store_Admin::getInstalledAddons() as $key => $val) {
      
      $code = $val['code'];
      $title = $val['title'];

      $lC_DirectoryListing = new lC_DirectoryListing(DIR_FS_CATALOG . 'addons/' . $code . '/hooks');
      $lC_DirectoryListing->setRecursive(true);
      $lC_DirectoryListing->setIncludeDirectories(false);
      $lC_DirectoryListing->setAddDirectoryToFilename(true);
      $lC_DirectoryListing->setStats(true);
      $lC_DirectoryListing->setCheckExtension('xml');

      foreach ( $lC_DirectoryListing->getFiles() as $file ) {       
        $status = (self::_cacheFileExists($file['path'], 'catalog') == true) ? $lC_Language->get('cached_file_exists') . '<span class="icon-tick icon-green icon-size2 margin-left"></span>' : $lC_Language->get('cached_file_not_exists') . '<span class="icon-cross icon-red icon-size2 margin-left"></span>';
        $list .= '<li>' . $title . ' => hooks/' . $file['name'] . ' => ' . $status . '</li>';  
      }
      
      $lC_DirectoryListing = new lC_DirectoryListing(DIR_FS_CATALOG . 'addons/' . $code . '/admin/hooks');
      $lC_DirectoryListing->setRecursive(true);
      $lC_DirectoryListing->setIncludeDirectories(false);
      $lC_DirectoryListing->setAddDirectoryToFilename(true);
      $lC_DirectoryListing->setStats(true);
      $lC_DirectoryListing->setCheckExtension('xml');

      foreach ( $lC_DirectoryListing->getFiles() as $file ) { 
        $status = (self::_cacheFileExists($file['path']) == true) ? $lC_Language->get('cached_file_exists') . '<span class="icon-tick icon-green icon-size2 margin-left"></span>' : $lC_Language->get('cached_file_not_exists') . '<span class="icon-cross icon-red icon-size2 margin-left"></span>';
        $list .= '<li>' . $title . ' => admin/hooks/' . $file['name'] . ' => ' . $status . '</li>';  
      }  
    }    
  
    return $list;  
  }
 /*
  * Check if the vqmod hokd file exists in the cache
  *
  * @param string $file The cache file to check
  * @param string $mode Admin or Catalog mode 
  * @access protected
  * @return boolean
  */  
  protected static function _cacheFileExists($file, $mode = 'admin') {
    if (file_exists($file)) {
      $lC_XML = new lC_XML(file_get_contents($file));
      $source = $lC_XML->toArray();
    }
    
    $chkFile = (($mode == 'admin') ? DIR_FS_ADMIN : DIR_FS_CATALOG) . $source['modification']['file attr']['name'];
    $chkFile = 'vq2-' . preg_replace('~[:/\\\\]+~', '_', $chkFile);
    $chkFileA = preg_replace('www', 'public_html', $chkFile);
    $chkFileB = preg_replace('www', 'httpdocs', $chkFile);
    
    $exists = false;
    if (file_exists(DIR_FS_WORK . 'cache/vqmod/' . $chkFile)) {
      $exists = true;
    } else if (file_exists(DIR_FS_WORK . 'cache/vqmod/' . $chkFileA)) {
      $exists = true;
    } else if (file_exists(DIR_FS_WORK . 'cache/vqmod/' . $chkFileB)) {
      $exists = true;
    }

    return $exists;    
  }
}
?>