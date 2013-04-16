<?php
/**  
  $Id: cresecure-template.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     Loaded Commerce Team
  @copyright  (c) 2013 Loaded Commerce Team
  @license    http://loadedcommerce.com/license.html
*/ 
require('includes/application_top.php');
?>
<html>
<head>
</head>
<body>
<style>
/*form styles*/ 
#payformContainer { position:relative; width: 100%; margin-left:15px; }
#payformContainer .form-list { color: #544F4B; font-size: 13px; font-family: Arial,Helvetica,sans-serif; }
#payformContainer ul, #payformContainer li{ border:0; margin:0; padding:0; list-style:none; }
#payformContainer li{ clear:both; list-style:none; padding-bottom:10px; }
#payformContainer li > span > span { font-size:.8em; position:absolute; right:36%; }
#payformContainer input{ float:left; width:63%; }
#payformContainer label{ font-weight: bold; width:30%; float:left; } 
#payformContainer select { height:22px; } 
#payformContainer .v-fix { display: inline-block; }   
#payformContainer .form-list label.required em { position:absolute; display:none; }
#name { margin-bottom: 10px; }  
#PAN { margin-bottom: 10px; }  
#payment-buttons-container .required { display: none; }
#payment-buttons-container { width: 40%; float:right; margin-top:10px; }
#payment-button { color:#fff; font-size:14px; font-weight:bold; padding:8px 14px; background:#873b7a !important; border:0px; line-height:100%; cursor:pointer; vertical-align:middle; }
#cancel { font-size:.8em; font-family:Arial,Helvetica,sans-serif; }
.error_message { background-color: transparent !important; color: #544F4B !important; font-weight: normal !important; font-family:Arial,Helvetica,sans-serif !important; }
#error_message { border-radius: 1px 1px 1px 1px; font-size: 11px; margin: -19px 21px 15px -10px; padding: 10px 5px; width: auto; background: none repeat scroll 0 0 #FFE3E2; border: 1px solid #D84646; }
#error_message span { display: block; padding: 6px 0 6px 40px !important; background: url("<?php echo lc_href_link('templates/default/images/shortcodes/error.png', null, 'SSL', null, null, true); ?>") no-repeat scroll left center transparent !important; }
#payment-processing { font-family:Arial,Helvetica,sans-serif !important; right: 28px; bottom:-37px !important; }

/* Mobile (landscape) ----------- */
@media only screen 
and (min-width : 321px) 
and (max-device-width : 480px) {
  #payformContainer { padding-top:20px; margin-left:-10px !important; width:104% !important; }
  #payformContainer label { width:35% !important; }
  #payformContainer input { width:65% !important; }  
  #payformContainer li > span > span { right:25% !important; }
  #error_message { margin:-20px 0 10px 0 !important; }
  #card_type { width:65% !important; }
  #cresecure_cc_expires_month { width:210% !important; }
  #cresecure_cc_expires_year { width:58% !important; margin-left:95px; } 
  #payment-buttons-container { width:36% !important; }
  #payment-processing { right: 0px !important; }
}

/* Mobile (portrait) ----------- */
@media only screen 
and (max-width : 320px) {
  #payformContainer { margin-left:-30px !important; }
  #payformContainer label { width:70% !important; }
  #payformContainer input { width:92% !important; }
  #card_type { width:92% !important; }
  #cv_data { width:92% !important; }
  #cresecure_cc_expires_month { width:190% !important; }
  #cresecure_cc_expires_year { width:41% !important; margin-left:80px; }
  #payment-buttons-container { width:68% !important; }
  #error_message { margin:0px 15px 10px -5px !important; }
  ul#payment_form_ccsave li:last-child div.v-fix { width:100% !important; }
  #payment-processing { right: 20px !important; }
  #payformContainer li > span > span { right:34% !important; }

}

/* Tablet (portrait and landscape) ----------- */
@media only screen 
and (min-device-width : 768px) 
and (max-device-width : 1024px) {
  #payformContainer { padding-top:20px; margin-left:-25px !important; width:100% !important; }
  #payformContainer label { width:35% !important; }
  #payformContainer input { width:52% !important; }  
  #payformContainer li > span > span { right:29% !important; } 
  #error_message { margin:-20px 50px 10px 0 !important; }
  #card_type { width:52% !important; }
  #cresecure_cc_expires_month { width:165% !important; }
  #cresecure_cc_expires_year { width:54% !important; margin-left:58px; } 
  #payment-buttons-container { width:46% !important; }
  #payment-processing { right: 35px !important; }}

/* Desktops and laptops ----------- */
@media only screen 
and (min-width : 1224px) {
/* Styles */
}

/* Large screens ----------- */
@media only screen 
and (min-width : 1824px) {
/* Styles */
}

/* iPhone 4 ----------- */
@media
only screen and (-webkit-min-device-pixel-ratio : 1.5),
only screen and (min-device-pixel-ratio : 1.5) {
/* Styles */
}
</style>
<div id="payformContainer">[[FORM INSERT]]</div>
</body>
</html>