<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: batchCopy.php v1.0 2013-08-08 datazen $
*/
?>
<style>
#batchCopy { padding-bottom:20px; }
</style>
<script>
function batchCopy(id) {
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
    $.modal.alert('<?php echo $lC_Language->get('ms_error_nothing_to_move');?>');
    $(".select option:first").attr('selected','selected');
    return false;
  }
  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '=' . $_GET[$lC_Template->getModule()] . '&action=getProductFormData'); ?>'
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
        content: '<div id="batchCopy">'+
                 '  <div id="batchCopyConfirm">'+
                 '    <form name="pbCopy" id="pbCopy" action="" method="post" enctype="multipart/form-data">'+
                 '      <p><?php echo $lC_Language->get('introduction_batch_copy_products'); ?></p>'+
                 '      <p class="button-height inline-label">'+
                 '        <label for="new_category_id" class="label"><?php echo $lC_Language->get('field_copy_to_category'); ?></label>'+
                 '        <?php echo lc_draw_pull_down_menu('new_category_id', null, null, 'class="input with-small-padding" style="width:77%;" id="copyNewCategoryId"'); ?>'+
                 '      </p>'+
                 '      <p class="button-height inline-label">'+
                 '        <label for="copy_as" class="label"><?php echo $lC_Language->get('field_copy_method'); ?></label>'+
                 '        <span class="button-group">'+
                 '          <label for="copy_as_1" class="button green-active">'+
                 '            <input type="radio" name="copy_as" id="copy_as_1" value="link" checked><?php echo $lC_Language->get('copy_method_link'); ?>'+
                 '          </label>'+
                 '          <label for="copy_as_2" class="button green-active">'+
                 '            <input type="radio" name="copy_as" id="copy_as_2" value="duplicate"><?php echo $lC_Language->get('copy_method_duplicate'); ?>'+
                 '          </label>'+
                 '        </span>'+
                 '      </p>'+
                 '    </form>'+
                 '  </div>'+
                 '</div>',
        title: '<?php echo $lC_Language->get('modal_heading_batch_copy_products'); ?>',
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
          '<?php echo $lC_Language->get('button_move'); ?>': {
            classes:  'blue-gradient glossy',
            click:    function(win) {
              var nvp = $("#pbCopy").serialize();
              var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=batchCopy&NVP&BATCH'); ?>'
              $.getJSON(jsonLink.replace('NVP', nvp).replace('BATCH', values),
                function (rdata) {
                  if (rdata.rpcStatus == -10) { // no session
                    window.location = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";
                  }
                  if (rdata.rpcStatus != 1) {
                    $.modal.alert('<?php echo $lC_Language->get('ms_error_action_not_performed'); ?>');
                    return false;
                  }
                  updateProductFilter();
                }
              );
              win.closeModal();
            }
          }
        },
        buttonsLowPadding: true
      });
      $("#copyNewCategoryId").empty();  // clear the old values
      $.each(data.categoriesArray, function(val, text) {
        $("#copyNewCategoryId").append(
           $("<option></option>").val(val).html(text)
        );
      });
    }
  );
}
</script>