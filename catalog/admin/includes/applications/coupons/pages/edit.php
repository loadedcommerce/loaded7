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
    $coupons_name = array();
    $coupons_description = array();
    while ($Qcd->next()) {
      $coupons_name[$Qcd->valueInt('language_id')] = $Qcd->value('coupons_name');
      $coupons_description[$Qcd->valueInt('language_id')] = $Qcd->value('coupons_description');
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
    <h1><?php echo (isset($lC_ObjectInfo) && isset($coupons_name[$lC_Language->getID()])) ? $coupons_name[$lC_Language->getID()] : $lC_Language->get('heading_title_new_coupon'); ?></h1>
    <?php
      if ( $lC_MessageStack->exists($lC_Template->getModule()) ) {
        echo $lC_MessageStack->get($lC_Template->getModule());
      }
    ?>
  </hgroup>
  <div class="with-padding-no-top">
    <form name="coupon" id="coupon" class="dataForm" action="<?php echo lc_href_link_admin(FILENAME_DEFAULT, $lC_Template->getModule() . '=' . (isset($lC_ObjectInfo) ? $lC_ObjectInfo->getInt('coupons_id') : '') . '&action=save'); ?>" method="post" enctype="multipart/form-data">
      <div class="columns with-padding">
        <div class="new-row-mobile twelve-columns twelve-columns-mobile no-margin-bottom">
          <div class="columns">              
            <div class="new-row-mobile six-columns twelve-columns-mobile">
              <span class="button-group">
                <label for="coupons_mode_coupon" class="button blue-active">
                  <input type="radio" name="coupons_mode" id="coupons_mode_coupon" value="coupon" checked>
                  <?php echo $lC_Language->get('text_coupon'); ?>
                </label>
                <label for="coupons_mode_rule" class="button green-active disabled">
                  <input type="radio" name="coupons_mode" id="coupons_mode_rule" value="rule" disabled>
                  <?php echo $lC_Language->get('text_rule') . lc_go_pro(); ?>
                </label>
              </span>
            </div>              
            <div class="new-row-mobile six-columns twelve-columns-mobile align-right" id="coupons_switch">
              <input type="checkbox" name="coupons_status" id="coupons_status" class="switch wider" data-text-off="DISABLED" data-text-on="ENABLED"<?php echo ((isset($lC_ObjectInfo) && $lC_ObjectInfo->get('coupons_status') != 1) ? null : ' checked'); ?> />
              <?php echo lc_show_info_bubble($lC_Language->get('info_bubble_switch'), null, 'info-spot on-left grey mid-margin-left'); ?>
            </div>
          </div>
        </div>
        <div class="new-row-mobile twelve-columns twelve-columns-mobile">
          <fieldset class="fieldset fields-list">
            <legend class="legend"><?php echo $lC_Language->get('legend_coupon_details'); ?></legend>
            <div class="field-block button-height margin-bottom">
              <label for="coupons_code" class="label"><b><?php echo $lC_Language->get('label_name_description'); ?></b></label>
              <?php
                foreach ( $lC_Language->getAll() as $l ) {
              ?>
                 <p> 
                  <span class="input" style="width:80%;">
                    <label class="button silver-gradient glossy" for="<?php echo 'coupons_name[' . $l['id'] . ']'; ?>">
                      <?php echo $lC_Language->showImage($l['code']); ?>
                    </label>
                    <?php echo lc_draw_input_field('coupons_name[' . $l['id'] . ']', (isset($lC_ObjectInfo) && isset($coupons_name[$l['id']]) ? $coupons_name[$l['id']] : null), 'class="required input-unstyled" style="width:80%;"'); ?>
                  </span>
                  <?php echo lc_show_info_bubble($lC_Language->get('info_bubble_name') . ': <b>' . $l['name'] . '</b>', null, 'grey on-left margin-left'); ?>
                </p>  
              <?php
                }
              ?>
            </div>
            <div class="field-block button-height margin-bottom">
              <label for="coupons_code" class="label"><b><?php echo $lC_Language->get('label_redemption_code'); ?></b></label>
              <input type="text" name="coupons_code" id="coupons_code" value="<?php echo (isset($lC_ObjectInfo) ? $lC_ObjectInfo->get('coupons_code') : null); ?>" class="input">
              <?php echo lc_show_info_bubble($lC_Language->get('info_bubble_redemption_code'), null, 'info-spot on-left grey margin-left'); ?>
            </div>
            <div class="field-drop button-height black-inputs">
              <div>
                <label for="coupons_type_reward" class="label"><b><?php echo $lC_Language->get('label_reward'); ?></b></label>
                <input type="radio" id="coupons_type_reward" class="radio mid-margin-right small-margin-left checked">
                <input type="text" name="coupons_reward" id="coupons_reward" value="<?php echo (isset($lC_ObjectInfo) ? $lC_ObjectInfo->get('coupons_reward') : null); ?>" class="input">
                <span class="input-info mid-margin-left"><?php echo $lC_Language->get('text_price_or_percent'); ?></span>
                <?php echo lc_show_info_bubble($lC_Language->get('info_bubble_price_or_percent'), null, 'info-spot on-left grey margin-left'); ?>
              </div>
              <div class="mid-margin-top">
                <label for="coupons_type_free_shipping" class="label"></label>
                <input type="radio" id="coupons_type_free_shipping" class="radio mid-margin-right small-margin-left disabled" onchange="alert('-1');">
                <span class="input-info mid-margin-left"><?php echo $lC_Language->get('text_free_shipping'); ?></span>
                <?php echo lc_show_info_bubble($lC_Language->get('info_bubble_free_shipping'), null, 'info-spot on-left grey margin-left'); ?>
              </div>
              <div class="mid-margin-top">
                <label for="coupons_type_free_product" class="label"></label>
                <input type="radio" id="coupons_type_free_product" class="radio mid-margin-right small-margin-left disabled" onchange="alert('-2')">
                <span class="input-info mid-margin-left"><?php echo $lC_Language->get('text_free_product'); ?></span>
                <span class="small-margin-left"><?php echo lc_go_pro(); ?></span>
                <?php echo lc_show_info_bubble($lC_Language->get('info_bubble_free_product'), null, 'info-spot on-left grey margin-left'); ?>
              </div>
              <input type="hidden" name="coupons_type" id="coupons_type" value="R">
            </div>
          </fieldset>
        </div>
        <div class="new-row-mobile twelve-columns twelve-columns-mobile">
          <fieldset class="fieldset fields-list">
            <legend class="legend"><?php echo $lC_Language->get('legend_use_limits'); ?></legend>
            <div class="field-block button-height">
              <label for="coupons_purchase_over" class="label"><b><?php echo $lC_Language->get('label_purchase_over'); ?></b></label>
              <input type="text" name="coupons_purchase_over" id="coupons_purchase_over" value="<?php echo (isset($lC_ObjectInfo) ? $lC_ObjectInfo->get('coupons_purchase_over') : null); ?>" class="input">
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
              <label for="coupons_start_date" class="label"><b><?php echo $lC_Language->get('label_start_date'); ?></b></label>
              <div>
                <span class="input">
                  <span class="icon-calendar"></span>
                  <input type="text" name="coupons_start_date" id="coupons_start_date" value="<?php echo (isset($lC_ObjectInfo) ? $lC_ObjectInfo->get('coupons_start_date') : null); ?>" class="input-unstyled datepicker" style="max-width:147px;">
                </span>
                <?php echo lc_show_info_bubble($lC_Language->get('info_bubble_start_date'), null, 'info-spot on-left grey margin-left'); ?>
              </div>
            </div>
            <div class="field-block button-height">
              <label for="coupons_expires_date" class="label"><b><?php echo $lC_Language->get('label_expires_date'); ?></b></label>
              <div>
                <span class="input">
                  <span class="icon-calendar"></span>
                  <input type="text" name="coupons_expires_date" id="coupons_expires_date" value="<?php echo (isset($lC_ObjectInfo) ? $lC_ObjectInfo->get('coupons_expires_date') : null); ?>" class="input-unstyled datepicker" style="max-width:147px;">
                </span>
                <?php echo lc_show_info_bubble($lC_Language->get('info_bubble_expires_date'), null, 'info-spot on-left grey margin-left'); ?>
              </div>
            </div>
          </fieldset>
        </div>
        <div class="new-row-mobile twelve-columns twelve-columns-mobile">
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
            <p class="white big-text small-margin-top"><?php echo (isset($lC_ObjectInfo) ? $lC_ObjectInfo->get('coupons_name') : $lC_Language->get('text_new_coupon')); ?></p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>