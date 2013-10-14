<?php
/*
  $Id: branding_manager.js.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

global $lC_Template, $lC_Language, $lC_ObjectInfo;
?>
<script>
$(document).ready(function() {
  
  createUploader();
  ogUploader();
 
  <?php
  if (defined('MODULE_CONTENT_HOMEPAGE_HTML_CONTENT')) {
    if (ENABLE_EDITOR == '1') { 
      echo "CKEDITOR.replace('branding_home_page_text', { height: 200, width: '99%', filebrowserUploadUrl: '../ext/jquery/ckeditor/ck_upload.php' });";
    } else {
      echo '$("#branding_home_page_text").css("height", "200px").css("width", "99.8%");';
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
  
  // remove log image
  $("#imagePreviewContainer").mouseover(function() {
    $("#bmlogo_controls").show();
    $("#imagePreviewContainer").css("background", "none repeat scroll 0 0 rgba(0, 0, 0, 0.45)");
    $("#imagePreviewContainer").css("border-radius", "4px 4px 4px 4px");
  })
  .mouseout(function() {
    $("#bmlogo_controls").hide();
    $("#imagePreviewContainer").css("background", "none");
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
      $('#imagePreviewContainer').html('<img src="../images/branding/' + fileName + '" border="0" style="max-width:100%;" /><input type="hidden" id="branding_manager_logo" name="branding_manager_logo" value="' + fileName + '">');
    },
  });
} 

function ogUploader() {
  var uploader = new qq.FileUploader({
    element: document.getElementById('ogfileUploaderImageContainer'),
    action: '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=fileUpload'); ?>',
	  allowedExtensions: ['jpg', 'jpeg', 'png', 'gif'],
	  multiple: false,
    onComplete: function(id, fileName, responseJSON) {
      $('#ogimagePreviewContainer').html('<img src="../images/branding/' + fileName + '" border="0" style="max-width:100%;" /><input type="hidden" id="branding_graph_site_thumbnail" name="branding_graph_site_thumbnail" value="' + fileName + '">');
    },
  });
}

function deleteBmLogo(logo) {
  alert(logo);
  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=deleteBmLogo&logo=LOGO'); ?>';
  $.getJSON(jsonLink.replace('LOGO', logo));
  // here we update the logo preview div etc, change the below code to whats needed              
  //$("#status_" + id).attr('onclick', 'updateStatus(\'' + id + '\', \'0\')');
  //$("#status_" + id).html('<span class="icon-tick icon-size2 icon-green cursor-pointer with-tooltip" title="<?php echo $lC_Language->get('text_disable_category'); ?>"></span>');
}

function deleteOgImage(ogimage) {
  alert(ogimage);
  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=deleteOgImage&ogimage=OGIMAGE'); ?>';
  $.getJSON(jsonLink.replace('OGIMAGE', ogimage));
  // here we update the ogimage preview div etc, change the below code to whats needed              
  //$("#status_" + id).attr('onclick', 'updateStatus(\'' + id + '\', \'0\')');
  //$("#status_" + id).html('<span class="icon-tick icon-size2 icon-green cursor-pointer with-tooltip" title="<?php echo $lC_Language->get('text_disable_category'); ?>"></span>');
} 

</script>