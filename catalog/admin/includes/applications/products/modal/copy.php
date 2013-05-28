<?php
/*
  $Id: edit.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
?>
<style>
#copyProduct { padding-bottom:20px; }
</style>
<script>
function copyProduct(id, name) {
  var accessLevel = '<?php echo $_SESSION['admin']['access'][$lC_Template->getModule()]; ?>';
  if (parseInt(accessLevel) < 3) {
    $.modal.alert('<?php echo $lC_Language->get('ms_error_no_access');?>');
    return false;
  }
  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '=' . $_GET[$lC_Template->getModule()] . '&action=getProductFormData&pid=PID'); ?>'
  $.getJSON(jsonLink.replace('PID', parseInt(id)),
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
          content: '<div id="copyProduct">'+
                   '  <div id="copyProductForm">'+
                   '    <form name="pCopy" id="pCopy" action="" method="post" enctype="multipart/form-data">'+
                   '      <p><?php echo $lC_Language->get('introduction_copy_product'); ?></p>'+
                   '      <p class="button-height inline-label">'+
                   '        <label for="category_id" class="label" style="width:40%"><?php echo $lC_Language->get('field_current_categories'); ?></label>'+
                   '        <span id="categoryPath"></span>'+
                   '      </p>'+
                   '      <p class="button-height inline-label">'+
                   '        <label for="new_category_id" class="label" style="width:40%"><?php echo $lC_Language->get('field_copy_to_category'); ?></label>'+
                   '        <?php echo lc_draw_pull_down_menu('new_category_id', null, null, 'class="select" style="width:73%;"'); ?>'+
                   '      </p>'+
                   '      <p class="button-height inline-label">'+
                   '        <label for="copy_as" class="label" style="width:40%"><?php echo $lC_Language->get('field_copy_method'); ?></label>'+
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
          title: '<?php echo $lC_Language->get('modal_heading_copy_product'); ?>',
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
            '<?php echo $lC_Language->get('button_copy'); ?>': {
              classes:  'blue-gradient glossy',
              click:    function(win) {
                var bValid = $("#pCopy").validate({
                  rules: {
                    new_category_id: { required: true }
                  },
                  invalidHandler: function() {
                  }
                }).form();
                if (bValid) {
                  var nvp = $("#pCopy").serialize();
                  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=copyProduct&pid=PID&BATCH'); ?>'
                  $.getJSON(jsonLink.replace('PID', parseInt(id)).replace('BATCH', nvp),
                    function (rdata) {
                      if (rdata.rpcStatus == -10) { // no session
                        window.location = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";
                      }
                      if (rdata.rpcStatus != 1) {
                        alert('<?php echo $lC_Language->get('ms_error_action_not_performed'); ?>');
                        return false;
                      }
                      updateProductFilter();
                    }
                  );
                  win.closeModal();
                }
              }
            }
          },
          buttonsLowPadding: true
      });
      $("#categoryPath").html(data.categoryPath);
      $("#new_category_id").empty();  // clear the old values
      i=0;
      $.each(data.categoriesArray, function(val, text) {
        if(i == 0) {
          $("#new_category_id").next("span.select-value:first").text(text);
          i++;
        }
        $("#new_category_id").append(
           $("<option></option>").val(val).html(text)
        );
      });
    }
  );
}
</script>