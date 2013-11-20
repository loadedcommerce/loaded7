<?php
/**  
  @package    catalog::templates::content
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on DevKit http://www.bootstraptor.com under GPL license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: checkout_success.php v1.0 2013-08-08 datazen $
*/ 
$oID = lC_Success::getOrderID($lC_Customer->getID());
?>
<!--content/checkout/checkout_success.php start-->
<div class="row">
  <div class="col-sm-12 col-lg-12 large-margin-bottom">  
    <h1 class="no-margin-top"><?php echo $lC_Language->get('text_checkout'); ?></h1>
    <?php 
    if ( $lC_MessageStack->size('checkout_success') > 0 ) echo '<div class="message-stack-container alert alert-danger small-margin-bottom">' . $lC_MessageStack->get('checkout_success') . '</div>' . "\n"; 
    ?>
    <div id="content-checkout-shipping-container">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="no-margin-top no-margin-bottom"><?php echo $lC_Language->get('box_ordering_steps_complete'); ?></h3>
        </div>
        <div class="panel-body no-padding-bottom">
          <div class="alert alert-success"><h3 class="no-margin-top no-margin-bottom"><img class="margin-right" alt="<?php echo $lC_Language->get('success_heading'); ?>" src="templates/core/images/icons/32/success.png"><?php echo $lC_Language->get('success_heading'); ?></h3></div>
          <div class="row">
            <div class="col-sm-4 col-lg-4">
              <?php
              if (defined('SKIP_CHECKOUT_SHIPPING_PAGE') && SKIP_CHECKOUT_SHIPPING_PAGE == '1') {
              } else {
                ?>
                <div class="well relative no-padding-bottom">
                  <h4 class="no-margin-top"><?php echo $lC_Language->get('ship_to_address'); ?></h4>
                  <address>
                    <?php echo lC_Address::format(lC_Success::getShippingAddress($oID, $lC_Customer->getID()), '<br />'); ?>                
                  </address>
                </div>
                <div class="well relative clearfix small-padding-top small-padding-bottom"> 
                  <h4><?php echo $lC_Language->get('shipping_method_heading'); ?></h4>
                  <p>              
                  <?php 
                    foreach (lC_Success::getOrderTotals($oID) as $module) {
                      if ($module['class'] == 'shipping') {
                        echo $module['title']; 
                      }                    
                    } 
                    ?>
                  </p>
                </div>  
              <?php
              }
              ?> 
              <div class="well relative no-padding-bottom">
                <h4 class="no-margin-top"><?php echo $lC_Language->get('bill_to_address'); ?></h4>
                <address>
                  <?php echo lC_Address::format(lC_Success::getBillingAddress($oID, $lC_Customer->getID()), '<br />'); ?>                
                </address>
              </div>
              <div class="well relative clearfix small-padding-top small-padding-bottom"> 
                <h4><?php echo $lC_Language->get('payment_method_heading'); ?></h4>
                <p><?php echo lC_Success::getPaymentMethod($oID); ?></p>                  
              </div>       
              <div class="well relative clearfix small-padding-top small-padding-bottom"> 
                <h4><?php echo $lC_Language->get('order_comment_title'); ?></h4>
                <div class="form-group">
                  <?php echo (lC_Success::getOrderComments($oID) != null) ? lC_Success::getOrderComments($oID) : $lC_Language->get('order_comment_none'); ?>
                </div>                  
              </div>                                        
            </div>
            <div class="col-sm-8 col-lg-8">
              <h3 class="no-margin-top">
                <span class="pull-right"><?php echo $lC_Language->get('checkout_order_number') . '&nbsp;' . $_SESSION['cartID']; ?></span>
                <span class="pull-left"><?php echo $lC_Language->get('checkout_order_id') . '&nbsp;' . $oID; ?></span>
              </h3>
              <div class="clearfix" id="content-checkout-success-products-table">
                <table class="table responsive-table no-margin-bottom">
                  <thead>
                    <tr><th colspan="3"><?php echo $lC_Language->get('order_products_title'); ?></th></tr>
                  </thead>
                  <?php
                  foreach (lC_Success::getOrderProducts($oID) as $products) {
                    echo '<tr class="confirmation-products-listing-row">' . "\n" .
                         '  <td class="content-checkout-success-qty-td">' . $products['quantity'] . '&nbsp;x&nbsp;</td>' . "\n" .
                         '  <td><span class="text-info strong">' . $products['name'] . '</span>' . "\n";
                    echo '<br /><span class="confirmation-products-listing-model">' . $lC_Language->get('listing_model_heading') . ': ' . $products['model'] . '</span>';

                    if ( is_array($products['options']) && empty($products['options']) === false ) {
                      foreach ( $products['options'] as $key => $val) {
                        echo '<br /><small>- ' . $val['group_title'] . ': ' . $val['value_title'] . '</small>';
                      }
                    }                     
                    if ( lC_Success::isVariant($products['id']) === true ) {
                      foreach ( lC_Success::getVariants($products['id']) as $variant) {
                        echo '<br /><small>- ' . $val['group_title'] . ': ' . $val['value_title'] . '</small>';
                      }
                    }                    
                    echo '</td>' . "\n";
                    echo '<td class="text-right">' . $lC_Currencies->displayPrice($products['price'], $products['tax_class_id'], $products['quantity']) . '</td>' . "\n" .
                    '</tr>' . "\n";
                  }
                  ?>
                </table> 
                <table class="table margin-bottom-neg"><tr><td>&nbsp;</td></tr></table>
              </div>
              <div class="col-sm-offset-6 margin-right" id="content-shopping-cart-order-totals">
                <?php
                foreach (lC_Success::getOrderTotals($oID) as $module) { 
                  ?>
                  <div class="clearfix">
                    <span class="pull-left ot-<?php echo strtolower(str_replace('_', '-', $module['class'])); ?>"><?php echo strip_tags($module['title']); ?></span>
                    <span class="pull-right ot-<?php echo strtolower(str_replace('_', '-', $module['class'])); ?>"><?php echo strip_tags($module['text']); ?></span>                
                  </div>                    
                  <?php
                }
                ?>     
              </div>
              <div class="well large-margin-top padding-bottom">
                <form name="checkout_success" id="checkout_success" action="<?php echo lc_href_link(FILENAME_CHECKOUT, 'success=update', 'SSL'); ?>" method="post">
                  <?php
                    $products_array = lC_Success::globalNotifications($lC_Customer->getID());
                    if (isset($products_array) && !empty($products_array)) {
                      echo $lC_Language->get('add_selection_to_product_notifications') . '<br />';
                      $products_displayed = array();
                      for ($i=0, $n=sizeof($products_array); $i<$n; $i++) {
                        if (!in_array($products_array[$i]['id'], $products_displayed)) {
                          echo '<div class="checkbox"><label class="">' . lc_draw_checkbox_field('notify[]', $products_array[$i]['id'], null, null, null) . $products_array[$i]['text'] . '</label></div>' . "\n";
                          $products_displayed[] = $products_array[$i]['id'];
                        }
                      }
                    } else {
                      echo sprintf($lC_Language->get('view_order_history'), lc_href_link(FILENAME_ACCOUNT, null, 'SSL'), lc_href_link(FILENAME_ACCOUNT, 'orders', 'SSL')) . '<br /><br />' . sprintf($lC_Language->get('contact_store_owner'), lc_href_link(FILENAME_INFO, 'contact'));
                    }
                  ?>
                </form>
              </div>               
              <div class="btn-set clearfix">
                <button id="content-checkout-success-confirm-button" class="btn btn-lg btn-success pull-right" onclick="$('#checkout_success').submit();" type="button"><?php echo $lC_Language->get('button_continue'); ?></button>
              </div> 
              <?php
                if (DOWNLOAD_ENABLED == '1') {
                ?>
                <div class="well">
                  <table class="table">
                    <?php
                    if (file_exists(DIR_FS_TEMPLATE . 'modules/downloads.php')) {
                      require($lC_Vqmod->modCheck(DIR_FS_TEMPLATE . 'modules/downloads.php'));
                    } else {
                      require($lC_Vqmod->modCheck('includes/modules/downloads.php')); 
                    }    
                    ?>
                  </table>
                </div>
                <?php
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
});
</script>
<!--content//checkout_success.php end-->