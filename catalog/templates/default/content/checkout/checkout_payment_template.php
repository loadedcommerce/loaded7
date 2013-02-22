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
#checkoutConfirmationDetails { background-color: #F9F8F6; border: 1px solid #EBE2D9; padding: 13px 20px 35px 25px; width: 89%; }
.v-fix { padding-bottom:15px; }
#payment_form_ccsave u { float:right; padding-right: 15px; }
#payment-processing { right: 50px; margin-top: 8px; }
#ot-container { display: block; }
@media only screen and (max-width: 320px) {
  #checkoutConfirmationDetails { width: 83% !important; } 
}
</style>
<div class="full_page">
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
          <div class="col2-set">
            <div id="checkout_shipping_col1" style="width:35%; float:left;">
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
            <div id="checkout_shipping_col2" style="width:60%; float:right;">
              <div id="checkoutConfirmationDetails"> [[FORM INSERT]] </div>
            </div>
          </div>
        </div>
      </li>
    </ol>
  </div>
</div>
<div style="clear:both;"></div>
<!--content/checkout/checkout_payment_template.php end-->