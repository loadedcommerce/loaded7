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
<script>
function deleteClass(id, name) {
  var accessLevel = '<?php echo $_SESSION['admin']['access'][$lC_Template->getModule()]; ?>';
  if (parseInt(accessLevel) < 4) {
    $.modal.alert('<?php echo $lC_Language->get('ms_error_no_access');?>');
    return false;
  }
  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=getFormData&wcid=WCID'); ?>';
  $.getJSON(jsonLink.replace('WCID', id),
    function (data) {
      if (data.rpcStatus == -10) { // no session
        var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";
        $(location).attr('href',url);
      }
      if (data.rpcStatus != 1) {
        if (data.rpcStatus == -2) {
          $.modal.alert('<?php echo $lC_Language->get('delete_error_weight_class_in_use'); ?> ' + data.totalInUse + ' <?php echo $lC_Language->get('delete_error_weight_class_in_use_end'); ?>');
        } else if (data.rpcStatus == -3) {
          $.modal.alert('<?php echo $lC_Language->get('delete_error_weight_class_prohibited'); ?>');
        } else {
          $.modal.alert('<?php echo $lC_Language->get('ms_error_retrieving_data'); ?>');
        }
        return false;
      }
      $.modal({
        content: '<div id="deleteClass">'+
                 '  <div id="deleteConfirm">'+
                 '    <p id="deleteConfirmMessage"><?php echo $lC_Language->get('introduction_delete_weight_class'); ?>'+
                 '      <p><b>' + decodeURI(name.replace(/\+/g, '%20')) + '</b></p>'+
                 '    </p>'+
                 '  </div>'+
                 '</div>',
        title: '<?php echo $lC_Language->get('modal_heading_delete_weight_class'); ?>',
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
              var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=deleteClass&wcid=WCID'); ?>';
              $.getJSON(jsonLink.replace('WCID', parseInt(id)),
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
                }
              );
              win.closeModal();
            }
          }
        },
        buttonsLowPadding: true
      });
    }
  );
}
</script>