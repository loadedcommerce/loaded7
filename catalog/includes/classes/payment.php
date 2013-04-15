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
class lC_Payment {
  var $selected_module;

  var $_modules = array(),
      $_group = 'payment',
      $order_status = DEFAULT_ORDERS_STATUS_ID;

  // class constructor
  function lC_Payment($module = '') {
    global $lC_Database, $lC_Language, $lC_Vqmod;

    include_once($lC_Vqmod->modCheck(dirname(__FILE__) . '/credit_card.php'));

    $Qmodules = $lC_Database->query('select code from :table_templates_boxes where modules_group = "payment"');
    $Qmodules->bindTable(':table_templates_boxes', TABLE_TEMPLATES_BOXES);
    $Qmodules->setCache('modules-payment');
    $Qmodules->execute();

    while ($Qmodules->next()) {
      if (!file_exists('includes/modules/payment/' . $Qmodules->value('code') . '.' . substr(basename(__FILE__), (strrpos(basename(__FILE__), '.')+1)))) {
        $this->removeModule($Qmodules->value('code'));
        continue;
      }
      $this->_modules[] = $Qmodules->value('code');
    }

    $Qmodules->freeResult();

    if (empty($this->_modules) === false) {
      if ((empty($module) === false) && in_array($module, $this->_modules)) {
        $this->_modules = array($module);
        $this->selected_module = 'lC_Payment_' . $module;
      }

      $lC_Language->load('modules-payment');

      foreach ($this->_modules as $modules) {
        
        include($lC_Vqmod->modCheck('includes/modules/payment/' . $modules . '.' . substr(basename(__FILE__), (strrpos(basename(__FILE__), '.')+1))));

        $module_class = 'lC_Payment_' . $modules;

        $GLOBALS[$module_class] = new $module_class();
      }

      usort($this->_modules, array('lC_Payment', '_usortModules'));

        if ( (!empty($module)) && (in_array($module, $this->_modules)) && (isset($GLOBALS['lC_Payment_' . $module]->form_action_url)) ) {
          $this->form_action_url = $GLOBALS['lC_Payment_' . $module]->form_action_url;
        }
        
        if ( (!empty($module)) && (in_array($module, $this->_modules)) && (isset($GLOBALS['lC_Payment_' . $module]->iframe_action_url)) ) {
          $this->iframe_action_url = $GLOBALS['lC_Payment_' . $module]->iframe_action_url;
        }        
      }
    }
 
  function removeModule($code) {
    global $lC_Database;
    
    $Qmd = $lC_Database->query('delete from :table_templates_boxes where code = :code and modules_group = "payment"');
    $Qmd->bindTable(':table_templates_boxes', TABLE_TEMPLATES_BOXES);
    $Qmd->bindValue(':code', $code);
    $Qmd->execute();    
  }  

  function getCode() {
    return $this->_code;
  }

  function getTitle() {
    return $this->_title;
  }

  function getDescription() {
    return $this->_description;
  }

  function getMethodTitle() {
    return $this->_method_title;
  }      

  function isEnabled() {
    return $this->_status;
  }

  function getSortOrder() {
    return $this->_sort_order;
  }     

  function getJavascriptBlock() {
  }

  function getJavascriptBlocks() {
    global $lC_Language;

    $js = '';
    if (is_array($this->_modules)) {
      $js = '<script type="text/javascript"><!-- ' . "\n" .
            'function check_form() {' . "\n" .
            '  var error = 0;' . "\n" .
            '  var error_message = "' . $lC_Language->get('js_error') . '";' . "\n" .
            '  var payment_value = null;' . "\n" .
            '  if (document.checkout_payment.payment_method.length) {' . "\n" .
            '    for (var i=0; i<document.checkout_payment.payment_method.length; i++) {' . "\n" .
            '      if (document.checkout_payment.payment_method[i].checked) {' . "\n" .
            '        payment_value = document.checkout_payment.payment_method[i].value;' . "\n" .
            '      }' . "\n" .
            '    }' . "\n" .
            '  } else if (document.checkout_payment.payment_method.checked) {' . "\n" .
            '    payment_value = document.checkout_payment.payment_method.value;' . "\n" .
            '  } else if (document.checkout_payment.payment_method.value) {' . "\n" .
            '    payment_value = document.checkout_payment.payment_method.value;' . "\n" .
            '  }' . "\n\n";

      foreach ($this->_modules as $module) {
        if ($GLOBALS['lC_Payment_' . $module]->isEnabled()) {
          $js .= $GLOBALS['lC_Payment_' . $module]->getJavascriptBlock();
        }
      }

      $js .= "\n" . '  if (payment_value == null) {' . "\n" .
             '    error_message = error_message + "' . $lC_Language->get('js_no_payment_module_selected') . '\n";' . "\n" .
             '    error = 1;' . "\n" .
             '  }' . "\n\n" .
             '  if (error == 1) {' . "\n" .
             '    alert(error_message);' . "\n" .
             '    return false;' . "\n" .
             '  } else {' . "\n" .
             '    return true;' . "\n" .
             '  }' . "\n" .
             '}' . "\n" .
             '//--></script>' . "\n";
    }

    return $js;
  }

  function selection() {
    $selection_array = array();

    foreach ($this->_modules as $module) {
      if ($GLOBALS['lC_Payment_' . $module]->isEnabled()) {
        $selection = $GLOBALS['lC_Payment_' . $module]->selection();
        if (is_array($selection)) $selection_array[] = $selection;
      }
    }

    return $selection_array;
  }

  function pre_confirmation_check() {
    if (is_array($this->_modules)) {
      if (isset($GLOBALS[$this->selected_module]) && is_object($GLOBALS[$this->selected_module]) && $GLOBALS[$this->selected_module]->isEnabled()) {
        $GLOBALS[$this->selected_module]->pre_confirmation_check();
      }
    }
  }

  function confirmation() {
    if (is_array($this->_modules)) {
      if (isset($GLOBALS[$this->selected_module]) && is_object($GLOBALS[$this->selected_module]) && $GLOBALS[$this->selected_module]->isEnabled()) {
        return $GLOBALS[$this->selected_module]->confirmation();
      }
    }
  }

  function process_button() {
    if (is_array($this->_modules)) {
      if (isset($GLOBALS[$this->selected_module]) && is_object($GLOBALS[$this->selected_module]) && $GLOBALS[$this->selected_module]->isEnabled()) {
        return $GLOBALS[$this->selected_module]->process_button();
      }
    }
  }

  function process() {
    if (is_array($this->_modules)) {
      if (isset($GLOBALS[$this->selected_module]) && is_object($GLOBALS[$this->selected_module]) && $GLOBALS[$this->selected_module]->isEnabled()) {
        return $GLOBALS[$this->selected_module]->process();
      }
    }
  }

  function get_error() {
    if (is_array($this->_modules)) {
      if (isset($GLOBALS[$this->selected_module]) && is_object($GLOBALS[$this->selected_module]) && $GLOBALS[$this->selected_module]->isEnabled()) {
        return $GLOBALS[$this->selected_module]->get_error();
      }
    }
  }

  function hasActionURL() {
    if (is_array($this->_modules)) {
      if (isset($GLOBALS[$this->selected_module]) && is_object($GLOBALS[$this->selected_module]) && $GLOBALS[$this->selected_module]->isEnabled()) {
        if (isset($GLOBALS[$this->selected_module]->form_action_url) && (empty($GLOBALS[$this->selected_module]->form_action_url) === false)) {
          return true;
        }
      }
    }

    return false;
  }

  function getActionURL() {
    return $GLOBALS[$this->selected_module]->form_action_url;
  }

  function hasActive() {
    static $has_active;

    if (isset($has_active) === false) {
      $has_active = false;

      foreach ($this->_modules as $module) {
        if ($GLOBALS['lC_Payment_' . $module]->isEnabled()) {
          $has_active = true;
          break;
        }
      }
    }

    return $has_active;
  }

    function hasIframeURL() {
      if (is_array($this->_modules)) {
        if (isset($GLOBALS[$this->selected_module]) && is_object($GLOBALS[$this->selected_module]) && $GLOBALS[$this->selected_module]->isEnabled()) {
          if (isset($GLOBALS[$this->selected_module]->iframe_action_url) && (empty($GLOBALS[$this->selected_module]->iframe_action_url) === false)) {
            return true;
          }
        }
      }

      return false;
    }

    function getIframeURL() {
      return $GLOBALS[$this->selected_module]->iframe_action_url;
    }

    function numberOfActive() {
      static $active;

    if (isset($active) === false) {
      $active = 0;

      foreach ($this->_modules as $module) {
        if ($GLOBALS['lC_Payment_' . $module]->isEnabled()) {
          $active++;
        }
      }
    }

    return $active;
  }

  function _usortModules($a, $b) {
    if ($GLOBALS['lC_Payment_' . $a]->getSortOrder() == $GLOBALS['lC_Payment_' . $b]->getSortOrder()) {
      return strnatcasecmp($GLOBALS['lC_Payment_' . $a]->getTitle(), $GLOBALS['lC_Payment_' . $a]->getTitle());
    }

    return ($GLOBALS['lC_Payment_' . $a]->getSortOrder() < $GLOBALS['lC_Payment_' . $b]->getSortOrder()) ? -1 : 1;
  }
}
?>