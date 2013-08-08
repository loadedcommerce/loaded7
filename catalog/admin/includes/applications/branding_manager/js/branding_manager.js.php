<?php
/*
  $Id: branding_manager.js.php v1.0 2013-01-01 datazen $

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
  $(window).resize(function() {
    if ($(window).width() < 1380) {
      $("#branding_manager_tabs").removeClass("side-tabs");
      $("#branding_manager_tabs").addClass("standard-tabs");
    } if ($(window).width() >= 1380) {
      $("#branding_manager_tabs").removeClass("standard-tabs");
      $("#branding_manager_tabs").addClass("side-tabs");
    }
  });
});
</script>