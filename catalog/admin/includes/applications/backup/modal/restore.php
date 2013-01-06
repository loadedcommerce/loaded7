<?php
/*
  $Id: delete.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
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
          var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=restoreEntry&fname=FNAME'); ?>';   
          $.getJSON(jsonLink.replace('FNAME', id),
            function (data) {
              if (data.rpcStatus == -10) { // no session
                var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";
                $(location).attr('href',url);
              }
              $("#status-working").fadeOut('slow');
              if (data.rpcStatus != 1) {
                $.modal.alert('<?php echo $lC_Language->get('ms_error_action_not_performed'); ?>');
                return false;
              }
              oTable.fnReloadAjax();
              $("#bRestore").submit();
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
</script>