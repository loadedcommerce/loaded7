<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: export.php v1.0 2013-08-08 datazen $
*/
?>
<script>
function exportLanguage(id) {
  var accessLevel = '<?php echo $_SESSION['admin']['access'][$lC_Template->getModule()]; ?>';
  if (parseInt(accessLevel) < 2) {
    $.modal.alert('<?php echo $lC_Language->get('ms_error_no_access');?>');
    return false;
  }
  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=getExportFormData&lid=LID'); ?>'
  $.getJSON(jsonLink.replace('LID', parseInt(id)),
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
          content: '<div id="exportLanguage">'+
                   '  <div id="exportLanguageForm">'+
                   '    <form name="lExport" id="lExport" autocomplete="off" action="" method="post">'+
                   '      <p><?php echo $lC_Language->get('introduction_export_language'); ?></p>'+
                   '      <p class="button-height">'+
                   '       (<a href="javascript:selectAllFromPullDownMenu(\'groups\');"><u><?php echo $lC_Language->get('select_all'); ?></u></a> | <a href="javascript:resetPullDownMenuSelection(\'groups\');"><u><?php echo $lC_Language->get('select_none'); ?></u></a>)<br /><?php echo lc_draw_pull_down_menu('groups[]', null, null, 'id="groups" size="10" multiple="multiple" class="input" style="width:98%;"'); ?>'+
                   '      </p>'+
                   '      <p class="button-height inline-label">'+
                   '        <label for="default" class="label" style="width:50% !important;"><?php echo $lC_Language->get('field_export_with_data'); ?></label>'+
                   '        <?php echo lc_draw_checkbox_field('include_data', null, null, 'class="switch medium" data-text-on="' . strtoupper($lC_Language->get('button_yes')) . '" data-text-off="' . strtoupper($lC_Language->get('button_no')) . '"'); ?>'+
                   '      </p>'+
                   '    </form>'+
                   '  </div>'+
                   '</div>',
          title: '<?php echo $lC_Language->get('modal_heading_export_language'); ?>',
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
                var bValid = $("#lExport").validate({
                  rules: {
                    language_import: { required: true },
                    import_type: { required: true }
                  },
                  invalidHandler: function() {
                  }
                }).form();
                if (bValid) {
                  var formURL = '<?php echo lc_href_link_admin(FILENAME_DEFAULT, $lC_Template->getModule() . '&lid=LID&action=export'); ?>'
                  $("#lExport").attr('action', formURL.replace('LID', id)).submit();
                }
                win.closeModal();
              }
            }
          },
          buttonsLowPadding: true
      });
      $("#groups").empty();  // clear the old values
      $.each(data.groupsArray, function(val, text) {
        if (val != '') {
          var selected = '';
          if (val.indexOf('account|general|checkout|index|info|order|products|search')) selected = 'selected="selected"';
          $("#groups").append(
            $('<option ' + selected + '></option>').val(val).html(text)
          );
        }
      });
      $('#include_data').attr('checked', true).change();
    }
  );
}
</script>
