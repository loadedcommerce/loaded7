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
    //$lC_ObjectInfo = new lC_ObjectInfo(lC_Coupons_Admin::getData($_GET[$lC_Template->getModule()]));
    $lC_ObjectInfo = '';
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
    <form name="coupon" id="coupon" class="dataForm" action="<?php echo lc_href_link_admin(FILENAME_DEFAULT, $lC_Template->getModule() . '=' . (isset($lC_ObjectInfo) ? $lC_ObjectInfo->getInt('coupons_id') : '') . '&cid=' . $_GET['cid'] . '&action=save'); ?>" method="post" enctype="multipart/form-data">
      <div id="coupon_tabs" class="side-tabs">
        <ul class="tabs">
          <li class="active"><?php echo lc_link_object('#section_general_content', $lC_Language->get('section_general')); ?></li>
          <li><?php echo lc_link_object('#section_details', $lC_Language->get('section_details')); ?></li>
          <li><?php echo lc_link_object('#section_limits', $lC_Language->get('section_limits')); ?></li>
          <li><?php echo lc_link_object('#section_restrictions', $lC_Language->get('section_restrictions')); ?></li>
        </ul>
        <div class="clearfix tabs-content">
          <div id="section_general_content">
            <div class="columns with-padding">
              <div class="new-row-mobile twelve-columns twelve-columns-mobile">
                <div id="languageTabs" class="standard-tabs">
                  <ul class="tabs">
                  <?php
                    foreach ( $lC_Language->getAll() as $l ) {
                      echo '<li>' . lc_link_object('#languageTabs_' . $l['code'], $lC_Language->showImage($l['code']) . '&nbsp;' . $l['name']) . '</li>';
                    }
                  ?>
                  </ul>
                  <div class="clearfix tabs-content">
                  <?php
                    foreach ( $lC_Language->getAll() as $l ) {
                    ?>
                    <div id="languageTabs_<?php echo $l['code']; ?>" class="with-padding mid-margin-bottom">
                      <p class="button-height block-label">
                        <label class="label" for="<?php echo 'coupons_name[' . $l['id'] . ']'; ?>">
                          <?php echo $lC_Language->get('field_name'); ?>
                          <?php echo lc_show_info_bubble($lC_Language->get('info_bubble_coupons_name'), null); ?>
                        </label>
                        <?php echo lc_draw_input_field('coupons_name[' . $l['id'] . ']', (isset($lC_ObjectInfo) && isset($coupons_name[$l['id']]) ? $coupons_name[$l['id']] : null), 'class="required input full-width mid-margin-top"'); ?>
                      </p>
                      <p class="button-height block-label">
                        <label class="label" for="<?php echo 'coupons_description[' . $l['id'] . ']'; ?>">
                          <?php echo $lC_Language->get('field_description'); ?>
                          <?php echo lc_show_info_bubble($lC_Language->get('info_bubble_coupons_description'), null); ?>
                        </label>
                        <div style="margin-bottom:-6px;"></div>
                        <?php echo lc_draw_textarea_field('coupons_description[' . $l['id'] . ']', (isset($lC_ObjectInfo) && isset($coupons_description[$l['id']]) ? $coupons_description[$l['id']] : null), null, 10, 'id="ckEditorCouponsDescription_' . $l['id'] . '" class="input full-width autoexpanding"'); ?>
                        <span class="float-right small-margin-top small-margin-right"><?php echo '<a href="javascript:toggleEditor(\'' . $l['id'] . '\');">' . $lC_Language->get('text_toggle_html_editor') . '</a>'; ?></span>
                      </p>
                    </div>
                    <div class="clear-both"></div>
                    <?php
                    }
                  ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div id="section_details">
            <div class="columns with-padding">
              <div class="new-row-mobile twelve-columns twelve-columns-mobile">
                Coupon Details
              </div>
            </div>
          </div>
          <div id="section_limits">
            <div class="columns with-padding">
              <div class="new-row-mobile twelve-columns twelve-columns-mobile">
                Use Limits
              </div>
            </div>
          </div>
          <div id="section_restrictions">
            <div class="columns with-padding">
              <div class="new-row-mobile twelve-columns twelve-columns-mobile">
                Restrictions
              </div>
            </div>
          </div>    
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
              <a class="button" href="<?php echo lc_href_link_admin(FILENAME_DEFAULT, $lC_Template->getModule() . ($_GET['cid'] != '') ? 'coupons=' . $_GET['cid'] : ''); ?>">
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
            <p class="white big-text small-margin-top"><?php echo (isset($lC_ObjectInfo) ? $lC_ObjectInfo->get('coupons_name') : 'New Coupon'); ?></p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>