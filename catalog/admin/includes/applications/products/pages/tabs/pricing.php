<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: pricing.php v1.0 2013-08-08 datazen $
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
        <input type="text" onfocus="this.select();" onchange="updatePricingDiscountDisplay();" class="input-unstyled" name="products_base_price" id="products_base_price" value="<?php echo (isset($pInfo) ? number_format(lc_round($pInfo->get('products_price'), DECIMAL_PLACES), DECIMAL_PLACES) : null); ?>" class="input strong" onblur="$('#products_price0').val(this.value);" />
      </div>    
      <?php echo lc_show_info_bubble($lC_Language->get('info_bubble_pricing_base_price'), null, 'info-spot on-left grey margin-left'); ?>
    </div>

    <div class="upsellwrapper field-block field-block-product button-height">
      <label upselltitle="<?php echo $lC_Language->get('text_group_pricing'); ?>" upselldesc="<?php echo $lC_Language->get('text_group_pricing_desc'); ?>" for="" class="label upsellinfo"><b><?php echo $lC_Language->get('text_group_pricing'); ?></b></label>
      <input type="checkbox" class="switch wider" onchange="togglePricingSection(this, 'groups_pricing_container');" data-text-off="<?php echo $lC_Language->get('slider_switch_disabled'); ?>" data-text-on="<?php echo $lC_Language->get('slider_switch_preview'); ?>" /><?php echo lc_show_info_bubble($lC_Language->get('info_bubble_pricing_group_pricing'), null, 'info-spot on-left grey margin-left'); ?>
      <?php //echo lc_go_pro(); ?>
      <div onclick="togglePricingSection(this, 'groups_pricing_container');" style="cursor:pointer;" class="field-block-chevron-container float-right"><span id="groups_pricing_container_span" class="icon-chevron-thin-down icon-size2"></span></div>
    </div>
    <div id="groups_pricing_container" class="field-drop button-height black-inputs" style="display:none;">
      <?php echo lC_Products_Admin::getGroupPricingContent(isset($pInfo) ? $pInfo->get('products_price') : null); ?>
    </div>

    
    
    <div class="field-block field-block-product button-height">
      <label for="qpb-switch" class="label"><b><?php echo $lC_Language->get('text_qty_break_pricing'); ?></b></label>
      <input id="qpb-switch" type="checkbox" class="switch wider" onchange="togglePricingSection(this, 'qty_breaks_pricing_container');" data-text-off="<?php echo $lC_Language->get('slider_switch_disabled'); ?>" data-text-on="<?php echo $lC_Language->get('slider_switch_enabled'); ?>" /><?php echo lc_show_info_bubble($lC_Language->get('info_bubble_pricing_qty_price_breaks'), null, 'info-spot on-left grey margin-left'); ?>
      <span id="qty_breaks_number_of_break_points">
        <div onclick="togglePricingSection(this, 'qty_breaks_pricing_container');" style="cursor:pointer;" class="field-block-chevron-container float-right"><span id="qty_breaks_pricing_container_span" class="icon-chevron-thin-down icon-size2"></span></div>
      </span>
    </div> 
    <div id="qty_breaks_pricing_container" class="field-drop button-height black-inputs" style="display:none;">
      <?php echo lC_Products_Admin_Pro::getQPBPricingContent(); ?>
    </div>
    
    <script>     
    function validateQPBPoint(e) {
      var curr = $(e).val();
      var thisID = $(e).attr('id');
      var prevArr = thisID.split('_');
      var prevID = (parseInt(prevArr[5]) > 0) ? parseInt(prevArr[5]) - 1 : 0;
      var prevIDText = prevArr[0] + '_' + prevArr[1] + '_' + prevArr[2] + '_point_' + prevArr[4] + '_' + prevID.toString();
      var prev = $('#' + prevIDText).val();
      
      if (parseInt(curr) <= parseInt(prev)) { 
        $.modal({
          content: '<div id="qpbMsg">'+
                   '  <div id="qpbConfirm">'+
                   '    <p><?php echo $lC_Language->get('ms_error_break_point_must_be_higher'); ?></p>'+
                   '  </div>'+
                   '</div>',
          title: '<?php echo $lC_Language->get('text_error'); ?>',
          width: 350,
          actions: {
            '<?php echo $lC_Language->get('button_close'); ?>' : {
              color: 'red',
              click: function(win) { $('#' + thisID).focus(); win.closeModal(); return false; }
            }
          },
          buttons: {
            '<?php echo $lC_Language->get('button_continue'); ?>': {
              classes:  'blue-gradient glossy',
              click:    function(win) { $('#' + thisID).focus(); win.closeModal(); return false
              }
            }
          },
          buttonsLowPadding: true
        });
      }
      
      _addNewQPBRow(e);
    }
    
    function _addNewQPBRow(e) {
      var parts = $(e).attr('id').split('_');
      var group = parseInt(parts[4]);
      var id = parseInt(parts[5]) + 1;
      var symbol = '<?php echo $lC_Currencies->getSymbolLeft(); ?>';
      
      if( $('#products_qty_break_point_' + group + '_' + parts[5]).val() == '' ) return false;
      if( $('#products_qty_break_point_' + group + '_' + id).length > 0 && $('#products_qty_break_point_' + group + '_' + id).val() == '') return false;

      row = '<div class="new-row-mobile twelve-columns small-margin-top">' +
            '  <div class="inputs" style="display:inline; padding:8px 0;">' +
            '    <span class="mid-margin-left no-margin-right">#</span>' +                  
            '    <input type="text" onblur="validateQPBPoint(this);" onfocus="this+select();" name="products_qty_break_point[' + group + '][' + id + ']" id="products_qty_break_point_' + group + '_' + id + '" value="" class="input-unstyled small-margin-right" style="width:60px;" />' +
            '  </div>' +         
            '  <small class="input-info mid-margin-left mid-margin-right no-wrap">Qty</small>' + 
            '  <div class="inputs" style="display:inline; padding:8px 0;">' +
            '    <span class="mid-margin-left no-margin-right">' + symbol + '</span>' +
            '    <input type="text" onblur="validateQPBPrice(this);" onfocus="this+select();" name="products_qty_break_price[' + group + '][' + id + ']" id="products_qty_break_price_' + group + '_' + id + '" value="" class="input-unstyled small-margin-right" style="width:60px;" />' +
            '  </div>' + 
            '  <small class="input-info mid-margin-left no-wrap">Price</small>' + 
            '</div>';      
                  
      $('#qpbContainer').append(row);  
    }
        
    function validateQPBPrice(e) {
      var curr = $(e).val();
      var thisID = $(e).attr('id');
      var prevArr = thisID.split('_');
      var prevID = (parseFloat(prevArr[5]) > 0) ? parseFloat(prevArr[5]) - 1 : 0;
      var prevIDText = prevArr[0] + '_' + prevArr[1] + '_' + prevArr[2] + '_price_' + prevArr[4] + '_' + prevID.toString();
      var prev = $('#' + prevIDText).val();
      
      if (parseFloat(curr) >= parseFloat(prev)) { 
        $.modal({
          content: '<div id="qpbMsg">'+
                   '  <div id="qpbConfirm">'+
                   '    <p><?php echo $lC_Language->get('ms_error_break_price_must_be_lower'); ?></p>'+
                   '  </div>'+
                   '</div>',
          title: '<?php echo $lC_Language->get('text_error'); ?>',
          width: 350,
          actions: {
            '<?php echo $lC_Language->get('button_close'); ?>' : {
              color: 'red',
              click: function(win2) { $('#' + thisID).focus(); win2.closeModal(); return false; }
            }
          },
          buttons: {
            '<?php echo $lC_Language->get('button_continue'); ?>': {
              classes:  'blue-gradient glossy',
              click:    function(win2) { $('#' + thisID).focus(); win2.closeModal(); return false
              }
            }
          },
          buttonsLowPadding: true
        });
      }
    }
    
    </script>
    
    
    
    
    <?php if ($pInfo) { ?>
    <div class="field-block field-block-product button-height">
      <label for="specials_pricing_switch" class="label"><b><?php echo $lC_Language->get('text_special_pricing'); ?></b></label>
      <input id="specials_pricing_switch" name="specials_pricing_switch" onchange="togglePricingSection(this, 'specials_pricing_container');" type="checkbox" class="switch wider specials-pricing" data-text-off="<?php echo $lC_Language->get('slider_switch_disabled'); ?>" data-text-on="<?php echo $lC_Language->get('slider_switch_enabled'); ?>"<?php echo (isset($pInfo) && ($pInfo->get('products_special_price') != null) ? ' checked' : ''); ?> /><?php echo lc_show_info_bubble($lC_Language->get('info_bubble_pricing_specials'), null, 'info-spot on-left grey margin-left margin-right'); ?>
      <div onclick="togglePricingSection(this, 'specials_pricing_container');" style="cursor:pointer;" class="field-block-chevron-container float-right"><span id="specials_pricing_container_span" class="icon-chevron-thin-down icon-size2"></span></div>
    </div>
    <div id="specials_pricing_container" class="field-drop button-height black-inputs no-margin-bottom"<?php echo (isset($pInfo) && ($pInfo->get('products_special_price') != null) ? ' style="display:block;"' : ' style="display:none;"'); ?>>
      <?php echo lC_Products_Admin::getSpecialPricingContent(); ?>
    </div>
    <?php } ?>                
  </fieldset>
  <fieldset class="fieldset large-margin-top">
    <legend class="legend"><?php echo $lC_Language->get('text_options_pricing'); ?></legend>  
    <dl id="simple-options-pricing-tab" class="accordion">
      <?php echo lC_Products_Admin::getOptionsPricingContent(); ?>
    </dl>     
  </fieldset>
</div> 