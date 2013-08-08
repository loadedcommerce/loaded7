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
  $bInfo = new lC_ObjectInfo(lC_Branding_manager_Admin::get($_GET[$lC_Template->getModule()]));
}
?>
<style>
.inline-label > .label {
  width: 200px;
}
</style>
<section role="main" id="main">
  <noscript class="message black-gradient simpler"><?php echo $lC_Language->get('ms_error_javascript_not_enabled_warning'); ?></noscript>
  <hgroup id="main-title" class="thin large-margin-bottom">
    <h1><?php echo $lC_Template->getPageTitle(); ?></h1>
  </hgroup>
  <div class="with-padding">
    <form name="branding_manager" id="branding_manager" action="#" method="post">
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
                <legend class="legend">Header</legend>
                <p class="button-height inline-label">
                  <label class="label" for="input-6">Catalog Logo</label>
                  <input type="text" value="" class="input two-thirds-width" id="input-6" name="input-6">
                </p>
                <p class="button-height inline-label">
                  <label class="label" for="input-7">Site Name</label>
                  <input type="text" value="" class="input two-thirds-width" id="input-7" name="input-7">
                </p>
                <p class="button-height inline-label">
                  <label class="label" for="input-8">Site Slogan</label>
                  <input type="text" value="" class="input two-thirds-width" id="input-8" name="input-8">
                </p>
                <p class="button-height inline-label">
                  <label class="label" for="input-8">Live Chat Code</label>
                  <input type="text" value="" class="input two-thirds-width" id="input-8" name="input-8">
                </p>
              </fieldset>
              <fieldset class="fieldset">
                <legend class="legend">Company Info</legend>
                <p class="button-height inline-label">
                  <label class="label" for="input-6">Address</label>
                  <input type="text" value="" class="input two-thirds-width" id="input-6" name="input-6">
                </p>
                <p class="button-height inline-label">
                  <label class="label" for="input-7">Support Phone</label>
                  <input type="text" value="" class="input two-thirds-width" id="input-7" name="input-7">
                </p>
                <p class="button-height inline-label">
                  <label class="label" for="input-8">Support Email</label>
                  <input type="text" value="" class="input two-thirds-width" id="input-8" name="input-8">
                </p>
                <p class="button-height inline-label">
                  <label class="label" for="input-8">Sales Phone</label>
                  <input type="text" value="" class="input two-thirds-width" id="input-8" name="input-8">
                </p>
                <p class="button-height inline-label">
                  <label class="label" for="input-8">Sales Email</label>
                  <input type="text" value="" class="input two-thirds-width" id="input-8" name="input-8">
                </p>
              </fieldset>
              <fieldset class="fieldset">
                <legend class="legend">SEO</legend>
                <p class="button-height inline-label">
                  <label class="label" for="input-6">Site Meta Description</label>
                  <input type="text" value="" class="input two-thirds-width" id="input-6" name="input-6">
                </p>
                <p class="button-height inline-label">
                  <label class="label" for="input-7">Site Meta Keywords</label>
                  <input type="text" value="" class="input two-thirds-width" id="input-7" name="input-7">
                </p>
                <p class="button-height inline-label">
                  <label class="label" for="input-8">Open Graph Site Thumbnail</label>
                  <input type="text" value="" class="input two-thirds-width" id="input-8" name="input-8">
                  <small class="branding-extra-line">Open Graph image must be larger than 200 x 200</small></span>
                </p>
                <p class="button-height inline-label">
                  <label class="label" for="input-8">Home Page Meta Title</label>
                  <input type="text" value="" class="input two-thirds-width" id="input-8" name="input-8">
                </p>
                <p class="button-height inline-label">
                  <label class="label" for="input-8">Site Meta Title Slug</label>
                  <input type="text" value="" class="input two-thirds-width" id="input-8" name="input-8">
                  <span class="branding-extra-line">  
                    <input type="radio" class="radio">
                    <input type="radio" class="radio">
                  </span>
                </p>
                <p class="button-height inline-label">
                  <label class="label" for="input-8">Site Meta Title Delimeter</label>
                  <input type="text" value="" class="input two-thirds-width" id="input-8" name="input-8">
                </p>
              </fieldset>
              <fieldset class="fieldset">
                <legend class="legend">Footer Text</legend>
                <p class="button-height inline-label">
                  <label class="label" for="input-6">Site Footer Text</label>
                  <input type="text" value="" class="input two-thirds-width" id="input-6" name="input-6">
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
  </div>
</section>