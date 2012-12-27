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
#editSpecial { padding-bottom:10px; }
</style>
<script>
function editSpecial(id) {
  var accessLevel = '<?php echo $_SESSION['admin']['access'][$lC_Template->getModule()]; ?>';
  if (parseInt(accessLevel) < 3) {
    $.modal.alert('<?php echo $lC_Language->get('ms_error_no_access');?>');
    return false;
  }
  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=getEntry&sid=SID'); ?>';
  $.getJSON(jsonLink.replace('SID', id),
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
          content: '<div id="editSpecial">'+
                   '  <div id="editSpecialForm">'+
                   '    <form name="eSpec" id="eSpec" action="" method="post">'+
                   '      <input type="hidden" name="products_id" id="edit_products_id">'+
                   '      <p><?php echo $lC_Language->get('introduction_edit_special'); ?></p>'+
                   '      <p class="button-height inline-label">'+
                   '        <label for="products_name" class="label" style="width:60%;"><?php echo $lC_Language->get('field_product'); ?></label>'+
                   '        <span class="input">'+
                   '          <?php echo lc_draw_input_field('products_name', null, 'class="input-unstyled disabled" id="edit_products_name"'); ?>'+
                   '          <label for="products_name" class="button red-gradient glossy" id="lbl-edit_products_name"></label>'+
                   '        </span>'+
                   '      </p>'+
                   '      <p class="button-height inline-label">'+
                   '        <label for="specials_price" class="label" style="width:60%;"><?php echo $lC_Language->get('field_price_net_percentage'); ?></label>'+
                   '        <?php echo lc_draw_input_field('specials_price', null, 'class="input" onkeyup="updateGrossEdit(event);" id="edit_specials_price"'); ?>'+
                   '      </p>'+
                   '      <p class="button-height inline-label">'+
                   '        <label for="specials_price_gross" class="label" style="width:60%;"><?php echo $lC_Language->get('field_price_gross'); ?></label>'+
                   '        <?php echo lc_draw_input_field('specials_price_gross', null, 'class="input" onkeyup="updateNetEdit(event);" id="edit_specials_price_gross"'); ?>'+
                   '      </p>'+
                   '      <p class="button-height inline-label">'+
                   '        <label for="specials_status" class="label" style="width:60%;"><?php echo $lC_Language->get('field_status'); ?></label>'+
                   '        <?php echo lc_draw_checkbox_field('specials_status', '1', true, 'id="edit_specials_status" class="switch medium" data-text-on="' . strtoupper($lC_Language->get('button_yes')) . '" data-text-off="' . strtoupper($lC_Language->get('button_no')) . '"'); ?>'+
                   '      </p>'+
                   '      <p class="button-height inline-label">'+
                   '        <label for="specials_start_date" class="label" style="width:60%;"><?php echo $lC_Language->get('field_date_start'); ?></label>'+
                   '        <span class="input">'+
                   '          <span class="icon-calendar"></span>'+
                   '          <?php echo lc_draw_input_field('specials_start_date', null, 'class="input-unstyled datepicker" id="edit_specials_start_date"'); ?>'+
                   '        </span>'+
                   '      </p>'+
                   '      <p class="button-height inline-label">'+
                   '        <label for="specials_expires_date" class="label" style="width:60%;"><?php echo $lC_Language->get('field_date_expires'); ?></label>'+
                   '        <span class="input">'+
                   '          <span class="icon-calendar"></span>'+
                   '          <?php echo lc_draw_input_field('specials_expires_date', null, 'class="input-unstyled datepicker" id="edit_specials_expires_date"'); ?>'+
                   '        </span>'+
                   '      </p>'+
                   '    </form>'+
                   '  </div>'+
                   '</div>',
          title: '<?php echo $lC_Language->get('modal_heading_edit_special'); ?>',
          width: 550,
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
                var bValid = $("#eSpec").validate({
                  rules: {
                    products_id: { required: true },
                    specials_price: { required: true, number: true }
                  },
                  invalidHandler: function() {
                  }
                }).form();
                if (bValid) {
                  var nvp = $("#eSpec").serialize();
                  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=saveEntry&sid=SID&BATCH'); ?>'
                  $.getJSON(jsonLink.replace('SID', id).replace('BATCH', nvp),
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
      var pid = data.products_id;
      var link = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=getTaxClass&pid=PID'); ?>'
      var jsonLink = link.replace('PID', pid);
      $.getJSON(jsonLink,
        function (tdata) {
          if (tdata.rpcStatus == -10) { // no session
            var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";
            $(location).attr('href',url);
          }
          if (tdata.rpcStatus != 1) {
            $.modal.alert('<?php echo $lC_Language->get('ms_error_retrieving_data'); ?>');
            return false;
          }
          $("#taxClassRate").html(tdata.taxClassRate);
          updateGrossEdit(false);
        }
      );
      $("#edit_products_id").val(data.products_id);
      $("#edit_products_name").val(data.products_name);
      $("#lbl-edit_products_name").html(data.products_price_formatted);
      $("#edit_specials_price").val(data.specials_new_products_price).focus();
      if (data.status == 1) {
        $("#edit_specials_status").attr('checked', true);
      } else {
        $("#edit_specials_status").attr('checked', false);
      }
      $("#edit_specials_start_date").val(data.start_date_formatted);
      $("#edit_specials_expires_date").val(data.expires_date_formatted);
      $('.datepicker').glDatePicker({ zIndex: 100 });
    }
  );
}
</script>