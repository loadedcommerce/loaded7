<?php
/**
*  $Id: index.js v1.0 2011-11-04  datazen $
*
*  LoadedCommerce, Innovative eCommerce Solutions
*  http://www.loadedcommerce.com
*
*  Copyright (c) 2013 Loaded Commerce, LLC
*
*  @author     LoadedCommerce Team
*  @copyright  (c) 2013 LoadedCommerce Team
*  @license    http://loadedcommerce.com/license.html
*/ 
?>
<script>
//change the holding values
function __jquery_placeholder_goTitling() {
  $("input[type=text],textarea").each(function(){
    if (($(this).attr("holder") != "") && ($.trim($(this).val()) == "")) {
      $(this).val($(this).attr("holder"));
      $(this).addClass("holder");
    }
  });
}
</script>