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

require_once($lC_Vqmod->modCheck('../includes/classes/addons.php'));

class lC_Addons_Admin extends lC_Addons {

  private static $_data;
  
 // class contructor 
  public function __construct() {
    if (array_key_exists('login', $_GET)) return false;

    if ( !isset($_SESSION['lC_Addons_Admin_data']) ) {
      self::_init();
    }
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
  * Determine if the module has actions
  *
  * @param string $module The addon module
  * @param string $action The action to perform
  * @access public
  * @return boolean
  */ 
  public static function hasAdminModuleActions($module, $action) {
    foreach (self::getAdminAddons('enabled') as $addon => $val) {
      if ( file_exists(DIR_FS_CATALOG . 'addons/' . $addon . '/admin/applications/' . $module . '/actions/' . $action . '.php') ) {
        return true;
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
  public static function getAdminModuleActionsPath($module, $action) {
    foreach (self::getAdminAddons('enabled') as $addon => $val) { 
      if (file_exists(DIR_FS_CATALOG . 'addons/' . $addon . '/admin/applications/' . $module . '/actions/' . $action . '.php')) {
        return DIR_FS_CATALOG . 'addons/' . $addon . '/admin/applications/' . $module . '/actions/' . $action . '.php';
      }
    }
    
    return false;
  }
 /*
  * Determine if the module has modals
  *
  * @param string $module The addon module
  * @access public
  * @return boolean
  */ 
  public static function hasAdminModuleModals($module) {
    foreach (self::getAdminAddons('enabled') as $addon => $val) {
      if ( is_dir(DIR_FS_CATALOG . 'addons/' . $addon . '/admin/applications/' . $module . '/modal') ) {
        if ( ($files = @scandir(DIR_FS_CATALOG . 'addons/' . $addon . '/admin/applications/' . $module . '/modal')) && (count($files) > 2) ) {
          return true;
        }
      }
    }
    
    return false;
  }  
 /*
  * Retrieve addon module modals
  *
  * @param  string $modules The addon module
  * @access public
  * @return array
  */
  public static function loadAdminModuleModals($module) {
    global $lC_Vqmod;
    
    foreach (self::getAdminAddons('enabled') as $addon => $val) {
      if ( is_dir(DIR_FS_CATALOG . 'addons/' . $addon . '/admin/applications/' . $module . '/modal') ) {
        if ( ($files = @scandir(DIR_FS_CATALOG . 'addons/' . $addon . '/admin/applications/' . $module . '/modal')) && (count($files) > 2) ) {
          $modalPath = DIR_FS_CATALOG . 'addons/' . $addon . '/admin/applications/' . $module . '/modal';
          $pattern = '/(\w*)\.php$/';
          $dir = opendir($modalPath);
          while( $file = readdir( $dir ) ) {
            if ($file == '.'  || $file == '..') continue;
            $match = array();
            if ( preg_match($pattern, $file, $match) > 0 ) {
              if (file_exists($modalPath . '/' . $match[0])) {
                include($lC_Vqmod->modCheck($modalPath . '/' . $match[0]));
              }
            }
          }    
          return true;
        }
      }
    }
    
    return false;
  }  
 /*
  * Determine if the module has page script
  *
  * @param string $module The addon module
  * @access public
  * @return boolean
  */ 
  public static function hasAdminModulePageScript($module) {
    foreach (self::getAdminAddons('enabled') as $addon => $val) {
      if ( file_exists(DIR_FS_CATALOG . 'addons/' . $addon . '/admin/applications/' . $module . '/js/' . $module . '.js.php') ) {
        return true;
      }
    }
    
    return false;
  }   
 /*
  * Retrieve module has page script path
  *
  * @param string $module The addon module
  * @access public
  * @return mixed
  */ 
  public static function getAdminModulePageScriptPath($module) {
    foreach (self::getAdminAddons('enabled') as $addon => $val) {
      if ( file_exists(DIR_FS_CATALOG . 'addons/' . $addon . '/admin/applications/' . $module . '/js/' . $module . '.js.php') ) {
        return DIR_FS_CATALOG . 'addons/' . $addon . '/admin/applications/' . $module . '/js/' . $module . '.js.php';
      }
    }
    
    return false;
  }
  
 /*
  * Determine if the module has responsive page script
  *
  * @param string $module The addon module
  * @access public
  * @return boolean
  */ 
  public static function hasAdminModulePageResponsiveScript($module) {
    foreach (self::getAdminAddons('enabled') as $addon => $val) {
      if ( file_exists(DIR_FS_CATALOG . 'addons/' . $addon . '/admin/applications/' . $module . '/js/responsive.js.php') ) {
        return true;
      }
    }
    
    return false;
  }   
 /*
  * Retrieve module has page responsive script path
  *
  * @param string $module The addon module
  * @access public
  * @return mixed
  */ 
  public static function getAdminModulePageResponsiveScriptPath($module) {
    foreach (self::getAdminAddons('enabled') as $addon => $val) {
      if ( file_exists(DIR_FS_CATALOG . 'addons/' . $addon . '/admin/applications/' . $module . '/js/responsive.js.php') ) {
        return DIR_FS_CATALOG . 'addons/' . $addon . '/admin/applications/' . $module . '/js/responsive.js.php';
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
  * Determine if there are addon pages
  *
  * @param string $module   The addon module
  * @param string $filename The page filename
  * @access public
  * @return mixed
  */  
  public static function hasAdminPage($module, $filename) {
    
    foreach (self::getAdminAddons('enabled') as $addon => $val) {
      if (file_exists(DIR_FS_CATALOG . 'addons/' . $addon . '/admin/applications/' . $module . '/pages/' . $filename)) {
        return true;
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
  * Retrieve the admin addon product attribute files
  *
  * @access public
  * @return array
  */ 
  public static function getAdminAddonsProductAttributesFiles() {
    $files = array();
    foreach (self::getAdminAddons('enabled') as $addon => $val) {
      if (is_dir(DIR_FS_CATALOG . 'addons/' . $addon . '/admin/modules/product_attributes')) {
        $lC_DirectoryListing = new lC_DirectoryListing(DIR_FS_CATALOG . 'addons/' . $addon . '/admin/modules/product_attributes');
        $lC_DirectoryListing->setIncludeDirectories(false);
        $lC_DirectoryListing->setStats(true);

        $files = array_merge((array)$files, (array)$lC_DirectoryListing->getFiles());
      }
    }
    
    return $files;    
  } 
 /*
  * Retrieve the product attributes definitions
  *
  * @param string $class The addon class name
  * @access public
  * @return void
  */
  public function loadAdminAddonsProductAttributesDefinitions($class) {
    global $lC_Language;
    
    foreach (self::getAdminAddons('enabled') as $addon => $val) {
      if (file_exists(DIR_FS_CATALOG . 'addons/' . $addon . '/admin/languages/' . $lC_Language->getCode() . '/modules/product_attributes/' . $class . '.php')) {
        $lC_Language->loadIniFile(DIR_FS_CATALOG . 'addons/' . $addon . '/admin/languages/' . $lC_Language->getCode() . '/modules/product_attributes/' . $class . '.php', null, null, true);
      }
    }    
  }
 /*
  * Determine if there are addon attribute module
  *
  * @param string $module   The addon module
  * @param string $filename The page filename
  * @access public
  * @return mixed
  */  
  public static function hasAdminAddonsProductAttributesModule($module) {
    foreach (self::getAdminAddons('enabled') as $addon => $val) {
      if (file_exists(DIR_FS_CATALOG . 'addons/' . $addon . '/admin/modules/product_attributes/' . $module . '.php')) {
        return true;
      }
    }
    
    return false;    
  }  
 /*
  * Retrieve the admin addon attribute module
  *
  * @param string $module   The addon module
  * @param string $filename The page filename
  * @access public
  * @return mixed
  */  
  public static function getAdminAddonsProductAttributesModulePath($module) {
    foreach (self::getAdminAddons('enabled') as $addon => $val) {
      if (file_exists(DIR_FS_CATALOG . 'addons/' . $addon . '/admin/modules/product_attributes/' . $module . '.php')) {
        return DIR_FS_CATALOG . 'addons/' . $addon . '/admin/modules/product_attributes/' . $module . '.php';
      }
    }
    
    return false;    
  }  
 /*
  * Install the product attributes addon
  *
  * @param string $class The addon class name
  * @access public
  * @return boolean
  */
  public function installAdminAddonsProductAttributesModule($class) {
    global $lC_Language, $lC_Vqmod;
    
    $result = array();
    foreach (self::getAdminAddons('enabled') as $addon => $val) {
      if ( file_exists(DIR_FS_CATALOG . 'addons/' . $addon . '/admin/modules/product_attributes/' . $class . '.php') ) {
        include($lC_Vqmod->modCheck(DIR_FS_CATALOG . 'addons/' . $addon . '/admin/modules/product_attributes/' . $class . '.php'));
        if ( class_exists('lC_ProductAttributes_' . $class) ) {
          $module = 'lC_ProductAttributes_' . $class;
          $module = new $module();
          
          self::loadAdminAddonsProductAttributesDefinitions($class);
          
          if ( $module->installModule($lC_Language->get('product_attributes_' . $module->getCode() . '_title'), $module->getCode()) ) {
          } else {
            $result['rpcStatus'] = -1;
          }
        }
      }      
    }    
    
    return $result;
  }  
 /*
  * Uninstall the product attributes addon
  *
  * @param string $class The addon class name
  * @access public
  * @return boolean
  */
  public function uninstallAdminAddonsProductAttributesModule($class) {
    global $lC_Language, $lC_Vqmod;
    
    $result = array();
    foreach (self::getAdminAddons('enabled') as $addon => $val) {
      if ( file_exists(DIR_FS_CATALOG . 'addons/' . $addon . '/admin/modules/product_attributes/' . $class . '.php') ) {
        include($lC_Vqmod->modCheck(DIR_FS_CATALOG . 'addons/' . $addon . '/admin/modules/product_attributes/' . $class . '.php'));
        if ( class_exists('lC_ProductAttributes_' . $class) ) {
          $module = 'lC_ProductAttributes_' . $class;
          $module = new $module();
          
          if ( $module->removeModule($module->getID(), $module->getCode()) ) {
          } else {
            $result['rpcStatus'] = -1;
          }
        }
      }      
    }    
    
    return $result;
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
        
        if ($GLOBALS[$class]->isAutoInstall()) {
          if (defined('ADDONS_' . strtoupper($GLOBALS[$class]->getAddonType()) . '_' . strtoupper($class) . '_STATUS')) {
            $isInstalled = $GLOBALS[$class]->isInstalled();
            $isEnabled = $GLOBALS[$class]->isEnabled();            
          } else {
            if (class_exists('lC_Store_Admin')) { 
            } else {
              include_once($lC_Vqmod->modCheck('includes/applications/store/classes/store.php'));
            }            
            
            lC_Store_Admin::install($class);
            $isInstalled = true;  
            $isEnabled = true;
          }
        } else {
          $isInstalled = $GLOBALS[$class]->isInstalled();
          $isEnabled = $GLOBALS[$class]->isEnabled();
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
                                                          'installed' => $isInstalled,
                                                          'mobile' => $GLOBALS[$class]->isMobileEnabled(),
                                                          'auto_install' => $GLOBALS[$class]->isAutoInstall(),
                                                          'enabled' => $isEnabled);         
        
        if ($isEnabled) $enabled .= $addon['path'] . ';';
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