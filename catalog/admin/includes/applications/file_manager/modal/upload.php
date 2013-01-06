<?php
/*
  $Id: upload.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
?>
<style>
#uploadConfirm { padding-bottom:20px; }
</style>
<script>
function uploadFile() {
  var accessLevel = '<?php echo $_SESSION['admin']['access'][$lC_Template->getModule()]; ?>';
  if (parseInt(accessLevel) < 3) {
    $.modal.alert('<?php echo $lC_Language->get('ms_error_no_access');?>');
    return false;
  }
  $.modal({
    content: '<div id="uploadConfirm">'+
             '  <form name="fmUpload" id="fmUpload" action="<?php echo lc_href_link_admin(FILENAME_DEFAULT, $lC_Template->getModule() . '&action=upload'); ?>" method="post" enctype="multipart/form-data">'+
             '  <p><?php echo $lC_Language->get('introduction_upload_file'); ?></p>'+
             '  <p><?php echo lc_draw_file_field('fmFile[]', true, 'id="fmFileUpload" class="file"'); ?></p>'+
             '  </form>'+
             '  <p class="margin-top"><?php echo lc_output_string_protected($_SESSION['fm_directory']) . '/'; ?></p>'+
             '</div>',
    title: '<?php echo $lC_Language->get('modal_heading_upload_file'); ?>',
    width: 350,
    scrolling: false,
    actions: {
      'Close' : {
        color: 'red',
        click: function(win) { win.closeModal(); }
      }
    },
    buttons: {
      '<?php echo $lC_Language->get('button_cancel'); ?>': {
        classes:  'glossy',
        click:    function(win) { win.closeModal(); }
      },
      '<?php echo $lC_Language->get('button_upload'); ?>': {
        classes:  'blue-gradient glossy',
        click:    function(win) {
          $("#fmUpload").submit();
          win.closeModal();
        }
      }
    },
    buttonsLowPadding: true
  });
} 
</script>