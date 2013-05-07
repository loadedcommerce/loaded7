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
    while ($Qcd->next()) {
      $categories_name[$Qcd->valueInt('language_id')] = $Qcd->value('categories_name');
      $categories_menu_name[$Qcd->valueInt('language_id')] = $Qcd->value('categories_menu_name');
      $categories_description[$Qcd->valueInt('language_id')] = $Qcd->value('categories_description');
      $categories_keyword[$Qcd->valueInt('language_id')] = $Qcd->value('categories_keyword');
      $categories_tags[$Qcd->valueInt('language_id')] = $Qcd->value('categories_tags');
      $categories_meta_title[$Qcd->valueInt('language_id')] = $Qcd->value('categories_meta_title');
      $categories_meta_keywords[$Qcd->valueInt('language_id')] = $Qcd->value('categories_meta_keywords');
      $categories_meta_description[$Qcd->valueInt('language_id')] = $Qcd->value('categories_meta_description');
    }
  }
  
  $assignedCategoryTree = new lC_CategoryTree();
  $assignedCategoryTree->setBreadcrumbUsage(false);
  $assignedCategoryTree->setSpacerString('&nbsp;', 5);

  if (isset($_SESSION['error'])) unset($_SESSION['error']);
  if (isset($_SESSION['errmsg'])) unset($_SESSION['errmsg']);
  $lC_Template->loadModal($lC_Template->getModule());
?>
<script>
  function toggleEditor(id) {
    var editorHidden = $(".clEditorCategoriesDescription").is(":visible");
    if (editorHidden) {
      //alert('show');
      $(".clEditorCategoriesDescription").cleditor({width:"99%", height:"255"});
    } else {
      //alert('hide');
      var editor = $(".clEditorCategoriesDescription").cleditor()[0];
      editor.$area.insertBefore(editor.$main); // Move the textarea out of the main div
      editor.$area.removeData("cleditor"); // Remove the cleditor pointer from the textarea
      editor.$main.remove(); // Remove the main div and all children from the DOM
      $(".clEditorCategoriesDescription").show();
    }
  }
  $(document).ready(function() {
    $(".clEditorCategoriesDescription").cleditor({width:"99%", height:"255"});
  });
</script>
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
    form.dataForm fieldset legend { padding: 3px 5px; border-bottom: 1px solid black; font-weight: bold; width: 99%; }
    LABEL { font-weight:bold; }
    TD { padding: 5px 0 0 5px; }
  </style>
  <div class="with-padding-no-top">
    <form name="product" id="product" class="dataForm" action="<?php echo lc_href_link_admin(FILENAME_DEFAULT, $lC_Template->getModule() . '=' . (isset($lC_ObjectInfo) ? $lC_ObjectInfo->getInt('categories_id') : '') . '&cID=' . $_GET['cID'] . '&action=save'); ?>" method="post" enctype="multipart/form-data">
      <div id="category_tabs" class="side-tabs">
        <ul class="tabs">
          <li class="active"><?php echo lc_link_object('#section_general_content', $lC_Language->get('section_general')); ?></li>
          <li id="tabHeaderSectionDataContent"><?php echo lc_link_object('#section_data_content', $lC_Language->get('section_data')); ?></li>
          <li><?php echo lc_link_object('#section_categories_content', $lC_Language->get('section_categories')); ?></li>
        </ul>
        <div class="clearfix tabs-content">
          <div id="section_general_content">
            <div class="columns">
              <div class="twelve-columns large-margin-top">
                <div class="with-padding">
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
                          <div class="columns margin-top">
                            <div class="eight-columns">
                              <p class="button-height block-label">
                                <label class="label" for="<?php echo 'categories_name[' . $l['id'] . ']'; ?>">
                                  <!--<small>Additional information</small>-->
                                  <?php echo $lC_Language->get('field_name'); ?>
                                </label>
                                <?php echo lc_draw_input_field('categories_name[' . $l['id'] . ']', (isset($lC_ObjectInfo) && isset($categories_name[$l['id']]) ? $categories_name[$l['id']] : null), 'class="required input full-width mid-margin-top"'); ?>
                              </p>
                              <p class="button-height block-label">
                                <label class="label" for="<?php echo 'categories_menu_name[' . $l['id'] . ']'; ?>">
                                  <!--<small>Additional information</small>-->
                                  <?php echo $lC_Language->get('field_menu_name'); ?>
                                </label>
                                <?php echo lc_draw_input_field('categories_menu_name[' . $l['id'] . ']', (isset($lC_ObjectInfo) && isset($categories_menu_name[$l['id']]) ? $categories_menu_name[$l['id']] : null), 'class="required input full-width mid-margin-top"'); ?>
                              </p>
                              <p class="button-height block-label">
                                <label class="label" for="<?php echo 'categories_description[' . $l['id'] . ']'; ?>">
                                  <!--<small>Additional information</small>-->
                                  <?php echo $lC_Language->get('field_description'); ?>
                                </label>
                                <?php echo lc_draw_textarea_field('categories_description[' . $l['id'] . ']', (isset($lC_ObjectInfo) && isset($categories_description[$l['id']]) ? $categories_description[$l['id']] : null), null, 10, 'class="required input full-width autoexpanding clEditorCategoriesDescription"'); ?>
                                <span class="float-right"><?php echo '<a href="javascript:toggleEditor();">' . $lC_Language->get('text_toggle_html_editor') . '</a>'; ?></span>
                              </p>
                              <p class="button-height block-label">
                                <label class="label" for="<?php echo 'categories_meta_keywords[' . $l['id'] . ']'; ?>">
                                  <!--<small>Additional information</small>-->
                                  <?php echo $lC_Language->get('field_meta_keywords'); ?>
                                </label>
                                <?php echo lc_draw_input_field('categories_meta_keywords[' . $l['id'] . ']', (isset($lC_ObjectInfo) && isset($categories_meta_keywords[$l['id']]) ? $categories_meta_keywords[$l['id']] : null), 'class="required input full-width mid-margin-top"'); ?>
                              </p>
                            </div>
                            <div class="four-columns small-margin-top">
                              <dl class="accordion same-height margin-top">
                                <dt><?php echo $lC_Language->get('text_categories_image_preview'); ?>
                                  <!--<div class="button-group absolute-right compact mid-margin-right">
                                    <a href="#" class="button icon-cloud-upload disabled">Upload</a>
                                    <a href="#" class="button icon-trash with-tooltip disabled" title="Delete"></a>
                                  </div>-->
                                </dt>
                                <dd>
                                  <div class="with-padding">
                                    <?php //if ($Qpi->value('image')) { ?>
                                    <!--<div class="prod-image align-center"><img src="<?php //echo DIR_WS_HTTP_CATALOG . 'images/categories/large/' . $Qpi->value('image'); ?>" style="max-width:100%;" /></div>-->
                                    <?php //} else { ?>
                                    <div class="prod-image align-center"><img src="images/no-image.png" style="max-width: 100%; height: auto;" align="center" /><br /><?php echo $lC_Language->get('text_no_image'); ?></div>
                                    <?php //} ?>
                                  </div>
                                </dd>
                              </dl>
                              <p class="align-center"><?php echo $lC_Language->get('text_thumbnail_image'); ?></p>
                            </div>  
                          </div>
                        </div>
                        <div class="clear-both"></div>
                      <?php
                      }
                    ?>
                    </div>
                  </div>
                </div>
              </div>
              <div class="twelve-columns no-margin-bottom">
                <div class="field-drop-tabs button-height black-inputs">
                  <div class="columns no-margin-bottom">
                    <div class="one-column">
                      <label class="label" for="resize_height"><b>Mode</b></label>
                    </div>
                    <div class="eleven-columns margin-bottom">
                      <div>
                        <input id="category_mode_1" type="radio" value="category" name="category_mode">
                        <span>Product Category</span>
                      </div>
                      <div>
                        <input id="category_mode_2" type="radio" value="page" name="category_mode">
                        <span>Page Only</span>
                      </div>
                    </div>
                  </div>
                  <div class="columns">
                    <div class="one-column">
                      <label class="label" for="resize_height"><b>Parent</b></label>
                    </div>
                    <div class="eleven-columns">
                      <select class="select">
                        <option>Top</option>
                        <option>Women</option>
                        <option>Men</option>
                        <option>Kids</option>
                        <option>Accessories</option>
                      </select>
                    </div>
                  </div>
                </div>
              </div>
              
              
              
              
              <div class="twelve-columns with-padding no-margin-top no-margin-bottom">
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
            <fieldset class="fieldset">
              <legend class="legend"><?php echo $lC_Language->get('text_categories'); ?></legend>
              <table border="0" width="100%" cellspacing="0" cellpadding="2" style="margin-top:-10px;">
                <tr>
                  <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
                    <tbody>
                    <?php
                      foreach ($assignedCategoryTree->getArray() as $value) {
                        echo '          <tr>' . "\n" .
                             '            <td width="30px" class="cat_rel_td">' . lc_draw_checkbox_field('categories[]', $value['id'], in_array($value['id'], $product_categories_array), 'class="input" id="categories_' . $value['id'] . '"') . '</td>' . "\n" .
                             '            <td class="cat_rel_td"><a href="#" onclick="document.product.categories_' . $value['id'] . '.checked=!document.product.categories_' . $value['id'] . '.checked;">' . $value['title'] . '</a></td>' . "\n" .
                             '          </tr>' . "\n";
                      }
                    ?>
                    </tbody>
                  </table></td>
                </tr>
              </table>
              <br />
            </fieldset>
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
              <a class="button" href="<?php echo lc_href_link_admin(FILENAME_DEFAULT, $lC_Template->getModule()); ?>">
                <span class="button-icon red-gradient glossy">
                  <span class="icon-cross"></span>
                </span><?php echo $lC_Language->get('button_cancel'); ?>
              </a>&nbsp;
              <a class="button<?php echo (((int)$_SESSION['admin']['access'][$lC_Template->getModule()] < 3) ? ' disabled' : NULL); ?>" href="<?php echo (((int)$_SESSION['admin']['access'][$lC_Template->getModule()] < 2) ? '#' : 'javascript://" onclick="$(\'#product\').submit();'); ?>">
                <span class="button-icon green-gradient glossy">
                  <span class="icon-download"></span>
                </span><?php echo $lC_Language->get('button_save'); ?>
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
