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
#newEntry { padding-bottom:20px; }
</style>
<script>
function newEntry() {
  var accessLevel = '<?php echo $_SESSION['admin']['access']['definitions']; ?>';
  if (parseInt(accessLevel) < 2) {
    $.modal.alert('<?php echo $lC_Language->get('ms_error_no_access');?>');
    return false;
  }
  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=getFormData'); ?>';
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
          content: '<div id="newEntry">'+
                   '  <div id="newEntryForm">'+
                   '    <form name="mNew" id="mNew" action="<?php echo lc_href_link_admin(FILENAME_DEFAULT, $lC_Template->getModule() . '&action=save'); ?>" method="post" enctype="multipart/form-data">'+
                   '      <p><?php echo $lC_Language->get('introduction_new_manufacturer'); ?></p>'+
                   '      <p class="button-height inline-label">'+
                   '        <label for="manufacturers_name" class="label" style="width:33%;"><?php echo $lC_Language->get('field_name'); ?></label>'+
                   '        <?php echo lc_draw_input_field('manufacturers_name', null, 'class="input" style="width:92%;"'); ?>'+
                   '      </p>'+
                   '      <p class="button-height inline-label">'+
                   '        <label for="manufacturers_image" class="label" style="width:33%;"><?php echo $lC_Language->get('field_image'); ?></label>'+
                   '        <?php echo lc_draw_file_field('manufacturers_image', true, 'class="file"'); ?>'+
                   '      </p>'+
                   '      <p class="button-height inline-label">'+
                   '        <label for="manufacturers_url" class="label" style="width:33%;"><?php echo $lC_Language->get('field_url'); ?></label>'+
                   '        <span id="fieldUrl"></span>'+
                   '      </p>'+
                   '    </form>'+
                   '  </div>'+
                   '</div>',
          title: '<?php echo $lC_Language->get('modal_heading_new_manufacturer'); ?>',
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
                var bValid = $("#mNew").validate({
                  rules: {
                    manufacturers_name: { required: true }
                  },
                  invalidHandler: function() {
                  }
                }).form();
                if (bValid) {
                  $("#mNew").submit();
                  win.closeModal();
                }
              }
            }
          },
          buttonsLowPadding: true
      });
      $("#fieldUrl").html(data.mfgUrl);
    }
  );
}
</script>