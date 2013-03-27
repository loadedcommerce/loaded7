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

?>
<!--content/checkout/checkout_payment_template.php start-->
<style>
/*content area width, it is needed*/
.colMid {width:75%;}
/*form styles*/
.form-list li { margin:0 0 8px; }
.form-list label { float:left; color:#666; font-weight:bold; position:relative; z-index:0; }
.form-list label.required {}
.form-list label.required em { float:right; font-style:normal; color:#eb340a; position:absolute; top:10; right:-18px; }
.form-list .input-box { display:block; clear:both; width:98%; }
.form-list .field { float:left; width:275px; }
.form-list input.input-text { width:98%; height:20px; }
.form-list select { width:100%; height:25px; padding:5px }
#payment-button { color:#fff; font-size:14px; font-weight:bold; padding:8px 14px; background:#873b7a !important; border:0px; line-height:100%; cursor:pointer; vertical-align:middle; float:right; }
#payment-buttons-container { width: 98%; }
#cancel { float:left; }
#checkoutConfirmationDetails { background-color: #F9F8F6; border: 1px solid #EBE2D9; padding-top: 4px; width: 100%; height:478px; }
.v-fix { padding-bottom:15px; }
#payment_form_ccsave u { float:right; padding-right: 15px; }
#payment-processing { right: 50px; margin-top: 8px; }
#ot-container { display: block; }
@media only screen and (max-width: 320px) {
  #checkoutConfirmationDetails { width: 83% !important; } 
}

#paymentTemplateContainer .security-info-title { cursor:pointer; float:right; }
#paymentTemplateContainer .security-info-text-container { margin:0 -5px 10px 0; background-color:#edfbec; }
#paymentTemplateContainer .security-info-url { border:1px solid #ccc; padding:5px; }
#paymentTemplateContainer .security-info-text { border:1px solid #ccc; padding:7px 5px; }
#paymentTemplateContainer .arrow-container { margin-top:15px; }
#paymentTemplateContainer .arrow-down { border-left: 10px solid transparent; border-right: 10px solid transparent; border-top: 10px solid gray; float: right; height: 0; width: 0; margin:13px 0 0 6px; }
#paymentTemplateContainer .arrow-up { border-bottom: 10px solid gray; border-left: 10px solid transparent; border-right: 10px solid transparent; float: right; height: 0; width: 0; margin:13px 0 0 6px; } 
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
                         '  <div class="security-info-url"><?php echo lc_image('images/greenlock.png', null, null, null, 'style="vertical-align:middle; margin-right:5px;"') . "http" . (($_SERVER['SERVER_PORT'] == 443) ? "s://" : "://") . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?></div>'+
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
            </div>
            
            <div id="checkout_shipping_col2" style="width:67%; float:right; margin-right:-4px">
              <div id="checkoutConfirmationDetails"> 
                <?php
                if (isset($_POST['SECURETOKEN']) && $_POST['SECURETOKEN'] != NULL && isset($_POST['iframe_action_url']) && $_POST['iframe_action_url'] != NULL) {
                  // paypal iframe
                  echo '<iframe src="' . $_POST['iframe_action_url'] . '?mode=' . $_POST['MODE'] . '&amp;SECURETOKEN=' . $_POST['SECURETOKEN'] . '&amp;SECURETOKENID=' . $_POST['SECURETOKENID'] . '" width="480" height="475" scrolling="no" frameborder="0" border="0" allowtransparency="true"></iframe>';
                } else {
                  // cre
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
<!--content/checkout/checkout_payment_template.php end-->