<?php
/**  
*  $Id: checkout_payment_template.php v1.0 2013-01-01 datazen $
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
$secureUrl = ($lC_Payment->hasIframeURL()) ? substr($lC_Payment->getIframeURL(), 0, strpos($lC_Payment->getIframeURL(), '?')) : (($lC_Payment->hasRelayURL()) ?  $lC_Payment->getRelayURL() : NULL);
?>
<!--content/checkout/checkout_payment_template.php start-->
<style>
/*content area width, it is needed*/
.colMid { width:75%; }
#checkoutConfirmationDetails { background-color: #F9F8F6; border: 1px solid #EBE2D9; padding-top: 4px; width: 100%; }
#paymentTemplateContainer .security-info-title { cursor:pointer; float:right; }
#paymentTemplateContainer .security-info-text-container { margin:0 -5px 10px 0; background-color:#edfbec; }
#paymentTemplateContainer .security-info-url { border:1px solid #ccc; padding:5px; font-weight:bold; }
#paymentTemplateContainer .security-info-text { border:1px solid #ccc; padding:7px 5px; }
#paymentTemplateContainer .arrow-container { margin-top:15px; }
#paymentTemplateContainer .arrow-down { border-left: 10px solid transparent; border-right: 10px solid transparent; border-top: 10px solid gray; float: right; height: 0; width: 0; margin:13px 0 0 6px; }
#paymentTemplateContainer .arrow-up { border-bottom: 10px solid gray; border-left: 10px solid transparent; border-right: 10px solid transparent; float: right; height: 0; width: 0; margin:13px 0 0 6px; } 
#loadingContainer { position:absolute; right:250px; }
#iloader { margin:100px 0 0 0px; }
<?php 
if ($lC_ShoppingCart->getBillingMethod('id') == 'paypal_adv') {
  echo "#payformIframe { min-width:468px; min-height:580px; }";
} else if ($lC_ShoppingCart->getBillingMethod('id') == 'cresecure') {
  echo "#payformIframe { min-width:480px; min-height:300px; }";
} else {
  echo "#payformIframe { min-width:500px; min-height:300px; }";
  echo "#checkout_shipping_col1 { width:28% !important; }";
  echo "#checkout_shipping_col2 { width:71% !important; }";
}
?>

/* Mobile (landscape) ----------- */
@media only screen 
and (min-width : 321px) 
and (max-device-width : 480px) {
  #payformIframe { min-width:460px; min-height:300px; }
  #checkoutConfirmationDetails {width: 98% !important; }
}

/* Mobile (portrait) ----------- */
@media only screen 
and (max-width : 320px) {
  #payformIframe { min-width:320px; min-height:380px; }
  #checkoutConfirmationDetails {width: 96% !important; }
}

/* Tablet (landscape) ----------- */
@media only screen 
and (min-device-width : 768px) 
and (max-device-width : 1024px) 
and (orientation : landscape) {
  #payformIframe { min-width:520px; min-height:300px; }
  #checkoutConfirmationDetails {width: 96% !important; }
}

/* Tablet (portrait) ----------- */
@media only screen 
and (min-device-width : 768px) 
and (max-device-width : 1024px) 
and (orientation : portrait) {
  #payformIframe { min-width:460px; min-height:300px; }
  #checkoutConfirmationDetails {width: 96% !important; }
}

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
<div id="paymentTemplateContainer" class="full_page">
  <h5><?php echo $lC_Language->get('text_checkout'); ?></h5>
  <div class="checkout_steps">
    <ol id="checkoutSteps">
      <li class="first-checkout-li"> <a href="<?php echo lc_href_link(FILENAME_CHECKOUT, 'shipping', 'SSL'); ?>">
        <div class="step-title">
          <h2><?php echo $lC_Language->get('box_ordering_steps_delivery'); ?></h2>
        </div>
        </a> 
      </li>
      <li> <a href="<?php echo lc_href_link(FILENAME_CHECKOUT, 'payment', 'SSL'); ?>">
        <div class="step-title">
          <h2><?php echo $lC_Language->get('box_ordering_steps_payment'); ?></h2>
        </div>
        </a> 
      </li>
      <li class="section allow active">
        <div class="step-title">
          <h2><?php echo $lC_Language->get('box_ordering_steps_secure_checkout'); ?></h2>
        </div>
        <div id="checkout-step-login">
          <p><?php echo $lC_Language->get('secure_payment_description_text'); ?></p>
          <script>
            /* we use javascript here due to js scrubbing at payment endpoints - we don't want to show if javascript is disabled or scrubbed */
            var output = '<div class="security-info-container">'+
                         '<div class="security-info-title" onclick="toggleSecurityInfo();"><?php echo lc_image('images/greenlock.png', null, null, null, 'style="vertical-align:middle; margin:10px 5px;"') . $lC_Language->get('secure_payment_security_info_title'); ?><span class="arrow-container"><span id="arrow" class="arrow-down"></span></span></div>'+
                         '<div style="clear:both;"></div>'+
                         '<div class="security-info-text-container" style="display:none;">'+
                         '  <div class="security-info-url"><?php echo lc_image('images/greenlock.png', null, null, null, 'style="vertical-align:middle; margin-right:5px;"') . $secureUrl; ?></div>'+
                         '  <div class="security-info-text"><?php echo $lC_Language->get('secure_payment_security_info_text'); ?></div>'+
                         '</div></div><div style="clear:both;"></div>';
            
            document.write(output);            
          </script>                   
          <div class="col2-set">
            <div id="checkout_shipping_col1" style="width:32%; float:left;">
              <div id="ot-container">
                <div class="ot-block" id="order-number">
                  <label><?php echo $lC_Language->get('checkout_order_number'); ?></label>
                  <span><?php echo $_SESSION['cartID']; ?></span> 
                </div>
              </div>
              <div id="ot-container" class="margin-top">
                <div class="ot-block" id="order-number">
                  <label>Amount Due</label>
                  <span><?php echo $lC_Currencies->format($lC_ShoppingCart->getTotal()); ?></span>
                </div>
              </div>
              <div id="bill-to-address-block">
                <h3><?php echo $lC_Language->get('bill_to_address'); ?></h3>
                <span style="float:right;"><a href="<?php echo lc_href_link(FILENAME_CHECKOUT, 'payment_address', 'SSL'); ?>" class="sc-button small grey colorWhite noDecoration"><?php echo $lC_Language->get('button_edit'); ?></a></span> <span id="bill-to-span"><?php echo lC_Address::format($lC_ShoppingCart->getBillingAddress(), '<br />'); ?></span>
              </div>
              <?php
                //echo '[' . $_SESSION['mediaType'] . '][' . $_SESSION['mediaSize'] . ']<br>';
              ?>
            </div>
            <div id="checkout_shipping_col2" style="width:67%; float:right; margin-right:-4px">
              <div id="checkoutConfirmationDetails"> 
                <div id="loadingContainer"><p id="iloader"></p></div>
                <?php  
                if ($lC_Payment->hasIframeURL()) {
                  echo '<iframe onload="hideLoader();" id="payformIframe" src="' . $lC_Payment->getIframeURL() . '" scrolling="no" frameborder="0" border="0" allowtransparency="true">Your browser does not support iframes.</iframe>';
                } else if ($lC_Payment->hasRelayURL()) { 
                  echo '<form name="pmtForm" id="pmtForm" action="' . $lC_Payment->getRelayURL() . '" target="pmtFrame" method="post">' . lC_Checkout_Payment_template::rePost() . '</form>' . "\n";        
                  echo '<iframe frameborder="0" onload="setTimeout(function() {hideLoader();},1250);" src="" id="pmtFrame" name="pmtFrame" width="' . lC_Checkout_Payment_template::getIframeWidth() . '" height="550px" scrolling="no" frameborder="0" border="0" allowtransparency="true">Your browser does not support iframes.</iframe>'; 
                } else {
                  echo '[[FORM INSERT]]'; 
                }
              ?>
              </div>
            </div>     
          </div>
        </div> 
      </li>
    </ol>
  </div>
</div>
<div style="clear:both;"></div>
<script>
function hideLoader() {
  var loadDiv = document.getElementById("loadingContainer"); 
  loadDiv.style.display = "none"; 
}

$(function() {
  $('#iloader').activity({segments: 12, width: 5.5, space: 6, length: 13, color: '#252525', speed: 1.5});
});

<?php
if ($lC_Payment->hasRelayURL()) {
  echo "window.onload = function(){ document.forms['pmtForm'].submit(); };";
}
?>
</script>
<!--content/checkout/checkout_payment_template.php end-->