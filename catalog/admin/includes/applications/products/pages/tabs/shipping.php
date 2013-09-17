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
global $lC_Language, $pInfo, $weight_class_array; 
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
              <input type="text" class="input full-width" value="<?php echo (isset($pInfo) ? $pInfo->get('products_weight') : null); ?>" id="products_weight" name="products_weight" />
            </div>
          </div>
          <div class="new-row-mobile six-columns six-columns-tablet twelve-columns-mobile">
            <div class="twelve-columns no-margin-bottom strong">
              <span><?php echo $lC_Language->get('field_weight_class'); ?></span><?php echo lc_show_info_bubble($lC_Language->get('info_bubble_shipping_weight_class')); ?>
            </div>
            <div class="twelve-columns no-margin-bottom small-margin-top">
              <?php echo lc_draw_pull_down_menu('products_weight_class', $weight_class_array, (isset($pInfo) ? $pInfo->get('products_weight_class') : SHIPPING_WEIGHT_UNIT), 'class="select full-width small-margin-top"'); ?>
            </div>
          </div>
        </div>
      </fieldset>
    </div>
    <div class="twelve-columns">
      <fieldset class="fieldset">
        <legend class="legend"><?php echo $lC_Language->get('text_supplier_characteristics'); ?></legend>
        <div class="columns no-margin-bottom">
          <?php echo lC_Products_Admin::getProductAttributeModules('shippingSupplierCharacteristics'); ?>
        </div>
      </fieldset>
    </div>
  </div>
</div>