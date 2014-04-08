<?php
/**
  @package    catalog::classes
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: order_total.php v1.0 2013-08-08 datazen $
*/
class lC_OrderTotal {
  var $_modules = array(),
      $_data = array(),
      $_group = 'order_total';

  // class constructor
  public function lC_OrderTotal() {
    global $lC_Database, $lC_Language, $lC_Vqmod;

    $Qmodules = $lC_Database->query('select code from :table_templates_boxes where modules_group = "order_total"');
    $Qmodules->bindTable(':table_templates_boxes', TABLE_TEMPLATES_BOXES);
    $Qmodules->setCache('modules-order_total');
    $Qmodules->execute();

    while ($Qmodules->next()) {
      if (!file_exists('includes/modules/order_total/' . $Qmodules->value('code') . '.' . substr(basename(__FILE__), (strrpos(basename(__FILE__), '.')+1)))) {
        $this->removeModule($Qmodules->value('code'));
        continue;
      }      
      $this->_modules[] = $Qmodules->value('code');
    }
    
    $Qmodules->freeResult();

    $lC_Language->load('modules-order_total');

    foreach ($this->_modules as $module) {
      $module_class = 'lC_OrderTotal_' . $module; 

      if (class_exists($module_class) === false) {
        include($lC_Vqmod->modCheck('includes/modules/order_total/' . $module . '.' . substr(basename(__FILE__), (strrpos(basename(__FILE__), '.')+1))));
      }

      $GLOBALS[$module_class] = new $module_class();
    }

    usort($this->_modules, array('lC_OrderTotal', '_usortModules'));
  }

  // class methods
  public function removeModule($code) {
    global $lC_Database;
    
    $Qmd = $lC_Database->query('delete from :table_templates_boxes where code = :code and modules_group = "order_total"');
    $Qmd->bindTable(':table_templates_boxes', TABLE_TEMPLATES_BOXES);
    $Qmd->bindValue(':code', $code);
    $Qmd->execute();    
  }
    
  public function getCode() {
    return $this->_code;
  }

  public function getTitle() {
    return $this->_title;
  }

  public function getDescription() {
    return $this->_description;
  }

  public function isEnabled() {
    return $this->_status;
  }

  public function getSortOrder() {
    return $this->_sort_order;
  }

  public function &getResult() {
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

  public function hasActive() {
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

  private function _usortModules($a, $b) {
    if ($GLOBALS['lC_OrderTotal_' . $a]->getSortOrder() == $GLOBALS['lC_OrderTotal_' . $b]->getSortOrder()) {
      return strnatcasecmp($GLOBALS['lC_OrderTotal_' . $a]->getTitle(), $GLOBALS['lC_OrderTotal_' . $a]->getTitle());
    }

    return ($GLOBALS['lC_OrderTotal_' . $a]->getSortOrder() < $GLOBALS['lC_OrderTotal_' . $b]->getSortOrder()) ? -1 : 1;
  }
}
?>