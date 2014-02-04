<?php
/**
  @package    admin::classes
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: access.php v1.0 2013-08-08 datazen $
*/
class lC_Access {
  var $_group = 'misc',
      $_icon = 'configure.png',
      $_title,
      $_sort_order = 0,
      $_subgroups;

  public function getUserLevels($id) {
    global $lC_Database;

    $modules = array();

    $Qaccess = $lC_Database->query('select module, level from :table_administrators_access where administrators_groups_id = :administrators_groups_id');
    $Qaccess->bindTable(':table_administrators_access', TABLE_ADMINISTRATORS_ACCESS);
    $Qaccess->bindInt(':administrators_groups_id', $id);
    $Qaccess->execute();

    while ( $Qaccess->next() ) {
      if (strstr($Qaccess->value('module'), '-')) {
        $modules[substr($Qaccess->value('module'), strpos($Qaccess->value('module'), '-')+1)] = $Qaccess->value('level');
      } else {
        $modules[$Qaccess->value('module')] = $Qaccess->value('level');
      }
    }

    if ( array_key_exists('*', $modules) ) {
      $modules = array();

      $lC_DirectoryListing = new lC_DirectoryListing('includes/modules/access');
      $lC_DirectoryListing->setIncludeDirectories(false);

      foreach ($lC_DirectoryListing->getFiles() as $file) {
        $modules[substr($file['name'], 0, strrpos($file['name'], '.'))] = '99';
      } 
      
      $modulesPlusAddons = lC_Addons_Admin::getModulesAccessTopAdmin($modules);
      $modules = $modulesPlusAddons;
    }

    return $modules;
  }

  public function getLevels() {
    global $lC_Language, $lC_Vqmod;

    $access = array();
 
    foreach ( $_SESSION['admin']['access'] as $module => $level) {

      if ($module == 'images') continue; // To hide image manager from Big menu

      if ((int)$level >= 1) { // at least View access
        if ( file_exists('includes/modules/access/' . $module . '.php') ) {
          $module_class = 'lC_Access_' . ucfirst($module);

          if ( !class_exists( $module_class ) ) {
            $lC_Language->loadIniFile('modules/access/' . $module . '.php');
            include($lC_Vqmod->modCheck('includes/modules/access/' . $module . '.php'));
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
        } else if (lC_Addons_Admin::hasModulesAccess($module)) {
          $access = lC_Addons_Admin::getModulesAccess($module, $level, $access);
        }
      }
    }
    
    return $access;
  }

  public function getModule() {
    return $this->_module;
  }

  public function getGroup() {
    return $this->_group;
  }

  public function getGroupTitle($group) {
    global $lC_Language;

    if ( !$lC_Language->isDefined('access_group_' . $group . '_title') ) {
      if (file_exists(DIR_FS_ADMIN . 'includes/languages/' . $lC_Language->getCode() . '/modules/access/groups/' . $group . '.php')) {
        $lC_Language->loadIniFile('modules/access/groups/' . $group . '.php');
      } else {     
        lC_Addons_Admin::loadAccessGroupDefinitions($group);
      }
    }

    return $lC_Language->get('access_group_' . $group . '_title');
  }

  public function getIcon() {
    return $this->_icon;
  }

  public function getTitle() {
    return $this->_title;
  }

  public function getSortOrder() {
    return $this->_sort_order;
  }

  public function getSubGroups() {
    return $this->_subgroups;
  }

  public function hasAccess($module = null) {
    if ( empty($module) ) {
      $module = $this->_module;
    }

    if ($_SESSION['moduleType'] == 'addon') {
      return lC_Addons_Admin::hasAccess($module);
    } else {
      return !@file_exists( 'includes/modules/access/' . $module . '.php' ) ||
              @array_key_exists( $module, $_SESSION['admin']['access'] ) ||
              ( (int)$_SESSION['admin']['access'][$module] >= 1 );
    }

  }
}
?>