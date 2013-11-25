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
#newCard { padding-bottom:20px; }
</style>
<script>
function newCard() {
  var accessLevel = '<?php echo $_SESSION['admin']['access'][$lC_Template->getModule()]; ?>';
  if (parseInt(accessLevel) < 2) {
    $.modal.alert('<?php echo $lC_Language->get('ms_error_no_access');?>');
    return false;
  }
  $.modal({
      content: '<div id="newCard">'+
               '  <div id="newCardForm">'+
               '    <form name="ccNew" id="ccNew" autocomplete="off" action="" method="post">'+
               '      <p><?php echo $lC_Language->get('introduction_new_card'); ?></p>'+
               '      <p class="button-height inline-label">'+
               '        <label for="credit_card_name" class="label"><?php echo $lC_Language->get('field_name'); ?></label>'+
               '        <?php echo lc_draw_input_field('credit_card_name', null, 'class="input full-width"'); ?>'+
               '      </p>'+
               '      <p class="button-height inline-label">'+
               '        <label for="pattern" class="label"><?php echo $lC_Language->get('field_pattern'); ?></label>'+
               '        <?php echo lc_draw_input_field('pattern', null, 'class="input full-width"'); ?>'+
               '      </p>'+
               '      <p class="button-height inline-label">'+
               '        <label for="sort_order" class="label"><?php echo $lC_Language->get('field_sort_order'); ?></label>'+
               '        <?php echo lc_draw_input_field('sort_order', null, 'class="input full-width"'); ?>'+
               '      </p>'+
               '      <p class="button-height inline-label">'+
               '        <label for="credit_card_status" class="label"><?php echo $lC_Language->get('field_active'); ?></label>'+
               '        <?php echo lc_draw_checkbox_field( 'credit_card_status', '1', null, 'class="switch medium" data-text-on="' . strtoupper($lC_Language->get('button_yes')) . '" data-text-off="' . strtoupper($lC_Language->get('button_no')) . '"'); ?>'+
               '      </p>'+
               '    </form>'+
               '  </div>'+
               '</div>',
      title: '<?php echo $lC_Language->get('modal_heading_new_card'); ?>',
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

          var bValid = $("#ccNew").validate({
            rules: {
              credit_card_name: { required: true },
              pattern: { required: true }
            },
            invalidHandler: function() {
            }
          }).form();
          if (bValid) {
              var nvp = $("#ccNew").serialize();
              var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=saveCard&BATCH'); ?>'
              $.getJSON(jsonLink.replace('BATCH', nvp),
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
}
</script>
