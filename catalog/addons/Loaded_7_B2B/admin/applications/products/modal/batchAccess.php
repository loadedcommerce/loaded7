<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: batchAccess.php v1.0 2013-08-08 datazen $
*/
if (file_exists(DIR_FS_CATALOG . 'addons/Loaded_7_B2B/admin/applications/customer_groups/classes/customer_groups.php')) include_once($lC_Vqmod->modCheck(DIR_FS_CATALOG . 'addons/Loaded_7_B2B/admin/applications/customer_groups/classes/customer_groups.php'));

global $lC_Language, $lC_Template;
?>
<script>
function batchAccess() {
  $("#selectAction option:first").attr('selected','selected');
  $('#check-all').attr('checked', false);
  var accessLevel = '<?php echo $_SESSION['admin']['access'][$lC_Template->getModule()]; ?>';
  if (parseInt(accessLevel) < 4) {
    $.modal.alert('<?php echo $lC_Language->get('ms_error_no_access');?>');
    return false;
  }
  var values = $("#batch").serialize();
  var pattern = /batch/gi;
  if (values.match(pattern) == null) {
    $.modal.alert('<?php echo $lC_Language->get('ms_error_nothing_to_change');?>');
    $(".select option:first").attr('selected','selected');
    return false;
  }
  
  $.modal({
    content: '<div id="batchAccess">'+
             '  <div id="batchAccessConfirm" class="margin-bottom">'+
             '    <form name="batch-access-edit" id="batch-access-edit" method="post">'+
             '      <p><?php echo $lC_Language->get('introduction_batch_edit_access'); ?></p>'+
             '      <p id="batchAccessConfirmMessage"><?php echo lC_Customer_groups_b2b_Admin::getCustomerAccessLevelsHtml('products'); ?></p>'+
             '    </form>'+
             '  </div>'+
             '</div>',
    title: '<?php echo $lC_Language->get('modal_heading_batch_access_levels_override'); ?>',
    width: 400,
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
      '<?php echo $lC_Language->get('button_apply'); ?>': {
        classes:  'blue-gradient glossy',
        click:    function(win) {
          var access = $("#batch-access-edit").serialize();
          var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '=' . $_GET[$lC_Template->getModule()] . '&action=batchEditAccess&addon=Loaded_7_B2B&BATCH&ACCESS'); ?>';
          $.getJSON(jsonLink.replace('BATCH', values).replace('ACCESS', access),
            function (data) {
              if (data.rpcStatus == -10) { // no session
                var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";
                $(location).attr('href',url);
              }
              if (data.rpcStatus != 1) {
                $.modal.alert('<?php echo $lC_Language->get('ms_error_action_not_performed'); ?>');
                return false;
              }
              modalMessage('<?php echo $lC_Language->get('text_access_levels_updated'); ?>');
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
function checkAllLevels(e) {
  var checked = $(e).is(":checked");
  $('.levels').prop('checked', checked);
}

$(document).ready(function() {  
  if ($('.levels:checked').length == $('.levels').length) $('#check_all_levels').prop('checked', true);
});
</script>