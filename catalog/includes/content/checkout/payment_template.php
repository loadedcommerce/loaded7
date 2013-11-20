<?php
/**
  @package    catalog::content
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: payment_template.php v1.0 2013-08-08 datazen $
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