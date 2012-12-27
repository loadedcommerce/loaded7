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
$groupsArr = lC_Administrators_Admin::getAllGroups(true);
$groupsSelectArr = array();
foreach ($groupsArr as $key => $value) {
  $groupsSelectArr[] = array('id' => $value['id'], 'text' => $value['name']);
}
?>
<style>
#editAdmin { padding-bottom:20px; }
</style>
<script>
function editAdmin(id) {
  var accessLevel = '<?php echo $_SESSION['admin']['access'][$lC_Template->getModule()]; ?>';
  if (parseInt(accessLevel) < 3) {
    $.modal.alert('<?php echo $lC_Language->get('ms_error_no_access');?>');
    return false;
  }
  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=getData&aid=AID'); ?>'
  $.getJSON(jsonLink.replace('AID', id),
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
      $.modal({
          content: '<div id="editAdmin">'+
                   '  <div id="editAdminForm">'+
                   '    <form name="aEdit" id="aEdit" autocomplete="off" action="" method="post">'+
                   '      <p><?php echo $lC_Language->get('introduction_edit_administrator'); ?></p>'+
                   '      <p class="button-height inline-label">'+
                   '        <label for="first_name" class="label"><?php echo $lC_Language->get('field_first_name'); ?></label>'+
                   '        <?php echo lc_draw_input_field('first_name', null, 'id="edit-first_name" class="input full-width"'); ?>'+
                   '      </p>'+
                   '      <p class="button-height inline-label">'+
                   '        <label for="last_name" class="label"><?php echo $lC_Language->get('field_last_name'); ?></label>'+
                   '        <?php echo lc_draw_input_field('last_name', null, 'id="edit-last_name" class="input full-width"'); ?>'+
                   '      </p>'+
                   '      <p class="button-height inline-label">'+
                   '        <label for="user_name" class="label"><?php echo $lC_Language->get('field_username'); ?></label>'+
                   '        <?php echo lc_draw_input_field('user_name', null, 'id="edit-user_name" class="input full-width"'); ?>'+
                   '      </p>'+
                   '      <p class="button-height inline-label">'+
                   '        <label for="user_password" class="label"><?php echo $lC_Language->get('field_password'); ?></label>'+
                   '        <?php echo lc_draw_password_field('user_password', 'id="edit-user_password" class="input full-width"'); ?>'+
                   '      </p>'+
                   '      <p class="button-height inline-label">'+
                   '        <label for="access_group_id" class="label"><?php echo $lC_Language->get('field_access_group'); ?></label>'+
                   '        <?php echo lc_draw_pull_down_menu('access_group_id', $groupsSelectArr, null, 'id="edit-access_group_id" class="select blue-gradient check-list replacement"'); ?>'+
                   '      </p>'+
                   '    </form>'+
                   '  </div>'+
                   '</div>',
          title: '<?php echo $lC_Language->get('modal_heading_edit_administrator'); ?>',
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

              var bValid = $("#aEdit").validate({
                rules: {
                  first_name: { required: true },
                  last_name: { required: true },
                  user_name: { required: true }
                },
                invalidHandler: function() {
                }
              }).form();
              if (bValid) {
                  var nvp = $("#aEdit").serialize();
                  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=saveAdmin&aid=AID&BATCH'); ?>'
                  $.getJSON(jsonLink.replace('AID', id).replace('BATCH', nvp),
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
      $('#edit-first_name').val(data.first_name);
      $('#edit-last_name').val(data.last_name);
      $('#edit-user_name').val(data.user_name);
      $('#edit-access_group_id').val(data.access_group_id).change();
    }
  );
}
</script>