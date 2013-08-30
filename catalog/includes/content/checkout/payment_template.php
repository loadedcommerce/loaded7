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
  function lC_Checkout_Payment_template() {
      //global $lC_Database, $lC_Session, $lC_ShoppingCart, $lC_Customer, $lC_Services, $lC_Language, $lC_NavigationHistory, $lC_Breadcrumb, $lC_Payment, $lC_MessageStack, $lC_Vqmod;
      global $lC_ShoppingCart, $lC_Language, $lC_Payment;
      
      if ($lC_ShoppingCart->getBillingMethod('id')) {
        $lC_Payment = new lC_Payment($lC_ShoppingCart->getBillingMethod('id'));                
      } else {
        $lC_Payment = new lC_Payment();                
      }               

      $this->_page_title = $lC_Language->get('secure_payment_heading_title');
      
      $this->addJavascriptFilename('ext/jquery/jquery.activity-indicator-1.0.0.min.js');
  }

  function getIframeWidth() {
    $mediaType = (isset($_SESSION['mediaType']) && $_SESSION['mediaType'] != NULL) ? strtolower($_SESSION['mediaType']) : 'desktop';
    switch($mediaType) {
      case 'mobile-portrait' :
        $frameWidth = '254px';
        break;
      case 'mobile-landscape' :
        $frameWidth = '414px';
        break;
      case 'small-tablet-portrait' :
        $frameWidth = '490px';
        break;   
      case 'small-tablet-landscape' :
        $frameWidth = '410px';
        break;                                         
      case 'tablet-portrait' :
        $frameWidth = '410px';
        break;  
      case 'tablet-landscape' :
        $frameWidth = '470px';
        break;                                                                 
      default : // desktop
        $frameWidth = '500px';
    }    
      
    return $frameWidth;
  }
  
  
  function rePost() {
    $postData = '';
    foreach ($_POST as $key => $value) {
      $postData .= '<input type="hidden" name="' . $key . '" value="' . $value . '">' . "\n";
    }
    
    return $postData;
  }  
}
?>