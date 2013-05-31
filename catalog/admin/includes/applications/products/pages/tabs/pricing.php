<?php
/**
  $Id: pricing.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
global $lC_Language, $pInfo; 
?>
<div id="section_pricing_content" class="with-padding">
  <fieldset class="fieldset fields-list" style="padding-bottom:0;">
    <legend class="legend"><?php echo $lC_Language->get('text_pricing_overrides'); ?></legend>
    <div class="field-block button-height">
      <label for="products_base_price" class="label"><b><?php echo $lC_Language->get('text_base_price'); ?></b></label>
      <input type="text" name="products_base_price" id="products_base_price" value="<?php echo number_format(lc_round($pInfo->get('products_price'), DECIMAL_PLACES), DECIMAL_PLACES);; ?>" class="input strong" onblur="$('#products_price0').val(this.value);" /><?php echo lc_show_info_bubble($lC_Language->get('info_bubble_pricing_base_price'), null, 'info-spot on-left grey margin-left'); ?>
    </div>

    <!-- lc_group_pricing begin -->
    <div class="field-block field-block-product button-height">
      <label for="" class="label"><b><?php echo $lC_Language->get('text_group_pricing'); ?></b></label>
      <input type="checkbox" class="switch wider" data-text-off="DISABLED" data-text-on="ENABLED" /><?php echo lc_show_info_bubble($lC_Language->get('info_bubble_pricing_group_pricing'), null, 'info-spot on-left grey margin-left'); ?>
      <?php echo lc_go_pro(); ?>
      <div onclick="toggleSection('groups_pricing_container');" style="cursor:pointer;" class="field-block-chevron-container float-right"><span id="groups_pricing_container_span" class="icon-chevron-thin-down icon-size2"></span></div>
    </div>
    <div id="groups_pricing_container" class="field-drop button-height black-inputs" style="display:none;">
      <?php echo lC_Products_Admin::getGroupPricingContent($pInfo->get('products_price')); ?>
    </div>
    <!-- lc_group_pricing end -->

    <!-- lc_qty_price_breaks begin -->
    <div class="field-block field-block-product button-height">
      <label for="" class="label"><b><?php echo $lC_Language->get('text_qty_break_pricing'); ?></b></label>
      <input type="checkbox" class="switch wider disabled" data-text-off="DISABLED" data-text-on="ENABLED" /><?php echo lc_show_info_bubble($lC_Language->get('info_bubble_pricing_qty_price_breaks'), null, 'info-spot on-left grey margin-left'); ?>
      <span id="qty_breaks_number_of_break_points">
        <?php echo lc_go_pro(); ?>
        <div style="cursor:pointer;" class="field-block-chevron-container float-right"><span id="qty_breaks_pricing_container_span" class="icon-chevron-thin-down icon-size2"></span></div>
      </span>
    </div> 
    <div id="qty_breaks_pricing_container" class="field-drop button-height black-inputs" style="display:none;">
    </div>
    <!-- lc_qty_price_breaks end --> 
    
    <div class="field-block field-block-product button-height">
      <label for="specials-pricing-switch" class="label"><b><?php echo $lC_Language->get('text_special_pricing'); ?></b></label>
      <input id="specials-pricing-switch" type="checkbox" class="switch wider specials-pricing" data-text-off="DISABLED" data-text-on="ENABLED"<?php echo (($pInfo->get('products_special_price') != null) ? ' checked' : ''); ?> /><?php echo lc_show_info_bubble($lC_Language->get('info_bubble_pricing_specials'), null, 'info-spot on-left grey margin-left margin-right'); ?>
      <div onclick="toggleSection('specials_pricing_container', 'caret');" style="cursor:pointer;" class="field-block-chevron-container float-right"><span id="specials_pricing_container_span" class="icon-chevron-thin-down icon-size2"></span></div>

    </div>
    <div id="specials_pricing_container" class="field-drop button-height black-inputs no-margin-bottom"<?php echo (($pInfo->get('products_special_price') != null) ? ' style="display:block;"' : ' style="display:none;"'); ?>>
      <label for="resize_height" class="label"><b>Special Retail Price</b></label>
      <div class="columns">
        <div class="new-row-mobile twelve-columns twelve-columns-mobile">
          <input type="checkbox" class="switch<?php if ($pInfo->get('status') != -1) echo ' checked'; ?>" />
          <span class="input" style="background:#dd380d;">
            <input name="products_special_price" id="products_special_price" value="<?php echo number_format($pInfo->get('products_special_price'), DECIMAL_PLACES); ?>" placeholder="Price or %" class="input-unstyled white strong align-right" />
          </span>
        </div>
        <div class="new-row-mobile twelve-columns twelve-columns-mobile margin-bottom">
          <span class="nowrap margin-right">
            <span class="input small-margin-top">
              <input name="products_special_start_date" id="products_special_start_date" type="text" placeholder="Start" class="input-unstyled datepicker" value="<?php echo $pInfo->get('products_special_start_date'); ?>" style="width:97px;" />
            </span>
            <span class="icon-calendar icon-size2 small-margin-left"></span>
          </span>
          <span class="nowrap">
            <span class="input small-margin-top">
              <input name="products_special_expires_date" id="products_special_expires_date" type="text" placeholder="End" class="input-unstyled datepicker" value="<?php echo $pInfo->get('products_special_expires_date'); ?>" style="width:97px;" />
            </span>
            <span class="icon-calendar icon-size2 small-margin-left"></span>
          </span>
        </div>
      </div>
    </div>                
  </fieldset>
  
  <dl id="simple-options-pricing-tab" class="accordion">
    <?php echo lC_Products_Admin::getSimpleOptionsPricingContent($pInfo->get('simple_options')); ?>
  </dl>     
  
    
</div>  
<script>
$(document).ready(function() {
  _refreshSymbols();
});

function _refreshSymbols() {
  $('#simpleOptionsPricingTable input').each(function(index, element) {
    var id = $(this).attr("id").replace('simple_options_entry_price_modifier_', '');
    showSymbol(element, id); 
  });
}

function showSymbol(e, id) {
  var val = $(e).val();
  if (parseFloat(val) >= 0.0000) {
    $('#div_' + id).removeClass('icon-red').removeClass('icon-minus-round').addClass('icon-green').addClass('icon-plus-round');   
  } else {
    $('#div_' + id).removeClass('icon-green').removeClass('icon-plus-round').addClass('icon-red').addClass('icon-minus-round');   
  }
}

function toggleSection(section, from) {
  
  var open = $('#' + section).is(":visible");

  if (from != 'caret') {
  
    if (section == 'groups_pricing_container') {
    } else if (section == 'qty_breaks_pricing_container') {
    } else if (section == 'specials_pricing_container') {
      var chkd = $('.specials-pricing').hasClass('checked');

alert(chkd);      
  
      if (chkd) {
         open = false;
      } else {
         open = true;
      }
    }
    
  }
    
    

  if (open) {
    $('#' + section).slideUp('300');
    $('#' + section + '_span').removeClass('icon-chevron-thin-up');
    $('#' + section + '_span').addClass('icon-chevron-thin-down');
  } else {
    $('#' + section).slideDown('300');
    $('#' + section + '_span').removeClass('icon-chevron-thin-down');
    $('#' + section + '_span').addClass('icon-chevron-thin-up');        
  }
}
</script>