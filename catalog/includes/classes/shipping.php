<?php
/**
  @package    catalog::classes
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: shipping.php v1.0 2013-08-08 datazen $
*/    
require_once(DIR_FS_CATALOG . 'includes/classes/addons.php');

class lC_Shipping {
  
  var $_modules = array(),
      $_selected_module,
      $_quotes = array(),
      $_group = 'shipping';

  // class constructor
  public function lC_Shipping($module = '') {
    global $lC_Database, $lC_Language, $lC_Addons, $lC_Vqmod;

    $this->_quotes =& $_SESSION['lC_ShoppingCart_data']['shipping_quotes'];

    $Qmodules = $lC_Database->query("select code, modules_group from :table_templates_boxes where modules_group LIKE '%shipping%'");
    $Qmodules->bindTable(':table_templates_boxes', TABLE_TEMPLATES_BOXES);
    //$Qmodules->setCache('modules-shipping');
    $Qmodules->execute();

    while ($Qmodules->next()) {
      
      if ($Qmodules->value('modules_group') == 'shipping') {
        if (!file_exists('includes/modules/shipping/' . $Qmodules->value('code') . '.' . substr(basename(__FILE__), (strrpos(basename(__FILE__), '.')+1)))) {
          $this->removeModule($Qmodules->value('code'));
          continue;
        }
        $this->_modules[] = $Qmodules->value('code');
      } else { // addons
        $addon = end(explode("|", $Qmodules->value('modules_group')));
        $module = $Qmodules->value('code'); 
        
        if (!file_exists(DIR_FS_CATALOG . 'addons/' . $addon . '/modules/shipping/' . $module . '.php')) {
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

      $lC_Language->load('modules-shipping');

      foreach ($this->_modules as $module) {
        if (strstr($module, '|')) {
          $mArr = explode('|', $module);
          $module = $mArr[0];
          $addon = $mArr[1];
        }

        $module_class = 'lC_Shipping_' . $module;

        if (class_exists($module_class) === false) {
          if (file_exists('includes/modules/shipping/' . $module . '.' . substr(basename(__FILE__), (strrpos(basename(__FILE__), '.')+1)))) {
            include($lC_Vqmod->modCheck('includes/modules/shipping/' . $module . '.' . substr(basename(__FILE__), (strrpos(basename(__FILE__), '.')+1))));
          } else if (file_exists(DIR_FS_CATALOG . 'addons/' . $addon . '/modules/shipping/' . $module . '.php')) { // addons
            include($lC_Vqmod->modCheck(DIR_FS_CATALOG . 'addons/' . $addon . '/modules/shipping/' . $module . '.php'));
          }
        }
        
        $GLOBALS[$module_class] = new $module_class();
        $GLOBALS[$module_class]->initialize();
      }

      usort($this->_modules, array('lC_Shipping', '_usortModules'));
    } 
    
    if (empty($_GET) === false) {
      $first_array = array_slice($_GET, 0, 1);
      $_module = lc_sanitize_string(basename(key($first_array)));
    }

    if ( empty($this->_quotes) || $_module == 'shipping') {
      $this->_calculate();
    }
  }

// class methods
  public function removeModule($code) {
    global $lC_Database;
    
    $Qmd = $lC_Database->query("delete from :table_templates_boxes where code = :code and modules_group LIKE 'shipping'");
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

  public function hasQuotes() {
    return !empty($this->_quotes);
  }

  public function numberOfQuotes() {
    $total_quotes = 0;

    foreach ($this->_quotes as $quotes) {
      $total_quotes += sizeof($quotes['module']);
    }

    return $total_quotes;
  }

  public function getQuotes() {
    return $this->_quotes;
  }
  
  public function getFirstQuote($key = 'id') {
    return $this->_quotes[0][$key];
  }   

  public function getQuote($module = '') {
    if (empty($module)) {
      $module = $this->_selected_module;
    }
    if (strstr($module, '|')) {
      $mArr = explode('|', $module);
      $module = $mArr[0];
    }    

    list($module_id, $method_id) = explode('_', $module);

    $rate = array();

    foreach ($this->_quotes as $quote) {
      if ($quote['id'] == $module_id) {
        foreach ($quote['methods'] as $method) {
          if ($method['id'] == $method_id) {
            $rate = array('id' => $module,
                          'title' => $quote['module'] . ((empty($method['title']) === false) ? ' (' . $method['title'] . ')' : ''),
                          'cost' => $method['cost'],
                          'tax_class_id' => $quote['tax_class_id'],
                          'is_cheapest' => null);

            break 2;
          }
        }
      }
    }

    return $rate;
  }

  public function getCheapestQuote() {
    $rate = array();

    foreach ($this->_quotes as $quote) {
      if (!is_array($quote['methods'])) continue;
      foreach ($quote['methods'] as $method) {
        if (empty($rate) || ($method['cost'] < $rate['cost'])) {
          $rate = array('id' => $quote['id'] . '_' . $method['id'],
                        'title' => $quote['module'] . ((empty($method['title']) === false) ? ' (' . $method['title'] . ')' : ''),
                        'cost' => $method['cost'],
                        'tax_class_id' => $quote['tax_class_id'],
                        'is_cheapest' => false);
        }
      }
    }

    if (!empty($rate)) {
      $rate['is_cheapest'] = true;
    }

    return $rate;
  }

  public function hasActive() {
    static $has_active;

    if (isset($has_active) === false) {
      $has_active = false;

      foreach ($this->_modules as $module) {
        if (strstr($module, '|')) {
          $mArr = explode('|', $module);
          $module = $mArr[0];
        }        
        if ($GLOBALS['lC_Shipping_' . $module]->isEnabled()) {
          $has_active = true;
          break;
        }
      }
    }

    return $has_active;
  }

  private function _calculate() {
    $this->_quotes = array();

    if (is_array($this->_modules)) {
      $include_quotes = array();

      if (defined('ADDONS_SHIPPING_FREE_SHIPPING_STATUS') && isset($GLOBALS['lC_Shipping_free']) && $GLOBALS['lC_Shipping_free']->isEnabled()) {
        $include_quotes[] = 'lC_Shipping_free';
      } else {
        foreach ($this->_modules as $module) {
          if (strstr($module, '|')) {
            $mArr = explode('|', $module);
            $module = $mArr[0];
          }   
              
          if ($GLOBALS['lC_Shipping_' . $module]->isEnabled()) {
            $include_quotes[] = 'lC_Shipping_' . $module;
          }
        }
      }

      foreach ($include_quotes as $module) {
        $quotes = $GLOBALS[$module]->quote();

        if (is_array($quotes)) {
          $this->_quotes[] = $quotes;
        }
      }
    }
  }

  private function _usortModules($a, $b) {
    if (strstr($a, '|')) $a = substr($a, 0, strpos($a, '|'));
    if (strstr($b, '|')) $b = substr($b, 0, strpos($b, '|'));
   
    if ($GLOBALS['lC_Shipping_' . $a]->getSortOrder() == $GLOBALS['lC_Shipping_' . $b]->getSortOrder()) {
      return strnatcasecmp($GLOBALS['lC_Shipping_' . $a]->getTitle(), $GLOBALS['lC_Shipping_' . $a]->getTitle());
    }

    return ($GLOBALS['lC_Shipping_' . $a]->getSortOrder() < $GLOBALS['lC_Shipping_' . $b]->getSortOrder()) ? -1 : 1;
  }  
}
?>