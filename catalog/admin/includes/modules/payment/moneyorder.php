<?php
/**  
*  $Id: moneyorder.php v1.0 2013-01-01 datazen $
*
*  LoadedCommerce, Innovative eCommerce Solutions
*  http://www.loadedcommerce.com
*
*  Copyright (c) 2013 Loaded Commerce, LLC
*
*  @author     Loaded Commerce Team
*  @copyright  (c) 2013 Loaded Commerce Team
*  @license    http://loadedcommerce.com/license.html
* 
*/
class lC_Payment_moneyorder extends lC_Payment_Admin {
 /**
  * The administrative title of the payment module
  *
  * @var string
  * @access private
  */
  public $_title;
 /**
  * The code of the payment module
  *
  * @var string
  * @access public
  */
  public $_code = 'moneyorder';
 /**
  * The developers name
  *
  * @var string
  * @access public
  */
  public $_author_name = 'Loaded Commerce';
 /**
  * The developers address
  *
  * @var string
  * @access public
  */
  public $_author_www = 'http://www.loadedcommerce.com';
 /**
  * The status of the module
  *
  * @var boolean
  * @access public
  */
  public $_status = false;
 /**
  * Constructor
  */
  public function __constructor() {
    global $lC_Language;

    $this->_title = $lC_Language->get('payment_moneyorder_title');
    $this->_description = $lC_Language->get('payment_moneyorder_description');
    $this->_method_title = $lC_Language->get('payment_moneyorder_method_title');
    $this->_status = (defined('MODULE_PAYMENT_MONEYORDER_STATUS') && (MODULE_PAYMENT_MONEYORDER_STATUS == '1') ? true : false);
    $this->_sort_order = (defined('MODULE_PAYMENT_MONEYORDER_SORT_ORDER') ? MODULE_PAYMENT_MONEYORDER_SORT_ORDER : null);
  }
 /**
  * Checks to see if the module has been installed
  *
  * @access public
  * @return boolean
  */
  public function isInstalled() {
    return (bool)defined('MODULE_PAYMENT_MONEYORDER_STATUS');
  }
 /**
  * Installs the module
  *
  * @access public
  * @return void
  */
  public function install() {
    global $lC_Database;

    parent::install();

    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Enable Money Order Module', 'MODULE_PAYMENT_MONEYORDER_STATUS', '-1', 'Do you want to accept Money Order payments?', '6', '0', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Payment Zone', 'MODULE_PAYMENT_MONEYORDER_ZONE', '0', 'If a zone is selected, only enable this payment method for that zone.', '6', '0', 'lc_cfg_use_get_zone_class_title', 'lc_cfg_set_zone_classes_pull_down_menu', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort order of display.', 'MODULE_PAYMENT_MONEYORDER_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Set Order Status', 'MODULE_PAYMENT_MONEYORDER_ORDER_STATUS_ID', '0', 'Set the status of orders made with this payment module to this value', '6', '0', 'lc_cfg_set_order_statuses_pull_down_menu', 'lc_cfg_use_get_order_status_title', now())");
  }
 /**
  * Return the configuration parameter keys in an array
  *
  * @access public
  * @return array
  */
  public function getKeys() {
    if (!isset($this->_keys)) {
      $this->_keys = array('MODULE_PAYMENT_MONEYORDER_STATUS',
                           'MODULE_PAYMENT_MONEYORDER_ZONE',
                           'MODULE_PAYMENT_MONEYORDER_ORDER_STATUS_ID',
                           'MODULE_PAYMENT_MONEYORDER_SORT_ORDER');
    }

    return $this->_keys;
  }
}
?>