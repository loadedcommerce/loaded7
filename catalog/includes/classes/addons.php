<?php
/*
  $Id: addons.php v1.0 2011-11-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

  class lC_Addons {
    var $_modules = array(),
        $_selected_module,
        $_quotes = array(),
        $_group = 'addons';

    // class constructor
    function lC_Addons($module = '') {
      global $lC_Database, $lC_Language;

      $Qmodules = $lC_Database->query('select code from :table_templates_boxes where modules_group = "addons"');
      $Qmodules->bindTable(':table_templates_boxes', TABLE_TEMPLATES_BOXES);
      $Qmodules->setCache('modules-addons');
      $Qmodules->execute();

      while ($Qmodules->next()) {
        $this->_modules[] = $Qmodules->value('code');
      }

      $Qmodules->freeResult();

      if (empty($this->_modules) === false) {
        if ((empty($module) === false) && in_array(substr($module, 0, strpos($module, '_')), $this->_modules)) {
          $this->_selected_module = $module;
          $this->_modules = array(substr($module, 0, strpos($module, '_')));
        }

        $lC_Language->load('modules-addons');

        foreach ($this->_modules as $module) {
          $module_class = 'lC_Addons_' . $module;

          if (class_exists($module_class) === false) {
            include('includes/modules/addons/' . $module . '.' . substr(basename(__FILE__), (strrpos(basename(__FILE__), '.')+1)));
          }

          $GLOBALS[$module_class] = new $module_class();
          $GLOBALS[$module_class]->initialize();
        }

        usort($this->_modules, array('lC_Addons', '_usortModules'));
      }
    }

    // class methods
    function getCode() {
      return $this->_code;
    }

    function getTitle() {
      return $this->_title;
    }

    function getDescription() {
      return $this->_description;
    }

    function isEnabled() {
      return $this->_status;
    }

    function getSortOrder() {
      return $this->_sort_order;
    }

    function hasActive() {
      static $has_active;

      if (isset($has_active) === false) {
        $has_active = false;

        foreach ($this->_modules as $module) {
          if ($GLOBALS['lC_Addons_' . $module]->isEnabled()) {
            $has_active = true;
            break;
          }
        }
      }

      return $has_active;
    }

    function _usortModules($a, $b) {
      if ($GLOBALS['lC_Addons_' . $a]->getSortOrder() == $GLOBALS['lC_Addons_' . $b]->getSortOrder()) {
        return strnatcasecmp($GLOBALS['lC_Addons_' . $a]->getTitle(), $GLOBALS['lC_Addons_' . $a]->getTitle());
      }

      return ($GLOBALS['lC_Addons_' . $a]->getSortOrder() < $GLOBALS['lC_Addons_' . $b]->getSortOrder()) ? -1 : 1;
    }
  }
?>