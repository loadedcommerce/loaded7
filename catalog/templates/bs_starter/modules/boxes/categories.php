<?php
/**  
*  $Id: categories.php v1.0 2013-01-01 datazen $
*
*  LoadedCommerce, Innovative eCommerce Solutions
*  http://www.loadedcommerce.com
*
*  Copyright (c) 2013 Loaded Commerce, LLC
*
*  @author     Loaded Commerce Team
*  @copyright  (c) 2013 Loaded Commerce Team
*  @license    http://loadedcommerce.com/license.html
*/
?>
<!--modules/boxes/categories.php start-->
<div class="well" >
  <div class="nav-header"><?php echo $lC_Box->getTitle(); ?></div>
  <?php echo $lC_Box->getContent(); ?>
</div>
<script>
$(document).ready(function() {
  $('#ul-top-categories').attr('class', 'nav nav-list');
  // loop thru and set the active marker
  $('#ul-top-categories li').each(function() {
    
    var loc = document.location.href;
    cmp1 = loc.substring(loc.indexOf("?") + 1);
    cmp1 = cmp1.substring(cmp1.indexOf("&") + 1);
    
    var href = $(this).find('a').attr('href');
    cmp2 = href.substring(href.indexOf("?") + 1);
    cmp2 = cmp2.substring(cmp2.indexOf("&") + 1);

    if (cmp1 == cmp2) { 
      $(this).addClass('active');
    } else {
      $(this).removeClass('active');
    }
  });  
});
</script>
<!--modules/boxes/categories.php end-->