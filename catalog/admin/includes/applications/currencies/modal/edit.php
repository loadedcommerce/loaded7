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
<script>
function editCurrency(id) {
  var defaultCode = '<?php echo DEFAULT_CURRENCY; ?>';
  var accessLevel = '<?php echo $_SESSION['admin']['access'][$lC_Template->getModule()]; ?>';
  if (parseInt(accessLevel) < 3) {
    $.modal.alert('<?php echo $lC_Language->get('ms_error_no_access');?>');
    return false;
  }
  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=getFormData&cid=CID&edit=true'); ?>'
  $.getJSON(jsonLink.replace('CID', parseInt(id)),
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
          content: '<div id="editCurrency">'+
                   '  <div id="editCurrencyForm">'+
                   '    <form name="cEdit" id="cEdit" autocomplete="off" action="" method="post">'+
                   '      <p><?php echo $lC_Language->get('introduction_new_currency'); ?></p>'+
                   '      <p class="button-height inline-label">'+
                   '        <label for="title" class="label"><?php echo $lC_Language->get('field_title'); ?></label>'+
                   '        <?php echo lc_draw_input_field('title', null, 'class="input full-width" id="editTitle"'); ?>'+
                   '      </p>'+
                   '      <p class="button-height inline-label">'+
                   '        <label for="code" class="label"><?php echo $lC_Language->get('field_code'); ?></label>'+
                   '        <?php echo lc_draw_input_field('code', null, 'class="input full-width" id="editCode"'); ?>'+
                   '      </p>'+
                   '      <p class="button-height inline-label">'+
                   '        <label for="symbol_left" class="label"><?php echo $lC_Language->get('field_symbol_left'); ?></label>'+
                   '        <?php echo lc_draw_input_field('symbol_left', null, 'class="input full-width" id="editSymbolLeft"'); ?>'+
                   '      </p>'+
                   '      <p class="button-height inline-label">'+
                   '        <label for="symbol_right" class="label"><?php echo $lC_Language->get('field_symbol_right'); ?></label>'+
                   '        <?php echo lc_draw_input_field('symbol_right', null, 'class="input full-width" id="editSymbolRight"'); ?>'+
                   '      </p>'+
                   '      <p class="button-height inline-label">'+
                   '        <label for="decimal_places" class="label"><?php echo $lC_Language->get('field_decimal_places'); ?></label>'+
                   '        <?php echo lc_draw_input_field('decimal_places', null, 'class="input full-width" id="editDecimalPlaces"'); ?>'+
                   '      </p>'+
                   '      <p class="button-height inline-label">'+
                   '        <label for="value" class="label"><?php echo $lC_Language->get('field_currency_value'); ?></label>'+
                   '        <?php echo lc_draw_input_field('value', null, 'class="input full-width" id="editValue"'); ?>'+
                   '      </p>'+
                   '      <p class="button-height inline-label" id="editCurrencyDefault"></p>'+
                   '    </form>'+
                   '  </div>'+
                   '</div>',
          title: '<?php echo $lC_Language->get('modal_heading_edit_currency'); ?>',
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
                var bValid = $("#cEdit").validate({
                rules: {
                  title: { required: true },
                  code: { required: true, minlength: 3, maxlength: 3 },
                  decimal_places: { required: true, digits: true },
                  currency_value: { required: true, number: true }
                },
                invalidHandler: function() {
                }
              }).form();
              if (bValid) {
                  var nvp = $("#cEdit").serialize();
                  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=saveCurrency&cid=CID&BATCH'); ?>'
                  $.getJSON(jsonLink.replace('CID', parseInt(id)).replace('BATCH', nvp),
                    function (rdata) {
                      if (rdata.rpcStatus == -10) { // no session
                        var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";
                        $(location).attr('href',url);
                      }
                      if (rdata.rpcStatus != 1) {
                        $.modal.alert('<?php echo $lC_Language->get('ms_error_action_not_performed'); ?>');
                        return false;
                      }
                      if ( data.currencyData.code != defaultCode && ($('.switch').is('.checked')) ) {
                        // because default is a constant, we need to refresh the page to pick up the value if checked
                        window.location.href = window.location.href;
                      } else {
                        oTable.fnReloadAjax();
                      }                    }
                  );
                  win.closeModal();
                }
              }
            }
          },
          buttonsLowPadding: true
      });
      $("#editTitle").val(data.currencyData.title);
      $("#editCode").val(data.currencyData.code);
      $("#editSymbolLeft").val(data.currencyData.symbol_left);
      $("#editSymbolRight").val(data.currencyData.symbol_right);
      $("#editDecimalPlaces").val(data.currencyData.decimal_places);
      $("#editValue").val(data.currencyData.value);
      if ( data.currencyData.code != defaultCode ) {
        $("#editCurrencyDefault").html('<label for="default" class="label"><?php echo $lC_Language->get('field_set_default'); ?></label><?php echo lc_draw_checkbox_field('default', null, null, 'class="switch medium" data-text-on="' . strtoupper($lC_Language->get('button_yes')) . '" data-text-off="' . strtoupper($lC_Language->get('button_no')) . '"'); ?>');
      }
    }
  );
}
</script>