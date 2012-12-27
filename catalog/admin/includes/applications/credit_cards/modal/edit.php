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
<script>
function editCard(id) {
  var accessLevel = '<?php echo $_SESSION['admin']['access'][$lC_Template->getModule()]; ?>';
  if (parseInt(accessLevel) < 3) {
    $.modal.alert('<?php echo $lC_Language->get('ms_error_no_access');?>');
    return false;
  }
  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=getFormData&ccid=CCID'); ?>'
  $.getJSON(jsonLink.replace('CCID', parseInt(id)),
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
          content: '<div id="editCard">'+
                   '  <div id="editCardForm">'+
                   '    <form name="ccEdit" id="ccEdit" autocomplete="off" action="" method="post">'+
                   '      <p><?php echo $lC_Language->get('introduction_edit_card'); ?></p>'+
                   '      <p class="button-height inline-label">'+
                   '        <label for="credit_card_name" class="label"><?php echo $lC_Language->get('field_name'); ?></label>'+
                   '        <?php echo lc_draw_input_field('credit_card_name', null, 'class="input full-width" id="editCreditCardName"'); ?>'+
                   '      </p>'+
                   '      <p class="button-height inline-label">'+
                   '        <label for="pattern" class="label"><?php echo $lC_Language->get('field_pattern'); ?></label>'+
                   '        <?php echo lc_draw_input_field('pattern', null, 'class="input full-width" id="editPattern"'); ?>'+
                   '      </p>'+
                   '      <p class="button-height inline-label">'+
                   '        <label for="sort_order" class="label"><?php echo $lC_Language->get('field_sort_order'); ?></label>'+
                   '        <?php echo lc_draw_input_field('sort_order', null, 'class="input full-width" id="editSortOrder"'); ?>'+
                   '      </p>'+
                   '      <p class="button-height inline-label">'+
                   '        <label for="credit_card_status" class="label"><?php echo $lC_Language->get('field_active'); ?></label>'+
                   '        <?php echo lc_draw_checkbox_field('credit_card_status', '1', null, 'id="editCreditCardStatus" class="switch medium" data-text-on="' . strtoupper($lC_Language->get('button_yes')) . '" data-text-off="' . strtoupper($lC_Language->get('button_no')) . '"'); ?>'+
                   '      </p>'+
                   '    </form>'+
                   '  </div>'+
                   '</div>',
          title: '<?php echo $lC_Language->get('modal_heading_edit_card'); ?>',
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
                var bValid = $("#ccEdit").validate({
                rules: {
                  credit_card_name: { required: true },
                  pattern: { required: true }
                },
                invalidHandler: function() {
                }
              }).form();
              if (bValid) {
                  var nvp = $("#ccEdit").serialize();
                  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=saveCard&ccid=CCID&BATCH'); ?>'
                  $.getJSON(jsonLink.replace('CCID', parseInt(id)).replace('BATCH', nvp),
                    function (data) {
                      if (data.rpcStatus == -10) { // no session
                        var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";
                        $(location).attr('href',url);
                      }
                      if (data.rpcStatus != 1) {
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
      $("#editCreditCardName").val(data.ccData.credit_card_name);
      $("#editPattern").val(data.ccData.pattern);
      $("#editSortOrder").val(data.ccData.sort_order);
      if (data.ccData.credit_card_status == 1) {
        $("#editCreditCardStatus").attr('checked', true).change();
      } else {
        $("#editCreditCardStatus").attr('checked', false).change();
      }
    }
  );
}
</script>