<?php
  /**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: edit.php v1.0 2013-08-08 datazen $
  */
?>
<style scoped="scoped">
  .legend { font-weight:bold; font-size: 1.1em; }
  .qq-upload-drop-area { min-height: 100px; top: -200px; }
  .qq-upload-drop-area span { margin-top:-16px; }
  LABEL { font-weight:bold; }
  TD { padding: 5px 0 0 5px; }
</style>
<!-- Main content -->
<section role="main" id="main">
  <hgroup id="main-title" class="thin">
    <h1><?php echo (isset($cInfo)) ? lC_Application_Coupons::getName($cInfo, $lC_Language->getID()) : $lC_Language->get('heading_title_new_coupon'); ?></h1>
    <?php
      if ( $lC_MessageStack->exists($lC_Template->getModule()) ) {
        echo $lC_MessageStack->get($lC_Template->getModule());
      }
    ?>
  </hgroup>
  <form name="coupon" id="coupon" class="dataForm" action="<?php echo lc_href_link_admin(FILENAME_DEFAULT, $lC_Template->getModule() . '=' . (isset($cInfo) ? $cInfo->getInt('coupons_id') : '') . '&action=save'); ?>" method="post" enctype="multipart/form-data">
    <div id="coupon_div" class="columns with-padding-no-top">
      <div class="new-row-mobile twelve-columns twelve-columns-mobile no-margin-bottom">
        <div class="columns">              
          <div class="new-row-mobile six-columns twelve-columns-mobile">
            <!-- span class="button-group upsellwrapper">
            <label for="mode_coupon" class="button blue-active">
            <input type="radio" name="mode" id="mode_coupon" value="coupon" checked>
            <?php echo $lC_Language->get('text_coupon'); ?>
            </label>
            <label for="mode_rule" class="button green-active disabled upsellinfo" upselltitle="<?php echo $lC_Language->get('text_new_rule_upsell_title'); ?>" upselldesc="<?php echo $lC_Language->get('text_new_rule_upsell_desc'); ?>">
            <input type="radio" name="mode" id="mode_rule" value="rule" disabled>
            <?php echo $lC_Language->get('text_rule') . lc_go_pro(); ?>
            </label>
            </span -->
          </div>
        </div>
      </div>
      <div class="new-row-mobile twelve-columns twelve-columns-mobile" id="content">
        <fieldset class="fieldset fields-list">
          <legend class="legend"><?php echo $lC_Language->get('legend_coupon_details'); ?></legend>
          <div class="field-block button-height margin-bottom">
            <label for="code" class="label"><b><?php echo $lC_Language->get('label_short_name'); ?></b></label>
            <?php
              foreach ( $lC_Language->getAll() as $l ) {
              ?>
              <p> 
                <span class="input">
                  <label class="button silver-gradient glossy" for="<?php echo 'name[' . $l['id'] . ']'; ?>">
                    <?php echo $lC_Language->showImage($l['code']); ?>
                  </label>
                  <?php echo lc_draw_input_field('name[' . $l['id'] . ']', ((isset($cInfo)) ? lC_Application_Coupons::getName($cInfo, $l['id']) : null), 'class="required input-unstyled" onfocus="this.select();"'); ?>
                </span>
                <?php echo lc_show_info_bubble($lC_Language->get('info_bubble_name'), null, 'grey on-left margin-left'); ?>
              </p>  
              <?php
              }
            ?>
          </div>
          <div class="field-block button-height margin-bottom">
            <label for="notes" class="label"><b><?php echo $lC_Language->get('label_notes'); ?></b></label>
            <input type="text" onfocus="this.select();" name="notes" id="notes" value="<?php echo (isset($cInfo) ? $cInfo->get('notes') : null); ?>" class="input" style="width:80%;">
            <?php echo lc_show_info_bubble($lC_Language->get('info_bubble_notes'), null, 'info-spot on-left grey margin-left'); ?>
          </div>            
          <div class="field-block button-height margin-bottom">
            <label for="code" class="label"><b><?php echo $lC_Language->get('label_redemption_code'); ?></b></label>
            <input type="text" onfocus="this.select();" name="code" id="code" value="<?php echo (isset($cInfo) ? $cInfo->get('code') : null); ?>" class="input">
            <?php echo lc_show_info_bubble($lC_Language->get('info_bubble_redemption_code'), null, 'info-spot on-left grey margin-left'); ?>
          </div>
          <div class="field-drop button-height black-inputs">
            <div>
              <label for="type_reward" class="label"><b><?php echo $lC_Language->get('label_reward'); ?></b></label>
              <input type="radio" name="type" value="T" id="type_1" class="switch tiny mid-margin-right small-margin-left<?php if (isset($cInfo)) { echo ($cInfo->get('type') == 'T' || $cInfo->get('type') == 'R') ? ' checked' : null; } else { echo ' checked'; } ?>" onchange="updateRewardField($(this).val());">
              <input type="text" onfocus="this.select();" name="reward" id="reward" value="<?php echo (isset($cInfo) ? $cInfo->get('reward') : null); ?>" class="input">
              <span class="input-info mid-margin-left"><?php echo $lC_Language->get('text_price_or_percent'); ?></span>
              <?php echo lc_show_info_bubble($lC_Language->get('info_bubble_price_or_percent'), null, 'info-spot on-left grey margin-left'); ?>
            </div>
            <div class="mid-margin-top">
              <label for="type_free_shipping" class="label"></label>
              <input type="radio" name="type" value="S" id="type_2" class="switch tiny mid-margin-right small-margin-left<?php if (isset($cInfo)) { echo ($cInfo->get('type') == 'S') ? ' checked' : null; } ?>" onchange="updateRewardField($(this).val());">
              <span class="input-info mid-margin-left"><?php echo $lC_Language->get('text_free_shipping'); ?></span>
              <?php echo lc_show_info_bubble($lC_Language->get('info_bubble_free_shipping'), null, 'info-spot on-left grey margin-left'); ?>
            </div>
            <div class="mid-margin-top">
              <input type="checkbox" name="status" id="status" class="switch wider" data-text-off="<?php echo $lC_Language->get('slider_switch_disabled'); ?>" data-text-on="<?php echo $lC_Language->get('slider_switch_enabled'); ?>"<?php echo ((isset($cInfo) && $cInfo->get('status') != 1) ? null : ' checked'); ?> />
              <?php echo lc_show_info_bubble($lC_Language->get('info_bubble_switch'), null, 'info-spot on-left grey margin-left'); ?>
            </div>
            <!-- div class="mid-margin-top upsellwrapper">
            <label for="type_free_product" class="label"></label>
            <input type="radio" name="type" value="P" id="type_3" class="switch tiny mid-margin-right small-margin-left disabled<?php if (isset($cInfo)) { echo ($cInfo->get('type') == 'P') ? ' checked' : null; } ?>" onchange="updateRewardField($(this).val());">
            <span class="input-info mid-margin-left"><?php echo $lC_Language->get('text_free_product'); ?></span>
            <span class="small-margin-left upsellinfo" upselltitle="<?php echo $lC_Language->get('text_free_product_upsell_title'); ?>" upselldesc="<?php echo $lC_Language->get('text_free_product_upsell_desc'); ?>"><?php echo lc_go_pro(); ?></span>
            <?php echo lc_show_info_bubble($lC_Language->get('info_bubble_free_product'), null, 'info-spot on-left grey margin-left'); ?>
            </div -->
            <input type="hidden" name="type" id="type" value="<?php echo (isset($cInfo) ? $cInfo->get('type') : null); ?>" />
          </div>
        </fieldset>
        <script>
          function updateReward(val) {
            $('#reward').val("");
            $('#type').val(val);
          }
        </script>
      </div>
      <div class="new-row-mobile twelve-columns twelve-columns-mobile" id="limits">
        <fieldset class="fieldset fields-list">
          <legend class="legend"><?php echo $lC_Language->get('legend_use_limits'); ?></legend>
          <div class="field-block button-height">
            <label for="purchase_over" class="label"><b><?php echo $lC_Language->get('label_purchase_over'); ?></b></label>
            <div class="inputs" style="display:inline; padding:8px 0;">
              <span class="mid-margin-left no-margin-right"><?php echo $lC_Currencies->getSymbolLeft(); ?></span>
              <input type="text" style="width:152px;" onfocus="this.select();" onchange="updatePricingDiscountDisplay();" class="input-unstyled" name="purchase_over" id="purchase_over" value="<?php echo (isset($cInfo) ? $cInfo->get('purchase_over') : number_format(0.00, DECIMAL_PLACES)); ?>" class="input strong" />
            </div>              
            <?php echo lc_show_info_bubble($lC_Language->get('info_bubble_purchase_over'), null, 'info-spot on-left grey margin-left'); ?>
          </div>
          <div class="field-block button-height margin-bottom">
            <label for="uses_per_coupon" class="label"><b><?php echo $lC_Language->get('label_uses_per_coupon'); ?></b></label>
            <input type="text"onfocus="this.select();" name="uses_per_coupon" id="uses_per_coupon" value="<?php echo (isset($cInfo) ? $cInfo->get('uses_per_coupon') : 0); ?>" class="input">
            <?php echo lc_show_info_bubble($lC_Language->get('info_bubble_uses_per_coupon'), null, 'info-spot on-left grey margin-left'); ?>
          </div>
          <div class="field-block button-height">
            <label for="uses_per_customer" class="label"><b><?php echo $lC_Language->get('label_uses_per_customer'); ?></b></label>
            <input type="text" onfocus="this.select();" name="uses_per_customer" id="uses_per_customer" value="<?php echo (isset($cInfo) ? $cInfo->get('uses_per_customer') : 0); ?>" class="input">
            <?php echo lc_show_info_bubble($lC_Language->get('info_bubble_uses_per_customer'), null, 'info-spot on-left grey margin-left'); ?>
          </div>
          <div class="field-block button-height">
            <label for="start_date" class="label"><b><?php echo $lC_Language->get('label_start_date'); ?></b></label>
            <div>
              <span class="input">
                <span class="icon-calendar"></span>
                <input type="text" onfocus="this.select();" name="start_date" id="start_date" value="<?php echo (isset($cInfo) ? $cInfo->get('start_date') : null); ?>" class="input-unstyled datepicker" style="max-width:147px;">
              </span>
              <?php echo lc_show_info_bubble($lC_Language->get('info_bubble_start_date'), null, 'info-spot on-left grey margin-left'); ?>
            </div>
          </div>
          <div class="field-block button-height">
            <label for="expires_date" class="label"><b><?php echo $lC_Language->get('label_expires_date'); ?></b></label>
            <div>
              <span class="input">
                <span class="icon-calendar"></span>
                <input type="text" name="expires_date" id="expires_date" value="<?php echo (isset($cInfo) ? $cInfo->get('expires_date') : null); ?>" class="input-unstyled datepicker" style="max-width:147px;">
              </span>
              <?php echo lc_show_info_bubble($lC_Language->get('info_bubble_expires_date'), null, 'info-spot on-left grey margin-left'); ?>
            </div>
          </div>
        </fieldset>
      </div>
      <!-- div class="new-row-mobile twelve-columns twelve-columns-mobile" id="restrictions">
      <fieldset class="fieldset fields-list upsellwrapper">
      <legend class="legend upsellinfo" upselltitle="<?php //echo $lC_Language->get('text_coupon_restrictions_upsell_title'); ?>" upselldesc="<?php //echo $lC_Language->get('text_coupon_restrictions_upsell_desc'); ?>"><?php //echo $lC_Language->get('legend_restrictions'); ?><?php //echo lc_go_pro(true); ?></legend>
      <div class="field-block button-height">
      <label for="" class="label"><b><?php //echo $lC_Language->get('label_products'); ?></b></label>
      <input type="checkbox" class="switch wider disabled" data-text-off="<?php //echo $lC_Language->get('slider_switch_disabled'); ?>" data-text-on="<?php //echo $lC_Language->get('slider_switch_enabled'); ?>" />  
      <?php //echo lc_show_info_bubble($lC_Language->get('info_bubble_proucts_restrictions'), null, 'info-spot on-left grey margin-left'); ?>
      </div>
      <div class="field-block button-height">
      <label for="" class="label"><b><?php //echo $lC_Language->get('label_customers'); ?></b></label>
      <input type="checkbox" class="switch wider disabled" data-text-off="<?php //echo $lC_Language->get('slider_switch_disabled'); ?>" data-text-on="<?php //echo $lC_Language->get('slider_switch_enabled'); ?>" />
      <?php //echo lc_show_info_bubble($lC_Language->get('info_bubble_customers_restrictions'), null, 'info-spot on-left grey margin-left'); ?>
      </div>
      <div class="field-block button-height margin-bottom">
      <label for="" class="label"><b><?php //echo $lC_Language->get('label_groups'); ?></b><small class="tag orange-bg small-margin-left">B2B</small></label>
      <input type="checkbox" class="switch wider disabled" data-text-off="<?php //echo $lC_Language->get('slider_switch_disabled'); ?>" data-text-on="<?php //echo $lC_Language->get('slider_switch_enabled'); ?>" />
      <?php //echo lc_show_info_bubble($lC_Language->get('info_bubble_groups_restrictions'), null, 'info-spot on-left grey margin-left'); ?>
      </div>
      </fieldset>
      </div -->
    </div>
    <?php echo lc_draw_hidden_field('subaction', 'confirm'); ?>
    <div class="clear-both"></div>
    <div class="six-columns twelve-columns-tablet">
      <div id="buttons-menu-div-listing">
        <div id="buttons-container" style="position: relative;" class="clear-both">
          <div class="align-right">
            <p class="button-height">
              <?php
                $save = (((int)$_SESSION['admin']['access'][$lC_Template->getModule()] < 2) ? '' : ' onclick="validateForm(\'#coupon\');"');
                $close = lc_href_link_admin(FILENAME_DEFAULT, $lC_Template->getModule());
                button_save_close($save, true, $close);
              ?>
            </p>
          </div>
        </div>
      </div>
    </div>
  </form>
</section>