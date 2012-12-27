<?php
/*
  $Id: order_total.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

  class lC_OrderTotal {
    var $_modules = array(),
        $_data = array(),
        $_group = 'order_total';

    // class constructor
    function lC_OrderTotal() {
      global $lC_Database, $lC_Language;

      $Qmodules = $lC_Database->query('select code from :table_templates_boxes where modules_group = "order_total"');
      $Qmodules->bindTable(':table_templates_boxes', TABLE_TEMPLATES_BOXES);
      $Qmodules->setCache('modules-order_total');
      $Qmodules->execute();

      while ($Qmodules->next()) {
        $this->_modules[] = $Qmodules->value('code');
      }

      $Qmodules->freeResult();

      $lC_Language->load('modules-order_total');

      foreach ($this->_modules as $module) {
        $module_class = 'lC_OrderTotal_' . $module;

        if (class_exists($module_class) === false) {
          include('includes/modules/order_total/' . $module . '.' . substr(basename(__FILE__), (strrpos(basename(__FILE__), '.')+1)));
        }

        $GLOBALS[$module_class] = new $module_class();
      }

      usort($this->_modules, array('lC_OrderTotal', '_usortModules'));
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

    function &getResult() {
      global $lC_ShoppingCart;

      $this->_data = array();

      foreach ($this->_modules as $module) {
        $module = 'lC_OrderTotal_' . $module;

        if ($GLOBALS[$module]->isEnabled() === true) {
          $GLOBALS[$module]->process();

          foreach ($GLOBALS[$module]->output as $output) {
            if (!empty($output['title']) && !empty($output['text'])) {
              $this->_data[] = array('code' => $GLOBALS[$module]->getCode(),
                                     'title' => $output['title'],
                                     'text' => $output['text'],
                                     'value' => $output['value'],
                                     'sort_order' => $GLOBALS[$module]->getSortOrder());
            }
          }
        }
      }

      return $this->_data;
    }

    function hasActive() {
      static $has_active;

      if (isset($has_active) === false) {
        $has_active = false;

        foreach ($this->_modules as $module) {
          if ($GLOBALS['lC_OrderTotal_' . $module]->isEnabled() === true) {
            $has_active = true;
            break;
          }
        }
      }

      return $has_active;
    }

    function _usortModules($a, $b) {
      if ($GLOBALS['lC_OrderTotal_' . $a]->getSortOrder() == $GLOBALS['lC_OrderTotal_' . $b]->getSortOrder()) {
        return strnatcasecmp($GLOBALS['lC_OrderTotal_' . $a]->getTitle(), $GLOBALS['lC_OrderTotal_' . $a]->getTitle());
      }

      return ($GLOBALS['lC_OrderTotal_' . $a]->getSortOrder() < $GLOBALS['lC_OrderTotal_' . $b]->getSortOrder()) ? -1 : 1;
    }
  }
?>