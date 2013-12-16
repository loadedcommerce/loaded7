<?php
/**
  @package    catalog::admin::templates
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: profilePassChange.js.php v1.0 2013-08-08 datazen $
*/
global $lC_Vqmod;

include_once($lC_Vqmod->modCheck('includes/applications/administrators/classes/administrators.php'));
$groupsArr = lC_Administrators_Admin::getAllGroups(true);
$groupsSelectArr = array();
foreach ($groupsArr as $key => $value) {
  $groupsSelectArr[] = array('id' => $value['id'], 'text' => $value['name']);
}
?>
function profilePassChange(id) {
  var accessLevel = '<?php echo $_SESSION['admin']['access']['administrators']; ?>';
  if (parseInt(accessLevel) < 3) {
    $.modal.alert('<?php echo $lC_Language->get('ms_error_no_access');?>');
    return false;
  }
  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', 'administrators&action=getData&aid=AID'); ?>'
  $.getJSON(jsonLink.replace('AID', id),
    function (data) {
      if (data.rpcStatus == -10) { // no session
        var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";
        $(location).attr('href', url);
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
          content: '<div id="profilePassChange" style="padding-bottom:10px;">'+
                   '  <div id="profilePassChangeForm">'+
                   '    <form name="pPassChange" id="pPassChange" autocomplete="off" action="" method="post">'+
                   '      <p><?php echo $lC_Language->get('introduction_profile_pass_change'); ?></p>'+
                   '      <input type="hidden" id="edit-first_name" name="first_name" />'+
                   '      <input type="hidden" id="edit-last_name" name="last_name" />'+
                   '      <input type="hidden" id="edit-user_name" name="user_name" />'+
                   '      <p class="button-height inline-label">'+
                   '        <label for="profile_password" class="label"><?php echo $lC_Language->get('profile_password'); ?></label>'+
                   '        <?php echo lc_draw_password_field('profile_password', 'id="profile_password" class="input full-width"'); ?>'+
                   '      </p>'+
                   '      <p class="button-height inline-label">'+
                   '        <label for="profile_password_new" class="label"><?php echo $lC_Language->get('profile_password_new'); ?></label>'+
                   '        <?php echo lc_draw_password_field('profile_password_new', 'id="profile_password_new" class="input full-width"'); ?>'+
                   '      </p>'+
                   '      <p class="button-height inline-label">'+
                   '        <label for="profile_password_confirm" class="label"><?php echo $lC_Language->get('profile_password_confirm'); ?></label>'+
                   '        <?php echo lc_draw_password_field('profile_password_confirm', 'id="profile_password_confirm" class="input full-width"'); ?>'+
                   '      </p>'+
                   '      <input type="hidden" id="edit-access_group_id" name="access_group_id" />'+
                   '      <input type="hidden" id="encrypted" name="encrypted" />'+
                   '    </form>'+
                   '  </div>'+
                   '</div>',
          title: '<?php echo $lC_Language->get('modal_heading_profile_pass_change'); ?>',
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

              var bValid = $("#pPassChange").validate({
                rules: {
                  profile_password: { required: true },
                  profile_password_new: { required: true },
                  profile_password_confirm: { required: true }
                },
                invalidHandler: function() {
                }
              }).form();
              if (bValid) {
                  var plain = $('#profile_password').val();
                  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', 'administrators&action=validatePassword&encrypted=' . $_SESSION['admin']['password'] . '&plain=PLAIN'); ?>'
                  $.getJSON(jsonLink.replace('PLAIN', plain),
                    function (data) {
                      if (data.rpcStatus == -10) { // no session
                        var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";
                        $(location).attr('href',url);
                      }
                      if (data.rpcStatus != 1) {
                        if (data.rpcStatus == -2 ) {
                          $.modal.alert('<?php echo $lC_Language->get('invalid_current_password'); ?>');
                        } else {                      
                          $.modal.alert('<?php echo $lC_Language->get('ms_error_action_not_performed'); ?>');
                        }
                        return false;
                      }
                    }
                  );              
                  var new_password = $('#profile_password_new').val();
                  var confirm_password = $('#profile_password_confirm').val();
                  if (new_password != confirm_password) {
                    $.modal.alert('<?php echo $lC_Language->get('invalid_new_password_match'); ?>');
                    return false;
                  }
                  var nvp = $("#pPassChange").serialize();
                  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', 'administrators&action=saveAdmin&aid=AID&BATCH&user_password=USERPASS'); ?>'
                  $.getJSON(jsonLink.replace('AID', id).replace('BATCH', nvp).replace('USERPASS', $('#profile_password_confirm').val()),
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
                    }
                  );
                  win.closeModal();
                  modalMessage(<?php echo $lC_Language->get('text_password_updated'); ?>);
                }
              }
            }
          },
          buttonsLowPadding: true
      });
      $('#edit-first_name').val(data.first_name);
      $('#edit-last_name').val(data.last_name);
      $('#edit-user_name').val(data.user_name);
      $('#edit-access_group_id').val(data.access_group_id);
      $('#encrypted').val(data.user_password);
    }
  );
}
