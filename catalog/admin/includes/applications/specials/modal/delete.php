<?php
/*
  $Id: delete.php v1.0 2011-11-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
?>
<script>
function deleteSpecial(id, name) {
  var accessLevel = '<?php echo $_SESSION['admin']['access'][$lC_Template->getModule()]; ?>';
  if (parseInt(accessLevel) < 4) {
    $.modal.alert('<?php echo $lC_Language->get('ms_error_no_access');?>');
    return false;
  }
  $.modal({
    content: '<div id="deleteSpecial">'+
             '  <div id="deleteConfirm">'+
             '    <p id="deleteConfirmMessage"><?php echo $lC_Language->get('introduction_delete_special'); ?>'+
             '      <p><b>' + decodeURI(name.replace(/\+/g, '%20').replace(/\%3A/g, ':')) + '</b></p>'+
             '    </p>'+
             '  </div>'+
             '</div>',
    title: '<?php echo $lC_Language->get('modal_heading_delete_specials'); ?>',
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
      '<?php echo $lC_Language->get('button_delete'); ?>': {
        classes:  'blue-gradient glossy',
        click:    function(win) {
          var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=deleteEntry&sid=SID'); ?>';
          $.getJSON(jsonLink.replace('SID', id),
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