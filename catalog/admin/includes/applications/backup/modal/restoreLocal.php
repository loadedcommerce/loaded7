<?php
/*
  $Id: restoreLocal.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
?>
<style>
#restoreLocalConfirm { padding-bottom:20px; }
</style>
<script>
function restoreLocal() {
  var accessLevel = '<?php echo $_SESSION['admin']['access'][$lC_Template->getModule()]; ?>';
  if (parseInt(accessLevel) < 3) {
    $.modal.alert('<?php echo $lC_Language->get('ms_error_no_access');?>');
    return false;
  }
  $.modal({
    content: '<div id="restoreLocalConfirm">'+
             '  <form name="bRestoreLocal" id="bRestoreLocal" action="<?php echo lc_href_link_admin(FILENAME_DEFAULT, $lC_Template->getModule() . '&action=restoreLocal'); ?>" method="post" enctype="multipart/form-data">'+
             '  <p><?php echo $lC_Language->get('introduction_restore_local_file'); ?></p>'+
             '  <p><?php echo lc_draw_file_field('sql_file', true, 'class="file"'); ?></p>'+
             '  </form>'+
             '</div>',
    title: '<?php echo $lC_Language->get('modal_heading_restore_file'); ?>',
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
      '<?php echo $lC_Language->get('button_restore'); ?>': {
        classes:  'blue-gradient glossy',
        click:    function(win) {
          $("#bRestoreLocal").submit();
          win.closeModal();
        }
      }
    },
    buttonsLowPadding: true
  });
}
</script>