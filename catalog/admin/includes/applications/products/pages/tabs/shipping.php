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
        <div class="columns">
          <div class="new-row-mobile six-columns six-columns-tablet twelve-columns-mobile">
            <div class="twelve-columns no-margin-bottom">
              <span><?php echo $lC_Language->get('field_weight'); ?></span>
              <span class="info-spot on-left grey float-right">
                <span class="icon-info-round"></span>
                <span class="info-bubble">
                  Put the bubble text here
                </span>
              </span>
            </div>
            <div class="twelve-columns no-margin-bottom small-margin-top">
              <input type="text" class="required input full-width" value="<?php echo $lC_ObjectInfo->get('products_weight'); ?>" id="products_weight" name="products_weight" />
            </div>
            <div class="twelve-columns no-margin-bottom margin-top grey disabled">
              <?php echo $lC_Language->get('text_non_shippable_item'); ?>
              <input type="checkbox" id="virtual" name="virtual" disabled /> 
              <?php echo $lC_Language->get('text_coming_soon'); ?>
            </div>
          </div>
          <div class="new-row-mobile six-columns six-columns-tablet twelve-columns-mobile">
            <div class="twelve-columns no-margin-bottom">
              <span><?php echo $lC_Language->get('text_dimensional'); ?></span>
              <span class="info-spot on-left grey">
                <small class="tag red-bg">Pro</small>
                <span class="info-bubble">
                  <b>Go Pro!</b> and enjoy this feature!
                </span>
              </span>
              <span class="info-spot on-left grey float-right">
                <span class="icon-info-round"></span>
                <span class="info-bubble">
                  Put the bubble text here
                </span>
              </span>
            </div>
            <div class="twelve-columns no-margin-bottom margin-top">
              <div class="twelve-columns clear-both">
                <div style="width:50px;" class="float-left small-margin-top"><label for="product_length" class="label"><?php echo $lC_Language->get('text_length'); ?></label></div>
                <input type="text" class="input unstyled margin-bottom float-left" value="<?php echo $lC_Language->get('text_coming_soon'); ?>" id="product_length" name="product_length" disabled />
              </div>
              <div class="twelve-columns clear-both">
                <div style="width:50px;" class="float-left small-margin-top"><label for="product_length" class="label"><?php echo $lC_Language->get('text_width'); ?></label></div>
                <input type="text" class="input unstyled margin-bottom" value="<?php echo $lC_Language->get('text_coming_soon'); ?>" id="product_width" name="product_width" disabled />
              </div>
              <div class="twelve-columns">
                <div style="width:50px;" class="float-left small-margin-top"><label for="product_length" class="label"><?php echo $lC_Language->get('text_height'); ?></label></div>
                <input type="text" class="input unstyled margin-bottom" value="<?php echo $lC_Language->get('text_coming_soon'); ?>" id="product_height" name="product_height" disabled />
              </div>
            </div>
          </div>                
        </div>
        <div class="columns">
          <div class="new-row-mobile six-columns six-columns-tablet twelve-columns-mobile">
            <div class="twelve-columns no-margin-bottom">
              <span><?php echo $lC_Language->get('field_weight_class'); ?></span>
              <span class="info-spot on-left grey float-right">
                <span class="icon-info-round"></span>
                <span class="info-bubble">
                  Put the bubble text here
                </span>
              </span>
            </div>
            <div class="twelve-columns no-margin-bottom small-margin-top">
              <?php echo lc_draw_pull_down_menu('products_weight_class', $weight_class_array, (isset($lC_ObjectInfo) ? $lC_ObjectInfo->get('products_weight_class') : SHIPPING_WEIGHT_UNIT), 'class="select full-width small-margin-top required"'); ?>
            </div>
          </div>
          <div class="new-row-mobile six-columns six-columns-tablet twelve-columns-mobile">
            &nbsp;
          </div>                
        </div>
      </fieldset>
    </div>
    <div class="twelve-columns">
      <fieldset class="fieldset">
        <legend class="legend"><?php echo $lC_Language->get('text_order_fee_modifiers'); ?></legend>
        <div class="columns">
          <div class="new-row-mobile six-columns six-columns-tablet twelve-columns-mobile">
            <div class="twelve-columns no-margin-bottom">
              <span><?php echo $lC_Language->get('text_shipping_fee_override'); ?></span>
              <span class="info-spot on-left grey float-right">
                <span class="icon-info-round"></span>
                <span class="info-bubble">
                  Put the bubble text here
                </span>
              </span>
            </div>
            <div class="twelve-columns no-margin-bottom small-margin-top">
              <input type="text" class="required input full-width" value="<?php echo $lC_Language->get('text_coming_soon'); ?>" id="products_shipping_fee_override" name="products_shipping_fee_override" disabled /><small class="small-margin-top"><?php echo $lC_Language->get('text_zero_for_free_shipping'); ?></small>
            </div>
          </div>
          <div class="new-row-mobile six-columns six-columns-tablet twelve-columns-mobile">
            <div class="twelve-columns no-margin-bottom">
              <span><?php echo $lC_Language->get('text_add_handling_fee'); ?></span>
              <span class="info-spot on-left grey">
                <small class="tag red-bg">Pro</small>
                <span class="info-bubble">
                  <b>Go Pro!</b> and enjoy this feature!
                </span>
              </span>
              <span class="info-spot on-left grey float-right">
                <span class="icon-info-round"></span>
                <span class="info-bubble">
                  Put the bubble text here
                </span>
              </span>
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
        <div class="columns">
          <div class="new-row-mobile six-columns six-columns-tablet twelve-columns-mobile">
            <div class="twelve-columns no-margin-bottom">
              <span><?php echo $lC_Language->get('text_days_to_ship'); ?></span>
              <span class="info-spot on-left grey float-right">
                <span class="icon-info-round"></span>
                <span class="info-bubble">
                  Put the bubble text here
                </span>
              </span>
            </div>
            <div class="twelve-columns no-margin-bottom small-margin-top">
              <input type="text" class="required input full-width" value="<?php echo $lC_Language->get('text_coming_soon'); ?>" id="products_days_to_ship" name="products_days_to_ship" disabled />
            </div>
          </div>
          <div class="new-row-mobile six-columns six-columns-tablet twelve-columns-mobile">
            <div class="twelve-columns no-margin-bottom">
              <span><?php echo $lC_Language->get('text_warehouse'); ?></span>
              <span class="info-spot on-left grey">
                <small class="tag red-bg">Pro</small>
                <span class="info-bubble">
                  <b>Go Pro!</b> and enjoy this feature!
                </span>
              </span>
              <span class="info-spot on-left grey float-right">
                <span class="icon-info-round"></span>
                <span class="info-bubble">
                  Put the bubble text here
                </span>
              </span>
            </div>
            <div class="twelve-columns no-margin-bottom small-margin-top">
              <input type="text" class="required input full-width" value="<?php echo $lC_Language->get('text_coming_soon'); ?>" id="products_warehouse" name="products_warehouse" disabled />
            </div>
          </div>
          <div class="new-row-mobile six-columns six-columns-tablet twelve-columns-mobile">
            <div class="twelve-columns no-margin-bottom">
              <span><?php echo $lC_Language->get('text_stock_date_expected'); ?></span>
              <span class="info-spot on-left grey float-right">
                <span class="icon-info-round"></span>
                <span class="info-bubble">
                  Put the bubble text here
                </span>
              </span>
            </div>
            <div class="twelve-columns no-margin-bottom small-margin-top">
              <span class="nowrap margin-right">
                <span class="input small-margin-top full-width">
                  <input type="text" placeholder="" class="input-unstyled datepicker full-width" value="<?php echo $lC_Language->get('text_coming_soon'); ?>" disabled />
                  <span class="icon-calendar icon-size2 small-margin-left float-right" style="margin-top:-29px;"></span>
                </span>
              </span>
            </div>
          </div>                
        </div>
      </fieldset>
    </div>
  </div>
</div>