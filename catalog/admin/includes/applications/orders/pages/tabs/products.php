<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: products.php v1.0 2013-08-08 datazen $
*/
?>
<div id="section_orders_products">
  <h3 class="show-below-768 margin-left margin-top no-margin-bottom"><?php echo $lC_Language->get('text_products'); ?></h3>
  <div class="columns with-padding">
    <div class="new-row-mobile twelve-columns twelve-columns-mobile">
      <fieldset>
        <legend class="small-margin-bottom">
          <span class="icon-list icon-anthracite"><strong class="small-margin-left"><?php echo $lC_Language->get('text_products_ordered'); ?></strong></span>
        </legend>
        <div class="columns with-small-padding small-margin-left hide-below-768 bbottom-grey">
          <div class="new-row-mobile two-columns twelve-columns-mobile no-margin-bottom"><?php echo $lC_Language->get('text_sku_model'); ?></div>
          <div class="new-row-mobile two-columns twelve-columns-mobil no-margin-bottom"><?php echo $lC_Language->get('text_name'); ?></div>
          <div class="new-row-mobile two-columns twelve-columns-mobile no-margin-bottom"><?php echo $lC_Language->get('text_fulfillment'); ?></div>
          <div class="new-row-mobile one-column twelve-columns-mobile no-margin-bottom"><?php echo $lC_Language->get('text_tax_class'); ?></div>
          <div class="new-row-mobile one-column twelve-columns-mobile no-margin-bottom"><?php echo $lC_Language->get('text_price'); ?></div>
          <div class="new-row-mobile one-column twelve-columns-mobile no-margin-bottom"><?php echo $lC_Language->get('text_qty'); ?></div>
          <div class="new-row-mobile one-column twelve-columns-mobile no-margin-bottom"><?php echo $lC_Language->get('text_product_total'); ?></div>
          <div class="new-row-mobile two-columns twelve-columns-mobile no-margin-bottom align-right"><?php echo $lC_Language->get('text_action'); ?></div>
        </div>
        <?php
          $Qordersproducts = array(array('id' => '24', 'model' => 'Floral001RL', 'name' => 'Floral Dress - Red - Large', 'stock' => 'In Stock', 'tax_class' => '5%', 'price' => '125.00', 'qty' => '2', 'total' => '250.00'),
                                   array('id' => '25', 'model' => 'Watch001', 'name' => 'Mens Watch', 'stock' => 'Shipped', 'tax_class' => '7% (FL)', 'price' => '5%', 'qty' => '1', 'total' => '100.00'),
                                   array('id' => '26', 'model' => 'GC124GIFT', 'name' => 'Gift Certificate $125', 'stock' => '-', 'tax_class' => 'Non Taxable', 'price' => '125.00', 'qty' => '1', 'total' => '125.00'),
                                   array('id' => '27', 'model' => 'Floral001GXL', 'name' => 'Floral Dress - Green - Large', 'stock' => 'Out of Stock', 'tax_class' => '5%', 'price' => '75.00', 'qty' => '2', 'total' => '125.00')); 
          foreach ($Qordersproducts as $products) {
        ?>
        <div class="columns with-small-padding small-margin-left bbottom-grey">
          <div class="new-row-mobile two-columns twelve-columns-mobile small-margin-bottom">
            <span class="show-below-768 bold"><?php echo $lC_Language->get('text_sku_model'); ?> </span>
            <span id="products_model_<?php echo $products['id']; ?>"><?php echo $products['model']; ?></span>
          </div>
          <div class="new-row-mobile two-columns twelve-columns-mobile small-margin-bottom">
            <span class="show-below-768 bold"><?php echo $lC_Language->get('text_name'); ?> </span>
            <span id="products_name_<?php echo $products['id']; ?>"><?php echo $products['name']; ?></span>
          </div>
          <div class="new-row-mobile two-columns twelve-columns-mobile small-margin-bottom">
            <span class="show-below-768 bold"><?php echo $lC_Language->get('text_fulfillment'); ?> </span>
            <span id="products_stock_<?php echo $products['id']; ?>"><?php echo $products['stock']; ?></span>
          </div>
          <div class="new-row-mobile one-column twelve-columns-mobile small-margin-bottom">
            <span class="show-below-768 bold"><?php echo $lC_Language->get('text_tax_class'); ?> </span>
            <span id="products_tax_class_<?php echo $products['id']; ?>"><?php echo $products['tax_class']; ?></span>
          </div>
          <div class="new-row-mobile one-column twelve-columns-mobile small-margin-bottom">
            <span class="show-below-768 bold"><?php echo $lC_Language->get('text_price'); ?> </span>
            <span id="products_price_<?php echo $products['id']; ?>"><?php echo $products['price']; ?></span>
          </div>
          <div class="new-row-mobile one-column twelve-columns-mobile small-margin-bottom">
            <span class="show-below-768 bold"><?php echo $lC_Language->get('text_qty'); ?> </span>
            <span id="products_qty_<?php echo $products['id']; ?>"><?php echo $products['qty']; ?></span>
          </div>
          <div class="new-row-mobile one-column twelve-columns-mobile small-margin-bottom">
            <span class="show-below-768 bold"><?php echo $lC_Language->get('text_total'); ?> </span>
            <span id="products_total_<?php echo $products['id']; ?>"><?php echo $products['total']; ?></span>
          </div>
          <div class="new-row-mobile two-columns twelve-columns-mobile small-margin-bottom align-right">
            <span id="buttons_<?php echo $products['id']; ?>">
              <span class="button-group">
                <a class="button compact icon-pencil" href="javascript:void(0);" onclick="editOrderProduct('<?php echo $products['id']; ?>');"><?php echo $lC_Language->get('text_edit'); ?></a>
                <a class="button compact icon-trash with-tooltip" title="<?php echo $lC_Language->get('text_delete'); ?>" href="javascript:void(0)" onclick="deleteOrderProduct('<?php echo $products['id']; ?>');"></a>
              </span>
            </span>
          </div>
        </div>
        <?php
          }
        ?>
      </fieldset>
    </div> 
  </div>
</div>