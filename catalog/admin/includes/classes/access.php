<?php
/*
  $Id: access.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

  class lC_Access {
    var $_group = 'misc',
        $_icon = 'configure.png',
        $_title,
        $_sort_order = 0,
        $_subgroups;

    function getUserLevels($id) {
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
      }

      return $modulesPlusAddons;
    }

    function getLevels() {
      global $lC_Language, $lC_Vqmod;

      $access = array();

      foreach ( $_SESSION['admin']['access'] as $module => $level) {
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

    function getModule() {
      return $this->_module;
    }

    function getGroup() {
      return $this->_group;
    }

    function getGroupTitle($group) {
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

    function getIcon() {
      return $this->_icon;
    }

    function getTitle() {
      return $this->_title;
    }

    function getSortOrder() {
      return $this->_sort_order;
    }

    function getSubGroups() {
      return $this->_subgroups;
    }

    function hasAccess($module = null) {
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