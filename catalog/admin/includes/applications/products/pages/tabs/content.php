<?php
 /**
  $Id: content.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
global $lC_Language, $pInfo, $products_description, $products_keyword, $products_tags; 
?>
<div id="section_general_content" class="with-padding">
  <div class="columns">
    <?php if ($pInfo) { ?>
    <div class="new-row-mobile four-columns twelve-columns-mobile">
      <div class="twelve-columns">
        <span class="strong margin-right"><?php echo $lC_Language->get('text_product_image'); ?></span><?php echo lc_show_info_bubble($lC_Language->get('info_bubble_content_image'), null); ?>   
        <div style="padding-left:6px;" class="small-margin-top">
          <div id="imagePreviewContainer" class="prod-image align-center"></div>
        </div>   
        <p class="thin margin-top" align="center"><?php echo $lC_Language->get('text_drag_drop_to_replace'); ?></p>
        <center style="margin-top:-12px;"><div id="fileUploaderImageContainer" class="small-margin-top">
          <noscript>
            <p><?php echo $lC_Language->get('ms_error_javascript_not_enabled_for_upload'); ?></p>
          </noscript>
        </div></center>        
      </div>
    </div>
    <?php } ?>
    <div class="new-row-mobile <?php echo (isset($pInfo) ? 'eight' : 'twelve'); ?>-columns twelve-columns-mobile">             
      <div class="columns">
      
        <div id="languageTabs" class="standard-tabs same-height no-margin-bottom" style="width:99%;">
          <ul class="tabs">
            <?php               
              foreach ( $lC_Language->getAll() as $l ) {
                echo '<li>' . lc_link_object('#languageTabs_' . $l['code'], $lC_Language->showImage($l['code']) . '&nbsp;' . $l['name']) . '</li>';
              }
            ?>
          </ul>
          <div class="tabs-content description-content">
            <?php
              foreach ( $lC_Language->getAll() as $l ) {
              ?>
              <div style="min-height:530px;" id="languageTabs_<?php echo $l['code']; ?>">
                <fieldset>
                  <p class="button-height block-label">
                    <label class="label" for="<?php echo 'products_name[' . $l['id'] . ']'; ?>"><?php echo $lC_Language->get('field_name') . lc_show_info_bubble($lC_Language->get('info_bubble_content_name')); ?></label>
                    <?php echo lc_draw_input_field('products_name[' . $l['id'] . ']', (isset($pInfo) && isset($products_name[$l['id']]) ? $products_name[$l['id']] : null), 'id="products_name_' . $l['id'] . '" class="required input" style="width:97%;"'); ?>
                  </p>
                  <p class="button-height block-label">
                  <label class="label" for="<?php echo 'products_description[' . $l['id'] . ']'; ?>"><?php echo $lC_Language->get('field_description') . lc_show_info_bubble($lC_Language->get('info_bubble_content_description')); ?></label>
                  <?php echo lc_draw_textarea_field('products_description[' . $l['id'] . ']', (isset($pInfo) && isset($products_description[$l['id']]) ? $products_description[$l['id']] : null), null, 10, 'id="ckEditorProductDescription_' . $l['id'] . '" style="width:97%;" class="required input full-width autoexpanding"'); ?>
                  <p align="right" style="padding:0; margin:-10px 10px -10px 0; font-size:.9em;"><?php echo '<a href="javascript:toggleEditor(\'' . $l['id'] . '\');">' . $lC_Language->get('text_toggle_html_editor') . '</a>'; ?></p>
                  </p>
                  <p class="button-height block-label">
                    <label class="label" for="<?php echo 'products_keyword[' . $l['id'] . ']'; ?>"><?php echo $lC_Language->get('field_keyword') . lc_show_info_bubble($lC_Language->get('info_bubble_content_keyword')); ?></label>
                    <?php echo lc_draw_input_field('products_keyword[' . $l['id'] . ']', (isset($pInfo) && isset($products_keyword[$l['id']]) ? $products_keyword[$l['id']] : null), 'class="input" style="width:97%" id="products_keyword_' . $l['id'] . '"'); ?>
                  </p>
                  <p class="button-height block-label">
                    <label class="label" for="<?php echo 'products_tags[' . $l['id'] . ']'; ?>"><?php echo $lC_Language->get('field_tags') . lc_show_info_bubble($lC_Language->get('info_bubble_content_tags')); ?></label>
                    <?php echo lc_draw_input_field('products_tags[' . $l['id'] . ']', (isset($pInfo) && isset($products_tags[$l['id']]) ? $products_tags[$l['id']] : null), 'id="products_tags_' . $l['id'] . '" class="input" style="width:97%" maxlength="255"'); ?>
                  </p>
                </fieldset>
              </div><div style="clear:both;"></div>
              <?php
              }
            ?>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="field-drop-product button-height black-inputs extreme-margin-bottom">
    <div class="columns">
      <div id="basprice_status_left" class="new-row-mobile four-columns twelve-columns-mobile">
      </div>
      <div class="new-row-mobile eight-columns twelve-columns-mobile">
        <div class="columns">      
          <div class="new-row-mobile new-row-tablet twelve-columns-mobile twelve-columns-tablet six-columns margin-bottom">
            <span>
              <span class="strong"><?php echo $lC_Language->get('field_base_price'); ?></span><?php echo lc_show_info_bubble($lC_Language->get('info_bubble_content_base_price'), 'margin:8px 6px 0 0;'); ?>
            </span> 
            <div class="inputs large" style="font-size:1.8em; padding:8px 0;">
              <span class="mid-margin-left no-margin-right"><?php echo $lC_Currencies->getSymbolLeft(); ?></span>
              <?php echo lc_draw_input_field('products_price', (isset($pInfo) ? lc_round($pInfo->get('products_price'), DECIMAL_PLACES) : null), 'style="font-size:1em; padding:4px; height:20px; width:80%;" class="input-unstyled" onfocus="this.select();" id="products_price0" onchange="$(\'#products_base_price\').val(this.value); updatePricingDiscountDisplay();"'); ?>
            </div>              
          </div>
          <div class="new-row-mobile new-row-tablet twelve-columns-mobile twelve-columns-tablet six-columns">
            <span>
              <span class="strong"><?php echo $lC_Language->get('field_status'); ?></span><?php echo lc_show_info_bubble($lC_Language->get('info_bubble_content_status'), null, 'info-spot on-left grey margin-left'); ?>
            </span><br />
            <span class="button-group">
              <label for="ps_radio_1" class="button blue-active">
                <?php if (isset($pInfo)) { ?>
                  <input type="radio" name="products_status" id="ps_radio_1" value="active"<?php echo ($pInfo->getInt('products_status') == 1 ? ' checked' : ''); ?> />
                <?php } else { ?>
                  <input type="radio" name="products_status" id="ps_radio_1" value="active" checked />
                <?php } ?>
                <?php echo $lC_Language->get('field_status_active'); ?>
              </label>
              <label for="ps_radio_2" class="button red-active">
                <input type="radio" name="products_status" id="ps_radio_2" value="inactive"<?php echo ((isset($pInfo) && $pInfo->getInt('products_status') == -1) ? ' checked' : ''); ?> />
                <?php echo $lC_Language->get('field_status_inactive'); ?>
              </label>
            </span>
          </div>                  
        </div>                  
      </div>
    </div>
  </div>
</div>