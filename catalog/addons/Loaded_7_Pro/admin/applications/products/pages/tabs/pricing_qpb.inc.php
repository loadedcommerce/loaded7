<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: pricing_qpb.inc.php v1.0 2014-01-24 datazen $
*/
global $lC_Language, $lC_Currencies, $pInfo; 
?>
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
$(document).ready(function() {
  var hasQPB = '<?php echo (isset($pInfo) && lC_Products_Admin_Pro::hasQPBPricing($pInfo->get('products_id')) === true) ? 1 : 0; ?>';
  if (hasQPB == 1) {
    $('#qpb-switch').click();
  }      
});
  
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