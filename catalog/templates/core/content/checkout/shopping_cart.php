<?php
/**  
  @package    catalog::templates::content
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on DevKit http://www.bootstraptor.com under GPL license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: shopping_cart.php v1.0 2013-08-08 datazen $
*/  
?>
<!--content/checkout/shopping_cart.php start-->
<div class="row">
  <div class="col-sm-12 col-lg-12">
    <h1 class="no-margin-top"><?php echo $lC_Template->getPageTitle(); ?></h1>
    <?php 
    if ( $lC_MessageStack->size('shopping_cart') > 0 ) echo '<div class="message-stack-container alert alert-danger small-margin-bottom">' . $lC_MessageStack->get('shopping_cart') . '</div>' . "\n"; 
    if ($lC_ShoppingCart->hasContents()) { 
      ?>
      <form role="form" class="no-margin-bottom" name="shopping_cart" id="shopping_cart" action="<?php echo lc_href_link(FILENAME_CHECKOUT, 'action=cart_update', 'SSL'); ?>" method="post">
        <table class="table tabled-striped table-responsive no-margin-bottom" id="shopping-cart-table">
          <thead>
            <tr>
              <th><?php echo $lC_Language->get('listing_products_heading'); ?></th>
              <th class="text-left hide-on-mobile-portrait"></th>
              <th class="text-right hide-on-mobile-portrait"><?php echo $lC_Language->get('text_unit_price'); ?></th>
              <th class="text-center large-padding-left"><?php echo $lC_Language->get('text_quantity_abbr'); ?></th>
              <th class="text-right"><?php echo $lC_Language->get('text_sub_total'); ?></th>
              <th class="text-center"></th>
            </tr>
          </thead>
          <tbody>
          <?php 
            foreach ($lC_ShoppingCart->getProducts() as $products) {
            ?>
            <tr id="tr-<?php echo $products['item_id'];?>">
              <td class="text-left content-shopping-cart-image-td">
                <?php 
                  echo lc_link_object(lc_href_link(FILENAME_PRODUCTS, $products['keyword']), $lC_Image->show($products['image'], $products['name'], null, 'mini')); 
                ?>
              </td>
              <td class="text-left hide-on-mobile-portrait">
                <?php 
                  echo '<div class="pull-right">' . lc_link_object(lc_href_link(FILENAME_PRODUCTS, $products['keyword']), strtolower($lC_Language->get('button_edit'))) . '</div>' . "\n";
                  echo '<h4 class="no-margin-top no-margin-bottom">' . lc_link_object(lc_href_link(FILENAME_PRODUCTS, $products['keyword']), $products['name']) . '</h4>' . "\n";
                  echo '<div class="clearfix primary">' . "\n";
                  if (!empty($products['model'])) {
                    echo '<small>' . $lC_Language->get('listing_model_heading') . ': ' . $products['model'] . '</small>' . "\n";
                  }
                  if ( (STOCK_CHECK == '1') && ($lC_ShoppingCart->isInStock($products['id']) === false) ) {
                    echo '<span class="warning">' . STOCK_MARK_PRODUCT_OUT_OF_STOCK . '</span>' . "\n";
                  }
                  if ( $lC_ShoppingCart->isVariant($products['item_id']) ) {
                    foreach ( $lC_ShoppingCart->getVariant($products['item_id']) as $variant) {
                      echo '<br />- ' . $variant['group_title'] . ': ' . $variant['value_title'] . "\n";
                    }
                  }   
                  if ( $lC_ShoppingCart->hasSimpleOptions($products['item_id']) ) {
                    foreach ( $lC_ShoppingCart->getSimpleOptions($products['item_id']) as $option) {
                      echo '<br /><small>- ' . $option['group_title'] . ': ' . $option['value_title'] . '</span>' . "\n";
                    }
                  }                             
                  echo '</div>' . "\n";
                ?>
              </td>
              <td class="text-right hide-on-mobile-portrait"><span><?php echo $lC_Currencies->displayPrice($products['price'], $products['tax_class_id']); ?></span></td> 
              <td class="text-right content-shopping-cart-qty-input-td"><div class="form-group pull-right"><label class="sr-only"></label><input class="form-control content-shopping-cart-qty-input text-center" type="number" name="products[<?php echo $products['item_id']; ?>]" value="<?php echo $products['quantity']; ?>" onfocus="$(this).select();"></div></td>
              <td class="text-right"><span class="price"><?php echo $lC_Currencies->displayPrice($products['price'], $products['tax_class_id'], $products['quantity']); ?></span></td>
              <td class="text-center content-shopping-cart-remove-td"><a href="javascript:void(0);" onclick="deleteItem('<?php echo $products['item_id']; ?>');"><?php echo lc_icon('cart_remove.png'); ?></a></td>
            </tr>
            <?php 
            } 
          ?>
          </tbody>
          <tfoot>
            <tr>
              <td></td>
              <td class="hide-on-mobile-portrait"></td>
              <td class="hide-on-mobile-portrait"></td>
              <td colspan="3"></td>
            </tr>
          </tfoot>
        </table>   
        <?php
        if ( (STOCK_CHECK == '1') && ($lC_ShoppingCart->hasStock() === false) ) {
          if (AUTODISABLE_OUT_OF_STOCK_PRODUCT == '-1') {
            echo '<p class="alert alert-danger text-center">' . sprintf($lC_Language->get('products_out_of_stock_checkout_possible'), STOCK_MARK_PRODUCT_OUT_OF_STOCK) . '</p>';
          } else {
            echo '<p class="alert alert-danger text-center">' . sprintf($lC_Language->get('products_out_of_stock_checkout_not_possible'), STOCK_MARK_PRODUCT_OUT_OF_STOCK) . '</p>';
          }
        }
        ?>         
      </form>
      <div class="row" id="content-shopping-cart-order-totals">
        <div id="content-shopping-cart-order-totals-left" class="col-sm-6 col-lg-6"></div>
        <div id="content-shopping-cart-order-totals-right" class="col-sm-6 col-lg-6">
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
      if (isset($_SESSION['PPEC_PROCESS']) && !empty($_SESSION['PPEC_PROCESS'])) { 
      } else { 
        if ((defined('ADDONS_PAYMENT_PAYPAL_PAYMENTS_ADVANCED_STATUS') && ADDONS_PAYMENT_PAYPAL_PAYMENTS_ADVANCED_STATUS == '1') && (defined('ADDONS_PAYMENT_PAYPAL_PAYMENTS_ADVANCED_EC_STATUS') && ADDONS_PAYMENT_PAYPAL_PAYMENTS_ADVANCED_EC_STATUS == 'On')) { 
          ?>
          <div id="paypal-ec-button-container" style="float: right; margin:20px 4px 0px 0;">
            <div id="paypal-ec-button">
              <a href="<?php echo lc_href_link(FILENAME_CHECKOUT, 'shipping&ppec=process', 'SSL'); ?>"><img style="vertical-align: middle;" src="https://www.paypalobjects.com/en_US/i/btn/btn_xpressCheckout.gif"></a><br />
              <span style="margin:0 58px;">-OR-</span>
            </div>
          </div>
          <?php 
        }
      } 
      ?>
      <div class="clear-both btn-set">
        <div class="margin-top large-margin-bottom pull-left">
          <button onclick="window.location.href='<?php echo lc_href_link(FILENAME_PRODUCTS, 'new', 'SSL'); ?>'" class="btn btn-primary" type="button"><?php echo $lC_Language->get('cart_continue_shopping'); ?></button>
        </div>      
        <div class="margin-top large-margin-bottom pull-right">
          <button class="btn btn-lg btn-default" onclick="$('#shopping_cart').submit();" type="button"><?php echo $lC_Language->get('button_update'); ?></button>
          <button onclick="window.location.href='<?php echo lc_href_link(FILENAME_CHECKOUT, 'shipping', 'SSL'); ?>'" class="btn btn-lg btn-success" type="button"><?php echo $lC_Language->get('button_checkout'); ?></button>
        </div>  
      </div> 
      <?php
      if ($lC_Customer->isLoggedOn() !== false) {
        if (defined('MODULE_SERVICES_INSTALLED') && in_array('coupons', explode(';', MODULE_SERVICES_INSTALLED)) && 
            defined('SERVICE_COUPONS_DISPLAY_ON_CART_PAGE') && SERVICE_COUPONS_DISPLAY_ON_CART_PAGE == '1') {
          ?>
          <div class="row clear-both clearfix">
            <div class="col-sm-6 col-lg-6"></div>
            <div class="col-sm-6 col-lg-6">
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
            </div>
          </div>
          <?php 
        } 
      }
    } else {  
      ?>
      <div class="well large-margin-top">
        <p class="no-margin-bottom"><?php echo $lC_Language->get('shopping_cart_empty'); ?></p>
      </div>
      <div class="btn-set clearfix">  
        <form action="<?php echo lc_href_link(FILENAME_PRODUCTS, 'new', 'SSL'); ?>" method="post"><button onclick="$(this).closest('form').submit();" class="pull-right btn btn-lg btn-primary" type="submit"><?php echo $lC_Language->get('cart_continue_shopping'); ?></button></form>
      </div>        
      <?php 
    } 
    ?>      
  </div> <!-- /col -->
</div>  
<script>
$(document).ready(function() {
  var rows = $('#content-center-container').attr('class');
  if (rows == 'col-sm-6 col-lg-6') {
    $('#content-shopping-cart-order-totals-left').attr('class', 'col-sm-5 col-lg-5');  
    $('#content-shopping-cart-order-totals-right').attr('class', 'col-sm-7 col-lg-7');  
  } else if (rows == 'col-sm-9 col-lg-9') {
    $('#content-shopping-cart-order-totals-left').attr('class', 'col-sm-6 col-lg-6');  
    $('#content-shopping-cart-order-totals-right').attr('class', 'col-sm-6 col-lg-6');   
  } else {
    $('#content-shopping-cart-order-totals-left').attr('class', 'col-sm-8 col-lg-8');  
    $('#content-shopping-cart-order-totals-right').attr('class', 'col-sm-4 col-lg-4');  
  }
});
</script>
<!--content/checkout/shopping_cart.php end-->