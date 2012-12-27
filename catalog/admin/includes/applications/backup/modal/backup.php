<?php
/*
  $Id: backup.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
$compression_array = array(array('id' => 'none',
                                 'text' => $lC_Language->get('field_compression_none')));
if (function_exists('exec')) {
  $gzip_path = exec('which gzip');
  if($gzip_path > ' ') { 
    $compression_array[] = array('id' => 'gzip',
                                 'text' => $lC_Language->get('field_compression_gzip'));
  }
  $zip_path = exec('which zip');
  if($zip_path > ' ') {
    $compression_array[] = array('id' => 'zip',
                                 'text' => $lC_Language->get('field_compression_zip'));
  }
}
?>
<style>
#backupContent { padding-bottom:20px; }
</style>
<script>
function doBackup() {
  var accessLevel = '<?php echo $_SESSION['admin']['access'][$lC_Template->getModule()]; ?>';
  if (parseInt(accessLevel) < 2) {
    $.modal.alert('<?php echo $lC_Language->get('ms_error_no_access');?>');
    return false;
  }
  $.modal({
    content: '<div id="backupContent">'+
             '  <form name="bBackup" id="bBackup" action="" method="post">'+
             '  <p><?php echo $lC_Language->get('introduction_new_backup'); ?></p>'+
             '  <p><?php echo lc_draw_radio_field('compression', $compression_array, 'none', null, '&nbsp;<br />'); ?></p>'+
             '  <p><?php if (!lc_empty(DIR_FS_BACKUP) && @is_dir(DIR_FS_BACKUP) && @is_writeable(DIR_FS_BACKUP) ) { echo lc_draw_checkbox_field('download_only', array(array('id' => 'yes', 'text' => $lC_Language->get('field_download_only')))); } else { echo lc_draw_radio_field('download_only', array(array('id' => 'yes', 'text' => $lC_Language->get('field_download_only'))), true); } ?></p>'+
             '  </form>'+
             '</div>',
    title: '<?php echo $lC_Language->get('modal_heading_new_backup'); ?>',
    width: 300,
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
      '<?php echo $lC_Language->get('button_backup'); ?>': {
        classes:  'blue-gradient glossy',
        click:    function(win) {
          var checked = $("#download_only").attr('checked');
          if (checked == true) {
            $("#backupContent").dialog('close');
            window.location = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, $lC_Template->getModule() . '&action=backup&download_only=yes'); ?>";
          }
          var nvp = $("#bBackup").serialize();  
          var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=doBackup&DATA'); ?>'  
          $.getJSON(jsonLink.replace('DATA', nvp),        
            function (data) {
              if (data.rpcStatus == -10) { // no session
                var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";     
                $(location).attr('href',url);
              }                
              if (data.rpcStatus != 1) {
                $.modal.alert('<?php echo $lC_Language->get('ms_error_action_not_performed'); ?>');
                return false;
              }                
              oTable.fnReloadAjax();
            }              
          );          
          win.closeModal();
        }
      }
    },
    buttonsLowPadding: true
  });
}
</script>