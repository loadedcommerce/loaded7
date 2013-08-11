<?php
/**
  @package    catalog::templates::boxes
  @author     Loaded Commerce, LLC
  @copyright  Copyright 2003-2013 Loaded Commerce Development Team
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on DevKit http://www.bootstraptor.com under GPL license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: shopping_cart.php v1.0 2013-08-08 datazen $
*/
?>
<!--modules/boxes/shopping_cart.php start-->
<div class="well" >
  <ul id="box-shopping-cart" class="nav nav-list">
    <li class="nav-header"><?php echo $lC_Box->getTitle(); ?></li>
    <?php echo $lC_Box->getContent(); ?>
  </ul>
</div>
<script>
$(document).ready(function() {
  $("#box-shopping-cart li").each(function(){
    if ($(this).attr('class') != 'nav-header') $(this).addClass('margin-left-li');
    $(this).find('a').attr('style', 'display:inline;');
  });  
   $("#box-shopping-cart li:last-child").addClass('margin-top align-right');
});
</script>
<!--modules/boxes/shopping_cart.php end-->