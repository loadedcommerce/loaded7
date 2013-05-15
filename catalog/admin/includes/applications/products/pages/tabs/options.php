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
          <div class="strong"><?php echo $lC_Language->get('text_inventory_control'); ?><?php echo lc_show_info_bubble($lC_Language->get('info_bubble_inventory_control'), null, 'info-spot on-right margin-left'); ?></div>
          
          <div id="optionsInvControlButtons" class="button-group small-margin-top">
            <!-- lc_options_inventory_control begin -->
            <label for="ioc_radio_1" class="oicb button blue-active<?php echo (isset($lC_ObjectInfo) && ($lC_ObjectInfo->getInt('has_children') == 1) ? '' : ' active'); ?>">
              <input type="radio" name="inventory_option_control_radio_group" id="ioc_radio_1" value="1" />
              <?php echo $lC_Language->get('text_simple'); ?>
            </label>
            <label for="ioc_radio_2" class="oicb button red-active<?php echo (isset($lC_ObjectInfo) && ($lC_ObjectInfo->getInt('has_children') == 1) ? ' active' : ''); ?>">
              <input type="radio" name="inventory_option_control_radio_group" id="ioc_radio_2" value="2" />
              <?php echo $lC_Language->get('text_multi_sku'); ?>
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
          <?php
            $Qattributes = $lC_Database->query('select id, code from :table_templates_boxes where modules_group = :modules_group order by code');
            $Qattributes->bindTable(':table_templates_boxes');
            $Qattributes->bindValue(':modules_group', 'product_attributes');
            $Qattributes->execute();
            
            while ( $Qattributes->next() ) {
echo "<pre>";
print_r($Qattributes->toArray());
echo "</pre>";

              
              $module = basename($Qattributes->value('code'));
              if ( !class_exists('lC_ProductAttributes_' . $module) ) {
                if ( file_exists(DIR_FS_CATALOG . 'admin/includes/modules/product_attributes/' . $module . '.php') ) {
                  include(DIR_FS_CATALOG . 'admin/includes/modules/product_attributes/' . $module . '.php');
                }
              }
              if ( class_exists('lC_ProductAttributes_' . $module) ) {
                $module = 'lC_ProductAttributes_' . $module;
                $module = new $module();

    $lC_Language->loadIniFile('modules/product_attributes/' . $module->getCode() . '.php');
                
                
echo '[' . $lC_Language->get('product_attributes_' . $module->getCode() . '_title') . ']<br>';
echo '[' . $module->getTitle() . ']<br>';
                
              ?>
              <div class="twelve-columns small-margin-bottom">
                <span><?php echo $module->getTitle(); ?></span>
                <span><?php echo $module->getSection(); ?></span>
              </div>
              <div class="twelve-columns margin-bottom product-module-content">
                <?php echo $module->setFunction((isset($attributes[$Qattributes->valueInt('id')]) ? $attributes[$Qattributes->valueInt('id')] : null)); ?>
              </div>
              <?php
              }
            }
          ?>
        </div>
      </fieldset>
    </div>
    
    <div id="simpleOptionsContainer" class="twelve-columns">
      <fieldset class="fieldset">
        <legend class="legend"><?php echo $lC_Language->get('text_simple_options'); ?></legend>
        <span class="float-right" style="margin:-23px -8px 0 0;"><a class="button icon-plus-round green-gradient " href="javascript:void(0)" onclick="addNewSimpleOption();"><?php echo $lC_Language->get('button_add'); ?></a></span>

      </fieldset>    
    </div>
    
    <div id="bundleProductsContainer" class="twelve-columns">
      <fieldset class="fieldset">
        <legend class="legend"><?php echo $lC_Language->get('text_bundle_products'); ?><?php echo lc_go_pro('info-spot on-right margin-left mid-margin-right'); ?></legend>
        <span class="float-right" style="margin:-23px -8px 0 0;"><a class="button icon-plus-round green-gradient " href="javascript:void(0)" onclick="addNewBundleOption();"><?php echo $lC_Language->get('button_add'); ?></a></span>

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
  alert('addNewMultiSkuOption() called');
}

function addNewSimpleOption() {
  alert('addNewSimpleOption() called');
}

function addNewBundleOption() {
  alert('Coming Soon');
}

</script>