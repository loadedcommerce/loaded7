<?php
/*
  $Id: install.php v1.0 2011-11-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/  
?>   
<script type="text/javascript"> 
function installModule(id) { 
  $("#status-working").fadeIn('fast');  
  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=installModule&module=MODULE'); ?>'  
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
}              
</script>