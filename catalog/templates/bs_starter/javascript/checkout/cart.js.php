<?php
/**
  @package    catalog::templates::javascript
  @author     Loaded Commerce, LLC
  @copyright  Copyright 2003-2013 Loaded Commerce Development Team
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on DevKit http://www.bootstraptor.com under GPL license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: cart.js.php v1.0 2013-08-08 datazen $
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