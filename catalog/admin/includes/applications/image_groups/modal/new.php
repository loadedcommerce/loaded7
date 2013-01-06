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
<script>
function newGroup() {
  var accessLevel = '<?php echo $_SESSION['admin']['access']['definitions']; ?>';
  if (parseInt(accessLevel) < 2) {
    $.modal.alert('<?php echo $lC_Language->get('ms_error_no_access');?>');
    return false;
  }
  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=getFormData'); ?>'
  $.getJSON(jsonLink,
    function (data) {
      if (data.rpcStatus == -10) { // no session
        var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";
        $(location).attr('href',url);
      }
      $("#status-working").fadeOut('slow');
      if (data.rpcStatus != 1) {
        $.modal.alert('<?php echo $lC_Language->get('ms_error_action_not_performed'); ?>');
        return false;
      }
      $.modal({
          content: '<div id="newGroup">'+
                   '  <div id="newGroupForm">'+
                   '    <form name="gNew" id="gNew" autocomplete="off" action="" method="post">'+
                   '      <p><?php echo $lC_Language->get('introduction_new_image_group'); ?></p>'+
                   '      <p class="button-height inline-label">'+
                   '        <label for="title" class="label"><?php echo $lC_Language->get('field_title'); ?></label>'+
                   '        <span id="newGroupTitleContainer"></span>'+
                   '      </p>'+
                   '      <p class="button-height inline-label">'+
                   '        <label for="code" class="label"><?php echo $lC_Language->get('field_code'); ?></label>'+
                   '        <?php echo lc_draw_input_field('code', null, 'class="input full-width"'); ?>'+
                   '      </p>'+
                   '      <p class="button-height inline-label">'+
                   '        <label for="width" class="label"><?php echo $lC_Language->get('field_width'); ?></label>'+
                   '        <?php echo lc_draw_input_field('width', null, 'class="input full-width"'); ?>'+
                   '      </p>'+
                   '      <p class="button-height inline-label">'+
                   '        <label for="height" class="label"><?php echo $lC_Language->get('field_height'); ?></label>'+
                   '        <?php echo lc_draw_input_field('height', null, 'class="input full-width"'); ?>'+
                   '      </p>'+
                   '      <p class="button-height inline-label">'+
                   '        <label for="force_size" class="label"><?php echo $lC_Language->get('field_force_size'); ?></label>'+
                   '        <?php echo lc_draw_checkbox_field('force_size', null, null, 'class="switch medium" data-text-on="' . strtoupper($lC_Language->get('button_yes')) . '" data-text-off="' . strtoupper($lC_Language->get('button_no')) . '"'); ?>'+
                   '      </p>'+
                   '      <p class="button-height inline-label">'+
                   '        <label for="default" class="label"><?php echo $lC_Language->get('field_set_as_default'); ?></label>'+
                   '        <?php echo lc_draw_checkbox_field('default', null, null, 'class="switch medium" data-text-on="' . strtoupper($lC_Language->get('button_yes')) . '" data-text-off="' . strtoupper($lC_Language->get('button_no')) . '"'); ?>'+
                   '      </p>'+
                   '    </form>'+
                   '  </div>'+
                   '</div>',
          title: '<?php echo $lC_Language->get('modal_heading_new_image_group'); ?>',
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
                var bValid = $("#gNew").validate({
                  rules: {
                    'title[1]': { required: true },
                    code: { required: true },
                    width: { required: true, digits: true },
                    height: { digits: true }
                  },
                  invalidHandler: function() {
                  }
                }).form();
                if (bValid) {
                  var nvp = $("#gNew").serialize();
                  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=saveGroup&BATCH'); ?>'
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
      $("#newGroupTitleContainer").html(data.titleCode);
    }
  );
}
</script>