<?php
/**
  @package    admin::modules
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: terms_handling.php v1.0 2013-08-08 datazen $
*/
class lC_OrderTotal_terms_handling extends lC_Modules_order_total_Admin {
  var $_title,
      $_code = 'terms_handling',
      $_author_name = 'Loaded Commerce',
      $_author_www = 'http://www.loadedcommerce.com',
      $_status = false,
      $_sort_order;

  public function lC_OrderTotal_terms_handling() {
    global $lC_Language;
    
    if (utility::isB2B() === false) return;

    $this->_title = $lC_Language->get('order_total_terms_handling_title');
    $this->_description = $lC_Language->get('order_total_terms_handling_description');
    $this->_status = (defined('MODULE_ORDER_TOTAL_TERMS_HANDLING_STATUS') && (MODULE_ORDER_TOTAL_TERMS_HANDLING_STATUS == 'true') ? true : false);
    $this->_sort_order = (defined('MODULE_ORDER_TOTAL_TERMS_HANDLING_SORT_ORDER') ? MODULE_ORDER_TOTAL_TERMS_HANDLING_SORT_ORDER : null);
  }

  public function isInstalled() {
    
    if (utility::isB2B() === false) return false;

    return (bool)defined('MODULE_ORDER_TOTAL_TERMS_HANDLING_STATUS');
  }

  public function install() {
    global $lC_Database;

    $lC_Database->simpleQuery("insert ignore into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Handling Fee?', 'MODULE_ORDER_TOTAL_TERMS_HANDLING_STATUS', 'true', 'Do you want to enable the handling fee value?', '6', '1', 'lc_cfg_set_boolean_value(array(\'true\', \'false\'))', now())");
    $lC_Database->simpleQuery("insert ignore into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_ORDER_TOTAL_TERMS_HANDLING_SORT_ORDER', '150', 'Sort order of display.', '6', '2', now())");
    $lC_Database->simpleQuery("insert ignore into " . TABLE_TEMPLATES_BOXES . " (title, code, author_name, author_www, modules_group) values ('Tax', 'terms_handling', 'LoadedComerce', 'http://www.loadedcommerce.com', 'order_total')");
  }

  public function remove() {
    global $lC_Database;

    $lC_Database->simpleQuery("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->getKeys()) . "')");
    $lC_Database->simpleQuery("delete from " . TABLE_TEMPLATES_BOXES . " where code = 'terms_handling'");
  }

  public function getKeys() {
    
    if (!isset($this->_keys)) {
      $this->_keys = array('MODULE_ORDER_TOTAL_TERMS_HANDLING_STATUS',
                           'MODULE_ORDER_TOTAL_TERMS_HANDLING_SORT_ORDER');
    }

    return $this->_keys;
  }
}
?>