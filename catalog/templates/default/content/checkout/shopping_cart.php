<?php
/**  
*  $Id: shopping_cart.php v1.0 2013-01-01 datazen $
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
if ($lC_MessageStack->size('shopping_cart') > 0) {
  if (isset($_SESSION['messageToStack']) && $_SESSION['messageToStack'] != NULL) $lC_MessageStack = new lC_MessageStack();
  echo '<br /><div class="short-code msg error"><span>' . $lC_MessageStack->get('shopping_cart', DIR_WS_TEMAPLTE_IMAGES . 'shortcodes/', '.png') . '</span></div>';
}
?>
<!--content/checkout/shopping_cart.php start-->
<div id="shopping_cart_content" class="full_page">
  <h1><?php echo $lC_Template->getPageTitle(); ?></h1>
  <?php 
  if ($lC_ShoppingCart->hasContents()) { 
    ?>
    <!-- <div class="message success">The Product was added to your shopping cart.</div> -->
    <div class="cart_table">
      <form name="shopping_cart" id="shopping_cart" action="<?php echo lc_href_link(FILENAME_CHECKOUT, 'action=cart_update', 'SSL'); ?>" method="post">
        <table class="data-table cart-table" id="shopping-cart-table" cellpadding="0" cellspacing="0">
          <thead>
            <tr>
              <th colspan="2"><?php echo $lC_Language->get('listing_products_heading'); ?></th>
              <th class="align_center hide-on-320" width="12%"><?php echo $lC_Language->get('text_unit_price'); ?></th>
              <th class="align_center" width="10%"><?php echo $lC_Language->get('text_quantity_abbr'); ?></th>
              <th class="align_center" width="12%"><?php echo $lC_Language->get('text_sub_total'); ?></th>
              <th class="align_center" width="6%"></th>
            </tr>
          </thead>
          <tbody>
          <?php 
            foreach ($lC_ShoppingCart->getProducts() as $products) {
            ?>
            <tr id="tr-<?php echo $products['item_id'];?>">
              <td>
                <?php 
                  echo lc_link_object(lc_href_link(FILENAME_PRODUCTS, $products['keyword']), $lC_Image->show($products['image'], $products['name'], null, 'mini')); 
                ?>
              </td>
              <td class="align_left">
                <?php 
                  echo lc_link_object(lc_href_link(FILENAME_PRODUCTS, $products['keyword']), strtolower($lC_Language->get('button_edit')), 'class="cart-edit hide-on-320"') . '</a>';
                  echo lc_link_object(lc_href_link(FILENAME_PRODUCTS, $products['keyword']), $products['name'], 'class="pr_name"') . '</a>';
                  if (!empty($products['model'])) {
                    echo '<small class="purple">' . $lC_Language->get('listing_model_heading') . ': ' . $products['model'] . '</small>';
                  }
                  if ( (STOCK_CHECK == '1') && ($lC_ShoppingCart->isInStock($products['item_id']) === false) ) {
                    echo '<span class="markProductOutOfStock">' . STOCK_MARK_PRODUCT_OUT_OF_STOCK . '</span>';
                  }
                  if ( $lC_ShoppingCart->isVariant($products['item_id']) ) {
                    foreach ( $lC_ShoppingCart->getVariant($products['item_id']) as $variant) {
                      echo '<br />- ' . $variant['group_title'] . ': ' . $variant['value_title'];
                    }
                  }   
                  if ( $lC_ShoppingCart->hasSimpleOptions($products['item_id']) ) {
                    foreach ( $lC_ShoppingCart->getSimpleOptions($products['item_id']) as $option) {
                      echo '<br /><span style="font-size:.9em;">- ' . $option['group_title'] . ': ' . $option['value_title'] . '</span>';
                    }
                  }                             
                ?>
              </td>
              <td class="align_center vline hide-on-320"><span><?php echo $lC_Currencies->displayPrice($products['price'], $products['tax_class_id']); ?></span></td> 
              <td class="align_center vline"><?php echo lc_draw_input_field('products[' . $products['item_id'] . ']', $products['quantity'], 'class="qty_box"'); ?></td>
              <td class="align_center vline"><span class="price"><?php echo $lC_Currencies->displayPrice($products['price'], $products['tax_class_id'], $products['quantity']); ?></span></td>
              <td class="align_center vline"><a href="javascript://" onclick="deleteItem('<?php echo $products['item_id']; ?>');"><?php echo lc_icon('cart_remove.png'); ?></a></td>
            </tr>
            <?php 
            } 
          ?>
          </tbody>
        </table>
        <?php
          if ( (STOCK_CHECK == '1') && ($lC_ShoppingCart->hasStock() === false) ) {
            if (STOCK_ALLOW_CHECKOUT == '1') {
              echo '<br /><p class="stockWarning" align="center">' . sprintf($lC_Language->get('products_out_of_stock_checkout_possible'), STOCK_MARK_PRODUCT_OUT_OF_STOCK) . '</p>';
            } else {
              echo '<br /><p class="stockWarning" align="center">' . sprintf($lC_Language->get('products_out_of_stock_checkout_not_possible'), STOCK_MARK_PRODUCT_OUT_OF_STOCK) . '</p>';
            }
          }
        ?>
      </form>
      <div class="totals">
        <table id="totals-table">
          <tbody>
            <?php 
              foreach ($lC_ShoppingCart->getOrderTotals() as $module) {
              ?>
              <tr>
                <td class="align_right<?php if ($module['code'] == 'sub_total') echo ' sc_sub_total'; if ($module['code'] == 'total') echo ' sc_total'; ?>" style="padding-right:10px;"><?php echo $module['title']; ?></td>
                <td class="align_right<?php if ($module['code'] == 'sub_total') echo ' sc_sub_total'; if ($module['code'] == 'total') echo ' sc_total'; ?>"><?php echo $module['text']; ?></td>
              </tr>
              <?php
              }
            ?>     
          </tbody>       
        </table>
      </div>
    </div>
    
    <!--VQMOD-001-->
    
    <div class="action_buttonbar margin-top">
      <button type="button" onclick="location='<?php echo lc_href_link(FILENAME_CHECKOUT, 'shipping', 'SSL'); ?>'" class="checkout"><?php echo $lC_Language->get('button_checkout'); ?></button>
      <span class="buttonRight padding-right-15"><button type="button" class="continue" onclick="$('#shopping_cart').submit();"><?php echo $lC_Language->get('button_update'); ?></button></span>
    </div>
    <div class="checkout_tax">
      <!--
      <div class="shipping_tax">
        <h4>Estimate Shipping and Tax</h4>
        <p>Enter your destination to get a shipping estimate.</p>
        <label>Country</label>
        <select><option>Canada</option></select>
        <label>Postal Code</label>
        <input type="text">
        <br class="clear"/>
        <label>State</label>
        <select><option>Vancouver</option></select>
        <button type="button" title="" class="brown_btn">Get a Quote</button>
      </div>
      -->
      <div class="checkout_discount">
        <h4><?php echo $lC_Language->get('text_coupon_code_heading'); ?></h4>
        <p><?php echo $lC_Language->get('text_coupon_code_instructions'); ?></p>
        <form name="coupon" id="coupon" action="">
          <input type="text" name="coupon_code" id="coupon_code">
        </form>
        <button type="button" class="brown_btn" onclick="addCoupon();"><?php echo $lC_Language->get('text_apply_coupon'); ?></button>
      </div>
    </div>
    <?php
  } else {  
    ?>
    <div class="cart_table">
      <p align="center">
        <?php echo $lC_Language->get('shopping_cart_empty'); ?>
        <br />&nbsp;<br />&nbsp;<br />&nbsp;<br />
      </p>
    </div>
    <div class="action_buttonbar" align="right">
      <form name="shopping_cart" id="shopping_cart" action="<?php echo lc_href_link(FILENAME_PRODUCTS, 'new', 'SSL'); ?>" method="post">
        <button type="submit" class="continue"><?php echo $lC_Language->get('cart_continue_shopping'); ?></button>
      </form>
    </div>
    <?php 
  } 
  ?>
</div>
<!--content/checkout/shopping_cart.php end-->