<?php
/**  
  @package    catalog::templates::content
  @author     Loaded Commerce, LLC
  @copyright  Copyright 2003-2013 Loaded Commerce Development Team
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
              <th class="text-center hide-on-mobile-portrait" width="12%"><?php echo $lC_Language->get('text_unit_price'); ?></th>
              <th class="text-center" width="80px"><?php echo $lC_Language->get('text_quantity_abbr'); ?></th>
              <th class="text-center" width="12%"><?php echo $lC_Language->get('text_sub_total'); ?></th>
              <th class="text-center" width="6%"></th>
            </tr>
          </thead>
          <tbody>
          <?php 
            foreach ($lC_ShoppingCart->getProducts() as $products) {
            ?>
            <tr id="tr-<?php echo $products['item_id'];?>">
              <td class="text-left" width="<?php echo $lC_Image->getWidth('mini'); ?>px">
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
                  if ( (STOCK_CHECK == '1') && ($lC_ShoppingCart->isInStock($products['item_id']) === false) ) {
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
              <td class="text-center hide-on-mobile-portrait"><span><?php echo $lC_Currencies->displayPrice($products['price'], $products['tax_class_id']); ?></span></td> 
              <td class="text-center"><div class="form-group"><label class="sr-only"></label><input type="number" size="4" name="products[<?php echo $products['item_id']; ?>]" value="<?php echo $products['quantity']; ?>" onfocus="$(this).select();" class="form-control text-center"></div></td>
              <td class="text-center"><span class="price"><?php echo $lC_Currencies->displayPrice($products['price'], $products['tax_class_id'], $products['quantity']); ?></span></td>
              <td class="text-center"><a href="javascript(void);" onclick="deleteItem('<?php echo $products['item_id']; ?>');"><?php echo lc_icon('cart_remove.png'); ?></a></td>
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
          if (STOCK_ALLOW_CHECKOUT == '1') {
            echo '<p class="alert alert-danger text-center">' . sprintf($lC_Language->get('products_out_of_stock_checkout_possible'), STOCK_MARK_PRODUCT_OUT_OF_STOCK) . '</p>';
          } else {
            echo '<p class="alert alert-danger text-center">' . sprintf($lC_Language->get('products_out_of_stock_checkout_not_possible'), STOCK_MARK_PRODUCT_OUT_OF_STOCK) . '</p>';
          }
        }
        ?>         
      </form>
      <div class="col-sm-offset-7 margin-right" id="content-shopping-cart-order-totals">
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
      <div class="clear-both large-margin-top large-margin-bottom pull-right clearfix">
        <button class="btn btn-lg btn-primary" onclick="$('#shopping_cart').submit();" type="button"><?php echo $lC_Language->get('button_update'); ?></button>
        <a href="<?php echo lc_href_link(FILENAME_CHECKOUT, 'shipping', 'SSL'); ?>"><button class="btn btn-lg btn-success" type="button"><?php echo $lC_Language->get('button_checkout'); ?></button></a>
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
                <form role="form" name="coupon" id="coupon" action="">
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
        <a href="<?php echo lc_href_link(FILENAME_PRODUCTS, 'new', 'SSL'); ?>"><button class="btn btn-lg btn-primary pull-right" type="button"><?php echo $lC_Language->get('cart_continue_shopping'); ?></button></a>
      </div>        
      <?php 
    } 
    ?>      
  </div> <!-- /col -->
</div>  
<!--content/checkout/shopping_cart.php end-->