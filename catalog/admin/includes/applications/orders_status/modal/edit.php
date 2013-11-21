<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: edit.php v1.0 2013-08-08 datazen $
*/
$typesSelectArr[] = array('id' => 'Pending', 'text' => "Pending");
$typesSelectArr[] = array('id' => 'Approved', 'text' => "Approved");
$typesSelectArr[] = array('id' => 'Rejected', 'text' => "Rejected");
?>
<style>
#editStatus { padding-bottom:20px; }
</style>
<script>
function editStatus(id) {
  var defaultId = '<?php echo DEFAULT_ORDERS_STATUS_ID; ?>';
  var accessLevel = '<?php echo $_SESSION['admin']['access'][$lC_Template->getModule()]; ?>';
  if (parseInt(accessLevel) < 3) {
    $.modal.alert('<?php echo $lC_Language->get('ms_error_no_access');?>');
    return false;
  }
  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=getFormData&osid=OSID&edit=true'); ?>'
  $.getJSON(jsonLink.replace('OSID', parseInt(id)),
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
          content: '<div id="editStatus">'+
                   '  <div id="editStatusForm">'+
                   '    <form name="osEdit" id="osEdit" autocomplete="off" action="" method="post">'+
                   '      <p><?php echo $lC_Language->get('introduction_edit_order_status'); ?></p>'+
                   '      <p class="button-height inline-label">'+
                   '        <label for="name" class="label"><?php echo $lC_Language->get('field_name'); ?></label>'+
                   '        <span id="editStatusNamesContainer"></span>'+
                   '      </p>'+
                   '      <p class="button-height inline-label">'+
                   '        <label for="type" class="label"><?php echo $lC_Language->get('field_type'); ?></label>'+
                   '        <?php echo lc_draw_pull_down_menu('type', $typesSelectArr, null, 'id="status_type_id" class="select check-list replacement" style="min-width:200px;"'); ?>'+
                   '      </p>'+
                   '      <p class="button-height inline-label" id="editOrdersStatusDefault"></p>'+
                   '    </form>'+
                   '  </div>'+
                   '</div>',
          title: '<?php echo $lC_Language->get('modal_heading_edit_order_status'); ?>',
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
                var bValid = $("#osEdit").validate({
                  rules: {
                    name: { required: true }
                  },
                  invalidHandler: function() {
                  }
                }).form();
                if (bValid) {
                  var nvp = $("#osEdit").serialize();
                  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=saveStatus&osid=OSID&BATCH'); ?>'
                  $.getJSON(jsonLink.replace('OSID', parseInt(id)).replace('BATCH', nvp),
                    function (rdata) {
                      if (rdata.rpcStatus == -10) { // no session
                        var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";
                        $(location).attr('href',url);
                      }
                      if (rdata.rpcStatus != 1) {
                        $.modal.alert('<?php echo $lC_Language->get('ms_error_action_not_performed'); ?>');
                        return false;
                      }
                      if (nvp.indexOf("default=on") != -1) {
                        // because default is a constant, we need to refresh the page to pick up the value if checked
                        window.location.href = window.location.href;
                      } else {
                        oTable.fnReloadAjax();
                      }                    }
                  );
                  win.closeModal();
                }
              }
            }
          },
          buttonsLowPadding: true
      });
      $("#editStatusNamesContainer").html(data.editNames);
      $('#status_type_id').val(data.editType).change();
      $("#editOrdersStatusTable > tfoot").empty();
      if ( id != defaultId ) {
        $("#editOrdersStatusDefault").html('<label for="default" class="label"><?php echo $lC_Language->get('field_set_as_default'); ?></label><?php echo '&nbsp;' . lc_draw_checkbox_field('default', null, null, 'class="switch medium" data-text-on="' . strtoupper($lC_Language->get('button_yes')) . '" data-text-off="' . strtoupper($lC_Language->get('button_no')) . '"');?>');
      }
    }
  );
}
</script>