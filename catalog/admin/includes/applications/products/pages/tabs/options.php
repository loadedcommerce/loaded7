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
            <label for="ioc_radio_1" class="oicb button blue-active<?php echo (isset($pInfo) && ($pInfo->getInt('has_children') == 1 || $pInfo->getInt('has_subproducts') == 1) ? '' : ' active'); ?>">
              <input type="radio" name="inventory_option_control_radio_group" id="ioc_radio_1" value="1" />
              <?php echo $lC_Language->get('text_simple'); ?>
            </label>
            <label upsell="<?php echo $lC_Language->get('text_multi_sku_desc'); ?>" for="ioc_radio_2" class="disabled oicb button red-active<?php echo (isset($pInfo) && ($pInfo->getInt('has_children') == 1 || $pInfo->getInt('has_subproducts') == 1) ? ' active' : ''); ?>">
              <input type="radio" name="inventory_option_control_radio_group" id="ioc_radio_2" value="2" />
              <?php echo $lC_Language->get('text_multi_sku') . '<span class="small-margin-left">' . lc_go_pro() . '</span>'; ?>
            </label>
            <!-- lc_options_inventory_control end -->
          </div>
        </div>
      </div>
    </div>

    <div id="multiSkuContainer" class="twelve-columns" style="position:relative; display:none;">
      <fieldset class="fieldset">
        <legend class="legend"><?php echo $lC_Language->get('text_multi_sku_options'); ?></legend>
        <div class="twelve-columns"> 

          <!--VQMOD1-->          

        </div>
      </fieldset>
    </div>
    
    <div id="simpleOptionsContainer" class="twelve-columns">
      <fieldset class="fieldset">
        <legend class="legend"><?php echo $lC_Language->get('text_simple_options'); ?></legend>
        <span class="float-right" style="margin:-46px 0px 4px 0;"><a class="button icon-plus-round green-gradient glossy compact" href="javascript:void(0)" onclick="addSimpleOption();"><?php echo $lC_Language->get('button_add'); ?></a></span>
        <table width="100%" style="margin-top:-8px;" id="simpleOptionsTable" class="simple-table">
          <thead>
            <tr>
              <th scope="col" class="align-center">&nbsp;</th>
              <th scope="col" class="align-left with-tooltip" onclick="toggleAllSimpleOptionsRows();" data-tooltip-options='{"classes":["grey-gradient"],"position":"left"}' title="<?php echo $lC_Language->get('text_expand_collapse_all'); ?>" width="16px" style="cursor:pointer; font-size:1em;"><span id="toggle-all" class="icon-squared-plus icon-grey icon-size2"></span></th>
              <th scope="col" class="align-left"><?php echo $lC_Language->get('table_heading_name'); ?></th>
              <th scope="col" class="align-left hide-below-480"><?php echo $lC_Language->get('table_heading_type'); ?></th>
              <th scope="col" class="align-left hide-below-480"><?php echo $lC_Language->get('table_heading_sort'); ?></th>
              <th scope="col" class="align-center"><?php echo $lC_Language->get('table_heading_on'); ?></th>
              <th scope="col" class="align-right" width="50px"><?php echo $lC_Language->get('table_heading_action'); ?></th>
            </tr>
          </thead>
          <tbody class="sorted_table"><?php echo (isset($pInfo) ? lC_Products_Admin::getSimpleOptionsContent($pInfo->get('simple_options')) : null); ?></tbody>
        </table>
      </fieldset>    
    </div>
    <?php /*
    <div id="bundleProductsContainer" class="twelve-columns">
      <fieldset class="fieldset">
        <legend class="legend"><?php echo $lC_Language->get('text_bundle_products'); ?><?php echo lc_go_pro(); ?></legend>
        <span class="float-right" style="margin:-23px -8px 0 0;"><a class="button icon-plus-round green-gradient " href="javascript:void(0)" onclick="addNewBundleOption();"><?php echo $lC_Language->get('button_add'); ?></a></span>
        <span class="thin"><?php echo $lC_Language->get('text_coming_soon'); ?>...</span>
      </fieldset>     
    </div>
    */ ?>
  </div>
</div>