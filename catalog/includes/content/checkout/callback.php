<?php
/**
  $Id: callback.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
class lC_Checkout_Callback extends lC_Template {

  /* Private variables */
  var $_module = 'callback';

  /* Class constructor */
  function lC_Checkout_Callback() {
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