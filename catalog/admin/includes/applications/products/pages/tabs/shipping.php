<?php
/**
  $Id: shipping.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
global $lC_Language, $lC_ObjectInfo, $weight_class_array; 
?>
<div id="section_shipping_content" class="with-padding">
  <div class="columns">
    <div class="twelve-columns">
      <fieldset class="fieldset">
        <legend class="legend"><?php echo $lC_Language->get('text_product_characteristics'); ?></legend>
        <div class="columns no-margin-bottom">
        
        
          <div class="new-row-mobile six-columns six-columns-tablet twelve-columns-mobile">
            <div class="twelve-columns no-margin-bottom strong">
              <span><?php echo $lC_Language->get('field_weight'); ?></span><?php echo lc_show_info_bubble($lC_Language->get('info_bubble_shipping_weight')); ?>
            </div>
            <div class="twelve-columns no-margin-bottom small-margin-top">
              <input type="text" class="required input full-width" value="<?php echo $lC_ObjectInfo->get('products_weight'); ?>" id="products_weight" name="products_weight" />
            </div>
          </div>
          
          <div class="new-row-mobile six-columns six-columns-tablet twelve-columns-mobile">
            <div class="twelve-columns no-margin-bottom strong">
              <span><?php echo $lC_Language->get('field_weight_class'); ?></span><?php echo lc_show_info_bubble($lC_Language->get('info_bubble_shipping_weight_class')); ?>
            </div>
            <div class="twelve-columns no-margin-bottom small-margin-top">
              <?php echo lc_draw_pull_down_menu('products_weight_class', $weight_class_array, (isset($lC_ObjectInfo) ? $lC_ObjectInfo->get('products_weight_class') : SHIPPING_WEIGHT_UNIT), 'class="select full-width small-margin-top required"'); ?>
            </div>
          </div>
          
          <div class="new-row-mobile six-columns six-columns-tablet twelve-columns-mobile">
            <div class="twelve-columns no-margin-bottom strong">
              <span><?php echo $lC_Language->get('field_non_shippable'); ?></span><?php echo lc_show_info_bubble($lC_Language->get('info_bubble_shipping_non_shippable'), null, 'info-spot grey on-right mid-margin-left'); ?>
            </div>
            <div class="twelve-columns no-margin-bottom small-margin-top">
              <?php echo lc_draw_checkbox_field('products_weight_class', null, null, 'class="input small-margin-top required"'); ?>
              <span><?php echo $lC_Language->get('text_non_shippable_item'); ?></span>
            </div>
          </div>          
          
        
          

          <div class="new-row-mobile six-columns six-columns-tablet twelve-columns-mobile no-margin-bottom">
            <div class="twelve-columns no-margin-bottom strong">
              <span><?php echo $lC_Language->get('text_dimensions'); ?></span><?php echo lc_go_pro() . lc_show_info_bubble($lC_Language->get('info_bubble_shipping_dimensions')); ?>
            </div>
            
            <div class="twelve-columns no-margin-bottom mid-margin-top">
            
              <div class="twelve-columns clear-both" style="display:inline-block; white-space:nowrap;">
                <input type="text" class="input" style="width:24%;" value="" placeholder="<?php echo $lC_Language->get('text_length'); ?>" id="product_length" name="product_length" disabled /><span class="mid-margin-left small-margin-right strong">X</span> 
                <input type="text" class="input" style="width:24%;" value="" placeholder="<?php echo $lC_Language->get('text_width'); ?>" id="product_width" name="product_width" disabled /><span class="mid-margin-left small-margin-right strong">X</span> 
                <input type="text" class="input" style="width:24%;" value="" placeholder="<?php echo $lC_Language->get('text_height'); ?>" id="product_height" name="product_height" disabled />
              </div>

            </div>
          </div>  



        </div>
      </fieldset>
    </div>
    <div class="twelve-columns">
      <fieldset class="fieldset">
        <legend class="legend"><?php echo $lC_Language->get('text_order_fee_modifiers'); ?></legend>
        <div class="columns no-margin-bottom ">
          <div class="new-row-mobile six-columns six-columns-tablet twelve-columns-mobile no-margin-bottom ">
            <div class="twelve-columns no-margin-bottom strong">
              <span><?php echo $lC_Language->get('text_shipping_fee_override'); ?></span><?php echo lc_show_info_bubble($lC_Language->get('info_bubble_shipping_fee_override'), 'margin-right:4px', 'info-spot on-left grey float-right'); ?>
            </div>
            <div class="twelve-columns no-margin-bottom small-margin-top">
              <input type="text" class="required input full-width" value="<?php echo $lC_Language->get('text_coming_soon'); ?>" id="products_shipping_fee_override" name="products_shipping_fee_override" disabled /><small class="small-margin-top"><?php echo $lC_Language->get('text_zero_for_free_shipping'); ?></small>
            </div>
          </div>
          <div class="new-row-mobile six-columns six-columns-tablet twelve-columns-mobile">
            <div class="twelve-columns no-margin-bottom strong">
              <span><?php echo $lC_Language->get('text_add_handling_fee'); ?></span><?php echo lc_go_pro('info-spot on-left grey mid-margin-left') . lc_show_info_bubble($lC_Language->get('info_bubble_shipping_handling_fee'), 'margin-right:4px', 'info-spot on-left grey float-right'); ?>
            </div>
            <div class="twelve-columns no-margin-bottom small-margin-top">
              <input type="text" class="required input full-width" value="<?php echo $lC_Language->get('text_coming_soon'); ?>" id="products_add_handling_fee" name="products_add_handling_fee" disabled />
            </div>
          </div>                
        </div>
      </fieldset>
    </div>
    <div class="twelve-columns">
      <fieldset class="fieldset">
        <legend class="legend"><?php echo $lC_Language->get('text_supplier_characteristics'); ?></legend>
        <div class="columns no-margin-bottom">
        
          <div class="new-row-mobile six-colmns six-columns-tablet twelve-columns-mobile no-margin-bottom">
            <?php echo lC_Products_Admin::getProductAttributeModules('shippingSupplierCharacteristics'); ?>
          </div>
          
          <div class="new-row-mobile six-columns six-columns-tablet twelve-columns-mobile">
            <div class="twelve-columns no-margin-bottom strong">
              <span><?php echo $lC_Language->get('text_warehouse'); ?></span><?php echo lc_go_pro('info-spot on-left grey mid-margin-left') . lc_show_info_bubble($lC_Language->get('info_bubble_shipping_date_expected'), 'margin-right:4px', 'info-spot on-left grey float-right'); ?>
            </div>
            <div class="twelve-columns no-margin-bottom small-margin-top">
              <input type="text" class="required input full-width" value="<?php echo $lC_Language->get('text_coming_soon'); ?>" id="products_warehouse" name="products_warehouse" disabled />
            </div>
          </div>      
          
          <div class="new-row-mobile six-columns six-columns-tablet twelve-columns-mobile">
            <div class="twelve-columns no-margin-bottom strong">
              <span><?php echo $lC_Language->get('text_stock_date_expected'); ?></span><?php echo lc_go_pro('info-spot on-left grey mid-margin-left') . lc_show_info_bubble($lC_Language->get('info_bubble_shipping_date_expected'), 'margin-right:4px', 'info-spot on-left grey float-right'); ?>
            </div>
            <div class="twelve-columns no-margin-bottom small-margin-top">
              <span class="input full-width">
                <span class="icon-calendar"></span>
                <input type="text" class="input-unstyled datepicker" id="date_expected" name="date_expected">
              </span>
            </div>
          </div>                
          
        </div>
      </fieldset>
    </div>
  </div>
</div>