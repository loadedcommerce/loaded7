<?php
/*
  $Id: edit.php v1.0 2011-11-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
?>
<style>
#editEntry { padding-bottom:20px; }
</style>
<script>
function editEntry(id) {
  var accessLevel = '<?php echo $_SESSION['admin']['access'][$lC_Template->getModule()]; ?>';
  if (parseInt(accessLevel) < 3) {
    $.modal.alert('<?php echo $lC_Language->get('ms_error_no_access');?>');
    return false;
  }
  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=getEntryFormData&trid=TRID'); ?>'
  $.getJSON(jsonLink.replace('TRID', parseInt(id)),
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
          content: '<div id="editEntry">'+
                 '  <div id="editEntryForm">'+
                 '    <form name="trEdit" id="trEdit" autocomplete="off" action="" method="post">'+
                 '      <p><?php echo $lC_Language->get('introduction_new_tax_rate'); ?></p>'+
                 '      <p class="button-height inline-label">'+
                 '        <label for="tax_zone_id" class="label"><?php echo $lC_Language->get('field_tax_rate_zone_group'); ?></label>'+
                 '        <?php echo lc_draw_pull_down_menu('tax_zone_id', null, null, 'class="input with-small-padding" id="editTaxZoneId"'); ?>'+
                 '      </p>'+
                 '      <p class="button-height inline-label">'+
                 '        <label for="tax_description" class="label"><?php echo $lC_Language->get('field_tax_rate_description'); ?></label>'+
                 '        <?php echo  lc_draw_input_field('tax_description', null, 'class="input input full-width" id="editTaxDescription"'); ?>'+
                 '      </p>'+
                 '      <p class="button-height inline-label">'+
                 '        <label for="tax_rate" class="label"><?php echo $lC_Language->get('field_tax_rate'); ?></label>'+
                 '        <?php echo  lc_draw_input_field('tax_rate', null, 'class="input input full-width" id="editTaxRate"'); ?>'+
                 '      </p>'+
                 '      <p class="button-height inline-label">'+
                 '        <label for="tax_priority" class="label"><?php echo $lC_Language->get('field_tax_rate_priority'); ?></label>'+
                 '        <?php echo  lc_draw_input_field('tax_priority', null, 'class="input input full-width" id="editTaxPriority"'); ?>'+
                 '      </p>'+
                 '    </form>'+
                 '  </div>'+
                 '</div>',
          title: '<?php echo $lC_Language->get('modal_heading_edit_tax_rate'); ?>',
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
                var bValid = $("#trEdit").validate({
                rules: {
                  tax_zone_id: { required: true },
                  tax_description: { required: true },
                  tax_rate: { required: true, number: true },
                  tax_priority: { required: true, number: true }
                },
                invalidHandler: function() {
                }
              }).form();
              if (bValid) {
                  var nvp = $("#trEdit").serialize();
                  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=saveEntry&tax_class_id=' . $_GET[$lC_Template->getModule()] . '&trid=TRID&BATCH'); ?>'
                  $.getJSON(jsonLink.replace('TRID', id).replace('BATCH', nvp),
                    function (rdata) {
                      if (rdata.rpcStatus == -10) { // no session
                        var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";
                        $(location).attr('href',url);
                      }
                      if (rdata.rpcStatus != 1) {
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
      $("#editTaxZoneId").empty();
      $.each(data.zonesArray, function(val, text) {
        var selected = (data.editFormData.tax_zone_id == val) ? 'selected="selected"' : '';
        $("#editTaxZoneId").append(
          $('<option ' + selected + '></option>').val(val).html(text)
        );
      });
      $("#editTaxDescription").val(data.editFormData.tax_description);
      $("#editTaxRate").val(data.editFormData.tax_rate);
      $("#editTaxPriority").val(data.editFormData.tax_priority);
    }
  );
}
</script>