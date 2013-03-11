<?php
/**
  $Id: profileEdit.js.php v1.0 2012-08-08 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
global $lC_Vqmod;

include_once($lC_Vqmod->modCheck('includes/applications/administrators/classes/administrators.php'));
$groupsArr = lC_Administrators_Admin::getAllGroups(true);
$groupsSelectArr = array();
foreach ($groupsArr as $key => $value) {
  $groupsSelectArr[] = array('id' => $value['id'], 'text' => $value['name']);
}
?>
function profileEdit(id) {
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
          content: '<div id="profileEdit" style="padding-bottom:10px;">'+
                   '  <div id="profileEditForm">'+
                   '    <form name="pEdit" id="pEdit" autocomplete="off" action="" method="post" enctype="multipart/form-data">'+
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
                   '      <p class="button-height inline-label" id="pImage">'+
                   '        <label for="profile_image" class="label"><?php echo $lC_Language->get('profile_image'); ?></label>'+
                   '        <img alt="<?php echo $lC_Language->get('profile_image'); ?>" />'+
                   '        <input type="hidden" name="avatar" id="generalAvatar" />'+
                   '      </p>'+
                   '      <p class="inline-label small-margin-top" id="profileUploaderContainerGeneral">'+ 
                   '        <noscript>'+
                   '          <p><?php echo $lC_Language->get('ms_error_javascript_not_enabled_for_upload'); ?></p>'+
                   '        </noscript>'+
                   '      </p>'+
                   '      <input type="hidden" id="edit-access_group_id" name="access_group_id" />'+
                   '    </form>'+
                   '  </div>'+
                   '</div>',
          title: '<?php echo $lC_Language->get('modal_heading_profile_edit'); ?>',
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

              var bValid = $("#pEdit").validate({
                rules: {
                  first_name: { required: true },
                  last_name: { required: true },
                  user_name: { required: true }
                },
                invalidHandler: function() {
                }
              }).form();
              if (bValid) {
                  var nvp = $("#pEdit").serialize();
                  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', 'administrators&action=saveAdmin&aid=AID&BATCH'); ?>'
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
      $('#pImage').children('img').attr("src", "<?php echo DIR_WS_IMAGES; ?>avatar/" + data.image).attr("width", 64).attr("height", 64).attr("name", "avatar").attr("id", "avatar");
      $('#edit-access_group_id').val(data.access_group_id);
      function createProfileUploaderGeneral(){
        var uploader = new qq.FileUploader({
          element: document.getElementById('profileUploaderContainerGeneral'),
          action: '<?php echo lc_href_link_admin('rpc.php', 'administrators=' . $_SESSION['admin']['id'] . '&action=fileUpload'); ?>',
          onComplete: function(id, fileName, responseJSON){
            $('#generalAvatar').attr("value", fileName);
            $('#pImage').children('img').attr("src", "<?php echo DIR_WS_IMAGES; ?>avatar/" + fileName).attr("width", 64).attr("height", 64);
            $('#profileLeft').children('img').attr("src", "<?php echo DIR_WS_IMAGES; ?>avatar/" + fileName);
            $('.profile-right-fourth').children('img').attr("src", "<?php echo DIR_WS_IMAGES; ?>avatar/" + fileName);
          },
        });
      }
      createProfileUploaderGeneral();
    }
  );
}