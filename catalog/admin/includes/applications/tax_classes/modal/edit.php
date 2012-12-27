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
#editClass { padding-bottom:20px; }
</style>
<script>
function editClass(id) {
  var accessLevel = '<?php echo $_SESSION['admin']['access'][$lC_Template->getModule()]; ?>';
  if (parseInt(accessLevel) < 3) {
    $.modal.alert('<?php echo $lC_Language->get('ms_error_no_access');?>');
    return false;
  }
  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=getFormData&tcid=TCID'); ?>';
  $.getJSON(jsonLink.replace('TCID', id),
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
          content: '<div id="editClass">'+
                   '  <div id="editClassForm">'+
                   '    <form name="tcEdit" id="tcEdit" autocomplete="off" action="" method="post">'+
                   '      <p><?php echo $lC_Language->get('introduction_edit_tax_class'); ?></p>'+
                   '      <p class="button-height inline-label">'+
                   '        <label for="tax_class_title" class="label"><?php echo $lC_Language->get('field_title'); ?></label>'+
                   '        <?php echo lc_draw_input_field('tax_class_title', null, 'class="input full-width" id="editTaxClassTitle"'); ?>'+
                   '      </p>'+
                   '      <p class="button-height inline-label">'+
                   '        <label for="tax_class_description" class="label"><?php echo $lC_Language->get('field_description'); ?></label>'+
                   '        <?php echo lc_draw_input_field('tax_class_description', null, 'class="input full-width" id="editTaxClassDescription"'); ?>'+
                   '      </p>'+
                   '    </form>'+
                   '  </div>'+
                   '</div>',
          title: '<?php echo $lC_Language->get('modal_heading_edit_tax_class'); ?>',
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
                var bValid = $("#tcEdit").validate({
                  rules: {
                    tax_class_title: { required: true },
                    tax_class_description: { required: true }
                  },
                  invalidHandler: function() {
                  }
                }).form();
                if (bValid) {
                  var nvp = $("#tcEdit").serialize();
                  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=saveClass&tcid=TCID&BATCH'); ?>'
                  $.getJSON(jsonLink.replace('TCID', id).replace('BATCH', nvp),
                    function (rdata) {
                      if (rdata.rpcStatus == -10) { // no session
                        var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";
                        $(location).attr('href',url);
                      }
                      $("#status-working").fadeOut('slow');
                      if (rdata.rpcStatus != 1) {
                        alert('<?php echo $lC_Language->get('ms_error_action_not_performed'); ?>');
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
      $("#editTaxClassTitle").val(data.tcData.tax_class_title);
      $("#editTaxClassDescription").val(data.tcData.tax_class_description);
    }
  );
}
</script>