<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: delete.php v1.0 2013-08-08 datazen $
*/
?>
<script>
function deleteGroup(id, name) {
  var accessLevel = '<?php echo $_SESSION['admin']['access'][$lC_Template->getModule()]; ?>';
  if (parseInt(accessLevel) < 4) {
    $.modal.alert('<?php echo $lC_Language->get('ms_error_no_access');?>');
    return false;
  }
  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=getFormData&pvid=PVID'); ?>';
  $.getJSON(jsonLink.replace('PVID', parseInt(id)),
    function (data) {
      if (data.rpcStatus == -10) { // no session
        var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";
        $(location).attr('href',url);
      }
      $("#status-working").fadeOut('slow');
      if (data.pvData.total_products > 0) {
        $.modal.alert('<?php echo $lC_Language->get('delete_error_entry_in_use_start'); ?> ' + data.pvData.total_products + ' <?php echo $lC_Language->get('delete_error_entry_in_use_end'); ?>');
        return false;
      }
      if (data.rpcStatus != 1) {
        $.modal.alert('<?php echo $lC_Language->get('ms_error_retrieving_data'); ?>');
        return false;
      }
      $.modal({
        content: '<div id="deleteGroup">'+
                 '  <div id="deleteConfirm">'+
                 '    <p id="deleteConfirmMessage"><?php echo $lC_Language->get('introduction_delete_variant_group'); ?>'+
                 '      <p><b>' + decodeURI(name.replace(/\+/g, '%20')) + '</b></p>'+
                 '    </p>'+
                 '  </div>'+
                 '</div>',
        title: '<?php echo $lC_Language->get('modal_heading_delete_variant_group'); ?>',
        width: 300,
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
              var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=deleteGroup&pvid=PVID'); ?>';
              $.getJSON(jsonLink.replace('PVID', parseInt(id)),
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
  );
}
</script>