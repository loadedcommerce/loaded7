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
#newCountry { padding-bottom:20px; }
</style>
<script>
function newCountry() {
  var accessLevel = '<?php echo $_SESSION['admin']['access'][$lC_Template->getModule()]; ?>';
  if (parseInt(accessLevel) < 2) {
    $.modal.alert('<?php echo $lC_Language->get('ms_error_no_access');?>');
    return false;
  }
  $.modal({
      content: '<div id="newCountry">'+
               '  <div id="newCountryForm">'+
               '    <form name="cNew" id="cNew" autocomplete="off" action="" method="post">'+
               '      <p><?php echo $lC_Language->get('introduction_new_country'); ?></p>'+
               '      <p class="button-height inline-label">'+
               '        <label for="countries_name" class="label"><?php echo $lC_Language->get('field_name'); ?></label>'+
               '        <?php echo lc_draw_input_field('countries_name', null, 'class="input full-width"'); ?>'+
               '      </p>'+
               '      <p class="button-height inline-label">'+
               '        <label for="countries_iso_code_2" class="label"><?php echo $lC_Language->get('field_iso_code_2'); ?></label>'+
               '        <?php echo lc_draw_input_field('countries_iso_code_2', null, 'class="input full-width"'); ?>'+
               '      </p>'+
               '      <p class="button-height inline-label">'+
               '        <label for="countries_iso_code_3" class="label"><?php echo $lC_Language->get('field_iso_code_3'); ?></label>'+
               '        <?php echo lc_draw_input_field('countries_iso_code_3', null, 'class="input full-width"'); ?>'+
               '      </p>'+
               '      <p class="button-height inline-label">'+
               '        <label for="address_format" class="label"><?php echo $lC_Language->get('field_address_format'); ?></label>'+
               '        <?php echo lc_draw_textarea_field('address_format', null, null, null, 'class="input full-width"'); ?>'+
               '      </p>'+
               '    </form>'+
               '  </div>'+
               '</div>',
      title: '<?php echo $lC_Language->get('modal_heading_new_country'); ?>',
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
              countries_name: { required: true },
              countries_iso_code_2: { required: true, rangelength: [2, 2] },
              countries_iso_code_3: { required: true, rangelength: [3, 3] }
            },
            invalidHandler: function() {
            }
          }).form();
          if (bValid) {
              var nvp = $("#cNew").serialize();
              var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=saveCountry&BATCH'); ?>'
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
