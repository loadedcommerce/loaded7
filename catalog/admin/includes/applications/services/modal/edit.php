<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: edit.php v1.0 2013-08-08 datazen $
*/
?>
<style>
#editModule { padding-bottom:20px; }
.fieldset.fields-list { background-size: 62%; }
.field-block { padding: 0 30px 0 230px; }
.field-block .label { margin: 6px 0 0 -180px; width: 230px; padding-right: 60px; }
</style>
<script>
function editModule(id) {
  var accessLevel = '<?php echo $_SESSION['admin']['access'][$lC_Template->getModule()]; ?>';
  if (parseInt(accessLevel) < 3) {
    $.modal.alert('<?php echo $lC_Language->get('ms_error_no_access');?>');
    return false;
  }
  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=getFormData&module=MODULE'); ?>';
  $.getJSON(jsonLink.replace('MODULE', id),
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
          content: '<p><?php echo $lC_Language->get('introduction_edit_service_module'); ?></p>'+
                   '<fieldset class="fieldset fields-list">'+
                   '  <form name="mEdit" id="mEdit" autocomplete="off" action="" method="post">'+                  
                   '    <div class="field-block relative" id="editModuleFormKeys">'+
                   '    </div>'+
                   '  </form>'+
                   '</fieldset>',                   
          title: '<?php echo $lC_Language->get('modal_heading_edit_service_module'); ?>',
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
                var nvp = $("#mEdit").serialize();
                var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=saveModule&module=MODULE&BATCH'); ?>'
                $.getJSON(jsonLink.replace('MODULE', id).replace('BATCH', nvp),
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
          },
          buttonsLowPadding: true
      });
      $("#editModuleFormKeys").html(data.keys);  
      $.modal.all.centerModal();
    }
  );
}
</script>