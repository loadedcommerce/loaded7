<?php
/**
  @package    catalog::templates::content
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on DevKit http://www.bootstraptor.com under GPL license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: cart.js.php v1.0 2013-08-08 datazen $
*/
global $lC_Language; 
?>
<script>
function deleteItem(id) {
  var jsonLink = '<?php echo lc_href_link('rpc.php', 'checkout&action=deleteItem&item=ITEM', 'AUTO'); ?>';   
  $.getJSON(jsonLink.replace('ITEM', id).split('amp;').join(''),
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
    }
  );
}
</script>