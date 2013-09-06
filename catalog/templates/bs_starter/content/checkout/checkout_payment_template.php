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
if ($lC_Payment->hasIframeParams()) {
  $params = utility::nvp2arr($lC_Payment->getIframeParams());
  $fWidth = (isset($params['width']) && empty($params['width']) === false) ? $params['width'] : '550px';
  $fHeight = (isset($params['height']) && empty($params['height']) === false) ? $params['height'] : '550px';
  $fScroll = (isset($params['scroll']) && empty($params['scroll']) === false) ? $params['scroll'] : 'no';
  $fStyle = (isset($params['margin-left']) && empty($params['margin-left']) === false) ? 'style="margin-left:' . $params['margin-left'] . '"' : null;
} else {
  $fWidth = '550px';
  $fHeight = '550px';
  $fScroll = 'no';
  $fStyle = null;
}
$secureUrl = ($lC_Payment->hasIframeURL()) ? substr($lC_Payment->getIframeURL(), 0, strpos($lC_Payment->getIframeURL(), '?')) : (($lC_Payment->hasRelayURL()) ?  $lC_Payment->getRelayURL() : NULL);
?>
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
                             '  <div class="security-info-title text-right cursor-pointer" onclick="$(\'#security-info-alert\').toggle(\'slideDown\');"><?php echo lc_image('images/greenlock.png', null, null, null, 'class="valign-middle margin-top margin-bottom small-margin-left small-margin-right"') . $lC_Language->get('secure_payment_security_info_title'); ?></div>'+
                             '  <div id="security-info-alert" class="alert alert-success" style="display:none;">'+
                             '  <div class="security-info-url"><?php echo lc_image('images/greenlock.png', null, null, null, 'class="valign-middle small-margin-right"') . $secureUrl; ?></div>'+
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
                  <form action="<?php echo lc_href_link(FILENAME_CHECKOUT, 'payment_address', 'SSL'); ?>" method="post"><button type="button" onclick="$(this).closest('form').submit();" class="btn btn-default btn-xs"><?php echo $lC_Language->get('button_edit'); ?></button></form>
                </div>                  
              </div>
            </div>
            <div class="col-sm-8 col-lg-8 no-padding-left large-margin-bottom">
              <div id="checkoutConfirmationDetails"> 
                <div id="loading-container"><p id="iloader"></p></div>
                <?php  
//echo '[' . $lC_Payment->getIframeParams() . ']<br>';
                
                if ($lC_Payment->hasIframeURL()) {
                  echo '<iframe onload="hideLoader();" id="payformIframe" src="' . $lC_Payment->getIframeURL() . '" scrolling="' . $fScroll . '" height="' . $fHeight . '" width="' . $fWidth . '" ' . $fStyle . ' frameborder="0" border="0" allowtransparency="true">Your browser does not support iframes.</iframe>';
                } else if ($lC_Payment->hasRelayURL()) { 
                  echo '<form name="pmtForm" id="pmtForm" action="' . $lC_Payment->getRelayURL() . '" target="pmtFrame" method="post">' . lC_Checkout_Payment_template::rePost() . '</form>' . "\n";        
                  echo '<iframe frameborder="0" onload="setTimeout(function() {hideLoader();},1250);" src="" id="pmtFrame" name="pmtFrame" width="' . $fWidth . '" height="' . $fHeight . '" scrolling="' . $fScroll . '" ' . $fStyle . ' frameborder="0" border="0" allowtransparency="true">Your browser does not support iframes.</iframe>'; 
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
  var loadDiv = document.getElementById("loading-container"); 
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