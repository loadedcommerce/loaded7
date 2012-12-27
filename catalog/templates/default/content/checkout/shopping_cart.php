<?php
/*
  $Id: shopping_cart.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
?>
<!--SHOPPING CART SECTION STARTS-->
  <!--SHOPPING CART DETAILS STARTS-->
  <div id="shopping_cart_content" class="full_page">
    <h1><?php echo $lC_Template->getPageTitle(); ?></h1>
    <?php 
      if ($lC_ShoppingCart->hasContents()) { 
    ?>
    <!--ADD TO CART SUCCESS MESSAGE STARTS
    <div class="message success">The Product was added to your shopping cart.</div>
    ADD TO CART SUCCESS MESSAGE ENDS-->
    <!--ACTION BUTTON BAR STARTS-->
    <div class="action_buttonbar">
      <a onclick="$('#shopping_cart').submit();"><button type="button" class="continue"><?php echo $lC_Language->get('button_update'); ?></button></a>&nbsp;
      <a href="<?php echo lc_href_link(FILENAME_PRODUCTS, 'new'); ?>" style="text-decoration: none;"><button type="button" title="" class="continue"><?php echo $lC_Language->get('cart_continue_shopping'); ?></button></a>
      <button type="button" onclick="location='<?php echo lc_href_link(FILENAME_CHECKOUT, 'shipping', 'SSL'); ?>'" title="" class="checkout"><?php echo $lC_Language->get('button_checkout'); ?></button>
    </div>
    <!--ACTION BUTTON BAR ENDS-->
    <!--SHOPPING CART TABLE STARTS-->
    <div class="cart_table">
      <form name="shopping_cart" id="shopping_cart" action="<?php echo lc_href_link(FILENAME_CHECKOUT, 'action=cart_update', 'SSL'); ?>" method="post">
      <table class="data-table cart-table" id="shopping-cart-table" cellpadding="0" cellspacing="0">
        <tr>
          <th colspan="2"><?php echo $lC_Language->get('listing_products_heading'); ?></th>
          <th class="align_center hide-mobile-portrait"><?php echo $lC_Language->get('listing_model_heading'); ?></th>
          <th class="align_center" width="10%"><?php echo $lC_Language->get('text_quantity'); ?></th>
          <th class="align_center" width="12%"><?php echo $lC_Language->get('text_unit_price'); ?></th>
          <th class="align_center" width="12%"><?php echo $lC_Language->get('text_total'); ?></th>
          <th class="align_center" width="6%"><?php echo $lC_Language->get('cart_remove'); ?></th>
        </tr>
        <?php 
          foreach ($lC_ShoppingCart->getProducts() as $products) {
        ?>
        <tr>
          <td>
          <?php 
            echo lc_link_object(lc_href_link(FILENAME_PRODUCTS, $products['keyword']), $lC_Image->show($products['image'], $products['name'], null, 'mini')); 
          ?>
          </td>
          <td class="align_left">
          <?php 
            echo lc_link_object(lc_href_link(FILENAME_PRODUCTS, $products['keyword']), $products['name'], 'class="pr_name"') . '</a>';
            if ( (STOCK_CHECK == '1') && ($lC_ShoppingCart->isInStock($products['item_id']) === false) ) {
              echo '<span class="markProductOutOfStock">' . STOCK_MARK_PRODUCT_OUT_OF_STOCK . '</span>';
            }
            if ( $lC_ShoppingCart->isVariant($products['item_id']) ) {
              foreach ( $lC_ShoppingCart->getVariant($products['item_id']) as $variant) {
                echo '<br />- ' . $variant['group_title'] . ': ' . $variant['value_title'];
              }
            }              
          ?>
          </td>
          <td class="align_center vline hide-mobile-portrait"><span class="price"><?php echo $products['model']; ?></span></td>
          <td class="align_center vline"><?php echo lc_draw_input_field('products[' . $products['item_id'] . ']', $products['quantity'], 'class="qty_box"'); ?></td>
          <td class="align_center vline"><span class="price"><?php echo $lC_Currencies->displayPrice($products['price'], $products['tax_class_id']); ?></span></td> 
          <td class="align_center vline"><span class="price"><?php echo $lC_Currencies->displayPrice($products['price'], $products['tax_class_id'], $products['quantity']); ?></span></td>
          <td class="align_center vline"><?php echo lc_draw_checkbox_field('delete[' . $products['item_id'] . ']'); ?></td>
        </tr>
        <?php 
          } 
        ?>
      </table>
      <!--SHOPPING CART TABLE ENDS-->
      <!--OUT OF STOCK STARTS-->
      <?php
        if ( (STOCK_CHECK == '1') && ($lC_ShoppingCart->hasStock() === false) ) {
          if (STOCK_ALLOW_CHECKOUT == '1') {
            echo '<br /><p class="stockWarning" align="center">' . sprintf($lC_Language->get('products_out_of_stock_checkout_possible'), STOCK_MARK_PRODUCT_OUT_OF_STOCK) . '</p>';
          } else {
            echo '<br /><p class="stockWarning" align="center">' . sprintf($lC_Language->get('products_out_of_stock_checkout_not_possible'), STOCK_MARK_PRODUCT_OUT_OF_STOCK) . '</p>';
          }
        }
      ?>
      <!--OUT OF STOCK ENDS-->
      </form>
      <!--ORDER TOTAL LISTING STARTS-->
      <div class="totals">
        <table id="totals-table">
          <?php 
            foreach ($lC_ShoppingCart->getOrderTotals() as $module) {
          ?>
          <tr>
            <td class="align_left<?php if ($module['code'] == 'sub_total') echo ' sc_sub_total'; if ($module['code'] == 'total') echo ' sc_total'; ?>" style="padding-right:10px;"><?php echo $module['title']; ?></td>
            <td class="align_right<?php if ($module['code'] == 'sub_total') echo ' sc_sub_total'; if ($module['code'] == 'total') echo ' sc_total'; ?>"><?php echo $module['text']; ?></td>
          </tr>
          <?php
            }
          ?>            
        </table>
      </div>
      <!--ORDER TOTAL LISTING ENDS-->
    </div>
    <!--ACTION BUTTON BAR STARTS-->
    <div class="action_buttonbar">
      <button type="button" class="continue" onclick="$('#shopping_cart').submit();"><?php echo $lC_Language->get('button_update'); ?></button>&nbsp;
      <a href="<?php echo lc_href_link(FILENAME_PRODUCTS, 'new'); ?>" class="noDecoration hide-mobile-portrait"><button type="button" class="continue"><?php echo $lC_Language->get('cart_continue_shopping'); ?></button></a>
      <button type="button" onclick="location='<?php echo lc_href_link(FILENAME_CHECKOUT, 'shipping', 'SSL'); ?>'" class="checkout"><?php echo $lC_Language->get('button_checkout'); ?></button>
    </div>
    <!--ACTION BUTTON BAR ENDS-->
  </div>
  <!--SHOPPING CART DETAILS ENDS-->
  <!--ADDITIONAL CART DETAILS STARTS
  <div class="checkout_tax">
    <!--SHIPPING ESTIMATION STARTS
    <div class="shipping_tax">
      <h4>Estimate Shipping and Tax</h4>
        <p>Enter your destination to get a shipping estimate.</p>
        <label>Country</label><select><option>Canada</option></select><label>Postal code</label><input type="text">
        <br class="clear"/>
        <label>State</label><select><option>Vancouver</option></select>
        <button type="button" title="" class="brown_btn">Get a Quote</button>
    </div>
    <!--SHIPPING ESTIMATION ENDS
    <!--VOUCHER/COUPON STARTS
    <div class="checkout_discount">
      <h4>Discount codes</h4>
        <p>Enter your coupon code if you have one.</p>
        <input type="text">
        <button type="button" title="" class="brown_btn">Apply Coupon</button>
    </div>
    <!--VOUCHER COUPON ENDS
  </div>
  ADDITIONAL CART DETAILS ENDS-->
  <?php
    } else {  
  ?>
  <!--SHOPPING CART TABLE STARTS-->
  <div class="cart_table">
    <p align="center">
      <?php echo $lC_Language->get('shopping_cart_empty'); ?>
      <br />&nbsp;<br />&nbsp;<br />&nbsp;<br />
    </p>
  </div>
  <!--SHOPPING CART TABLE ENDS-->
  <!--ACTION BUTTON BAR STARTS-->
  <div class="action_buttonbar" align="right">
    <form name="shopping_cart" id="shopping_cart" action="<?php echo lc_href_link(FILENAME_PRODUCTS, 'new', 'SSL'); ?>" method="post">
      <button type="submit" class="continue"><?php echo $lC_Language->get('cart_continue_shopping'); ?></button>
    </form>
  </div>
  <!--ACTION BUTTON BAR ENDS-->
    <?php 
    } 
  ?>