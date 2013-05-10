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
global $lC_Language, $lC_Template, $pInfo, $products_description, $products_keyword, $products_tags; 

//echo "<pre>";
//print_r($pInfo);
//echo "</pre>";
  
?>
<style>
#fileUploaderImageContainer .qq-upload-drop-area { left: 19%; min-height: 104px; position: absolute; width: 65%; top: -200px; }
#fileUploaderImageContainer .qq-upload-drop-area span { margin-top:-16px; }
</style>
<div id="section_general_content" class="with-padding">
  <div class="columns">
    <div class="new-row-mobile four-columns twelve-columns-mobile">
      <div class="twelve-columns">
        <span class="strong margin-right"><?php echo $lC_Language->get('text_product_image'); ?></span><?php echo lc_show_info_bubble($lC_Language->get('info_bubble_content_image'), null); ?>   
        <div style="padding-left:6px;" class="small-margin-top">
          <div id="imagePreviewContainer" class="prod-image"></div>
        </div>   
        <p class="thin" align="center"><?php echo $lC_Language->get('text_drag_drop_to_replace'); ?></p>
        <div id="fileUploaderImageContainer" class="small-margin-top">
          <noscript>
            <p><?php echo $lC_Language->get('ms_error_javascript_not_enabled_for_upload'); ?></p>
          </noscript>
        </div>        
      </div>
    </div>
    
    <div class="new-row-mobile eight-columns twelve-columns-mobile">             
      <div class="columns">
      
        <div id="languageTabs" class="standard-tabs same-height no-margin-bottom" style="width:99%;">
          <ul class="tabs">
            <?php               
              foreach ( $lC_Language->getAll() as $l ) {
                echo '<li>' . lc_link_object('#languageTabs_' . $l['code'], $lC_Language->showImage($l['code']) . '&nbsp;' . $l['name']) . '</li>';
              }
            ?>
          </ul>
          <div class="tabs-content" style="padding:10px 10px 20px 16px;">
            <?php
              foreach ( $lC_Language->getAll() as $l ) {
              ?>
              <div id="languageTabs_<?php echo $l['code']; ?>">
                <fieldset>
                  <p class="button-height block-label">
                    <label class="label" for="<?php echo 'products_name[' . $l['id'] . ']'; ?>"><?php echo $lC_Language->get('field_name') . lc_show_info_bubble($lC_Language->get('info_bubble_content_name')); ?></label>
                    <?php echo lc_draw_input_field('products_name[' . $l['id'] . ']', (isset($lC_ObjectInfo) && isset($products_name[$l['id']]) ? $products_name[$l['id']] : null), 'id="products_name_' . $l['id'] . '" class="required input" style="width:96%;"'); ?>
                  </p>
                  <p class="button-height block-label">
                  <label class="label" for="<?php echo 'products_description[' . $l['id'] . ']'; ?>"><?php echo $lC_Language->get('field_description') . lc_show_info_bubble($lC_Language->get('info_bubble_content_description')); ?></label>
                  <?php echo lc_draw_textarea_field('products_description[' . $l['id'] . ']', (isset($lC_ObjectInfo) && isset($products_description[$l['id']]) ? $products_description[$l['id']] : null), null, 10, 'id="clEditorProductDescription_' . $l['id'] . '" style="width:96%;" class="required input with-editor"'); ?>
                  <p align="right" style="padding:0; margin:-10px 10px -10px 0; font-size:.9em;"><?php echo '<a href="javascript:toggleEditor(\'' . $l['id'] . '\');">' . $lC_Language->get('text_toggle_html_editor') . '</a>'; ?></p>
                  </p>
                  <p class="button-height block-label">
                    <label class="label" for="<?php echo 'products_keyword[' . $l['id'] . ']'; ?>"><?php echo $lC_Language->get('field_keyword') . lc_show_info_bubble($lC_Language->get('info_bubble_content_keyword')); ?></label>
                    <?php echo lc_draw_input_field('products_keyword[' . $l['id'] . ']', (isset($lC_ObjectInfo) && isset($products_keyword[$l['id']]) ? $products_keyword[$l['id']] : null), 'class="input" style="width:96%" id="products_keyword_' . $l['id'] . '"'); ?>
                  </p>
                  <p class="button-height block-label">
                    <label class="label" for="<?php echo 'products_tags[' . $l['id'] . ']'; ?>"><?php echo $lC_Language->get('field_tags') . lc_show_info_bubble($lC_Language->get('info_bubble_content_tags')); ?></label>
                    <?php echo lc_draw_input_field('products_tags[' . $l['id'] . ']', (isset($lC_ObjectInfo) && isset($products_tags[$l['id']]) ? $products_tags[$l['id']] : null), 'id="products_tags_' . $l['id'] . '" class="input" style="width:96%" maxlength="255"'); ?>
                  </p>
                </fieldset>
              </div>
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
      <div class="new-row-mobile four-columns twelve-columns-mobile">
      </div>
      <div class="new-row-mobile eight-columns twelve-columns-mobile">
        <div style="width:100%;">
          <div style="float:left;" class="new-row-mobile new-row-tablet twelve-columns-mobile twelve-columns-tablet baseprice-status">
            <span class="full-width">
              <span class="strong"><?php echo $lC_Language->get('field_base_price'); ?></span><?php echo lc_show_info_bubble($lC_Language->get('info_bubble_content_base_price')); ?>
            </span>
            <?php echo lc_draw_input_field('products_price', (isset($pInfo) ? lc_round($pInfo->get('products_price'), DECIMAL_PLACES) : null), 'style="font-size:2em;" class="input full-width" id="products_price0" onblur="$(\'#products_base_price\').val(this.value);"'); ?>
          </div>
          <div style="float:left;width:2%;">&nbsp;</div>
          <div style="float:left;" class="new-row-mobile new-row-tablet twelve-columns-mobile twelve-columns-tablet baseprice-status">
            <span class="full-width">
              <span class="strong"><?php echo $lC_Language->get('field_status'); ?></span><?php echo lc_show_info_bubble($lC_Language->get('info_bubble_content_status')); ?>
            </span><br />
            <span class="button-group">
              <label for="ps_radio_1" class="button blue-active">
                <input type="radio" name="products_status" id="ps_radio_1" value="active"<?php echo ((isset($pInfo) && $pInfo->getInt('products_status') == 1) ? ' checked' : ''); ?> />
                <?php echo $lC_Language->get('field_status_active'); ?>
              </label>
              <label for="ps_radio_2" class="button red-active">
                <input type="radio" name="products_status" id="ps_radio_2" value="inactive"<?php echo ((isset($pInfo) && $pInfo->getInt('products_status') == -1) ? ' checked' : ''); ?> />
                <?php echo $lC_Language->get('field_status_inactive'); ?>
              </label>
              <label for="ps_radio_3" class="button orange-active disabled">
                <input type="radio" name="products_status" id="ps_radio_3" value="recurring"<?php echo ((isset($pInfo) && $pInfo->getInt('products_status') == 0) ? ' checked' : ''); ?> />
                <?php echo $lC_Language->get('field_status_coming'); ?>
              </label>
            </span>
          </div>                  
        </div>                  
      </div>
    </div>
  </div>
  <div class="columns large-margin-top">
    <div class="four-columns twelve-columns-mobile large-margin-bottom">
      <center><img src="images/prodchart.png" /></center>
    </div>
    <div class="four-columns twelve-columns-mobile"> 
      <span class="full-width">
        <span class="strong"><?php echo $lC_Language->get('field_model'); ?></span><?php echo lc_show_info_bubble($lC_Language->get('info_bubble_content_model')); ?>
      </span>
      <p style="background-color:#cccccc;" class="with-small-padding small-margin-top"><b><?php echo (isset($pInfo) && ($pInfo->getInt('has_children') == 1)) ? $lC_Language->get('text_complex_variants') : (($pInfo->get('products_model') != '') ? $pInfo->get('products_model') : $lC_Language->get('text_no_model')); ?></b></p>             
    </div>
    <div class="four-columns twelve-columns-mobile">
      <span class="full-width">
        <span class="strong"><?php echo $lC_Language->get('field_weight'); ?></span><?php echo lc_show_info_bubble($lC_Language->get('info_bubble_content_weight')); ?>
      </span>
      <p style="background-color:#cccccc;" class="with-small-padding small-margin-top"><b><?php echo $pInfo->get('products_weight'); ?></b></p>              
    </div>
  </div>
</div>    
<script>
$(document).ready(function() {
  createUploader2();
  $('#fileUploaderImageContainer .qq-upload-button').hide();
  $('#fileUploaderImageContainer .qq-upload-list').hide();
});

function createUploader2(){
  var uploader = new qq.FileUploader({
      element: document.getElementById('fileUploaderImageContainer'),
      action: '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '=' . $lC_ObjectInfo->getInt('products_id') . '&action=fileUpload&default=1'); ?>',
      onComplete: function(id, fileName, responseJSON){
        getImages();
      },
  });
}



$(document).ready(function() {    
  <?php               
  foreach ( $lC_Language->getAll() as $l ) {
    echo "toggleEditor('" . $l['id'] . "');";
  }
  ?>  

  $('#products_name_1').focus();
  $(this).scrollTop(0);
});

function toggleEditor(id) {
  var selection = $("#clEditorProductDescription_" + id);
  
  if ($(selection).is(":visible")) {
    $("#clEditorProductDescription_" + id).cleditor({width:"98%", height:"255"});
  } else {
    var editor = $("#clEditorProductDescription_" + id).cleditor()[0];
    editor.$area.insertBefore(editor.$main); // Move the textarea out of the main div
    editor.$area.removeData("cleditor"); // Remove the cleditor pointer from the textarea
    editor.$main.remove(); // Remove the main div and all children from the DOM
    $("#clEditorProductDescription_" + id).css('width', '96%').show();
  }
}
</script>