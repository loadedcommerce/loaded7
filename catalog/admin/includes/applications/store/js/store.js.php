<?php
/*
  $Id: store.js.php v1.0 2013-01-01 datazen $

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
  // tweak template depending on view
  if ($.template.mediaQuery.isSmallerThan('desktop')) { 
    $('#headerRightContainer').css('width', '100%');    
    $('#storeSearchContainer').css('width', '100%');    
    $('#filterContainer').css('width', '100%');    
  }
});
</script>