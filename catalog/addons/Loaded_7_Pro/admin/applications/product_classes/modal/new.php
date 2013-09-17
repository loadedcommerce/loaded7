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
#newClass { padding-bottom:20px; }
</style>
<script>
function newClass() {
  var accessLevel = '<?php echo $_SESSION['admin']['access']['product_settings']; ?>';
  if (parseInt(accessLevel) < 2) {
    $.modal.alert('<?php echo $lC_Language->get('ms_error_no_access');?>');
    return false;
  }
  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=getFormData&addon=Loaded_7_Pro'); ?>'
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
          content: '<div id="newClass">'+
                   '  <div id="newClassForm">'+
                   '    <p><?php echo $lC_Language->get('introduction_new_class'); ?></p>'+
                   '    <fieldset class="fieldset fields-list">'+
                   '    <form name="pcNew" id="pcNew" autocomplete="off" action="" method="post">'+
                   '      <p class="field-block button-height">'+
                   '        <label for="name" class="label anthracite"><?php echo $lC_Language->get('field_name'); ?></label>'+
                   '        <span id="newClassNamesContainer"></span>'+
                   '      </p>'+
                   '      <p class="field-block button-height">'+
                   '        <label for="comment" class="label anthracite"><?php echo $lC_Language->get('field_comment'); ?></label>'+
                   '        <?php echo lc_draw_input_field('comment', null, 'class="input"'); ?>'+
                   '      </p>'+ 
                   '      <p class="field-block button-height">'+
                   '        <label for="status" class="label anthracite"><?php echo $lC_Language->get('field_status'); ?></label>'+
                   '         <?php echo '&nbsp;' . lc_draw_checkbox_field('status', null, null, 'class="switch medium checked" checked="checked" data-text-on="' . strtoupper($lC_Language->get('button_yes')) . '" data-text-off="' . strtoupper($lC_Language->get('button_no')) . '"');?>'+
                   '      </p>'+                                     
                   '      <p class="field-block button-height margin-bottom">'+
                   '        <label for="default" class="label anthracite"><?php echo $lC_Language->get('field_set_as_default'); ?></label>'+
                   '        <?php echo lc_draw_checkbox_field('default', null, null, 'class="switch medium" data-text-on="' . strtoupper($lC_Language->get('button_yes')) . '" data-text-off="' . strtoupper($lC_Language->get('button_no')) . '"'); ?>'+
                   '      </p>'+
                   '    </form>'+
                   '    </fieldset>'+
                   '  </div>'+
                   '</div>',
          title: '<?php echo $lC_Language->get('modal_heading_new_class'); ?>',
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
                var bValid = $("#pcNew").validate({
                  rules: {
                    'name[1]': { required: true }
                  },
                  invalidHandler: function() {
                  }
                }).form();
                if (bValid) {
                  var nvp = $("#pcNew").serialize();
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
      $("#newClassNamesContainer").html(data.names);
    }
  );
}
</script>