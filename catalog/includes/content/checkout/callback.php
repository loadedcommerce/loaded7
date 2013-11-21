<?php
/**
  @package    catalog::content
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: callback.php v1.0 2013-08-08 datazen $
*/
class lC_Checkout_Callback extends lC_Template {

  /* Private variables */
  var $_module = 'callback';

  /* Class constructor */
  public function lC_Checkout_Callback() {
    global $lC_Vqmod;
    
    if (isset($_GET['module']) && (empty($_GET['module']) === false)) {
      if (file_exists('includes/modules/payment/' . $_GET['module'] . '.php')) {
        include($lC_Vqmod->modCheck('includes/classes/order.php'));
        include($lC_Vqmod->modCheck('includes/classes/payment.php'));
        include($lC_Vqmod->modCheck('includes/modules/payment/' . $_GET['module'] . '.php'));
        $module = 'lC_Payment_' . $_GET['module'];
        $module = new $module();
        $module->callback();
      }
    }
    exit;
  }
}
?>