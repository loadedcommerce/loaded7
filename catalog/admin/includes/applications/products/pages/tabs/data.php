<?php
/**
  $Id: data.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
global $lC_Language, $pInfo, $tax_class_array; 
?>
<div id="section_data_content" class="with-padding">
  <fieldset class="fieldset">
    <legend class="legend"><?php echo $lC_Language->get('text_inventory_settings'); ?></legend>
    <div class="columns">
      <div class="new-row-mobile six-columns six-columns-tablet twelve-columns-mobile">
        <div class="twelve-columns no-margin-bottom strong">
          <span><?php echo $lC_Language->get('field_model'); ?></span><?php echo lc_show_info_bubble($lC_Language->get('info_bubble_data_model')); ?>
        </div>
        <div class="twelve-columns no-margin-bottom small-margin-top">
          <input type="text" onfocus="this.select();" class="required input full-width" value="<?php echo (isset($pInfo) ? $pInfo->get('products_model') : null); ?>" id="products_model" name="products_model" />
        </div>
      </div>
      <div class="new-row-mobile six-columns six-columns-tablet twelve-columns-mobile">
        <div class="twelve-columns no-margin-bottom strong">
          <span><?php echo $lC_Language->get('text_msrp'); ?></span><?php echo lc_show_info_bubble($lC_Language->get('info_bubble_data_msrp')); ?>
        </div>
        <div class="twelve-columns no-margin-bottom small-margin-top">
          <div class="inputs" style="display:inline; padding:8px 0;">
            <span class="mid-margin-left no-margin-right"><?php echo $lC_Currencies->getSymbolLeft(); ?></span>
            <input type="text" onfocus="this.select();" style="width:94%;" class="required input-unstyled" value="<?php echo (isset($pInfo) ? number_format($pInfo->get('products_msrp'), DECIMAL_PLACES) : null); ?>" id="products_msrp" name="products_msrp" />
          </div>         
        </div>
      </div>      
    </div>
    <div class="columns">
      <div class="new-row-mobile six-columns six-columns-tablet twelve-columns-mobile">
        <div class="twelve-columns no-margin-bottom strong">
          <span><?php echo $lC_Language->get('text_inventory_control'); ?></span><?php echo lc_show_info_bubble($lC_Language->get('info_bubble_inventory_control'), 'margin-left:8px', 'info-spot info-spot on-right grey'); ?>
        </div>
        <div class="twelve-columns no-margin-bottom small-margin-top">
          <span id=invControlButtons" class="button-group">
            <label for="ic_radio_1" class="oicb button blue-active<?php echo (isset($pInfo) && ($pInfo->getInt('has_children') == 1) ? '' : ' active'); ?>">
              <input type="radio" name="inventory_control_radio_group" id="ic_radio_1" value="1" />
              <?php echo $lC_Language->get('text_simple'); ?>
            </label>
            <label upsell="<?php echo $lC_Language->get('text_multi_sku_desc'); ?>" for="ic_radio_2" class="disabled oicb button red-active<?php echo (isset($pInfo) && ($pInfo->getInt('has_children') == 1) ? ' active' : ''); ?>">
              <input type="radio" name="inventory_control_radio_group" id="ic_radio_2" value="2" />
              <?php echo $lC_Language->get('text_multi_sku') . '<span class="small-margin-left float-right">' . lc_go_pro() . '</span>'; ?>
            </label>
          </span>
        </div>
      </div>
      <div class="new-row-mobile six-columns six-columns-tablet twelve-columns-mobile">&nbsp;</div>
    </div>
    <div id="inventory_control_container" class="field-drop button-height black-inputs">
      <div id="inventory_control_simple"<?php echo (isset($pInfo) && ($pInfo->getInt('has_children') == 1) ? ' style="display:none;"' : ''); ?>>
        <div>
          <label for="" class="label"><b><?php echo $lC_Language->get('text_qty_on_hand'); ?></b></label>
          <input type="text" name="products_quantity" id="products_quantity" value="<?php echo (isset($pInfo) ? $pInfo->get('products_quantity') : null); ?>" class="input small-margin-right" style="width:60px;" />
          <input type="text" name="products_sku" id="products_sku" placeholder="<?php echo $lC_Language->get('text_sku'); ?>" value="<?php echo (isset($pInfo) ? $pInfo->get('products_sku') : null); ?>" class="input" />
        </div>
      </div>
      <div id="inventory_control_multi"<?php echo (isset($pInfo) && ($pInfo->getInt('has_children') == 1) ? '' : ' style="display:none;"'); ?>>
        <span class="icon-warning icon icon-size2 icon-orange small-margin-right"></span> <?php echo $lC_Language->get('text_edit_qoh_sku'); ?>
      </div>
    </div>
  </fieldset>
  <fieldset class="fieldset">
    <legend class="legend"><?php echo $lC_Language->get('text_tax_settings'); ?></legend>
    <div class="columns no-margin-bottom">
      <div class="new-row-mobile six-columns six-columns-tablet twelve-columns-mobile mid-margin-bottom">
        <div class="twelve-columns no-margin-bottom strong">
          <span><?php echo $lC_Language->get('text_tax_class'); ?></span><?php echo lc_show_info_bubble($lC_Language->get('info_bubble_data_tax_class')); ?>
        </div>
        <div class="twelve-columns no-margin-bottom small-margin-top">
          <?php echo lc_draw_pull_down_menu('products_tax_class_id', $tax_class_array, (isset($pInfo) ? $pInfo->getInt('products_tax_class_id') : null), 'class="select full-width small-margin-top" id="tax_class0"'); ?>
        </div>
      </div>
      <div class="new-row-mobile six-columns six-columns-tablet twelve-columns-mobile mid-margin-bottom">
        <div class="twelve-columns no-margin-bottom strong">
          <span><?php echo $lC_Language->get('text_base_price_with_tax'); ?></span><?php echo lc_show_info_bubble($lC_Language->get('info_bubble_data_price_with_tax')); ?>
        </div>
        <div class="twelve-columns no-margin-bottom small-margin-top">
          <div class="inputs blue-gradient" style="display:inline; padding:8px 0;">
            <span class="mid-margin-left no-margin-right strong"><?php echo $lC_Currencies->getSymbolLeft(); ?></span>
            <?php echo lc_draw_input_field('products_price_gross', (isset($pInfo) ? lc_round($pInfo->get('products_price'), DECIMAL_PLACES) : null), 'style="width:94%;" class="required input-unstyled strong products-price-gross" id="products_price0_gross" READONLY'); ?>
          </div>         
        </div>
      </div>
    </div>
  </fieldset>
  <fieldset class="fieldset">
    <legend class="legend"><?php echo $lC_Language->get('text_management_settings'); ?></legend>
    <div class="columns no-margin-bottom">
      <?php echo lC_Products_Admin::getProductAttributeModules('dataManagementSettings'); ?>
    </div>
  </fieldset>
</div>