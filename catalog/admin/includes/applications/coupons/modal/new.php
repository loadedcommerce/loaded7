<?php
/*
  $Id: new.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
?>
<style>
#newSpecial { padding-bottom:20px; }
</style>
<script>
function newSpecial() {
  var accessLevel = '<?php echo $_SESSION['admin']['access'][$lC_Template->getModule()]; ?>';
  if (parseInt(accessLevel) < 2) {
    $.modal.alert('<?php echo $lC_Language->get('ms_error_no_access');?>');
    return false;
  }
  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=getFormData'); ?>';
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
          content: '<div id="newSpecial">'+
                   '  <div id="newSpecialForm">'+
                   '    <form name="special" id="special" action="<?php echo lc_href_link_admin(FILENAME_DEFAULT, $lC_Template->getModule() . '=' . $_GET[$lC_Template->getModule()] . '&action=save'); ?>" method="post">'+
                   '      <p><?php echo $lC_Language->get('introduction_new_special'); ?></p>'+
                   '      <p class="button-height inline-label">'+
                   '        <label for="products_id" class="label" style="width:50%;"><?php echo $lC_Language->get('field_product'); ?></label>'+
                   '        <?php echo lc_draw_pull_down_menu('products_id', null, null, 'class="select" onchange="getTaxClass();" style=width:73%;"'); ?>'+
                   '      </p>'+
                   '      <p class="button-height inline-label">'+
                   '        <label for="specials_price" class="label" style="width:50%;"><?php echo $lC_Language->get('field_price_net_percentage'); ?></label>'+
                   '        <?php echo lc_draw_input_field('specials_price', null, 'class="input" onkeyup="updateGross(event);"'); ?>'+
                   '      </p>'+
                   '      <p class="button-height inline-label">'+
                   '        <label for="specials_price_gross" class="label" style="width:50%;"><?php echo $lC_Language->get('field_price_gross'); ?></label>'+
                   '        <?php echo lc_draw_input_field('specials_price_gross', null, 'class="input" onkeyup="updateNet(event);"'); ?>'+
                   '      </p>'+
                   '      <p class="button-height inline-label">'+
                   '        <label for="specials_status" class="label" style="width:50%;"><?php echo $lC_Language->get('field_status'); ?></label>'+
                   '        <?php echo lc_draw_checkbox_field('specials_status', '1', true, 'class="switch medium" data-text-on="' . strtoupper($lC_Language->get('button_yes')) . '" data-text-off="' . strtoupper($lC_Language->get('button_no')) . '"'); ?>'+
                   '      </p>'+
                   '      <p class="button-height inline-label">'+
                   '        <label for="specials_start_date" class="label" style="width:50%;"><?php echo $lC_Language->get('field_date_start'); ?></label>'+
                   '        <span class="input">'+
                   '          <span class="icon-calendar"></span>'+
                   '          <?php echo lc_draw_input_field('specials_start_date', null, 'class="input-unstyled datepicker"'); ?>'+
                   '        </span>'+
                   '      </p>'+
                   '      <p class="button-height inline-label">'+
                   '        <label for="specials_expires_date" class="label" style="width:50%;"><?php echo $lC_Language->get('field_date_expires'); ?></label>'+
                   '        <span class="input">'+
                   '          <span class="icon-calendar"></span>'+
                   '          <?php echo lc_draw_input_field('specials_expires_date', null, 'class="input-unstyled datepicker"'); ?>'+
                   '        </span>'+
                   '      </p>'+
                   '    </form>'+
                   '  </div>'+
                   '</div>',
          title: '<?php echo $lC_Language->get('modal_heading_new_special'); ?>',
          width: 600,
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
                var bValid = $("#special").validate({
                  rules: {
                    products_id: { required: true },
                    specials_price: { required: true, number: true }
                  },
                  invalidHandler: function() {
                  }
                }).form();
                if (bValid) {
                  var nvp = $("#special").serialize();
                  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=saveEntry&BATCH'); ?>'
                  $.getJSON(jsonLink.replace('BATCH', nvp),
                    function (data) {
                      if (data.rpcStatus == -10) { // no session
                        var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";
                        $(location).attr('href',url);
                      }
                      if (data.rpcStatus != 1) {
                        if (data.rpcStatus == -1) {
                          $.modal.alert('<?php echo $lC_Language->get('error_specials_price'); ?>');
                        } else if (data.rpcStatus == -2) {
                          $.modal.alert('<?php echo $lC_Language->get('error_specials_date'); ?>');
                        } else {
                          $.modal.alert('<?php echo $lC_Language->get('ms_error_action_not_performed'); ?>');
                        }
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
      $("#products_id").empty();
      i = 0;
      $.each(data.specialsArray, function(key, val) {
        if(i == 0) {
          $("#products_id").closest("span + *").prevAll("span.select-value:first").text(val.text);
          i++;
        }
        $("#products_id").append(
          $("<option></option>").val(val.id).html(val.text)
        );
      });
      getTaxClass();
      updateGross("specials_price", false);
      $('.datepicker').glDatePicker({ zIndex: 100 });
    }
  );
}
</script>
