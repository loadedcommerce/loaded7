<?php
  /*
  $Id: edit.php v1.0 2011-11-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
  */

  $bInfo = new lC_ObjectInfo(lC_Branding_manager_Admin::get($lC_Template->getModule()));

  $slogan = array();
  $meta_description = array();
  $meta_keywords = array();
  $meta_title = array();
  $meta_title_prefix = array();
  $meta_title_suffix = array();
  $footer_text = array();

  $QbrandingName = $lC_Database->query('select configuration_value from :table_configuration where configuration_key = "STORE_NAME"');
  $QbrandingName->bindTable(':table_configuration', TABLE_CONFIGURATION);
  $QbrandingName->execute();

  $QbrandingAddress = $lC_Database->query('select configuration_value from :table_configuration where configuration_key = "STORE_NAME_ADDRESS"');
  $QbrandingAddress->bindTable(':table_configuration', TABLE_CONFIGURATION);
  $QbrandingAddress->execute();

  $QbrandingData = $lC_Database->query('select * from :table_branding_data');
  $QbrandingData->bindTable(':table_branding_data', TABLE_BRANDING_DATA);
  $QbrandingData->execute();

  $site_image = $QbrandingData->value('site_image');
  $og_image = $QbrandingData->value('og_image');
  $chat_code = $QbrandingData->value('chat_code');
  $support_phone = $QbrandingData->value('support_phone');
  $support_email = $QbrandingData->value('support_email');
  $sales_phone = $QbrandingData->value('sales_phone');
  $sales_email = $QbrandingData->value('sales_email');
  $meta_delimeter = $QbrandingData->value('meta_delimeter');
  $social_facebook_page = $QbrandingData->value('social_facebook_page');
  $social_twitter = $QbrandingData->value('social_twitter');
  $social_pinterest = $QbrandingData->value('social_pinterest');
  $social_google_plus = $QbrandingData->value('social_google_plus');
  $social_youtube = $QbrandingData->value('social_youtube');
  $social_linkedin = $QbrandingData->value('social_linkedin');



  $Qbranding = $lC_Database->query('select * from :table_branding');
  $Qbranding->bindTable(':table_branding', TABLE_BRANDING);
  $Qbranding->execute();

  if($Qbranding->numberOfRows() > 0 ){

    while ($Qbranding->next()) {
      $slogan[$Qbranding->valueInt('language_id')] = $Qbranding->value('slogan');
      $meta_description[$Qbranding->valueInt('language_id')] = $Qbranding->value('meta_description');
      $meta_keywords[$Qbranding->valueInt('language_id')] = $Qbranding->value('meta_keywords');
      $meta_title[$Qbranding->valueInt('language_id')] = $Qbranding->value('meta_title');
      $meta_title_prefix[$Qbranding->valueInt('language_id')] = $Qbranding->value('meta_title_prefix');
      $meta_title_suffix[$Qbranding->valueInt('language_id')] = $Qbranding->value('meta_title_suffix');
      $footer_text[$Qbranding->valueInt('language_id')] = $Qbranding->value('footer_text');
    }
  }

?>
<style>
  .inline-label > .label {
    width: 200px;
  }
</style>
<section role="main" id="main">
  <noscript class="message black-gradient simpler">
    <?php echo $lC_Language->get('ms_error_javascript_not_enabled_warning'); ?>
  </noscript>
  <hgroup id="main-title" class="thin">
    <h1><?php echo $lC_Template->getPageTitle(); ?></h1>
  </hgroup>
  <div class="with-padding">
    <form name="branding_manager" id="branding_manager" action="<?php echo lc_href_link_admin(FILENAME_DEFAULT, $lC_Template->getModule() . '=' . '&action=save'); ?>" method="post">

    <!-- Wrapper, set tabs style class here -->
    <div class="side-tabs"> 

      <!-- Tabs -->
      <ul class="tabs">
        <li class="active"><a href="#tab-1"><?php echo $lC_Language->get('tab_header'); ?></a></li>
        <li><a href="#tab-2"><?php echo $lC_Language->get('tab_company_info'); ?></a></li>
        <li><a href="#tab-3"><?php echo $lC_Language->get('tab_seo'); ?></a></li>
        <li><a href="#tab-4"><?php echo $lC_Language->get('tab_social_links'); ?></a></li>
        <li><a href="#tab-5"><?php echo $lC_Language->get('tab_footer_text'); ?></a></li>
      </ul>

      <!-- Content -->
      <div class="tabs-content">
        <div id="tab-1" class="with-padding">
          <div class="columns">
            <div class="four-columns">
              <p class="button-height inline-label">
              <label class="label" for="branding_image"><?php echo $lC_Language->get('field_branding_manager_logo'); ?></label>
              <?php echo lc_show_info_bubble($lC_Language->get('info_bubble_branding_manager_logo'), null); ?> 
              <div class="small-margin-top">
                <div id="imagePreviewContainer" class="cat-image align-center">
                  <img src="<?php echo '../' . DIR_WS_IMAGES . (isset($bInfo) && isset($site_image) ? 'branding/' . $site_image : 'no-image.png');?>" style="max-width: 100%; height: auto;" align="center" />
                  <input type="hidden" id="branding_manager_logo" name="branding_manager_logo" value="<?php echo (isset($bInfo) && isset($site_image) ? $site_image : 'no-image.png');?>">
                </div>
              </div>  
              <p class="thin mid-margin-top" align="center"><?php echo $lC_Language->get('text_drag_drop_to_replace'); ?></p>
              <center>
                <div id="fileUploaderImageContainer" class="small-margin-top">
                  <noscript>
                    <p><?php echo $lC_Language->get('ms_error_javascript_not_enabled_for_upload'); ?></p>
                  </noscript>
                </div>
              </center>
              </p></div>
            <div class="eight-columns">
              <span class="button-height block-label">
                <label for="branding_name" class="label"> <small><?php echo lc_show_info_bubble($lC_Language->get('info_bubble_site_name'), 'margin-bottom:8px;'); ?></small> <?php echo $lC_Language->get('field_site_name') ; ?> </label>
                <input type="text" name="branding_name" id="branding_name" class="input full-width required" value="<?php echo $QbrandingName->value('configuration_value');?>">
              </span>

              <span class="button-height"><b><?php echo $lC_Language->get('field_site_slogan'); ?></b><small><?php echo lc_show_info_bubble($lC_Language->get('info_bubble_site_slogan'), 'margin-top:8px;'); ?></small></span>
              <span class="button-height block-label">
                <?php
                  foreach ( $lC_Language->getAll() as $l ) {
                  ?>
                  <span class="required input full-width mid-margin-bottom">
                    <label class="button silver-gradient glossy" for="branding_chat_code[<?php echo $l['id'];?>]"><?php echo  $lC_Language->showImage($l['code']);?></label>
                    <input type="text" value="<?php echo (isset($bInfo) && isset($slogan[$l['id']]) ? $slogan[$l['id']] : null);?>" class="input-unstyled ten-columns required" id="branding_slogan[<?php echo $l['id'];?>]" name="branding_slogan[<?php echo $l['id'];?>]">
                  </span> 
                  <?php
                  }
                ?>
              </span>
              <span class="button-height block-label">
                <label for="branding_chat_code" class="label"> <small><?php echo lc_show_info_bubble($lC_Language->get('info_bubble_live_chat_code'), null); ?></small> <?php echo $lC_Language->get('field_live_chat_code'); ?> </label>
                <span class="required input full-width autoexpanding mid-margin-bottom"> <?php echo lc_draw_textarea_field('branding_chat_code', (isset($bInfo) && isset($chat_code) ? $chat_code : null) , 48, 2, 'class="required input-unstyled full-width autoexpanding"');?> </span> 
              </span>
            </div>
          </div>
        </div>
        <div id="tab-2" class="with-padding"> 
          <p class="button-height block-label mid-margin-bottom">
            <label class="label" for="branding_address"><small><?php echo lc_show_info_bubble($lC_Language->get('info_bubble_address'), null); ?></small><?php echo $lC_Language->get('field_address'); ?></label>
            <?php echo lc_draw_textarea_field('branding_address',  $QbrandingAddress->value('configuration_value'), 48, 2, 'id="branding_address" class="input full-width required autoexpanding"');?>
          </p>
          <p class="button-height block-label mid-margin-bottom mid-margin-top">
            <label class="label" for="branding_support_phone"><small><?php echo lc_show_info_bubble($lC_Language->get('info_bubble_support_phone'), null); ?></small><?php echo $lC_Language->get('field_support_phone'); ?></label>
            <input type="text" value="<?php echo (isset($bInfo) && isset($support_phone) ? $support_phone : null);?>" class="input full-width required" id="branding_support_phone" name="branding_support_phone">
          </p>
          <p class="button-height block-label mid-margin-bottom mid-margin-top">
            <label class="label" for="branding_support_email"><small><?php echo lc_show_info_bubble($lC_Language->get('info_bubble_support_email'), null); ?></small><?php echo $lC_Language->get('field_support_email'); ?></label>
            <input type="text" value="<?php echo (isset($bInfo) && isset($support_email) ? $support_email : null);?>" class="input full-width required" id="branding_support_email" name="branding_support_email">
          </p>
          <p class="button-height block-label mid-margin-bottom">
            <label class="label" for="branding_sales_phone"><small><?php echo lc_show_info_bubble($lC_Language->get('info_bubble_sales_phone'), null); ?></small><?php echo $lC_Language->get('field_sales_phone'); ?></label>
            <input type="text" value="<?php echo (isset($bInfo) && isset($sales_phone) ? $sales_phone : null);?>" class="input full-width required" id="branding_sales_phone" name="branding_sales_phone">
          </p>
          <p class="button-height block-label mid-margin-bottom">
            <label class="label" for="branding_sales_email"><small><?php echo lc_show_info_bubble($lC_Language->get('info_bubble_sales_email1'), null); ?></small><?php echo $lC_Language->get('field_sales_email'); ?></label>
            <input type="text" value="<?php echo (isset($bInfo) && isset($sales_email) ? $sales_email : null);?>" class="input full-width required" id="branding_sales_email" name="branding_sales_email">
          </p>


        </div>
        <div id="tab-3" class="with-padding">
          <div class="columns">
            <div class="four-columns">                <p class="button-height inline-label">
              <label class="label" for="branding_image"><?php echo $lC_Language->get('field_open_graph_site_thumbnail'); ?></label>
              <?php echo lc_show_info_bubble($lC_Language->get('info_bubble_open_graph_site_thumbnail'), null); ?> 
              <div class="small-margin-top">
                <div id="ogimagePreviewContainer" class="cat-image align-center">
                  <img src="<?php echo '../' . DIR_WS_IMAGES . (isset($bInfo) && isset($og_image) ? 'branding/' . $og_image : 'no-image.png');?>" style="max-width: 100%; height: auto;" align="center" />
                  <input type="hidden" id="branding_graph_site_thumbnail" name="branding_graph_site_thumbnail" value="<?php echo (isset($bInfo) && isset($og_image) ? $og_image : 'no-image.png');?>">
                </div>
              </div>  
              <p class="thin mid-margin-top" align="center"><?php echo $lC_Language->get('text_drag_drop_to_replace'); ?></p>
              <center>
                <div id="ogfileUploaderImageContainer" class="small-margin-top">
                  <noscript>
                    <p><?php echo $lC_Language->get('ms_error_javascript_not_enabled_for_upload'); ?></p>
                  </noscript>
                </div>
              </center>
              </p></div>
            <div class="eight-columns">
              <span><b><?php echo $lC_Language->get('field_site_meta_description'); ?></b><?php echo lc_show_info_bubble($lC_Language->get('info_bubble_site_meta_description'), null); ?></span>
              <?php
                foreach ( $lC_Language->getAll() as $l ) {
                ?>
                <span class="required input full-width mid-margin-bottom">
                  <label for="branding_meta_description[' . $l['id'] . ']" class="button silver-gradient glossy"> <?php echo  $lC_Language->showImage($l['code']);?> </label>
                <?php echo lc_draw_textarea_field('branding_meta_description[' . $l['id'] . ']', (isset($bInfo) && isset($meta_description[$l['id']]) ? $meta_description[$l['id']] : null), 48, 2, 'id="branding_meta_description[' . $l['id'] . ']" class="input-unstyled full-width required autoexpanding" ');?> </span>
                <?php
                }
              ?>
              <span class="button-height block-label"> <small class="float-right"><?php echo lc_show_info_bubble($lC_Language->get('info_bubble_site_meta_keywords'), null); ?></small> <b><?php echo $lC_Language->get('field_site_meta_keywords'); ?></b>
                <?php
                  foreach ( $lC_Language->getAll() as $l ) {
                  ?>
                  <span class="required input full-width mid-margin-bottom">
                    <label class="button silver-gradient glossy" for="branding_meta_keywords[<?php echo $l['id'];?>]"><?php echo  $lC_Language->showImage($l['code']);?></label>
                    <input type="text" value="<?php echo (isset($bInfo) && isset($meta_keywords[$l['id']]) ? $meta_keywords[$l['id']] : null);?>" class="input-unstyled ten-columns required" id="branding_meta_keywords[<?php echo $l['id'];?>]" name="branding_meta_keywords[<?php echo $l['id'];?>]">
                  </span>
                  <?php
                  }
                ?>
              </span> 
              <span class="button-height block-label"> <small class="float-right"><?php echo lc_show_info_bubble($lC_Language->get('info_bubble_home_page_meta_title'), null); ?></small> <b><?php echo $lC_Language->get('field_home_page_meta_title'); ?></b>
                <?php
                  foreach ( $lC_Language->getAll() as $l ) {
                  ?>
                  <span class="required input full-width mid-margin-bottom">
                    <label class="button silver-gradient glossy" for="branding_meta_title[<?php echo $l['id'];?>]"><?php echo  $lC_Language->showImage($l['code']);?></label>
                    <input type="text" value="<?php echo (isset($bInfo) && isset($meta_title[$l['id']]) ? $meta_title[$l['id']] : null);?>" class="input-unstyled ten-columns required" id="branding_meta_title[<?php echo $l['id'];?>]" name="branding_meta_title[<?php echo $l['id'];?>]">
                  </span>
                  <?php
                  }
                ?>
              </span> <span class="button-height block-label"> <small class="float-right"><?php echo lc_show_info_bubble($lC_Language->get('info_bubble_site_meta_title_prefix'), null); ?></small> <b><?php echo $lC_Language->get('field_site_meta_title_prefix'); ?></b>
                <?php
                  foreach ( $lC_Language->getAll() as $l ) {
                  ?>
                  <span class="required input full-width mid-margin-bottom">
                    <label class="button silver-gradient glossy" for="branding_meta_title_prefix[<?php echo $l['id'];?>]"><?php echo  $lC_Language->showImage($l['code']);?></label>
                    <input type="text" value="<?php echo (isset($bInfo) && isset($meta_title_prefix[$l['id']]) ? $meta_title_prefix[$l['id']] : null);?>" class="input-unstyled ten-columns required" id="branding_meta_title_prefix[<?php echo $l['id'];?>]" name="branding_meta_title_prefix[<?php echo $l['id'];?>]">
                  </span>
                  <?php
                  }
                ?>
              </span> <span class="button-height block-label"> <small class="float-right"><?php echo lc_show_info_bubble($lC_Language->get('info_bubble_site_meta_title_suffix'), null); ?></small> <b><?php echo $lC_Language->get('field_site_meta_title_suffix'); ?></b>
                <?php
                  foreach ( $lC_Language->getAll() as $l ) {
                  ?>
                  <span class="required input full-width mid-margin-bottom">
                    <label class="button silver-gradient glossy" for="branding_meta_title_suffix[<?php echo $l['id'];?>]"><?php echo  $lC_Language->showImage($l['code']);?></label>
                    <input type="text" value="<?php echo (isset($bInfo) && isset($meta_title_suffix[$l['id']]) ? $meta_title_suffix[$l['id']] : null);?>" class="input-unstyled ten-columns required" id="branding_meta_title_suffix[<?php echo $l['id'];?>]" name="branding_meta_title_suffix[<?php echo $l['id'];?>]">
                  </span>
                  <?php
                  }
                ?>
              </span>
              <p class="button-height block-label">
                <label for="branding_meta_title_delimeter" class="label"> <small><?php echo lc_show_info_bubble($lC_Language->get('info_bubble_site_meta_title_delimeter'), null); ?></small> <?php echo $lC_Language->get('field_site_meta_title_delimeter'); ?> </label>
                <input type="text" name="branding_meta_title_delimeter" id="branding_meta_title_delimeter" class="input full-width required" value="<?php echo (isset($bInfo) && isset($meta_delimeter) ? $meta_delimeter : null);?>">
              </p>
            </div>
          </div>
        </div>
        <div id="tab-4" class="with-padding">
          <p class="button-height block-label">
            <label for="branding_social_fb_page" class="label"> <small><?php echo lc_show_info_bubble($lC_Language->get('info_bubble_facebook_page'), null); ?></small> <?php echo $lC_Language->get('field_facebook_page') ; ?> </label>
            <input type="text" name="branding_social_fb_page" id="branding_social_fb_page" class="input full-width required" value="<?php echo (isset($bInfo) && isset($social_facebook_page) ? $social_facebook_page : null);?>">
          </p>
          <p class="button-height block-label">
            <label for="branding_social_twitter" class="label"> <small><?php echo lc_show_info_bubble($lC_Language->get('info_bubble_twitter'), null); ?></small> <?php echo $lC_Language->get('field_twitter') ; ?> </label>
            <input type="text" name="branding_social_twitter" id="branding_social_twitter" class="input full-width required" value="<?php echo (isset($bInfo) && isset($social_twitter) ? $social_twitter : null);?>">
          </p>
          <p class="button-height block-label">
            <label for="branding_social_pinterest" class="label"> <small><?php echo lc_show_info_bubble($lC_Language->get('info_bubble_pinterest'), null); ?></small> <?php echo $lC_Language->get('field_pinterest') ; ?> </label>
            <input type="text" name="branding_social_pinterest" id="branding_social_pinterest" class="input full-width required" value="<?php echo (isset($bInfo) && isset($social_pinterest) ? $social_pinterest : null);?>">
          </p>
          <p class="button-height block-label">
            <label for="branding_social_google_plus" class="label"> <small><?php echo lc_show_info_bubble($lC_Language->get('info_bubble_google_plus'), null); ?></small> <?php echo $lC_Language->get('field_google_plus') ; ?> </label>
            <input type="text" name="branding_social_google_plus" id="branding_social_google_plus" class="input full-width required" value="<?php echo (isset($bInfo) && isset($social_google_plus) ? $social_google_plus : null);?>">
          </p>
          <p class="button-height block-label">
            <label for="branding_social_youtube" class="label"> <small><?php echo lc_show_info_bubble($lC_Language->get('info_bubble_youtube'), null); ?></small> <?php echo $lC_Language->get('field_youtube') ; ?> </label>
            <input type="text" name="branding_social_youtube" id="branding_social_youtube" class="input full-width required" value="<?php echo (isset($bInfo) && isset($social_youtube) ? $social_youtube : null);?>">
          </p>
          <p class="button-height block-label">
            <label for="branding_social_linkedin" class="label"> <small><?php echo lc_show_info_bubble($lC_Language->get('info_bubble_linkedin'), null); ?></small> <?php echo $lC_Language->get('field_linkedin') ; ?> </label>
            <input type="text" name="branding_social_linkedin" id="branding_social_linkedin" class="input full-width required" value="<?php echo (isset($bInfo) && isset($social_linkedin) ? $social_linkedin : null);?>">
          </p>
        </div>
        <div id="tab-5" class="with-padding">
          <p><b><?php echo $lC_Language->get('field_site_footer_text'); ?></b><?php echo lc_show_info_bubble($lC_Language->get('info_bubble_footer'), null); ?></p>
          <?php
            foreach ( $lC_Language->getAll() as $l ) {
            ?>
            <span class="required input full-width autoexpanding mid-margin-bottom">
              <label for="branding_footer_text[<?php echo $l['id'];?>]" class="button silver-gradient glossy"> <?php echo  $lC_Language->showImage($l['code']);?> </label>
            <?php echo lc_draw_textarea_field('branding_footer_text[' . $l['id'] . ']', (isset($bInfo) && isset($footer_text[$l['id']]) ? $footer_text[$l['id']] : null), 48, 2, 'id="branding_footer_text[' . $l['id'] . ']" class="required input-unstyled full-width autoexpanding"');?> </span>
            <?php
            }
          ?>
        </div>
      </div>
    </div>
  </div>
  </form>
  <div class="clear-both"></div>
  <div id="floating-button-container" class="six-columns twelve-columns-tablet margin-bottom">
    <div id="floating-menu-div-listing">
      <div id="buttons-container" style="position: relative;" class="clear-both">
        <div style="float:right;">
          <p class="button-height" align="right"> <a class="button" href="<?php echo lc_href_link_admin(FILENAME_DEFAULT, $lC_Template->getModule()); ?>"> <span class="button-icon blue-gradient"> <span class="icon-undo"></span> </span><span class="button-text"><?php echo $lC_Language->get('button_reset'); ?></span> </a>&nbsp; <a class="button<?php echo (((int)$_SESSION['admin']['access'][$lC_Template->getModule()] < 3) ? ' disabled' : NULL); ?>" href="<?php echo (((int)$_SESSION['admin']['access'][$lC_Template->getModule()] < 2) ? '#' : 'javascript://" onclick="validateForm(\'#branding_manager\');'); ?>"> <span class="button-icon green-gradient glossy"> <span class="icon-download"></span> </span><span class="button-text"><?php echo $lC_Language->get('button_save'); ?></span> </a>&nbsp; </p>
        </div>
        <div id="floating-button-container-title" class="hidden">
          <p class="white big-text small-margin-top"><?php echo $lC_Template->getPageTitle(); ?></p>
        </div>
      </div>
    </div>
  </div>
  </div>
</section>