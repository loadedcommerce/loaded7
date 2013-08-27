<?php
/**  
  @package    catalog::templates::content
  @author     Loaded Commerce, LLC
  @copyright  Copyright 2003-2013 Loaded Commerce Development Team
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on DevKit http://www.bootstraptor.com under GPL license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: checkout_shipping.php v1.0 2013-08-08 datazen $
*/ 
?>
<!--content/checkout/checkout_shipping.php start-->
<div class="row">
  <div class="col-sm-12 col-lg-12 large-margin-bottom">  
    <h1 class="no-margin-top"><?php echo $lC_Language->get('text_checkout'); ?></h1>
    <?php 
    if ( $lC_MessageStack->size('checkout_shipping') > 0 ) echo '<div class="message-stack-container alert alert-danger small-margin-bottom">' . $lC_MessageStack->get('checkout_shipping') . '</div>' . "\n"; 
    ?>
    <form name="checkout_shipping" id="checkout_shipping" action="<?php echo lc_href_link(FILENAME_CHECKOUT, 'shipping=process', 'SSL'); ?>" method="post">
      <div id="content-checkout-shipping-container">
        <div class="panel panel-default no-margin-bottom">
          <div class="panel-heading">
            <h3 class="no-margin-top no-margin-bottom"><?php echo $lC_Language->get('box_ordering_steps_delivery'); ?></h3>
          </div>
          <div class="panel-body no-padding-bottom">
            <div class="row">
              <div class="col-sm-4 col-lg-4">
                <div class="well relative no-padding-bottom">
                  <h4 class="no-margin-top"><?php echo $lC_Language->get('ship_to_address'); ?></h4>
                  <address>
                    <?php echo lC_Address::format($lC_ShoppingCart->getShippingAddress(), '<br />'); ?>                
                  </address>
                  <div class="checkbox">
                    <input type="checkbox" name="shipto_as_billable" id="shipto_as_billable"><label class="small-margin-left"><?php echo $lC_Language->get('billable_address_checkbox'); ?></label>
                  </div>
                  <div class="btn-group clearfix absolute-top-right">
                    <a href="<?php echo lc_href_link(FILENAME_CHECKOUT, 'shipping_address', 'SSL'); ?>"><button type="button" class="btn btn-default btn-xs"><?php echo $lC_Language->get('button_edit'); ?></button></a>
                  </div>
                </div>
                <div class="well">
                  <div class="clearfix">
                    <span class="strong pull-left"><?php echo $lC_Language->get('checkout_order_number'); ?></span>
                    <span class="strong pull-right"><?php echo $_SESSION['cartID']; ?></span>                
                  </div>
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
                <div class="">
                  <?php
                  if ($lC_Shipping->hasQuotes()) {
                    ?>
                    <h3><?php echo $lC_Language->get('shipping_method_title'); ?></h3>
                    <?php 
                    echo ($lC_Shipping->numberOfQuotes() > 1) ? '<div class="alert alert-warning">' . $lC_Language->get('choose_shipping_method') . '</div>' : '<div class="alert alert-warning">' . $lC_Language->get('only_one_shipping_method_available') . '</div>' . "\n"; 

                    $radio_buttons = 0;
                    foreach ($lC_Shipping->getQuotes() as $quotes) {
                      ?>
                      <h4><?php echo $quotes['module']; ?></h4>
                      <table class="content-shipping-methods-table table table-hover table-responsive">
                        <?php
                        if (isset($quotes['error'])) {
                          ?>
                          <tr><td colspan="3" class=""><?php echo $quotes['error']; ?></td></tr>
                          <?php
                        } else {
                          $counter = 0;   
                          foreach ($quotes['methods'] as $methods) {
                            if (($quotes['id'] . '_' . $methods['id'] == $lC_ShoppingCart->getShippingMethod('id')) || sizeof($quotes['methods']) == 1) {
                              echo '<tr class="module-row-selected cursor-pointer" id="default-selected" onclick="selectRowEffect(this, ' . $radio_buttons . ')">' . "\n";
                            } else {
                              echo '<tr class="module-row cursor-pointer" onclick="selectRowEffect(this, ' . $radio_buttons . ')">' . "\n";
                            }
                            ?>
                            <td width="75%"><?php echo $methods['title']; ?></td>
                            <?php
                              if ( ($lC_Shipping->numberOfQuotes() > 1) || (sizeof($quotes['methods']) > 1) ) {
                              ?>
                              <td><?php echo $lC_Currencies->displayPrice($methods['cost'], $quotes['tax_class_id']); ?></td>
                              <td class="text-right"><?php echo lc_draw_radio_field('shipping_mod_sel', $quotes['id'] . '_' . $methods['id'], $lC_ShoppingCart->getShippingMethod('id'), 'id="' . $quotes['id'] . '_' . $counter . '"',''); ?></span></td>
                              <?php
                            } else {
                              $counter = 0;
                              foreach ($quotes['methods'] as $methods) {
                                if (($quotes['id'] . '_' . $methods['id'] == $lC_ShoppingCart->getShippingMethod('id')) || sizeof($quotes['methods']) == 1) {
                                  echo '<tr id="defaultSelected" class="moduleRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="selectRowEffect(this, ' . $radio_buttons . ')">' . "\n";
                                } else {
                                  echo '<tr class="moduleRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="selectRowEffect(this, ' . $radio_buttons . ')">' . "\n";
                                }
                              ?>
                              <td colspan="2" class="text-right"><?php echo $lC_Currencies->displayPrice($methods['cost'], $quotes['tax_class_id']) . lc_draw_hidden_field('shipping_mod_sel', $quotes['id'] . '_' . $methods['id']); ?></td>
                              <?php
                            }
                            ?>
                            </tr>
                            <?php
                            $counter++;
                            $radio_buttons++;
                          }
                        }
                        ?>
                      </table>
                      <?php                          
                    }
                  }
                  ?>                
                </div>
                <div class="btn-set clearfix">
                  <button class="btn btn-lg btn-success pull-right" onclick="$('#checkout_shipping').submit();" type="button"><?php echo $lC_Language->get('button_continue'); ?></button>
                  <a href="<?php echo lc_href_link(FILENAME_CHECKOUT, '', 'SSL'); ?>"><button class="btn btn-lg btn-default" type="button"><?php echo $lC_Language->get('button_back'); ?></button></a>
                </div> 
                <?php
                if ($lC_Customer->isLoggedOn() !== false) {
                  if (defined('MODULE_SERVICES_INSTALLED') && in_array('coupons', explode(';', MODULE_SERVICES_INSTALLED)) && 
                      defined('SERVICE_COUPONS_DISPLAY_ON_CART_PAGE') && SERVICE_COUPONS_DISPLAY_ON_CART_PAGE == '1') {
                    ?>
                    <div class="well">
                      <h3 class="no-margin-top"><?php echo $lC_Language->get('text_coupon_code_heading'); ?></h3>
                      <p><?php echo $lC_Language->get('text_coupon_code_instructions'); ?></p>
                      <form role="form" name="coupon" id="coupon" action="">
                        <div class="form-group">
                          <label class="sr-only"></label><input type="text" name="coupon_code" id="coupon_code" class="form-control">
                        </div>
                      </form>
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
        <div class="clearfix panel panel-default no-margin-bottom">
          <div class="panel-heading">
            <h3 class="no-margin-top no-margin-bottom"><?php echo $lC_Language->get('box_ordering_steps_payment'); ?></h3>
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
<!--content/checkout/checkout_shipping.php end-->