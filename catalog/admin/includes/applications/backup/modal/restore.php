<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: restore.php v1.0 2013-08-08 datazen $
*/
?>
<style>
#restoreConfirm { padding-bottom:20px; }
</style>
<script>
function restoreEntry(id) {  
  var accessLevel = '<?php echo $_SESSION['admin']['access'][$lC_Template->getModule()]; ?>';
  if (parseInt(accessLevel) < 3) {
    $.modal.alert('<?php echo $lC_Language->get('ms_error_no_access');?>');
    return false;
  }
  $.modal({
    content: '<div id="restoreConfirm">'+
             '  <form name="bRestore" id="bRestore" action="<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>" method="post" enctype="multipart/form-data">'+
             '    <p id="restoreConfirmMessage"></p>'+
             '  </form>'+ 
             '</div>',
    title: '<?php echo $lC_Language->get('modal_heading_restore_file'); ?>',
    width: 400,
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
          mask();
          var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=restoreEntry&fname=FNAME'); ?>';   
          $.getJSON(jsonLink.replace('FNAME', id),
            function (data) {
              unmask();
              if (data.rpcStatus == -10) { // no session
                var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";
                $(location).attr('href',url);
              }
              if (data.rpcStatus != 1) {
                $.modal.alert('<?php echo $lC_Language->get('ms_error_action_not_performed'); ?>');
                return false;
              }
              modalMessage('<?php echo $lC_Language->get('message_backup_success'); ?>');
              setTimeout('__redirect()', 1000);
            }
          );
          win.closeModal();
        }
      }
    },
    buttonsLowPadding: true
  });
  $("#restoreConfirmMessage").html('<span class="icon-warning icon-red"></span>&nbsp;<?php echo $lC_Language->get('introduction_restore_file'); ?><p class="margin-top"><b>' + decodeURI(id) + '</b></p>');
}

function __redirect() {
  var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'backup'); ?>";
  $(location).attr('href',url);  
}

</script>