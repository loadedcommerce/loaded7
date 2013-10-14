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
#editEntryForm { padding-bottom:20px; }
</style>
<script>
function editEntry(id, name) {
  var accessLevel = '<?php echo $_SESSION['admin']['access'][$lC_Template->getModule()]; ?>';
  if (parseInt(accessLevel) < 2) {
    $.modal.alert('<?php echo $lC_Language->get('ms_error_no_access');?>');
    return false;
  }
  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=getEntry&fid=FID'); ?>';  
  $.getJSON(jsonLink.replace('FID', id),        
    function (data) {
      if (data.rpcStatus == -10) { // no session
        var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";     
        $(location).attr('href',url);
      }
      $("#status-working").fadeOut('slow'); 
      if (data.rpcStatus != 1) {
        $.modal.alert('<?php echo sprintf($lC_Language->get('ms_error_file_not_writable'), $id); ?>');
        return false;
      }  
      $.modal({
        content: '<div id="editEntryForm">'+
                 '  <form name="editEntry" id="editEntry" action="" method="post">'+ 
                 '    <p><?php echo $lC_Language->get('introduction_new_file'); ?></p>'+ 
                 '    <p class="button-height inline-label">'+
                 '      <label for="fid" class="label" style="width:30%;"><?php echo $lC_Language->get('field_file_name'); ?></label>'+
                 '      <span id="editEntryName"></span>'+
                 '    </p>'+
                 '    <p class="button-height inline-label">'+
                 '      <label for="contents" class="label" style="width:30%;"><?php echo $lC_Language->get('field_file_contents'); ?></label>'+
                 '      <?php echo lc_draw_textarea_field('contents', null, 80, 20, 'id="contentsEdit" class="input" style="width: 90%;"'); ?>'+
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
              var bValid = $("#editEntry").validate({
                rules: {
                  fid: { required: true },
                  contents: { required: true },
                },
                invalidHandler: function() {
                }
              }).form();
              if (bValid) {              
                var nvp = $("#editEntry").serialize(); 
                var link = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=saveEntry&fid=FID&dir=DIR&BATCH'); ?>'  
                var jsonLink = link.replace('FID', id).replace('DIR', data.target);
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
      $("#editEntryName").html(id);
      $("#contentsEdit").val(data.contents);      
    }
  );
}
</script>