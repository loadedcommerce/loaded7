<?php
/**  
  @package    catalog::templates::content
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on DevKit http://www.bootstraptor.com under GPL license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: checkout_payment.php v1.0 2013-08-08 datazen $
*/ 
?>
<!--content/checkout/checkout_payment.php start-->
<div class="row">
  <div class="col-sm-12 col-lg-12 large-margin-bottom">  
    <h1 class="no-margin-top"><?php echo $lC_Language->get('text_checkout'); ?></h1>
    <?php 
    if ( $lC_MessageStack->size('checkout_payment') > 0 ) echo '<div class="message-stack-container alert alert-danger small-margin-bottom">' . $lC_MessageStack->get('checkout_payment') . '</div>' . "\n"; 
    ?>
    <form name="checkout_payment" id="checkout_payment" action="<?php echo lc_href_link(FILENAME_CHECKOUT, 'confirmation', 'SSL'); ?>" method="post" onsubmit="return check_form();">
      <div id="content-checkout-payment-container">
        <div class="panel panel-default no-margin-bottom">
          <div class="panel-heading cursor-pointer" onclick="window.location.href='<?php echo lc_href_link(FILENAME_CHECKOUT, 'shipping', 'SSL'); ?>'">
            <h3 class="no-margin-top no-margin-bottom"><?php echo $lC_Language->get('box_ordering_steps_delivery'); ?></h3>
          </div>
        </div>
        <div class="clearfix panel panel-default no-margin-bottom">
          <div class="panel-heading">
            <h3 class="no-margin-top no-margin-bottom"><?php echo $lC_Language->get('box_ordering_steps_payment'); ?></h3>
          </div>
          <div class="panel-body no-padding-bottom">
            <div class="row">
              <div class="col-sm-4 col-lg-4">
                <div class="well relative no-padding-bottom">
                  <h4 class="no-margin-top"><?php echo $lC_Language->get('ship_to_address'); ?></h4>
                  <address>
                    <?php echo lC_Address::format($lC_ShoppingCart->getShippingAddress(), '<br />'); ?>                
                  </address>
                  <div class="btn-group clearfix absolute-top-right small-padding-right small-padding-top">
                    <button type="button" onclick="window.location.href='<?php echo lc_href_link(FILENAME_CHECKOUT, 'shipping_address', 'SSL'); ?>';" class="btn btn-default btn-xs"><?php echo $lC_Language->get('button_edit'); ?></button>
                  </div>
                </div>
                <div class="well relative clearfix padding-bottom"> 
                  <h4 class="no-margin-top"><?php echo $lC_Language->get('shipping_method_heading'); ?></h4>
                  <p><?php echo $lC_ShoppingCart->getShippingMethod('title'); ?></p>
                  <div class="btn-group clearfix absolute-top-right small-padding-right small-padding-top">
                    <button type="button" onclick="window.location.href='<?php echo lc_href_link(FILENAME_CHECKOUT, 'shipping', 'SSL'); ?>';" class="btn btn-default btn-xs"><?php echo $lC_Language->get('button_edit'); ?></button>
                  </div>                  
                </div>                
                <div class="well">
                  <?php 
                  foreach ($lC_ShoppingCart->getOrderTotals() as $module) {   
                    ?>
                    <div class="clearfix">
                      <span class="pull-left ot-<?php echo strtolower(str_replace('_', '-', $module['code'])); ?>"><?php echo strip_tags($module['title']); ?></span>
                      <span class="pull-right ot-<?php echo strtolower(str_replace('_', '-', $module['code'])); ?>"><?php echo strip_tags($module['text']); ?></span>                
                    </div>                    
                    <?php
                  }
                  ?>                
                </div>        
              </div>
              <div class="col-sm-8 col-lg-8">
                <div class="well relative">
                  <h4 class="no-margin-top"><?php echo $lC_Language->get('bill_to_address'); ?></h4>
                  <address class="no-margin-bottom">
                    <?php  
                      if ($_SESSION['shipto_as_billable'] == 'on') {
                        echo lC_Address::format($lC_ShoppingCart->getShippingAddress(), '<br />'); 
                      } else {
                        echo lC_Address::format($lC_ShoppingCart->getBillingAddress(), '<br />'); 
                      }                      
                    ?>
                  </address>    
                  <div class="btn-group clearfix absolute-top-right small-padding-right small-padding-top">
                    <button type="button" onclick="window.location.href='<?php echo lc_href_link(FILENAME_CHECKOUT, 'payment_address', 'SSL'); ?>'" class="btn btn-default btn-xs"><?php echo $lC_Language->get('button_edit'); ?></button>
                  </div>                                       
                </div>
                <h3 class="no-margin-top"><?php echo $lC_Language->get('payment_method_title'); ?></h3>
                <?php 
                $selection = $lC_Payment->selection();
                echo (sizeof($selection) > 1) ? '<div class="alert alert-warning">' . $lC_Language->get('choose_payment_method') . '</div>' : '<div class="alert alert-warning">' . $lC_Language->get('only_one_payment_method_available') . '</div>' . "\n"; 
                $radio_buttons = 0;
                for ($i=0, $n=sizeof($selection); $i<$n; $i++) {
                  ?>
                  <table class="content-checkout-payment-methods-table table table-hover table-responsive no-margin-bottom">
                    <?php
                    if ( ($n == 1) || ($lC_ShoppingCart->hasBillingMethod() && ($selection[$i]['id'] == $lC_ShoppingCart->getBillingMethod('id'))) ) {
                      echo '<tr class="module-row-selected cursor-pointer" id="default-selected" onclick="selectRowEffect(this, ' . $radio_buttons . ')">' . "\n";
                    } else {
                      echo '<tr class="module-row cursor-pointer" onclick="selectRowEffect(this, ' . $radio_buttons . ')">' . "\n";
                    }
                    
                    if ($n > 1) {
                      ?>
                      <td><?php echo $selection[$i]['module']; ?></td>
                      <td class="text-right"><?php echo lc_draw_radio_field('payment_method', $selection[$i]['id'], ($lC_ShoppingCart->hasBillingMethod() ? $lC_ShoppingCart->getBillingMethod('id') : null), 'id="pm_' . $counter . '"',''); ?></td>
                      <?php
                    } else {
                      ?>
                      <td class="content-checkout-listing-blank no-padding-left"></td>
                      <td><?php echo $selection[$i]['module'] . lc_draw_hidden_field('payment_method', $selection[$i]['id']); ?></td>
                      <?php
                    }                          
                    ?>
                    </tr>
                    
                    <?php
                    if (isset($selection[$i]['error'])) {
                      ?>
                      <tr><td colspan="2" class=""><?php echo $selection[$i]['error']; ?></td></tr>
                      <?php
                    } else if(isset($selection[$i]['fields']) && is_array($selection[$i]['fields'])) {
                      for ($j=0, $n2=sizeof($selection[$i]['fields']); $j<$n2; $j++) {
                        ?>
                        <tr>
                          <td><?php echo $selection[$i]['fields'][$j]['title']; ?></td>
                          <td><?php echo $selection[$i]['fields'][$j]['field']; ?></td>
                        </tr>
                        <?php
                      }
                    }
                    $counter++;
                    $radio_buttons++;
                    ?>
                  </table> 
                  <?php                          
                }
                ?>
                <table class="table margin-bottom-neg"><tr><td>&nbsp;</td></tr></table>
                <div class="btn-set clearfix no-margin-top">
                  <button class="btn btn-lg btn-success pull-right" onclick="$('#checkout_payment').submit();" type="button"><?php echo $lC_Language->get('button_continue'); ?></button>
                  <button class="btn btn-lg btn-default" onclick="window.location.href='<?php echo lc_href_link(FILENAME_CHECKOUT, 'shipping', 'SSL'); ?>'" type="button"><?php echo $lC_Language->get('button_back'); ?></button>
                </div> 
                <?php
                if ($lC_Customer->isLoggedOn() !== false) {
                  if (defined('MODULE_SERVICES_INSTALLED') && in_array('coupons', explode(';', MODULE_SERVICES_INSTALLED)) && 
                      defined('SERVICE_COUPONS_DISPLAY_ON_CART_PAGE') && SERVICE_COUPONS_DISPLAY_ON_PAYMENT_PAGE == '1') {
                    ?>
                    <div class="well">
                      <h3 class="no-margin-top"><?php echo $lC_Language->get('text_coupon_code_heading'); ?></h3>
                      <p><?php echo $lC_Language->get('text_coupon_code_instructions'); ?></p>
                      <div class="form-group">
                        <label class="sr-only"></label><input type="text" name="coupon_code" id="coupon_code" class="form-control">
                      </div>
                      <div class="btn-set clearfix no-margin-top no-margin-bottom">
                        <button type="button" class="btn btn-primary pull-right" onclick="addCoupon();"><?php echo $lC_Language->get('text_apply_coupon'); ?></button>
                      </div>
                    </div>
                    <?php 
                  } 
                }
                ?>
              </div>
            </div>        
          </div>
        </div>    
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="no-margin-top no-margin-bottom"><?php echo $lC_Language->get('box_ordering_steps_confirmation'); ?></h3>
          </div>
        </div> 
      </div> 
    </form> 
  </div>
</div> 
<!--content/checkout/checkout_payment.php end-->