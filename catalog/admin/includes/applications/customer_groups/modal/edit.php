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
?>
<style>
#editGroup { padding-bottom:20px; }
</style>
<script>
function editGroup(id) {
  var defaultId = '<?php echo DEFAULT_CUSTOMERS_GROUP_ID; ?>';
  var accessLevel = '<?php echo $_SESSION['admin']['access'][$lC_Template->getModule()]; ?>';
  if (parseInt(accessLevel) < 3) {
    $.modal.alert('<?php echo $lC_Language->get('ms_error_no_access');?>');
    return false;
  }
  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=getFormData&cgid=CGID&edit=true'); ?>'
  $.getJSON(jsonLink.replace('CGID', parseInt(id)),
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
                   '    <p><?php echo $lC_Language->get('introduction_edit_customer_group'); ?></p>'+
                   '    <fieldset class="fieldset fields-list">'+
                   '    <form name="osEdit" id="osEdit" autocomplete="off" action="" method="post">'+
                   '      <div class="field-block button-height">'+
                   '        <label for="name" class="label anthracite"><?php echo $lC_Language->get('field_name'); ?></label>'+
                   '        <span id="editGroupNamesContainer"></span>'+
                   '      </div>'+
                   '      <div id="editGroupDefaultContainer" class="field-block button-height margin-bottom">'+
                   '        <span id="editGroupDefault"></span>'+
                   '      </div>'+
                   '      <div class="field-drop button-height black-inputs">'+
                   '        <label for="baseline" class="label" style="width:63%;"><?php echo $lC_Language->get('field_baseline_discount'); ?></label>'+
                   '        <div class="inputs' + ((id == '1') ? ' disabled' : '') + '" style="width:28%">'+
                   '          <span class="mid-margin-right float-right strong">%</span><input type="text" name="baseline" class="input-unstyled small-margin-left strong" id="editBaseline" onfocus="this.select();" style="width:50%;"' + ((id == '1') ? ' DISABLED' : '') + '>'+
                   '        </div>'+
                   '      </div>'+
                   '    </form>'+
                   '    </fieldset>'+
                   '  </div>'+
                   '</div>',        
          title: '<?php echo $lC_Language->get('modal_heading_edit_customer_group'); ?>',
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
                  $("#osEdit :input").each(function(index, elm){
                    $(elm).val($(elm).val().replace( /%/g, "#164;" ));
                  });
                  var nvp = $("#osEdit").serialize();
                  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=saveGroup&cgid=CGID&BATCH'); ?>'
                  $.getJSON(jsonLink.replace('CGID', parseInt(id)).replace('BATCH', nvp),
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
      $("#editGroupNamesContainer").html(data.editNames);
      $("#editGroupFormTable > tfoot").empty(); // clear the old values
   //   if ( id != defaultId ) {
   //     $("#editGroupDefault").html('<label for="default" class="label anthracite"><?php echo $lC_Language->get('field_set_as_default'); ?></label><?php echo lc_draw_checkbox_field('default', null, null, 'class="switch medium" data-text-on="' . strtoupper($lC_Language->get('button_yes')) . '" data-text-off="' . strtoupper($lC_Language->get('button_no')) . '"');?>');
   //     $("#editGroupDefaultContainer").addClass('field-block');
   //   } else {
        $("#editGroupDefaultContainer").removeClass('field-block');
   //   }
      $('#editBaseline').val(data.editBaseline.toFixed(2));
    }
  );
}
</script>