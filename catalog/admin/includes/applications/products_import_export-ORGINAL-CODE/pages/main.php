<?php
/*
  $Id: main.php v1.0 2013-09-19 $

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
  <div class="with-padding">
    <div class="columns">
      <div class="twelve-columns">
        <div class="block margin-bottom">
  	      <h3 class="block-title"><?php echo $lC_Language->get('block_title_products'); ?></h3>
          <div class="columns">
            <div class="six-columns twelve-columns-mobile">
              <form id="products-export-form">
                <fieldset class="fieldset margin-top margin-bottom margin-left">
                  <legend class="legend"><?php echo $lC_Language->get('fieldset_title_products_export'); ?></legend>
                  <p class="button-height">
                    <label for class="label hidden" upsell="Listing Filter Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus diam. Sed nisi. Nulla quis sem at nibh elementum imperdiet.">Listing Filter</label>
                    <select id="products-filter" name="products-filter" class="select disabled">
                      <option value="None">No Filter</option>
                    </select>
                    <span class="info-spot on-left grey margin-left">
                      <span class="icon-info-round"></span>
                      <span class="info-bubble blue-bg"><?php echo $lC_Language->get('info_export_filter'); ?></span>
                    </span>
                    <span class="upsell-spot">
                      <a href="javascript:void(0);" onclick="showUpsellSpot(this); return false;" style="cursor:pointer !important;">
                        <small class="tag red-bg with-tooltip" title=" Click for Info" data-tooltip-options="{"classes":["anthracite-gradient glossy small no-padding"],"position":"right"}">Pro</small>
                      </a>
                    </span>
                  </p>
                  <p>
                    <?php echo $lC_Language->get('text_filter_applied'); ?> <span id="products-filter-applied">None</span><br />
                    <span id="products-filter-total">0</span> <?php echo $lC_Language->get('text_records_to_be_exported'); ?>
                  </p>
                  <p class="button-height">
                    <input type="radio" class="radio" checked value="tabbed" name="products-export-format" id="products-export-format-tabbed" /> <label class="label" for="export-format-tabbed">Tabbed</label>
                  </p>
                  <p class="button-height">
                    <input type="radio" class="radio" disabled value="csv" name="products-export-format" id="products-export-format-csv" /> <label class="label" for="export-format-csv">CSV <small class="tag red-bg">Pro</small></label>
                  </p>
                  <p class="button-height">
                    <?php echo $lC_Language->get('text_choose_a_data_set'); ?>
                  </p>
                  <p class="button-height">
                    <button type="button" class="button green-gradient icon-download" onClick="Javascript:getProducts('full');"><?php echo $lC_Language->get('button_full_data_set'); ?></button> 
                    <button type="button" class="button green-gradient icon-download" onClick="Javascript:getProducts('qtyprice');"><?php echo $lC_Language->get('button_qty_price_set'); ?></button>
                  </p>
                  <p class="button-height">
                    <a href="/admin/includes/applications/products_import_export/samples/products_import_sample.txt" download>
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
                  <p class="button-height">
                    <input type="file" class="file" id="productFile" name="productFile" />
                  </p>
                  <p class="button-height">
                    <input type="radio" class="radio" checked value="addmatch" name="products-import-type" id="import-type-addmatch" /> <label class="label" for="products-import-type-addmatch"><?php echo $lC_Language->get('label_add_match'); ?></label>
                  </p>
                  <p class="button-height">
                    <input type="radio" class="radio" disabled value="replace" name="products-import-type" id="import-type-replace" /> <label class="label" for="products-export-format-csv"><?php echo $lC_Language->get('label_replace_database'); ?> <small class="tag red-bg">Pro</small></label>
                  </p>
                  <p class="button-height">
                    <input type="checkbox" class="checkbox disabled" disabled value="1" name="products-mapping-wizard" id="products-mapping-wizard" /> <label class="label" for="products-mapping-wizard"><?php echo $lC_Language->get('label_use_mapping_wizard'); ?> <small class="tag red-bg">Pro</small></label>
                  </p>
                  <p class="button-height">
                    <input type="checkbox" class="checkbox disabled" disabled value="1" name="products-create-backup" id="products-create-backup" /> <label class="label" for="products-create-backup"><?php echo $lC_Language->get('label_create_product_backup'); ?> <small class="tag red-bg">Pro</small></label>
                  </p>
                  <p class="button-height">
                    <button type="button" class="float-right button green-gradient icon-cloud" onClick="Javascript:importProducts();"><?php echo $lC_Language->get('button_import'); ?></button>
                  </p>
                </fieldset>
              </form>
            </div>
          </div>
        </div>
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
        <div class="block margin-bottom">
  	      <h3 class="block-title"><?php echo $lC_Language->get('block_title_categories'); ?></h3>
          <div class="columns">
            <div class="six-columns twelve-columns-mobile">
              <form id="categories-export-form">
                <fieldset class="fieldset margin-top margin-bottom margin-left">
                  <legend class="legend"><?php echo $lC_Language->get('fieldset_title_categories_export'); ?></legend>
                  <p class="button-height">
                    <label for class="label hidden" upsell="Listing Filter Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus diam. Sed nisi. Nulla quis sem at nibh elementum imperdiet.">Listing Filter</label>
                    <select id="categories-filter" name="categories-filter" class="select disabled">
                      <option value="None">No Filter</option>
                    </select>
                    <span class="info-spot on-left grey margin-left">
                      <span class="icon-info-round"></span>
                      <span class="info-bubble blue-bg"><?php echo $lC_Language->get('info_export_filter'); ?></span>
                    </span>
                    <span class="upsell-spot">
                      <a href="javascript:void(0);" onclick="showUpsellSpot(this); return false;" style="cursor:pointer !important;">
                        <small class="tag red-bg with-tooltip" title=" Click for Info" data-tooltip-options="{"classes":["anthracite-gradient glossy small no-padding"],"position":"right"}">Pro</small>
                      </a>
                    </span>
                  </p>
                  <p>
                    <?php echo $lC_Language->get('text_filter_applied'); ?> <span id="categories-filter-applied">None</span><br />
                    <span id="categories-filter-total">0</span> <?php echo $lC_Language->get('text_records_to_be_exported'); ?>
                  </p>
                  <p class="button-height">
                    <input type="radio" class="radio" checked value="tabbed" name="categories-export-format" id="categories-export-format-tabbed" /> <label class="label" for="export-format-tabbed">Tabbed</label>
                  </p>
                  <p class="button-height">
                    <input type="radio" class="radio" disabled value="csv" name="categories-export-format" id="categories-export-format-csv" /> <label class="label" for="export-format-csv">CSV <small class="tag red-bg">Pro</small></label>
                  </p>
                  <p class="button-height">
                    <button type="button" class="button green-gradient icon-download" onClick="Javascript:getCategories();"><?php echo $lC_Language->get('button_full_data_set'); ?></button>
                  </p>
                  <p class="button-height">
                    <a href="/admin/includes/applications/products_import_export/samples/categories_import_sample.txt" download>
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
                  <p class="button-height">
                    <input type="file" class="file" name="categoriesFile" id="categoriesFile" />
                  </p>
                  <p class="button-height">
                    <input type="radio" class="radio" checked value="tabbed" name="categories-import-type" id="categories-import-type-addmatch" /> <label class="label" for="categories-import-type-addmatch"><?php echo $lC_Language->get('label_add_match'); ?></label>
                  </p>
                  <p class="button-height">
                    <input type="radio" class="radio" disabled value="csv" name="categories-import-type" id="categories-import-type-replace" /> <label class="label" for="categories-export-format-csv"><?php echo $lC_Language->get('label_replace_database'); ?> <small class="tag red-bg">Pro</small></label>
                  </p>
                  <p class="button-height">
                    <input type="checkbox" class="checkbox disabled" disabled value="1" name="categories-mapping-wizard" id="categories-mapping-wizard" /> <label class="label" for="categories-mapping-wizard"><?php echo $lC_Language->get('label_use_mapping_wizard'); ?> <small class="tag red-bg">Pro</small></label>
                  </p>
                  <p class="button-height">
                    <input type="checkbox" class="checkbox disabled" disabled value="1" name="categories-create-backup" id="categories-create-backup" /> <label class="label" for="categories-create-backup"><?php echo $lC_Language->get('label_create_category_backup'); ?> <small class="tag red-bg">Pro</small></label>
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
                  <p class="button-height">
                    <label for class="label hidden" upsell="Listing Filter Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus diam. Sed nisi. Nulla quis sem at nibh elementum imperdiet.">Listing Filter</label>
                    <select id="options-filter" name="options-filter" class="select disabled">
                      <option value="None">No Filter</option>
                    </select>
                    <span class="info-spot on-left grey margin-left">
                      <span class="icon-info-round"></span>
                      <span class="info-bubble blue-bg"><?php echo $lC_Language->get('info_export_filter'); ?></span>
                    </span>
                    <span class="upsell-spot">
                      <a href="javascript:void(0);" onclick="showUpsellSpot(this); return false;" style="cursor:pointer !important;">
                        <small class="tag red-bg with-tooltip" title=" Click for Info" data-tooltip-options="{"classes":["anthracite-gradient glossy small no-padding"],"position":"right"}">Pro</small>
                      </a>
                    </span>
                  </p>
                  <p>
                    <?php echo $lC_Language->get('text_filter_applied'); ?> <span id="options-filter-applied">None</span><br />
                    <span id="options-filter-total">0</span> <?php echo $lC_Language->get('text_records_to_be_exported'); ?>
                  </p>
                  <p class="button-height">
                    <input type="radio" class="radio" checked value="tabbed" name="options-export-format" id="options-export-format-tabbed" /> <label class="label" for="export-format-tabbed">Tabbed</label>
                  </p>
                  <p class="button-height">
                    <input type="radio" class="radio" disabled value="csv" name="options-export-format" id="options-export-format-csv" /> <label class="label" for="export-format-csv">CSV <small class="tag red-bg">Pro</small></label>
                  </p>
                  <p class="button-height">
                    <button type="button" class="button green-gradient icon-download" onClick="Javascript:getOptionGroups();"><?php echo $lC_Language->get('button_export_options_groups'); ?></button>
                  </p>
                  <p class="button-height">
                    <a href="/admin/includes/applications/products_import_export/samples/options_groups_import_sample.txt" download>
                      <button type="button" class="button white-gradient"><?php echo $lC_Language->get('button_get_sample_file'); ?></button>
                    </a>
                  </p>
                  <p class="button-height">
                    <button type="button" class="button green-gradient icon-download" onClick="Javascript:getOptionVariants();"><?php echo $lC_Language->get('button_export_option_variants'); ?></button>
                  </p>
                  <p class="button-height">
                    <a href="/admin/includes/applications/products_import_export/samples/options_variants_import_sample.txt" download>
                      <button type="button" class="button white-gradient"><?php echo $lC_Language->get('button_get_sample_file'); ?></button>
                    </a>
                  </p>
                  <p class="button-height">
                    <button type="button" class="button green-gradient icon-download" onClick="Javascript:getOptionProducts();"><?php echo $lC_Language->get('button_export_options_to_products'); ?></button>
                  </p>
                  <p class="button-height">
                    <a href="/admin/includes/applications/products_import_export/samples/options_to_products_import_sample.txt" download>
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
                    <input type="file" id="optionsGroupsFile" name="optionsGroupsFile" class="file" />
                  </p>
                  <p class="button-height block-label">
                    <label for="variants-file" class="label">Variants</label>
                    <input type="file" id="optionsVariantsFile" name="optionsVariantsFile" class="file" />
                  </p>
                  <p class="button-height block-label">
                    <label for="toproducts-file" class="label">To Products</label>
                    <input type="file" id="optionsProductsFile" name="optionsProductsFile" class="file" />
                  </p>
                  <p class="button-height">
                    <input type="radio" class="radio" checked value="tabbed" name="options-import-type" id="import-type-addmatch" /> <label class="label" for="options-import-type-addmatch"><?php echo $lC_Language->get('label_add_match'); ?></label>
                  </p>
                  <p class="button-height">
                    <input type="radio" class="radio" disabled value="csv" name="options-import-type" id="import-type-replace" /> <label class="label" for="options-export-format-csv"><?php echo $lC_Language->get('label_replace_database'); ?> <small class="tag red-bg">Pro</small></label>
                  </p>
                  <p class="button-height">
                    <input type="checkbox" class="checkbox disabled" disabled value="1" name="options-mapping-wizard" id="options-mapping-wizard" /> <label class="label" for="options-mapping-wizard"><?php echo $lC_Language->get('label_use_mapping_wizard'); ?> <small class="tag red-bg">Pro</small></label>
                  </p>
                  <p class="button-height">
                    <input type="checkbox" class="checkbox disabled" disabled value="1" name="options-create-backup" id="options-create-backup" /> <label class="label" for="options-create-backup"><?php echo $lC_Language->get('label_create_options_backup'); ?> <small class="tag red-bg">Pro</small></label>
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