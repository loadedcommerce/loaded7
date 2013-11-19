<?php
/**  
  @package    catalog::templates::content
  @author     Loaded Commerce, LLC
  @copyright  Copyright 2003-2013 Loaded Commerce Development Team
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on DevKit http://www.bootstraptor.com under GPL license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: checkout_confirmation.php v1.0 2013-08-08 datazen $
*/
?>
<!--content/checkout/checkout_confirmation.php start-->
<div class="row">
  <div class="col-sm-12 col-lg-12 large-margin-bottom">  
    <h1 class="no-margin-top"><?php echo $lC_Language->get('text_checkout'); ?></h1>
    <?php 
    if ( $lC_MessageStack->size('checkout_confirmation') > 0 ) echo '<div class="message-stack-container alert alert-danger small-margin-bottom">' . $lC_MessageStack->get('checkout_confirmation') . '</div>' . "\n"; 
    ?>
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
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="no-margin-top no-margin-bottom"><?php echo $lC_Language->get('box_ordering_steps_confirmation'); ?></h3>
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
              <div class="well relative clearfix small-padding-top small-padding-bottom"> 
                <h4><?php echo $lC_Language->get('shipping_method_heading'); ?></h4>
                <p><?php echo $lC_ShoppingCart->getShippingMethod('title'); ?></p>
                <div class="btn-group clearfix absolute-top-right small-padding-right small-padding-top">
                  <button type="button" onclick="window.location.href='<?php echo lc_href_link(FILENAME_CHECKOUT, 'shipping', 'SSL'); ?>'" class="btn btn-default btn-xs"><?php echo $lC_Language->get('button_edit'); ?></button>
                </div>                  
              </div>   
              <div class="well relative no-padding-bottom">
                <h4 class="no-margin-top"><?php echo $lC_Language->get('bill_to_address'); ?></h4>
                <address>
                  <?php echo lC_Address::format($lC_ShoppingCart->getBillingAddress(), '<br />'); ?>                
                </address>
                <div class="btn-group clearfix absolute-top-right small-padding-right small-padding-top">
                  <button type="button" onclick="window.location.href='<?php echo lc_href_link(FILENAME_CHECKOUT, 'payment_address', 'SSL'); ?>';" class="btn btn-default btn-xs"><?php echo $lC_Language->get('button_edit'); ?></button>
                </div>
              </div>
              <div class="well relative clearfix small-padding-top small-padding-bottom"> 
                <h4><?php echo $lC_Language->get('payment_method_heading'); ?></h4>
                <p><?php echo $lC_ShoppingCart->getBillingMethod('title'); ?></p>
                <div class="btn-group clearfix absolute-top-right small-padding-right small-padding-top">
                  <button type="button" onclick="window.location.href='<?php echo lc_href_link(FILENAME_CHECKOUT, 'payment', 'SSL'); ?>';" class="btn btn-default btn-xs"><?php echo $lC_Language->get('button_edit'); ?></button>
                </div>                  
              </div>
              <form name="checkout_confirmation" id="checkout_confirmation" action="<?php echo ($lC_Payment->hasActionURL()) ? $lC_Payment->getActionURL() : lc_href_link(FILENAME_CHECKOUT, 'process', 'SSL'); ?>" method="post">       
              <div class="well relative clearfix small-padding-top small-padding-bottom"> 
                <h4><?php echo $lC_Language->get('order_comment_title'); ?></h4>
                <div class="form-group">
                  <?php echo lc_draw_textarea_field('comments', (isset($_SESSION['comments']) ? $_SESSION['comments'] : null), 60, 5, 'class="form-control" placeholder="' . $lC_Language->get('text_add_comment_to_order') . '" onkeyup="sendOrderCommentsToSession(this.value);"'); ?>
                </div>                 
              </div>                                        
            </div>
            <div class="col-sm-8 col-lg-8">
              <div class="" id="content-checkout-confirmation-products-table">
                <table class="table responsive-table no-margin-bottom">
                  <?php
                  if ($lC_ShoppingCart->numberOfTaxGroups() > 1) {
                    ?>
                    <thead>
                      <tr>
                        <th colspan="2"><?php echo $lC_Language->get('order_products_title'); ?></th>
                        <th class="text-right"><?php echo $lC_Language->get('order_tax_title'); ?></th>
                        <th class="text-right"><?php echo $lC_Language->get('order_total_title'); ?></th>
                      </tr>
                    </thead>
                    <?php
                  } else {
                    ?>
                    <thead>
                      <tr>
                        <th colspan="2"><?php echo $lC_Language->get('order_products_title'); ?></th>
                        <th>&nbsp;</th>
                      </tr>
                    </thead>
                    <?php
                  }
                  foreach ($lC_ShoppingCart->getProducts() as $products) {
                    echo '<tr class="confirmation-products-listing-row">' . "\n" .
                         '  <td class="content-checkout-confirmation-qty-td">' . $products['quantity'] . '&nbsp;x&nbsp;</td>' . "\n" .
                         '  <td><span class="text-info strong">' . $products['name'] . '</span>' . "\n";
                    if ( (STOCK_CHECK == '1') && !$lC_ShoppingCart->isInStock($products['item_id']) ) {
                      echo '<span class="text-danger small-margin-left">' . STOCK_MARK_PRODUCT_OUT_OF_STOCK . '</span>' . "\n";
                    }
                    echo '<br /><span class="confirmation-products-listing-model">' . $lC_Language->get('listing_model_heading') . ': ' . $products['model'] . '</span>';
                    if ( $lC_ShoppingCart->isVariant($products['item_id']) ) {
                      foreach ( $lC_ShoppingCart->getVariant($products['item_id']) as $variant) {
                        echo '<br /><small>- ' . $variant['group_title'] . ': ' . $variant['value_title'] . '</small>' . "\n";
                      }
                    }
                    if ( $lC_ShoppingCart->hasSimpleOptions($products['item_id']) ) {
                      foreach ( $lC_ShoppingCart->getSimpleOptions($products['item_id']) as $option) {
                        echo '<br /><small>- ' . $option['group_title'] . ': ' . $option['value_title'] . '</small>' . "\n";
                      }
                    }                        
                    echo '</td>' . "\n";
                    if ($lC_ShoppingCart->numberOfTaxGroups() > 1) {
                      echo '<td class="text-right">' . lC_Tax::displayTaxRateValue($products['tax']) . '</td>' . "\n";
                    }
                    echo '<td class="text-right">' . $lC_Currencies->displayPrice($products['price'], $products['tax_class_id'], $products['quantity']) . '</td>' . "\n" .
                    '</tr>' . "\n";
                  }
                  ?>
                </table> 
                <table class="table margin-bottom-neg"><tr><td>&nbsp;</td></tr></table>
              </div>
              <div class="row padding-right" id="content-checkout-confirmation-order-totals">
                <div id="content-checkout-confirmation-order-totals-left" class="col-sm-5 col-lg-5"></div>
                <div id="content-checkout-confirmation-order-totals-right" class="col-sm-7 col-lg-7">
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
              <?php 
              /* ppec inject: we will be moving this to the addon */ 
              if (isset($_SESSION['PPEC_PROCESS']['LINK']) && $_SESSION['PPEC_PROCESS']['LINK'] != NULL) $form_action_url = $_SESSION['PPEC_PROCESS']['LINK'];
              if ($lC_Payment->hasActive()) echo $lC_Payment->process_button();                 
              if (DISPLAY_CONDITIONS_ON_CHECKOUT == '1') {
                ?>     
                <div class="well padding-top padding-bottom large-margin-top">
                  <div class="checkbox">
                    <?php echo lc_draw_checkbox_field('conditions', array(array('id' => 1, 'text' => $lC_Language->get('order_conditions_acknowledge'))), false); ?>
                  </div>
                </div>                    
                <?php
              }
              ?>
              </form>
              <div class="btn-set clearfix">
                <button id="content-checkout-confirmation-confirm-button" class="btn btn-lg btn-success pull-right" onclick="$('#checkout_confirmation').submit();" type="button"><?php echo $lC_Language->get('button_confirm_order'); ?></button>
                <button class="btn btn-lg btn-default" onclick="window.location.href='<?php echo lc_href_link(FILENAME_CHECKOUT, 'payment', 'SSL'); ?>';" type="button"><?php echo $lC_Language->get('button_back'); ?></button>
              </div> 
              <?php
              if ($lC_Customer->isLoggedOn() !== false) {
                if (defined('MODULE_SERVICES_INSTALLED') && in_array('coupons', explode(';', MODULE_SERVICES_INSTALLED)) && 
                    defined('SERVICE_COUPONS_DISPLAY_ON_CART_PAGE') && SERVICE_COUPONS_DISPLAY_ON_CART_PAGE == '1') {
                  ?>
                  <div class="well">
                    <h3 class="no-margin-top"><?php echo $lC_Language->get('text_coupon_code_heading'); ?></h3>
                    <p><?php echo $lC_Language->get('text_coupon_code_instructions'); ?></p>
                    <form role="form" name="coupon" id="coupon">
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
    </div> 
  </div>
</div> 
<script>
$(document).ready(function() {
  if (_setMediaType() == 'mobile-portrait') $('#content-checkout-confirmation-confirm-button').html('<?php echo $lC_Language->get('button_confirm'); ?>');
  var rows = $('#content-center-container').attr('class');
  if (rows == 'col-sm-6 col-lg-6') {
    $('#content-checkout-confirmation-order-totals-left').attr('class', 'col-sm-1 col-lg-1');  
    $('#content-checkout-confirmation-order-totals-right').attr('class', 'col-sm-11 col-lg-11');  
  } else if (rows == 'col-sm-9 col-lg-9') {
    $('#content-checkout-confirmation-order-totals-left').attr('class', 'col-sm-5 col-lg-5');  
    $('#content-checkout-confirmation-order-totals-right').attr('class', 'col-sm-7 col-lg-7');   
  } else {
    $('#content-checkout-confirmation-order-totals-left').attr('class', 'col-sm-6 col-lg-6');  
    $('#content-checkout-confirmation-order-totals-right').attr('class', 'col-sm-6 col-lg-6');  
  }
});

$("#checkout_confirmation").submit(function() {
  var enabled = '<?php echo ((DISPLAY_CONDITIONS_ON_CHECKOUT == '1') ? true : false); ?>';
  if (enabled) {
    if($('#conditions').is(':checked')){
    } else {
      alert('<?php echo $lC_Language->get('error_conditions_not_accepted'); ?>');
      return false;
    }
  }
});
</script>
<!--content//checkout_confirmation.php end-->