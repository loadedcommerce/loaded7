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
  // extend the functionality to support more widths
  var winW = $(window).width();
  
  // tweak template depending on view
  if ($.template.mediaQuery.name === 'mobile-portrait') { 
    $('#storeHeaderRightContainer').css('width', '100%');    
    $('#storeSearchContainer').css('width', '100%').removeClass('no-margin-bottom').addClass('margin-top margin-bottom');    
    $('#storeFilterContainer').css('width', '100%');    
    $('#storeSearchContainerInput').removeClass('no-padding');
  } else if ($.template.mediaQuery.name === 'mobile-landscape') { 
    if (winW > 464) { // small tablet portrait 600x800
      $('#storeHeaderRightContainer').css('width', '100%');    
      $('#storeSearchContainer').css('width', '46%').css('float', 'right').addClass('margin-top');    
      $('#storeFilterContainer').css('width', '48%').css('float', 'left').addClass('margin-top');  
    } else { // mobile landscape
      $('#storeHeaderRightContainer').css('width', '100%');    
      $('#storeSearchContainer').css('width', '100%').removeClass('no-margin-bottom').addClass('margin-top margin-bottom');    
      $('#storeFilterContainer').css('width', '100%').css('margin-left', '80px');    
      $('#storeSearchContainerInput').removeClass('no-padding');    
    }
  } else if ($.template.mediaQuery.name === 'tablet-portrait') {  
      $('#storeHeaderRightContainer').css('width', '100%');    
      $('#storeSearchContainer').css('width', '46%').css('float', 'right').addClass('margin-top');    
      $('#storeFilterContainer').css('width', '48%').css('float', 'left').addClass('margin-top');    
  } else if ($.template.mediaQuery.name === 'tablet-landscape') { 
      $('#storeHeaderRightContainer').css('width', '100%');    
      $('#storeSearchContainer').css('width', '46%').css('float', 'right').addClass('margin-top');    
      $('#storeFilterContainer').css('width', '48%').css('float', 'left').addClass('margin-top');     
  } else { // desktop
  }

});

</script>