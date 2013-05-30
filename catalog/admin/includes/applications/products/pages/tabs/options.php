<?php
/**
  $Id: options.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
global $lC_Language, $pInfo; 
?>
<div id="section_options_content" class="with-padding">
  <div class="columns">
    <div class="twelve-columns">
      <div class="new-row-mobile six-columns six-columns-tablet twelve-columns-mobile">
        <div class="twelve-columns no-margin-bottom">
          <div class="strong"><?php echo $lC_Language->get('text_inventory_control'); ?><?php echo lc_show_info_bubble($lC_Language->get('info_bubble_inventory_control'), null, 'info-spot on-right grey margin-left'); ?></div>
          
          <div id="optionsInvControlButtons" class="button-group small-margin-top">
            <!-- lc_options_inventory_control begin -->
            <label for="ioc_radio_1" class="oicb button blue-active<?php echo (isset($pInfo) && ($pInfo->getInt('has_children') == 1) ? '' : ' active'); ?>">
              <input type="radio" name="inventory_option_control_radio_group" id="ioc_radio_1" value="1" />
              <?php echo $lC_Language->get('text_simple'); ?>
            </label>
            <label for="ioc_radio_2" class="disabled oicb button red-active<?php echo (isset($pInfo) && ($pInfo->getInt('has_children') == 1) ? ' active' : ''); ?>">
              <input type="radio" name="inventory_option_control_radio_group" id="ioc_radio_2" value="2" />
              <?php echo $lC_Language->get('text_multi_sku') . lc_go_pro('info-spot on-left grey mid-margin-left mid-margin-right'); ?>
            </label>
            <!-- lc_options_inventory_control end -->
          </div>
        </div>
      </div>
    </div>

    <div id="multiSkuContainer" class="twelve-columns" style="position:relative; display:none;">
      <fieldset class="fieldset">
        <legend class="legend"><?php echo $lC_Language->get('text_multi_sku_options'); ?></legend>
        <span class="float-right" style="margin:-23px -8px 0 0;"><a class="button icon-plus-round green-gradient " href="javascript:void(0)" onclick="addNewMultiSkuOption();"><?php echo $lC_Language->get('button_add'); ?></a></span>
        <div class="new-row-mobile six-columns six-columns-tablet twelve-columns-mobile no-margin-bottom">
          <span class="thin"><?php echo $lC_Language->get('text_coming_soon'); ?>...</span>
        </div>
      </fieldset>
    </div>
    
    <div id="simpleOptionsContainer" class="twelve-columns">
      <fieldset class="fieldset">
        <legend class="legend"><?php echo $lC_Language->get('text_simple_options'); ?></legend>
        <span class="float-right" style="margin:-26px -8px 4px 0;"><a class="button icon-plus-round green-gradient " href="javascript:void(0)" onclick="addSimpleOption();"><?php echo $lC_Language->get('button_add'); ?></a></span>
        <table width="100%" style="" id="simpleOptionsTable" class="simple-table">
          <thead>
            <tr>
              <th scope="col" class="align-center with-tooltip" data-tooltip-options='{"classes":["orange-gradient"],"position":"bottom"}' title="Drag & Drop Rows to Sort" width="16px"><img style="vertical-align:middle;" src="templates/default/img/icons/16/drag.png"></th>
              <th scope="col" class="align-left"><?php echo $lC_Language->get('table_heading_name'); ?></th>
              <th scope="col" class="align-left"><?php echo $lC_Language->get('table_heading_type'); ?></th>
              <th scope="col" class="align-left"><?php echo $lC_Language->get('table_heading_sort'); ?></th>
              <th scope="col" class="align-center" width="50px"><?php echo $lC_Language->get('table_heading_remove'); ?></th>
            </tr>
          </thead>
          <tbody class="sorted_table"><?php echo lC_Products_Admin::getSimpleOptionsContent($pInfo->get('simple_options')); ?></tbody>
        </table>
      </fieldset>    
    </div>
    <?php /*
    <div id="bundleProductsContainer" class="twelve-columns">
      <fieldset class="fieldset">
        <legend class="legend"><?php echo $lC_Language->get('text_bundle_products'); ?><?php echo lc_go_pro('info-spot on-right margin-left mid-margin-right'); ?></legend>
        <span class="float-right" style="margin:-23px -8px 0 0;"><a class="button icon-plus-round green-gradient " href="javascript:void(0)" onclick="addNewBundleOption();"><?php echo $lC_Language->get('button_add'); ?></a></span>
        <span class="thin"><?php echo $lC_Language->get('text_coming_soon'); ?>...</span>
      </fieldset>     
    </div>
    */ ?>
  </div>
</div>
<script>
$(document).ready(function() {
  _setSortOrder();
  
  $('.sorted_table').sortable({  
    containerSelector: 'tbody',
    itemSelector: 'tr',
    placeholder: '<tr class="placeholder"/>',
    tolerance: '1',
    onDragStart: function (item, group, _super) {      
      item.css({
        height: item.height(),
        width: item.width()
      });
      item.addClass("dragged");
      $('body').addClass('dragging');
    },
    onDrop: function  (item, container, _super) { 
      item.removeClass("dragged");
      $("body").removeClass("dragging");

      _setSortOrder();
    }    
  });
});   

function _setSortOrder() {
  var order = 0;
  $('#simpleOptionsTable tr').each(function () {
    var sort = $(this).find('input[class=sort]');
    var td = $(this).find('td[class=sort]');
    if ($(sort.val()) != undefined) {
      $(sort).val(order.toString());
      $(td).text(order.toString());
      order = parseInt(order) + 10;
    }
  });
}

$('input[name=inventory_control_radio_group]').click(function() {
  _updateInvControlType($(this).val());
});
$('input[name=inventory_option_control_radio_group]').click(function() {
  _updateInvControlType($(this).val());
});

function _updateInvControlType(type) {
  // remomve the active classes
  $('.oicb').removeClass('active');  
  if (type == '1') {
    $('#inventory_control_simple').show('300');
    $('#inventory_control_multi').hide('300');
    $('label[for=\'ic_radio_1\']').addClass('active');
    $('label[for=\'ioc_radio_1\']').addClass('active'); 
    $('#multiSkuContainer').hide();   
    $('#simpleOptionsContainer').show();   
  } else if (type == '2') {   
    $('#inventory_control_simple').hide('300');
    $('#inventory_control_multi').show('300');
    $('label[for=\'ic_radio_2\']').addClass('active');
    $('label[for=\'ioc_radio_2\']').addClass('active'); 
    $('#multiSkuContainer').show();   
    $('#simpleOptionsContainer').hide();        
  }
}

function addNewMultiSkuOption() {
  alert('<span class="thin"><?php echo $lC_Language->get('text_coming_soon'); ?>...</span>');
}

function addNewBundleOption() {
  alert('<span class="thin"><?php echo $lC_Language->get('text_coming_soon'); ?>...</span>');
}
</script>