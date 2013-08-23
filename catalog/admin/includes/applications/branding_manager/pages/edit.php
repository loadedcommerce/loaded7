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
if ( is_numeric($_GET[$lC_Template->getModule()]) ) {
  $bInfo = new lC_ObjectInfo(lC_Branding_manager_Admin::get($lC_Template->getModule()));

      $site_image = array();
      $name = array();
      $slogan = array();
      $chat_code = array();
      $address = array();
      $support_phone = array();
      $support_email = array();
      $sales_phone = array();
      $sales_email = array();
      $meta_description = array();
      $meta_keywords = array();
      $og_image = array();
      $meta_title = array();
      $meta_title_prefix = array();
      $meta_title_suffix = array();
      $meta_delimeter = array();
      $social_facebook_page = array();
      $social_twitter = array();
      $social_pinterest = array();
      $social_google_plus = array();
      $social_youtube = array();
      $social_linkedin = array();
      $footer_text = array();

      $Qbranding = $lC_Database->query('select * from :table_branding');
      $Qbranding->bindTable(':table_branding', TABLE_BRANDING);
      $Qbranding->execute();      

    while ($Qbranding->next()) {
      $site_image[$Qbranding->valueInt('language_id')] = $Qbranding->value('site_image');
      $name[$Qbranding->valueInt('language_id')] = $Qbranding->value('name');
      $slogan[$Qbranding->valueInt('language_id')] = $Qbranding->value('slogan');
      $chat_code[$Qbranding->valueInt('language_id')] = $Qbranding->value('chat_code');
      $address[$Qbranding->valueInt('language_id')] = $Qbranding->value('address');
      $support_phone[$Qbranding->valueInt('language_id')] = $Qbranding->value('support_phone');
      $support_email[$Qbranding->valueInt('language_id')] = $Qbranding->value('support_email');
      $sales_phone[$Qbranding->valueInt('language_id')] = $Qbranding->value('sales_phone');
      $sales_email[$Qbranding->valueInt('language_id')] = $Qbranding->value('sales_email');
      $meta_description[$Qbranding->valueInt('language_id')] = $Qbranding->value('meta_description');
      $meta_keywords[$Qbranding->valueInt('language_id')] = $Qbranding->value('meta_keywords');
      $og_image[$Qbranding->valueInt('language_id')] = $Qbranding->value('og_image');
      $meta_title[$Qbranding->valueInt('language_id')] = $Qbranding->value('meta_title');
      $meta_title_prefix[$Qbranding->valueInt('language_id')] = $Qbranding->value('meta_title_prefix');
      $meta_title_suffix[$Qbranding->valueInt('language_id')] = $Qbranding->value('meta_title_suffix');
      $meta_delimeter[$Qbranding->valueInt('language_id')] = $Qbranding->value('meta_delimeter');
      $social_facebook_page[$Qbranding->valueInt('language_id')] = $Qbranding->value('social_facebook_page');
      $social_twitter[$Qbranding->valueInt('language_id')] = $Qbranding->value('social_twitter');
      $social_pinterest[$Qbranding->valueInt('language_id')] = $Qbranding->value('social_pinerest');
      $social_google_plus[$Qbranding->valueInt('language_id')] = $Qbranding->value('social_google_plus');
      $social_youtube[$Qbranding->valueInt('language_id')] = $Qbranding->value('social_youtube');
      $social_linkedin[$Qbranding->valueInt('language_id')] = $Qbranding->value('social_linkedin');
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
  <noscript class="message black-gradient simpler"><?php echo $lC_Language->get('ms_error_javascript_not_enabled_warning'); ?></noscript>
  <hgroup id="main-title" class="thin">
    <h1><?php echo $lC_Template->getPageTitle(); ?></h1>
  </hgroup>
  <div class="with-padding">
    <form name="branding_manager" id="branding_manager" action="<?php echo lc_href_link_admin(FILENAME_DEFAULT, $lC_Template->getModule() . '=' . '&action=save'); ?>" method="post">
      <div id="branding_manager_tabs" class="side-tabs">
        <ul class="tabs">
        <?php
          foreach ( $lC_Language->getAll() as $l ) {
            echo '<li>' . lc_link_object('#languageTabs_' . $l['code'], $lC_Language->showImage($l['code']) . '&nbsp;' . $l['name']) . '</li>';
          }
        ?>
        </ul>
        <div class="clearfix tabs-content">
          <div id="section_branding_content">
          <?php
            foreach ( $lC_Language->getAll() as $l ) {
            ?>
            <div id="languageTabs_<?php echo $l['code']; ?>" class="with-padding">
              <fieldset class="fieldset">
                <legend class="legend"><?php echo $lC_Language->get('field_header'); ?></legend>
                <p class="button-height inline-label">
                  <label class="label" for="branding_image[<?php echo $l['id'];?>]"><?php echo $lC_Language->get('field_branding_manager_logo'); ?></label>
                  <?php echo lc_show_info_bubble($lC_Language->get('info_bubble_branding_manager_logo'), null); ?> 
                  <div style="padding-left:6px;" class="small-margin-top">
                  <div id="imagePreviewContainer" class="cat-image align-center">
                    <img src="<?php echo '../' . DIR_WS_IMAGES . (isset($bInfo) && isset($site_image[$l['id']]) ? 'branding/' . $site_image[$l['id']] : 'no-image.png');?>" style="max-width: 100%; height: auto;" align="center" />
                    <input type="hidden" id="branding_manager_logo[<?php echo $l['id'];?>]" name="branding_manager_logo[<?php echo $l['id'];?>]" value="<?php echo (isset($bInfo) && isset($site_image[$l['id']]) ? $site_image[$l['id']] : 'no-image.png');?>">
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
                </p>
                <p class="button-height inline-label">
                  <label class="label" for="branding_name[<?php echo $l['id'];?>]"><?php echo $lC_Language->get('field_site_name') ; ?></label>
                  <input type="text" value="<?php echo (isset($bInfo) && isset($name[$l['id']]) ? $name[$l['id']] : null);?>" class="input two-thirds-width" id="branding_name[<?php echo $l['id'];?>]" name="branding_name[<?php echo $l['id'];?>]">
                </p>
                <p class="button-height inline-label">
                  <label class="label" for="branding_slogan[<?php echo $l['id'];?>]"><?php echo $lC_Language->get('field_site_slogan'); ?></label>
                  <input type="text" value="<?php echo (isset($bInfo) && isset($slogan[$l['id']]) ? $slogan[$l['id']] : null);?>" class="input two-thirds-width" id="branding_slogan[<?php echo $l['id'];?>]" name="branding_slogan[<?php echo $l['id'];?>]">
                </p>
                <p class="button-height inline-label">
                  <label class="label" for="branding_chat_code[<?php echo $l['id'];?>]"><?php echo $lC_Language->get('field_live_chat_code'); ?></label>
                  <?php echo lc_draw_textarea_field('branding_chat_code[' . $l['id'] . ']', (isset($bInfo) && isset($chat_code[$l['id']]) ? $chat_code[$l['id']] : null) , 48, 2, 'id="editHtmlText" class="input two-thirds-width autoexpanding"');?>
                </p>
              </fieldset>
              <fieldset class="fieldset">
                <legend class="legend"><?php echo $lC_Language->get('field_company_info'); ?></legend>
                <p class="button-height inline-label">
                  <label class="label" for="branding_address[<?php echo $l['id'];?>]"><?php echo $lC_Language->get('field_address'); ?></label>
                  <?php echo lc_draw_textarea_field('branding_address[' . $l['id'] . ']', (isset($bInfo) && isset($address[$l['id']]) ? $address[$l['id']] : null), 48, 2, 'id="editHtmlText" class="input two-thirds-width autoexpanding"');?>
                </p>
                <p class="button-height inline-label">
                  <label class="label" for="branding_support_phone[<?php echo $l['id'];?>]"><?php echo $lC_Language->get('field_support_phone'); ?></label>
                  <input type="text" value="<?php echo (isset($bInfo) && isset($support_phone[$l['id']]) ? $support_phone[$l['id']] : null);?>" class="input two-thirds-width" id="branding_support_phone[<?php echo $l['id'];?>]" name="branding_support_phone[<?php echo $l['id'];?>]">
                </p>
                <p class="button-height inline-label">
                  <label class="label" for="branding_support_email[<?php echo $l['id'];?>]"><?php echo $lC_Language->get('field_support_email'); ?></label>
                  <input type="text" value="<?php echo (isset($bInfo) && isset($support_email[$l['id']]) ? $support_email[$l['id']] : null);?>" class="input two-thirds-width" id="branding_support_email[<?php echo $l['id'];?>]" name="branding_support_email[<?php echo $l['id'];?>]">
                </p>
                <p class="button-height inline-label">
                  <label class="label" for="branding_sales_phone[<?php echo $l['id'];?>]"><?php echo $lC_Language->get('field_sales_phone'); ?></label>
                  <input type="text" value="<?php echo (isset($bInfo) && isset($sales_phone[$l['id']]) ? $sales_phone[$l['id']] : null);?>" class="input two-thirds-width" id="branding_sales_phone[<?php echo $l['id'];?>]" name="branding_sales_phone[<?php echo $l['id'];?>]">
                </p>
                <p class="button-height inline-label">
                  <label class="label" for="branding_sales_email[<?php echo $l['id'];?>]"><?php echo $lC_Language->get('field_sales_email'); ?></label>
                  <input type="text" value="<?php echo (isset($bInfo) && isset($sales_email[$l['id']]) ? $sales_email[$l['id']] : null);?>" class="input two-thirds-width" id="branding_sales_email[<?php echo $l['id'];?>]" name="branding_sales_email[<?php echo $l['id'];?>]">
                </p>
              </fieldset>
              <fieldset class="fieldset">
                <legend class="legend"><?php echo $lC_Language->get('field_seo'); ?></legend>
                <p class="button-height inline-label">
                  <label class="label" for="branding_meta_description[<?php echo $l['id'];?>]"><?php echo $lC_Language->get('field_site_meta_description'); ?></label>
                  <?php echo lc_draw_textarea_field('branding_meta_description[' . $l['id'] . ']', (isset($bInfo) && isset($meta_description[$l['id']]) ? $meta_description[$l['id']] : null), 48, 2, 'id="editHtmlText" class="input two-thirds-width autoexpanding" ');?>
                </p>
                <p class="button-height inline-label">
                  <label class="label" for="branding_meta_keywords[<?php echo $l['id'];?>]"><?php echo $lC_Language->get('field_site_meta_keywords'); ?></label>
                  <input type="text" value="<?php echo (!lc_empty($bInfo->get('meta_keywords')) ? $bInfo->getProtected('meta_keywords') : null);?>" class="input two-thirds-width" id="branding_meta_keywords[<?php echo $l['id'];?>]" name="branding_meta_keywords[<?php echo $l['id'];?>]">
                </p>
                <p class="button-height inline-label">
                  <label class="label" for="branding_image[<?php echo $l['id'];?>]"><?php echo $lC_Language->get('field_open_graph_site_thumbnail'); ?></label>
                  <?php echo lc_show_info_bubble($lC_Language->get('info_bubble_open_graph_site_thumbnail'), null); ?> 
                  <div style="padding-left:6px;" class="small-margin-top">
                  <div id="ogimagePreviewContainer" class="cat-image align-center">
                    <img src="<?php echo '../' . DIR_WS_IMAGES . (isset($bInfo) && isset($og_image[$l['id']]) ? 'branding/' . $og_image[$l['id']] : 'no-image.png');?>" style="max-width: 100%; height: auto;" align="center" />
                    <input type="hidden" id="branding_graph_site_thumbnail[<?php echo $l['id'];?>]" name="branding_graph_site_thumbnail[<?php echo $l['id'];?>]" value="<?php echo (isset($bInfo) && isset($og_image[$l['id']]) ? $og_image[$l['id']] : 'no-image.png');?>">
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
                </p>
                <p class="button-height inline-label">
                  <label class="label" for="branding_meta_title[<?php echo $l['id'];?>]"><?php echo $lC_Language->get('field_home_page_meta_title'); ?></label>
                  <input type="text" value="<?php echo (isset($bInfo) && isset($meta_title[$l['id']]) ? $meta_title[$l['id']] : null);?>" class="input two-thirds-width" id="branding_meta_title[<?php echo $l['id'];?>]" name="branding_meta_title[<?php echo $l['id'];?>]">
                </p>
                <p class="button-height inline-label">
                  <label class="label" for="branding_meta_title_prefix[<?php echo $l['id'];?>]"><?php echo $lC_Language->get('field_site_meta_title_prefix'); ?></label>
                  <input type="text" value="<?php echo (isset($bInfo) && isset($meta_title_prefix[$l['id']]) ? $meta_title_prefix[$l['id']] : null);?>" class="input two-thirds-width" id="branding_meta_title_prefix[<?php echo $l['id'];?>]" name="branding_meta_title_prefix[<?php echo $l['id'];?>]">
                </p>
                <p class="button-height inline-label">
                  <label class="label" for="branding_meta_title_suffix[<?php echo $l['id'];?>]"><?php echo $lC_Language->get('field_site_meta_title_suffix'); ?></label>
                  <input type="text" value="<?php echo (isset($bInfo) && isset($meta_title_suffix[$l['id']]) ? $meta_title_suffix[$l['id']] : null);?>" class="input two-thirds-width" id="branding_meta_title_suffix[<?php echo $l['id'];?>]" name="branding_meta_title_suffix[<?php echo $l['id'];?>]">
                </p>
                <p class="button-height inline-label">
                  <label class="label" for="branding_meta_title_delimeter[<?php echo $l['id'];?>]"><?php echo $lC_Language->get('field_site_meta_title_delimeter'); ?></label>
                  <input type="text" value="<?php echo (isset($bInfo) && isset($meta_delimeter[$l['id']]) ? $meta_delimeter[$l['id']] : null);?>" class="input" id="branding_meta_title_delimeter[<?php echo $l['id'];?>]" name="branding_meta_title_delimeter[<?php echo $l['id'];?>]">
                </p>
              </fieldset>
              <fieldset class="fieldset">
                <legend class="legend"><?php echo $lC_Language->get('field_social_links'); ?></legend>
                <p class="button-height inline-label">
                  <label class="label" for="branding_social_fb_page[<?php echo $l['id'];?>]"><?php echo $lC_Language->get('field_facebook_page'); ?></label>
                  <input type="text" value="<?php echo (isset($bInfo) && isset($social_facebook_page[$l['id']]) ? $social_facebook_page[$l['id']] : null);?>" class="input two-thirds-width" id="branding_social_fb_page[<?php echo $l['id'];?>]" name="branding_social_fb_page[<?php echo $l['id'];?>]">
                </p>
                <p class="button-height inline-label">
                  <label class="label" for="branding_social_twitter[<?php echo $l['id'];?>]"><?php echo $lC_Language->get('field_twitter'); ?></label>
                  <input type="text" value="<?php echo (isset($bInfo) && isset($social_twitter[$l['id']]) ? $social_twitter[$l['id']] : null);?>" class="input two-thirds-width" id="branding_social_twitter[<?php echo $l['id'];?>]" name="branding_social_twitter[<?php echo $l['id'];?>]">
                </p>
                <p class="button-height inline-label">
                  <label class="label" for="branding_social_pinterest[<?php echo $l['id'];?>]"><?php echo $lC_Language->get('field_pinterest'); ?></label>
                  <input type="text" value="<?php echo (isset($bInfo) && isset($social_pinterest[$l['id']]) ? $social_pinterest[$l['id']] : null);?>" class="input two-thirds-width" id="branding_social_pinterest[<?php echo $l['id'];?>]" name="branding_social_pinterest[<?php echo $l['id'];?>]">
                </p>
                <p class="button-height inline-label">
                  <label class="label" for="branding_social_google_plus[<?php echo $l['id'];?>]"><?php echo $lC_Language->get('field_google_plus'); ?></label>
                  <input type="text" value="<?php echo (isset($bInfo) && isset($social_google_plus[$l['id']]) ? $social_google_plus[$l['id']] : null);?>" class="input two-thirds-width" id="branding_social_google_plus[<?php echo $l['id'];?>]" name="branding_social_google_plus[<?php echo $l['id'];?>]">
                </p>
                <p class="button-height inline-label">
                  <label class="label" for="branding_social_youtube[<?php echo $l['id'];?>]"><?php echo $lC_Language->get('field_youtube'); ?></label>
                  <input type="text" value="<?php echo (isset($bInfo) && isset($social_youtube[$l['id']]) ? $social_youtube[$l['id']] : null);?>" class="input two-thirds-width" id="branding_social_youtube[<?php echo $l['id'];?>]" name="branding_social_youtube[<?php echo $l['id'];?>]">
                </p>
                <p class="button-height inline-label">
                  <label class="label" for="branding_social_linkedin[<?php echo $l['id'];?>]"><?php echo $lC_Language->get('field_linkedin'); ?></label>
                  <input type="text" value="<?php echo (isset($bInfo) && isset($social_linkedin[$l['id']]) ? $social_linkedin[$l['id']] : null);?>" class="input two-thirds-width" id="branding_social_linkedin[<?php echo $l['id'];?>]" name="branding_social_linkedin[<?php echo $l['id'];?>]">
                </p>
              </fieldset>
              <fieldset class="fieldset">
                <legend class="legend"><?php echo $lC_Language->get('field_footer_text'); ?></legend>
                <p class="button-height inline-label">
                  <label class="label" for="branding_footer_text[<?php echo $l['id'];?>]"><?php echo $lC_Language->get('field_site_footer_text'); ?></label>
                  <?php echo lc_draw_textarea_field('branding_footer_text[' . $l['id'] . ']', (isset($bInfo) && isset($footer_text[$l['id']]) ? $footer_text[$l['id']] : null), 48, 2, 'id="editHtmlText" class="input two-thirds-width autoexpanding" ');?>                 
                </p>
              </fieldset>
            </div>
            <?php
            }
          ?>
          </div>
        </div>
      </div>    
    </form>
    <div class="clear-both"></div>
    <div id="floating-button-container" class="six-columns twelve-columns-tablet margin-bottom">
      <div id="floating-menu-div-listing">
        <div id="buttons-container" style="position: relative;" class="clear-both">
          <div style="float:right;">
            <p class="button-height" align="right">
              <a class="button" href="<?php echo lc_href_link_admin(FILENAME_DEFAULT, $lC_Template->getModule()); ?>">
                <span class="button-icon blue-gradient">
                  <span class="icon-undo"></span>
                </span><span class="button-text"><?php echo $lC_Language->get('button_reset'); ?></span>
              </a>&nbsp;
              <a class="button<?php echo (((int)$_SESSION['admin']['access'][$lC_Template->getModule()] < 3) ? ' disabled' : NULL); ?>" href="<?php echo (((int)$_SESSION['admin']['access'][$lC_Template->getModule()] < 2) ? '#' : 'javascript://" onclick="validateForm(\'#branding_manager\');'); ?>">
                <span class="button-icon green-gradient glossy">
                  <span class="icon-download"></span>
                </span><span class="button-text"><?php echo $lC_Language->get('button_save'); ?></span>
              </a>&nbsp;
            </p>
          </div>
          <div id="floating-button-container-title" class="hidden">
            <p class="white big-text small-margin-top"><?php echo $lC_Template->getPageTitle(); ?></p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>