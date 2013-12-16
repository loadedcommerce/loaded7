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
<style>
  .legend { font-weight:bold; font-size: 1.1em; }
  .qq-upload-drop-area { min-height: 100px; top: -200px; }
  .qq-upload-drop-area span { margin-top:-16px; }
  LABEL { font-weight:bold; }
  TD { padding: 5px 0 0 5px; }
</style>
<!-- Main content -->
<section role="main" id="main">
  <hgroup id="main-title" class="thin">
    <h1><?php echo (isset($fInfo)) ? lC_Featured_products_Admin::getFeaturedName($fInfo->get('products_id')) : $lC_Language->get('heading_title_new_featured_product'); ?></h1>
    <?php
      if ( $lC_MessageStack->exists($lC_Template->getModule()) ) {
        echo $lC_MessageStack->get($lC_Template->getModule());
      }
    ?>
  </hgroup>
  <div class="with-padding-no-top">
    <form name="featured" id="featured" class="dataForm" action="<?php echo lc_href_link_admin(FILENAME_DEFAULT, $lC_Template->getModule() . '=' . (isset($fInfo) ? $fInfo->getInt('id') : '') . '&action=save'); ?>" method="post" enctype="multipart/form-data">
      <div id="featured_div" class="columns with-padding">
        <div class="new-row-mobile twelve-columns twelve-columns-mobile" id="content">
          <fieldset class="fieldset fields-list">
            <legend class="legend"><?php echo $lC_Language->get('legend_featured_product_details'); ?></legend>
            <?php
              if (!isset($fInfo)) {
            ?>
            <div class="field-block button-height margin-bottom">
              <label for="products_id" class="label"><b><?php echo $lC_Language->get('label_product'); ?></b></label>
              <div>
                <select id="products_id" name="products_id" class="input with-small-padding">
                  <option value="">Select Product</option>
                  <option value="1">Product 1</option>
                  <option value="2">Product 2</option>
                  <option value="3">Product 3</option>
                  <option value="4">Product 4</option>
                </select>
                <?php echo lc_show_info_bubble($lC_Language->get('info_bubble_product'), null, 'info-spot on-left grey margin-left'); ?>
              </div>
            </div>
            <?php 
              } else {
                echo lc_draw_hidden_field('products_id', $fInfo->getInt('products_id'));
              }
            ?>
            <div class="field-block button-height margin-bottom">
              <label for="status" class="label"><b><?php echo $lC_Language->get('label_status'); ?></b></label>
              <div>
                <input type="checkbox" name="status" id="status" class="switch wider" data-text-off="<?php echo $lC_Language->get('slider_switch_disabled'); ?>" data-text-on="<?php echo $lC_Language->get('slider_switch_enabled'); ?>"<?php echo ((isset($fInfo) && $fInfo->getInt('status') != 1) ? null : ' checked'); ?> />
                <?php echo lc_show_info_bubble($lC_Language->get('info_bubble_status'), null, 'info-spot on-left grey margin-left'); ?>
              </div>
            </div>
            <div class="field-block button-height margin-bottom">
              <label for="expires_date" class="label"><b><?php echo $lC_Language->get('label_expires_date'); ?></b></label>
              <div>
                <span class="input">
                  <span class="icon-calendar"></span>
                  <input type="text" name="expires_date" id="expires_date" value="<?php echo (isset($fInfo) ? $fInfo->get('expires_date') : null); ?>" class="input-unstyled datepicker" style="max-width:147px;">
                </span>
                <?php echo lc_show_info_bubble($lC_Language->get('info_bubble_expires_date'), null, 'info-spot on-left grey margin-left'); ?>
              </div>
            </div>
          </fieldset>
        </div>
      </div>
      <?php echo lc_draw_hidden_field('subaction', 'confirm'); ?>
    </form>
    <div class="clear-both"></div>
    <div class="six-columns twelve-columns-tablet">
      <div id="buttons-menu-div-listing">
        <div id="buttons-container" style="position: relative;" class="clear-both">
          <div style="float:right;">
            <p class="button-height" align="right">
              <a class="button" href="<?php echo lc_href_link_admin(FILENAME_DEFAULT, $lC_Template->getModule()); ?>">
                <span class="button-icon red-gradient glossy">
                  <span class="icon-cross"></span>
                </span>
                <span><?php echo $lC_Language->get('button_cancel'); ?></span>
              </a>&nbsp;
              <a class="button<?php echo (((int)$_SESSION['admin']['access'][$lC_Template->getModule()] < 3) ? ' disabled' : NULL); ?>" href="<?php echo (((int)$_SESSION['admin']['access'][$lC_Template->getModule()] < 2) ? '#' : 'javascript://" onclick="$(\'#featured\').submit();'); ?>">
                <span class="button-icon green-gradient glossy">
                  <span class="icon-download"></span>
                </span>
                <span><?php echo $lC_Language->get('button_save'); ?></span> 
              </a>&nbsp;
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>