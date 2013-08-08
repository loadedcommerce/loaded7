<?php
/*
  $Id: new.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
$groupsArr = lC_Administrators_Admin::getAllGroups(true);
$groupsSelectArr = array();
foreach ($groupsArr as $jey => $value) {
  $groupsSelectArr[] = array('id' => $value['id'], 'text' => $value['name']);
}

include_once($lC_Vqmod->modCheck('includes/applications/languages/classes/languages.php'));

$languagesArr = lC_Languages_Admin::getIdNameArray();
$languagesSelectArr = array();
foreach ($languagesArr as $key => $value) {
  $languagesSelectArr[] = array('id' => $value['languages_id'], 'text' => $value['name']);
}
$data = lC_Administrators_Admin::getData($_SESSION['admin']['id']);
$admin_language_default = $data['language_id'];
?>
<style>
#newAdmin { padding-bottom:20px; }
</style>
<script>
function newAdmin() {
  var accessLevel = '<?php echo $_SESSION['admin']['access'][$lC_Template->getModule()]; ?>';
  if (parseInt(accessLevel) < 2) {
    $.modal.alert('<?php echo $lC_Language->get('ms_error_no_access');?>');
    return false;
  }
  $.modal({
      content: '<div id="newAdmin">'+
               '  <div id="newAdminForm">'+
               '    <form name="aNew" id="aNew" autocomplete="off" action="" method="post">'+
               '      <p><?php echo $lC_Language->get('introduction_new_administrator'); ?></p>'+
               '      <p class="button-height inline-label">'+
               '        <label for="first_name" class="label"><?php echo $lC_Language->get('field_first_name'); ?></label>'+
               '        <?php echo lc_draw_input_field('first_name', null, 'class="input full-width"'); ?>'+
               '      </p>'+
               '      <p class="button-height inline-label">'+
               '        <label for="last_name" class="label"><?php echo $lC_Language->get('field_last_name'); ?></label>'+
               '        <?php echo lc_draw_input_field('last_name', null, 'class="input full-width"'); ?>'+
               '      </p>'+
               '      <p class="button-height inline-label">'+
               '        <label for="user_name" class="label"><?php echo $lC_Language->get('field_username'); ?></label>'+
               '        <?php echo lc_draw_input_field('user_name', null, 'class="input full-width"'); ?>'+
               '      </p>'+
               '      <p class="button-height inline-label">'+
               '        <label for="user_password" class="label"><?php echo $lC_Language->get('field_password'); ?></label>'+
               '        <?php echo lc_draw_password_field('user_password', 'class="input full-width"'); ?>'+
               '      </p>'+

               '      <p class="button-height inline-label">'+
               '        <label for="language_id" class="label"><?php echo $lC_Language->get('field_admin_language'); ?></label>'+
               '        <?php  echo lc_draw_pull_down_menu('language_id', $languagesSelectArr, $admin_language_default, 'id="edit-language_id" class="select" style="min-width:200px;"'); ?>'+
               '      </p>'+

               '      <p class="button-height inline-label" id="pImage">'+
               '        <label for="profile_image" class="label"><?php echo $lC_Language->get('profile_image'); ?></label>'+
               '        <img alt="<?php echo $lC_Language->get('profile_image'); ?>" />'+
               '        <input type="hidden" name="avatar" id="newAvatar" />'+
               '      </p>'+
               '      <p class="inline-label small-margin-top" id="profileUploaderContainerNew">'+ 
               '        <noscript>'+
               '          <p><?php echo $lC_Language->get('ms_error_javascript_not_enabled_for_upload'); ?></p>'+
               '        </noscript>'+
               '      </p>'+
               '      <p class="button-height inline-label">'+
               '        <label for="access_group_id" class="label"><?php echo $lC_Language->get('field_access_group'); ?></label>'+
               '        <?php echo lc_draw_pull_down_menu('access_group_id', $groupsSelectArr, null, 'class="select blue-gradient check-list replacement"'); ?>'+
               '      </p>'+
               '    </form>'+
               '  </div>'+
               '</div>',
      title: '<?php echo $lC_Language->get('modal_heading_new_administrator'); ?>',
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
          var bValid = $("#aNew").validate({
            rules: {
              first_name: { required: true },
              last_name: { required: true },
              user_name: { required: true, email: true },
              user_password: { required: true }
            },
            invalidHandler: function() {
            }
          }).form();
          if (bValid) {
              var nvp = $("#aNew").serialize();
              var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=saveAdmin&BATCH'); ?>'
              $.getJSON(jsonLink.replace('BATCH', nvp),
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
  function createProfileUploaderNew(){
    var uploader = new qq.FileUploader({
      element: document.getElementById('profileUploaderContainerNew'),
      action: '<?php echo lc_href_link_admin('rpc.php', 'administrators=' . $_SESSION['admin']['id'] . '&action=fileUpload'); ?>',
      onComplete: function(id, fileName, responseJSON){
        $('#newAvatar').attr("value", fileName);
        $('#pImage').children('img').attr("src", "<?php echo DIR_WS_IMAGES; ?>avatar/" + fileName).attr("width", 64).attr("height", 64);
      },
    });
  }
  createProfileUploaderNew(); 
}
</script>