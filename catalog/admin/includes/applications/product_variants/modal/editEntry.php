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
  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=getEntryFormData&pveid=PVEID'); ?>'
  $.getJSON(jsonLink.replace('PVEID', parseInt(id)),
    function (data) {
      if (data.rpcStatus == -10) { // no session
        var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";
        $(location).attr('href',url);
      }
      if (data.rpcStatus != 1) {
        $.modal.alert('<?php echo $lC_Language->get('ms_error_retrieving_data'); ?>');
        return false;
      }
      $.modal({
          content: '<div id="editEntry">'+
                 '  <div id="editEntryForm">'+
                 '    <form name="pveEdit" id="pveEdit" autocomplete="off" action="" method="post">'+
                 '      <p><?php echo $lC_Language->get('introduction_edit_entry'); ?></p>'+
                 '      <p class="button-height inline-label">'+
                 '        <label for="entry_name" class="label"><?php echo $lC_Language->get('field_name'); ?></label>'+
                 '        <span id="editEntryNames"></span>'+
                 '      </p>'+
                 '      <p class="button-height inline-label">'+
                 '        <label for="sort_order" class="label"><?php echo $lC_Language->get('field_sort_order'); ?></label>'+
                 '        <?php echo  lc_draw_input_field('sort_order', null, 'class="input" id="editEntrySortOrder"'); ?>'+
                 '      </p>'+
                 '    </form>'+
                 '  </div>'+
                 '</div>',
          title: '<?php echo $lC_Language->get('modal_heading_edit_entry'); ?>',
          width: 500,
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
            '<?php echo $lC_Language->get('button_save'); ?>': {
              classes:  'blue-gradient glossy',
              click:    function(win) {
                var bValid = $("#pveEdit").validate({
                rules: {
                  'entry_name[1]': { required: true },
                  sort_order: { digits: true }
                },
                invalidHandler: function() {
                }
              }).form();
              if (bValid) {
                  var nvp = $("#pveEdit").serialize();
                  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '=' . $_GET[$lC_Template->getModule()] . '&action=saveEntry&pveid=PVEID&BATCH'); ?>'
                  $.getJSON(jsonLink.replace('PVEID', parseInt(id)).replace('BATCH', nvp),
                  function (rdata) {
                    if (rdata.rpcStatus == -10) { // no session
                      var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";
                      $(location).attr('href',url);
                    }
                    $("#status-working").fadeOut('slow');
                    if (rdata.rpcStatus != 1) {
                      $.modal.alert('<?php echo $lC_Language->get('ms_error_action_not_performed'); ?>');
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
      $("#editEntryNames").html(data.editEntryNames);
      $("#editEntrySortOrder").val(data.pveData.sort_order);
    }
  );
}
</script>