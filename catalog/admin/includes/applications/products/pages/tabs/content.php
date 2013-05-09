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
<div id="section_general_content" class="with-padding">
  <div class="columns">
    <div class="new-row-mobile four-columns twelve-columns-mobile">
      <!--<div class="twelve-columns hide-below-768" style="height:38px;">
      &nbsp;
      </div>-->
      <div class="twelve-columns">
        <span class="strong"><?php echo $lC_Language->get('text_product_image'); ?></span><?php echo lc_show_info_bubble($lC_Language->get('info_bubble_content_image')); ?>   
        
        <div style="padding-left:6px;" class="small-margin-top">
          <div id="imagePreviewContainer" class="prod-image"></div>
        </div>        
      </div>
    </div>
    
    <div class="new-row-mobile eight-columns twelve-columns-mobile">             
      <div class="columns">

      
          <div id="section_general_content_container">
            <div id="languageTabs" class="standard-tabs same-height">
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
                    <fieldset>
                      <p class="button-height inline-small-label"><label class="label" for="<?php echo 'products_name[' . $l['id'] . ']'; ?>"><?php echo $lC_Language->get('field_name'); ?></label><?php echo lc_draw_input_field('products_name[' . $l['id'] . ']', (isset($lC_ObjectInfo) && isset($products_name[$l['id']]) ? $products_name[$l['id']] : null), 'class="required input float-right" style="width:95%;"'); ?></p>
                      <p class="button-height inline-small-label"><label class="label" for="<?php echo 'products_description[' . $l['id'] . ']'; ?>"><?php echo $lC_Language->get('field_description'); ?></label><?php echo lc_draw_textarea_field('products_description[' . $l['id'] . ']', (isset($lC_ObjectInfo) && isset($products_description[$l['id']]) ? $products_description[$l['id']] : null), null, 4, 'class="required input float-right" style="width:95%;"'); ?><div style="width:99%; text-align: right;"><?php echo '<a href="javascript:toggleEditor(\'products_description[' . $l['id'] . ']\');">' . $lC_Language->get('toggle_html_editor') . '</a>'; ?></div></p>
                      <p class="button-height inline-small-label"><label class="label" for="<?php echo 'products_keyword[' . $l['id'] . ']'; ?>"><?php echo $lC_Language->get('field_keyword'); ?></label><?php echo lc_draw_input_field('products_keyword[' . $l['id'] . ']', (isset($lC_ObjectInfo) && isset($products_keyword[$l['id']]) ? $products_keyword[$l['id']] : null), 'class="input float-right" style="width:95%" id="keyword' . $l['id'] . '"'); ?></p>
                      <p class="button-height inline-small-label"><label class="label" for="<?php echo 'products_tags[' . $l['id'] . ']'; ?>"><?php echo $lC_Language->get('field_tags'); ?></label><?php echo lc_draw_input_field('products_tags[' . $l['id'] . ']', (isset($lC_ObjectInfo) && isset($products_tags[$l['id']]) ? $products_tags[$l['id']] : null), 'class="input float-right" style="width:95%" maxlength="255"'); ?></p>
                    </fieldset>
                  </div><div class="clear-both"></div>
                  <?php
                }
                ?>
              </div>
            </div>
            <fieldset>
              <table border="0" width="100%" cellspacing="0" cellpadding="2">
                <tr><td>&nbsp;</td></tr>
                <?php
                $Qattributes = $lC_Database->query('select id, code from :table_templates_boxes where modules_group = :modules_group order by code');
                $Qattributes->bindTable(':table_templates_boxes');
                $Qattributes->bindValue(':modules_group', 'product_attributes');
                $Qattributes->execute();
                while ( $Qattributes->next() ) {
                  $module = basename($Qattributes->value('code'));
                  if ( !class_exists('lC_ProductAttributes_' . $module) ) {
                    if ( file_exists(DIR_FS_CATALOG . 'admin/includes/modules/product_attributes/' . $module . '.php') ) {
                      include(DIR_FS_CATALOG . 'admin/includes/modules/product_attributes/' . $module . '.php');
                    }
                  }
                  if ( class_exists('lC_ProductAttributes_' . $module) ) {
                    $module = 'lC_ProductAttributes_' . $module;
                    $module = new $module();
                    ?>
                    <tr class="with-padding">
                      <td width="150px" style="font-weight:bold;"><?php echo $module->getTitle() . ':'; ?></td>
                      <td><?php echo $module->setFunction((isset($attributes[$Qattributes->valueInt('id')]) ? $attributes[$Qattributes->valueInt('id')] : null)); ?></td>
                    </tr>
                    <tr><td>&nbsp;</td></tr>
                    <?php
                  }
                }
                ?>
              </table>
            </fieldset>
            <script type="text/javascript">
            $(document).ready(function() {
              var pid = '<?php echo $_GET[$lC_Template->getModule()]; ?>';
              var jsonVKUrl = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=validateKeyword&pid=PID'); ?>';
              $("#product").validate({

                invalidHandler: function() {
                  $("#checkAllTabs").html('<?php echo $lC_Language->get('ms_error_check_all_lang_tabs'); ?>').fadeIn('fast').delay(2000).fadeOut('slow');
                },
                rules: {
                  <?php
                  foreach ( $lC_Language->getAll() as $l ) {
                    ?>
                    "products_keyword[<?php echo $l['id']; ?>]": {
                      required: true,
                      remote: jsonVKUrl.replace('PID', pid),

                    },
                    <?php
                  }
                  ?>
                },
                messages: {
                  <?php
                  foreach ( $lC_Language->getAll() as $l ) {
                    ?>
                    "products_keyword[<?php echo $l['id']; ?>]": "<?php echo $lC_Language->get('ms_error_product_keyword_exists'); ?>",
                    <?php
                  }
                  ?>
                }
               });
               <?php
               if ( isset($lC_ObjectInfo) && ($lC_ObjectInfo->getInt('has_children') == 1) ) {
                 ?>
                 $("#has_variants").attr('checked', true);
                 <?php
               }
               ?>
               //$( "button, input:submit, a", ".ui-dialog-buttonset" ).button();
             });
            </script>
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
            <?php echo lc_draw_input_field('products_price', (isset($pInfo) ? lc_round($pInfo->get('products_price'), DECIMAL_PLACES) : null), 'style="font-size:2em;" class="input full-width" id="products_price0" onkeyup="updateGross(\'products_price0\')"'); ?>
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
      <!-- span class="full-width">
        <span class="strong"><?php //echo $lC_Language->get('field_date_available'); ?></span><?php //echo lc_show_info_bubble($lC_Language->get('info_bubble_content_date_available')); ?>
      </span>
      <p style="background-color:#cccccc;" class="with-small-padding small-margin-top"><b>Random Date Here</b></p -->              
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

});

$("#keyword").blur(function() {
  alert('blur');
});

function toggleEditor(id) {
  var editorHidden = $(".clEditorProductDescription").is(":visible");
  if (editorHidden) {
    //alert('show');
    $(".clEditorProductDescription").cleditor({width:"99%", height:"255"});
  } else {
    //alert('hide');
    var editor = $(".clEditorProductDescription").cleditor()[0];
    editor.$area.insertBefore(editor.$main); // Move the textarea out of the main div
    editor.$area.removeData("cleditor"); // Remove the cleditor pointer from the textarea
    editor.$main.remove(); // Remove the main div and all children from the DOM
    $(".clEditorProductDescription").show();
  }
}
</script>