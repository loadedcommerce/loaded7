<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: responsive.js.php v1.0 2013-08-08 datazen $
*/
?>
<script>
// responsive functions
$(document).on('init-queries', function() {
  if ($.template.mediaQuery.isSmallerThan('mobile-landscape')) {  // mobile-portrait
    $('.hide-on-mobile-portrait').hide();
    $('#uninstallButton').empty();
  }       
}).on('enter-query-mobile-landscape', function() {
    $('.hide-on-mobile-portrait').show(); 
    $('#uninstallButton').html('Uninstall');
}).on('quit-query-mobile-landscape', function() {
    $('#uninstallButton').empty();
});
</script>