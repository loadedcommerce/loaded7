<?php
/**
  @package    catalog::modules::order_total
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: total.php v1.0 2013-08-08 datazen $
*/
class lC_OrderTotal_total extends lC_OrderTotal {
  var $output;

  var $_title,
      $_code = 'total',
      $_status = false,
      $_sort_order;

  function lC_OrderTotal_total() {
    global $lC_Language;

    $this->output = array();

    $this->_title = $lC_Language->get('order_total_total_title');
    $this->_description = $lC_Language->get('order_total_total_description');
    $this->_status = (defined('MODULE_ORDER_TOTAL_TOTAL_STATUS') && (MODULE_ORDER_TOTAL_TOTAL_STATUS == 'true') ? true : false);
    $this->_sort_order = (defined('MODULE_ORDER_TOTAL_TOTAL_SORT_ORDER') ? MODULE_ORDER_TOTAL_TOTAL_SORT_ORDER : null);
  }

  function process() {
    global $lC_ShoppingCart, $lC_Currencies, $lC_Customer;
    
    $ot_total = $lC_ShoppingCart->getTotal();
    $store_credit = $lC_Customer->getStoreCredit(true);
    if( isset($_POST['use_credit']) && $store_credit > 0 ) {

      if($store_credit > $ot_total){
        $ot_total = '0.00';
      }else{
        $ot_total = ($ot_total - $store_credit);
      }
    }

    $this->output[] = array('title' => $this->_title . ':',
                            'text' => '<b>' . $lC_Currencies->format($ot_total) . '</b>',
                            'value' => $lC_Currencies->formatRaw($ot_total));

  }
}
?>