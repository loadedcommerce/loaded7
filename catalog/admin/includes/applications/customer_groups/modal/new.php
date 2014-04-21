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
global $lC_Addons;
?>
<style>
#newGroup { padding-bottom:20px; }
.legend { font-weight:bold; font-size: 1.0em; }
.fieldset.fields-list { background-image: none; }
</style>
<script>
function newGroup() {
  var accessLevel = '<?php echo $_SESSION['admin']['access']['definitions']; ?>';
  if (parseInt(accessLevel) < 2) {
    $.modal.alert('<?php echo $lC_Language->get('ms_error_no_access');?>');
    return false;
  }
  mask();
  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=getFormData'); ?>'
  $.getJSON(jsonLink,
    function (data) {
      unmask();
      if (data.rpcStatus == -10) { // no session
        var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";
        $(location).attr('href',url);
      }
      if (data.rpcStatus != 1) {
        $.modal.alert('<?php echo $lC_Language->get('ms_error_action_not_performed'); ?>');
        return false;
      }
      var extraFormHtml = ((data.extraFormHtml != undefined) ? data.extraFormHtml : '');
      $.modal({
          content: '<div id="newGroup">'+
                   '  <div id="newGroupForm">'+
                   '    <p><?php echo $lC_Language->get('introduction_new_customer_group'); ?></p>'+
                   '    <fieldset class="fieldset fields-list">'+
                   '    <form name="osNew" id="osNew" autocomplete="off" action="" method="post">'+
                   '      <div class="field-block button-height margin-bottom">'+
                   '        <label for="name" class="label anthracite"><?php echo $lC_Language->get('field_name'); ?></label>'+
                   '        <span id="newGroupNamesContainer"></span>'+
                   '      </div>'+
                   '      <div class="field-drop button-height black-inputs">'+
                   '        <label for="baseline" class="label" style="width:55%;"><?php echo $lC_Language->get('field_baseline_discount'); ?></label>'+
                   '        <div class="inputs" style="width:28%">'+
                   '          <span class="mid-margin-right float-right strong">%</span><?php echo lc_draw_input_field('baseline', '0.00', 'onfocus="this.select();" class="input-unstyled small-margin-left strong" style="width:50%;"'); ?>'+
                   '        </div>'+
                   '      </div>'+ extraFormHtml +
                   '    </form>'+
                   '    </fieldset>'+
                   '  </div>'+
                   '</div>',
          title: '<?php echo $lC_Language->get('modal_heading_new_customer_group'); ?>',
          width: 600,
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
                var bValid = $("#osNew").validate({
                  rules: {
                    'name[1]': { required: true }
                  },
                  invalidHandler: function() {
                  }
                }).form();
                if (bValid) {
                  mask();
                  var nvp = $("#osNew").serialize();
                  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=saveGroup&BATCH'); ?>'
                  $.getJSON(jsonLink.replace('BATCH', nvp),
                    function (data) {
                      unmask();
                      if (data.rpcStatus == -10) { // no session
                        var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";
                        $(location).attr('href',url);
                      }
                      if (data.rpcStatus != 1) {
                        $.modal.alert('<?php echo $lC_Language->get('ms_error_action_not_performed'); ?>');
                        return false;
                      }
                      if (nvp.indexOf("default=on") != -1) {
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
      $("#newGroupNamesContainer").html(data.names);
    }
  );
}
</script>