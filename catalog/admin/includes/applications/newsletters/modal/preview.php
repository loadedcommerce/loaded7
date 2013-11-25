<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: preview.php v1.0 2013-08-08 datazen $
*/
?> 
<script type="text/javascript"> 
function showPreview(id) {
  $("#status-working").fadeIn('fast');  
  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=getPreview&nid=NID'); ?>'  
  $.getJSON(jsonLink.replace('NID', parseInt(id)),     
    function (data) {
      if (data.rpcStatus == -10) { // no session
        var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";     
        $(location).attr('href',url);
      }    
      $("#status-working").fadeOut('slow');    
      $("#previewContent").modal({bgiframe: true, resizable: false, height:500, width:900, modal: true, overlay: { backgroundColor: '#000', opacity: 0.5 },
        buttons: {  
          '<?php echo $lC_Language->get('button_close'); ?>': function() {
            $("#previewContent").modal('close');
          }
        }
      });            
      $("#previewContent").modal('open');         
      $("#previewContentMessage").html('<table width="100%" border="0"><tr><td width="16" height="30"><?php echo lc_icon('file.png'); ?></td><td><b>' + data.title + '</b></td></tr><tr><td colspan="2">' + data.content + '</td></tr></table>');
    }
  );
}  
</script>  
<div id="preview">
  <div id="previewContent" style="display:none;" title="<?php echo $lC_Language->get('dialog_heading_preview_newsletter');?>">
    <p id="previewContentMessage"></p>
   </div>
</div>