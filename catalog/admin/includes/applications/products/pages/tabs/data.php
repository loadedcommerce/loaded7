<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: data.php v1.0 2013-08-08 datazen $
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
          <input type="text" onfocus="this.select();" class="input full-width" value="<?php echo (isset($pInfo) ? $pInfo->get('products_model') : null); ?>" id="products_model" name="products_model" />
        </div>
      </div>
      <div class="new-row-mobile six-columns six-columns-tablet twelve-columns-mobile">
        <div class="twelve-columns no-margin-bottom strong">
          <span><?php echo $lC_Language->get('text_msrp'); ?></span><?php echo lc_show_info_bubble($lC_Language->get('info_bubble_data_msrp')); ?>
        </div>
        <div class="twelve-columns no-margin-bottom small-margin-top">
          <div class="inputs" style="display:inline; padding:8px 0;">
            <span class="mid-margin-left no-margin-right"><?php echo $lC_Currencies->getSymbolLeft(); ?></span>
            <input type="text" onfocus="this.select();" style="width:94%;" class="input-unstyled" value="<?php echo (isset($pInfo) ? number_format($pInfo->get('products_msrp'), DECIMAL_PLACES) : null); ?>" id="products_msrp" name="products_msrp" />
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
          <span id="invControlButtons" class="button-group upsellwrapper">
            <label for="ic_radio_1" class="oicb button blue-active<?php echo (isset($pInfo) && ($pInfo->getInt('has_children') == 1 || $pInfo->getInt('has_subproducts') == 1) ? '' : ' active'); ?>">
              <input type="radio" name="inventory_control_radio_group" id="ic_radio_1" value="1" />
              <?php echo $lC_Language->get('text_simple'); ?>
            </label>
            <label upselltitle="<?php echo $lC_Language->get('text_multi_sku'); ?>" upselldesc="<?php echo $lC_Language->get('text_multi_sku_desc'); ?>" for="ic_radio_2" class="upsellinfo disabled oicb button red-active<?php echo (isset($pInfo) && ($pInfo->getInt('has_children') == 1 || $pInfo->getInt('has_subproducts') == 1) ? ' active' : ''); ?>">
              <input type="radio" name="inventory_control_radio_group" id="ic_radio_2" value="2" />
              <?php echo $lC_Language->get('text_multi_sku') . '<span class="small-margin-left float-right">' . lc_go_pro() . '</span>'; ?>
            </label>
          </span>
        </div>
      </div>
      <div class="new-row-mobile six-columns six-columns-tablet twelve-columns-mobile">&nbsp;</div>
    </div>
    <div id="inventory_control_container" class="field-drop button-height black-inputs">
      <div id="inventory_control_simple"<?php echo (isset($pInfo) && ($pInfo->getInt('has_children') == 1 || $pInfo->getInt('has_subproducts') == 1) ? ' style="display:none;"' : ''); ?>>
        <div>
          <label for="" class="label"><b><?php echo $lC_Language->get('text_qty_on_hand'); ?></b></label>
          <input type="text" name="products_quantity" id="products_quantity" value="<?php echo (isset($pInfo) ? $pInfo->get('products_quantity') : null); ?>" class="input small-margin-right" style="width:60px;" />
          <input type="text" name="products_sku" id="products_sku" placeholder="<?php echo $lC_Language->get('text_sku'); ?>" value="<?php echo (isset($pInfo) ? $pInfo->get('products_sku') : null); ?>" class="input" />
        </div>
      </div>
      <div id="inventory_control_multi"<?php echo (isset($pInfo) && ($pInfo->getInt('has_children') == 1 || $pInfo->getInt('has_subproducts') == 1) ? '' : ' style="display:none;"'); ?>>
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
            <?php 
            if (isset($pInfo)) {
              if(DISPLAY_PRICE_WITH_TAX == 1) { 
                $products_price_gross = lc_round($pInfo->get('products_price_with_tax'), DECIMAL_PLACES);
              } else {
                $products_price_gross = lc_round($pInfo->get('products_price'), DECIMAL_PLACES);
              }
            } else {
              $products_price_gross = null;
            }
            echo lc_draw_input_field('products_price_gross', $products_price_gross, 'style="width:94%;" class="input-unstyled strong products-price-gross" id="products_price0_gross" READONLY'); ?>
          </div>         
        </div>
      </div>
    </div>
  </fieldset>
  <fieldset class="fieldset">
    <legend class="legend"><?php echo $lC_Language->get('text_management_settings'); ?></legend>
    <div class="columns no-margin-bottom">
      <?php echo lC_Products_Admin::getProductAttributeModules('dataManagementSettings'); ?>
      <div class="new-row-mobile six-columns six-columns-tablet twelve-columns-mobile no-margin-bottom">
      <?php 
      if (utility::isPro() === false) { 
        ?>
        <div class="twelve-columns strong mid-margin-bottom upsellwrapper">
          <span class="upsellinfo" upselltitle="<?php echo $lC_Language->get('text_product_class_upsell_title'); ?>" upselldesc="<?php echo $lC_Language->get('text_product_class_upsell_desc'); ?>"><?php echo $lC_Language->get('text_product_class') . lc_go_pro(); ?></span>
          <?php echo lc_show_info_bubble($lC_Language->get('info_bubble_data_product_class')); ?>
        </div>
        <div class="twelve-columns product-module-content margin-bottom">
          <span class="select full-width replacement select-styled-list disabled">
            <select class="withClearFunctions disabled">
              <option><?php echo $lC_Language->get('text_common'); ?></option>
            </select>
            <span class="select-value"><?php echo $lC_Language->get('text_common'); ?></span>
            <span class="select-arrow"></span>
            <span class="drop-down"></span>
          </span>
        </div>
        <?php
      }
      ?>
      </div>
    </div>
  </fieldset>
</div>