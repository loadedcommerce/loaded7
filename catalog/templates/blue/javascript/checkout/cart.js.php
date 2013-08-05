<?php
/*
  $Id: cart.js.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
 
  @function The lC_Default class manages default template functions
*/ 
global $lC_Language; 
?>
<script>
function deleteItem(id) {
  var jsonLink = '<?php echo lc_href_link('rpc.php', 'action=deleteItem&item=ITEM', 'AUTO'); ?>';   
  $.getJSON(jsonLink.replace('ITEM', id).replace('&amp;', '&'),
    function (data) {
      if (data.rpcStatus != 1) {
        alert('<?php echo $lC_Language->get('ms_error_action_not_performed'); ?>');
        return false;
      }
      if (data.redirect == '1') {
        window.location = location.href;
      }
      $('#tr-' + id).remove();
      $('#totals-table tbody').html(data.otText);
      $('#mini-cart-container').html(data.mcText);
    }
  );
}
</script>