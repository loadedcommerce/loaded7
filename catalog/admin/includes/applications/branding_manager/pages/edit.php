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

$bInfo = new lC_ObjectInfo(lC_Branding_manager_Admin::get($_GET[$lC_Template->getModule()]));

?>
<?php //echo (isset($lC_ObjectInfo)) ? $lC_ObjectInfo->get('products_price') : 0; ?>
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

           /* echo   '      <p class="button-height inline-label" id="pImage">'.
                   '        <label for="profile_image" class="label">iiii</label>'.
                   '        <img alt="'.$lC_Language->get('profile_image').'" />'.
                   '        <input type="hidden" name="avatar" id="editAvatar" />'.
                   '      </p>'.
                   '      <p class="inline-label small-margin-top" id="profileUploaderContainerEdit">'. 
                   '        <noscript>'.
                   '          <p>'. $lC_Language->get('ms_error_javascript_not_enabled_for_upload').'</p>'.
                   '        </noscript>'.
                   '      </p>';*/
            ?>
            <div id="languageTabs_<?php echo $l['code']; ?>" class="with-padding">
              <fieldset class="fieldset">
                <legend class="legend"><?php echo $lC_Language->get('field_header'); ?></legend>
                <p class="button-height inline-label">
                  <label class="label" for="branding_image"><?php echo $lC_Language->get('field_branding_manager_logo'); ?></label>
                  <!-- <input type="text" value="<?php echo (!lc_empty($bInfo->get('site_image')) ? $bInfo->getProtected('site_image') : null);?>" class="input two-thirds-width" id="branding_image" name="branding_image"> -->
                  <?php echo lc_show_info_bubble($lC_Language->get('info_bubble_branding_manager_logo'), null); ?> 
                  <div style="padding-left:6px;" class="small-margin-top">
                  <div id="imagePreviewContainer" class="cat-image align-center">
                    <img src="../images/no-image.png" style="max-width: 100%; height: auto;" align="center" />
                    <input type="hidden" id="branding_manager_logo" name="branding_manager_logo" value="no-image.png">
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
                  <label class="label" for="branding_name"><?php echo $lC_Language->get('field_site_name'); ?></label>
                  <input type="text" value="<?php echo (!lc_empty($bInfo->get('name')) ? $bInfo->getProtected('name') : null);?>" class="input two-thirds-width" id="branding_name" name="branding_name">
                </p>
                <p class="button-height inline-label">
                  <label class="label" for="branding_slogan"><?php echo $lC_Language->get('field_site_slogan'); ?></label>
                  <input type="text" value="<?php echo (!lc_empty($bInfo->get('slogan')) ? $bInfo->getProtected('slogan') : null);?>" class="input two-thirds-width" id="branding_slogan" name="branding_slogan">
                </p>
                <p class="button-height inline-label">
                  <label class="label" for="branding_chat_code"><?php echo $lC_Language->get('field_live_chat_code'); ?></label>
                  <?php echo lc_draw_textarea_field('branding_chat_code', (!lc_empty($bInfo->get('chat_code')) ? $bInfo->getProtected('chat_code') : null), 48, 2, 'id="editHtmlText" class="input" ');?>
                </p>
              </fieldset>
              <fieldset class="fieldset">
                <legend class="legend"><?php echo $lC_Language->get('field_company_info'); ?></legend>
                <p class="button-height inline-label">
                  <label class="label" for="branding_address"><?php echo $lC_Language->get('field_address'); ?></label>
                  <?php echo lc_draw_textarea_field('branding_address', (!lc_empty($bInfo->get('address')) ? $bInfo->getProtected('address') : null), 48, 2, 'id="editHtmlText" class="input" ');?>
                </p>
                <p class="button-height inline-label">
                  <label class="label" for="branding_support_phone"><?php echo $lC_Language->get('field_support_phone'); ?></label>
                  <input type="text" value="<?php echo (!lc_empty($bInfo->get('support_phone')) ? $bInfo->getProtected('support_phone') : null);?>" class="input two-thirds-width" id="branding_support_phone" name="branding_support_phone">
                </p>
                <p class="button-height inline-label">
                  <label class="label" for="branding_support_email"><?php echo $lC_Language->get('field_support_email'); ?></label>
                  <input type="text" value="<?php echo (!lc_empty($bInfo->get('support_email')) ? $bInfo->getProtected('support_email') : null);?>" class="input two-thirds-width" id="branding_support_email" name="branding_support_email">
                </p>
                <p class="button-height inline-label">
                  <label class="label" for="branding_sales_phone"><?php echo $lC_Language->get('field_sales_phone'); ?></label>
                  <input type="text" value="<?php echo (!lc_empty($bInfo->get('sales_phone')) ? $bInfo->getProtected('sales_phone') : null);?>" class="input two-thirds-width" id="branding_sales_phone" name="branding_sales_phone">
                </p>
                <p class="button-height inline-label">
                  <label class="label" for="branding_sales_email"><?php echo $lC_Language->get('field_sales_email'); ?></label>
                  <input type="text" value="<?php echo (!lc_empty($bInfo->get('sales_email')) ? $bInfo->getProtected('sales_email') : null);?>" class="input two-thirds-width" id="branding_sales_email" name="branding_sales_email">
                </p>
              </fieldset>
              <fieldset class="fieldset">
                <legend class="legend"><?php echo $lC_Language->get('field_seo'); ?></legend>
                <p class="button-height inline-label">
                  <label class="label" for="branding_meta_description"><?php echo $lC_Language->get('field_site_meta_description'); ?></label>
                  <?php echo lc_draw_textarea_field('branding_meta_description', (!lc_empty($bInfo->get('meta_description')) ? $bInfo->getProtected('meta_description') : null), 48, 2, 'id="editHtmlText" class="input" ');?>
                </p>
                <p class="button-height inline-label">
                  <label class="label" for="branding_meta_keywords"><?php echo $lC_Language->get('field_site_meta_keywords'); ?></label>
                  <input type="text" value="<?php echo (!lc_empty($bInfo->get('meta_keywords')) ? $bInfo->getProtected('meta_keywords') : null);?>" class="input two-thirds-width" id="branding_meta_keywords" name="branding_meta_keywords">
                </p>
                <p class="button-height inline-label">
                  <label class="label" for="branding_graph_site_thumbnail"><?php echo $lC_Language->get('field_open_graph_site_thumbnail'); ?></label>
                   <p class="padding_image">
                   <?php
                  
                  echo lc_draw_file_field('branding_graph_site_thumbnail', null, 'class="file"');
                  echo '<div id="fileUploaderContainer" style="display:none;">'.(!lc_empty($bInfo->get('og_image')) ? $bInfo->getProtected('og_image') : null).'</div>';
                  ?>
                  </p>
                  <small class="branding-extra-line"><?php echo $lC_Language->get('field_open_graph_image'); ?></small></span>


                  


                </p>
                <p class="button-height inline-label">
                  <label class="label" for="branding_meta_title"><?php echo $lC_Language->get('field_home_page_meta_title'); ?></label>
                  <input type="text" value="<?php echo (!lc_empty($bInfo->get('meta_title')) ? $bInfo->getProtected('meta_title') : null);?>" class="input two-thirds-width" id="branding_meta_title" name="branding_meta_title">
                </p>
                <p class="button-height inline-label">
                  <label class="label" for="branding_meta_title_slug"><?php echo $lC_Language->get('field_site_meta_title_slug'); ?></label>
                  <input type="text" value="<?php echo (!lc_empty($bInfo->get('meta_slug')) ? $bInfo->getProtected('meta_slug') : null);?>" class="input two-thirds-width" id="branding_meta_title_slug" name="branding_meta_title_slug">
                  <p class="small-margin-bottom branding-extra-line">
                    <span class="branding-extra-line">  
                    <input name  = 'meta_slug_placement' type="radio" class="radio"> <label class="label" for="meta_slug_placement"><?php echo $lC_Language->get('field_prefix'); ?></label>
                     </span>
                    </p>
                    <p class="small-margin-bottom branding-extra-line">
                      <span class="branding-extra-line">
                      <input name  = 'meta_slug_placement' type="radio" class="radio"> <label class="label" for="meta_slug_placement"><?php echo $lC_Language->get('field_suffix'); ?></label>
                      </span>
                    </p>
                </p>
                <p class="button-height inline-label">
                  <label class="label" for="branding_meta_title_delimeter"><?php echo $lC_Language->get('field_site_meta_title_delimeter'); ?></label>
                  <input type="text" value="<?php echo (!lc_empty($bInfo->get('meta_delimeter')) ? $bInfo->getProtected('meta_delimeter') : null);?>" class="input two-thirds-width" id="branding_meta_title_delimeter" name="branding_meta_title_delimeter">
                </p>
              </fieldset>
              <fieldset class="fieldset">
                <legend class="legend"><?php echo $lC_Language->get('field_footer_text'); ?></legend>
                <p class="button-height inline-label">
                  <label class="label" for="branding_footer_text"><?php echo $lC_Language->get('field_site_footer_text'); ?></label>
                  <?php echo lc_draw_textarea_field('branding_footer_text', (!lc_empty($bInfo->get('footer_text')) ? $bInfo->getProtected('footer_text') : null), 48, 2, 'id="editHtmlText" class="input" ');?>                 
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