<?php
/*
  $Id: main.php v1.0 2013-12-01 resultsonlyweb $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
?>
<!-- Main content -->
<section role="main" id="main">
<noscript class="message black-gradient simpler"><?php echo $lC_Language->get('ms_error_javascript_not_enabled_warning'); ?></noscript>
  <hgroup id="main-title" class="thin">
	<h1><?php echo $lC_Template->getPageTitle(); ?></h1>
  </hgroup>
  <div class="with-padding margin-top">
    <div class="columns">
      <div class="twelve-columns">
        <div class="block margin-bottom">
  	      <h3 class="block-title"><?php echo $lC_Language->get('block_title_products'); ?></h3>
          <div class="columns">
            <div class="six-columns twelve-columns-mobile">
              <form id="products-export-form">
                <fieldset class="fieldset margin-top margin-bottom margin-left">
                  <legend class="legend"><?php echo $lC_Language->get('fieldset_title_products_export'); ?></legend>
                  <p>
                    <span id="products-filter-total">0</span> <?php echo $lC_Language->get('text_records_to_be_exported'); ?>
                  </p>
                  <p class="button-height">
                    <input type="radio" class="radio" checked value="tabbed" name="products-export-format" id="products-export-format-tabbed" /> <label class="label" for="export-format-tabbed">Tabbed</label>
                  </p>
                  <!--<p class="button-height">
                    <?php echo $lC_Language->get('text_choose_a_data_set'); ?>
                  </p>-->
                  <p class="button-height">
                    <button type="button" class="button green-gradient icon-download" onClick="Javascript:getProducts('full');"><?php echo $lC_Language->get('button_full_data_set'); ?></button> 
                  </p>
                  <p class="button-height">
                    <a href="includes/applications/products_import_export/samples/products_import_sample.txt" download>
                      <button type="button" class="button white-gradient"><?php echo $lC_Language->get('button_get_sample_file'); ?></button>
                    </a>
                  </p>
                </fieldset>
              </form>
            </div>
            <div class="six-columns twelve-columns-mobile">
              <form id="products-import-form">
                <fieldset class="fieldset margin-top margin-bottom margin-right">
                  <legend class="legend"><?php echo $lC_Language->get('fieldset_title_products_import'); ?></legend>
                  <div id="fileUploaderProductsContainer" class="small-margin-top">
                    <noscript>
                      <p><?php echo $lC_Language->get('ms_error_javascript_not_enabled_for_upload'); ?></p>
                    </noscript>
                  </div>
                  <p class="button-height">
                    <input type="radio" class="radio" checked value="addmatch" name="products-import-type" id="import-type-addmatch" /> <label class="label" for="products-import-type-addmatch"><?php echo $lC_Language->get('label_add_match'); ?></label>
                  </p>
                  <p class="button-height">
                    <button type="button" class="float-right button green-gradient icon-cloud" onClick="Javascript:importProducts();"><?php echo $lC_Language->get('button_import'); ?></button>
                  </p>
                </fieldset>
              </form>
            </div>
          </div>
        </div>
        <!--
        <div class="margin-bottom">
          <div class="columns">
            <div class="three-columns twelve-columns-mobile">
              <div class="with-border with-padding">
                Partner Solution Promotion Goes Here
              </div>
            </div>
            <div class="three-columns twelve-columns-mobile">
              <div class="with-border with-padding">
                Partner Solution Promotion Goes Here
              </div>
            </div>
            <div class="three-columns twelve-columns-mobile">
              <div class="with-border with-padding">
                Partner Solution Promotion Goes Here
              </div>
            </div>
            <div class="three-columns twelve-columns-mobile">
              <div class="with-border with-padding">
                Partner Solution Promotion Goes Here
              </div>
            </div>
          </div>
        </div>
        -->
        <div class="block margin-bottom">
  	      <h3 class="block-title"><?php echo $lC_Language->get('block_title_categories'); ?></h3>
          <div class="columns">
            <div class="six-columns twelve-columns-mobile">
              <form id="categories-export-form">
                <fieldset class="fieldset margin-top margin-bottom margin-left">
                  <legend class="legend"><?php echo $lC_Language->get('fieldset_title_categories_export'); ?></legend>
                  <p>
                    <span id="categories-filter-total">0</span> <?php echo $lC_Language->get('text_records_to_be_exported'); ?>
                  </p>
                  <p class="button-height">
                    <input type="radio" class="radio" checked value="tabbed" name="categories-export-format" id="categories-export-format-tabbed" /> <label class="label" for="export-format-tabbed">Tabbed</label>
                  </p>
                  <p class="button-height">
                    <button type="button" class="button green-gradient icon-download" onClick="Javascript:getCategories();"><?php echo $lC_Language->get('button_full_data_set'); ?></button>
                  </p>
                  <p class="button-height">
                    <a href="includes/applications/products_import_export/samples/categories_import_sample.txt" download>
                      <button type="button" class="button white-gradient"><?php echo $lC_Language->get('button_get_sample_file'); ?></button>
                    </a>
                  </p>
                </fieldset>
              </form>
            </div>
            <div class="six-columns twelve-columns-mobile">
              <form id="categories-import-form">
                <fieldset class="fieldset margin-top margin-bottom margin-right">
                  <legend class="legend"><?php echo $lC_Language->get('fieldset_title_categories_import'); ?></legend>
                  <div id="fileUploaderCategoriesContainer" class="small-margin-top">
                    <noscript>
                      <p><?php echo $lC_Language->get('ms_error_javascript_not_enabled_for_upload'); ?></p>
                    </noscript>
                  </div>
                  <p class="button-height">
                    <input type="radio" class="radio" checked value="tabbed" name="categories-import-type" id="categories-import-type-addmatch" /> <label class="label" for="categories-import-type-addmatch"><?php echo $lC_Language->get('label_add_match'); ?></label>
                  </p>
                  <p class="button-height">
                    <button type="button" class="float-right button green-gradient icon-cloud" onClick="Javascript:importCategories();"><?php echo $lC_Language->get('button_import'); ?></button>
                  </p>
                </fieldset>
              </form>
            </div>
          </div>
        </div>
        <div class="block">
  	      <h3 class="block-title"><?php echo $lC_Language->get('block_title_options'); ?></h3>
          <div class="columns">
            <div class="six-columns twelve-columns-mobile">
              <form id="options-export-form">
                <fieldset class="fieldset margin-top margin-bottom margin-left">
                  <legend class="legend"><?php echo $lC_Language->get('fieldset_title_options_export'); ?></legend>
                  <p>
                    <span id="options-filter-total">0</span> <?php echo $lC_Language->get('text_records_to_be_exported'); ?>
                  </p>
                  <p class="button-height">
                    <input type="radio" class="radio" checked value="tabbed" name="options-export-format" id="options-export-format-tabbed" /> <label class="label" for="export-format-tabbed">Tabbed</label>
                  </p>
                  <p class="button-height">
                    <button type="button" class="button green-gradient icon-download" onClick="Javascript:getOptionGroups();"><?php echo $lC_Language->get('button_export_options_groups'); ?></button>
                  </p>
                  <p class="button-height">
                    <a href="includes/applications/products_import_export/samples/options_groups_import_sample.txt" download>
                      <button type="button" class="button white-gradient"><?php echo $lC_Language->get('button_get_sample_file'); ?></button>
                    </a>
                  </p>
                  <p class="button-height">
                    <button type="button" class="button green-gradient icon-download" onClick="Javascript:getOptionVariants();"><?php echo $lC_Language->get('button_export_option_variants'); ?></button>
                  </p>
                  <p class="button-height">
                    <a href="includes/applications/products_import_export/samples/options_variants_import_sample.txt" download>
                      <button type="button" class="button white-gradient"><?php echo $lC_Language->get('button_get_sample_file'); ?></button>
                    </a>
                  </p>
                  <p class="button-height">
                    <button type="button" class="button green-gradient icon-download" onClick="Javascript:getOptionProducts();"><?php echo $lC_Language->get('button_export_options_to_products'); ?></button>
                  </p>
                  <p class="button-height">
                    <a href="includes/applications/products_import_export/samples/options_to_products_import_sample.txt" download>
                      <button type="button" class="button white-gradient"><?php echo $lC_Language->get('button_get_sample_file'); ?></button>
                    </a>
                  </p>
                </fieldset>
              </form>
            </div>
            <div class="six-columns twelve-columns-mobile">
              <form id="options-import-form">
                <fieldset class="fieldset margin-top margin-bottom margin-right">
                  <legend class="legend"><?php echo $lC_Language->get('fieldset_title_options_import'); ?></legend>
                  <p class="button-height block-label">
                    <label for="groups-file" class="label">Groups</label>
                  <div id="fileUploaderOptionsGroupsContainer" class="small-margin-top">
                    <noscript>
                      <p><?php echo $lC_Language->get('ms_error_javascript_not_enabled_for_upload'); ?></p>
                    </noscript>
                  </div>
                  </p>
                  <p class="button-height block-label">
                    <label for="variants-file" class="label">Variants</label>
                  <div id="fileUploaderOptionsVarientsContainer" class="small-margin-top">
                    <noscript>
                      <p><?php echo $lC_Language->get('ms_error_javascript_not_enabled_for_upload'); ?></p>
                    </noscript>
                  </div>
                  </p>
                  <p class="button-height block-label">
                    <label for="toproducts-file" class="label">To Products</label>
                  <div id="fileUploaderOptionsProductsContainer" class="small-margin-top">
                    <noscript>
                      <p><?php echo $lC_Language->get('ms_error_javascript_not_enabled_for_upload'); ?></p>
                    </noscript>
                  </div>
                  </p>
                  <p class="button-height">
                    <input type="radio" class="radio" checked value="tabbed" name="options-import-type" id="import-type-addmatch" /> <label class="label" for="options-import-type-addmatch"><?php echo $lC_Language->get('label_add_match'); ?></label>
                  </p>
                  <p class="button-height">
                    <button type="button" class="float-right button green-gradient icon-cloud" onClick="Javascript:importOptions();"><?php echo $lC_Language->get('button_import'); ?></button>
                  </p>
                </fieldset>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<?php
  if (isset($_SESSION['error'])) unset($_SESSION['error']);
  if (isset($_SESSION['errmsg'])) unset($_SESSION['errmsg']);
  $lC_Template->loadModal($lC_Template->getModule());
?>
<!-- End main content -->