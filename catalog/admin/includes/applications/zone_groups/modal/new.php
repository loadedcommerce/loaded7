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
#newGroup { padding-bottom:20px; }
</style>
<script>
function newGroup() {
  var accessLevel = '<?php echo $_SESSION['admin']['access'][$lC_Template->getModule()]; ?>';
  if (parseInt(accessLevel) < 2) {
    $.modal.alert('<?php echo $lC_Language->get('ms_error_no_access');?>');
    return false;
  }
  $.modal({
      content: '<div id="newGroup">'+
               '  <div id="newGroupForm">'+
               '    <form name="zNew" id="zNew" autocomplete="off" action="" method="post">'+
               '      <p><?php echo $lC_Language->get('introduction_new_zone_group'); ?></p>'+
               '      <p class="button-height inline-label">'+
               '        <label for="zone_name" class="label"><?php echo $lC_Language->get('field_name'); ?></label>'+
               '        <?php echo lc_draw_input_field('zone_name', null, 'class="input full-width"'); ?>'+
               '      </p>'+
               '      <p class="button-height inline-label">'+
               '        <label for="zone_description" class="label"><?php echo $lC_Language->get('field_description'); ?></label>'+
               '        <?php echo lc_draw_input_field('zone_description', null, 'class="input full-width"'); ?>'+
               '      </p>'+
               '    </form>'+
               '  </div>'+
               '</div>',
      title: '<?php echo $lC_Language->get('modal_heading_new_zone_group'); ?>',
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
            var bValid = $("#zNew").validate({
              rules: {
                zone_name: { required: true },
                zone_description: { required: true }
              },
              invalidHandler: function() {
              }
            }).form();
            if (bValid) {
              var nvp = $("#zNew").serialize();
              var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=saveGroup&BATCH'); ?>'
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
