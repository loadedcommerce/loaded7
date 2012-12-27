<?php
/*
  $Id: uninstall.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/  
?>   
<script type="text/javascript"> 
function uninstallModule(id, name) { 
  $("#uninstallModuleForm").dialog({bgiframe: true, resizable: false, height:160, width:400, modal: true, overlay: { backgroundColor: '#000', opacity: 0.5 },
    buttons: {  
      '<?php echo $lC_Language->get('button_uninstall'); ?>': function() { 
        $("#status-working").fadeIn('fast');  
        var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=uninstallModule&module=MODULE'); ?>'  
        $.getJSON(jsonLink.replace('MODULE', id),        
          function (rdata) {
            if (rdata.rpcStatus == -10) { // no session
              var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";     
              $(location).attr('href',url);
            }                    
            $("#status-working").fadeOut('slow');
            if (rdata.rpcStatus != 1) {
              alert('<?php echo $lC_Language->get('ms_error_action_not_performed'); ?>');
              return false;
            }  
            oTable.fnReloadAjax();                
          }              
        );
        $("#uninstallModuleForm").dialog('destroy');  
      },        
      '<?php echo $lC_Language->get('button_cancel'); ?>': function() { 
        $("#uninstallModuleForm").dialog('destroy');
      }
    }
  }); 
  $("#uninstallModuleForm").dialog('open'); 
  $("#uninstallModuleFormMessage").html('<span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span><?php echo $lC_Language->get('introduction_uninstall_service_module'); ?><p><b>' + urldecode(name) + '</b></p>');
}              
</script>
<div id="uninstallModule">
  <div id="uninstallModuleForm" style="display:none;" title="<?php echo $lC_Language->get('modal_heading_uninstall_service_module'); ?>">
    <p id="uninstallModuleFormMessage"></p> 
  </div>
</div>