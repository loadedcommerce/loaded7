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
          $orders_ID = $_GET[$lC_Template->getModule()];
          $Qordersproducts = lC_Orders_Admin::getordersproducts($orders_ID);
          
          $order_total_array = $oInfo->get('orderTotalsData');
          $product_sub_total = '';

          if(is_array($order_total_array)) {
            foreach( $order_total_array as $v) {
              if($v['title'] == 'Sub-Total:') {
                $product_sub_total = $v['text'];
                break;
              }  
            }
          }
          require_once($lC_Vqmod->modCheck('includes/applications/products/classes/products.php'));
         

          foreach ($Qordersproducts as $products) {
        ?>
        <div class="columns with-small-padding small-margin-left bbottom-grey">
          <div class="new-row-mobile two-columns twelve-columns-mobile small-margin-bottom">
            <span class="show-below-768 bold"><?php echo $lC_Language->get('text_sku_model'); ?> </span>
            <span id="products_model_<?php echo $products['orders_products_id']; ?>"><?php echo $products['model']; ?></span>
          </div>
          <div class="new-row-mobile two-columns twelve-columns-mobile small-margin-bottom">
            <span class="show-below-768 bold"><?php echo $lC_Language->get('text_name'); ?> </span>
            <span id="products_name_<?php echo $products['orders_products_id']; ?>"><?php echo $products['name']; ?>
            <?php

              if ( isset($product['attributes']) && is_array($product['attributes']) && ( sizeof($product['attributes']) > 0 ) ) {
                foreach ( $product['attributes'] as $attributes ) {
                  echo '<br /><nobr>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- <span class="large-margin-left"><i>' . $attributes['option'] . ': ' . $attributes['value'] . '</i></span></nobr>';
                }
              }
              
              if ( isset($product['options']) && is_array($product['options']) && ( sizeof($product['options']) > 0 ) ) {
                foreach ( $product['options'] as $key => $val ) {
                  echo '<br /><nobr>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- <span class="small" class="large-margin-left"><i>' . $val['group_title'] . ': ' . $val['value_title'] . '</i></span></nobr>';
                }
              }
            ?>
            </span>
          </div>
          <div class="new-row-mobile two-columns twelve-columns-mobile small-margin-bottom">
            <span class="show-below-768 bold"><?php echo $lC_Language->get('text_fulfillment'); ?> </span>
            <span id="products_stock_<?php echo $products['orders_products_id']; ?>"><?php echo $products['stock']; ?></span>
          </div>
          <div class="new-row-mobile one-column twelve-columns-mobile small-margin-bottom">
            <span class="show-below-768 bold"><?php echo $lC_Language->get('text_tax_class'); ?> </span>
            <span id="products_tax_class_<?php echo $products['orders_products_id']; ?>"><?php echo $products['tax_class']; ?></span>
          </div>
          <div class="new-row-mobile one-column twelve-columns-mobile small-margin-bottom">
            <span class="show-below-768 bold"><?php echo $lC_Language->get('text_price'); ?> </span>
            <span id="products_price_<?php echo $products['orders_products_id']; ?>"><?php echo $products['price']; ?></span>
          </div>
          <div class="new-row-mobile one-column twelve-columns-mobile small-margin-bottom">
            <span class="show-below-768 bold"><?php echo $lC_Language->get('text_qty'); ?> </span>
            <span id="products_qty_<?php echo $products['orders_products_id']; ?>"><?php echo $products['quantity']; ?></span>
          </div>
          <div class="new-row-mobile one-column twelve-columns-mobile small-margin-bottom">
            <span class="show-below-768 bold"><?php echo $lC_Language->get('text_total'); ?> </span>
            <span id="products_total_<?php echo $products['orders_products_id']; ?>"><?php echo number_format($products['price']*$products['quantity'], DECIMAL_PLACES); ?></span>
          </div>
          <div class="new-row-mobile two-columns twelve-columns-mobile small-margin-bottom align-right">
            <span id="buttons_<?php echo $products['orders_products_id']; ?>">
              <span class="button-group">
                <a class="button compact icon-pencil" href="javascript:void(0);" onclick="editOrderProduct('<?php echo $orders_ID; ?>','<?php echo $products['orders_products_id']; ?>');"><?php echo $lC_Language->get('text_edit'); ?></a>
                <a class="button compact icon-trash with-tooltip" title="<?php echo $lC_Language->get('text_delete'); ?>" href="javascript:void(0)" onclick="deleteOrderProduct('<?php echo $products['orders_products_id']; ?>');"></a>
              </span>
            </span>
          </div>
        </div>
        <?php
          }
        ?>

         <div class="columns with-small-padding small-margin-left bbottom-grey small-margin-bottom align-right height" >
          <span class="with-margin-right"><?php echo $lC_Language->get('text_product_sub_total').'<span class="with-small-padding" ></span>'. ': '.'<span class="with-small-padding" ></span>' .$product_sub_total;?></span>
        </div>
        <div class="columns with-small-padding small-margin-left bbottom-grey small-margin-bottom align-left">
           <span class="with-padding-left"><?php echo $lC_Language->get('text_add_product'). ': ' .lc_draw_pull_down_menu('add_product', lC_Products_Admin::getProductDropdownArray());?></span>
          
          <span class="button-group">
            <a class="button compact icon-plus" href="javascript:void(0);" onclick="addOrderProduct(<?php echo $orders_ID;?>);"><?php echo 'Add item'; ?></a>            
          </span>


        </div>


      </fieldset>
    </div> 
  </div>
</div>