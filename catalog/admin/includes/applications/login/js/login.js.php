<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: login.js.php v1.0 2013-08-08 datazen $
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