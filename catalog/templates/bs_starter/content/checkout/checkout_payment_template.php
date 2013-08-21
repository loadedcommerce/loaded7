<?php
/**  
  @package    catalog::templates::content
  @author     Loaded Commerce, LLC
  @copyright  Copyright 2003-2013 Loaded Commerce Development Team
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on DevKit http://www.bootstraptor.com under GPL license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: checkout_payment_template.php v1.0 2013-08-08 datazen $
*/ 
$lC_Template->addJavascriptFilename('ext/jquery/jquery.activity-indicator-1.0.0.min.js');
?>
<!--content/checkout/checkout_payment_template.php start-->
<style>
/*content area width, it is needed*/
#loadingContainer { position:absolute; left:50%; top:10%; }
#iloader { margin:100px 0 0 0px; }
<?php 
// common
$fHeight = '550px';
$fScroll = 'no';

if ($lC_ShoppingCart->getBillingMethod('id') == 'paypal_adv') {
  echo "#payformIframe { min-width:500px; margin-left:14px; min-height:580px; }";
} else if ($lC_ShoppingCart->getBillingMethod('id') == 'cresecure') {
  echo "#payformIframe { min-width:480px; min-height:300px; }";
} else if ($lC_ShoppingCart->getBillingMethod('id') == 'authorizenet_cc' || 
           $lC_ShoppingCart->getBillingMethod('id') == 'globaliris' ||
           $lC_ShoppingCart->getBillingMethod('id') == 'usaepay_cc' ) {
  $fHeight = '400px';
  $fScroll = 'auto';
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
  #payformIframe { min-width:420px; min-height:300px; }
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

<!--content/checkout/checkout_payment_template.php start-->
<div class="row">
  <div class="col-sm-12 col-lg-12 large-margin-bottom">  
    <h1 class="no-margin-top"><?php echo $lC_Language->get('text_checkout'); ?></h1>
    <div id="content-checkout-shipping-container">
      <div class="panel panel-default no-margin-bottom">
        <div class="panel-heading cursor-pointer" onclick="window.location.href='<?php echo lc_href_link(FILENAME_CHECKOUT, 'shipping', 'SSL'); ?>'">
          <h3 class="no-margin-top no-margin-bottom"><?php echo $lC_Language->get('box_ordering_steps_delivery'); ?></h3>
        </div>
      </div>
      <div class="clearfix panel panel-default no-margin-bottom">
        <div class="panel-heading cursor-pointer" onclick="window.location.href='<?php echo lc_href_link(FILENAME_CHECKOUT, 'payment&skip=no', 'SSL'); ?>'">
          <h3 class="no-margin-top no-margin-bottom"><?php echo $lC_Language->get('box_ordering_steps_payment'); ?></h3>
        </div>
      </div>
      <div class="clearfix panel panel-default no-margin-bottom">
        <div class="panel-heading">
          <h3 class="no-margin-top no-margin-bottom"><?php echo $lC_Language->get('box_ordering_steps_secure_checkout'); ?></h3>
        </div>  
        <div class="panel-body no-padding-bottom">
          <div class="row">
            <div class="col-sm-12 col-lg-12">
              <div class="alert alert-warning no-margin-bottom">
                <?php echo $lC_Language->get('secure_payment_description_text'); ?>
              </div>
              <script>
                /* we use javascript here due to js scrubbing at payment endpoints - we don't want to show if javascript is disabled or scrubbed */
                var output = '<div class="security-info-container">'+
                             '  <div class="security-info-title text-right cursor-pointer" onclick="$(\'#security-info-alert\').toggle(\'slideDown\');"><?php echo lc_image('images/greenlock.png', null, null, null, 'style="vertical-align:middle; margin:10px 5px;"') . $lC_Language->get('secure_payment_security_info_title'); ?><span class="arrow-container"><span id="arrow" class="arrow-down"></span></span></div>'+
                             '  <div id="security-info-alert" class="alert alert-success" style="display:none;">'+
                             '    <div class="security-info-url"><?php echo lc_image('images/greenlock.png', null, null, null, 'style="vertical-align:middle; margin-right:5px;"') . ($lC_Payment->hasIframeURL()) ? substr($lC_Payment->getIframeURL(), 0, strpos($lC_Payment->getIframeURL(), '?')) : (($lC_Payment->hasRelayURL()) ?  $lC_Payment->getRelayURL() : NULL); ?></div>'+
                             '    <div class="security-info-text normal large-margin-left small-padding-left"><?php echo $lC_Language->get('secure_payment_security_info_text'); ?></div>'+
                             '  </div>' +
                             '</div>';
                
                document.write(output);            
              </script>   
            </div>
            <div class="col-sm-4 col-lg-4">
              <div class="well">
                <span class="strong"><?php echo $lC_Language->get('checkout_order_number'); ?></span>
                <span class="pull-right strong"><?php echo $_SESSION['cartID']; ?></span> 
              </div>
              <div class="well">
                <span class="strong"><?php echo $lC_Language->get('text_amount_due'); ?></span>
                <span class="pull-right strong"><?php echo $lC_Currencies->format($lC_ShoppingCart->getTotal()); ?></span>
              </div>
              <div class="well relative no-padding-bottom">
                <h4 class="no-margin-top"><?php echo $lC_Language->get('bill_to_address'); ?></h4>
                <address>
                  <?php echo lC_Address::format($lC_ShoppingCart->getBillingAddress(), '<br />'); ?>                
                </address>
                <div class="btn-group clearfix absolute-top-right">
                  <a href="<?php echo lc_href_link(FILENAME_CHECKOUT, 'payment_address', 'SSL'); ?>"><button type="button" class="btn btn-default btn-xs"><?php echo $lC_Language->get('button_edit'); ?></button></a>
                </div>                  
              </div>
            </div>
            <div class="col-sm-8 col-lg-8 no-padding-left large-margin-bottom">
              <div id="checkoutConfirmationDetails"> 
                <div id="loadingContainer"><p id="iloader"></p></div>
                <?php  
                if ($lC_Payment->hasIframeURL()) {
                  echo '<iframe onload="hideLoader();" id="payformIframe" src="' . $lC_Payment->getIframeURL() . '" scrolling="' . $fScroll . '" frameborder="0" border="0" allowtransparency="true">Your browser does not support iframes.</iframe>';
                } else if ($lC_Payment->hasRelayURL()) { 
                  echo '<form name="pmtForm" id="pmtForm" action="' . $lC_Payment->getRelayURL() . '" target="pmtFrame" method="post">' . lC_Checkout_Payment_template::rePost() . '</form>' . "\n";        
                  echo '<iframe frameborder="0" onload="setTimeout(function() {hideLoader();},1250);" src="" id="pmtFrame" name="pmtFrame" width="' . lC_Checkout_Payment_template::getIframeWidth() . '" height="' . $fHeight . '" scrolling="' . $fScroll . '" frameborder="0" border="0" allowtransparency="true">Your browser does not support iframes.</iframe>'; 
                } else {
                  echo '[[FORM INSERT]]'; 
                }
              ?>
              </div>
            </div>
          </div>        
        </div>
      </div>
    </div> 
  </div>
</div> 
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