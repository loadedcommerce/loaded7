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
#editModule { padding-bottom:20px; }
</style>
<script>
function editModule(id) {
  var accessLevel = '<?php echo $_SESSION['admin']['access'][$lC_Template->getModule()]; ?>';
  if (parseInt(accessLevel) < 2) {
    $.modal.alert('<?php echo $lC_Language->get('ms_error_no_access');?>');
    return false;
  }
  var filter = $("#filter").val();
  if (filter == null) filter = '<?php echo DEFAULT_TEMPLATE; ?>';
  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=getFormData&filter=FILTER&tid=TID&set=' . $_GET['set']); ?>';
  $.getJSON(jsonLink.replace('FILTER', filter).replace('TID', id),
    function (data) {
      if (data.rpcStatus == -10) { // no session
        var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";
        $(location).attr('href',url);
      }
      if (data.rpcStatus != 1) {
        alert('<?php echo $lC_Language->get('ms_error_retrieving_data'); ?>');
        return false;
      }
      $.modal({
          content: '<div id="editModule">'+
                   '  <div id="editModuleForm">'+
                   '    <form name="lEdit" id="lEdit" autocomplete="off" action="" method="post">'+
                   '      <p><?php echo $lC_Language->get('introduction_edit_template_layout_module'); ?></p>'+
                   '      <p class="button-height inline-label">'+
                   '        <label for="box" class="label"><?php echo $lC_Language->get('field_module'); ?></label>'+
                   '        <?php echo lc_draw_pull_down_menu('box', null, null, 'class="input with-small-padding" id="editBox"'); ?>'+
                   '      </p>'+
                   '      <p class="button-height inline-label">'+
                   '        <label for="content_page" class="label"><?php echo $lC_Language->get('field_pages'); ?></label>'+
                   '        <?php echo lc_draw_pull_down_menu('content_page', $pages_array, null, 'class="input with-small-padding" id="editContentPage"'); ?>'+
                   '      </p>'+
                   '      <p class="button-height inline-label">'+
                   '        <label for="page_specific" class="label"><?php echo $lC_Language->get('field_page_specific'); ?></label>'+
                   '        <?php echo lc_draw_checkbox_field('page_specific', null, null, 'class="switch medium" data-text-on="YES" data-text-off="NO" id="editPageSpecific"'); ?>'+
                   '      </p>'+
                   '      <p class="button-height inline-label">'+
                   '        <label for="group" class="label"><?php echo $lC_Language->get('field_group'); ?></label>'+
                   '        <span id="editModuleGroupsDropdown"></span>'+
                   '      </p>'+
                   '      <p class="button-height inline-label">'+
                   '        <label for="group_new" class="label"><?php echo $lC_Language->get('field_group_new'); ?></label>'+
                   '        <?php echo lc_draw_input_field('group_new', null, 'class="input full-width"'); ?>'+
                   '      </p>'+
                   '      <p class="button-height inline-label">'+
                   '        <label for="sort_order" class="label"><?php echo $lC_Language->get('field_sort_order'); ?></label>'+
                   '        <?php echo lc_draw_input_field('sort_order', null, 'class="input full-width" id="editSortOrder"'); ?>'+
                   '      </p>'+
                   '    </form>'+
                   '  </div>'+
                   '</div>',
          title: '<?php echo $lC_Language->get('modal_heading_edit_template_layout_module'); ?>',
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
                var groupSelect = $('#group').val();
                var groupNew = $('#group_new').val();
                if (groupSelect.length == 0 && groupNew.length == 0) {
                  $.modal.alert('<?php echo $lC_Language->get('validation_error_group_cannot_be_blank'); ?>');
                  return false;
                }
                var bValid = $("#lEdit").validate({
                  rules: {
                    sort_order: { number: true }
                  },
                  invalidHandler: function() {
                  }
                }).form();
                if (bValid) {
                  var nvp = $("#lEdit").serialize();
                  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=saveModule&tid=TID&set=' . $_GET['set'] . '&BATCH'); ?>'
                  $.getJSON(jsonLink.replace('TID', id).replace('BATCH', nvp),
                    function (rdata) {
                      if (rdata.rpcStatus == -10) { // no session
                        var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";
                        $(location).attr('href',url);
                      }
                      if (rdata.rpcStatus != 1) {
                        $.modal.alert('<?php echo $lC_Language->get('ms_error_action_not_performed'); ?>');
                        return false;
                      }
                      oTable.fnDraw();
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
      $("#editBox").empty();
      $.each(data.boxes_array, function(val, text) {
        var selected = (data.modules_selected == val) ? 'selected="selected"' : '';
        $("#editBox").append(
          $("<option " + selected + "></option>").val(val).html(text)
        );
      });
      $("#editContentPage").empty();
      $.each(data.pages_array, function(val, text) {
        var selected = (data.pages_selected == text.id) ? 'selected="selected"' : '';
        $("#editContentPage").append(
          $('<option ' + selected + '></option>').val(text.id).html(text.text)
        );
      });
      $("#editModuleGroupsDropdown").html(data.groups_dropdown)
      $("#group").val( data.group_selected ).attr('selected', true);
      if (data.page_specific == true) $("#editPageSpecific").attr('checked', 'checked').change();
      $("#editSortOrder").val(data.sort_order);
    }
  );
}
</script>