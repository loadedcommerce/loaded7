<?php
  /*
  $Id: login.js.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
?>
<script>
$(document).ready(function() {
  $('body').removeClass('clearfix with-menu with-shortcuts');
  $('html').addClass('linen');
  
  // added for api communication health check
  apiHealthCheck();
});

function apiHealthCheck() {
  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', 'login' . '&action=apiHealthCheck'); ?>';
  $.getJSON(jsonLink,
    function (data) {
      return true;
    }
  );
}
</script>