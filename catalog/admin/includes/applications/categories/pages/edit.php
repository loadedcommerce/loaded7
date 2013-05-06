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

  if (isset($_SESSION['error'])) unset($_SESSION['error']);
  if (isset($_SESSION['errmsg'])) unset($_SESSION['errmsg']);
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
    form.dataForm fieldset legend { padding: 3px 5px; border-bottom: 1px solid black; font-weight: bold; width: 99%; }
    LABEL { font-weight:bold; }
    TD { padding: 5px 0 0 5px; }
  </style>
  <div class="with-padding-no-top">
    <form name="product" id="product" class="dataForm" action="<?php echo lc_href_link_admin(FILENAME_DEFAULT, $lC_Template->getModule() . '=' . (isset($lC_ObjectInfo) ? $lC_ObjectInfo->getInt('products_id') : '') . '&cID=' . $_GET['cID'] . '&action=save'); ?>" method="post" enctype="multipart/form-data">
      <div class="side-tabs">
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
                  <div class="columns">
                  
                  </div>
                </div>
              </div>
              <div class="twelve-columns large-margin-top">
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
              
              
            </div>  
          </div>
          <div id="section_data_content" class="with-padding">
            Data
          </div>
          <div id="section_categories_content" class="with-padding">
            Relationships
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
