<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: batchMove.php v1.0 2013-08-08 datazen $
*/
?>
<style>
#batchMove { padding-bottom:20px; }
</style>
<script>
function batchMove() {
  $("#selectAction option:first").attr('selected','selected');
  $('#check-all').attr('checked', false);
  var accessLevel = '<?php echo $_SESSION['admin']['access'][$lC_Template->getModule()]; ?>';
  if (parseInt(accessLevel) < 4) {
    $.modal.alert('<?php echo $lC_Language->get('ms_error_no_access');?>');
    return false;
  }
  var values = $("#batch").serialize();
  $("#check-all").attr('checked', false);  
  var pattern = /batch/gi;
  if (values.match(pattern) == null) {
    $.modal.alert('<?php echo $lC_Language->get('ms_error_nothing_to_move');?>');
    $(".select option:first").attr('selected','selected');
    return false;
  }
  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '=' . $_GET[$lC_Template->getModule()] . '&action=getFormData'); ?>'
  $.getJSON(jsonLink,
    function (data) {
      if (data.rpcStatus == -10) { // no session
        var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";
        $(location).attr('href',url);
      }
      if (data.rpcStatus != 1) {
        $.modal.alert('<?php echo $lC_Language->get('ms_error_action_not_performed'); ?>');
        return false;
      }
      $.modal({
        content: '<div id="batchMove">'+
                 '  <div id="batchMoveConfirm">'+
                 '    <form name="cbMove" id="cbMove" action="" method="post" enctype="multipart/form-data">'+
                 '      <p><?php echo $lC_Language->get('introduction_batch_move_categories'); ?></p>'+
                 '      <p class="button-height inline-label">'+
                 '        <label for="new_category_id" class="label" style="width:125px;"><?php echo $lC_Language->get('field_parent_category'); ?></label>'+
                 '        <?php echo lc_draw_pull_down_menu('new_category_id', null, null, 'class="input with-small-padding" style="width:93%;" id="moveNewCategoryId"'); ?>'+
                 '      </p>'+
                 '    </form>'+
                 '  </div>'+
                 '</div>',
        title: '<?php echo $lC_Language->get('modal_heading_batch_move_categories'); ?>',
        width: 300,
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
          '<?php echo $lC_Language->get('button_move'); ?>': {
            classes:  'blue-gradient glossy',
            click:    function(win) {
              var nvp = $("#cbMove").serialize();
              var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=batchMove&NVP&BATCH'); ?>'
              $.getJSON(jsonLink.replace('NVP', nvp).replace('BATCH', values),
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
        },
        buttonsLowPadding: true
      });
      $("#moveNewCategoryId").empty();  // clear the old values
      $.each(data.categoriesArray, function(val, text) {
        var selected = (data.parentCategory == val) ? 'selected="selected"' : '';
        if(data.parentCategory == val) {
          $("#moveNewCategoryId").closest("span + *").prevAll("span.select-value:first").text(text);
        }
        $("#moveNewCategoryId").append(
           $("<option " + selected + "></option>").val(val).html(text)
        );
      });
    }
  );
}
</script>