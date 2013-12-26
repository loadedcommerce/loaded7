<?php
/*
  $Id: edit.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
?>
<style>
#editEntry { padding-bottom:20px; }
</style>
<script>
function editEntry(id) {
  var accessLevel = '<?php echo $_SESSION['admin']['access'][$lC_Template->getModule()]; ?>';
  if (parseInt(accessLevel) < 3) {
    $.modal.alert('<?php echo $lC_Language->get('ms_error_no_access');?>');
    return false;
  }
  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=getFormData&cid=CID'); ?>'
  $.getJSON(jsonLink.replace('CID', parseInt(id)),
    function (data) {
      if (data.rpcStatus == -10) { // no session
        var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";
        $(location).attr('href',url);
      }
      if (data.rpcStatus != 1) {
        if (data.rpcStatus == -2) {
          $.modal.alert('<?php echo $lC_Language->get('ms_error_username_already_exists'); ?>');
        } else {
          $.modal.alert('<?php echo $lC_Language->get('ms_error_action_not_performed'); ?>');
        }
        return false;
      }
      var labelClass = (data.valueField.indexOf('type="text"') != -1) ? 'block-label' : '';
      $.modal({
          content: '<div id="editEntry">'+
                   '  <div id="editEntryForm">'+
                   '    <form name="cEdit" id="cEdit" action="" method="post">'+
                   '      <p><?php echo $lC_Language->get('introduction_edit_parameter'); ?></p>'+
                   '      <p class="button-height ' + labelClass + '"><span class="margin-right" id="editEntryConfigTitle"></span>'+
                   '        <span id="editEntryValueField"></span>'+
                   '      </p>'+
                   '      <p><span id="editEntryConfigDescription"></span></p>'+
                   '    </form>'+
                   '  </div>'+
                   '</div>',
          title: '<?php echo $lC_Language->get('modal_heading_edit_parameter'); ?>',
          width: 500,
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
            '<?php echo $lC_Language->get('button_save'); ?>': {
              classes:  'blue-gradient glossy',
              click:    function(win) {

                var bValid = $("#cEdit").validate({
                  invalidHandler: function() {
                    $("#status-working").fadeOut('slow');
                  }
                }).form();
                if (bValid) {
                  var nvp = $("#cEdit").serialize();
                  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=saveEntry&cid=CID&BATCH'); ?>'
                  $.getJSON(jsonLink.replace('CID', parseInt(id)).replace('BATCH', nvp),
                    function (data) {
                      if (data.rpcStatus == -10) { // no session
                        var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";
                        $(location).attr('href',url);
                      }
                      if (data.rpcStatus != 1) {
                        alert('<?php echo $lC_Language->get('ms_error_action_not_performed'); ?>');
                        return false;
                      }
                      oTable.fnReloadAjax();
                    }
                  );
                  win.closeModal();
                }
              }
            }
          },
          buttonsLowPadding: true
      });
      $("#editEntryConfigTitle").html('<strong>' + data.cData.configuration_title + '</strong>');
      $("#editEntryValueField").html(data.valueField).change();
      $("#editEntryValueField").find("input[type=text]").addClass("input");
      $("#editEntryConfigDescription").html('<p class="message icon-info anthracite-gradient glossy margin-top full-width dark-stripes"> ' + data.cData.configuration_description + '</p>');
    }
  );
}
</script>