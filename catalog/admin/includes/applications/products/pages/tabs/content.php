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
?>
<div id="section_general_content" class="with-padding">
  <div class="columns">
    <div class="new-row-mobile four-columns twelve-columns-mobile">
      <div class="twelve-columns">
        <span class="strong margin-right"><?php echo $lC_Language->get('text_product_image'); ?></span><?php echo lc_show_info_bubble($lC_Language->get('info_bubble_content_image'), null); ?>   
        <div style="padding-left:6px;" class="small-margin-top">
          <div id="imagePreviewContainer" class="prod-image"></div>
        </div>   
        <p class="thin margin-top" align="center"><?php echo $lC_Language->get('text_drag_drop_to_replace'); ?></p>
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
      <div class="new-row-mobile four-columns twelve-columns-mobile">
      </div>
      <div class="new-row-mobile eight-columns twelve-columns-mobile">
        <div style="width:100%;">
          <div style="width:48%; float:left;" class="new-row-mobile new-row-tablet twelve-columns-mobile twelve-columns-tablet baseprice-status margin-bottom">
            <span>
              <span class="strong"><?php echo $lC_Language->get('field_base_price'); ?></span><?php echo lc_show_info_bubble($lC_Language->get('info_bubble_content_base_price'), 'margin:8px 6px 0 0;'); ?>
            </span>
            <?php echo lc_draw_input_field('products_price', (isset($pInfo) ? lc_round($pInfo->get('products_price'), DECIMAL_PLACES) : null), 'style="font-size:2em;" class="input full-width" id="products_price0" onblur="$(\'#products_base_price\').val(this.value);"'); ?>
          </div>
          
          <div style="width:48%; float:right;" class="new-row-mobile new-row-tablet twelve-columns-mobile twelve-columns-tablet baseprice-status">
            <span>
              <span class="strong"><?php echo $lC_Language->get('field_status'); ?></span><?php echo lc_show_info_bubble($lC_Language->get('info_bubble_content_status'), null, 'info-spot on-left grey margin-left'); ?>
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
            </span>
          </div>                  
        </div>                  
      </div>
    </div>
  </div>
  <!-- div class="columns large-margin-top">
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
  </div -->
</div>    
<script>
$(document).ready(function() {
  createUploader2();
  $('#fileUploaderImageContainer .qq-upload-button').hide();
  $('#fileUploaderImageContainer .qq-upload-list').hide();
  <?php               
  foreach ( $lC_Language->getAll() as $l ) {  
    echo "CKEDITOR.replace('ckEditorProductDescription_" . $l['id'] . "', { height: 200, width: '99%'  });";
  }
  ?>  
  $('#products_name_1').focus();
  $(this).scrollTop(0);  
});

function createUploader2(){
  var uploader = new qq.FileUploader({
      element: document.getElementById('fileUploaderImageContainer'),
      action: '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '=' . $pInfo->getInt('products_id') . '&action=fileUpload&default=1'); ?>',
      onComplete: function(id, fileName, responseJSON){
        getImages();
      },
  });
}

function toggleEditor(id) {
  var selection = $("#ckEditorProductDescription_" + id);
  if ($(selection).is(":visible")) {
    $('#ckEditorProductDescription_' + id).hide();
    $('#cke_ckEditorProductDescription_' + id).show();
  } else {
    $('#ckEditorProductDescription_' + id).attr('style', 'width:99%');
    $('#cke_ckEditorProductDescription_' + id).hide();
  }
}

function _disableCKEditor(textarea) {
    // We cannot use getEditor() since it will throw an exception.
    // http://ckeditor.com/blog/CKEditor_for_jQuery
    var ck = textarea.eq(0).data('ckeditorInstance');
    if (ck) {
        ck.destroy();
        ck = false;
    }
}

</script>