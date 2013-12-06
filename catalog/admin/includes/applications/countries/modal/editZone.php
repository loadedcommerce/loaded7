<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: editZone.php v1.0 2013-08-08 datazen $
*/
?>
<style>
#editZone { padding-bottom:20px; }
</style>
<script>
function editZone(id) {
  var accessLevel = '<?php echo $_SESSION['admin']['access'][$lC_Template->getModule()]; ?>';
  if (parseInt(accessLevel) < 3) {
    $.modal.alert('<?php echo $lC_Language->get('ms_error_no_access');?>');
    return false;
  }
  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=getZoneFormData&zid=ZID&edit=true'); ?>';
  $.getJSON(jsonLink.replace('ZID', parseInt(id)),
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
        content: '<div id="editZone">'+
                 '  <div id="editZoneForm">'+
                 '    <form name="zEdit" id="zEdit" autocomplete="off" action="" method="post">'+
                 '      <p><?php echo $lC_Language->get('introduction_edit_zone'); ?></p>'+
                 '      <p class="button-height inline-label">'+
                 '        <label for="zone_name" class="label"><?php echo $lC_Language->get('field_zone_name'); ?></label>'+
                 '        <?php echo lc_draw_input_field('zone_name', null, 'class="input full-width" id="editZoneName"'); ?>'+
                 '      </p>'+
                 '      <p class="button-height inline-label">'+
                 '        <label for="zone_code" class="label"><?php echo $lC_Language->get('field_zone_code'); ?></label>'+
                 '        <?php echo lc_draw_input_field('zone_code', null, 'class="input full-width" id="editZoneCode"'); ?>'+
                 '      </p>'+
                 '    </form>'+
                 '  </div>'+
                 '</div>',
          title: '<?php echo $lC_Language->get('modal_heading_edit_zone'); ?>',
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
                var bValid = $("#zEdit").validate({
                rules: {
                  zone_name: { required: true },
                  zone_code: { required: true }
                },
                invalidHandler: function() {
                }
              }).form();
              if (bValid) {
                  var nvp = $("#zEdit").serialize();
                  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=saveZone&zid=ZID&BATCH'); ?>'
                  $.getJSON(jsonLink.replace('ZID', parseInt(id)).replace('BATCH', nvp),
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
      $("#editZoneName").val(data.zData.zone_name);
      $("#editZoneCode").val(data.zData.zone_code);
    }
  );
}
</script>