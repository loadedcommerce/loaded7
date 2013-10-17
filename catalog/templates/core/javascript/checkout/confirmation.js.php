<?php
/**
  @package    catalog::templates::content
  @author     Loaded Commerce, LLC
  @copyright  Copyright 2003-2013 Loaded Commerce Development Team
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on DevKit http://www.bootstraptor.com under GPL license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: confirmation.js.php v1.0 2013-08-08 datazen $
*/
global $lC_Language; 
?>
<script>
function sendOrderCommentsToSession(c) {
  if (c != '') {
    var jsonLink = '<?php echo lc_href_link('rpc.php', 'checkout&action=sendOrderCommentsToSession&comments=COMMENTS', 'AUTO'); ?>';   
    $.getJSON(jsonLink.replace('COMMENTS', c).split('amp;').join(''),
      function (data) {
        if (data.rpcStatus != 1) {
          alert('<?php echo $lC_Language->get('ms_error_action_not_performed'); ?>');
          return false;
        }
      }
    );
  }
}
</script>