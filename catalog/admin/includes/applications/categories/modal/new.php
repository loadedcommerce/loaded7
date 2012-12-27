<?php
/*
  $Id: new.php v1.0 2011-11-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
?>
<style>
#newCategory { padding-bottom:20px; }
</style>
<script>
function newCategory() {
  var accessLevel = '<?php echo $_SESSION['admin']['access'][$lC_Template->getModule()]; ?>';
  if (parseInt(accessLevel) < 2) {
    $.modal.alert('<?php echo $lC_Language->get('ms_error_no_access');?>');
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
          content: '<div id="newCategory">'+
                   '  <div id="newCategoryForm">'+
                   '    <form name="cNew" id="cNew" action="<?php echo lc_href_link_admin(FILENAME_DEFAULT, $lC_Template->getModule() . '=' . $_GET[$lC_Template->getModule()] . '&action=save'); ?>" method="post" enctype="multipart/form-data">'+
                   '      <p><?php echo $lC_Language->get('introduction_new_category'); ?></p>'+
                   '      <p class="button-height inline-label">'+
                   '        <label for="parent_id" class="label" style="width:33%;"><?php echo $lC_Language->get('field_parent_category'); ?></label>'+
                   '        <?php echo lc_draw_pull_down_menu('parent_id', null, null, 'class="input with-small-padding"'); ?>'+
                   '      </p>'+
                   '      <p class="button-height inline-label">'+
                   '        <label for="categories_name" class="label" style="width:33%;"><?php echo $lC_Language->get('field_name'); ?></label>'+
                   '        <span id="categoryNames"></span>'+
                   '      </p>'+
                   '      <p class="button-height inline-label">'+
                   '        <label for="categories_image" class="label" style="width:33%;"><?php echo $lC_Language->get('field_image'); ?></label>'+
                   '        <?php echo lc_draw_file_field('categories_image', true, 'class="file"'); ?>'+
                   '      </p>'+
                   '      <p class="button-height inline-label">'+
                   '        <label for="sort_order" class="label" style="width:33%;"><?php echo $lC_Language->get('field_sort_order'); ?></label>'+
                   '        <?php echo lc_draw_input_field('sort_order', null, 'class="input"'); ?>'+
                   '      </p>'+
                   '    </form>'+
                   '  </div>'+
                   '</div>',
          title: '<?php echo $lC_Language->get('modal_heading_new_category'); ?>',
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
                var bValid = $("#cNew").validate({
                  rules: {
                    parent_id: { required: true },
                    'categories_name[1]': { required: true },
                    sort_order: { number: true }
                  },
                  invalidHandler: function() {
                  }
                }).form();
                if (bValid) {
                  $("#cNew").submit();
                  win.closeModal();
                }
              }
            }
          },
          buttonsLowPadding: true
      });
      $("#parent_id").empty();  // clear the old values
      $.each(data.categoriesArray, function(val, text) {
        var selected = (data.parentCategory == val) ? 'selected="selected"' : '';
        $("#parent_id").append(
           $("<option " + selected + "></option>").val(val).html(text)
        );
      });
      $("#categoryNames").html(data.categoryNames);
    }
  );
}
</script>
