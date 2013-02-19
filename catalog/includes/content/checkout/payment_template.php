<?php
/**  
*  $Id: payment_template.php v1.0 2013-01-01 datazen $
*
*  LoadedCommerce, Innovative eCommerce Solutions
*  http://www.loadedcommerce.com
*
*  Copyright (c) 2013 Loaded Commerce, LLC
*
*  @author     Loaded Commerce Team
*  @copyright  (c) 2013 Loaded Commerce Team
*  @license    http://loadedcommerce.com/license.html
*/
class lC_Checkout_Payment_template extends lC_Template {

  /* Private variables */
  var $_module = 'payment',
      $_group = 'checkout',
      $_page_title,
      $_page_contents = 'checkout_payment_template.php';

  /* Class constructor */
  function lC_Checkout_Payment_template() {
      global $lC_ShoppingCart, $lC_Language;

      $this->_page_title = $lC_Language->get('heading_title') . ' ' . session_name() . '=' . session_id();
      
      echo $this->_page_title;
  }
}
?>