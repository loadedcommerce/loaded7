<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: branding_manager.js.php v1.0 2013-08-08 datazen $
*/
global $lC_Template, $lC_Language, $lC_ObjectInfo;
?>
<script>
$(document).ready(function() {
  
  createUploader();
  createogUploader();
 
  <?php
  foreach ( $lC_Language->getAll() as $l ) {
    if (ENABLE_EDITOR == 1 && EDITOR_CONFIGURATION_HOMEPAGE != 'Off') { 
      echo "CKEDITOR.replace('branding_home_page_text[" . $l['id'] . "]', { toolbar: '" . EDITOR_CONFIGURATION_HOMEPAGE . "', height: 200, width: '99.9%', filebrowserUploadUrl: '../ext/jquery/ckeditor/ck_upload.php', contentsCss: '../templates/" . DEFAULT_TEMPLATE . "/css/styles.css', stylesSet: [] });";
    } else {
      echo '$("#branding_home_page_text[' . $l['id'] . ']").css("height", "200px").css("width", "99.9%");';
    }
  }
  ?>

  $(window).resize(function() {
    if ($(window).width() < 1380) {
      $("#branding_manager_tabs").removeClass("side-tabs");
      $("#branding_manager_tabs").addClass("standard-tabs");
    } if ($(window).width() >= 1380) {
      $("#branding_manager_tabs").removeClass("standard-tabs");
      $("#branding_manager_tabs").addClass("side-tabs");
    }
  });  
  
  // remove logo image
  $("#imagePreviewContainer").mouseover(function() {
    var bmLogo = $('#branding_manager_logo').val();
    if (bmLogo != '') {
      $("#bmlogo_controls").show();
      $("#imagePreviewContainer").css("background", "none repeat scroll 0 0 rgba(0, 0, 0, 0.45)");
      $("#imagePreviewContainer").css("border-radius", "4px 4px 4px 4px");
    }
  })
  .mouseout(function() {
    $("#bmlogo_controls").hide();
    $("#imagePreviewContainer").css("background", "none");
  });  
  
  // remove og image
  $("#ogimagePreviewContainer").mouseover(function() {
    var ogLogo = $('#branding_graph_site_thumbnail').val();
    if (ogLogo != '') {
      $("#og_image_controls").show();
      $("#ogimagePreviewContainer").css("background", "none repeat scroll 0 0 rgba(0, 0, 0, 0.45)");
      $("#ogimagePreviewContainer").css("border-radius", "4px 4px 4px 4px");
    }
  })
  .mouseout(function() {
    $("#og_image_controls").hide();
    $("#ogimagePreviewContainer").css("background", "none");
  });
                        
});

function validateForm() {
   var url = '<?php echo lc_href_link_admin(FILENAME_DEFAULT, $lC_Template->getModule() . '=' . $_GET[$lC_Template->getModule()] . '&action=save'); ?>'; 
   $("#branding_manager").attr("action", url);
   $("#branding_manager").submit();
} 


function createUploader() {
  var uploader = new qq.FileUploader({
    element: document.getElementById('fileUploaderImageContainer'),
    action: '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=fileUpload'); ?>',
    allowedExtensions: ['jpg', 'jpeg', 'png', 'gif'],
    multiple: false,
    onComplete: function(id, fileName, responseJSON) {
      $('#imagePreviewContainer').html('<div style="position:relative;"><div id="bmlogo_controls" class="controls"><span class="button-group compact children-tooltip"><a onclick="deleteBmLogo($(\'#branding_manager_logo\').val());" class="button icon-trash" href="#" title="<?php echo $lC_Language->get('text_delete'); ?>"></a></span></div></div><img src="../images/branding/' + fileName + '" border="0" style="max-width:100%;" /><input type="hidden" id="branding_manager_logo" name="branding_manager_logo" value="' + fileName + '">');
    },
  });
} 

function createogUploader() {
  var uploader = new qq.FileUploader({
    element: document.getElementById('ogfileUploaderImageContainer'),
    action: '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=fileUpload'); ?>',
    allowedExtensions: ['jpg', 'jpeg', 'png', 'gif'],
    multiple: false,
    onComplete: function(id, fileName, responseJSON) {
      $('#ogimagePreviewContainer').html('<div style="position:relative;"><div id="og_image_controls" class="controls"><span class="button-group compact children-tooltip"><a onclick="deleteOgImage($(\'#branding_graph_site_thumbnail\').val());" class="button icon-trash" href="#" title="<?php echo $lC_Language->get('text_delete'); ?>"></a></span></div></div><img src="../images/branding/' + fileName + '" border="0" style="max-width:100%;" /><input type="hidden" id="branding_graph_site_thumbnail" name="branding_graph_site_thumbnail" value="' + fileName + '">');
    },
  });
}

function deleteBmLogo(logo) {
  $("#bmlogo_controls").hide();
  $("#imagePreviewContainer").css("background", "none");
  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=deleteBmLogo&logo=LOGO'); ?>';
  $.getJSON(jsonLink.replace('LOGO', logo));
  $("#imagePreviewContainer").html('<div style="position:relative;"><div id="bmlogo_controls" class="controls"><span class="button-group compact children-tooltip"><a onclick="deleteBmLogo($(\'#branding_manager_logo\').val());" class="button icon-trash" href="#" title="<?php echo $lC_Language->get('text_delete'); ?>"></a></span></div></div><img src="../images/no_image.png" /><input type="hidden" id="branding_manager_logo" name="branding_manager_logo" value="">');
}

function deleteOgImage(ogimage) {
  $("#og_image_controls").hide();
  $("#ogimagePreviewContainer").css("background", "none");
  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=deleteOgImage&ogimage=OGIMAGE'); ?>';
  $.getJSON(jsonLink.replace('OGIMAGE', ogimage));
  $("#ogimagePreviewContainer").html('<div style="position:relative;"><div id="og_image_controls" class="controls"><span class="button-group compact children-tooltip"><a onclick="deleteOgImage($(\'#branding_graph_site_thumbnail\').val());" class="button icon-trash" href="#" title="<?php echo $lC_Language->get('text_delete'); ?>"></a></span></div></div><img src="../images/no_image.png" /><input type="hidden" id="branding_graph_site_thumbnail" name="branding_graph_site_thumbnail" value="">');
} 
</script>