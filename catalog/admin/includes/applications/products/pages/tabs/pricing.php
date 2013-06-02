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
global $lC_Language, $lC_Currencies, $pInfo; 
?>
<div id="section_pricing_content" class="with-padding">
  <fieldset class="fieldset fields-list" style="padding-bottom:0;">
    <legend class="legend"><?php echo $lC_Language->get('text_pricing_overrides'); ?></legend>
    
    <div class="field-block button-height">
      <label for="products_base_price" class="label"><b><?php echo $lC_Language->get('text_base_price'); ?></b></label>
      <div class="inputs" style="display:inline; padding:8px 0;">
        <span class="mid-margin-left no-margin-right"><?php echo $lC_Currencies->getSymbolLeft(); ?></span>
        <input type="text" onfocus="this.select();" onchange="updateDiscountDisplay();" class="input-unstyled" name="products_base_price" id="products_base_price" value="<?php echo number_format(lc_round($pInfo->get('products_price'), DECIMAL_PLACES), DECIMAL_PLACES);; ?>" class="input strong" onblur="$('#products_price0').val(this.value);" />
      </div>    
      <?php echo lc_show_info_bubble($lC_Language->get('info_bubble_pricing_base_price'), null, 'info-spot on-left grey margin-left'); ?>
    </div>

    <div class="field-block field-block-product button-height">
      <label upsell="<?php echo $lC_Language->get('text_group_pricing_desc'); ?>" for="" class="label"><b><?php echo $lC_Language->get('text_group_pricing'); ?></b></label>
      <input type="checkbox" class="switch wider" onchange="toggleSection('groups_pricing_container');" data-text-off="DISABLED" data-text-on="ENABLED" /><?php echo lc_show_info_bubble($lC_Language->get('info_bubble_pricing_group_pricing'), null, 'info-spot on-left grey margin-left'); ?>
      <?php echo lc_go_pro(); ?>
      <div onclick="toggleSection('groups_pricing_container');" style="cursor:pointer;" class="field-block-chevron-container float-right"><span id="groups_pricing_container_span" class="icon-chevron-thin-down icon-size2"></span></div>
    </div>
    <div id="groups_pricing_container" class="field-drop button-height black-inputs" style="display:none;">
      <?php echo lC_Products_Admin::getGroupPricingContent($pInfo->get('products_price')); ?>
    </div>

    <div class="field-block field-block-product button-height">
      <label upsell="<?php echo $lC_Language->get('text_qty_break_pricing_desc'); ?>" for="" class="label"><b><?php echo $lC_Language->get('text_qty_break_pricing'); ?></b></label>
      <input type="checkbox" class="switch wider disabled" data-text-off="DISABLED" data-text-on="ENABLED" /><?php echo lc_show_info_bubble($lC_Language->get('info_bubble_pricing_qty_price_breaks'), null, 'info-spot on-left grey margin-left'); ?>
      <span id="qty_breaks_number_of_break_points">
        <?php echo lc_go_pro(); ?>
        <div style="cursor:pointer;" class="field-block-chevron-container float-right"><span id="qty_breaks_pricing_container_span" class="icon-chevron-thin-down icon-size2"></span></div>
      </span>
    </div> 
    <div id="qty_breaks_pricing_container" class="field-drop button-height black-inputs" style="display:none;">
    </div>
    
    <div class="field-block field-block-product button-height">
      <label for="specials-pricing-switch" class="label"><b><?php echo $lC_Language->get('text_special_pricing'); ?></b></label>
      <input id="specials-pricing-switch" onchange="toggleSection('specials_pricing_container');" type="checkbox" class="switch wider specials-pricing" data-text-off="DISABLED" data-text-on="ENABLED"<?php echo (($pInfo->get('products_special_price') != null) ? ' checked' : ''); ?> /><?php echo lc_show_info_bubble($lC_Language->get('info_bubble_pricing_specials'), null, 'info-spot on-left grey margin-left margin-right'); ?>
      <div onclick="toggleSection('specials_pricing_container');" style="cursor:pointer;" class="field-block-chevron-container float-right"><span id="specials_pricing_container_span" class="icon-chevron-thin-down icon-size2"></span></div>
    </div>
    <div id="specials_pricing_container" class="field-drop button-height black-inputs no-margin-bottom"<?php echo (($pInfo->get('products_special_price') != null) ? ' style="display:block;"' : ' style="display:none;"'); ?>>
      <?php echo lC_Products_Admin::getSpecialPricingContent(); ?>
    </div>                
  </fieldset>
  
  <dl id="simple-options-pricing-tab" class="accordion">
    <?php echo lC_Products_Admin::getSimpleOptionsPricingContent($pInfo->get('simple_options')); ?>
  </dl>     
  
    
</div>  
<script>

$(document).ready(function() {
  _refreshSymbols();
  _updatePricingDivChevrons();
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

function updateDiscountDisplay() {
  var base = parseFloat($('#products_base_price').val());
  $(".sprice").each( function() {
    var sprice = parseFloat($(this).val());
    var discount = (((base - sprice) / base) * 100).toFixed(<?php echo DECIMAL_PLACES; ?>);
    if (sprice > base) {
      $(this).closest('div.columns').find('.tag').removeClass('blue-bg').addClass('red-bg').html('+' + discount.toString() + '%').blinkf();
    } else {
      $(this).closest('div.columns').find('.tag').removeClass('red-bg').addClass('blue-bg').html('-' + discount.toString() + '%').unblinkf();
    }
  });   
}

/* update the open/close chevrons on pricing tab */
function _updatePricingDivChevrons() {
  var gpVisible = $('#groups_pricing_container').is(":visible");
  var qpbVisible = $('#qty_breaks_pricing_container').is(":visible");
  var spVisible = $('#specials_pricing_container').is(":visible");
  var iconOpen = 'icon-chevron-thin-down';
  var iconClose = 'icon-chevron-thin-up';
  if (gpVisible) {
    $('#groups_pricing_container_span').removeClass(iconOpen).addClass(iconClose);
  } else {    
    $('#groups_pricing_container_span').removeClass(iconClose).addClass(iconOpen);
  }
  if (qpbVisible) {
    $('#qty_breaks_pricing_container_span').removeClass(iconOpen).addClass(iconClose);
  } else {    
    $('#qty_breaks_pricing_container_span').removeClass(iconClose).addClass(iconOpen);
  }
  if (spVisible) {
    $('#specials_pricing_container_span').removeClass(iconOpen).addClass(iconClose);
  } else {    
    $('#specials_pricing_container_span').removeClass(iconClose).addClass(iconOpen);
  }    
}

function toggleSection(section) {
  
  var open = $('#' + section).is(":visible");
  if (open) {
    $('#' + section).slideUp('300');
  } else {
    $('#' + section).slideDown('300');
  }
  
  setTimeout(function() {  
    _updatePricingDivChevrons();
  }, 400);
}
</script>