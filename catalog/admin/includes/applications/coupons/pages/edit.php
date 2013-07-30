<?php
/*
  $Id: edit.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
  if ( is_numeric($_GET[$lC_Template->getModule()]) ) {
    $lC_ObjectInfo = new lC_ObjectInfo(lC_Coupons_Admin::get($_GET[$lC_Template->getModule()]));
    $Qcd = $lC_Database->query('select * from :table_coupons_description where coupons_id = :coupons_id');
    $Qcd->bindTable(':table_coupons_description', TABLE_COUPONS_DESCRIPTION);
    $Qcd->bindInt(':coupons_id', $lC_ObjectInfo->get('coupons_id'));
    $Qcd->execute();
    $name = array();
    while ($Qcd->next()) {
      $name[$Qcd->valueInt('language_id')] = $Qcd->value('name');
    }
  }
  $lC_Template->loadModal($lC_Template->getModule());
?>
<style>
  .qq-upload-drop-area { min-height: 100px; top: -200px; }
  .qq-upload-drop-area span { margin-top:-16px; }
  LABEL { font-weight:bold; }
  TD { padding: 5px 0 0 5px; }
</style>
<!-- Main content -->
<section role="main" id="main">
  <hgroup id="main-title" class="thin">
    <h1><?php echo (isset($lC_ObjectInfo) && isset($name[$lC_Language->getID()])) ? $name[$lC_Language->getID()] : $lC_Language->get('heading_title_new_coupon'); ?></h1>
    <?php
      if ( $lC_MessageStack->exists($lC_Template->getModule()) ) {
        echo $lC_MessageStack->get($lC_Template->getModule());
      }
    ?>
  </hgroup>
  <div class="with-padding-no-top">
    <form name="coupon" id="coupon" class="dataForm" action="<?php echo lc_href_link_admin(FILENAME_DEFAULT, $lC_Template->getModule() . '=' . (isset($lC_ObjectInfo) ? $lC_ObjectInfo->getInt('coupons_id') : '') . '&action=save'); ?>" method="post" enctype="multipart/form-data">
      <div id="coupon_div" class="columns with-padding">
        <div class="new-row-mobile twelve-columns twelve-columns-mobile mid-margin-bottom">
          <div class="columns">              
            <div class="new-row-mobile six-columns twelve-columns-mobile">
              <span class="button-group">
                <label for="mode_coupon" class="button blue-active">
                  <input type="radio" name="mode" id="mode_coupon" value="coupon" checked>
                  <?php echo $lC_Language->get('text_coupon'); ?>
                </label>
                <label for="mode_rule" class="button green-active disabled">
                  <input type="radio" name="mode" id="mode_rule" value="rule" disabled>
                  <?php echo $lC_Language->get('text_rule') . lc_go_pro(); ?>
                </label>
              </span>
            </div>              
            <div class="new-row-mobile six-columns twelve-columns-mobile align-right" id="switch">
              <input type="checkbox" name="status" id="status" class="switch wider" data-text-off="DISABLED" data-text-on="ENABLED"<?php echo ((isset($lC_ObjectInfo) && $lC_ObjectInfo->get('status') != 1) ? null : ' checked'); ?> />
              <?php echo lc_show_info_bubble($lC_Language->get('info_bubble_switch'), null, 'info-spot on-left grey mid-margin-left'); ?>
            </div>
          </div>
        </div>
        <div class="new-row-mobile twelve-columns twelve-columns-mobile" id="content">
          <fieldset class="fieldset fields-list">
            <legend class="legend"><?php echo $lC_Language->get('legend_coupon_details'); ?></legend>
            <div class="field-block button-height margin-bottom">
              <label for="code" class="label"><b><?php echo $lC_Language->get('label_name_description'); ?></b></label>
              <?php
                foreach ( $lC_Language->getAll() as $l ) {
              ?>
                 <p> 
                  <span class="input" style="width:80%;">
                    <label class="button silver-gradient glossy" for="<?php echo 'name[' . $l['id'] . ']'; ?>">
                      <?php echo $lC_Language->showImage($l['code']); ?>
                    </label>
                    <?php echo lc_draw_input_field('name[' . $l['id'] . ']', (isset($lC_ObjectInfo) && isset($name[$l['id']]) ? $name[$l['id']] : null), 'class="required input-unstyled" style="width:80%;"'); ?>
                  </span>
                  <?php echo lc_show_info_bubble($lC_Language->get('info_bubble_name') . ': <b>' . $l['name'] . '</b>', null, 'grey on-left margin-left'); ?>
                </p>  
              <?php
                }
              ?>
            </div>
            <div class="field-block button-height margin-bottom">
              <label for="code" class="label"><b><?php echo $lC_Language->get('label_redemption_code'); ?></b></label>
              <input type="text" name="code" id="code" value="<?php echo (isset($lC_ObjectInfo) ? $lC_ObjectInfo->get('code') : null); ?>" class="input">
              <?php echo lc_show_info_bubble($lC_Language->get('info_bubble_redemption_code'), null, 'info-spot on-left grey margin-left'); ?>
            </div>
            <div class="field-drop button-height black-inputs">
              <?php
                if (isset($lC_ObjectInfo)) {
                  if ($lC_ObjectInfo->get('type') == 'T') {
                    $type = 'T';
                  } else if ($lC_ObjectInfo->get('type') == 'R') {
                    $type = 'R';
                  } else if ($lC_ObjectInfo->get('type') == 'S') {
                    $type = 'S';
                  } else if ($lC_ObjectInfo->get('type') == 'P') {
                    $type = 'P';
                  }
                }
              ?>
              <div>
                <label for="type_reward" class="label"><b><?php echo $lC_Language->get('label_reward'); ?></b></label>
                <input type="radio" name="type_radio" id="type_reward" class="radio mid-margin-right small-margin-left<?php echo ($type == 'T' || $type == 'R') ? ' checked' : null; ?>" onchange="$('#type').val('');">
                <input type="text" name="reward" id="reward" value="<?php echo (isset($lC_ObjectInfo) ? $lC_ObjectInfo->get('reward') : null); ?>" class="input">
                <span class="input-info mid-margin-left"><?php echo $lC_Language->get('text_price_or_percent'); ?></span>
                <?php echo lc_show_info_bubble($lC_Language->get('info_bubble_price_or_percent'), null, 'info-spot on-left grey margin-left'); ?>
              </div>
              <div class="mid-margin-top">
                <label for="type_free_shipping" class="label"></label>
                <input type="radio" name="type_radio" id="type_free_shipping" class="radio mid-margin-right small-margin-left disabled<?php echo ($type == 'S') ? ' checked' : null; ?>" onchange="$('#reward').val('');$('#type').val('S');">
                <span class="input-info mid-margin-left"><?php echo $lC_Language->get('text_free_shipping'); ?></span>
                <?php echo lc_show_info_bubble($lC_Language->get('info_bubble_free_shipping'), null, 'info-spot on-left grey margin-left'); ?>
              </div>
              <div class="mid-margin-top">
                <label for="type_free_product" class="label"></label>
                <input type="radio" name="type_radio" id="type_free_product" class="radio mid-margin-right small-margin-left disabled<?php echo ($type == 'P') ? ' checked' : null; ?>" onchange="$('#reward').val('');$('#type').val('P');">
                <span class="input-info mid-margin-left"><?php echo $lC_Language->get('text_free_product'); ?></span>
                <span class="small-margin-left"><?php echo lc_go_pro(); ?></span>
                <?php echo lc_show_info_bubble($lC_Language->get('info_bubble_free_product'), null, 'info-spot on-left grey margin-left'); ?>
              </div>
              <input type="hidden" name="type" id="type" value="<?php echo $type; ?>" />
            </div>
          </fieldset>
        </div>
        <div class="new-row-mobile twelve-columns twelve-columns-mobile" id="limits">
          <fieldset class="fieldset fields-list">
            <legend class="legend"><?php echo $lC_Language->get('legend_use_limits'); ?></legend>
            <div class="field-block button-height">
              <label for="purchase_over" class="label"><b><?php echo $lC_Language->get('label_purchase_over'); ?></b></label>
              <input type="text" name="purchase_over" id="purchase_over" value="<?php echo (isset($lC_ObjectInfo) ? $lC_ObjectInfo->get('purchase_over') : null); ?>" class="input">
              <?php echo lc_show_info_bubble($lC_Language->get('info_bubble_purchase_over'), null, 'info-spot on-left grey margin-left'); ?>
            </div>
            <div class="field-block button-height margin-bottom">
              <label for="uses_per_coupon" class="label"><b><?php echo $lC_Language->get('label_uses_per_coupon'); ?></b></label>
              <input type="text" name="uses_per_coupon" id="uses_per_coupon" value="<?php echo (isset($lC_ObjectInfo) ? $lC_ObjectInfo->get('uses_per_coupon') : null); ?>" class="input">
              <?php echo lc_show_info_bubble($lC_Language->get('info_bubble_uses_per_coupon'), null, 'info-spot on-left grey margin-left'); ?>
            </div>
            <div class="field-block button-height">
              <label for="uses_per_customer" class="label"><b><?php echo $lC_Language->get('label_uses_per_customer'); ?></b></label>
              <input type="text" name="uses_per_customer" id="uses_per_customer" value="<?php echo (isset($lC_ObjectInfo) ? $lC_ObjectInfo->get('uses_per_customer') : null); ?>" class="input">
              <?php echo lc_show_info_bubble($lC_Language->get('info_bubble_uses_per_customer'), null, 'info-spot on-left grey margin-left'); ?>
            </div>
            <div class="field-block button-height">
              <label for="start_date" class="label"><b><?php echo $lC_Language->get('label_start_date'); ?></b></label>
              <div>
                <span class="input">
                  <span class="icon-calendar"></span>
                  <input type="text" name="start_date" id="start_date" value="<?php echo (isset($lC_ObjectInfo) ? $lC_ObjectInfo->get('start_date') : null); ?>" class="input-unstyled datepicker" style="max-width:147px;">
                </span>
                <?php echo lc_show_info_bubble($lC_Language->get('info_bubble_start_date'), null, 'info-spot on-left grey margin-left'); ?>
              </div>
            </div>
            <div class="field-block button-height">
              <label for="expires_date" class="label"><b><?php echo $lC_Language->get('label_expires_date'); ?></b></label>
              <div>
                <span class="input">
                  <span class="icon-calendar"></span>
                  <input type="text" name="expires_date" id="expires_date" value="<?php echo (isset($lC_ObjectInfo) ? $lC_ObjectInfo->get('expires_date') : null); ?>" class="input-unstyled datepicker" style="max-width:147px;">
                </span>
                <?php echo lc_show_info_bubble($lC_Language->get('info_bubble_expires_date'), null, 'info-spot on-left grey margin-left'); ?>
              </div>
            </div>
          </fieldset>
        </div>
        <div class="new-row-mobile twelve-columns twelve-columns-mobile" id="restrictions">
          <fieldset class="fieldset fields-list">
            <legend class="legend"><?php echo $lC_Language->get('legend_restrictions'); ?><?php echo lc_go_pro(); ?></legend>
            <div class="field-block button-height">
              <label for="" class="label"><b><?php echo $lC_Language->get('label_products'); ?></b></label>
              <input type="checkbox" class="switch wider disabled" data-text-off="DISABLED" data-text-on="ENABLED" />
              <?php echo lc_show_info_bubble($lC_Language->get('info_bubble_proucts_restrictions'), null, 'info-spot on-left grey margin-left'); ?>
            </div>
            <div class="field-block button-height">
              <label for="" class="label"><b><?php echo $lC_Language->get('label_customers'); ?></b></label>
              <input type="checkbox" class="switch wider disabled" data-text-off="DISABLED" data-text-on="ENABLED" />
              <?php echo lc_show_info_bubble($lC_Language->get('info_bubble_customers_restrictions'), null, 'info-spot on-left grey margin-left'); ?>
            </div>
            <div class="field-block button-height margin-bottom">
              <label for="" class="label"><b><?php echo $lC_Language->get('label_groups'); ?></b><small class="tag orange-bg small-margin-left">B2B</small></label>
              <input type="checkbox" class="switch wider disabled" data-text-off="DISABLED" data-text-on="ENABLED" />
              <?php echo lc_show_info_bubble($lC_Language->get('info_bubble_groups_restrictions'), null, 'info-spot on-left grey margin-left'); ?>
            </div>
          </fieldset>
        </div>
      </div>
      <?php echo lc_draw_hidden_field('subaction', 'confirm'); ?>
    </form>
    <div class="clear-both"></div>
    <div id="floating-button-container" class="six-columns twelve-columns-tablet">
      <div id="floating-menu-div-listing">
        <div id="buttons-container" style="position: relative;" class="clear-both">
          <div style="float:right;">
            <p class="button-height" align="right">
              <a class="button" href="<?php echo lc_href_link_admin(FILENAME_DEFAULT, $lC_Template->getModule()); ?>">
                <span class="button-icon red-gradient glossy">
                  <span class="icon-cross"></span>
                </span>
                <span class="button-text"><?php echo $lC_Language->get('button_cancel'); ?></span>
              </a>&nbsp;
              <a class="button<?php echo (((int)$_SESSION['admin']['access'][$lC_Template->getModule()] < 3) ? ' disabled' : NULL); ?>" href="<?php echo (((int)$_SESSION['admin']['access'][$lC_Template->getModule()] < 2) ? '#' : 'javascript://" onclick="validateForm(\'#coupon\');'); ?>">
                <span class="button-icon green-gradient glossy">
                  <span class="icon-download"></span>
                </span>
                <span class="button-text"><?php echo $lC_Language->get('button_save'); ?></span> 
              </a>&nbsp;
            </p>
          </div>
          <div id="floating-button-container-title" class="hidden">
            <p class="white big-text small-margin-top"><?php echo (isset($lC_ObjectInfo) ? $lC_ObjectInfo->get('name') : $lC_Language->get('text_new_coupon')); ?></p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>