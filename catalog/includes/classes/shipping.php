<?php
/*
  $Id: shipping.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
class lC_Shipping {
  var $_modules = array(),
      $_selected_module,
      $_quotes = array(),
      $_group = 'shipping';

  // class constructor
  function lC_Shipping($module = '') {
    global $lC_Database, $lC_Language, $lC_Vqmod;

    $this->_quotes =& $_SESSION['lC_ShoppingCart_data']['shipping_quotes'];

    $Qmodules = $lC_Database->query('select code from :table_templates_boxes where modules_group = "shipping"');
    $Qmodules->bindTable(':table_templates_boxes', TABLE_TEMPLATES_BOXES);
    $Qmodules->setCache('modules-shipping');
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

      $lC_Language->load('modules-shipping');

      foreach ($this->_modules as $module) {
        $module_class = 'lC_Shipping_' . $module;

        if (class_exists($module_class) === false) {
          include($lC_Vqmod->modCheck('includes/modules/shipping/' . $module . '.' . substr(basename(__FILE__), (strrpos(basename(__FILE__), '.')+1))));
        }

        $GLOBALS[$module_class] = new $module_class();
        $GLOBALS[$module_class]->initialize();
      }

      usort($this->_modules, array('lC_Shipping', '_usortModules'));
    }

    if ( empty($this->_quotes) ) {
      $this->_calculate();
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

  function hasQuotes() {
    return !empty($this->_quotes);
  }

  function numberOfQuotes() {
    $total_quotes = 0;

    foreach ($this->_quotes as $quotes) {
      $total_quotes += sizeof($quotes['methods']);
    }

    return $total_quotes;
  }

  function getQuotes() {
    return $this->_quotes;
  }

  function getQuote($module = '') {
    if (empty($module)) {
      $module = $this->_selected_module;
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

  function getCheapestQuote() {
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

  function hasActive() {
    static $has_active;

    if (isset($has_active) === false) {
      $has_active = false;

      foreach ($this->_modules as $module) {
        if ($GLOBALS['lC_Shipping_' . $module]->isEnabled()) {
          $has_active = true;
          break;
        }
      }
    }

    return $has_active;
  }

  function _calculate() {
    $this->_quotes = array();

    if (is_array($this->_modules)) {
      $include_quotes = array();

      if (defined('MODULE_SHIPPING_FREE_STATUS') && isset($GLOBALS['lC_Shipping_free']) && $GLOBALS['lC_Shipping_free']->isEnabled()) {
        $include_quotes[] = 'lC_Shipping_free';
      } else {
        foreach ($this->_modules as $module) {
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

  function _usortModules($a, $b) {
    if ($GLOBALS['lC_Shipping_' . $a]->getSortOrder() == $GLOBALS['lC_Shipping_' . $b]->getSortOrder()) {
      return strnatcasecmp($GLOBALS['lC_Shipping_' . $a]->getTitle(), $GLOBALS['lC_Shipping_' . $a]->getTitle());
    }

    return ($GLOBALS['lC_Shipping_' . $a]->getSortOrder() < $GLOBALS['lC_Shipping_' . $b]->getSortOrder()) ? -1 : 1;
  }
}
?>