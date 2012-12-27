<?php
/*
  $Id: responsive.js.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
?>
<script>
// responsive functions
$(document).on('init-queries', function() {
  if ($.template.mediaQuery.isSmallerThan('mobile-landscape')) {  // mobile-portrait
    $('.hide-on-mobile-portrait').hide();
  }       
}).on('enter-query-mobile-landscape', function() {
    $('.hide-on-mobile-portrait').show(); 
}).on('quit-query-mobile-landscape', function() {
    $('.hide-on-mobile-portrait').hide(); 
});
</script>