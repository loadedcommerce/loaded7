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

  if($Qbranding->numberOfRows() > 0 ){

    while ($Qbranding->next()) {
    $site_image[$Qbranding->valueInt('language_id')] = $Qbranding->value('site_image');
    $name[$Qbranding->valueInt('language_id')] = $Qbranding->value('name');
    $slogan[$Qbranding->valueInt('language_id')] = $Qbranding->value('slogan');
    $chat_code[$Qbranding->valueInt('language_id')] = $Qbranding->value('chat_code');
    $address = $Qbranding->value('address');
  $support_phone = $Qbranding->value('support_phone');
  $support_email = $Qbranding->value('support_email');
  $sales_phone = $Qbranding->value('sales_phone');
  $sales_email = $Qbranding->value('sales_email');
  $meta_description[$Qbranding->valueInt('language_id')] = $Qbranding->value('meta_description');
  $meta_keywords[$Qbranding->valueInt('language_id')] = $Qbranding->value('meta_keywords');
  $og_image[$Qbranding->valueInt('language_id')] = $Qbranding->value('og_image');
  $meta_title[$Qbranding->valueInt('language_id')] = $Qbranding->value('meta_title');
  $meta_title_prefix[$Qbranding->valueInt('language_id')] = $Qbranding->value('meta_title_prefix');
  $meta_title_suffix[$Qbranding->valueInt('language_id')] = $Qbranding->value('meta_title_suffix');
  $meta_delimeter = $Qbranding->value('meta_delimeter');
  $social_facebook_page = $Qbranding->value('social_facebook_page');
  $social_twitter = $Qbranding->value('social_twitter');
  $social_pinterest = $Qbranding->value('social_pinerest');
  $social_google_plus = $Qbranding->value('social_google_plus');
  $social_youtube = $Qbranding->value('social_youtube');
  $social_linkedin = $Qbranding->value('social_linkedin');
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
            <div class="four-columns">Image</div>
            <div class="eight-columns">
              <p class="button-height block-label">
                <label for="name" class="label"> <small><span class="info-spot"><span class="icon-info-round"></span><span class="info-bubble">Put the bubble text here site name</span></span></small> <?php echo $lC_Language->get('field_site_name') ; ?> </label>
                <input type="text" name="name" id="name" class="input full-width required" value="<?php echo (isset($bInfo) && isset($name[$l['id']]) ? $name[$l['id']] : null);?>">
              </p>

<span class="button-height"><b><?php echo $lC_Language->get('field_site_slogan'); ?></b><small><span class="info-spot float-right mid-margin-top"><span class="icon-info-round"></span><span class="info-bubble"><?php echo $lC_Language->get('infobubble_field_site_slogan'); ?></span></span></small></span>
<span class="button-height block-label">
              <?php
            foreach ( $lC_Language->getAll() as $l ) {
            ?>
              <span class="required input full-width mid-margin-bottom">
              <label class="button silver-gradient glossy" for="branding_chat_code[<?php echo $l['id'];?>]"><?php echo  $lC_Language->showImage($l['code']);?></label>
              <input type="text" value="<?php echo (isset($bInfo) && isset($slogan[$l['id']]) ? $slogan[$l['id']] : null);?>" class="input-unstyled ten-columns required mid-margin-bottom" id="branding_slogan[<?php echo $l['id'];?>]" name="branding_slogan[<?php echo $l['id'];?>]">
              </span> 
              <?php
        }
      ?>
              </span>
              <p class="button-height block-label">

                <label for="branding_chat_code" class="label"> <small><span class="info-spot"><span class="icon-info-round"></span><span class="info-bubble">Put the bubble text here Live Chat Code</span></span></small> <?php echo $lC_Language->get('field_live_chat_code'); ?> </label>
                <span class="required input full-width autoexpanding mid-margin-bottom"> <?php echo lc_draw_textarea_field('branding_chat_code', (isset($bInfo) && isset($chat_code[$l['id']]) ? $chat_code[$l['id']] : null) , 48, 2, 'class="required input-unstyled full-width autoexpanding"');?> </span> </p>
            </div>
          </div>
        </div>
        <div id="tab-2" class="with-padding"> 
                <span class="button-height block-label mid-margin-bottom">
                  <label class="label" for="branding_address"><small><span class="info-spot"><span class="icon-info-round"></span><span class="info-bubble"><?php echo $lC_Language->get('infobuble_field_address') ; ?></span></span></small><?php echo $lC_Language->get('field_address'); ?></label>
                  <?php echo lc_draw_textarea_field('branding_address', (isset($bInfo) && isset($address) ? $address : null), 48, 2, 'id="branding_address" class="input full-width required autoexpanding"');?>
                </span>
                <span class="button-height block-label mid-margin-bottom">
                  <label class="label" for="branding_support_phone"><small><span class="info-spot"><span class="icon-info-round"></span><span class="info-bubble"><?php echo $lC_Language->get('infobuble_field_support_phone') ; ?></span></span></small><?php echo $lC_Language->get('field_support_phone'); ?></label>
                  <input type="text" value="<?php echo (isset($bInfo) && isset($support_phone) ? $support_phone : null);?>" class="input full-width required" id="branding_support_phone" name="branding_support_phone">
                </span>
                <span class="button-height block-label mid-margin-bottom">
                  <label class="label" for="branding_support_email"><small><span class="info-spot"><span class="icon-info-round"></span><span class="info-bubble"><?php echo $lC_Language->get('infobuble_field_support_email') ; ?></span></span></small><?php echo $lC_Language->get('field_support_email'); ?></label>
                  <input type="text" value="<?php echo (isset($bInfo) && isset($support_email) ? $support_email : null);?>" class="input full-width required" id="branding_support_email" name="branding_support_email">
                </span>
                <span class="button-height block-label mid-margin-bottom">
                  <label class="label" for="branding_sales_phone"><small><span class="info-spot"><span class="icon-info-round"></span><span class="info-bubble"><?php echo $lC_Language->get('infobuble_field_sales_phone') ; ?></span></span></small><?php echo $lC_Language->get('field_sales_phone'); ?></label>
                  <input type="text" value="<?php echo (isset($bInfo) && isset($sales_phone) ? $sales_phone : null);?>" class="input full-width required" id="branding_sales_phone" name="branding_sales_phone">
                </span>
                <span class="button-height block-label mid-margin-bottom">
                  <label class="label" for="branding_sales_email"><small><span class="info-spot"><span class="icon-info-round"></span><span class="info-bubble"><?php echo $lC_Language->get('infobuble_field_sales_email1') ; ?></span></span></small><?php echo $lC_Language->get('field_sales_email'); ?></label>
                  <input type="text" value="<?php echo (isset($bInfo) && isset($sales_email) ? $sales_email : null);?>" class="input full-width required" id="branding_sales_email" name="branding_sales_email">
                </span>


 </div>
        <div id="tab-3" class="with-padding">
          <div class="columns">
            <div class="four-columns"><?php echo $lC_Language->get('field_open_graph_site_thumbnail'); ?></div>
            <div class="eight-columns">
              <p><b><?php echo $lC_Language->get('field_site_meta_description'); ?></b><span class="info-spot float-right mid-margin-top"><span class="icon-info-round"></span><span class="info-bubble"><?php echo $lC_Language->get('infobuble_field_site_meta_description') ; ?></span></span></p>
              <?php
            foreach ( $lC_Language->getAll() as $l ) {
     ?>
              <span class="required input full-width mid-margin-bottom">
              <label for="branding_meta_description[' . $l['id'] . ']" class="button silver-gradient glossy"> <?php echo  $lC_Language->showImage($l['code']);?> </label>
              <?php echo lc_draw_textarea_field('branding_meta_description[' . $l['id'] . ']', (isset($bInfo) && isset($meta_description[$l['id']]) ? $meta_description[$l['id']] : null), 48, 2, 'id="branding_meta_description[' . $l['id'] . ']" class="input-unstyled full-width required autoexpanding" ');?> </span>
              <?php
}
?>
              <span class="button-height block-label"> <small class="float-right"><span class="info-spot"><span class="icon-info-round"></span><span class="info-bubble"><?php echo $lC_Language->get('infobuble_field_site_meta_keywords'); ?></span></span></small> <?php echo $lC_Language->get('field_site_meta_keywords'); ?>
              <?php
            foreach ( $lC_Language->getAll() as $l ) {
     ?>
              <span class="required input full-width mid-margin-bottom">
              <label class="button silver-gradient glossy" for="branding_meta_keywords[<?php echo $l['id'];?>]"><?php echo  $lC_Language->showImage($l['code']);?></label>
              <input type="text" value="<?php echo (isset($bInfo) && isset($meta_keywords[$l['id']]) ? $meta_keywords[$l['id']] : null);?>" class="input-unstyled ten-columns required mid-margin-bottom" id="branding_meta_keywords[<?php echo $l['id'];?>]" name="branding_meta_keywords[<?php echo $l['id'];?>]">
              </span>
              <?php
}
?>
              </span> <span class="button-height block-label"> <small class="float-right"><span class="info-spot"><span class="icon-info-round"></span><span class="info-bubble"><?php echo $lC_Language->get('infobuble_field_home_page_meta_title'); ?></span></span></small> <?php echo $lC_Language->get('field_home_page_meta_title'); ?>
              <?php
            foreach ( $lC_Language->getAll() as $l ) {
     ?>
              <span class="required input full-width mid-margin-bottom">
              <label class="button silver-gradient glossy" for="branding_meta_title[<?php echo $l['id'];?>]"><?php echo  $lC_Language->showImage($l['code']);?></label>
              <input type="text" value="<?php echo (isset($bInfo) && isset($meta_title[$l['id']]) ? $meta_title[$l['id']] : null);?>" class="input-unstyled ten-columns required mid-margin-bottom" id="branding_meta_title[<?php echo $l['id'];?>]" name="branding_meta_title[<?php echo $l['id'];?>]">
              </span>
              <?php
}
?>
              </span> <span class="button-height block-label"> <small class="float-right"><span class="info-spot"><span class="icon-info-round"></span><span class="info-bubble"><?php echo $lC_Language->get('infobuble_field_site_meta_title_prefix'); ?></span></span></small> <?php echo $lC_Language->get('field_site_meta_title_prefix'); ?>
              <?php
            foreach ( $lC_Language->getAll() as $l ) {
     ?>
              <span class="required input full-width mid-margin-bottom">
              <label class="button silver-gradient glossy" for="branding_meta_title_prefix[<?php echo $l['id'];?>]"><?php echo  $lC_Language->showImage($l['code']);?></label>
              <input type="text" value="<?php echo (isset($bInfo) && isset($meta_title_prefix[$l['id']]) ? $meta_title_prefix[$l['id']] : null);?>" class="input-unstyled ten-columns required mid-margin-bottom" id="branding_meta_title_prefix[<?php echo $l['id'];?>]" name="branding_meta_title_prefix[<?php echo $l['id'];?>]">
              </span>
              <?php
}
?>
              </span> <span class="button-height block-label"> <small class="float-right"><span class="info-spot"><span class="icon-info-round"></span><span class="info-bubble"><?php echo $lC_Language->get('infobuble_field_site_meta_title_suffix'); ?></span></span></small> <?php echo $lC_Language->get('field_site_meta_title_suffix'); ?>
              <?php
            foreach ( $lC_Language->getAll() as $l ) {
     ?>
              <span class="required input full-width mid-margin-bottom">
              <label class="button silver-gradient glossy" for="branding_meta_title_suffix[<?php echo $l['id'];?>]"><?php echo  $lC_Language->showImage($l['code']);?></label>
              <input type="text" value="<?php echo (isset($bInfo) && isset($meta_title_suffix[$l['id']]) ? $meta_title_suffix[$l['id']] : null);?>" class="input-unstyled ten-columns required mid-margin-bottom" id="branding_meta_title_suffix[<?php echo $l['id'];?>]" name="branding_meta_title_suffix[<?php echo $l['id'];?>]">
              </span>
              <?php
}
?>
              </span>
              <p class="button-height block-label">
                <label for="branding_meta_title_delimeter" class="label"> <small><span class="info-spot"><span class="icon-info-round"></span><span class="info-bubble"><?php echo $lC_Language->get('infobuble_field_site_meta_title_delimeter'); ?></span></span></small> <?php echo $lC_Language->get('field_site_meta_title_delimeter'); ?> </label>
                <input type="text" name="branding_meta_title_delimeter" id="branding_meta_title_delimeter" class="input full-width required" value="<?php echo (isset($bInfo) && isset($meta_delimeter) ? $meta_delimeter : null);?>">
              </p>
            </div>
          </div>
        </div>
        <div id="tab-4" class="with-padding">
          <p class="button-height block-label">
            <label for="branding_social_fb_page" class="label"> <small><span class="info-spot"><span class="icon-info-round"></span><span class="info-bubble"><?php echo $lC_Language->get('infobuble_field_facebook_page') ; ?></span></span></small> <?php echo $lC_Language->get('field_facebook_page') ; ?> </label>
            <input type="text" name="branding_social_fb_page" id="branding_social_fb_page" class="input full-width required" value="<?php echo (isset($bInfo) && isset($social_facebook_page) ? $social_facebook_page : null);?>">
          </p>
          <p class="button-height block-label">
            <label for="branding_social_twitter" class="label"> <small><span class="info-spot"><span class="icon-info-round"></span><span class="info-bubble"><?php echo $lC_Language->get('infobuble_field_twitter') ; ?></span></span></small> <?php echo $lC_Language->get('field_twitter') ; ?> </label>
            <input type="text" name="branding_social_twitter" id="branding_social_twitter" class="input full-width required" value="<?php echo (isset($bInfo) && isset($social_twitter) ? $social_twitter : null);?>">
          </p>
          <p class="button-height block-label">
            <label for="branding_social_pinterest" class="label"> <small><span class="info-spot"><span class="icon-info-round"></span><span class="info-bubble"><?php echo $lC_Language->get('infobuble_field_pinterest') ; ?></span></span></small> <?php echo $lC_Language->get('field_pinterest') ; ?> </label>
            <input type="text" name="branding_social_pinterest" id="branding_social_pinterest" class="input full-width required" value="<?php echo (isset($bInfo) && isset($social_pinterest) ? $social_pinterest : null);?>">
          </p>
          <p class="button-height block-label">
            <label for="branding_social_google_plus" class="label"> <small><span class="info-spot"><span class="icon-info-round"></span><span class="info-bubble"><?php echo $lC_Language->get('infobuble_field_google_plus') ; ?></span></span></small> <?php echo $lC_Language->get('field_google_plus') ; ?> </label>
            <input type="text" name="branding_social_google_plus" id="branding_social_google_plus" class="input full-width required" value="<?php echo (isset($bInfo) && isset($social_google_plus) ? $social_google_plus : null);?>">
          </p>
          <p class="button-height block-label">
            <label for="branding_social_youtube" class="label"> <small><span class="info-spot"><span class="icon-info-round"></span><span class="info-bubble"><?php echo $lC_Language->get('infobuble_field_youtube') ; ?></span></span></small> <?php echo $lC_Language->get('field_youtube') ; ?> </label>
            <input type="text" name="branding_social_youtube" id="branding_social_youtube" class="input full-width required" value="<?php echo (isset($bInfo) && isset($social_youtube) ? $social_youtube : null);?>">
          </p>
          <p class="button-height block-label">
            <label for="branding_social_linkedin" class="label"> <small><span class="info-spot"><span class="icon-info-round"></span><span class="info-bubble"><?php echo $lC_Language->get('infobuble_field_linkedin') ; ?></span></span></small> <?php echo $lC_Language->get('field_linkedin') ; ?> </label>
            <input type="text" name="branding_social_linkedin" id="branding_social_linkedin" class="input full-width required" value="<?php echo (isset($bInfo) && isset($social_linkedin) ? $social_linkedin : null);?>">
          </p>
        </div>
        <div id="tab-5" class="with-padding">
          <p><b><?php echo $lC_Language->get('field_footer_text'); ?></b><span class="info-spot float-right mid-margin-top"><span class="icon-info-round"></span><span class="info-bubble"><?php echo $lC_Language->get('infobuble_field_footer') ; ?></span></span></p>
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