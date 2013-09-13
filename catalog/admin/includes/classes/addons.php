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
global $lC_Vqmod;

require($lC_Vqmod->modCheck('../includes/classes/addons.php'));

class lC_Addons_Admin extends lC_Addons {

  private static $_data;
  
 // class contructor 
  public function __construct() {
    if (array_key_exists('login', $_GET)) return false;

 //   if ( !isset(self::$_data) ) {
      self::_init();
 //   }
  }
 /*
  * Determine if the admin has addons
  *
  * @param  string $module The addon applications module
  * @access public
  * @return boolean
  */  
  public static function hasAdminModule($module) {
    foreach (self::getAdminAddons('enabled') as $addon => $val) {
      if (file_exists(DIR_FS_CATALOG . 'addons/' . $addon . '/admin/applications/' . $module . '/' . $module . '.php')) {
        return true;
      }
    }
    
    return false;
  } 
 /*
  * Retrieve addon module application path
  *
  * @param  string $modules The addon module
  * @access public
  * @return mixed
  */  
  public static function getAdminModule($module) {
    foreach (self::getAdminAddons('enabled') as $addon => $val) {
      if (file_exists(DIR_FS_CATALOG . 'addons/' . $addon . '/admin/applications/' . $module . '/' . $module . '.php')) {
        return DIR_FS_CATALOG . 'addons/' . $addon . '/admin/applications/' . $module . '/' . $module . '.php';
      }
    }
    
    return false;
  }  
 /*
  * Retrieve addon language definitions path
  *
  * @param  string $modules The addon module
  * @param  string $action  The action file/operation
  * @access public
  * @return array
  */
  public static function getAdminLanguageDefinitionsPath($module) {
    global $lC_Language;
    
    foreach (self::getAdminAddons('enabled') as $addon => $val) { 
      if (file_exists(DIR_FS_CATALOG . 'addons/' . $addon . '/admin/languages/' . $lC_Language->getCode() . '/' . $module . '.php')) {
        return DIR_FS_CATALOG . 'addons/' . $addon . '/admin/languages/' . $lC_Language->getCode() . '/' . $module . '.php';
      }
    }
    
    return false;
  } 
 /*
  * Retrieve addon module actions
  *
  * @param  string $modules The addon module
  * @param  string $action  The action file/operation
  * @access public
  * @return array
  */
  public static function getAdminModuleActions($module, $action) {
    foreach (self::getAdminAddons('enabled') as $addon => $val) { 
      if (file_exists(DIR_FS_CATALOG . 'addons/' . $addon . '/admin/applications/' . $module . '/actions/' . $action . '.php')) {
        return DIR_FS_CATALOG . 'addons/' . $addon . '/admin/applications/' . $module . '/actions/' . $action . '.php';
      }
    }
    
    return false;
  }
 /*
  * Set top admin access to the addon modules
  *
  * @param  array  $modules The addon modules array
  * @access public
  * @return array
  */ 
  public static function getModulesAccessTopAdmin($modules) {
    foreach (self::getAdminAddons('enabled') as $addon => $val) {
      if (is_dir(DIR_FS_CATALOG . 'addons/' . $addon . '/admin/modules/access')) {
        $lC_DirectoryListing = new lC_DirectoryListing(DIR_FS_CATALOG . 'addons/' . $addon . '/admin/modules/access');
        $lC_DirectoryListing->setIncludeDirectories(false);
        foreach ($lC_DirectoryListing->getFiles() as $file) {
          $modules[substr($file['name'], 0, strrpos($file['name'], '.'))] = '99';
        }     
      }
    } 
    
    return $modules;
  }
 /*
  * Determine access to the addon module
  *
  * @param string $module The addon module
  * @access public
  * @return boolean
  */ 
  public static function hasModulesAccess($module) {
    foreach (self::getAdminAddons('enabled') as $addon => $val) {
      if ( file_exists(DIR_FS_CATALOG . 'addons/' . $addon . '/admin/modules/access/' . $module . '.php') ) {
        return true;
      }
    }
    
    return false;
  }  
 /*
  * Retrieve the addon module access
  *
  * @param string $module The addon module
  * @param string $level  The access level
  * @param string $access The access array
  * @access public
  * @return array
  */
  public static function getModulesAccess($module, $level, $access) {
    global $lC_Language, $lC_Vqmod;
    
    foreach (self::getAdminAddons('enabled') as $addon => $val) {
      if ( file_exists(DIR_FS_CATALOG . 'addons/' . $addon . '/admin/modules/access/' . $module . '.php') ) {

        $module_class = 'lC_Access_' . ucfirst($module);

        if ( !class_exists( $module_class ) ) {
          $lC_Language->loadIniFile(DIR_FS_CATALOG . 'addons/' . $addon . '/admin/languages/' . $lC_Language->getCode() . '/modules/access/' . $module . '.php', null, null, true);
          include($lC_Vqmod->modCheck(DIR_FS_CATALOG . 'addons/' . $addon . '/admin/modules/access/' . $module . '.php'));
        }

        $module_class = new $module_class();

        $data = array('module' => $module,
                      'icon' => $module_class->getIcon(),
                      'title' => $module_class->getTitle(),
                      'subgroups' => $module_class->getSubGroups());

        if ( !isset( $access[$module_class->getGroup()][$module_class->getSortOrder()] ) ) {
          $access[$module_class->getGroup()][$module_class->getSortOrder()] = $data;
        } else {
          $access[$module_class->getGroup()][] = $data;
        }    
      }
    }
    
    return $access;
  }
 /*
  * Retrieve the access group definitions
  *
  * @param string $group  The addon group
  * @access public
  * @return void
  */
  public static function loadAccessGroupDefinitions($group) {
    global $lC_Language;
    
    foreach (self::getAdminAddons('enabled') as $addon => $val) {
      if (file_exists(DIR_FS_CATALOG . 'addons/' . $addon . '/admin/languages/' . $lC_Language->getCode() . '/modules/access/groups/' . $group . '.php')) {
        $lC_Language->loadIniFile(DIR_FS_CATALOG . 'addons/' . $addon . '/admin/languages/' . $lC_Language->getCode() . '/modules/access/groups/' . $group . '.php', null, null, true);
      }
    }
  }
 /*
  * Determine access to the addon page
  *
  * @param string $module   The addon module
  * @access public
  * @return boolean
  */ 
  public static function hasAccess($module) {
    foreach (self::getAdminAddons('enabled') as $addon => $val) {
      if (@file_exists(DIR_FS_CATALOG . 'addons/' . $addon . '/admin/modules/access/' . $module . '.php')) {
        if (@array_key_exists( $module, $_SESSION['admin']['access'] ) || ((int)$_SESSION['admin']['access'][$module] >= 1 )) {
          return true;
        }          
      } 
    }
    
    return false;
  }
 /*
  * Retrieve the admin addon page
  *
  * @param string $module   The addon module
  * @param string $filename The page filename
  * @access public
  * @return mixed
  */  
  public static function getAdminPage($module, $filename) {
    foreach (self::getAdminAddons('enabled') as $addon => $val) {
      if (file_exists(DIR_FS_CATALOG . 'addons/' . $addon . '/admin/applications/' . $module . '/pages/' . $filename)) {
        return DIR_FS_CATALOG . 'addons/' . $addon . '/admin/applications/' . $module . '/pages/' . $filename;
      }
    }
    
    return false;    
  } 
 /*
  * Retrieve the admin addons
  *
  * @param string $key  The enabled key
  * @access public
  * @return array
  */   
  public static function getAdminAddons($key = null) {
    global $lC_Language;
    
    if (!is_array(self::$_data)) {
      self::_init();
    }
    
    if ($key == 'enabled') {
      $dArr = array();
      foreach(self::$_data as $ao => $aoData) {
        if ($aoData['enabled'] == true) {
          $dArr[$ao] = $aoData;
        }
      } 
      $data = $dArr;
    } else {
      $data = self::$_data; 
    }
    
    return $data;
  }  
 /*
  * Initialize the addons data array
  *
  * @access private
  * @return void
  */ 
  private static function _init() {
    global $lC_Vqmod, $lC_Language;
    
    $lC_DirectoryListing = new lC_DirectoryListing(DIR_FS_CATALOG . 'addons');
    $lC_DirectoryListing->setRecursive(true);
    $lC_DirectoryListing->setIncludeDirectories(false);
    $lC_DirectoryListing->setCheckExtension('php');
    $lC_DirectoryListing->setStats(true);
      
    $enabled = '';
    foreach ( $lC_DirectoryListing->getFiles() as $addon ) { 
      
      $ao = utility::cleanArr($addon);  
      if ($ao['name'] != 'controller.php') continue;
      
      $nameArr = explode('/', $ao['path']);
      $class = $nameArr[count($nameArr)-2];

      if (file_exists($ao['path'])) {
        if (class_exists($class)) { 
        } else {
          include_once($lC_Vqmod->modCheck($ao['path']));
          $GLOBALS[$class] = new $class();
        }         
        
        $_SESSION['lC_Addons_Admin_data'][$class] = array('type' => $GLOBALS[$class]->getAddonType(),
                                                          'title' => self::_getLanguageDefinition($GLOBALS[$class]->getAddonTitle(), $class),
                                                          'description' => self::_getLanguageDefinition($GLOBALS[$class]->getAddonDescription(), $class),
                                                          'rating' => $GLOBALS[$class]->getAddonRating(),
                                                          'author' => $GLOBALS[$class]->getAddonAuthor(),
                                                          'authorWWW' => $GLOBALS[$class]->getAddonAuthorWWW(),
                                                          'thumbnail' => $GLOBALS[$class]->getAddonThumbnail(),
                                                          'version' => $GLOBALS[$class]->getAddonVersion(),
                                                          'compatibility' => $GLOBALS[$class]->getCompatibility(),
                                                          'installed' => $GLOBALS[$class]->isInstalled(),
                                                          'mobile' => $GLOBALS[$class]->isMobileEnabled(),
                                                          'enabled' => $GLOBALS[$class]->isEnabled());         
        
        if ($GLOBALS[$class]->isEnabled()) $enabled .= $addon['path'] . ';';
      }
    }   
       
    if ($enabled != '') $enabled = substr($enabled, 0, -1);
    if (!file_exists(DIR_FS_WORK . 'cache/addons.cache')) {
      file_put_contents(DIR_FS_WORK . 'cache/addons.cache', serialize($enabled));
    }
     
    self::$_data = $_SESSION['lC_Addons_Admin_data'];
  } 
 /*
  * Retrieve a addon language definition value
  *
  * @param string $key    The language key
  * @param string $class  The addon class
  * @access private
  * @return string
  */   
  private static function _getLanguageDefinition($key, $class) {
    global $lC_Language;

    $langValue = '';
    if (file_exists(DIR_FS_CATALOG . 'addons/' . $class . '/languages/' . $lC_Language->getCode() . '.xml')) {
      $lC_XML = new lC_XML(file_get_contents(DIR_FS_CATALOG . 'addons/' . $class . '/languages/' . $lC_Language->getCode() . '.xml'));
      $definitions = $lC_XML->toArray();

      foreach ($definitions['language']['definitions']['definition'] as $def) {
        if ($def['key'] == $key) {
          $langValue = $def['value'];
          break;
        }
      }
      
      return $langValue;
    }    
  }   
}
?>