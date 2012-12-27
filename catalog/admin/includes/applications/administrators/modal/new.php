<?php
/*
  $Id: new.php v1.0 2011-11-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
$groupsArr = lC_Administrators_Admin::getAllGroups(true);
$groupsSelectArr = array();
foreach ($groupsArr as $jey => $value) {
  $groupsSelectArr[] = array('id' => $value['id'], 'text' => $value['name']);
}
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
              user_name: { required: true },
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
}
</script>