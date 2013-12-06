<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: new.php v1.0 2013-08-08 datazen $
*/
?>
<style>
#newCurrency { padding-bottom:20px; }
</style>
<script>
function newCurrency() {
  var accessLevel = '<?php echo $_SESSION['admin']['access'][$lC_Template->getModule()]; ?>';
  if (parseInt(accessLevel) < 2) {
    $.modal.alert('<?php echo $lC_Language->get('ms_error_no_access');?>');
    return false;
  }
  $.modal({
      content: '<div id="newCurrency">'+
               '  <div id="newCurrencyForm">'+
               '    <form name="cNew" id="cNew" autocomplete="off" action="" method="post">'+
               '      <p><?php echo $lC_Language->get('introduction_new_currency'); ?></p>'+
               '      <p class="button-height inline-label">'+
               '        <label for="title" class="label"><?php echo $lC_Language->get('field_title'); ?></label>'+
               '        <?php echo lc_draw_input_field('title', null, 'class="input full-width"'); ?>'+
               '      </p>'+
               '      <p class="button-height inline-label">'+
               '        <label for="code" class="label"><?php echo $lC_Language->get('field_code'); ?></label>'+
               '        <?php echo lc_draw_input_field('code', null, 'class="input full-width"'); ?>'+
               '      </p>'+
               '      <p class="button-height inline-label">'+
               '        <label for="symbol_left" class="label"><?php echo $lC_Language->get('field_symbol_left'); ?></label>'+
               '        <?php echo lc_draw_input_field('symbol_left', null, 'class="input full-width"'); ?>'+
               '      </p>'+
               '      <p class="button-height inline-label">'+
               '        <label for="symbol_right" class="label"><?php echo $lC_Language->get('field_symbol_right'); ?></label>'+
               '        <?php echo lc_draw_input_field('symbol_right', null, 'class="input full-width"'); ?>'+
               '      </p>'+
               '      <p class="button-height inline-label">'+
               '        <label for="decimal_places" class="label"><?php echo $lC_Language->get('field_decimal_places'); ?></label>'+
               '        <?php echo lc_draw_input_field('decimal_places', null, 'class="input full-width"'); ?>'+
               '      </p>'+
               '      <p class="button-height inline-label">'+
               '        <label for="value" class="label"><?php echo $lC_Language->get('field_currency_value'); ?></label>'+
               '        <?php echo lc_draw_input_field('value', null, 'class="input full-width"'); ?>'+
               '      </p>'+
               '      <p class="button-height inline-label">'+
               '        <label for="default" class="label"><?php echo $lC_Language->get('field_set_default'); ?></label>'+
               '        <?php echo lc_draw_checkbox_field('default', null, null, 'class="switch medium" data-text-on="' . strtoupper($lC_Language->get('button_yes')) . '" data-text-off="' . strtoupper($lC_Language->get('button_no')) . '"'); ?>'+
               '      </p>'+
               '    </form>'+
               '  </div>'+
               '</div>',
      title: '<?php echo $lC_Language->get('modal_heading_new_currency'); ?>',
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

          var bValid = $("#cNew").validate({
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
              var nvp = $("#cNew").serialize();
              var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=saveCurrency&BATCH'); ?>'
              $.getJSON(jsonLink.replace('BATCH', nvp),
                function (rdata) {
                  if (rdata.rpcStatus == -10) { // no session
                    var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";
                    $(location).attr('href',url);
                  }
                  if (rdata.rpcStatus != 1) {
                    $.modal.alert('<?php echo $lC_Language->get('ms_error_action_not_performed'); ?>');
                    return false;
                  }
                  if ($('.switch').is('.checked')) {
                    // because default is a constant, we need to refresh the page to pick up the value if checked
                    window.location.href = window.location.href;
                  } else {
                    oTable.fnReloadAjax();
                  }
                }
              );
              win.closeModal();
            }
          }
        }
      },
      buttonsLowPadding: true
  });
}
</script>
