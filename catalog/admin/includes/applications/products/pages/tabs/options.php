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
global $lC_Language, $lC_ObjectInfo; 
?>
<div id="section_options_content" class="with-padding">
  <div class="columns">
    <div class="twelve-columns">
      <div class="new-row-mobile six-columns six-columns-tablet twelve-columns-mobile">
        <div class="twelve-columns no-margin-bottom">
          <span class="large-margin-right"><?php echo $lC_Language->get('text_inventory_control'); ?></span>
          <span class="large-margin-right">&nbsp;</span>
          <span class="margin-right">&nbsp;</span>
          <span class="info-spot on-left grey large-margin-left">
            <span class="icon-info-round"></span>
            <span class="info-bubble">
              Put the bubble text here
            </span>
          </span><br />
          <span id="optionsInvControlButtons" class="button-group small-margin-top">
            <!-- lc_options_inventory_control begin -->
            <label for="ioc_radio_1" class="oicb button blue-active<?php echo (isset($lC_ObjectInfo) && ($lC_ObjectInfo->getInt('has_children') == 1) ? '' : ' active'); ?>">
              <!-- move onclick to function later maestro -->
              <input type="radio" name="inventory_option_control_radio_group" id="ioc_radio_1" value="1" />
              <?php echo $lC_Language->get('text_simple'); ?>
            </label>
            <label for="ioc_radio_2" class="oicb button red-active<?php echo (isset($lC_ObjectInfo) && ($lC_ObjectInfo->getInt('has_children') == 1) ? ' active' : ''); ?>">
              <input type="radio" name="inventory_option_control_radio_group" id="ioc_radio_2" value="2" />
              <?php echo $lC_Language->get('text_multi_sku'); ?>
            </label>
            <label for="ioc_radio_3" class="oicb button disabled orange-active">
              <input type="radio" name="inventory_option_control_radio_group" id="ioc_radio_3" value="3" />
              <?php echo $lC_Language->get('text_recurring'); ?>
            </label><?php echo lc_go_pro(); ?>
            <!-- lc_options_inventory_control end -->
          </span>
        </div>
      </div>
    </div>
    <div class="twelve-columns">
      <fieldset class="fieldset">
        <legend class="legend"><?php echo $lC_Language->get('text_inventory_options_combo_sets'); ?></legend>
        <div class="columns">
          <div class="twelve-columns">
            Data Table is Going to be here :)                   
          </div>
        </div>
        <div><a class="button icon-plus icon-size2 icon-green margin-bottom nowrap" href="javascript:void(0)"><?php echo $lC_Language->get('text_new_inventory_option_combo_set'); ?></a></div>
      </fieldset>
    </div>
    <div class="twelve-columns">
      <fieldset class="fieldset">
        <legend class="legend"><?php echo $lC_Language->get('text_simple_inventory_options'); ?></legend>
        <div><a class="button icon-plus icon-size2 icon-green margin-bottom nowrap" href="javascript:void(0)"><?php echo $lC_Language->get('text_new_simple_inventory_option'); ?></a></div>
      </fieldset>
    </div>
    <div class="twelve-columns">
      <fieldset class="fieldset">
        <legend class="legend"><?php echo $lC_Language->get('text_simple_options'); ?></legend>
        <div><a class="button icon-plus icon-size2 icon-green margin-bottom nowrap" href="javascript:void(0)"><?php echo $lC_Language->get('text_new_simple_option'); ?></a></div>
      </fieldset>
    </div>
  </div>
</div>
<script>
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
  } else if (type == '2') {   
    $('#inventory_control_simple').hide('300');
    $('#inventory_control_multi').show('300');
    $('label[for=\'ic_radio_2\']').addClass('active');
    $('label[for=\'ioc_radio_2\']').addClass('active');    
  } else { // type=3
  
  }
}

</script>