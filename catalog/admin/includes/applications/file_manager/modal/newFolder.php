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
#newFileForm { padding-bottom:20px; }
</style>
<script>
function newFolder() {
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
        content: '<div id="newFolderForm">'+
                 '  <form name="addFolder" id="addFolder" action="" method="post">'+ 
                 '    <p><?php echo $lC_Language->get('introduction_new_directory'); ?></p>'+ 
                 '    <p class="button-height inline-label">'+
                 '      <label for="fid" class="label" style="width:30%;"><?php echo $lC_Language->get('field_directory_name'); ?></label>'+
                 '      <?php echo lc_draw_input_field('fid', null, 'class="input" style="width:90%;"'); ?>'+
                 '    </p>'+                
                 '  </form>'+ 
                 '  <p class="margin-top"><?php echo lc_output_string_protected($_SESSION['fm_directory']); ?></p>'+ 
                 '</div>',
        title: '<?php echo $lC_Language->get('modal_heading_new_folder'); ?>',
        width: 600,
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
              var bValid = $("#addFolder").validate({
                rules: {
                  fid: { required: true },
                },
                invalidHandler: function() {
                }
              }).form();
              if (bValid) {              
                var nvp = $("#addFolder").serialize(); 
                var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=addFolder&dir=' . $_SESSION['fm_directory'] . '&BATCH'); ?>'  
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