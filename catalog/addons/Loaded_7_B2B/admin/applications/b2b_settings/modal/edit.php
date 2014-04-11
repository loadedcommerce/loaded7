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
global $lC_Language, $lC_Template;
?>
<style>
#editCustomerAccessGroup { padding-bottom:20px; }
</style>
<script>
function editCustomerAccessGroup(id) {
  var accessLevel = '<?php echo $_SESSION['admin']['access']['product_settings']; ?>';
  if (parseInt(accessLevel) < 2) {
    $.modal.alert('<?php echo $lC_Language->get('ms_error_no_access');?>');
    return false;
  }
  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=getCustomerAccessFormData&aid=AID&addon=Loaded_7_B2B'); ?>'
  $.getJSON(jsonLink.replace('AID', parseInt(id)),
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
          content: '<div id="editCustomerAccessGroup">'+
                   '  <div id="editCustomerAccessGroupForm">'+
                   '    <p><?php echo $lC_Language->get('introduction_edit_customer_access_level'); ?></p>'+
                   '    <fieldset class="fieldset fields-list">'+
                   '    <form name="accessEdit" id="accessEdit" method="post">'+
                   '      <p class="field-block button-height">'+
                   '        <label for="level" class="label anthracite"><?php echo $lC_Language->get('label_new_access_level'); ?></label>'+
                   '        <?php echo lc_draw_input_field('level', null, 'id="editLevel" class="input full-width"'); ?>'+
                   '      </p>'+ 
                   '      <p class="field-block button-height">'+
                   '        <label for="name" class="label anthracite"><?php echo $lC_Language->get('label_active'); ?></label>'+
                   '         <?php echo "&nbsp;" . lc_draw_checkbox_field('status', null, null, 'class="switch medium" data-text-on="' . strtoupper($lC_Language->get('button_yes')) . '" data-text-off="' . strtoupper($lC_Language->get('button_no')) . '"');?>' +
                   '    </form>'+
                   '    </fieldset>'+
                   '  </div>'+
                   '</div>',
          title: '<?php echo $lC_Language->get('modal_heading_edit_customer_access_level'); ?>',
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
                var bValid = $("#accessEdit").validate({
                  rules: {
                    level: { required: true }
                  },
                  invalidHandler: function() {
                  }
                }).form();
                if (bValid) {
                  var nvp = $("#accessEdit").serialize();
                  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=updateCustomerAccessLevels&addon=Loaded_7_B2B&aid=AID&BATCH'); ?>';
                  $.getJSON(jsonLink.replace('AID', parseInt(id)).replace('BATCH', nvp),
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
      $("#editLevel").val(data.level);
      if (data.status == 1) { 
        $("#status").attr('checked', 'checked').change();
      } else {
        $("#status").removeAttr('checked').change();
      }
    }
  );
}
</script>