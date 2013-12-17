<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: new.php v1.0 2013-08-08 datazen $
*/
$assignedCategoryTree = new lC_CategoryTree();
$assignedCategoryTree->setBreadcrumbUsage(false);
$assignedCategoryTree->setSpacerString('&nbsp;', 5);

$lC_Template->loadModal($lC_Template->getModule());
?>
<style>
.qq-upload-button { margin-top: -12px; }
.qq-upload-drop-area { min-height: 100px; top: -185px; }
.qq-upload-drop-area span { margin-top:-16px; }
LABEL { font-weight:bold; }
TD { padding: 5px 0 0 5px; }
</style>
<!-- Main content -->
<section role="main" id="main">
  <hgroup id="main-title" class="thin">
    <h1><?php echo (isset($lC_ObjectInfo) && isset($categories_name[$lC_Language->getID()])) ? $categories_name[$lC_Language->getID()] : $lC_Language->get('heading_title_new_category'); ?></h1>
    <?php
      if ( $lC_MessageStack->exists($lC_Template->getModule()) ) {
        echo $lC_MessageStack->get($lC_Template->getModule());
      }
    ?>
  </hgroup>
  <div class="with-padding-no-top">
    <form name="category" id="category" class="dataForm" action="<?php echo lc_href_link_admin(FILENAME_DEFAULT, $lC_Template->getModule() . '=&action=save'); ?>" method="post" enctype="multipart/form-data">
      <div id="category_tabs" class="side-tabs">
        <ul class="tabs">
          <li class="active"><?php echo lc_link_object('#section_general_content', $lC_Language->get('section_general')); ?></li>
          <li id="tabHeaderSectionDataContent"><?php echo lc_link_object('#section_data_content', $lC_Language->get('section_data')); ?></li>
          <!--<li><?php echo lc_link_object('#section_categories_content', $lC_Language->get('section_categories')); ?></li>-->
        </ul>
        <div class="clearfix tabs-content">
          <div id="section_general_content">
            <div class="columns with-padding">
              <div class="new-row-mobile four-columns twelve-columns-mobile">
                <span class="strong margin-right"><?php echo $lC_Language->get('text_categories_image'); ?></span><?php echo lc_show_info_bubble($lC_Language->get('info_bubble_categories_image'), null); ?>   
                <div style="padding-left:6px;" class="small-margin-top">
                  <div id="imagePreviewContainer" class="cat-image align-center">
                    <img src="../images/no_image.png" style="max-width: 100%; height: auto;" align="center" />
                    <input type="hidden" id="categories_image" name="categories_image" value="">
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
              </div>
              <div class="new-row-mobile eight-columns twelve-columns-mobile">
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
                    <div id="languageTabs_<?php echo $l['code']; ?>" class="with-padding">
                      <p class="button-height block-label">
                        <label class="label" for="<?php echo 'categories_name[' . $l['id'] . ']'; ?>">
                          <?php echo $lC_Language->get('field_name'); ?>
                          <?php echo lc_show_info_bubble($lC_Language->get('info_bubble_categories_name'), null); ?>
                        </label>
                        <?php echo lc_draw_input_field('categories_name[' . $l['id'] . ']', null, 'class="required input full-width mid-margin-top" id="categories_name_' . $l['id'] . '"'); ?>
                      </p>
                      <p class="button-height block-label">
                        <label class="label" for="<?php echo 'categories_menu_name[' . $l['id'] . ']'; ?>">
                          <?php echo $lC_Language->get('field_menu_name'); ?>
                          <?php echo lc_show_info_bubble($lC_Language->get('info_bubble_categories_menu_name'), null); ?>
                        </label>
                        <?php echo lc_draw_input_field('categories_menu_name[' . $l['id'] . ']', null, 'class="input full-width mid-margin-top"'); ?>
                      </p>
                      <p class="button-height block-label">
                        <label class="label" for="<?php echo 'categories_blurb[' . $l['id'] . ']'; ?>">
                          <?php echo $lC_Language->get('field_blurb'); ?>
                          <?php echo lc_show_info_bubble($lC_Language->get('info_bubble_categories_blurb'), null); ?>
                        </label>
                        <?php echo lc_draw_textarea_field('categories_blurb[' . $l['id'] . ']', null, null, 1, 'class="input full-width mid-margin-top"'); ?>
                      </p>
                      <p class="button-height block-label">
                        <label class="label" for="<?php echo 'categories_description[' . $l['id'] . ']'; ?>">
                          <?php echo $lC_Language->get('field_description'); ?>
                          <?php echo lc_show_info_bubble($lC_Language->get('info_bubble_categories_description'), null); ?>
                        </label>
                        <div style="margin-bottom:-6px;"></div>
                        <?php echo lc_draw_textarea_field('categories_description[' . $l['id'] . ']', null, null, 10, 'id="ckEditorCategoriesDescription_' . $l['id'] . '" style="width:97%;" class="input full-width autoexpanding"'); ?>
                        <?php if(ENABLE_EDITOR == '1') { ?>
                        <span class="float-right small-margin-top small-margin-right"><?php echo '<a href="javascript:toggleEditor();">' . $lC_Language->get('text_toggle_html_editor') . '</a>'; ?></span>
                        <?php } ?>
                      </p>
                      <br />
                      <p class="button-height block-label">
                        <label class="label" for="<?php echo 'categories_permalink[' . $l['id'] . ']'; ?>">
                          <?php echo $lC_Language->get('field_permalink'); ?>
                          <?php echo lc_show_info_bubble($lC_Language->get('info_bubble_categories_permalink'), null); ?>
                        </label>
                        <?php echo lc_draw_input_field('categories_permalink[' . $l['id'] . ']', null, 'class="required input full-width mid-margin-top" onblur="validatePermalink(this.value);" id="categories_permalink_' . $l['id'] . '"'); ?>
                      </p>
                      <p class="button-height block-label">
                        <label class="label" for="<?php echo 'categories_tags[' . $l['id'] . ']'; ?>">
                          <?php echo $lC_Language->get('field_tags'); ?>
                          <?php echo lc_show_info_bubble($lC_Language->get('info_bubble_categories_tags'), null); ?>
                        </label>
                        <?php echo lc_draw_input_field('categories_tags[' . $l['id'] . ']', null, 'class="input full-width mid-margin-top"'); ?>
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
            <div class="columns">
              <div class="twelve-columns no-margin-bottom">
                <div class="field-drop-tabs button-height black-inputs">
                  <div class="columns no-margin-bottom mid-margin-top">
                    <div class="six-columns twelve-columns-mobile margin-bottom">
                      <label class="label" for="categories_mode"><b><?php echo $lC_Language->get('text_mode'); ?></b></label>
                      <select class="select full-width" id="categories_mode" name="categories_mode" onchange="customCheck();">
                      <?php
                        // later this will become an array from the possible places to link to and expandable by dvelopers also
                        $modes_array = array(
                          array('text' => $lC_Language->get('text_category'), 'value' => 'category'),
                          array('text' => $lC_Language->get('text_page'), 'value' => 'page'),
                          array('text' => $lC_Language->get('link_to_specials'), 'value' => 'specials'),
                          //array('text' => $lC_Language->get('link_to_featured'), 'value' => 'featured'),
                          array('text' => $lC_Language->get('link_to_new'), 'value' => 'new'),
                          array('text' => $lC_Language->get('link_to_search'), 'value' => 'search'),
                          array('text' => $lC_Language->get('link_to_cart'), 'value' => 'cart'),
                          array('text' => $lC_Language->get('link_to_account'), 'value' => 'account'),
                          array('text' => $lC_Language->get('link_to_info'), 'value' => 'info'),
                          array('text' => $lC_Language->get('text_custom_link'), 'value' => 'override')
                        );
                        foreach ($modes_array as $mode) {
                          echo '<option value="' . $mode['value'] . '">' . $mode['text'] . '</option>'; 
                        }
                      ?>
                      </select>
                      <p id="categories_link_target_p" class="small-margin-top" style="display:none;">
                        <input type="checkbox" class="checkbox" id="categories_link_target" name="categories_link_target"> <?php echo $lC_Language->get('text_new_window'); ?>
                      </p>
                    </div>
                    <div class="six-columns twelve-columns-mobile">
                      <?php echo lc_show_info_bubble($lC_Language->get('info_bubble_categories_mode'), null, 'on-left grey no-margin-left small-margin-right'); ?>  
                      <span id="categories_custom" style="display:none;">
                        <input type="text" class="input" id="categories_custom_url" name="categories_custom_url"> &nbsp;
                        <span id="custom_url_text">
                          <?php echo $lC_Language->get('text_custom_link'); ?>
                        </span>
                        <?php echo lc_show_info_bubble($lC_Language->get('info_bubble_categories_custom_url'), null, 'on-left grey margin-left'); ?>
                      </span>
                    </div>
                  </div>
                  <div class="columns">
                    <div class="six-columns twelve-columns-mobile">
                      <label class="label" for="parent_id"><b><?php echo $lC_Language->get('text_parent'); ?></b></label> 
                      <select class="select full-width" id="parent_id" name="parent_id">
                        <option value="0"><?php echo $lC_Language->get('text_top_category'); ?></option>
                        <?php
                          foreach ($assignedCategoryTree->getArray() as $value) {
                            $selected = ($value['id'] == $_GET['categories']) ? ' selected' : '';
                            echo '<option value="' . $value['id'] . '"' . $selected . '>' . $value['title'] . '</option>' . "\n";
                          }
                        ?>
                      </select>
                    </div>
                    <div class="six-columns twelve-columns-mobile small-margin-top">                                                                                        
                      <?php echo lc_show_info_bubble($lC_Language->get('info_bubble_categories_parent'), null, 'on-left grey mid-margin-right'); ?> 
                      <span class="button-group" id="categories_visibility">
                        <?php if ($_GET['categories'] == 0) { ?>
                        <label class="button blue-active" for="categories_visibility_nav">
                          <input type="checkbox" id="categories_visibility_nav" name="categories_visibility_nav">
                          <?php echo $lC_Language->get('text_visibility_nav'); ?>
                        </label>
                        <?php } ?>
                        <label class="button blue-active mid-margin-right" for="categories_visibility_box">
                          <input type="checkbox" checked="" id="categories_visibility_box" name="categories_visibility_box">
                          <?php echo $lC_Language->get('text_visibility_box'); ?>
                        </label>
                      </span>
                      <?php echo $lC_Language->get('text_visibility'); ?>
                      <?php echo lc_show_info_bubble($lC_Language->get('info_bubble_categories_visibility'), null, 'on-left grey margin-left'); ?>
                    </div>
                  </div>
                  <div class="columns">
                    <div class="six-columns twelve-columns-mobile">
                      <label class="label" for="categories_visibility"><b><?php echo $lC_Language->get('text_status'); ?></b></label>
                      <input type="checkbox" class="switch" id="categories_status" name="categories_status" checked="">
                      <?php echo lc_show_info_bubble($lC_Language->get('info_bubble_categories_status'), null, 'on-left grey margin-left'); ?>
                    </div>
                    <div class="six-columns twelve-columns-mobile">
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="columns with-padding no-margin-bottom">
              <div class="twelve-columns no-margin-top no-margin-bottom">
                <div class="columns no-margin-left">
                  <div class="three-columns twelve-columns-mobile"> 
                    <div class="margin-right">
                      <label class="label" for="categories_page_type"><b><?php echo $lC_Language->get('field_categories_page_type'); ?></b></label>
                    </div>
                  </div>
                  <div class="nine-columns twelve-columns-mobile">
                    <p class="mid-margin-bottom">
                      <input type="radio" class="radio small-margin-right" name="categories_page_type" value="html" checked disabled>
                      <?php echo $lC_Language->get('text_standard_html_page'); ?>  
                      <?php echo lc_show_info_bubble($lC_Language->get('info_bubble_categories_page_type'), null, 'on-left grey mid-margin-left'); ?>
                    </p>
                    <p class="mid-margin-bottom">
                      <input type="radio" class="radio small-margin-right" name="categories_page_type" value="photo" disabled>
                      <?php echo $lC_Language->get('text_photo_album'); ?>
                    </p>
                    <p class="mid-margin-bottom">
                      <input type="radio" class="radio small-margin-right" name="categories_page_type" value="faq" disabled>
                      <?php echo $lC_Language->get('text_faq'); ?>
                    </p>
                  </div>
                </div>
              </div>
              <div class="twelve-columns no-margin-top no-margin-bottom">
                <div class="columns no-margin-left">
                  <div class="three-columns twelve-columns-mobile"> 
                    <div class="margin-right">
                      <label class="label" for="categories_content_file"><b><?php echo $lC_Language->get('field_categories_content_file'); ?></b></label>
                    </div>
                  </div>
                  <div class="nine-columns twelve-columns-mobile upsellwrapper">
                    <?php echo lc_draw_input_field('categories_content_file', null, 'id="categories_content_file" name="categories_content_file" class="input" style="min-width:250px;" placeholder="/customhtml.php" disabled'); ?>
                    <span class="upsellinfo" upselltitle="<?php echo $lC_Language->get('text_html_content_file_upsell_title'); ?>" upselldesc="<?php echo $lC_Language->get('text_html_content_file_upsell_desc'); ?>"><?php echo lc_go_pro(); ?></span>  
                    <?php echo lc_show_info_bubble($lC_Language->get('info_bubble_categories_content_file'), null, 'on-left grey large-margin-left'); ?>
                    <p class="small-margin-top"><?php echo $lC_Language->get('text_path_to_file'); ?></p>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div id="section_data_content" class="with-padding">
            <fieldset class="fieldset">
              <legend class="legend"><?php echo $lC_Language->get('field_management_settings'); ?></legend>
              <div class="columns no-margin-bottom">
                <div class="six-columns twelve-columns-mobile upsellwrapper">
                  <label class="label" for="<?php echo 'categories_product_class'; ?>">
                    <?php echo $lC_Language->get('field_product_class'); ?>
                    <span class="upsellinfo" upselltitle="<?php echo $lC_Language->get('text_product_class_upsell_title'); ?>" upselldesc="<?php echo $lC_Language->get('text_product_class_upsell_desc'); ?>"><?php echo lc_go_pro(); ?></span>  
                    <?php echo lc_show_info_bubble($lC_Language->get('info_bubble_categories_product_class'), null); ?>
                  </label>
                  <select class="select full-width mid-margin-top" id="categories_product_class" name="categories_product_class" disabled>
                    <option><?php echo $lC_Language->get('text_common'); ?></option>
                  </select>
                </div>
              </div>
            </fieldset>
            <fieldset class="fieldset">
              <legend class="legend"><?php echo $lC_Language->get('field_access_settings'); ?></legend>
              <div class="columns no-margin-bottom">
                <div class="six-columns twelve-columns-mobile upsellwrapper">
                  <p class="margin-bottom">
                    <label class="label" for="categories_access_levels"><?php echo $lC_Language->get('field_access_levels'); ?></label>
                    <span class="upsellinfo" upselltitle="<?php echo $lC_Language->get('text_access_levels_upsell_title'); ?>" upselldesc="<?php echo $lC_Language->get('text_access_levels_upsell_desc'); ?>"><?php echo lc_go_b2b(); ?></span>  
                    <?php echo lc_show_info_bubble($lC_Language->get('info_bubble_categories_access_levels'), null, 'on-left grey large-margin-left'); ?>  
                  </p>
                  <p class="margin-left">
                    <input type="checkbox" class="checkbox small-margin-right" disabled> <?php echo $lC_Language->get('access_levels_registered'); ?>
                  </p>
                  <p class="margin-left">
                    <input type="checkbox" class="checkbox small-margin-right" disabled> <?php echo $lC_Language->get('access_levels_wholesale'); ?>
                  </p>
                  <p class="margin-left">
                    <input type="checkbox" class="checkbox small-margin-right" disabled> <?php echo $lC_Language->get('access_levels_dealer'); ?>
                  </p>
                </div>
                <div class="six-columns twelve-columns-mobile"></div>
              </div>
            </fieldset>
          </div>
          <!--<div id="section_categories_content" class="with-padding"> 
            Relationships (Later Phase)
          </div>-->
        </div>
      </div>
      <?php
        $next_sort = lC_Categories_Admin::nextSort(); 
        echo lc_draw_hidden_field('sort_order', $next_sort); 
        echo lc_draw_hidden_field('subaction', 'confirm'); 
      ?>
    
    <div class="clear-both"></div>
    <div class="six-columns twelve-columns-tablet">
      <div id="buttons-menu-div-listing">
        <div id="buttons-container" style="position: relative;" class="clear-both">
          <div class="align-right">
           <p class="button-height">
          <?php

          $save = (((int)$_SESSION['admin']['access'][$lC_Template->getModule()] < 3) ? '' : ' onclick="validateForm(\'#category\');"');
          $close = lc_href_link_admin(FILENAME_DEFAULT, $lC_Template->getModule() . ($_GET['categories'] != '') ? 'categories=' . $_GET['categories'] : '');
          button_save_close($save, $close);

          ?>
        </p>
      </div>
        </div>
      </div>
    </div>


    </form>
  </div>
</section>