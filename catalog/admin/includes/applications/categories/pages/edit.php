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
    $lC_ObjectInfo = new lC_ObjectInfo(lC_Categories_Admin::get($_GET[$lC_Template->getModule()]));
    $Qcd = $lC_Database->query('select * from :table_categories_description where categories_id = :categories_id');
    $Qcd->bindTable(':table_categories_description', TABLE_CATEGORIES_DESCRIPTION);
    $Qcd->bindInt(':categories_id', $lC_ObjectInfo->get('categories_id'));
    $Qcd->execute();
    $categories_name = array();
    $categories_menu_name = array();
    $categories_blurb = array();
    $categories_description = array();
    //$categories_keyword = array();
    $categories_tags = array();
    $categories_meta_title = array();
    $categories_meta_keywords = array();
    $categories_meta_description = array();
    while ($Qcd->next()) {
      $categories_name[$Qcd->valueInt('language_id')] = $Qcd->value('categories_name');
      $categories_menu_name[$Qcd->valueInt('language_id')] = $Qcd->value('categories_menu_name');
      $categories_blurb[$Qcd->valueInt('language_id')] = $Qcd->value('categories_blurb');
      $categories_description[$Qcd->valueInt('language_id')] = $Qcd->value('categories_description');
      //$categories_keyword[$Qcd->valueInt('language_id')] = $Qcd->value('categories_keyword');
      $categories_tags[$Qcd->valueInt('language_id')] = $Qcd->value('categories_tags');
      $categories_meta_title[$Qcd->valueInt('language_id')] = $Qcd->value('categories_meta_title');
      $categories_meta_keywords[$Qcd->valueInt('language_id')] = $Qcd->value('categories_meta_keywords');
      $categories_meta_description[$Qcd->valueInt('language_id')] = $Qcd->value('categories_meta_description');
    }
  }
  
  $assignedCategoryTree = new lC_CategoryTree();
  $assignedCategoryTree->setBreadcrumbUsage(false);
  $assignedCategoryTree->setSpacerString('&nbsp;', 5);

  $lC_Template->loadModal($lC_Template->getModule());
?>
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
  <style>
    LABEL { font-weight:bold; }
    TD { padding: 5px 0 0 5px; }
  </style>
  <div class="with-padding-no-top">
    <form name="category" id="category" class="dataForm" action="<?php echo lc_href_link_admin(FILENAME_DEFAULT, $lC_Template->getModule() . '=' . (isset($lC_ObjectInfo) ? $lC_ObjectInfo->getInt('categories_id') : '') . '&cid=' . $_GET['cid'] . '&action=save'); ?>" method="post" enctype="multipart/form-data">
      <div id="category_tabs" class="side-tabs">
        <ul class="tabs">
          <li class="active"><?php echo lc_link_object('#section_general_content', $lC_Language->get('section_general')); ?></li>
          <li id="tabHeaderSectionDataContent"><?php echo lc_link_object('#section_data_content', $lC_Language->get('section_data')); ?></li>
          <li><?php echo lc_link_object('#section_categories_content', $lC_Language->get('section_categories')); ?></li>
        </ul>
        <div class="clearfix tabs-content">
          <div id="section_general_content">
            <div class="columns with-padding">
              <div class="nine-columns twelve-columns-mobile">
                <div id="categoryLanguageTabs" class="standard-tabs at-bottom">
                  <ul class="tabs">
                  <?php
                    foreach ( $lC_Language->getAll() as $l ) {
                      echo '<li>' . lc_link_object('#languageTabs_' . $l['code'], $lC_Language->showImage($l['code']) . '&nbsp;' . $l['name']) . '</li>';
                    }
                  ?>
                  </ul>
                  <div class="clearfix tabs-content with-padding">
                  <?php
                    foreach ( $lC_Language->getAll() as $l ) {
                    ?>
                    <div id="languageTabs_<?php echo $l['code']; ?>">
                      <p class="button-height block-label">
                        <label class="label" for="<?php echo 'categories_name[' . $l['id'] . ']'; ?>">
                          <!--<small>Additional information</small>-->
                          <?php echo $lC_Language->get('field_name'); ?>
                          <span class="info-spot on-left grey float-right small-margin-bottom">
                            <span class="icon-info-round"></span>
                            <span class="info-bubble">
                              Put the bubble text here
                            </span>
                          </span>
                        </label>
                        <?php echo lc_draw_input_field('categories_name[' . $l['id'] . ']', (isset($lC_ObjectInfo) && isset($categories_name[$l['id']]) ? $categories_name[$l['id']] : null), 'class="required input full-width mid-margin-top"'); ?>
                      </p>
                      <p class="button-height block-label">
                        <label class="label" for="<?php echo 'categories_menu_name[' . $l['id'] . ']'; ?>">
                          <!--<small>Additional information</small>-->
                          <?php echo $lC_Language->get('field_menu_name'); ?>
                          <span class="info-spot on-left grey float-right small-margin-bottom">
                            <span class="icon-info-round"></span>
                            <span class="info-bubble">
                              Put the bubble text here
                            </span>
                          </span>
                        </label>
                        <?php echo lc_draw_input_field('categories_menu_name[' . $l['id'] . ']', (isset($lC_ObjectInfo) && isset($categories_menu_name[$l['id']]) ? $categories_menu_name[$l['id']] : null), 'class="required input full-width mid-margin-top"'); ?>
                      </p>
                      <p class="button-height block-label">
                        <label class="label" for="<?php echo 'categories_blurb[' . $l['id'] . ']'; ?>">
                          <!--<small>Additional information</small>-->
                          <?php echo $lC_Language->get('field_blurb'); ?>
                          <span class="info-spot on-left grey float-right small-margin-bottom">
                            <span class="icon-info-round"></span>
                            <span class="info-bubble">
                              Put the bubble text here
                            </span>
                          </span>
                        </label>
                        <?php echo lc_draw_textarea_field('categories_blurb[' . $l['id'] . ']', (isset($lC_ObjectInfo) && isset($categories_blurb[$l['id']]) ? $categories_blurb[$l['id']] : null), null, 1, 'class="required input full-width mid-margin-top"'); ?>
                      </p>
                      <p class="button-height block-label">
                        <label class="label" for="<?php echo 'categories_description[' . $l['id'] . ']'; ?>">
                          <!--<small>Additional information</small>-->
                          <?php echo $lC_Language->get('field_description'); ?>
                          <span class="info-spot on-left grey float-right small-margin-bottom">
                            <span class="icon-info-round"></span>
                            <span class="info-bubble">
                              Put the bubble text here
                            </span>
                          </span>
                        </label>
                        <?php echo lc_draw_textarea_field('categories_description[' . $l['id'] . ']', (isset($lC_ObjectInfo) && isset($categories_description[$l['id']]) ? $categories_description[$l['id']] : null), null, 10, 'class="required input full-width autoexpanding clEditorCategoriesDescription"'); ?>
                        <span class="float-right"><?php echo '<a href="javascript:toggleEditor();">' . $lC_Language->get('text_toggle_html_editor') . '</a>'; ?></span>
                      </p>
                      <br />
                      <p class="button-height block-label small-margin-top">
                        <label class="label" for="<?php echo 'categories_meta_keywords[' . $l['id'] . ']'; ?>">
                          <!--<small>Additional information</small>-->
                          <?php echo $lC_Language->get('field_meta_keywords'); ?>
                          <span class="info-spot on-left grey float-right small-margin-bottom">
                            <span class="icon-info-round"></span>
                            <span class="info-bubble">
                              Put the bubble text here
                            </span>
                          </span>
                        </label>
                        <?php echo lc_draw_input_field('categories_meta_keywords[' . $l['id'] . ']', (isset($lC_ObjectInfo) && isset($categories_meta_keywords[$l['id']]) ? $categories_meta_keywords[$l['id']] : null), 'class="required input full-width mid-margin-top"'); ?>
                      </p>
                    </div>
                    <div class="clear-both"></div>
                    <?php
                    }
                  ?>
                  </div>
                </div>
              </div>
              <div class="three-columns twelve-columns-mobile">
                <div id="category_image_display">
                  <?php if ($lC_ObjectInfo->get('categories_image')) { ?>
                  <div class="prod-image align-center"><img src="<?php echo DIR_WS_HTTP_CATALOG . 'images/categories/' . $lC_ObjectInfo->get('categories_image'); ?>" style="max-width:100%;" /></div>
                  <?php } else { ?>
                  <div class="prod-image align-center"><img src="images/no-image.png" style="max-width: 100%; height: auto;" align="center" /><br /><?php echo $lC_Language->get('text_no_image'); ?></div>
                  <?php } ?>
                </div>
                <p class="align-center"><?php echo $lC_Language->get('text_thumbnail_image'); ?></p>
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
                          array('text' => 'Category', 'value' => 'category'),
                          array('text' => 'Page', 'value' => 'page'),
                          array('text' => 'Link To: Specials', 'value' => 'specials'),
                          array('text' => 'Link To: Featured', 'value' => 'featured'),
                          array('text' => 'Link To: New Products', 'value' => 'new'),
                          array('text' => 'Link To: Search', 'value' => 'search'),
                          array('text' => 'Link To: Shopping Cart', 'value' => 'cart'),
                          array('text' => 'Link To: My Account', 'value' => 'account'),
                          array('text' => 'Custom Link', 'value' => 'override')
                        );
                        foreach ($modes_array as $mode) {
                          echo '<option value="' . $mode['value'] . '"' . (($lC_ObjectInfo->get('categories_mode') == $mode['value']) ? ' selected' : '') . '>' . $mode['text'] . '</option>'; 
                        }
                      ?>
                      </select>
                      <p id="categories_link_target_p" class="small-margin-top"<?php echo ($lC_ObjectInfo->get('categories_mode') != 'override') ? ' style="display:none;"' : ''; ?>><input type="checkbox" class="checkbox" id="categories_link_target" name="categories_link_target"<?php echo ($lC_ObjectInfo->getInt('categories_link_target') == 1) ? ' checked' : ''; ?>> <?php echo $lC_Language->get('text_new_window'); ?></p>
                    </div>
                    <div class="mid-margin-top" id="categories_link_target_info"<?php echo ($lC_ObjectInfo->get('categories_mode') == 'override') ? ' style="display:none;"' : ''; ?>> 
                      <span class="info-spot on-left grey float-right small-margin-bottom">
                        <span class="icon-info-round"></span>
                        <span class="info-bubble">
                          Put the bubble text here
                        </span>
                      </span>
                    </div>
                    <div class="six-columns twelve-columns-mobile" id="categories_custom"<?php echo ($lC_ObjectInfo->get('categories_mode') != 'override') ? ' style="display:none;"' : ''; ?>>  
                      <span class="info-spot on-left grey mid-margin-right">
                        <span class="icon-info-round"></span>
                        <span class="info-bubble">
                          Put the bubble text here
                        </span>
                      </span>
                      <input type="text" class="input" id="categories_custom_url" name="categories_custom_url"<?php echo ($lC_ObjectInfo->get('categories_mode') == 'override') ? ' value="' . $lC_ObjectInfo->get('categories_custom_url') . '"' : ''; ?>> &nbsp;<strong><?php echo $lC_Language->get('text_custom_link'); ?></strong>
                    </div>
                  </div>
                  <div class="columns">
                    <div class="six-columns twelve-columns-mobile">
                      <label class="label" for="parent_id"><b><?php echo $lC_Language->get('text_parent'); ?></b></label> 
                      <select class="select full-width" id="parent_id" name="parent_id">
                        <option value="top">Top</option>
                        <?php
                          foreach ($assignedCategoryTree->getArray() as $value) {
                            if ($lC_ObjectInfo->getInt('categories_id') != $value['id'] && lC_Categories_Admin::get_final_parent($value['id']) != $lC_ObjectInfo->getInt('categories_id')) {
                              echo '<option final_parent="' . $pStr . '" value="' . $value['id'] . '">' . $value['title'] . '</option>' . "\n";
                            }
                          }
                        ?>
                      </select>
                    </div>
                    <div class="six-columns twelve-columns-mobile small-margin-top">  
                      <span class="info-spot on-left grey mid-margin-right">
                        <span class="icon-info-round"></span>
                        <span class="info-bubble">
                          Put the bubble text here
                        </span>
                      </span>
                      <input type="checkbox" class="switch medium" id="categories_display_in_menu" name="categories_display_in_menu"<?php echo ($lC_ObjectInfo->getInt('categories_display_in_menu') == 1) ? ' checked' : ''; ?>> <strong><?php echo $lC_Language->get('text_display_in_menu'); ?></strong>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="columns with-padding">
              <div class="twelve-columns no-margin-top no-margin-bottom">
                <div class="columns">
                  <div class="three-columns">
                    Left
                  </div>
                  <div class="five-columns">
                    Middle
                  </div>
                  <div class="four-columns">
                    Right
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div id="section_data_content" class="with-padding">
            Data
          </div>
          <div id="section_categories_content" class="with-padding"> 
            Relationships (Later Phase)
          </div>
        </div>
      </div>
      <?php echo lc_draw_hidden_field('sort_order', '10'); ?>
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
                </span><?php echo $lC_Language->get('button_cancel'); ?>
              </a>&nbsp;
              <a class="button<?php echo (((int)$_SESSION['admin']['access'][$lC_Template->getModule()] < 3) ? ' disabled' : NULL); ?>" href="<?php echo (((int)$_SESSION['admin']['access'][$lC_Template->getModule()] < 2) ? '#' : 'javascript://" onclick="$(\'#category\').submit();'); ?>">
                <span class="button-icon green-gradient glossy">
                  <span class="icon-download"></span>
                </span><?php echo $lC_Language->get('button_save'); ?>
              </a>&nbsp;
            </p>
          </div>
          <div id="floating-button-container-title" class="hidden">
            <p class="white big-text small-margin-top"><?php echo $lC_ObjectInfo->get('categories_name'); ?></p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
