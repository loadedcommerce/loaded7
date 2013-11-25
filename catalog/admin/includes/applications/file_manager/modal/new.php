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
#newFileForm { padding-bottom:20px; }
</style>
<script>
function newFile() {
  var accessLevel = '<?php echo $_SESSION['admin']['access'][$lC_Template->getModule()]; ?>';
  if (parseInt(accessLevel) < 2) {
    $.modal.alert('<?php echo $lC_Language->get('ms_error_no_access');?>');
    return false;
  }
  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=checkPerms&dir=' . $_SESSION['fm_directory']); ?>';  
  $.getJSON(jsonLink,        
    function (data) {
      if (data.rpcStatus == -10) { // no session
        var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";     
        $(location).attr('href',url);
      }
      $("#status-working").fadeOut('slow'); 
      if (data.rpcStatus != 1) {
        $.modal.alert('<?php echo sprintf($lC_Language->get('ms_error_directory_not_writable'), $id); ?>');
        return false;
      } 
      $.modal({
        content: '<div id="newFileForm">'+
                 '  <form name="addEntry" id="addEntry" action="" method="post">'+ 
                 '    <p><?php echo $lC_Language->get('introduction_new_file'); ?></p>'+ 
                 '    <p class="button-height inline-label">'+
                 '      <label for="fid" class="label" style="width:30%;"><?php echo $lC_Language->get('field_file_name'); ?></label>'+
                 '      <?php echo lc_draw_input_field('fid', null, 'class="input" style="width:90%;"'); ?>'+
                 '    </p>'+
                 '    <p class="button-height inline-label">'+
                 '      <label for="contents" class="label" style="width:30%;"><?php echo $lC_Language->get('field_file_contents'); ?></label>'+
                 '      <?php echo lc_draw_textarea_field('contents', null, 80, 20, 'class="input" style="width: 90%;"'); ?>'+
                 '    </p>'+                  
                 '  </form>'+ 
                 '  <p class="margin-top"><?php echo lc_output_string_protected($_SESSION['fm_directory']); ?></p>'+ 
                 '</div>',
        title: '<?php echo $lC_Language->get('modal_heading_new_file'); ?>',
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
              var bValid = $("#addEntry").validate({
                rules: {
                  fid: { required: true },
                  contents: { required: true },
                },
                invalidHandler: function() {
                }
              }).form();
              if (bValid) {              
                var nvp = $("#addEntry").serialize(); 
                var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=saveEntry&dir=' . $_SESSION['fm_directory'] . '&BATCH'); ?>'  
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
  );
}
</script>