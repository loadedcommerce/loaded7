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
  
  $('#floating-menu-div-listing').fixFloat();
  createUploader();
  ogUploader();

  $(window).resize(function() {
    if ($(window).width() < 1380) {
      $("#branding_manager_tabs").removeClass("side-tabs");
      $("#branding_manager_tabs").addClass("standard-tabs");
    } if ($(window).width() >= 1380) {
      $("#branding_manager_tabs").removeClass("standard-tabs");
      $("#branding_manager_tabs").addClass("side-tabs");
    }
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

</script>