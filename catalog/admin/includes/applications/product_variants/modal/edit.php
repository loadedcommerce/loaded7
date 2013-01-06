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
#editGroup { padding-bottom:20px; }
</style>
<script>
function editGroup(id) {
  var accessLevel = '<?php echo $_SESSION['admin']['access'][$lC_Template->getModule()]; ?>';
  if (parseInt(accessLevel) < 3) {
    $.modal.alert('<?php echo $lC_Language->get('ms_error_no_access');?>');
    return false;
  }
  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=getFormData&pvid=PVID'); ?>'
  $.getJSON(jsonLink.replace('PVID', parseInt(id)),
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
          content: '<div id="editGroup">'+
                   '  <div id="editGroupForm">'+
                   '    <form name="pvEdit" id="pvEdit" autocomplete="off" action="" method="post">'+
                   '      <p><?php echo $lC_Language->get('introduction_edit_variant_group'); ?></p>'+
                   '      <p class="button-height inline-label">'+
                   '        <label for="products_id" class="label" style="width:30%;"><?php echo $lC_Language->get('field_name'); ?></label>'+
                   '        <span id="editGroupNames"></span>'+
                   '      </p>'+
                   '      <p class="button-height inline-label">'+
                   '        <label for="module" class="label" style="width:30%;"><?php echo $lC_Language->get('field_display_module'); ?></label>'+
                   '        <?php echo lc_draw_pull_down_menu('module', null, null, 'class="input with-small-padding" style=width:73%;" id="editModule"'); ?>'+
                   '      </p>'+
                   '      <p class="button-height inline-label">'+
                   '        <label for="sort_order" class="label" style="width:30%;"><?php echo $lC_Language->get('field_sort_order'); ?></label>'+
                   '        <?php echo lc_draw_input_field('sort_order', null, 'class="input" id="editSortOrder"'); ?>'+
                   '      </p>'+
                   '    </form>'+
                   '  </div>'+
                   '</div>',
          title: '<?php echo $lC_Language->get('modal_heading_edit_variant_group'); ?>',
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
                var bValid = $("#pvEdit").validate({
                  rules: {
                    'group_name[1]': { required: true },
                    zone_description: { required: true },
                    sort_order: { digits: true }
                  },
                  invalidHandler: function() {
                  }
                }).form();
                if (bValid) {
                  var nvp = $("#pvEdit").serialize();
                  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=saveGroup&pvid=PVID&BATCH'); ?>'
                  $.getJSON(jsonLink.replace('PVID', parseInt(id)).replace('BATCH', nvp),
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
            }
          },
          buttonsLowPadding: true
      });
      $("#editGroupNames").html(data.editGroupNames);
      $("#editModule").empty();  // clear the old values
      $.each(data.modulesArray, function(val, text) {
        var selected = (data.pvData.module == val) ? 'selected="selected"' : '';
        $("#editModule").append(
          $("<option " + selected + "></option>").val(val).html(text)
        );
      });
      $("#editSortOrder").val(data.pvData.sort_order);
    }
  );
}
</script>