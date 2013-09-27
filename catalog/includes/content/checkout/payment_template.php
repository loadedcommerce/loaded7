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
include_once('includes/classes/payment.php');  

class lC_Checkout_Payment_template extends lC_Template {

  /* Private variables */
  var $_module = 'payment',
      $_group = 'checkout',
      $_page_title,
      $_page_contents = 'checkout_payment_template.php';

  /* Class constructor */
  public function lC_Checkout_Payment_template() {
    global $lC_ShoppingCart, $lC_Language, $lC_Payment;
    
    if ($lC_ShoppingCart->getBillingMethod('id')) {
      $lC_Payment = new lC_Payment($lC_ShoppingCart->getBillingMethod('id'));                
    } else {
      $lC_Payment = new lC_Payment();                
    }               

    $this->_page_title = $lC_Language->get('secure_payment_heading_title');
    
    $this->addJavascriptFilename('ext/jquery/jquery.activity-indicator-1.0.0.min.js');
  }

  public function rePost() {
    $postData = '';
    foreach ($_POST as $key => $value) {
      $postData .= '<input type="hidden" name="' . $key . '" value="' . $value . '">' . "\n";
    }
    
    return $postData;
  }  
}
?>