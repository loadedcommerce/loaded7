<?php
/**
  $Id: payment.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
require_once(DIR_FS_CATALOG . 'includes/classes/addons.php');

class lC_Payment {
  var $selected_module;

  var $_modules = array(),
      $_group = 'payment',
      $order_status = DEFAULT_ORDERS_STATUS_ID;

  // class constructor
  public function lC_Payment($module = '') {
    global $lC_Database, $lC_Language, $lC_Vqmod;

    include_once($lC_Vqmod->modCheck(dirname(__FILE__) . '/credit_card.php'));

    $Qmodules = $lC_Database->query("select code, modules_group from :table_templates_boxes where modules_group LIKE '%payment%'");
    $Qmodules->bindTable(':table_templates_boxes', TABLE_TEMPLATES_BOXES);
 //   $Qmodules->setCache('modules-payment');
    $Qmodules->execute();

    while ($Qmodules->next()) {
      if ($Qmodules->value('modules_group') == 'payment') {
        if (!file_exists('includes/modules/payment/' . $Qmodules->value('code') . '.' . substr(basename(__FILE__), (strrpos(basename(__FILE__), '.')+1)))) {
          $this->removeModule($Qmodules->value('code'));
          continue;
        }
        $this->_modules[] = $Qmodules->value('code');
      } else { // addons
        $addon = end(explode("|", $Qmodules->value('modules_group')));
        $module = $Qmodules->value('code'); 
        
        if (!file_exists(DIR_FS_CATALOG . 'addons/' . $addon . '/modules/payment/' . $module . '.php')) {
          $this->removeModule($Qmodules->value('code'));
          continue;
        }
        
        if ($_SESSION['lC_Addons_data'][$addon]['enabled'] == '1') {
          $this->_modules[] = $Qmodules->value('code') . '|' . $addon;        
        }
      }         
    }
    
    $Qmodules->freeResult();

    if (empty($this->_modules) === false) {
      if ((empty($module) === false) && in_array(substr($module, 0, strpos($module, '_')), $this->_modules)) {
        $this->_selected_module = $module;
        $this->_modules = array(substr($module, 0, strpos($module, '_')));
      }

      $lC_Language->load('modules-payment');

      foreach ($this->_modules as $modules) {      
        if (strstr($modules, '|')) {
          $mArr = explode('|', $modules);
          $modules = $mArr[0];
          $addon = $mArr[1];
        }        

        $module_class = 'lC_Payment_' . $modules;
        
        if (class_exists($module_class) === false) {
          if (file_exists('includes/modules/payment/' . $modules . '.' . substr(basename(__FILE__), (strrpos(basename(__FILE__), '.')+1)))) {
            include($lC_Vqmod->modCheck('includes/modules/payment/' . $modules . '.' . substr(basename(__FILE__), (strrpos(basename(__FILE__), '.')+1))));
          } else if (file_exists(DIR_FS_CATALOG . 'addons/' . $addon . '/modules/payment/' . $modules . '.php')) { // addons
            include($lC_Vqmod->modCheck(DIR_FS_CATALOG . 'addons/' . $addon . '/modules/payment/' . $modules . '.php'));
          }
        }
        
        $GLOBALS[$module_class] = new $module_class();
      }

      usort($this->_modules, array('lC_Payment', '_usortModules'));

      if ( (!empty($module)) && (in_array($module, $this->_modules)) && (isset($GLOBALS['lC_Payment_' . $module]->form_action_url)) ) {
        $this->form_action_url = $GLOBALS['lC_Payment_' . $module]->form_action_url;
      }
      
      if ( (!empty($module)) && (in_array($module, $this->_modules)) && (isset($GLOBALS['lC_Payment_' . $module]->iframe_action_url)) ) {
        $this->iframe_action_url = $GLOBALS['lC_Payment_' . $module]->iframe_action_url;
      }   
      
      if ( (!empty($module)) && (in_array($module, $this->_modules)) && (isset($GLOBALS['lC_Payment_' . $module]->iframe_relay_url)) ) {
        $this->iframe_relay_url = $GLOBALS['lC_Payment_' . $module]->iframe_relay_url;
      }              
    }
  }
 
  public function removeModule($code) {
    global $lC_Database;
    
    $Qmd = $lC_Database->query("delete from :table_templates_boxes where code = :code and modules_group LIKE '%payment%'");
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

  public function getMethodTitle() {
    return $this->_method_title;
  }  
  
  public function getJavascriptBlocks() {
  }    

  public function isEnabled() {
    return $this->_status;
  }

  public function getSortOrder() {
    return $this->_sort_order;
  }     

  public function selection() {
    $selection_array = array();

    foreach ($this->_modules as $module) {
      if (strstr($module, '|')) $module = substr($module, 0, strpos($module, '|'));
      if ($GLOBALS['lC_Payment_' . $module]->isEnabled()) {
        $selection = $GLOBALS['lC_Payment_' . $module]->selection();
        if (is_array($selection)) $selection_array[] = $selection;
      }
    }

    return $selection_array;
  }

  public function pre_confirmation_check() {
    if (is_array($this->_modules)) {
      if (isset($GLOBALS[$this->selected_module]) && is_object($GLOBALS[$this->selected_module]) && $GLOBALS[$this->selected_module]->isEnabled()) {
        $GLOBALS[$this->selected_module]->pre_confirmation_check();
      }
    }
  }

  public function confirmation() {
    if (is_array($this->_modules)) {
      if (isset($GLOBALS[$this->selected_module]) && is_object($GLOBALS[$this->selected_module]) && $GLOBALS[$this->selected_module]->isEnabled()) {
        return $GLOBALS[$this->selected_module]->confirmation();
      }
    }
  }

  public function process_button() {
    if (is_array($this->_modules)) {
      if (isset($GLOBALS[$this->selected_module]) && is_object($GLOBALS[$this->selected_module]) && $GLOBALS[$this->selected_module]->isEnabled()) {
        return $GLOBALS[$this->selected_module]->process_button();
      }
    }
  }

  public function process() {
    if (is_array($this->_modules)) {
      if (isset($GLOBALS[$this->selected_module]) && is_object($GLOBALS[$this->selected_module]) && $GLOBALS[$this->selected_module]->isEnabled()) {
        return $GLOBALS[$this->selected_module]->process();
      }
    }
  }

  public function get_error() {
    if (is_array($this->_modules)) {
      if (isset($GLOBALS[$this->selected_module]) && is_object($GLOBALS[$this->selected_module]) && $GLOBALS[$this->selected_module]->isEnabled()) {
        return $GLOBALS[$this->selected_module]->get_error();
      }
    }
  }

  public function hasActionURL() {
    if (is_array($this->_modules)) {
      if (isset($GLOBALS[$this->selected_module]) && is_object($GLOBALS[$this->selected_module]) && $GLOBALS[$this->selected_module]->isEnabled()) {
        if (isset($GLOBALS[$this->selected_module]->form_action_url) && (empty($GLOBALS[$this->selected_module]->form_action_url) === false)) {
          return true;
        }
      }
    }

    return false;
  }

  public function getActionURL() {
    return $GLOBALS[$this->selected_module]->form_action_url;
  }

  public function hasIframeURL() {
    if (is_array($this->_modules)) {
      if (isset($GLOBALS[$this->selected_module]) && is_object($GLOBALS[$this->selected_module]) && $GLOBALS[$this->selected_module]->isEnabled()) {
        if (isset($GLOBALS[$this->selected_module]->iframe_action_url) && (empty($GLOBALS[$this->selected_module]->iframe_action_url) === false)) {
          return true;
          }
        }
      }

      return false;
  }

  public function getIframeURL() {
    return $GLOBALS[$this->selected_module]->iframe_action_url;
  }

  public function hasRelayURL() {
    if (is_array($this->_modules)) {
      if (isset($GLOBALS[$this->selected_module]) && is_object($GLOBALS[$this->selected_module]) && $GLOBALS[$this->selected_module]->isEnabled()) {
        if (isset($GLOBALS[$this->selected_module]->iframe_relay_url) && (empty($GLOBALS[$this->selected_module]->iframe_relay_url) === false)) {
          return true;
        }
      }
    }
    
    return false;
  }

  public function getRelayURL() {
    return $GLOBALS[$this->selected_module]->iframe_relay_url;
  }

  public function hasActive() {
    static $has_active;

    if (isset($has_active) === false) {
      $has_active = false;

      foreach ($this->_modules as $module) {
        if (strstr($module, '|')) $module = substr($module, 0, strpos($module, '|'));
        if ($GLOBALS['lC_Payment_' . $module]->isEnabled()) {
          $has_active = true;
          break;
        }
      }
    }

    return $has_active;
  }
    
  public function numberOfActive() {
    static $active;

    if (isset($active) === false) {
      $active = 0;

      foreach ($this->_modules as $module) {
        if (strstr($module, '|')) $module = substr($module, 0, strpos($module, '|'));
        if ($GLOBALS['lC_Payment_' . $module]->isEnabled()) {
          $active++;
        }
      }
    }

    return $active;
  }
   
  private function _usortModules($a, $b) {
    if (strstr($a, '|')) $a = substr($a, 0, strpos($a, '|'));
    if (strstr($b, '|')) $b = substr($b, 0, strpos($b, '|'));
        
    if ($GLOBALS['lC_Payment_' . $a]->getSortOrder() == $GLOBALS['lC_Payment_' . $b]->getSortOrder()) {
      return strnatcasecmp($GLOBALS['lC_Payment_' . $a]->getTitle(), $GLOBALS['lC_Payment_' . $a]->getTitle());
    }

    return ($GLOBALS['lC_Payment_' . $a]->getSortOrder() < $GLOBALS['lC_Payment_' . $b]->getSortOrder()) ? -1 : 1;
  }
}
?>