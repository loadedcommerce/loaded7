<?php
/*
  $Id: import.php v1.0 2011-11-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
?>
<script>
function importLanguage() {
  var accessLevel = '<?php echo $_SESSION['admin']['access'][$lC_Template->getModule()]; ?>';
  if (parseInt(accessLevel) < 2) {
    $.modal.alert('<?php echo $lC_Language->get('ms_error_no_access');?>');
    return false;
  }
  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=getImportFormData'); ?>'
  $.getJSON(jsonLink,
    function (data) {
      if (data.rpcStatus == -10) { // no session
        var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";
        $(location).attr('href',url);
        exit();
      }
      if (data.rpcStatus != 1) {
        $.modal.alert('<?php echo $lC_Language->get('ms_error_action_not_performed'); ?>');
        return false;
      }

      $.modal({
          content: '<div id="importLanguage">'+
                   '  <div id="importLanguageForm">'+
                   '    <form name="lImport" id="lImport" autocomplete="off" action="" method="post">'+
                   '      <p><?php echo $lC_Language->get('introduction_import_language'); ?></p>'+
                   '      <p class="button-height inline-label">'+
                   '        <label for="language_import" class="label" style="width:0px !important"><?php echo $lC_Language->get('field_language_selection'); ?></label>'+
                   '        <?php echo lc_draw_pull_down_menu('language_import', null, null, 'class="input with-small-padding"'); ?>'+
                   '      </p>'+
                   '      <p class="button-height inline-label">'+
                   '        <label for="import_type" class="absolute-left"><strong><?php echo $lC_Language->get('field_import_type'); ?></strong></label>'+
                   '        <div class="button-height inline-label"><?php echo lc_draw_radio_field('import_type', array(array('id' => 'add', 'text' => $lC_Language->get('only_add_new_records')), array('id' => 'update', 'text' => $lC_Language->get('only_update_existing_records')), array('id' => 'replace', 'text' => $lC_Language->get('replace_all'))), 'add', 'class="switch medium"'); ?></div>'+
                   '      </p>'+
                   '    </form>'+
                   '  </div>'+
                   '</div>',
          title: '<?php echo $lC_Language->get('modal_heading_import_language'); ?>',
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
                var bValid = $("#lImport").validate({
                  rules: {
                    language_import: { required: true },
                    import_type: { required: true }
                  },
                  invalidHandler: function() {
                  }
                }).form();
                if (bValid) {
                  var formURL = '<?php echo lc_href_link_admin(FILENAME_DEFAULT, $lC_Template->getModule() . '&action=import'); ?>'
                  $("#lImport").attr('action', formURL).submit();
                }
                win.closeModal();
              }
            }
          },
          buttonsLowPadding: true
      });
      $("#language_import").empty();
      $.each(data.languagesArray, function(val, text) {
        $("#language_import").append(
          $("<option></option>").val(val).html(text)
        );
      });
    }
  );
}
</script>
